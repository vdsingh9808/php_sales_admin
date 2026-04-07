<?php

namespace App\Http\Controllers;

use App\Http\Requests\UrlUpdateRequest;
use App\Models\Url;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class UrlController extends Controller
{
    public function index(): view
    {
        $url = Url::query()->first();

        return view('pages.settings.index', compact('url'));
    }

    public function update(UrlUpdateRequest $request, string $id): JsonResponse
    {
        $url = Url::query()->findOrFail($id);

        $url->fill($request->all());

        $url->save();

        return response()->json(['success' => true]);
    }
}
