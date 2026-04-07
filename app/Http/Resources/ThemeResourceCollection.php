<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ThemeResourceCollection extends JsonResource
{
    public function toArray($request): array
    {
        $logoUrl = $this->getFirstMediaUrl('logos');
        $bannerUrl = $this->getFirstMediaUrl('banners');
        $menuItems = json_decode($this->menu_name);

        return [
            'id' => $this->id,
            'name' => $this->name,
            'button_primary' => $this->button_primary,
            'button_secondary' => $this->button_secondary,
            'is_default' => $this->is_default,
            'menu_items' => $menuItems,
            'logo_url' => $logoUrl,
            'banner_url' => $bannerUrl,
            'general_heading' => $this->general_heading,
            'general_body' => $this->general_body,
            'general_icons' => $this->general_icons,
            'general_background' => $this->general_background,
            'table_heading' => $this->table_heading,
            'table_title' => $this->table_title,
            'table_body' => $this->table_body,
            'header_background' => $this->header_background,
            'header_text' => $this->header_text,
            'navbar_text' => $this->navbar_text,
            'navbar_background' => $this->navbar_background,
        ];
    }
}
