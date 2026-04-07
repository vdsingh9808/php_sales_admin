@foreach(json_decode($model->menu_name) as $menuValue)
    @php
        $menuLabel = \App\Enums\ThemeMenu::from($menuValue)->label();
    @endphp
    <span class="badge mb-1 bg-label-warning">{{ $menuLabel }}</span>
@endforeach
