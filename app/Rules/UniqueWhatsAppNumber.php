<?php

namespace App\Rules;

use App\Models\PesananUtama;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueWhatsAppNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Normalize the phone number for comparison
        $normalizedPhone = $this->normalizePhoneNumber($value);
        
        // Check if the WhatsApp number already exists (check both original and normalized)
        $existingOrder = PesananUtama::where('nomor_whatsapp', $normalizedPhone)
            ->orWhere('nomor_whatsapp', $value)
            ->first();
        
        if ($existingOrder) {
            $fail("Nomor WhatsApp '{$value}' sudah terdaftar sebelumnya. Silakan gunakan nomor yang berbeda atau hubungi admin jika ini adalah kesalahan.");
        }
    }
    
    /**
     * Normalize phone number to standard format
     */
    private function normalizePhoneNumber($phone)
    {
        // Remove all non-numeric characters
        $phone = preg_replace('/[^0-9]/', '', $phone);
        
        // Convert to standard format (62xxxxxxxxx)
        if (strpos($phone, '08') === 0) {
            $phone = '62' . substr($phone, 1);
        } elseif (strpos($phone, '62') !== 0) {
            $phone = '62' . $phone;
        }
        
        return $phone;
    }
}