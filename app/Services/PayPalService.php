<?php

namespace App\Services;

use PayPalCheckoutSdk\Core\PayPalHttpClient;
use PayPalCheckoutSdk\Core\SandboxEnvironment;
use PayPalCheckoutSdk\Core\ProductionEnvironment;
use PayPalCheckoutSdk\Orders\OrdersCreateRequest;
use PayPalCheckoutSdk\Orders\OrdersCaptureRequest;

class PayPalService
{
    private $client;

    public function __construct()
    {
        $environment = config('services.paypal.mode') === 'live'
            ? new ProductionEnvironment(config('services.paypal.client_id'), config('services.paypal.secret'))
            : new SandboxEnvironment(config('services.paypal.client_id'), config('services.paypal.secret'));

        $this->client = new PayPalHttpClient($environment);
    }

    public function createOrder($amount)
    {
        $request = new OrdersCreateRequest();
        $request->prefer('return=representation');
        $request->body = [
            'intent' => 'CAPTURE',
            'purchase_units' => [[
                'amount' => [
                    'currency_code' => 'USD',
                    'value' => number_format($amount, 2, '.', '')
                ]
            ]],
            'application_context' => [
                'cancel_url' => route('paypal.cancel'),
                'return_url' => route('paypal.success')
            ]
        ];

        try {
            $response = $this->client->execute($request);
            return $response->result->links[1]->href;  // Link para aprobar el pago
        } catch (\Exception $e) {
            return null;
        }
    }

    public function captureOrder($orderId)
    {
        $request = new OrdersCaptureRequest($orderId);
        $request->prefer('return=representation');

        try {
            $response = $this->client->execute($request);
            return $response->result;
        } catch (\Exception $e) {
            return null;
        }
    }
}
