<?php

namespace App\Http\Resources;

use App\Models\Theme;
use Illuminate\Http\Resources\Json\JsonResource;

class EntityResourceCollection extends JsonResource
{
    public function toArray($request): array
    {
        $defaultTheme = Theme::query()->where('is_default', true)->first();

        if (!$defaultTheme) {
            return [
                'error' => 'No default theme available',
            ];
        }

        return [
            'id' => $this->id,
            'entity_id' => $this->entity_id,
            'name' => $this->name,
            'api_key' => $this->api_key,
            'reference_key' => $this->reference_key,
            'theme' => $this->theme ? new ThemeResourceCollection($this->theme) : new ThemeResourceCollection($defaultTheme),
        ];
    }
}
