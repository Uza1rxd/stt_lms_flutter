@extends('layouts.app')

@section('title', $app_setting['name'] . ' | Course List')

@section('content')

    <!-- ****Body-Section***** -->
    <div class="app-main-outer">
        <div class="app-main-inner">
            <div
                class="page-title-actions px-3 py-3 d-flex justify-content-between align-items-center bg-white rounded mb-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb m-0 p-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Course</li>
                    </ol>
                </nav>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-5">
                        <div class="card-body">
                            <div class=" d-flex justify-content-between mb-3 align-items-center">
                                <div class="d-flex align-items-center gap-2 w-50">
                                    <a href="{{ route('course.create') }}" class="float-start btn btn-primary px-4 py-2">
                                        <i class="fa-solid fa-plus"></i> New Course
                                    </a>
                                    <a href="{{ route('invoice.trash') }}"
                                        class="float-start btn btn-outline-warning px-4 py-2">
                                        <i class="bi bi-arrow-clockwise"></i> Restore Course</a>
                                </div>
                                <div class="d-flex justify-content-end mb-3 align-items-center w-100">
                                    <form action="{{ route('course.index') }}" method="GET" class="w-25 me-2">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="inputGroupFile04"
                                                aria-describedby="inputGroupFileAddon04" aria-label="Upload"
                                                placeholder="Search" name="cat_search" value="{{ request('cat_search') }}">
                                            <button class="btn btn-outline-primary px-3" type="submit"
                                                id="inputGroupFileAddon04"><i class="bi bi-search"></i></button>
                                        </div>
                                    </form>
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('course.index') }}" class="px-3">
                                            <i class="bi bi-arrow-counterclockwise"></i> Refresh
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="table-responsive-xl">
                                <table class="table table-responsive-xl">
                                    <thead>
                                        <tr>
                                            <th><strong>#</strong></th>
                                            <th><strong>ID</strong></th>
                                            <th><strong>Free & Publish</strong></th>
                                            <th><strong>Course</strong></th>
                                            <th><strong>Price</strong></th>
                                            <th><strong>Instructor</strong></th>
                                            <th><strong>Status</strong></th>
                                            <th><strong>Action</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($courses as $course)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    {{ strtoupper($app_setting['name']) }}{{ $course->id }}</td>
                                                <td>
                                                    <div
                                                        class="d-flex
                                    justify-content-start gap-2">
                                                        <input data-bs-toggle="tooltip" data-bs-placement="top"
                                                            data-bs-custom-class="custom-tooltip"
                                                            data-bs-title="{{ $course->is_free ? 'Free' : 'Paid' }}"
                                                            href="#" class="form-check-input" type="radio"
                                                            name="exampleRadios{{ $course->id }}" id="courseFreeRadio"
                                                            onclick="sureAction('{{ route('course.free', $course->id) }}')"
                                                            {{ $course->is_free ? 'checked' : '' }}>
                                                        <span
                                                            class="badge {{ $course->is_free ? 'bg-success' : 'bg-dark' }}">{{ $course->is_free ? 'Free' : 'Paid' }}</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div class="listproduct-section">
                                                        <div class="courseImage">
                                                            <img src="{{ $course->mediaPath }}"
                                                                class="img-fluid w-100 h-100 object-fit-cover rounded-circle">
                                                        </div>
                                                        <div class="product-pera">
                                                            <p class="priceDis">
                                                                @if (strlen($course->title) > 20)
                                                                    {{ substr($course->title, 0, 20) . '...' }}
                                                                @else
                                                                    {{ $course->title }}
                                                                @endif
                                                            </p>
                                                        </div>
                                                    </div>
                                                </td>
                                                <td>
                                                    @if ($app_setting['currency_position'] == 'Left')
                                                        {{ $app_setting['currency_symbol'] }}{{ $course->price ? $course->price : $course->regular_price }}
                                                    @else
                                                        {{ $course->price ? $course->price : $course->regular_price }}{{ $app_setting['currency_symbol'] }}
                                                    @endif
                                                </td>
                                                <td>{{ $course->instructor->user->name }}</td>
                                                <td>
                                                    @if ($course->trashed())
                                                        <div class="statusItem">
                                                            <div class="circleDot animatedPending"></div>
                                                            <div class="statusText">
                                                                <span class="stutsPanding">Deleted</span>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="statusItem">
                                                            <div class="circleDot animatedCompleted"></div>
                                                            <div class="statusText">
                                                                <span class="stutsCompleted">Active</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="dropdown-item drop-item"
                                                                    href="{{ route('course.restore', $course->id) }}">
                                                                    Restore Course
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center text-danger">No data found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $courses->links() }}
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
