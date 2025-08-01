@extends('layouts.app')

@section('title', $app_setting['name'] . ' | ' . __('Banner Management'))

@section('content')
    <!-- ****Body-Section***** -->
    <div class="app-main-outer">
        <div class="app-main-inner">
            <div class="page-title-actions px-3 d-flex align-items-center mb-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Banner') }}</li>
                    </ol>
                </nav>
            </div>
            <div class="row mb-3">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body d-flex justify-content-{{ __('rtl') ? 'start' : 'end' }}">
                            <form id="publishForm" action="{{ route('banner.publish') }}" method="POST">
                                @csrf
                                <label class="switch switch-primary">
                                    <input type="checkbox" class="switch-input" value="1" name="is_publish"
                                        onchange="$('#publishForm').submit();" {{ $is_publish ? 'checked' : '' }}>
                                    <span class="switch-toggle-slider">
                                        <span class="switch-on">
                                            <i class="fa fa-check"></i>
                                        </span>
                                        <span class="switch-off">
                                            <i class="fa fa-times"></i>
                                        </span>
                                        <i></i>
                                    </span>
                                    <p class="switch-label">Publish Banner</p>
                                </label>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-5">
                        <div class="card-body">
                            {{-- start --}}
                            <div class="row g-4">
                                <!-- Left Column: Class Input -->
                                <div class="col-12 col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-info text-white">
                                            <h6 class="mb-0">{{ __('Banner List') }}</h6>
                                        </div>
                                        <div class="card-body p-2">
                                            <div class="table-responsive">
                                                <table class="table table-sm table-bordered mb-0">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 10%">#</th>
                                                            <th style="width: 20%">{{ __('Thumbnail') }}</th>
                                                            <th style="width: 40%">{{ __('Title') }}</th>
                                                            <th style="width: 20%">{{ __('Status') }}</th>
                                                            <th style="width: 10%">{{ __('Action') }}</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @forelse ($banners as $banner)
                                                            <tr>
                                                                <td>{{ $banners->firstItem() + $loop->index }}</td>
                                                                <td>
                                                                    <img style="width: 200px; height: 100px; border-radius: 5px; object-fit: cover;"
                                                                        src="{{ $banner->thumbnail }}"
                                                                        alt="banner thumbnail">
                                                                </td>
                                                                <td>{{ $banner->title }}</td>
                                                                <td>
                                                                    @if (!$banner->is_active)
                                                                        <div class="statusItem">
                                                                            <div class="circleDot animatedPending"></div>
                                                                            <div class="statusText">
                                                                                <span
                                                                                    class="stutsPanding">{{ __('Pending') }}</span>
                                                                            </div>
                                                                        </div>
                                                                    @else
                                                                        <div class="statusItem">
                                                                            <div class="circleDot animatedCompleted"></div>
                                                                            <div class="statusText">
                                                                                <span
                                                                                    class="stutsCompleted">{{ __('Active') }}</span>
                                                                            </div>
                                                                        </div>
                                                                    @endif
                                                                </td>
                                                                <td>
                                                                    <div class="dropdown">
                                                                        <button
                                                                            class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                                                            type="button" data-bs-toggle="dropdown"
                                                                            aria-expanded="false">
                                                                            <i class="bi bi-three-dots-vertical"></i>
                                                                        </button>
                                                                        <ul class="dropdown-menu">
                                                                            <li>
                                                                                <a class="dropdown-item drop-item"
                                                                                    href="javascript::void(0)"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#bannerOverview{{ $banner->id }}"
                                                                                    target="_blank">
                                                                                    {{ __('Details') }}
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a class="dropdown-item drop-item"
                                                                                    href="javascript::void(0)"
                                                                                    data-bs-toggle="modal"
                                                                                    data-bs-target="#bannerEdit{{ $banner->id }}"
                                                                                    target="_blank">
                                                                                    {{ __('Edit') }}
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a class="dropdown-item drop-item"
                                                                                    href="#"
                                                                                    onclick="deleteAction('{{ route('banner.delete', $banner->id) }}')">
                                                                                    {{ __('Delete') }}
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </td>
                                                            </tr>

                                                            {{-- edit banner modal --}}

                                                            <!-- Modal -->
                                                            <div class="modal fade" id="bannerEdit{{ $banner->id }}"
                                                                tabindex="-1" aria-labelledby="bannerEditLabel"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered modal-lg">
                                                                    <div class="modal-content">
                                                                        <div class="modal-header">
                                                                            <h1 class="modal-title fs-5"
                                                                                id="bannerEditLabel">
                                                                                {{ __('Edit Banner') }}</h1>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="Close"></button>
                                                                        </div>
                                                                        <div class="modal-body">
                                                                            <form
                                                                                action="{{ route('banner.update', $banner->id) }}"
                                                                                method="POST"
                                                                                enctype="multipart/form-data">
                                                                                @csrf
                                                                                <div class="mb-3">
                                                                                    <label for="className"
                                                                                        class="form-label">{{ __('Banner Title') }}</label>
                                                                                    <input type="text"
                                                                                        class="form-control" id="className"
                                                                                        name="title"
                                                                                        placeholder="Enter Banner Title"
                                                                                        value="{{ $banner->title }}">
                                                                                </div>

                                                                                <div class="mb-3">
                                                                                    <label for="className"
                                                                                        class="form-label">{{ __('Banner Thumbnail') }}</label>
                                                                                    <input type="file"
                                                                                        class="form-control"
                                                                                        id="className" name="thumbnail"
                                                                                        value="{{ old('thumbnail') }}">
                                                                                </div>

                                                                                <div class="mb-3">
                                                                                    <label for="className"
                                                                                        class="form-label">{{ __('Banner Description') }}</label>
                                                                                    <div id="texteditorForSubscriptionBannerUpdate_{{ $banner->id }}"
                                                                                        style="height: 200px">
                                                                                        {!! $banner->description !!}
                                                                                    </div>
                                                                                    <input type="hidden"
                                                                                        id="descriptionUpdate_{{ $banner->id }}"
                                                                                        name="description"
                                                                                        value="{{ $banner->description }}">
                                                                                </div>

                                                                                <div class="form-check mb-3">
                                                                                    <input class="form-check-input"
                                                                                        type="checkbox" value="1"
                                                                                        id="is_active" name="is_active"
                                                                                        {{ $banner->is_active ? 'checked' : '' }}>
                                                                                    <label class="form-check-label"
                                                                                        for="is_active">
                                                                                        {{ __('Is Active') }}
                                                                                    </label>
                                                                                </div>

                                                                                <button type="submit"
                                                                                    class="btn btn-primary">{{ __('Update Banner') }}</button>
                                                                            </form>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            {{-- edit banner modal --}}

                                                            <!-- banner overview modal start -->
                                                            <div class="modal fade bannerOverviewModal"
                                                                id="bannerOverview{{ $banner->id }}" tabindex="-1"
                                                                aria-labelledby="enrollLabel{{ $banner->id }}"
                                                                aria-hidden="true">
                                                                <div class="modal-dialog modal-dialog-centered modal-lg ">
                                                                    <div class="modal-content shadow">
                                                                        <div class="modal-header">
                                                                            <h5 class="modal-title"
                                                                                id="enrollLabel{{ $banner->id }}">
                                                                                {{ __('Subscription Overview') }} -
                                                                                #{{ $banner->id }}
                                                                            </h5>
                                                                            <button type="button" class="btn-close"
                                                                                data-bs-dismiss="modal"
                                                                                aria-label="{{ __('Close') }}"></button>
                                                                        </div>
                                                                        <div class="modal-body bg-white">
                                                                            <div class="container">

                                                                                <div class="info-row">
                                                                                    <div class="info-label">
                                                                                        {{ __('Thumbnail') }}:</div>
                                                                                    <div
                                                                                        class="info-value d-flex align-items-center gap-2">
                                                                                        <img src="{{ $banner->thumbnail }}"
                                                                                            alt="banner Image"
                                                                                            style="width: 200px; height: 100px; border-radius: 5px; object-fit: cover;">
                                                                                    </div>
                                                                                </div>

                                                                                <div class="info-row">
                                                                                    <div class="info-label">
                                                                                        {{ __('Title') }}:
                                                                                    </div>
                                                                                    <div class="info-value">
                                                                                        {{ $banner->title }}</div>
                                                                                </div>

                                                                                <div class="info-row">
                                                                                    <div class="info-label">
                                                                                        {{ __('Active Status') }}:
                                                                                    </div>
                                                                                    <div class="info-value">
                                                                                        @if (!$banner->is_active)
                                                                                            <div class="statusItem">
                                                                                                <div
                                                                                                    class="circleDot animatedPending">
                                                                                                </div>
                                                                                                <span
                                                                                                    class="text-danger fw-bold">{{ __('Inactive') }}</span>
                                                                                            </div>
                                                                                        @else
                                                                                            <div class="statusItem">
                                                                                                <div
                                                                                                    class="circleDot animatedCompleted">
                                                                                                </div>
                                                                                                <span
                                                                                                    class="text-success fw-bold">{{ __('Active') }}</span>
                                                                                            </div>
                                                                                        @endif
                                                                                    </div>
                                                                                </div>

                                                                                <div class="">
                                                                                    <div class="info-label mb-3">
                                                                                        {{ __('Description') }}:
                                                                                    </div>
                                                                                    <div
                                                                                        class="info-value d-flex align-items-center gap-2 border rounded p-3">
                                                                                        {!! $banner?->description ?? 'N/A' !!}
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!-- banner overview modal end -->
                                                        @empty
                                                            <tr>
                                                                <td colspan="5"
                                                                    class="text-center text-danger fw-bold">
                                                                    {{ __('No Banner Found') }}
                                                                </td>
                                                            </tr>
                                                        @endforelse
                                                        <!-- Dynamic rows go here -->
                                                    </tbody>

                                                    {{ $banners->links() }}
                                                </table>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Right Column: Level Input & Info -->
                                <div class="col-12 col-md-6">
                                    <div class="card">
                                        <div class="card-header bg-secondary">
                                            <h5 class="mb-0 text-white">{{ __('Add New Banner') }}</h5>
                                        </div>
                                        <div class="card-body">
                                            <form action="{{ route('banner.store') }}" method="POST"
                                                enctype="multipart/form-data">
                                                @csrf
                                                <div class="mb-3">
                                                    <label for="className"
                                                        class="form-label">{{ __('Banner Title') }}</label>
                                                    <input type="text" class="form-control" id="className"
                                                        name="title" placeholder="Enter Banner Title"
                                                        value="{{ old('title') }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="className"
                                                        class="form-label">{{ __('Banner Thumbnail') }}</label>
                                                    <input type="file" class="form-control" id="className"
                                                        name="thumbnail" value="{{ old('thumbnail') }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label for="className"
                                                        class="form-label">{{ __('Banner Description') }}</label>
                                                    <div id="texteditorForSubscriptionBanner" style="height: 200px">
                                                        {!! old('description') !!}
                                                    </div>
                                                    <input type="hidden" id="description" name="description"
                                                        value="{{ old('description') }}">
                                                </div>

                                                <div class="form-check mb-3">
                                                    <input class="form-check-input" type="checkbox" value="1"
                                                        id="is_active" name="is_active">
                                                    <label class="form-check-label" for="is_active">
                                                        {{ __('Is Active') }}
                                                    </label>
                                                </div>

                                                <button type="submit"
                                                    class="btn btn-primary">{{ __('Add Banner') }}</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- end --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- ****End-Body-Section**** -->
    </div>
@endsection


@push('styles')
    <style>
        .drop-item {
            color: #213448;
            cursor: pointer;
            font-size: 12px;
            padding: 0.5rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .drop-item:hover {
            background-color: #213448;
            color: #ffffff;
        }

        .break {
            border-top: 1px solid #f1f1f1;
            margin: 20px 0;
            width: 100%;
        }

        .bannerOverviewModal .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .bannerOverviewModal .modal-title {
            font-weight: bold;
        }

        .bannerOverviewModal .info-label {
            font-weight: 600;
            color: #6c757d;
            width: 150px;
        }

        .bannerOverviewModal .info-value {
            font-weight: 500;
            color: #212529;
        }

        .bannerOverviewModal .info-row {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .bannerOverviewModal .statusItem {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .bannerOverviewModal .circleDot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .bannerOverviewModal .animatedCompleted {
            background-color: #28a745;
            animation: pulse-green 1.5s infinite;
        }

        .bannerOverviewModal .animatedPending {
            background-color: #dc3545;
            animation: pulse-red 1.5s infinite;
        }

        @keyframes pulse-green {
            0% {
                box-shadow: 0 0 0 0 rgba(40, 167, 69, 0.5);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(40, 167, 69, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(40, 167, 69, 0);
            }
        }

        @keyframes pulse-red {
            0% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0.5);
            }

            70% {
                box-shadow: 0 0 0 10px rgba(220, 53, 69, 0);
            }

            100% {
                box-shadow: 0 0 0 0 rgba(220, 53, 69, 0);
            }
        }

        .bannerOverviewModal .student-img {
            border-radius: 50%;
            object-fit: cover;
            width: 40px;
            height: 40px;
            border: 1px solid #ccc;
        }


        .switch {
            margin-right: .75rem;
            position: relative;
            vertical-align: middle;
            margin-bottom: 0;
            display: inline-block;
            border-radius: 30rem;
            cursor: pointer;
            min-height: 1.35rem;
            font-size: .9375rem;
            line-height: 1.4;
            width: 100% !important;
            display: flex;
            align-items: center;
        }

        .switch-input {
            opacity: 0;
            position: absolute;
            padding: 0;
            margin: 0;
            z-index: -1;
        }

        .switch-primary.switch .switch-input:checked~.switch-toggle-slider {
            background: #7367f0;
            color: #fff;
            box-shadow: 0 2px 6px 0 rgba(115, 103, 240, .3);
        }

        .switch-input:checked~.switch-toggle-slider {
            background: #7367f0;
            color: #fff;
            box-shadow: 0 2px 6px 0 rgba(115, 103, 240, .3);
        }

        .switch .switch-toggle-slider {
            width: 2.5rem;
            height: 1.35rem;
            font-size: .625rem;
            line-height: 1.35rem;
            border: 1px solid rgba(0, 0, 0, 0);
            top: 50%;
            transform: translateY(-50%);
        }

        .switch-toggle-slider {
            position: absolute;
            overflow: hidden;
            border-radius: 30rem;
            background: #eaeaec;
            color: rgba(47, 43, 61, .4);
            transition-duration: .2s;
            transition-property: left, right, background, box-shadow;
            cursor: pointer;
            user-select: none;
            box-shadow: 0 0 .25rem 0 rgba(0, 0, 0, .16) inset;
        }

        .switch-input:checked~.switch-toggle-slider .switch-on {
            left: 0;
        }

        .switch .switch-on {
            padding-left: .25rem;
            padding-right: 1.1rem;
        }

        .switch-on {
            left: -100%;
        }

        .switch-off,
        .switch-on {
            height: 100%;
            width: 100%;
            text-align: center;
            position: absolute;
            top: 0;
            transition-duration: .2s;
            transition-property: left, right;
        }

        .switch .switch-toggle-slider i {
            position: relative;
            font-size: .9375rem;
            top: -1.35px;
        }

        .switch-input:checked~.switch-toggle-slider .switch-off {
            left: 100%;
            color: rgba(0, 0, 0, 0);
        }

        .switch .switch-off {
            padding-left: 1.1rem;
            padding-right: .25rem;
        }

        .switch-off {
            left: 0;
        }

        .switch-off,
        .switch-on {
            height: 100%;
            width: 100%;
            text-align: center;
            position: absolute;
            top: 0;
            transition-duration: .2s;
            transition-property: left, right;
        }

        .switch .switch-input~.switch-label {
            padding-left: 3rem;
        }

        .switch .switch-label {
            top: .01875rem;
        }

        .switch-label {
            display: inline-block;
            font-weight: 400;
            color: #444050;
            position: relative;
            cursor: default;
        }

        .switch .switch-input:checked~.switch-toggle-slider::after {
            left: 1.05rem;
        }

        .switch .switch-toggle-slider::after {
            margin-left: .25rem;
            width: 14px;
            height: 14px;
        }

        .switch-toggle-slider::after {
            content: "";
            position: absolute;
            left: 0;
            display: block;
            border-radius: 999px;
            background: #fff;
            box-shadow: 0 .0625rem .375rem 0 rgba(47, 43, 61, .1);
            transition-duration: .2s;
            transition-property: left, right, background;
        }

        .switch-toggle-slider::after {
            top: 50%;
            transform: translateY(-50%);
        }

        [dir="rtl"] .switch .switch-toggle-slider::after {
            left: auto;
            right: 0;
        }

        [dir="rtl"] .switch .switch-toggle-slider {
            direction: rtl;
        }

        [dir="rtl"] .switch .switch-toggle-slider .switch-on {
            left: auto;
            right: 0;
        }

        [dir="rtl"] .switch .switch-toggle-slider .switch-off {
            left: 0;
            right: auto;
        }

        [dir="rtl"] .switch .switch-toggle-slider i {
            left: auto;
            right: 0;
        }

        [dir="rtl"] .switch .switch-label {
            padding-left: 0;
            padding-right: 3rem !important;
        }
    </style>
@endpush


@push('scripts')
    @if ($errors->any())
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
    @endif

    <script>
        const quill = new Quill('#texteditorForSubscriptionBanner', {
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

        quill.on('text-change', function(delta, oldDelta, source) {
            document.getElementById('description').value = quill.root.innerHTML;
        });


        document.querySelectorAll('[id^="texteditorForSubscriptionBannerUpdate_"]').forEach(function(editorDiv) {
            const idSuffix = editorDiv.id.split('_').pop();
            const quill = new Quill('#' + editorDiv.id, {
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

            quill.on('text-change', function(delta, oldDelta, source) {
                document.getElementById('descriptionUpdate_' + idSuffix).value = quill.root.innerHTML;
            });
        });
    </script>
@endpush
