<?php

namespace App\Services;

use Illuminate\Support\Facades\RateLimiter;

class Notify 
{
 
    private $notifier;
    private $attemps = 3;
    private $identifier = 'Notifier';

    public function __construct(Object $notifier)
    {
        $this->notifier = $notifier;
    }

    public function setterNotifier(Object $notifier): void
    {
        $this->notifier = $notifier;
    }

    public function clientNotify(string|int $to, ?string $subject , ?string $message = null): void
    {
        RateLimiter::attempt(
            $this->identifier, 
            $perDay = $this->attemps,
            function() use ($to, $subject, $message){
                $this->notifier->sendNotify($to, $subject, $message);
            }  
        );
    }

    public function configLimiter(int $attemps, string $identifier)
    {
        $this->attemps = $attemps;
        $this->identifier = $identifier;
    }
}
