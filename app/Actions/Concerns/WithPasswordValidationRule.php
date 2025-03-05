<?php

declare(strict_types=1);

namespace App\Actions\Concerns;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Validation\Rules\Password;

trait WithPasswordValidationRule
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
