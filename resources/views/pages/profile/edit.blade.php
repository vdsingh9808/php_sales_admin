@extends('layouts.master')
@section('content')
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4 w-50 p-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h1 class="mb-0 fw-semibold fs-5">Change Password</h1>
                </div>
                <div class="card-body">
                    <div class="card-text alert bg-primary">
                        <p class="fw-semibold p-1">
                            Your new password must be at least 8 characters long and include at least one uppercase
                            letter,
                            one lowercase letter, one digit, and one special character.
                        </p>
                    </div>
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form id="change-password-form" method="POST" action="{{route('profile.update')}}">
                        @csrf
                        @method('PATCH')
                        <div class="mb-3">
                            <label class="mb-2 fw-semibold" for="old-password">Enter old password</label>
                            <input type="password" class="form-control" id="old-password" name="old_password"
                                   value="{{ old('old_password') }}"/>
                            @error('old_password')
                            <label class="error invalid-feedback">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="mb-2 fw-semibold" for="new-password">New password</label>
                            <input type="password" class="form-control" id="new-password" name="new_password"
                                   value="{{ old('new_password') }}"/>
                            @error('new_password')
                            <label class="error invalid-feedback">{{ $message }}</label>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label class="mb-2 fw-semibold" for="confirm-password">Confirm new password</label>
                            <input type="password" class="form-control" id="confirm-password" name="confirm_password"
                                   value="{{ old('confirm_password') }}"/>
                            @error('old_password')
                            <label class="error invalid-feedback">{{ $message }}</label>
                            @enderror
                        </div>
                        <button type="submit" class="btn btn-primary">Change Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('custom-scripts')

    <script>
        $(function () {
            $("#change-password-form").validate({
                rules: {
                    old_password: {
                        required: true
                    },
                    new_password: {
                        required: true,
                        minlength: 8
                    },
                    confirm_password: {
                        required: true,
                        equalTo: "#new-password"
                    }
                },
                messages: {
                    old_password: {
                        required: "Please enter your old password"
                    },
                    new_password: {
                        required: "Please enter a new password",
                        minlength: "Your password must be at least 8 characters long"
                    },
                    confirm_password: {
                        required: "Please confirm your new password",
                        equalTo: "Passwords do not match"
                    }
                },
            });
        });
    </script>
@endpush
