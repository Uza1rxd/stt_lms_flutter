@extends('layouts.app')

@section('title', $app_setting['name'] . ' | ' . __('Subscription Trash List'))

@section('content')
    <!-- ****Body-Section***** -->
    <div class="app-main-outer">
        <div class="app-main-inner">
            <div class="page-title-actions px-3 d-flex align-items-center mb-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Plan Trash') }}</li>
                    </ol>
                </nav>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <div class="card mb-5">
                        <div class="card-header">
                            <h4 class="m-0 p-0">
                                {{ __('Plan Trash List') }}
                            </h4>
                        </div>
                        <div class="card-body">
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
                                                                <a class="dropdown-item drop-item" href="#"
                                                                    onclick="restoreAction('{{ route('plan.restore', $plan->id) }}')">
                                                                    {{ __('Restore') }}
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </td>
                                            </tr>

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
    </style>
@endpush
