<span class="fs-6">
    @if(isset($$model->theme->name))
    {{ $model->theme?->name }}
    @else
    <span class="text-muted fs-6">N/A</span>
    @endif

</span>