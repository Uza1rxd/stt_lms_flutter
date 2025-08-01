@extends('layouts.app')

@section('title', $app_setting['name'] . ' | Invoices List')

@section('content')
    <!-- ****Body-Section***** -->
    <div class="app-main-outer">
        <div class="app-main-inner">
            <div class="page-title-actions px-3 d-flex">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Create Invoices</li>
                    </ol>
                </nav>
            </div>
            <div class="row" id="deleteTableItem">
                <div class="col-md-12">
                    <div class="card mb-5">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="d-flex align-items-center gap-2 w-50">
                                    <a href="{{ route('invoice.create') }}" class="float-start btn btn-primary px-4 py-2">
                                        <i class="bi bi-printer-fill"></i> New Invoice</a>
                                    <a href="{{ route('invoice.trash') }}"
                                        class="float-start btn btn-outline-warning px-4 py-2">
                                        <i class="bi bi-arrow-clockwise"></i> Restore Invoice</a>
                                </div>
                                <div class="d-flex justify-content-end mb-3 align-items-center w-100">
                                    <form action="{{ route('invoice.index') }}" method="GET" class="w-25 me-2">
                                        <div class="input-group">
                                            <input type="text" class="form-control" id="inputGroupFile04"
                                                aria-describedby="inputGroupFileAddon04" aria-label="Upload"
                                                placeholder="Search by id or name" name="cat_search"
                                                value="{{ request('cat_search') }}">
                                            <button class="btn btn-outline-primary px-3" type="submit"
                                                id="inputGroupFileAddon04"><i class="bi bi-search"></i></button>
                                        </div>
                                    </form>
                                    <div class="d-flex justify-content-end">
                                        <a href="{{ route('invoice.index') }}" class="px-3">
                                            <i class="bi bi-arrow-counterclockwise"></i> Refresh
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <div class="break"></div>

                            <form class="p-2 rounded-4 shadow-sm bg-white border border-light mb-3">
                                <div class="row g-2 align-items-center">
                                    <!-- Payment Date From -->
                                    <div class="col-md-4">
                                        <div class="card border-0 shadow-sm rounded-3">
                                            <div class="card-body">
                                                <label for="startDate" class="form-label fw-bold text-muted small mb-2">
                                                    <i class="bi bi-calendar-event me-1"></i> From Date
                                                </label>
                                                <input type="date" id="startDate" name="start_date"
                                                    class="form-control border-0 shadow-sm bg-light-subtle"
                                                    value="{{ request('start_date') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Payment Date To -->
                                    <div class="col-md-4">
                                        <div class="card border-0 shadow-sm rounded-3">
                                            <div class="card-body">
                                                <label for="endDate" class="form-label fw-bold text-muted small mb-2">
                                                    <i class="bi bi-calendar-check me-1"></i> To Date
                                                </label>
                                                <input type="date" id="endDate" name="end_date"
                                                    class="form-control border-0 shadow-sm bg-light-subtle"
                                                    value="{{ request('end_date') }}">
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Payment Status -->
                                    <div class="col-md-4">
                                        <div class="card border-0 shadow-sm rounded-3 h-100">
                                            <div class="card-body">
                                                <label class="form-label fw-bold text-muted small mb-2 d-block">
                                                    <i class="bi bi-cash-coin me-1"></i> Payment Status
                                                </label>
                                                <div class="btn-group w-100" role="group">
                                                    <input type="radio" class="btn-check" name="status" id="statusPaid"
                                                        value="1" autocomplete="off"
                                                        {{ request('status') === '1' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-success" for="statusPaid">Paid</label>

                                                    <input type="radio" class="btn-check" name="status" id="statusUnpaid"
                                                        value="0" autocomplete="off"
                                                        {{ request('status') === '0' ? 'checked' : '' }}>
                                                    <label class="btn btn-outline-danger" for="statusUnpaid">Unpaid</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Filter Button -->
                                <div class="d-flex justify-content-end mt-4 mb-2">
                                    <button type="submit" class="btn btn-dark px-5 py-2 rounded-pill">
                                        <i class="bi bi-funnel-fill me-2"></i> Filter Results
                                    </button>
                                </div>
                            </form>


                            <div class="table" id="tableContainer">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><strong>Invoice</strong></th>
                                            <th><strong>Create Date</strong></th>
                                            <th><strong>Payment Date</strong></th>
                                            <th><strong>Course Title</strong></th>
                                            <th><strong>Student Name</strong></th>
                                            <th><strong>Status</strong></th>
                                            <th><strong>Total</strong></th>
                                            <th><strong>Action</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($invoices as $invoice)
                                            <tr>
                                                <td class="tableId py-3" style="width: 10%;">
                                                    {{ $invoice->invoice_token }}
                                                </td>
                                                <td class="tableId py-3" style="width: 10%;">
                                                    {{ $invoice?->created_at ? \Carbon\Carbon::parse($invoice->created_at)->format('d M,Y') : 'N/A' }}
                                                </td>
                                                <td class="tableId py-3" style="width: 10%;">
                                                    {{ $invoice?->payment_at ? \Carbon\Carbon::parse($invoice->payment_at)->format('d M,Y') : 'N/A' }}
                                                </td>
                                                <td class="tableId py-3" style="width: 30%;">
                                                    {{ $invoice->courses->pluck('title')->map(fn($title) => Str::limit($title, 30))->implode(' && ') }}
                                                </td>
                                                <td class="tableId py-3">
                                                    {{ $invoice->user->name }}
                                                </td>
                                                <td>
                                                    @if ($invoice->payment_status == 0)
                                                        <div
                                                            class="statusItem d-flex justify-content-start align-items-center">
                                                            <div class="circleDot animatedPending"></div>
                                                            <div class="statusText">
                                                                <span class="stutsPanding">unpaid</span>
                                                            </div>
                                                        </div>
                                                    @else
                                                        <div
                                                            class="statusItem d-flex justify-content-start align-items-center">
                                                            <div class="circleDot animatedCompleted"></div>
                                                            <div class="statusText">
                                                                <span class="stutsCompleted">paid</span>
                                                            </div>
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="tableId py-3">
                                                    @if ($invoice->total_price)
                                                        @if ($app_setting['currency_position'] == 'Left')
                                                            {{ $app_setting['currency_symbol'] }}{{ $invoice->total_price }}
                                                        @else
                                                            {{ $invoice->total_price }}{{ $app_setting['currency_symbol'] }}
                                                        @endif
                                                    @else
                                                        N/A
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="dropdown">
                                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle"
                                                            type="button" data-bs-toggle="dropdown"
                                                            aria-expanded="false">
                                                            <i class="bi bi-three-dots-vertical"></i>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            @if ($invoice->trashed())
                                                                <li>
                                                                    <a class="dropdown-item drop-item"
                                                                        href="{{ route('invoice.restore', $invoice->id) }}">
                                                                        Restore Invoice
                                                                    </a>
                                                                </li>
                                                            @else
                                                                <li>
                                                                    <a class="dropdown-item drop-item"
                                                                        href="{{ route('invoice.verify', $invoice->invoice_token) }}"
                                                                        target="_blank">
                                                                        View Invoice
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item drop-item"
                                                                        href="javascript:void(0)"
                                                                        onclick="copyToClipboard('{{ route('invoice.verify', $invoice->invoice_token) }}', this)">
                                                                        Copy Invoice
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item drop-item"
                                                                        href="{{ route('invoice.download', $invoice->invoice_token) }}">
                                                                        Download Invoice
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item drop-item"
                                                                        href="{{ route('invoice.edit', $invoice->id) }}">
                                                                        Edit Invoice
                                                                    </a>
                                                                </li>
                                                                <li>
                                                                    <a class="dropdown-item drop-item" href="#"
                                                                        onclick="deleteAction('{{ route('invoice.delete', $invoice->id) }}')">
                                                                        Delete Invoice
                                                                    </a>
                                                                </li>
                                                            @endif
                                                        </ul>
                                                    </div>
                                                </td>

                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">No data found</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                            {{ $invoices->links() }}
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
        .copyUrl {
            background-color: #213448;
            color: #ffffff;
        }

        .input-group .custom-btnCopy {
            background-color: #213448;
            color: #ffffff;
            border: 1px solid #213448;
            font-size: 12px;
            border-radius: 8px !important;
            padding: 0.2rem 0.5rem;
            cursor: pointer;
        }

        .reLink {
            background-color: #213448;
            color: #ffffff;
            border: 1px solid #213448;
            font-size: 12px;
            border-radius: 8px !important;
            padding: 0.2rem 0.5rem;
            cursor: pointer;
        }

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

        function copyToClipboard(elementId, button) {
            navigator.clipboard.writeText(elementId).then(() => {
                // Optional feedback
                button.innerText = "Copied!";
                setTimeout(() => {
                    button.innerText = 'Copy Payment Link';
                }, 1500);
            }).catch(err => {
                console.error('Failed to copy: ', err);
            });
        }
    </script>
@endpush
