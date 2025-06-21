<?php

namespace App\Services;

use App\Mail\CustomerServiceMail;
use Illuminate\Support\Facades\Mail;
use App\Models\Customers;
use App\Models\Companys;

class Mailer
{
    /**
     * Kirim email customer service
     *
     * @param string $userEmail
     * @param string $userName
     * @param string $pdfLocation
     * @param object $company
     * @param string $serviceCode
     * @return bool
     */
    public function sendEmailCs($userEmail, $userName, $pdfLocation, $company, $serviceCode)
    {
        // Cari customer berdasarkan email
        $customer = Customers::where('email', $userEmail)->first();

        if (!$customer) {
            return false;  // Jika customer tidak ditemukan
        }

        try {
            // Kirim email menggunakan Mailable class
            Mail::to($customer->email, $customer->name)
                ->send(new CustomerServiceMail($customer, $company, $serviceCode, $pdfLocation));

            return true; // Jika email berhasil dikirim
        } catch (\Exception $e) {
            // Tangani kegagalan pengiriman email
            return false;
        }
    }
}
