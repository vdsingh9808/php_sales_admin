<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UrlResourceCollection;
use App\Models\Url;

class UrlController extends Controller
{
    public function __invoke(): UrlResourceCollection
    {
        $url = Url::all();

        return new UrlResourceCollection($url);
    }
}
