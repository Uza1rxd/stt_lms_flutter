@extends('layouts.app')

@section('title', $app_setting['name'] . ' | Restore List')

@section('content')
    <!-- ****Body-Section***** -->
    <div class="app-main-outer">
        <div class="app-main-inner">
            <div class="page-title-actions px-3 d-flex">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Restore Invoices</li>
                    </ol>
                </nav>
            </div>
            <div class="row" id="deleteTableItem">
                <div class="col-md-12">
                    <div class="card mb-5">
                        <div class="card-body">
                            <div class=" d-flex justify-content-end mb-3 align-items-center">
                                <form action="{{ route('invoice.trash') }}" method="GET" class="w-25 me-2">
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="inputGroupFile04"
                                            aria-describedby="inputGroupFileAddon04" aria-label="Upload"
                                            placeholder="Search" name="cat_search" value="{{ request('cat_search') }}">
                                        <button class="btn btn-outline-primary px-3" type="submit"
                                            id="inputGroupFileAddon04"><i class="bi bi-search"></i></button>
                                    </div>
                                </form>
                                <div class="d-flex justify-content-end">
                                    <a href="{{ route('invoice.index') }}" class="px-3">
                                        <i class="bi bi-arrow-counterclockwise"></i> Reset
                                    </a>
                                </div>
                            </div>
                            <div class="table">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th><strong>Invoice Id</strong></th>
                                            <th><strong>Course Title</strong></th>
                                            <th><strong>Student Name</strong></th>
                                            <th><strong>Payment Status</strong></th>
                                            <th><strong>Total Amount</strong></th>
                                            <th><strong>Action</strong></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($invoices as $invoice)
                                            <tr>
                                                <td class="tableId py-3" style="width: 15%;">
                                                    {{ $invoice->invoice_token }}
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
                                                            type="button" data-bs-toggle="dropdown" aria-expanded="false">
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
