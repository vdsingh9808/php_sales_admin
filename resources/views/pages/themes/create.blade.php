@extends('layouts.master')
@section('content')
    <div class="card mb-4">
        <div class="card-body">
            <h1 class="mb-0 fw-semibold fs-5">Set your new theme</h1>
            <p class="card-text pt-2">
                Define your project&#39;s new look. Easily set up a new theme with our intuitive
                customization tool.
            </p>
        </div>
    </div>
    <form id="theme-store-form" method="POST" action="{{ route('themes.store') }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4" style="padding-bottom: 60px!important;">
                    <h5 class="card-header fw-semibold">General</h5>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="defaultFormControlInput" class="form-label">Name</label>
                            <input type="text" class="form-control" id="defaultFormControlInput"
                                   aria-describedby="defaultFormControlHelp" name="name" value="{{ old('name') }}">
                            @error('name')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="mb-3">
                            <label for="defaultFormControlInput" class="form-label">Menu</label>
                            <div class="form-group">
                                <select class="js-example-basic-multiple form-control" multiple data-allow-clear="1"
                                        id="select2"
                                        name="menu_name[]">
                                    @foreach($validThemes as $menu)
                                        <option
                                            value="{{ $menu->value }}" {{ in_array($menu->value, old('menu_name', [])) ? 'selected' : '' }}>
                                            {{ $menu->label() }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @if ($errors->has('menu_name'))
                                <div class="text-danger">{{ $errors->first('menu_name') }}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <div>
                                <label for="colorPicker" class="form-label">Heading</label>
                            </div>
                            <div>
                                <div id="color-picker-rgb" class="input-group colorpicker-component position-relative">
                                    <input id="general-heading-color-picker" type="text" class="form-control"
                                           name="general_heading" value="{{ old('general_heading') }}"/>
                                    <span class="input-group-addon position-absolute end-0"><i
                                            class='bx bx-color'></i></span>
                                </div>
                                @error('general_heading')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <div>
                                <label for="colorPicker" class="form-label">Body</label>
                            </div>
                            <div>
                                <div id="color-picker-rgb" class="input-group colorpicker-component position-relative">
                                    <input id="general-body-color-picker" type="text" class="form-control"
                                           name="general_body" value="{{ old('general_body') }}"/>
                                    <span class="input-group-addon position-absolute end-0"><i
                                            class='bx bx-color'></i></span>
                                </div>
                                @error('general_body')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <div>
                                <label for="colorPicker" class="form-label">Background</label>
                            </div>
                            <div>
                                <div id="color-picker-rgb" class="input-group colorpicker-component position-relative">
                                    <input id="general-background-color-picker" type="text" class="form-control"
                                           name="general_background" value="{{ old('general_background') }}"/>
                                    <span class="input-group-addon position-absolute end-0"><i
                                            class='bx bx-color'></i></span>
                                </div>
                                @error('general_background')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <div>
                                <label for="colorPicker" class="form-label">Icons</label>
                            </div>
                            <div>
                                <div id="color-picker-rgb" class="input-group colorpicker-component position-relative">
                                    <input id="general-icons-color-picker" type="text" class="form-control"
                                           name="general_icons" value="{{ old('general_icons') }}"/>
                                    <span class="input-group-addon position-absolute end-0"><i
                                            class='bx bx-color'></i></span>
                                </div>
                                @error('general_icons')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <label for="defaultFormControlInput" class="form-label">Legal Entity logo</label>
                            <input class="form-control" type="file" id="logFile" name="logo" value="{{ old('logo') }}"
                                   accept="image/png, image/jpg, image/jpeg, image/svg+xml">
                            @if ($errors->has('logo'))
                                <div class="text-danger">{{ $errors->first('logo') }}</div>
                            @endif
                        </div>
                        <div class="mb-3">
                            <label for="defaultFormControlInput" class="form-label">Banner Image</label>
                            <input class="form-control" type="file" id="bannerFile" name="banner_image"
                                   value="{{ old('banner_image') }}"
                                   accept="image/png, image/jpg, image/jpeg, image/svg+xml">
                            @if ($errors->has('banner_image'))
                                <div class="text-danger">{{ $errors->first('banner_image') }}</div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="card mb-4" style="padding-bottom: 31px!important;">
                    <h5 class="card-header fw-semibold">Button Colors</h5>
                    <div class="card-body">
                        <div class="mb-3">
                            <div>
                                <label for="colorPicker" class="form-label">Primary</label>
                            </div>
                            <div>
                                <div id="color-picker-rgb" class="input-group colorpicker-component">
                                    <input id="primary-color-picker" type="text" class="form-control"
                                           name="button_primary" value="{{ old('button_primary') }}"/>
                                    <span class="input-group-addon position-absolute end-0"><i
                                            class='bx bx-color'></i></span>
                                </div>
                                @error('button_primary')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <div>
                                <label for="colorPicker" class="form-label">Secondary</label>
                            </div>
                            <div>
                                <div id="color-picker-rgb" class="input-group colorpicker-component position-relative">
                                    <input id="secondary-color-picker" type="text" class="form-control"
                                           name="button_secondary" value="{{ old('button_secondary') }}"/>
                                    <span class="input-group-addon position-absolute end-0"><i
                                            class='bx bx-color'></i></span>
                                </div>
                                @error('button_secondary')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card mb-4" style="padding-bottom: 39px!important;">
                    <h5 class="card-header fw-semibold">Header</h5>
                    <div class="card-body">
                        <div class="mb-3">
                            <div>
                                <label for="colorPicker" class="form-label">Background</label>
                            </div>
                            <div id="color-picker-rgb" class="input-group colorpicker-component">
                                <input id="header-background-color-picker" type="text" class="form-control"
                                       name="header_background"
                                       value="{{ old('header_background') }}"/>
                                <span class="input-group-addon position-absolute end-0"><i
                                        class='bx bx-color'></i></span>
                            </div>
                            @error('header_background')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror

                        </div>
                        <div class="mb-3">
                            <div>
                                <label for="colorPicker" class="form-label">Text</label>
                            </div>
                            <div id="color-picker-rgb" class="input-group colorpicker-component">
                                <input id="header-text-color-picker" type="text" class="form-control"
                                       name="header_text"
                                       value="{{ old('header_text') }}"/>
                                <span class="input-group-addon position-absolute end-0"><i
                                        class='bx bx-color'></i></span>
                            </div>
                            @error('header_text')
                            <div class="text-danger">{{ $message }}</div>
                            @enderror

                        </div>
                    </div>
                </div>
                <div class="card mb-4" style="padding-bottom: 91px!important;">
                    <h5 class="card-header fw-semibold">Navbar</h5>
                    <div class="card-body">
                        <div class="mb-3">
                            <div>
                                <label for="colorPicker" class="form-label">Background</label>
                            </div>

                            <div>
                                <div id="color-picker-rgb" class="input-group colorpicker-component">
                                    <input id="navbar-background-color-picker" type="text" class="form-control"
                                           name="navbar_background"
                                           value="{{ old('navbar_background') }}"/>
                                    <span class="input-group-addon position-absolute end-0"><i
                                            class='bx bx-color'></i></span>
                                </div>
                                @error('navbar_background')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <div>
                                <label for="colorPicker" class="form-label">Text</label>
                            </div>

                            <div>
                                <div id="color-picker-rgb" class="input-group colorpicker-component">
                                    <input id="navbar-text-color-picker" type="text" class="form-control"
                                           name="navbar_text" value="{{ old('navbar_text') }}"/>
                                    <span class="input-group-addon position-absolute end-0"><i
                                            class='bx bx-color'></i></span>
                                </div>
                                @error('navbar_text')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mb-4" style="padding-bottom: 91px!important;">
                    <h5 class="card-header fw-semibold">Table Colors</h5>
                    <div class="card-body">
                        <div class="mb-3">
                            <div>
                                <label for="colorPicker" class="form-label">Title</label>
                            </div>
                            <div>
                                <div id="color-picker-rgb" class="input-group colorpicker-component position-relative">
                                    <input id="table-title-color-picker" type="text" class="form-control"
                                           name="table_title" value="{{ old('table_title') }}"/>
                                    <span class="input-group-addon position-absolute end-0"><i
                                            class='bx bx-color'></i></span>
                                </div>
                                @error('table_title')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <div>
                                <label for="colorPicker" class="form-label">Heading</label>
                            </div>
                            <div>
                                <div id="color-picker-rgb" class="input-group colorpicker-component">
                                    <input id="table-heading-color-picker" type="text" class="form-control"
                                           name="table_heading" value="{{ old('table_heading') }}"/>
                                    <span class="input-group-addon position-absolute end-0"><i
                                            class='bx bx-color'></i></span>
                                </div>
                                @error('table_heading')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        <div class="mb-3">
                            <div>
                                <label for="colorPicker" class="form-label">Body</label>
                            </div>
                            <div>
                                <div id="color-picker-rgb" class="input-group colorpicker-component position-relative">
                                    <input id="table-body-color-picker" type="text" class="form-control"
                                           name="table_body" value="{{ old('table_body') }}"/>
                                    <span class="input-group-addon position-absolute end-0"><i
                                            class='bx bx-color'></i></span>
                                </div>
                                @error('table_body')
                                <div class="text-danger">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card mb-4">
            <div class="card-body">
                <div class="card-text alert bg-primary">
                    <p class="card-text pt-2">Review your theme settings before saving changes. Ensure everything looks
                        perfect for
                        your project&#39;s new aesthetic.
                    </p>
                </div>
                <div class="d-flex align-items-center">
                    <div class="me-auto">
                        <div class="form-check form-switch mb-2">
                            <input class="form-check-input" type="checkbox" id="flexSwitchCheckDefault"
                                   name="is_default" value="1" {{ old('is_default') ? 'checked' : '' }}>
                            <label class="form-check-label" for="flexSwitchCheckDefault">Default theme</label>
                        </div>
                    </div>
                    <div class="col-auto me-3">
                        <button type="submit" class="btn btn-primary" id="submit-form">Create Theme
                        </button>
                    </div>
                    <div class="col-auto">
                        <button type="button" class="btn btn-outline-secondary" id="cancel-form">Cancel</button>
                    </div>
                </div>
            </div>
        </div>
    </form>

    @include('pages.themes._columns.modal.success-modal')
    @include('pages.themes._columns.modal.default-theme-modal')
@endsection

@push('custom-scripts')
    <script>
        $(function () {
            $('#heading-color-picker').colorpicker();
            $('#title-color-picker').colorpicker();
            $('#body-color-picker').colorpicker();
            $('#title-color-picker').colorpicker();
            $('#menu-color-picker').colorpicker();
            $('#header-color-picker').colorpicker();
            $('#dashboard-color-picker').colorpicker();
            $('#primary-color-picker').colorpicker();
            $('#secondary-color-picker').colorpicker();
            $('#heading-secondary-color-picker').colorpicker();


            $('#general-heading-color-picker').colorpicker();
            $('#general-body-color-picker').colorpicker();
            $('#general-icons-color-picker').colorpicker();
            $('#general-background-color-picker').colorpicker();

            $('#table-heading-color-picker').colorpicker();
            $('#table-title-color-picker').colorpicker();
            $('#table-body-color-picker').colorpicker();

            $('#header-background-color-picker').colorpicker();
            $('#header-text-color-picker').colorpicker();

            $('#navbar-background-color-picker').colorpicker();
            $('#navbar-text-color-picker').colorpicker();

            $('#cancel-form').click(function () {
                window.location.href = "{{ route('themes.index') }}";
            });

            $.validator.addMethod("colorCode", function (value, element) {
                let regex = /^#([0-9A-F]{3}){1,2}$/i;
                return this.optional(element) || regex.test(value);
            }, "Please enter a valid color code (e.g., #RRGGBB).");

            $('#select2').on('change', function () {
                if ($(this).valid()) {
                    $(this).next('.text-danger').remove();
                }
            });

            $("#theme-store-form").validate({
                rules: {
                    name: {
                        required: true
                    },
                    logo: {
                        required: true,
                    },
                    banner_image: {
                        required: true,
                    },
                    'menu_name[]': {
                        required: true
                    },
                    text_heading: {
                        required: true,
                        colorCode: true,
                    },
                    text_heading_secondary: {
                        required: true,
                        colorCode: true,
                    },
                    text_title: {
                        required: true,
                        colorCode: true,
                    },
                    text_body: {
                        required: true,
                        colorCode: true,
                    },
                    button_primary: {
                        required: true,
                        colorCode: true,
                    },
                    button_secondary: {
                        required: true,
                        colorCode: true,
                    },
                    dashboard: {
                        required: true,
                        colorCode: true,
                    },
                    menu: {
                        required: true,
                        colorCode: true,
                    },
                    header: {
                        required: true,
                        colorCode: true,
                    },
                    general_heading: {
                        required: true,
                        colorCode: true,
                    },
                    general_body: {
                        required: true,
                        colorCode: true,
                    },
                    general_icons: {
                        required: true,
                        colorCode: true,
                    },
                    general_background: {
                        required: true,
                        colorCode: true,
                    },
                    table_body: {
                        required: true,
                        colorCode: true,
                    },
                    table_title: {
                        required: true,
                        colorCode: true,
                    },
                    table_heading: {
                        required: true,
                        colorCode: true,
                    },
                    header_background: {
                        required: true,
                        colorCode: true,
                    },
                    header_text: {
                        required: true,
                        colorCode: true,
                    },
                    navbar_background: {
                        required: true,
                        colorCode: true,
                    },
                    navbar_text: {
                        required: true,
                        colorCode: true,
                    },
                },
                messages: {
                    logo: {
                        required: "Please select a logo image.",
                        extension: "Please select a valid image file (png, jpg, jpeg, gif)."
                    },
                    banner_image: {
                        required: "Please select a banner image.",
                        extension: "Please select a valid image file (png, jpg, jpeg, gif)."
                    },
                    'menu_name[]': {
                        required: "Please select at least one menu item."
                    },
                },
                errorPlacement: function (error, element) {
                    if (error.text() === "" && element.hasClass('select2-hidden-accessible')) {
                        element.next('.select2-container').removeClass('is-invalid');
                        element.next('.select2-container').addClass('is-valid');
                    } else {
                        if (element.hasClass('js-example-basic-multiple')) {
                            error.insertAfter(element.next('.select2-container'));
                        } else if (element.attr("name") === "banner_image" ||
                            element.attr("name") === "button_primary" ||
                            element.attr("name") === "button_secondary" ||
                            element.attr("name") === "general_heading" ||
                            element.attr("name") === "general_body" ||
                            element.attr("name") === "general_icons" ||
                            element.attr("name") === "general_background" ||
                            element.attr("name") === "table_body" ||
                            element.attr("name") === "table_title" ||
                            element.attr("name") === "table_heading" ||
                            element.attr("name") === "header_background" ||
                            element.attr("name") === "header_text" ||
                            element.attr("name") === "navbar_background" ||
                            element.attr("name") === "navbar_text") {
                            error.appendTo(element.closest(".mb-3"));
                        } else {
                            error.insertAfter(element);
                        }
                    }
                }

            });

            $('#submit-form').click(function (event) {
                event.preventDefault();
                let form = $('#theme-store-form');

                let formData = new FormData(form[0]);

                if (form.valid()) {
                    $.ajax({
                        url: "{{route('themes.store')}}",
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
                                window.location.href = "{{ route('themes.index') }}";
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

            $(function () {
                $('#flexSwitchCheckDefault').change(function () {
                    if ($(this).is(':checked')) {
                        $('#defaultThemeModal').modal('show');
                        $('#is_default').val(1);
                    } else {
                        $('#is_default').val(0);
                    }
                });

                $('#confirmDefaultTheme').click(function () {
                    $('#defaultThemeModal').modal('hide');
                });

                $('#cancelDefaultTheme').click(function () {
                    $('#flexSwitchCheckDefault').prop('checked', false);
                    $('#is_default').val(0);
                    $('#defaultThemeModal').modal('hide');
                });
            });

        });
    </script>
@endpush

<style>
    .select2-container--default .select2-selection--multiple {
        border-color: #d9dee3 !important;
    }

    .input-group-addon {
        padding: 0.75rem !important;
    }

    .colorpicker-alpha {
        display: none !important;
    }

</style>
