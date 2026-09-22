<?php

namespace App\Http\Controllers;

use App\Models\Address;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Order;
use App\Models\OrderAddress;
use App\Models\OrderItem;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Stripe\Checkout\Session as StripeSession;
use Stripe\Stripe;

class PaymentController extends Controller
{
    public function __construct()
    {
        Stripe::setApiKey(config('services.stripe.secret'));
    }

    /**
     * Build a Stripe Checkout Session from the current cart and
     * redirect the customer to Stripe's hosted payment page.
     */
    public function checkout(Request $request): RedirectResponse
    {
        $user = $request->user('web');
        $data = $request->validate([
            'payment_method' => ['required', 'in:stripe,cod'],
            // Billing — either a saved address (address_id) or the inline
            // fields below when the customer has no saved addresses yet.
            'address_id' => ['nullable', 'integer', 'exists:addresses,id'],
            'first_name' => ['required_without:address_id', 'nullable', 'string', 'max:255'],
            'last_name' => ['nullable', 'string', 'max:255'],
            'phone' => ['required_without:address_id', 'nullable', 'string', 'max:20'],
            'address_line_1' => ['required_without:address_id', 'nullable', 'string', 'max:255'],
            'address_line_2' => ['nullable', 'string', 'max:255'],
            'city' => ['required_without:address_id', 'nullable', 'string', 'max:255'],
            'state' => ['required_without:address_id', 'nullable', 'string', 'max:255'],
            'postal_code' => ['required_without:address_id', 'nullable', 'string', 'max:20'],
            'country' => ['required_without:address_id', 'nullable', 'string', 'max:255'],
            // Shipping — defaults to the billing address above unless the
            // customer unchecks "same as billing" and fills these in.
            'same_as_billing' => ['nullable', 'boolean'],
            'shipping_first_name' => ['required_unless:same_as_billing,1', 'nullable', 'string', 'max:255'],
            'shipping_last_name' => ['nullable', 'string', 'max:255'],
            'shipping_phone' => ['required_unless:same_as_billing,1', 'nullable', 'string', 'max:20'],
            'shipping_address_line_1' => ['required_unless:same_as_billing,1', 'nullable', 'string', 'max:255'],
            'shipping_address_line_2' => ['nullable', 'string', 'max:255'],
            'shipping_city' => ['required_unless:same_as_billing,1', 'nullable', 'string', 'max:255'],
            'shipping_state' => ['required_unless:same_as_billing,1', 'nullable', 'string', 'max:255'],
            'shipping_postal_code' => ['required_unless:same_as_billing,1', 'nullable', 'string', 'max:20'],
            'shipping_country' => ['required_unless:same_as_billing,1', 'nullable', 'string', 'max:255'],
        ]);

        $cart = Cart::with('items.product')->where('user_id', $user->id)->firstOrFail();
        abort_if($cart->items->isEmpty(), 422, 'Your cart is empty.');
        $billingAddress = !empty($data['address_id'])
            ? $user->addresses()->findOrFail($data['address_id'])
            : Address::create(array_merge(collect($data)->except('address_id')->all(), ['user_id' => $user->id, 'type' => 'billing']));
        $shipping = !empty($data['same_as_billing']) ? $billingAddress->only([
            'first_name',
            'last_name',
            'phone',
            'address_line_1',
            'address_line_2',
            'city',
            'state',
            'postal_code',
            'country',
        ]) : [
            'first_name' => $data['shipping_first_name'],
            'last_name' => $data['shipping_last_name'] ?? null,
            'phone' => $data['shipping_phone'],
            'address_line_1' => $data['shipping_address_line_1'],
            'address_line_2' => $data['shipping_address_line_2'] ?? null,
            'city' => $data['shipping_city'],
            'state' => $data['shipping_state'],
            'postal_code' => $data['shipping_postal_code'],
            'country' => $data['shipping_country'],
        ];

        $subtotal = (float) $cart->items->sum(fn($item) => $item->price * $item->quantity);
        $coupon = session('coupon_code') ? Coupon::where('code', session('coupon_code'))->first() : null;
        $discount = $coupon ? $this->couponDiscount($coupon, $subtotal) : 0;
        $tax = round(($subtotal - $discount) * 0.08, 2);
        $total = round($subtotal - $discount + $tax, 2);
        $order = DB::transaction(function () use ($user, $cart, $billingAddress, $shipping, $subtotal, $discount, $tax, $total, $data, $coupon) {
            $order = Order::create([
                'user_id' => $user->id,
                'order_number' => 'ORD-' . strtoupper(str()->random(10)),
                'order_status' => 1,
                'payment_status' => Order::PAYMENT_PENDING,
                'payment_mode' => $data['payment_method'],
                'subtotal' => $subtotal,
                'discount' => $discount,
                'eco_tax' => $tax,
                'shipping' => 0,
                'total_price' => $total,
                'coupon_code' => $coupon?->code,
                'placed_at' => now(),
            ]);

            foreach ($cart->items as $item) {
                $order->items()->create([
                    'product_id' => $item->product_id,
                    'variant_id' => $item->variant_id,
                    'product_name' => $item->product->name,
                    'sku' => $item->product->sku,
                    'image' => $item->product->main_image,
                    'quantity' => $item->quantity,
                    'unit_price' => $item->price,
                    'total_price' => $item->price * $item->quantity,
                ]);
            }

            $order->addresses()->create(array_merge($billingAddress->only([
                'first_name',
                'last_name',
                'phone',
                'address_line_1',
                'address_line_2',
                'city',
                'state',
                'postal_code',
                'country',
            ]), ['type' => 'billing']));
            $order->addresses()->create(array_merge($shipping, ['type' => 'shipping']));

            return $order;
        });

        if ($data['payment_method'] === 'cod') {
            Payment::create([
                'order_id' => $order->id,
                'payment_method' => 'cod',
                'amount' => $total,
                'status' => 'pending',
            ]);
            $cart->items()->delete();
            session()->forget('coupon_code');
            return redirect()->route('checkout.success', ['order' => $order->order_number])
                ->with('status', "Order {$order->order_number} placed successfully. Pay on delivery.");
        }

        $lineItems = $cart->items->map(function ($item) {
            return [
                'price_data' => [
                    'currency'     => 'usd',
                    'product_data' => ['name' => $item->product->name],
                    'unit_amount'  => (int) round((float) $item->price * 100),
                ],
                'quantity' => $item->quantity,
            ];
        })->all();

        $lineItems[] = [
            'price_data' => [
                'currency'     => 'usd',
                'product_data' => ['name' => 'Tax (8%)'],
                'unit_amount'  => (int) round($tax * 100),
            ],
            'quantity' => 1,
        ];

        // Create a one-off, single-use coupon for this session's discount.
        // For a real coupon system, create named Stripe Coupons/Promotion
        // Codes once (in the dashboard or via API) and reuse their IDs
        // instead of creating a new one on every checkout.
        $stripeCoupon = $discount > 0 ? \Stripe\Coupon::create([
            'amount_off' => (int) round($discount * 100),
            'currency'   => 'usd',
            'duration'   => 'once',
            'name'       => $coupon?->code ?? 'Discount',
        ]) : null;

        $session = StripeSession::create([
            'mode'                => 'payment',
            'payment_method_types' => ['card'],
            'line_items'          => $lineItems,
            ...($stripeCoupon ? ['discounts' => [['coupon' => $stripeCoupon->id]]] : []),
            'success_url'         => route('checkout.success', ['order' => $order->order_number]) . '&session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'          => route('checkout.cancel'),
            'customer_email'      => $request->user()?->email,
            'metadata'            => [
                'user_id' => $request->user()?->id,
                'order_id' => $order->id,
            ],
        ]);

        Payment::create([
            'order_id' => $order->id,
            'payment_method' => 'stripe',
            'transaction_id' => $session->id,
            'amount' => $total,
            'status' => 'pending',
        ]);

        return redirect($session->url);
    }

    protected function couponDiscount(Coupon $coupon, float $subtotal): float
    {
        $discount = $coupon->type === 'percentage' ? $subtotal * ((float) $coupon->value / 100) : (float) $coupon->value;
        if ($coupon->maximum_discount !== null) $discount = min($discount, (float) $coupon->maximum_discount);
        return min(round($discount, 2), round($subtotal, 2));
    }

    public function success(Request $request)
    {
        $sessionId = $request->query('session_id');
        $order = null;

        if ($sessionId) {
            $session = StripeSession::retrieve($sessionId);

            // The webhook (StripeWebhookController) is the authoritative
            // place this happens — it's called server-to-server by Stripe,
            // so it fires even if the customer closes the tab here. But a
            // webhook endpoint is easy to forget to register (locally, or
            // right after a fresh deploy), and when that happens
            // payment_status is left stuck on "Pending" forever even
            // though Stripe successfully took the payment. This fallback
            // closes that gap: if we already know the session is paid by
            // the time the browser lands here, mark it now too.
            // markPaidFromStripeSession() is idempotent, so this is safe
            // to run whether or not the webhook already handled it.
            $orderId = $session->metadata->order_id ?? null;
            $order = $orderId ? Order::find($orderId) : null;
            $order?->markPaidFromStripeSession($session);

            Log::info('Stripe checkout redirect success', ['id' => $sessionId, 'status' => $session->payment_status]);
        }

        // Both the Stripe redirect and the Cash on Delivery redirect carry
        // ?order=ORD-XXXX, so this covers whichever route got us here.
        // Scoped to the signed-in customer so an order number can't be
        // guessed to view someone else's order.
        if (!$order && $orderNumber = $request->query('order')) {
            $order = Order::where('order_number', $orderNumber)
                ->where('user_id', $request->user('web')?->id)
                ->first();
        }

        $order?->load(['items', 'billingAddress', 'shippingAddress']);

        return view('checkout-success', ['sessionId' => $sessionId, 'order' => $order]);
    }

    public function cancel(Request $request)
    {
        return view('checkout-cancel');
    }
}
