<?php

namespace App\Services\Contracts;

interface NotifyHandler
{
    public function sendNotify(string|int $to, ?string $subject , ?string $message = null): bool;
}