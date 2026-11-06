<?php

namespace App\Services;

class SecretHasher
{
    public function hmac(string $value): string
    {
        return hash_hmac('sha256', $value, $this->key());
    }

    public function hmacBytes(string $bytes): string
    {
        return hash_hmac('sha256', $bytes, $this->key());
    }

    public function check(string $value, string $hmac): bool
    {
        return hash_equals($hmac, $this->hmac($value));
    }

    public function checkBytes(string $bytes, string $hmac): bool
    {
        return hash_equals($hmac, $this->hmacBytes($bytes));
    }

    public function otp(): string
    {
        return str_pad((string) random_int(0, 99999999), 8, '0', STR_PAD_LEFT);
    }

    public function formatearCodigo(string $digitos): string
    {
        return substr($digitos, 0, 4).'-'.substr($digitos, 4, 4);
    }

    public function normalizarCodigo(string $entrada): string
    {
        return preg_replace('/\D+/', '', $entrada) ?? '';
    }

    private function key(): string
    {
        $key = (string) config('app.key');

        if (str_starts_with($key, 'base64:')) {
            return (string) base64_decode(substr($key, 7), true);
        }

        return $key;
    }
}
