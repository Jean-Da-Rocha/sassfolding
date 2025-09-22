<?php

declare(strict_types=1);

namespace Modules\Users\Concerns;

use Illuminate\Validation\Rules\Password;

trait WithPasswordValidationRule
{
    /**
     * Get the validation rules used to validate passwords.
     *
     * @return array<int, Password|string>
     */
    protected function passwordRules(): array
    {
        return ['required', 'string', Password::default(), 'confirmed'];
    }
}
