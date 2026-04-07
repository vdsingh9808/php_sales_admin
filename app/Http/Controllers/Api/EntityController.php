<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\EntityResourceCollection;
use App\Models\Entity;
use Illuminate\Support\Facades\Validator;

class EntityController extends Controller
{
    public function __invoke($id): EntityResourceCollection
    {
        $validator = Validator::make(['entity_id' => $id], [
            'entity_id' => 'required|exists:entities,entity_id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        $entity = Entity::with('theme')->where('entity_id', $id)->firstOrFail();

        return new EntityResourceCollection($entity);
    }
}
