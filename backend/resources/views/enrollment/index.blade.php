@extends('layouts.app')

@section('title', $app_setting['name'] . ' | ' . __('Enrollment List'))

@section('content')
    <!-- ****Body-Section***** -->
    <div class="app-main-outer">
        <div class="app-main-inner">
            <div class="page-title-actions px-3 d-flex align-items-center mb-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Enrollment') }}</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-5">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-start">

                                <div class="dropdown">
                                    <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="submit"
                                        data-bs-toggle="dropdown">Export
                                        As</button>
                                    <ul class="dropdown-menu dropdown-menu-dark">
                                        <li>
                                            <a class="dropdown-item" href="#" id="exportCSV">CSV File</a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="#" id="exportPDF">PDF File</a>
                                        </li>

                                        <form id="filterFormCSV" action="{{ route('enrollment.exportCSV') }}" method="GET"
                                            target="_blank">
                                            <input type="hidden" name="page" value="{{ request('page') ?? 1 }}" />
                                        </form>

                                        <form id="filterForm" action="{{ route('enrollment.generate.pdf') }}" method="GET"
                                            target="_blank">
                                            <input type="hidden" name="page" value="{{ request('page') ?? 1 }}" />
                                        </form>
                                    </ul>
                                </div>


                                <div class="d-flex justify-content-end mb-3 align-items-center w-100">
                                    <form action="{{ route('enrollment.index') }}" method="GET" class="w-25 me-2">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="inputGroupFile04"
                                                aria-describedby="inputGroupFileAddon04" aria-label="Upload"
                                                placeholder="{{ __('Search') }}" name="cat_search"
                                                value="{{ request('cat_search') }}">
                                            <button class="btn btn-outline-primary px-3" type="submit"
                                                id="inputGroupFileAddon04"><i class="bi bi-search"></i></button>
                                        </div>
                                    </form>
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('enrollment.index') }}" class="px-3">
                                            <i class="bi bi-arrow-counterclockwise"></i> {{ __('Reset') }}
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><strong>#</strong></th>
                                            <th><strong>{{ __('Enroll ID') }}</strong></th>
                                            <th><strong>{{ __('Thumbnail') }}</strong></th>
                                            <th><strong>{{ __('Student') }}</strong></th>
                                            <th><strong>{{ __('Course Title') }}</strong></th>
                                            <th style="width: 15%"><strong>{{ __('Progress') }}</strong></th>
                                            <th><strong>{{ __('Action') }}</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($enrollments as $enrollment)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="tableId">{{ $enrollment->id }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center justify-content-start">
                                                        <img src="{{ $enrollment->user?->profilePicturePath }}"
                                                            alt="image" width="50" height="50"
                                                            style="border-radius: 16px; object-fit: cover">
                                                    </div>
                                                </td>
                                                <td class="tableProduct">{{ $enrollment->user?->name ?? 'N/A' }}</td>
                                                <td class="tableProduct">
                                                    <div class="product-pera">
                                                        <p class="priceDis">
                                                            @if (strlen($enrollment->course?->title) > 30)
                                                                {{ substr($enrollment->course?->title, 0, 30) . '...' }}
                                                            @else
                                                                {{ $enrollment->course?->title ?? 'N/A' }}
                                                            @endif
                                                        </p>
                                                    </div>
                                                </td>
                                                <td class="tableId">
                                                    {{ $enrollment->course_progress ?? 'N/A' }}
                                                    <div class="mb-3 progress">
                                                        <div class="progress-bar bg-danger progress-bar-animated progress-bar-striped"
                                                            role="progressbar"
                                                            aria-valuenow="{{ $enrollment->course_progress }}"
                                                            aria-valuemin="0" aria-valuemax="100"
                                                            style="width: {{ $enrollment->course_progress }}%;">
                                                            {{ $enrollment->course_progress }}%
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            @if ($enrollment->trashed())
                                                                <li>
                                                                    <a class="dropdown-item drop-item"
                                                                        href="{{ route('enrollment.restore', $enrollment->id) }}"
                                                                        target="_blank">
                                                                        Restore Enrollment
                                                                    </a>
                                                                </li>
                                                            @else
                                                                <li>
                                                                    <a class="dropdown-item drop-item"
                                                                        href="javascript::void(0)" data-bs-toggle="modal"
                                                                        data-bs-target="#certificateModal{{ $enrollment->id }}">
                                                                        Certificate Name
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item drop-item"
                                                                        href="javascript::void(0)" data-bs-toggle="modal"
                                                                        data-bs-target="#enrollmentOverview{{ $enrollment->id }}">
                                                                        Details
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item drop-item"
                                                                        href="{{ route('enrollment.suspended', $enrollment->id) }}"
                                                                        target="_blank">
                                                                        Suspend Enrollment
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item drop-item"
                                                                        href="{{ route('enrollment.destroy', $enrollment->id) }}">
                                                                        Delete Enrollment
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>

                                            {{-- certificate name change --}}
                                            <!-- Modal -->
                                            <div class="modal fade" id="certificateModal{{ $enrollment->id }}"
                                                tabindex="-1" aria-labelledby="certificateModalLabel"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="certificateModalLabel">Change
                                                                Student
                                                                Name</h5>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form
                                                                action="{{ route('enrollment.enrollment.update_certificate_name', $enrollment->id) }}"
                                                                method="POST">
                                                                @csrf
                                                                {{-- <input type="hidden" id="studentId"> --}}
                                                                <div class="mb-3">
                                                                    <label for="studentName" class="form-label">Student
                                                                        Name</label>
                                                                    <input type="text" class="form-control"
                                                                        name="student_name" id="studentName"
                                                                        value="{{ $enrollment->certificate_user_name ?? 'N/A' }}">
                                                                </div>
                                                                <div class="d-flex justify-content-end">
                                                                    <button type="submit"
                                                                        class="btn btn-secondary btn-sm">Save
                                                                        Changes</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>


                                            <!-- enrollment overview modal start -->
                                            <div class="modal fade" id="enrollmentOverview{{ $enrollment->id }}"
                                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">
                                                                {{ __('Enroll ID') }} #{{ $enrollment->id }}
                                                            </h1>
                                                            <button type="button" class="btn-close"
                                                                data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="row">
                                                                <div class="col-12 py-1">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="me-3">
                                                                            <h5 class="mb-0">{{ __('Student') }}:</h5>
                                                                        </div>
                                                                        <div>
                                                                            <p
                                                                                class="mb-0 d-flex gap-2 align-items-center">
                                                                                <img src="{{ $enrollment->user?->profilePicturePath }}"
                                                                                    alt="image" width="30"
                                                                                    height="30"
                                                                                    style="border-radius: 50%; object-fit: cover">
                                                                                {{ $enrollment->user->name }}
                                                                            </p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="col-12 py-1">
                                                                    <div class="d-flex align-items-center">
                                                                        <div class="me-3">
                                                                            <h5 class="mb-0">{{ __('Course Title') }}:
                                                                            </h5>
                                                                        </div>
                                                                        <div>
                                                                            <p class="mb-0">
                                                                                {{ $enrollment->course->title }}</p>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            <div class="col-12 py-1">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="me-3">
                                                                        <h5 class="mb-0">{{ __('Progress') }}:</h5>
                                                                    </div>
                                                                    <div>
                                                                        <p class="mb-0">
                                                                            {{ $enrollment->course_progress }}%</p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 py-1">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="me-3">
                                                                        <h5 class="mb-0">{{ __('Course Price') }}:</h5>
                                                                    </div>
                                                                    <div>
                                                                        <p class="mb-0">
                                                                            {{ currency($enrollment->course_price) }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <div class="col-12 py-1">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="me-3">
                                                                        <h5 class="mb-0">{{ __('Transaction Amount') }}:
                                                                        </h5>
                                                                    </div>
                                                                    <div>
                                                                        <p class="mb-0">
                                                                            {{ currency($enrollment?->transactions->first()?->payment_amount ?? 0.0) }}
                                                                        </p>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <div class="col-12 py-1">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="me-3">
                                                                        <h5 class="mb-0">{{ __('Last Activity') }}:</h5>
                                                                    </div>
                                                                    <div>
                                                                        <p class="mb-0">
                                                                            {{ $enrollment->last_activity ?? 'N/A' }}</p>
                                                                    </div>
                                                                </div>
                                                            </div>


                                                            <div class="col-12 py-1">
                                                                <div class="d-flex align-items-center">
                                                                    <div class="me-3">
                                                                        <h5 class="mb-0">{{ __('Status') }}:</h5>
                                                                    </div>
                                                                    <div>
                                                                        @if ($enrollment->trashed())
                                                                            <div class="statusItem">
                                                                                <div class="circleDot animatedPending">
                                                                                </div>
                                                                                <div class="statusText">
                                                                                    <span
                                                                                        class="stutsPanding">{{ __('Deleted') }}</span>
                                                                                </div>
                                                                            </div>
                                                                        @else
                                                                            <div class="statusItem">
                                                                                <div class="circleDot animatedCompleted">
                                                                                </div>
                                                                                <div class="statusText">
                                                                                    <span
                                                                                        class="stutsCompleted">{{ __('Active') }}</span>
                                                                                </div>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- enrollment overview modal end -->
                                        @empty
                                            <tr>
                                                <td colspan="9">
                                                    <h5 class="text-danger text-center m-0">
                                                        {{ __('No Enrollment Available') }}</h5>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $enrollments->links() }}
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
    </style>
@endpush

@push('scripts')
    <script>
        document.getElementById('exportCSV').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('filterFormCSV').submit();
        });

        document.getElementById('exportPDF').addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('filterForm').submit();
        });
    </script>
@endpush
