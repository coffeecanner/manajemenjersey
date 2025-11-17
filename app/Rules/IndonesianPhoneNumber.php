<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class IndonesianPhoneNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $value);
        
        // Check if it's a valid Indonesian phone number
        // Indonesian mobile numbers start with 08, 62, or +62
        if (!preg_match('/^(08|62|\+62)[0-9]{8,13}$/', $phone)) {
            $fail('Nomor WhatsApp harus dalam format nomor Indonesia yang valid (contoh: 08123456789 atau +628123456789).');
        }
        
        // Additional check for length (Indonesian mobile numbers are typically 10-13 digits after country code)
        $cleanPhone = preg_replace('/^(08|62|\+62)/', '', $phone);
        if (strlen($cleanPhone) < 8 || strlen($cleanPhone) > 13) {
            $fail('Nomor WhatsApp harus memiliki 8-13 digit setelah kode negara.');
        }
    }
}