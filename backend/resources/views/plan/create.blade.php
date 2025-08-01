@extends('layouts.app')

@section('title', $app_setting['name'] . ' | ' . __('Create New Plan'))

@section('content')
    <!-- ****Body-Section***** -->
    <div class="app-main-outer">
        <div class="app-main-inner">
            <div class="page-title-actions px-3 d-flex">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('New Subscription Plan') }}</li>
                    </ol>
                </nav>
            </div>


            <form action="{{ route('plan.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-12 my-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="mb-4 p-0 text-primary">
                                            {{ __('New Subscription Plan') }}
                                        </h6>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('Course') }} <span
                                                class="text-danger">*</span></label>
                                        <select id="courseSelect" name="course_ids[]" class="select2"
                                            style="width: 100% !important" multiple>
                                            @foreach ($courses as $course)
                                                <option value="{{ $course->id }}">
                                                    {{ $course->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('course_ids')
                                            <p class="text-danger my-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('Plan Title') }} <span
                                                class="text-danger">*</span></label>
                                        <input id="planTitle" type="text" name="title" class="form-control"
                                            maxlength="60" onchange="countPlanTitleChar()"
                                            placeholder="{{ __('Enter Plan Title') }}" value="{{ old('title') }}">
                                        <div class="mt-2">
                                            <strong>{{ __('Characters') }}:
                                                <span id="charCountTitle">0</span>/60
                                            </strong>
                                        </div>
                                        @error('title')
                                            <p class="text-danger my-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('Plan Type') }}<span
                                                class="text-danger">*</span></label>
                                        <select name="plan_type" class="form-control" id="discountType">
                                            <option disabled selected>{{ __('Select Plan Type') }}</option>
                                            <option value="monthly">{{ __('Monthly') }}</option>
                                            <option value="yearly">
                                                {{ __('Yearly') }}
                                            </option>
                                        </select>
                                        @error('plan_type')
                                            <p class="text-danger my-2">{{ $message }}</p>
                                        @enderror
                                    </div>
                                    <div class="col-md-6 mb-3 term-duration-field">
                                        <label class="form-label">{{ __('Duration') }}
                                            (<span class="text-warning"
                                                id="durationText">{{ __('This field accepts numbers only for days.') }}</span>)
                                            <span class="text-danger">*</span></label>
                                        <input id="durationForPlan" type="text" name="duration" class="form-control"
                                            value="{{  old('duration') ?? 0 }}">
                                        @error('duration')
                                            <p class="text-danger my-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('Price') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="price" id="coursePrice" class="form-control"
                                            value="{{ old('price') ?? 0.00 }}">

                                        @error('price')
                                            <p class="text-danger my-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-md-6 mb-3 term-duration-field">
                                        <label class="form-label">{{ __('Course Limit') }}
                                            <span class="text-danger">*</span></label>
                                        <input type="number" name="course_limit" class="form-control" value="{{ old('course_limit') ?? 0 }}">
                                        @error('course_limit')
                                            <p class="text-danger my-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">{{ __('Description') }}<span
                                                class="text-danger">*</span></label>
                                        <div id="texteditorForSubscription" style="height: 200px">
                                            {!! old('description') !!}
                                        </div>
                                        <input type="hidden" id="description" maxlength="80" name="description"
                                            value="{{ old('description') }}">
                                        <div class="mt-2">
                                            <strong>{{ __('Characters') }}:
                                                <span id="charCountDescription">0</span>/80
                                            </strong>
                                        </div>

                                        @error('description')
                                            <p class="text-danger my-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">{{ __('Features') }}<span
                                                class="text-danger">*</span></label>
                                        <div class="row align-items-center" id="input-container">
                                            @if (old('features'))
                                                @foreach (old('features') as $value)
                                                    <div class="col-6">
                                                        <div class="input-group mb-3">
                                                            <input type="text" class="form-control" name="features[]"
                                                                value="{{ $value }}"
                                                                placeholder="Enter Plan Features">
                                                            <button class="btn btn-danger remove-btn" type="button">
                                                                <i class="bi bi-x-circle"></i>
                                                            </button>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                <div class="col-6">
                                                    <div class="input-group mb-3">
                                                        <input type="text" class="form-control" name="features[]"
                                                            placeholder="Enter Plan Features">
                                                        <button class="btn btn-outline-danger remove-btn" type="button">
                                                            <i class="bi bi-x-circle"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            @endif

                                            @error('features')
                                                <p class="text-danger my-2">{{ $message }}</p>
                                            @enderror
                                        </div>
                                        <div class="row">
                                            <div class="col-12">
                                                <button type="button" id="add-input"
                                                    class="btn btn-primary btn-sm mb-3">
                                                    <i class="bi bi-plus-circle"></i> Add Features</button>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1"
                                                id="paymentComplete" name="is_active">
                                            <label class="form-check-label" for="paymentComplete">
                                                {{ __('Active Plan') }}
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-12 d-flex justify-content-between align-items-center mt-5">
                                        <button type="submit"
                                            class="btn btn-primary btn-lg px-5 py-2">{{ __('Create') }}</button>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </form>

            <!-- ****End-Body-Section**** -->
        </div>
    </div>
@endsection

@push('scripts')
    {{-- @if ($errors->any())
        <script>
            let errorMessages = [];
            @foreach ($errors->all() as $error)
                errorMessages.push("{{ $error }}");
            @endforeach
            const Toast = Swal.mixin({
                toast: true,
                position: "bottom-end",
                showConfirmButton: false,
                timer: 5000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.onmouseenter = Swal.stopTimer;
                    toast.onmouseleave = Swal.resumeTimer;
                }
            });
            Toast.fire({
                icon: "error",
                title: errorMessages.join("<br><br>"),
            });
        </script>
    @endif --}}

    <script>
        const quill = new Quill('#texteditorForSubscription', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{
                        'header': [1, 2, 3, 4, 5, 6, false]
                    }],
                    [{
                        'font': []
                    }],
                    ['bold', 'italic', 'underline', 'strike', 'blockquote'],
                    [{
                        'list': 'ordered'
                    }, {
                        'list': 'bullet'
                    }],
                    [{
                        'align': []
                    }],
                    [{
                        'script': 'sub'
                    }, {
                        'script': 'super'
                    }],
                    [{
                        'indent': '-1'
                    }, {
                        'indent': '+1'
                    }],
                    [{
                        'direction': 'rtl'
                    }],
                    [{
                        'color': []
                    }, {
                        'background': []
                    }],
                    ['link', 'image', 'video', 'formula']
                ]
            }
        });

        // quill.on('text-change', function(delta, oldDelta, source) {
        //     document.getElementById('description').value = quill.root.innerHTML;
        // });
        quill.on('text-change', function(delta, oldDelta, source) {
            const plainText = quill.getText().trim();
            const htmlContent = quill.root.innerHTML;

            if (plainText.length > 80) {
                quill.deleteText(80, plainText.length);
                return;
            }

            document.getElementById('description').value = htmlContent;
            document.getElementById('charCountDescription').textContent = plainText.length;
        });
    </script>


    <script>
        $(document).ready(function() {
            $('#add-input').click(function() {
                $('#input-container').append(`
                <div class="col-6">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" name="features[]" placeholder="Enter Plan Features">
                        <button class="btn btn-outline-danger remove-btn" type="button">
                            <i class="bi bi-x-circle"></i>
                        </button>
                    </div>
                </div>
            `);
            });

            $(document).on('click', '.remove-btn', function() {
                $(this).closest('.col-6').remove();
            });
        });
    </script>

    <script>
        $(document).ready(function() {
            $('#discountType').change(function() {
                const selectedValue = $(this).val();
                if (selectedValue === 'yearly') {
                    $('#durationText').text(
                        'Please enter the number of years, e.g., 1 for one year, 2 for two years.');
                    if (selectedValue === 'yearly') {
                        $('#durationForPlan').attr('maxlength', '1');
                    }
                } else {
                    $('#durationText').text(
                        'Please enter the number of days, e.g., 30 for one month, 365 for one year.');
                    if (selectedValue === 'monthly') {
                        $('#durationForPlan').attr('maxlength', '3');
                    }
                }
            });
            // Trigger change on page load to set initial visibility
            $('#discountType').trigger('change');
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const titleInput = document.getElementById('planTitle');
            const charCountDisplay = document.getElementById('charCountTitle');

            function countPlanTitleChar() {
                charCountDisplay.textContent = titleInput.value.length;
            }
            // Attach the event listener to update count in real time
            titleInput.addEventListener('input', countPlanTitleChar);
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const titleInput = document.getElementById('description');
            const charCountDisplay = document.getElementById('charCountDescription');

            function countPlanDescriptionChar() {
                charCountDisplay.textContent = titleInput.value.length;
            }
            // Attach the event listener to update count in real time
            titleInput.addEventListener('input', countPlanDescriptionChar);
        });
    </script>
@endpush
