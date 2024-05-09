<?php

namespace App\Services;


class Notify 
{
 
    private $notifier; 

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
        $this->notifier->sendNotify($to, $subject, $message);
    }
}
