<?php

declare(strict_types=1);

namespace App\Actions\Fortify;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rules\Password;

trait PasswordValidationRules
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, array|string|ValidationRule>
     */
    protected function passwordRules(): array
    {
        return ['required', 'string', Password::default(), 'confirmed'];
    }
}
