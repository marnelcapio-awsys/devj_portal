<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use PharIo\Manifest\Email;
use Symfony\Component\Mailer\Envelope;

class Employee extends Mailable
{
    use Queueable, SerializesModels;

    protected $mailType;
    protected $mailData;
    const MAIL_1 = '【DEV J PORTAL】Employee Registration';
    const MAIL_2 = '【DEV J PORTAL】Account Activation';
    const MAIL_3 = '【DEV J PORTAL】Employee Registration Rejection';
    const MAIL_4 = '【DEV J PORTAL】Employee Detail Update';
    const MAIL_5 = '【DEV J PORTAL】Employee Update Approval';
    const MAIL_6 = '【DEV J PORTAL】Employee Update Rejection';
    const MAIL_7 = '【DEV J PORTAL】Employee Detail Update';
    const MAIL_8 = '【DEV J PORTAL】Project Linkage';
    const MAIL_9 = '【DEV J PORTAL】Laptop Linkage';
    const MAIL_10 = '【DEV J PORTAL】Project Linkage';
    const MAIL_11 = '【DEV J PORTAL】Laptop Linkage';
    const MAIL_12 = '【DEV J PORTAL】Account Deactivation';
    const MAIL_13 = '【DEV J PORTAL】Surrender of Assets';
    const MAIL_14 = '【DEV J PORTAL】Account Reactivation';

    

    /**
     * Create a new message instance.
     *
     * @return void 
     */
    public function __construct($mailData, $mailType)
    {
        $this->mailData = $mailData;
        $this->mailType = $mailType;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->subject(constant("self::MAIL_{$this->mailType}"))
            ->view('mail.employee', ['mailData' => $this->mailData, 'mailType' => $this->mailType]);
    }
}
