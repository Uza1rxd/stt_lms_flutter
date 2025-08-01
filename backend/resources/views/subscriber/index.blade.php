@extends('layouts.app')

@section('title', $app_setting['name'] . ' | ' . __('Subscriber List'))

@section('content')
    <!-- ****Body-Section***** -->
    <div class="app-main-outer">
        <div class="app-main-inner">
            <div class="page-title-actions px-3 d-flex">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Subscriber Details') }}</li>
                    </ol>
                </nav>
            </div>
            <div class="row" id="deleteTableItem">
                <div class="col-md-12">
                    <div class="card mb-5">
                        <div class="card-body">
                            <button class="float-start btn btn-outline-primary px-4 py-2" type="submit"
                                onclick="printTable()"><i class="bi bi-printer-fill"></i> {{ __('Print') }}</button>
                            <div class=" d-flex justify-content-end mb-3 align-items-center">
                                <form action="{{ route('subscriber.index') }}" method="GET" class="w-25 me-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="inputGroupFile04"
                                            aria-describedby="inputGroupFileAddon04" aria-label="Upload"
                                            placeholder="{{ __('Search') }}" name="search"
                                            value="{{ request('search') }}">
                                        <button class="btn btn-outline-primary px-3" type="submit"
                                            id="inputGroupFileAddon04"><i class="bi bi-search"></i></button>
                                    </div>
                                </form>
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('subscriber.index') }}" class="px-3">
                                        <i class="bi bi-arrow-counterclockwise"></i> {{ __('Reset') }}
                                    </a>
                                </div>
                            </div>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><strong>#</strong></th>
                                            <th><strong>{{ __('ID') }}</strong></th>
                                            <th><strong>{{ __('Student Name') }}</strong></th>
                                            <th><strong>{{ __('Plan Name') }}</strong></th>
                                            <th><strong>{{ __('Plan Type') }}</strong></th>
                                            <th><strong>{{ __('Enrolled Courses') }}</strong></th>
                                            <th><strong>{{ __('Starts At') }}</strong></th>
                                            <th><strong>{{ __('Expires At') }}</strong></th>
                                            <th><strong>{{ __('Price') }}</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($subscribers as $subscriber)
                                            <tr>
                                                <td class="fw-bold">{{ $loop->index + 1 }}</td>
                                                <td>{{ $subscriber->id }}</td>
                                                <td>{{ $subscriber->user?->name }}</td>
                                                <td class="fw-bold text-primary">{{ $subscriber->plan?->title }}</td>
                                                <td>
                                                    <span
                                                        class="badge {{ $subscriber->plan?->plan_type == 'yearly' ? 'bg-warning' : 'bg-success' }}">
                                                        {{ $subscriber->plan?->plan_type }}
                                                    </span>
                                                </td>
                                                <td>
                                                    <button type="button"
                                                        class="btn btn-outline-danger position-relative d-flex align-items-center"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#courseViewModal{{ $subscriber->id }}">
                                                        <i class="bi bi-eye-fill"></i>
                                                        <span
                                                            class="fw-bolder position-absolute top-0 start-100 translate-middle"
                                                            style="font-size: 10px; width: 20px; height: 20px; border-radius: 50%; background: #ff0000; display: flex; justify-content: center; align-items: center; color: #fff">{{ $subscriber->courses->count() }}</span>
                                                    </button>
                                                </td>
                                                <td>{{ \Carbon\Carbon::parse($subscriber->starts_at)->format('d M,Y') }}
                                                </td>
                                                <td class="{{ $subscriber->ends_at < now() ? 'text-danger' : '' }}">
                                                    {{ \Carbon\Carbon::parse($subscriber->ends_at)->format('d M,Y') }}</td>
                                                <td>{{ $app_setting['currency_symbol'] . $subscriber->plan?->price }}</td>
                                            </tr>


                                            {{-- modal design for course --}}
                                            <div class="modal fade" id="courseViewModal{{ $subscriber->id }}"
                                                tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="courseModalLabel">Available Courses
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="table-like-container">
                                                                <div class="table-like">
                                                                    <!-- Header -->
                                                                    <div class="table-like-header">
                                                                        <div>Thumbnail</div>
                                                                        <div>Course Title</div>
                                                                        <div>Instrctor</div>
                                                                    </div>
                                                                    <!-- Rows -->
                                                                    @forelse ($subscriber->courses as $course)
                                                                        <div class="table-like-row">
                                                                            <div>
                                                                                <img src="{{ $course->mediaPath }}"
                                                                                    alt="{{ $course->title }}"
                                                                                    style="width: 50px; height: 50px; border-radius: 50%; object-fit: cover; margin-right: 10px;">
                                                                            </div>
                                                                            <div>
                                                                                {{ $course->title }}
                                                                            </div>
                                                                            <div>
                                                                                {{ $course->instructor?->user?->name }}
                                                                            </div>
                                                                        </div>
                                                                    @empty
                                                                        <div class="table-like-row">
                                                                            <div colspan="3" class="text-center">
                                                                                {{ __('No courses available') }}</div>
                                                                        </div>
                                                                    @endforelse
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-danger"
                                                                data-bs-dismiss="modal">Close</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- modal design for course --}}

                                        @empty
                                            <tr>
                                                <td colspan="9" class="text-center text-danger">
                                                    {{ __('No data found') }}</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
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
        .table-like {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            /* Adjust column widths */
            gap: 0;
            width: 100%;
            border: 1px solid #dee2e6;
            border-radius: 0.25rem;
        }

        .table-like-header,
        .table-like-row {
            display: contents;
            /* Allows child divs to follow grid layout */
        }

        .table-like-header>div {
            background-color: #e9ecef;
            font-weight: bold;
            padding: 0.75rem;
            border-bottom: 1px solid #dee2e6;
            border-right: 1px solid #dee2e6;
        }

        .table-like-row>div {
            padding: 0.75rem;
            border-bottom: 1px solid #dee2e6;
            border-right: 1px solid #dee2e6;
        }

        .table-like-row:nth-child(odd)>div {
            background-color: #f8f9fa;
            /* Mimics table-striped */
        }

        .table-like-row:nth-child(even)>div {
            background-color: #ffffff;
        }

        .table-like-row>div:last-child,
        .table-like-header>div:last-child {
            border-right: none;
            /* Remove right border on last column */
        }

        .table-like-container {
            overflow-x: auto;
            /* Responsive scrolling */
        }
    </style>
@endpush

@push('scripts')
    <script>
        function printTable() {
            const table = document.querySelector('#deleteTableItem .table-responsive');
            const printWindow = window.open('', '', 'width=800,height=600');
            printWindow.document.write(`
        <html>
            <head>
                <title>Print Table</title>
                <style>
                    /* Custom styles for the printed table */
                    body {
                        font-family: Arial, sans-serif;
                        margin: 20px;
                    }
                    table {
                        width: 100%;
                        border-collapse: collapse;
                    }
                    table, th, td {
                        border: 1px solid black;
                    }
                    th, td {
                        padding: 10px;
                        text-align: left;
                    }
                    th {
                        background-color: #f4f4f4;
                    }
                </style>
            </head>
            <body>
                ${table.outerHTML}
            </body>
        </html>
    `);
            printWindow.document.close();
            printWindow.print();
            printWindow.onafterprint = function() {
                printWindow.close();
            };
        }
    </script>
@endpush
