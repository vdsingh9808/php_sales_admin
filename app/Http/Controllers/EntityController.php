<?php

namespace App\Http\Controllers;

use App\DataTables\EntitiesDataTable;
use App\Http\Requests\EntityUpdateRequest;
use App\Models\Entity;
use App\Models\Theme;
use App\Models\Url;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class EntityController extends Controller
{
    public function index(EntitiesDataTable $dataTable)
    {
        $themes = Theme::query()->get();

        return $dataTable->render('pages.entities.index', compact('themes'));
    }

    public function create()
    {
        //
    }

    public function store()
    {
        $url = Cache::remember('auth_token', 3600, function () {
            return Url::query()->first();
        });

        $tokenResponse = Http::post($url->authentication_url);
        $authToken = $tokenResponse->json('access_token');

        $apiUrl = $url->server_url . '/lvt_legalentities';
        $fetchXml = '
            <fetch version="1.0" output-format="xml-platform" mapping="logical" distinct="false">
              <entity name="lvt_legalentity">
                <attribute name="lvt_legalentityid" />
                <attribute name="lvt_name" />
                <attribute name="createdon" />
                <order attribute="lvt_name" descending="false" />
                <filter type="and">
                  <condition attribute="statecode" operator="eq" value="0" />
                </filter>
              </entity>
            </fetch>';

        $apiResponse = Http::withToken($authToken)->get($apiUrl, ['fetchXml' => $fetchXml]);

        $entities = $apiResponse->json()['value'];
        foreach ($entities as $entityData) {
            $entityId = $entityData['lvt_legalentityid'];
            Entity::updateOrCreate(
                ['entity_id' => $entityId],
                [
                    'name' => $entityData['lvt_name'],
                    'entity_id' => $entityId,
                    'created_at' => $entityData['createdon'],
                ]
            );
        }

        return redirect()->route('entities.index')->with('success', 'Entity synced successfully');
    }

    public function show(Entity $entity)
    {
        return response()->json($entity);
    }

    public function edit(string $id)
    {
        //
    }

    public function update(EntityUpdateRequest $request, string $id): JsonResponse
    {
        $entity = Entity::query()->findOrFail($id);

        $entity->fill($request->all());

        $entity->save();

        return response()->json(['success' => true]);
    }

    public function destroy(string $id)
    {
        //
    }
}
