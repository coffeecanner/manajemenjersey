<?php

namespace App\Rules;

use App\Models\PesananUtama;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueCustomerName implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the customer name already exists (case insensitive)
        $existingOrder = PesananUtama::whereRaw('LOWER(nama_pemesan) = ?', [strtolower($value)])->first();
        
        if ($existingOrder) {
            $fail("Nama pemesan '{$value}' sudah terdaftar sebelumnya. Silakan gunakan nama yang berbeda atau hubungi admin jika ini adalah kesalahan.");
        }
    }
}