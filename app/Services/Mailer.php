<?php

namespace App\Services;

use App\Mail\NotifyError;
use Illuminate\Support\Facades\Mail;
use App\Services\Contracts\NotifyHandler;

class Mailer implements NotifyHandler
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public function sendNotify(string|int $to, ?string $subject = 'Visobra reporto un fallo', ?string $message = null): void
    {
        Mail::to($to)->send(new NotifyError($message, $subject));
    }
}
