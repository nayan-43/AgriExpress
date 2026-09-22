<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use UnexpectedValueException;

class StripeWebhookController extends Controller
{
    /**
     * Stripe calls this URL directly (never the browser), so this is the
     * only place that should actually mark an order paid / send the
     * confirmation email / decrement stock. The success() redirect in
     * PaymentController is just where the customer's browser lands —
     * it can be skipped entirely (closed tab, network blip) so never
     * fulfill an order from it.
     */
    public function handle(Request $request)
    {
        $payload = $request->getContent();
        $signature = $request->header('Stripe-Signature');
        $secret = config('services.stripe.webhook_secret');

        try {
            $event = Webhook::constructEvent($payload, $signature, $secret);
        } catch (UnexpectedValueException | SignatureVerificationException $e) {
            Log::warning('Stripe webhook signature/payload invalid', ['error' => $e->getMessage()]);
            return response('Invalid payload', 400);
        }

        switch ($event->type) {
            case 'checkout.session.completed':
                $session = $event->data->object;
                $orderId = $session->metadata->order_id ?? null;
                $order = $orderId ? Order::find($orderId) : null;

                if ($order && $session->payment_status === 'paid') {
                    $order->update(['payment_status' => Order::PAYMENT_PAID]);
                    Payment::where('order_id', $order->id)
                        ->where('transaction_id', $session->id)
                        ->update([
                            'status' => 'paid',
                            'paid_at' => now(),
                            'gateway_response' => json_encode($session),
                        ]);
                    $order->user?->cart?->items()->delete();
                }

                Log::info('Stripe checkout completed', [
                    'session_id' => $session->id,
                    'customer_email' => $session->customer_details->email ?? null,
                    'amount_total' => $session->amount_total,
                ]);
                break;

            case 'payment_intent.payment_failed':
                $intent = $event->data->object;
                Log::warning('Stripe payment failed', ['id' => $intent->id]);
                break;

            default:
                // Ignore other event types you haven't handled yet.
                break;
        }

        return response('Webhook handled', 200);
    }
}
