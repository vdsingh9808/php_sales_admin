<?php

namespace App\Rules;

use App\Models\Theme;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class AtLeastOneDefaultTheme implements ValidationRule
{
    protected $currentThemeId;

    public function __construct($currentThemeId)
    {
        $this->currentThemeId = $currentThemeId;
    }

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $theme = Theme::query()->find($this->currentThemeId);

        if ($theme && $theme->is_default && $value == 0) {
            $numberOfDefaults = Theme::query()->where('is_default', true)
                ->where('id', '!=', $this->currentThemeId)
                ->count();

            if ($numberOfDefaults === 0) {
                $fail('Cannot change default status. At least one theme must remain as default.');
            }
        }
    }
}
