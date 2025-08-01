<?php

namespace Database\Seeders;

use App\Enum\MediaTypeEnum;
use App\Models\Media;
use App\Models\PaymentGateway;
use Illuminate\Database\Seeder;

class PaymentGatewaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        PaymentGateway::truncate();

        $gateways = [
            'paypal' => [
                'mode' => 'sandbox',
                'app_id' => 'APP-80W284485P519543T',
                'client_id' => 'AdIm__wXnoq3THz55M5mgEWWyNZVQHF1mNWtrJPz5togtRLfZbfExq7fcPyxzGGk9-9IC1d_lLZYRO2H',
                'client_secret' => 'EH-ZMtMC7Kn7RNxEHURHFAxud2Z2iyR20TQsAzUGrhPoeOlvb4HZj4UEQRvMTl8uMlnnnGy--Rvo5PKs',
            ],
            'stripe' => [
                'publishable_key' => 'pk_test_51OU2r2KdjtJ0K09AnX8tNZGPSpTzJjd9hbWZ6iOwRru2LMbRSpdZJ8XWic5CPl2ps5wnGx7Wspr1pNMsUL7JadKg00MnWTpw12',
                'secret_key' => 'sk_test_51OU2r2KdjtJ0K09AajdztowtylB7pAXRdqsi2aFHNXqO1HEaR7a33YUZiHAOFTT1zoXDP9cca33JYCY7Q8K0bUSW00fd1XVpj4',
            ],
            '2checkout' => [
                'merchant' => '254928155937',
                'currency' => 'USD',
            ],
            'aamarpay' => [
                'store_id' => 'aamarpaytest',
                'signature_key' => 'dbb74894e82415a2f7ff0ec3a97e4183',
                'currency' => 'BDT',
            ],
            'razorpay' => [
                'title' => 'Razorpay',
                'name' => 'razorpay',
                'key' => 'rzp_live_C7ayx7PaJJkARf',
                'secret' => '4BdgF5N5FitWBRBA6QwZrVwi',
                'mode' => 'test',
                'alias' => 'Razorpay',
                'is_active' => true,
            ],
            // 'paymob' => [
            //     'public_key' => '',
            //     'secret_key' => '',
            //     'integration_id' => '',
            // ],
        ];

        foreach ($gateways as $name => $config) {
            $paymentGateway = PaymentGateway::updateOrCreate([
                'name' => $name,
            ],[
                'config' => json_encode($config),
                'type' => 'test',
                'is_active' => true,
            ]);

            switch ($name) {
                case 'paypal':
                    $media = Media::factory()->create([
                        'type' => MediaTypeEnum::IMAGE,
                        'src' => 'assets/images/payment/Paypal.png',
                        'path' => 'assets/images/payment/',
                        'extension' => 'png',
                    ]);
                    $paymentGateway->update([
                        'media_id' => $media->id
                    ]);
                    break;
                case 'stripe':
                    $media = Media::factory()->create([
                        'type' => MediaTypeEnum::IMAGE,
                        'src' => 'assets/images/payment/Stripe.png',
                        'path' => 'assets/images/payment/',
                        'extension' => 'png',
                    ]);
                    $paymentGateway->update([
                        'media_id' => $media->id
                    ]);
                    break;
                case '2checkout':
                    $media = Media::factory()->create([
                        'type' => MediaTypeEnum::IMAGE,
                        'src' => 'assets/images/payment/2checkout.png',
                        'path' => 'assets/images/payment/',
                        'extension' => 'png',
                    ]);
                    $paymentGateway->update([
                        'media_id' => $media->id
                    ]);
                    break;
                case 'aamarpay':
                    $media = Media::factory()->create([
                        'type' => MediaTypeEnum::IMAGE,
                        'src' => 'assets/images/payment/Aamarpay.png',
                        'path' => 'assets/images/payment/',
                        'extension' => 'png',
                    ]);
                    $paymentGateway->update([
                        'media_id' => $media->id
                    ]);
                    break;
                case 'razorpay':
                    $media = Media::factory()->create([
                        'type' => MediaTypeEnum::IMAGE,
                        'src' => 'assets/images/payment/Razorpay.png',
                        'path' => 'assets/images/payment/',
                        'extension' => 'png',
                    ]);
                    $paymentGateway->update([
                        'media_id' => $media->id
                    ]);
                    break;
                // case 'paymob':
                //     $media = Media::factory()->create([
                //         'type' => MediaTypeEnum::IMAGE,
                //         'src' => 'assets/images/payment/Paymob.png',
                //         'path' => 'assets/images/payment/',
                //         'extension' => 'png',
                //     ]);
                //     $paymentGateway->update([
                //         'media_id' => $media->id
                //     ]);
                //     break;
            }
        }
    }
}
