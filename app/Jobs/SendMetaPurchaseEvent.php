<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class SendMetaPurchaseEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public $clientIp;
    public $userAgent;

    /**
     * Create a new job instance.
     */
    public function __construct(Order $order, ?string $clientIp, ?string $userAgent)
    {
        $this->order = $order;
        $this->clientIp = $clientIp;
        $this->userAgent = $userAgent;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $pixelId = config('services.meta.pixel_id');
        $token = config('services.meta.capi_token');

        if (empty($pixelId) || empty($token)) {
            return;
        }

        $names = explode(' ', trim($this->order->customer_name));
        $firstName = $names[0] ?? '';
        $lastName = count($names) > 1 ? end($names) : '';

        // Format and hash phone number: strip non-numeric, prepend country code if local
        $phone = preg_replace('/[^0-9]/', '', $this->order->customer_phone);
        if (str_starts_with($phone, '0')) {
            $phone = '213' . substr($phone, 1);
        }

        $userData = [
            'client_ip_address' => $this->clientIp,
            'client_user_agent' => $this->userAgent,
            'ph' => [hash('sha256', $phone)],
            'fn' => [hash('sha256', strtolower(trim($firstName)))],
        ];

        if ($lastName) {
            $userData['ln'] = [hash('sha256', strtolower(trim($lastName)))];
        }

        $payload = [
            'data' => [
                [
                    'event_name' => 'Purchase',
                    'event_time' => time(),
                    'action_source' => 'website',
                    'user_data' => $userData,
                    'custom_data' => [
                        'value' => $this->order->total,
                        'currency' => 'DZD',
                    ],
                ]
            ],
            'access_token' => $token
        ];

        $response = Http::post("https://graph.facebook.com/v19.0/{$pixelId}/events", $payload);

        if ($response->failed()) {
            Log::error('Meta CAPI Error', [
                'status' => $response->status(),
                'body' => $response->body(),
                'order_id' => $this->order->id
            ]);
        }
    }
}
