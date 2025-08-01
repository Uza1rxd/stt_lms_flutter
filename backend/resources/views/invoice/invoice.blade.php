<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #ffffff;
            padding: 40px;
            color: #303042;
            font-size: 14px;
        }

        .invoice-box {
            max-width: 800px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            border-radius: 12px;
        }

        .header {
            width: 100%;
            display: table;
        }

        .header>div,
        .header>img {
            display: table-cell;
            vertical-align: top;
        }

        .logo {
            width: 100px;
            text-align: right;
        }

        .details {
            margin-top: 20px;
        }

        .details th,
        .details td {
            padding: 8px;
            text-align: left;
        }

        .summary-box {
            float: right;
            margin-top: 20px;
            border: 1px solid #ccc;
            padding: 10px;
        }

        .summary-box table {
            width: 100%;
        }

        .summary-box th,
        .summary-box td {
            padding: 4px 8px;
            text-align: right;
        }

        .items {
            margin-top: 30px;
            width: 100%;
            border-collapse: collapse;
        }

        .items th {
            background-color: #5864ff;
            color: white;
            padding: 6px;
            text-align: left;
        }

        .items td {
            padding: 10px;
            border: 1px solid #eee;
        }

        .total {
            text-align: right;
            margin-top: 20px;
        }

        .bkash-btn-wrapper {
            display: inline-block;
            margin-bottom: 10px;
            vertical-align: top;
        }

        .bkash-btn {
            background-color: white;
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            padding: 6px 12px;
            cursor: pointer;
            display: inline-block;
            vertical-align: middle;
            text-align: center;
            transition: all 0.3s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
        }

        .bkash-btn:hover {
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
            transform: translateY(-1px);
        }

        .bkash-btn img {
            height: 24px;
            margin-right: 6px;
            vertical-align: middle;
        }

        .bkash-btn span {
            font-weight: 600;
            font-size: 12px;
            color: #111827;
            vertical-align: middle;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }

        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 12px;
            color: #777;
        }

        .extra {
            margin-top: 40px;
            text-align: center;
        }

        .custom-dropdown {
            position: relative;
            display: inline-block;
            width: 250px;
        }

        .dropdown-selected {
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 6px;
            background: #fff;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .dropdown-selected img {
            width: 24px;
            height: 24px;
            margin-right: 8px;
        }

        .dropdown-items {
            position: absolute;
            bottom: 100%;
            /* open above */
            left: 0;
            background: white;
            border: 1px solid #ccc;
            border-radius: 6px;
            width: 100%;
            z-index: 10;
            display: none;
            max-height: 200px;
            overflow-y: auto;
            margin-bottom: 6px;
        }

        .dropdown-item {
            padding: 10px;
            display: flex;
            align-items: center;
            cursor: pointer;
            transition: background 0.2s;
        }

        .dropdown-item:hover {
            background: #f1f1f1;
        }

        .dropdown-item img {
            width: 24px;
            height: 24px;
            margin-right: 10px;
        }
    </style>
</head>

<body>
    <div class="invoice-box">
        <div class="header">
            <div style="width: 70%;">
                <strong>Billed From</strong><br>
                <span
                    style="font-size: 14px; font-weight: 500; color: #5864ff; line-height: 20px;">{{ config('app.name') }}</span><br>
                {{ $setting->footer_contact_number }}<br>
                {{ $setting->footer_support_mail }}<br>
                Label :1, House: 1/1 (A&B),<br> Adabor Bazar Road,<br> Mohammadpur, Dhaka - 1207
            </div>
            <div style="width: 30%; text-align: right;">
                <img class="logo" src="{{ $setting->logoPath }}" alt="Logo">
            </div>
        </div>

        <div class="details">
            <div style="float: left;">
                <strong>Billed To</strong><br>
                {{ $invoice->user->name }}<br>
                @if ($invoice->user->email)
                    {{ $invoice->user->email }}<br>
                @endif
                @if ($invoice->user->phone)
                    {{ $invoice->user->phone }}<br>
                @endif
            </div>
            <table style="float: right; border: 1px solid #ccc; border-collapse: collapse; background-color: #F8FAFC;">
                <tr>
                    <th style="border: 1px solid #ccc; padding: 5px;" colspan="2">INVOICE</th>
                </tr>
                <tr>
                    <td style="border: 1px solid #ccc; padding: 5px;">Invoice Number:</td>
                    <td style="border: 1px solid #ccc; padding: 5px;">#{{ $invoice->invoice_token }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ccc; padding: 5px;">Invoice Date:</td>
                    <td style="border: 1px solid #ccc; padding: 5px;">{{ $invoice->created_at->format('d F, Y') }}</td>
                </tr>
                <tr>
                    <td style="border: 1px solid #ccc; padding: 5px;">Payment Status:</td>
                    <td style="border: 1px solid #ccc; padding: 5px;">
                        <span style="color: {{ $invoice->payment_status ? '#4CAF50' : '#FF0000' }};">
                            {{ $invoice->payment_status ? 'Paid' : 'Unpaid' }}</span>
                    </td>
                </tr>
            </table>
            <div style="clear: both;"></div>
        </div>

        <table class="items">
            <thead>
                <tr>
                    <th>Description</th>
                    <th>Quantity</th>
                    <th>Unit Amount (BDT)</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $subTotal = 0;
                @endphp
                @foreach ($invoice->courses as $course)
                    <tr>
                        <td>{{ $course->title }}</td>
                        <td>1</td>
                        <td>{{ $course->price ? $course->price : $course->regular_price }}</td>
                    </tr>
                    @php
                        $subTotal += $course->price ? $course->price : $course->regular_price;
                    @endphp
                @endforeach
                @if ($invoice->description)
                    <tr>
                        <td colspan="5">Note: {{ $invoice?->description }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <table style="margin-left: auto; width: 300px; margin-top: 20px; border-collapse: collapse; font-size: 14px;">
            <tbody>
                <tr>
                    <td style="padding: 8px; border: 1px solid #ddd;">Sub Total:</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $subTotal }} x {{ $invoice->qty }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #ddd;">Discount
                        Amount({{ $invoice->discount_type == 'percentage' ? '%' : '৳' }}{{ $invoice->discount_amount ?? 0 }})
                    </td>
                    <td style="padding: 8px; border: 1px solid #ddd;">
                        @if ($invoice->discount_type == 'percentage')
                            {{ ($subTotal * $invoice->discount_amount) / 100 }}
                        @else
                            {{ $invoice->discount_amount ?? 0 }}
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding: 8px; font-weight: bold; border: 1px solid #ddd;">Grand Total:</td>
                    <td style="padding: 8px; font-weight: bold; border: 1px solid #ddd;">
                        {{ $app_setting['currency_symbol'] }}{{ $invoice->total_price }}
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <span>Thank you for your payment. If you have any questions, feel free to contact us.<br>
                Phone: {{ $setting->footer_contact_number }} | Email: {{ $setting->footer_support_mail }}</span>
        </div>
        <table class="extra no-print" style="width: 100%; margin-top: 40px;">
            <tr>
                <td style="vertical-align: top; text-align: left;">
                    <div class="bkash-btn-wrapper no-print">
                        <a href="{{ route('invoice.download', $invoice->invoice_token) }}" class="bkash-btn"
                            style="text-decoration: none;">
                            <img src="{{ asset('assets/images/icon/download.svg') }}" alt="Download Logo">
                            <span>Download Invoice</span>
                        </a>
                    </div>
                </td>
                <td style="vertical-align: top; text-align: right;">
                    <div class="custom-dropdown" id="paymentDropdown">
                        <div class="dropdown-selected" onclick="toggleDropdown()">
                            <span id="selectedText">Select Payment Method</span>
                            <span>▼</span>
                        </div>
                        <div class="dropdown-items" id="dropdownList">
                            @foreach ($paymentmethods as $method)
                                <div class="dropdown-item"
                                    onclick="selectPaymentMethod('{{ ucfirst($method->name) }}', '{{ asset($method->imagePath) }}'); runPayment({{ $invoice->id }}, {{ $invoice->total_price }}, '{{ $invoice->user }}', '{{ $method->name }}', this)">
                                    <img src="{{ asset($method->imagePath) }}" alt="{{ $method->name }}">
                                    <span>{{ ucfirst($method->name) }} payment</span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </td>
            </tr>
        </table>

    </div>


    <script src="{{ asset('assets/scripts/jquery-3.6.3.min.js') }}"></script>
    <!-- Sweet-Alert-link -->
    <script src="{{ asset('assets/scripts/sweetalert2.min.js') }}"></script>
    <script>
        function runPayment(invoiceID, price, user, gateway, element) {

            const button = element.tagName === "BUTTON" ? element : element.nextElementSibling;
            const loader = button ? button.querySelector('.loader') : null;

            if (loader && button) {
                // Show loader
                loader.classList.remove('d-none');
                button.classList.add('loading');
                button.disabled = true;
            }

            $.ajax({
                url: `/api/payment/invoice/${invoiceID}`,
                type: 'GET',
                data: {
                    payment_gateway: gateway,
                    total_amount: price,
                    user: user
                },
                success: function(response) {
                    if (loader && button) {
                        loader.classList.add('d-none');
                        button.classList.remove('loading');
                        button.disabled = false;
                    }
                    if (response.data.payment_webview_url) {
                        window.open(response.data.payment_webview_url, '_blank');
                    }
                },
                error: function(error) {
                    let errorMessage = error.responseJSON?.message || 'Something Went Wrong';

                    if (loader && button) {
                        loader.classList.add('d-none');
                        button.classList.remove('loading');
                        button.disabled = false;
                    }

                    const Toast = Swal.mixin({
                        toast: true,
                        position: "top-end",
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                        didOpen: (toast) => {
                            toast.onmouseenter = Swal.stopTimer;
                            toast.onmouseleave = Swal.resumeTimer;
                        }
                    });
                    Toast.fire({
                        icon: "error",
                        title: errorMessage,
                    });
                }
            });
        }
    </script>

    <script>
        function toggleDropdown() {
            const dropdown = document.getElementById('dropdownList');
            dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
        }

        function selectPaymentMethod(name, imagePath) {
            const selected = document.getElementById('selectedText');
            selected.innerHTML =
                `<div style="display:flex; align-items:center;"><img src="${imagePath}" style="width: 24px; height: 24px; margin-right: 6px;"> <span>${name}</span></div>`;
            toggleDropdown();
            // You can also trigger an AJAX request here if needed
        }

        document.addEventListener('click', function(e) {
            const dropdown = document.getElementById('paymentDropdown');
            if (!dropdown.contains(e.target)) {
                document.getElementById('dropdownList').style.display = 'none';
            }
        });
    </script>


</body>

</html>
