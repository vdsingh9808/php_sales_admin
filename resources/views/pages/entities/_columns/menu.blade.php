@if ($model->theme && $model->theme->menu_name)
@foreach (json_decode($model->theme->menu_name) as $menuName)
<span class="badge bg-label-warning">{{ $menuName }}</span>
@endforeach
@else
<span class="text-muted fs-6">N/A</span>
@endif