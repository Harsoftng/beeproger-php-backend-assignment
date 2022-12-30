<?php

    namespace App\Util;

    class Utilities
    {
        public static function getResponse(string $message, bool $status, int $code, array $errors = []): array {
            return [
                "status"  => $status,
                "code"    => $code,
                "message" => $message,
                "errors"  => $errors,
            ];
        }
    }
