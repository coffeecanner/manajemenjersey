<?php

namespace App\Rules;

use App\Models\PesananUtama;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class UniqueJerseyNumber implements ValidationRule
{
    /**
     * Run the validation rule.
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        // Check if the jersey number already exists
        $existingOrder = PesananUtama::where('nomor_punggung', $value)->first();
        
        if ($existingOrder) {
            $fail("Nomor punggung {$value} sudah digunakan oleh pemesan lain. Silakan pilih nomor punggung yang berbeda.");
        }
    }
}