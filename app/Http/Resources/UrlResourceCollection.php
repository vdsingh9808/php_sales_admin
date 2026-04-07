<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class UrlResourceCollection extends ResourceCollection
{

    public function toArray($request): array
    {
        return $this->collection->map(function ($url) {
            return [
                "authentication_url" => $url->authentication_url,
                "server_url" => $url->server_url,
                "payment_base_url" => $url->payment_base_url,
                "api_key" => $url->api_key,
                "reference_key" => $url->reference_key,
            ];
        })->toArray();
    }
}
