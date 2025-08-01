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
    </style>
</head>

<body>
    <div class="invoice-box">
        <table width="100%" style="border-collapse: collapse; margin-bottom: 20px;">
            <tr>
                <td style="width: 70%; vertical-align: top;">
                    <strong>Billed From</strong><br>
                    <span style="font-size: 14px; font-weight: 500; color: #5864ff;">{{ config('app.name') }}</span><br>
                    {{ $setting->footer_contact_number }}<br>
                    {{ $setting->footer_support_mail }}<br>
                    Label :1, House: 1/1 (A&B),<br>
                    Adabor Bazar Road,<br>
                    Mohammadpur, Dhaka - 1207
                </td>
                <td style="width: 30%; text-align: right; vertical-align: top;">
                    <img src="{{ $setting->logoPath }}" alt="Logo" class="logo">
                </td>
            </tr>
        </table>

        <table width="100%" style="border-collapse: collapse; margin-bottom: 20px;">
            <tr>
                <td style="width: 60%; vertical-align: top;">
                    <strong>Billed To</strong><br>
                    {{ $invoice->user->name }}<br>
                    @if ($invoice->user->email)
                        {{ $invoice->user->email }}<br>
                    @endif
                    @if ($invoice->user->phone)
                        {{ $invoice->user->phone }}<br>
                    @endif
                </td>
                <td style="width: 40%; vertical-align: top;">
                    <table width="100%"
                        style="border: 1px solid #ccc; border-collapse: collapse; background-color: #F8FAFC;">
                        <tr>
                            <th colspan="2" style="border: 1px solid #ccc; padding: 6px; text-align: left;">INVOICE
                            </th>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #ccc; padding: 6px;">Invoice Number:</td>
                            <td style="border: 1px solid #ccc; padding: 6px;">#{{ $invoice->invoice_token }}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #ccc; padding: 6px;">Invoice Date:</td>
                            <td style="border: 1px solid #ccc; padding: 6px;">
                                {{ $invoice->created_at->format('d F, Y') }}</td>
                        </tr>
                        <tr>
                            <td style="border: 1px solid #ccc; padding: 6px;">Payment Status:</td>
                            <td style="border: 1px solid #ccc; padding: 6px;">
                                <span style="color: {{ $invoice->payment_status ? '#4CAF50' : '#FF0000' }};">
                                    {{ $invoice->payment_status ? 'Paid' : 'Unpaid' }}</span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

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
                        <td colspan="3">Note: {{ $invoice?->description }}</td>
                    </tr>
                @endif
            </tbody>
        </table>

        <table style="margin-left: auto; width: 300px; margin-top: 20px; border-collapse: collapse; font-size: 14px;">
            <tbody>
                <tr>
                    <td style="padding: 8px; border: 1px solid #ddd;">Sub Total:</td>
                    <td style="padding: 8px; border: 1px solid #ddd;">{{ $subTotal }}</td>
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
                    <td style="padding: 8px; border: 1px solid #ddd;">
                        <span style="font-size: 20px;">{{ $app_setting['currency_symbol'] }}</span><span
                            style="font-weight: bold;">{{ $invoice->total_price }}</span>
                    </td>
                </tr>
            </tbody>
        </table>

        <div class="footer">
            <span>Thank you for your payment. If you have any questions, feel free to contact us.<br>
                Phone: {{ $setting->footer_contact_number }} | Email: {{ $setting->footer_support_mail }}</span>
        </div>

    </div>
</body>

</html>
