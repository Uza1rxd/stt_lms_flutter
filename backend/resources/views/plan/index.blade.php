@extends('layouts.app')

@section('title', $app_setting['name'] . ' | ' . __('Subscription List'))

@section('content')
    <!-- ****Body-Section***** -->
    <div class="app-main-outer">
        <div class="app-main-inner">
            <div class="page-title-actions px-3 d-flex align-items-center mb-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Subscription Plans') }}</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-5">
                        <div class="card-body">
                            <div class="d-flex justify-content-start mb-4">
                                <form id="publishForm" action="{{ route('plan.publish') }}" method="POST">
                                    @csrf
                                    <label class="switch switch-primary">
                                        <input type="checkbox" class="switch-input" value="1" name="is_publish"
                                            onchange="$('#publishForm').submit();" {{ $setting->publish_plan ? 'checked' : '' }}>
                                        <span class="switch-toggle-slider">
                                            <span class="switch-on">
                                                <i class="fa fa-check"></i>
                                            </span>
                                            <span class="switch-off">
                                                <i class="fa fa-times"></i>
                                            </span>
                                            <i></i>
                                        </span>
                                        <p class="switch-label text-primary">
                                            {{ __('Publish/Unpublish Plan Base System. If you publish, then full system will be enjoy this plans') }}
                                        </p>
                                    </label>
                                </form>
                            </div>
                            <div class="d-flex justify-content-start mb-3">
                                <div class="position-relative">
                                    <a href="{{ route('plan.trash') }}" class="btn btn-outline-dark">
                                        <i class="bi bi-arrow-clockwise"></i>
                                        {{ __('Trashes') }}
                                    </a>
                                    <div class="countTrash">{{ $totalTrash }}</div>
                                </div>
                            </div>
                            <div class="table">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><strong>#</strong></th>
                                            <th><strong>{{ __('Title') }}</strong></th>
                                            <th><strong>{{ __('Price') }}</strong></th>
                                            <th><strong>{{ __('Type') }}</strong></th>
                                            <th><strong>{{ __('Active Status') }}</strong></th>
                                            <th><strong>{{ __('Action') }}</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($plans as $plan)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td class="tableId fw-bold">{{ $plan->title }}</td>
                                                <td class="tableId fw-bold">{{ currency($plan->price) }}</td>
                                                <td class="tableId">
                                                    <span
                                                        class="badge {{ $plan->plan_type == 'yearly' ? 'bg-warning' : 'bg-success' }}">
                                                        {{ $plan->plan_type }}
                                                    </span>
                                                </td>
                                                <td class="tableId">
                                                    @if (!$plan->is_active)
                                                        <div class="statusItem">
                                                            <div class="circleDot animatedPending"></div>
                                                            <div class="statusText">
                                                                <span class="stutsPanding">{{ __('De-Activated') }}</span>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div class="statusItem">
                                                            <div class="circleDot animatedCompleted"></div>
                                                            <div class="statusText">
                                                                <span class="stutsCompleted">{{ __('Activated') }}</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="tableAction">
                                                    <div class="dropdown">
                                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="dropdown-item drop-item"
                                                                    href="javascript::void(0)" data-bs-toggle="modal"
                                                                    data-bs-target="#planOverview{{ $plan->id }}"
                                                                    target="_blank">
                                                                    {{ __('Details') }}
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item drop-item"
                                                                    href="{{ route('plan.edit', $plan->id) }}">
                                                                    {{ __('Edit') }}
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item drop-item" href="#"
                                                                    onclick="deleteAction('{{ route('plan.delete', $plan->id) }}')">
                                                                    {{ __('Delete') }}
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>


                                            <!-- subscription plan overview modal start -->
                                            <div class="modal fade planOverviewModal" id="planOverview{{ $plan->id }}"
                                                tabindex="-1" aria-labelledby="enrollLabel{{ $plan->id }}"
                                                aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered modal-lg ">
                                                    <div class="modal-content shadow">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="enrollLabel{{ $plan->id }}">
                                                                {{ __('Plan Overview') }} - #{{ $plan->id }}
                                                            </h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="{{ __('Close') }}"></button>
                                                        </div>
                                                        <div class="modal-body bg-white">
                                                            <div class="container">

                                                                <div class="info-row">
                                                                    <div class="info-label">{{ __('Title') }}:
                                                                    </div>
                                                                    <div class="info-value">
                                                                        {{ $plan->title }}</div>
                                                                </div>

                                                                <div class="info-row">
                                                                    <div class="info-label">{{ __('Plan Type') }}:
                                                                    </div>
                                                                    <div class="info-value text-capitalize">
                                                                        <span
                                                                            class="badge {{ $plan->plan_type == 'yearly' ? 'bg-warning' : 'bg-success' }}">
                                                                            {{ $plan->plan_type }}
                                                                        </span>
                                                                    </div>
                                                                </div>

                                                                <div class="info-row">
                                                                    <div class="info-label">{{ __('Price') }}:
                                                                    </div>
                                                                    <div class="info-value">
                                                                        {{ currency($plan->price) }}</div>
                                                                </div>

                                                                <div class="info-row">
                                                                    <div class="info-label">{{ __('Duration') }}:
                                                                    </div>
                                                                    <div class="info-value">
                                                                        {{ $plan?->duration . ' ' . __('Days') ?? 'N/A' }}
                                                                    </div>
                                                                </div>

                                                                <div class="info-row">
                                                                    <div class="info-label">
                                                                        {{ __('Limit of Enroll') }}:
                                                                    </div>
                                                                    <div class="info-value">
                                                                        {{ $plan?->course_limit ?? 'N/A' }}
                                                                    </div>
                                                                </div>


                                                                <div class="mb-2">
                                                                    <div class="info-label mb-2">
                                                                        {{ __('Selected Courses') }}:
                                                                    </div>
                                                                    <div class="info-value border p-3 rounded">
                                                                        <ul class="list-group mb-0 ms-4">
                                                                            @forelse ($plan->courses as $course)
                                                                                <li class="info-value"
                                                                                    style="font-size: 10px;">
                                                                                    {{ $course->title }}
                                                                                </li>
                                                                            @empty
                                                                                <li class="info-value text-danger">
                                                                                    {{ __('No Courses Found') }}!!
                                                                                </li>
                                                                            @endforelse
                                                                        </ul>
                                                                    </div>
                                                                </div>

                                                                <div class="info-row">
                                                                    <div class="info-label">{{ __('Active Status') }}:
                                                                    </div>
                                                                    <div class="info-value">
                                                                        @if (!$plan->is_active)
                                                                            <div class="statusItem">
                                                                                <div class="circleDot animatedPending">
                                                                                </div>
                                                                                <span
                                                                                    class="text-danger fw-bold">{{ __('Deleted') }}
                                                                                </span>
                                                                            </div>
                                                                        @else
                                                                            <div class="statusItem">
                                                                                <div class="circleDot animatedCompleted">
                                                                                </div>
                                                                                <span
                                                                                    class="text-success fw-bold">{{ __('Activeted') }}
                                                                                </span>
                                                                            </div>
                                                                        @endif
                                                                    </div>
                                                                </div>

                                                                <div class="">
                                                                    <div class="info-label mb-2">{{ __('Description') }}:
                                                                    </div>
                                                                    <div class="info-value border p-3 rounded">
                                                                        {!! $plan?->description ?? 'N/A' !!}
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <!-- subscription plan overview modal end -->

                                        @empty
                                            <tr>
                                                <td colspan="7">
                                                    <h5 class="text-danger text-center m-0">
                                                        {{ __('No Plans Available') }}</h5>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{-- {{ $requests->links() }} --}}
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

        .planOverviewModal .modal-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .planOverviewModal .modal-title {
            font-weight: bold;
        }

        .planOverviewModal .info-label {
            font-weight: 600;
            color: #6c757d;
            width: 120px;
        }

        .planOverviewModal .info-value {
            font-weight: 500;
            color: #212529;
        }

        .planOverviewModal .info-row {
            margin-bottom: 1rem;
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .planOverviewModal .statusItem {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .planOverviewModal .circleDot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }

        .planOverviewModal .animatedCompleted {
            background-color: #28a745;
            animation: pulse-green 1.5s infinite;
        }

        .planOverviewModal .animatedPending {
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

        .planOverviewModal .student-img {
            border-radius: 50%;
            object-fit: cover;
            width: 40px;
            height: 40px;
            border: 1px solid #ccc;
        }

        .countTrash {
            position: absolute;
            top: -10px;
            right: -10px;
            width: 20px;
            height: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            background: red;
            border-radius: 50%;
            color: #fff;
            font-size: 10px;
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
