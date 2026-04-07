<?php

namespace App\Http\Controllers;

use App\DataTables\ThemesDataTable;
use App\Enums\ThemeMenu;
use App\Http\Requests\ThemeStoreRequest;
use App\Http\Requests\ThemeUpdateRequest;
use App\Models\Theme;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ThemeController extends Controller
{
    public function index(ThemesDataTable $dataTable)
    {
        return $dataTable->render('pages.themes.index');
    }

    public function create(): View
    {
        $validThemes = [] ;
        
        $themesInEnv = config('services.theme_menus') ?? [];
        $themesInEnv = !empty($themesInEnv) ? explode(',', $themesInEnv) : [] ;

        $themes = ThemeMenu::cases() ;

        foreach($themes as $theme){
            if(in_array($theme->value, $themesInEnv, true)){
                $validThemes[] = ThemeMenu::from($theme->value) ;
            }
        }

        return view('pages.themes.create', compact('validThemes'));
    }

    public function store(ThemeStoreRequest $request): JsonResponse
    {
        $data = $request->all();

        $data['menu_name'] = json_encode($request->input('menu_name'));

        $isDefault = $request->has('is_default') && $request->input('is_default');

        if ($isDefault) {
            Theme::query()->update(['is_default' => false]);
        }

        $theme = Theme::create($data);

        if ($request->hasFile('logo')) {
            $theme->addMediaFromRequest('logo')
                ->toMediaCollection('logos');
        }

        if ($request->hasFile('banner_image')) {
            $theme->addMediaFromRequest('banner_image')
                ->toMediaCollection('banners');
        }

        if ($isDefault) {
            $theme->update(['is_default' => true]);
        }

        return response()->json(['success' => true]);
    }

    public function edit(Theme $theme): View
    {
        return view('pages.themes.edit', compact('theme'));
    }

    public function update(ThemeUpdateRequest $request, string $id): JsonResponse
    {
        $theme = Theme::findOrFail($id);

        $data = $request->all();
        $data['menu_name'] = json_encode($request->input('menu_name'));

        $isDefault = $request->has('is_default') && $request->input('is_default');

        if ($isDefault) {
            Theme::query()->where('id', '<>', $theme->id)->update(['is_default' => false]);
        }

        $theme->fill($data);
        $theme->is_default = $isDefault;
        $theme->save();

        if ($request->hasFile('logo')) {
            $theme->clearMediaCollection('logos');
            $theme->addMediaFromRequest('logo')->toMediaCollection('logos');
        }

        if ($request->hasFile('banner_image')) {
            $theme->clearMediaCollection('banners');
            $theme->addMediaFromRequest('banner_image')->toMediaCollection('banners');
        }

        return response()->json(['success' => true]);
    }

    public function destroy(string $id): RedirectResponse
    {
        $theme = Theme::findOrFail($id);

        if ($theme->is_default) {
            return redirect()->route('themes.index')->with('error', 'Default theme cannot be deleted.');
        }

        if ($theme->entity) {
            $theme->entity->theme_id = null;
            $theme->entity->save();
        }

        $theme->delete();

        return redirect()->route('themes.index')->with('success', 'Theme deleted successfully.');
    }
}
