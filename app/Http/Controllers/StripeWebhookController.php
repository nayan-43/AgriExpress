<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Webhook;
use UnexpectedValueException;

class StripeWebhookController extends Controller
{
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

                if ($order) {
                    $order->markPaidFromStripeSession($session);
                } else {
                    Log::warning('Stripe webhook: no matching order for session', ['session_id' => $session->id, 'order_id' => $orderId]);
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
