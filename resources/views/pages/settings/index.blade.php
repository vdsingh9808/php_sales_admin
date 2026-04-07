@extends('layouts.master')

@section('content')
    <div class="container">
        <form id="url-update-form" action="{{ route('settings.update', $url->id) }}" enctype="multipart/form-data">
            @method('PUT')
            @csrf
            <div class="row">
                <div class="col-lg-6">
                    <h5 class="pt-3 pb-3">Urls</h5>
                    <div class="mb-4">
                        <label for="authentication-url" class="form-label">Authentication URL</label>
                        <input type="text" id="authentication-url" class="form-control" name="authentication_url"
                               value="{{ old('authentication_url', $url->authentication_url)}}">
                        @error('authentication_url')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="server-url" class="form-label">Server URL</label>
                        <input type="text" id="server-url" class="form-control" name="server_url"
                               value="{{ old('server_url', $url->server_url )}}">
                        @error('server_url')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-lg-6">
                    <h5 class="pt-3 pb-3">Payment Urls</h5>
                    <div class="mb-4">
                        <label for="payment-base-url" class="form-label">Payment base URL</label>
                        <input type="text" id="payment-base-url" class="form-control" name="payment_base_url"
                               value="{{ old('payment_base_url',$url->payment_base_url )}}">
                        @error('payment_base_url')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="api-key" class="form-label">API Key</label>
                        <input type="text" id="api-key" class="form-control" name="api_key"
                               value="{{old('api_key', $url->api_key) }}">
                        @error('api_key')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label for="reference-key" class="form-label">Reference Key</label>
                        <input type="text" id="reference-key" class="form-control" name="reference_key"
                               value="{{ old('reference_key',$url->reference_key) }}">
                        @error('reference_key')
                        <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-end gap-1 pb-5">
                        <button type="submit" id="submit-form" class="btn btn-primary">Save Changes</button>
                        <button type="button" class="btn btn-outline-secondary">Cancel</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    @include('pages.settings.modal.update-success-modal')
@endsection
@push('custom-scripts')
    <script>
        $(document).ready(function () {
            $("#url-update-form").validate({
                rules: {
                    authentication_url: {
                        required: true,
                        url: true,
                    },
                    server_url: {
                        required: true,
                        url: true,
                    },
                    payment_base_url: {
                        required: true,
                        url: true,
                    },
                    api_key: {
                        required: true,
                    },
                    reference_key: {
                        required: true,
                    },
                },
            });

            $('#submit-form').click(function (event) {
                event.preventDefault();
                let form = $('#url-update-form');

                let formData = new FormData(form[0]);
                console.log(form.valid());
                if (form.valid()) {
                    $.ajax({
                        url: "{{route('settings.update', $url->id)}}",
                        type: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                        },
                        processData: false,
                        contentType: false,
                        data: formData,
                        success: function (response) {
                            $('#successModal').modal('show');

                            $('#successModal .btn-close, #successModal .btn-primary').on('click', function () {
                                $('#successModal').modal('hide');
                                window.location.href = "{{ route('settings.index') }}";
                            });
                        },
                        error: function (xhr, textStatus, errorThrown) {
                            if (xhr.responseJSON && xhr.responseJSON.errors) {
                                $.each(xhr.responseJSON.errors, function (field, errors) {
                                    $('input[name="' + field + '"]').closest('.mb-3').append('<div class="text-danger">' + errors.join('<br>') + '</div>');
                                });
                            } else {
                                console.log('Error:', errorThrown);
                            }
                        }
                    });
                }
            });
        });
    </script>
@endpush
