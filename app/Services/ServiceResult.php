<?php

namespace App\Services;

class ServiceResult
{
    public function __construct(
        public readonly bool $success,
        public readonly string $message,
        public readonly ?array $data = null
    ) {
    }

    public static function success(string $message, ?array $data = null): self
    {
        return new self(true, $message, $data);
    }

    public static function error(string $message): self
    {
        return new self(false, $message);
    }
}
