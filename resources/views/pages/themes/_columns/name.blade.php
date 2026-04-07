@php
    $menuLabel = isset($model) && $model->is_default ? 'Default' : '';
@endphp

{{ isset($model) ? $model->name : '' }}&nbsp;&nbsp;<span
    class="badge rounded-pill bg-label-warning">{{ $menuLabel }}</span>
