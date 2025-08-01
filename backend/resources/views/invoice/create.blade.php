@extends('layouts.app')

@section('title', $app_setting['name'] . ' | ' . __('Genarate New Invoice'))

@section('content')
    <!-- ****Body-Section***** -->
    <div class="app-main-outer">
        <div class="app-main-inner">
            <div class="page-title-actions px-3 d-flex">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{ __('Genarate New Invoice') }}</li>
                    </ol>
                </nav>
            </div>


            <form action="{{ route('invoice.store') }}" method="POST">
                @csrf

                <div class="row">
                    <div class="col-md-12 my-3">
                        <div class="card">
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-12">
                                        <h6 class="mb-4 p-0 text-primary">
                                            {{ __('New Invoice') }}
                                        </h6>
                                    </div>
                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('Course') }} <span
                                                class="text-danger">*</span></label>
                                        <select id="courseSelect" name="course_id[]" class="select2"
                                            style="width: 100% !important" multiple>
                                            @foreach ($courses as $course)
                                                <option value="{{ $course->id }}"
                                                    data-price="{{ $course->price ? $course->price : $course->regular_price }}">
                                                    {{ $course->title }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-6 mb-3">
                                        <label class="form-label">{{ __('Student') }} <span
                                                class="text-danger">*</span></label>
                                        <select name="user_id" class="select2" style="width: 100% !important">
                                            <option value="" disabled selected>Select Student</option>
                                            @foreach ($users as $user)
                                                <option value="{{ $user->id }}">
                                                    {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">{{ __('Qty') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="qty" id="qty" class="form-control" readonly
                                            value="0">
                                    </div>
                                    <div class="col-md-2 mb-3">
                                        <label class="form-label">{{ __('Discount Type') }}<span
                                                class="text-danger">*</span></label>
                                        <select name="discount_type" class="form-control" id="discountType">
                                            <option>{{ __('None') }}</option>
                                            <option value="percentage">{{ __('Percentage') }}(%)</option>
                                            <option value="flat">
                                                {{ __('Flat') }}({{ $app_setting['currency_symbol'] }})
                                            </option>
                                        </select>
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">{{ __('Discount Amount') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="number" name="discount_amount" id="discountAmount"
                                            class="form-control" value="0">
                                    </div>
                                    <div class="col-md-4 mb-3">
                                        <label class="form-label">{{ __('Total Price') }}<span
                                                class="text-danger">*</span></label>
                                        <input type="text" name="course_price" id="coursePrice" class="form-control"
                                            value="0.00" readonly>
                                    </div>
                                    <div class="col-md-12 mb-3">
                                        <label class="form-label">{{ __('Description') }}<span
                                                class="text-danger">*</span></label>
                                        <textarea name="description" class="form-control" rows="6"></textarea>
                                    </div>

                                    <div class="col-md-12 mb-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" value="1"
                                                id="paymentComplete" name="payment_status">
                                            <label class="form-check-label" for="paymentComplete">
                                                {{ __('Payment Complete') }}
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
        $(document).ready(function() {
            function calculateTotal() {
                let totalPrice = 0;
                let qty = 0;
                let discountType = $('#discountType').val();
                let discountAmount = parseFloat($('#discountAmount').val()) || 0;

                // Calculate base price
                $("#courseSelect option:selected").each(function() {
                    totalPrice += parseFloat($(this).data('price'));
                    qty += 1;
                });

                let discountedPrice = totalPrice;

                if (discountType === "percentage") {
                    // Percentage discount
                    discountedPrice = totalPrice - ((discountAmount / 100) * totalPrice);
                } else if (discountType === "flat") {
                    // Flat discount
                    discountedPrice = totalPrice - discountAmount;
                } else {
                    // No discount
                    discountedPrice = totalPrice;
                    $('#discountAmount').val('');
                }

                // Prevent negative total
                if (discountedPrice < 0) discountedPrice = 0;

                $('#coursePrice').val(discountedPrice.toFixed(2));
                $('#qty').val(qty);
            }

            // Trigger on course select change
            $('#courseSelect').on('change', function() {
                calculateTotal();
            });

            // Trigger on discount amount/type change
            $('#discountAmount, #discountType').on('input change', function() {
                calculateTotal();
            });
        });
    </script>

@endpush
