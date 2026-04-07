@extends('layouts.master')
@section('content')
    <div>
        <div class="card">
            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                <div class="mb-2 px-2">
                    <h3 class="card-header">Themes</h3>
                    <div class="d-flex justify-content-between">
                        <div class="px-4">
                            <p>Browse through and select themes to enhance your project&#39;s visual appeal.</p>
                        </div>
                        <div class="d-flex gap-2">
                            <div class="position-relative">
                                <input type="text" class="form-control" id="filter-theme-search" autocomplete="off"
                                       placeholder="Search.." data-dt-toggle="search"
                                       data-dt-target="#themes-table">
                                <span class="position-absolute" style="top: 10px;right: 8px"><i
                                        class='bx bx-search'></i></span>
                            </div>
                            <div>
                                <button type="submit" class="btn btn-primary"
                                        onclick="window.location.href='{{ route('themes.create') }}'">Create New Theme
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>
@endsection
@push('custom-scripts')
    {{ $dataTable->scripts() }}
@endpush

<style>
    .btn-outline-secondary:focus {
        color: #7D7B7B !important;
    }
</style>
