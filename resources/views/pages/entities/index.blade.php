@extends('layouts.master')
@section('content')
    <div>
        <div class="card">
            <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
                <div class="mb-2 px-2">
                    <h3 class="card-header">Entities</h3>
                    <div class="d-flex justify-content-between">
                        <div class="px-4">
                            <p>Find and map project themes easily with our comprehensive entity mapping tool.</p>
                        </div>
                        <div class="d-flex gap-2">
                            <div class="position-relative">
                                <input type="text" class="form-control" id="filter-user-search" autocomplete="off"
                                       placeholder="Search.." data-dt-toggle="search"
                                       data-dt-target="#entities-table">
                                <span class="position-absolute" style="top: 10px;right: 8px"><i
                                        class='bx bx-search'></i></span>
                            </div>
                            {{--                            <div class="col-6">--}}
                            {{--                                <button type="submit" class="btn btn-primary w-100"><span--}}
                            {{--                                        class="d-flex justify-content-center align-items-center">--}}
                            {{--                                        <i class='bx bx-refresh'></i>&nbsp;&nbsp;Sync--}}
                            {{--                                    </span>--}}
                            {{--                                </button>--}}
                            {{--                            </div>--}}
                            <div class="col-6">
                                <a href="#" id="syncButton" class="btn btn-primary w-100">
                                    <span class="d-flex justify-content-center align-items-center">
                                        <i class='bx bx-refresh'></i>&nbsp;&nbsp;Sync&nbsp;&nbsp;
                                        <div id="syncLoader" class="spinner-border spinner-border-sm d-none"
                                             role="status"><span class="visually-hidden">Loading...</span>
                                        </div>
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
                {{ $dataTable->table() }}
            </div>
        </div>
    </div>

    @include('pages.entities.modal.edit')
    @include('pages.entities.modal.success-modal')
@endsection

@push('custom-scripts')
    {{ $dataTable->scripts() }}
    <script>
        let $table = $('#entities-table');

        function populateEditModal(id) {
            let url = "{{ route('entities.show', ['entity' => ':id']) }}".replace(':id', id);
            $.ajax({
                url: url,
                type: 'GET',
                dataType: 'json',
            }).done(function (data) {
                $('#editModal').modal('show');
                $('#name').val(data?.name);
                $('#reference-key').val(data?.reference_key);
                $('#exampleFormControlSelect1').val(data?.theme_id);
                $('#api-key').val(data?.api_key);
                $('#entity-update-form').data('entity-id', id);
            }).fail(function (jqXHR, textStatus, errorThrown) {
                console.log(jqXHR);
            });
        }

        $table.on('click', '.edit-modal', function (e) {
            e.preventDefault();
            let id = $(this).data("id");
            populateEditModal(id);
        });

        $('#entity-update-form').submit(function (event) {
            event.preventDefault();
            let form = $(this);
            let id = form.data('entity-id');
            let url = "{{ route('entities.update', ['entity' => ':id']) }}".replace(':id', id);
            let formData = new FormData(form[0]);
            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#entities-table').DataTable().draw();
                    $('#editModal').modal('hide');
                    $('#successModal').modal('show');

                },
                error: function (xhr, textStatus, errorThrown) {
                    console.log(xhr);
                }
            });
        });

        document.getElementById('syncButton').addEventListener('click', function (event) {
            event.preventDefault();
            let form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('entities.store') }}';

            let csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            document.body.appendChild(form);
            form.submit();
        });

        document.getElementById('syncButton').addEventListener('click', function (event) {
            event.preventDefault();

            // Show loader
            $('#syncIcon').addClass('d-none');
            $('#syncLoader').removeClass('d-none');

            let form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route('entities.store') }}';

            let csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = '{{ csrf_token() }}';
            form.appendChild(csrfInput);

            document.body.appendChild(form);
            form.submit();
        });
    </script>
@endpush

<style>
    .edit-modal:focus {
        color: #7D7B7B !important;
    }

    .spinner-border {
        width: 1.5rem;
        height: 1.5rem;
        color: #fff;
    }
</style>
