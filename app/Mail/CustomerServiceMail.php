<?php

namespace App\Mail;

use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class CustomerServiceMail extends Mailable
{
    use SerializesModels;

    public $customer;
    public $company;
    public $serviceCode;
    public $pdfLocation;

    // Konstruktor untuk menerima data yang diperlukan
    public function __construct($customer, $company, $serviceCode, $pdfLocation)
    {
        $this->customer = $customer;
        $this->company = $company;
        $this->serviceCode = $serviceCode;
        $this->pdfLocation = $pdfLocation;
    }

    // Mendefinisikan tampilan email dan melampirkan file PDF
    public function build()
    {
        return $this->subject('Proposal biaya perbaikan Unit')
                    ->from('cs@xyzgoprint.com', 'Customer Service')
                    ->to($this->customer->email, $this->customer->name)
                    ->view('emails.customer_service') // Templating HTML email
                    ->with([
                        'username' => $this->customer->name,
                        'company_name' => $this->company->name,
                        'company_address' => $this->company->address,
                        'company_phone' => $this->company->phone,
                        'service_code' => $this->serviceCode
                    ])
                    ->attach($this->pdfLocation, [
                        'as' => 'biaya_perbaikan_' . $this->serviceCode . '.pdf',
                        'mime' => 'application/pdf',
                    ]);
    }
}

