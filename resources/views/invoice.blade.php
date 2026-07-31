<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invoice - {{ $order->order_number }}</title>
    <style>
        body {
            font-family: 'Inter', system-ui, -apple-system, sans-serif;
            color: #1f2937;
            margin: 0;
            padding: 40px;
            background-color: #f9fafb;
        }
        .invoice-card {
            max-width: 800px;
            margin: 0 auto;
            background: #ffffff;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            border: 1px solid #e5e7eb;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #f3f4f6;
            padding-bottom: 24px;
            margin-bottom: 24px;
        }
        .logo {
            font-size: 24px;
            font-weight: 800;
            color: #111827;
            letter-spacing: -0.05em;
        }
        .logo span {
            color: #d97706;
        }
        .invoice-title {
            text-align: right;
        }
        .invoice-title h1 {
            margin: 0;
            font-size: 28px;
            color: #111827;
        }
        .invoice-title p {
            margin: 4px 0 0 0;
            color: #6b7280;
        }
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 24px;
            margin-bottom: 32px;
        }
        .details-block h3 {
            margin: 0 0 8px 0;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #9ca3af;
        }
        .details-block p {
            margin: 4px 0;
            font-size: 15px;
            line-height: 1.5;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 32px;
        }
        th {
            background-color: #f9fafb;
            color: #374151;
            font-weight: 600;
            text-align: left;
            padding: 12px 16px;
            font-size: 14px;
            border-bottom: 1px solid #e5e7eb;
        }
        td {
            padding: 16px;
            font-size: 15px;
            border-bottom: 1px solid #f3f4f6;
        }
        .text-right {
            text-align: right;
        }
        .total-section {
            display: flex;
            justify-content: flex-end;
        }
        .total-table {
            width: 300px;
            margin-bottom: 0;
        }
        .total-table td {
            border: none;
            padding: 8px 16px;
        }
        .total-row td {
            font-size: 18px;
            font-weight: 700;
            color: #111827;
            border-top: 2px solid #e5e7eb;
            padding-top: 16px;
        }
        .footer {
            text-align: center;
            margin-top: 48px;
            padding-top: 24px;
            border-top: 1px solid #f3f4f6;
            color: #9ca3af;
            font-size: 13px;
        }
        .btn-print {
            display: inline-block;
            margin-bottom: 20px;
            padding: 10px 20px;
            background-color: #111827;
            color: #ffffff;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 14px;
            cursor: pointer;
            border: none;
            transition: background-color 0.2s;
        }
        .btn-print:hover {
            background-color: #374151;
        }
        @media print {
            body {
                background-color: #ffffff;
                padding: 0;
            }
            .invoice-card {
                box-shadow: none;
                border: none;
                padding: 0;
                max-width: 100%;
            }
            .btn-print {
                display: none;
            }
        }
    </style>
</head>
<body>
    <div style="max-width: 800px; margin: 0 auto; text-align: right;">
        <button onclick="window.print()" class="btn-print">Print Invoice</button>
    </div>
    <div class="invoice-card">
        <div class="header">
            <div class="logo">GENTLEMAN<span>.</span></div>
            <div class="invoice-title">
                <h1>INVOICE</h1>
                <p>#{{ $order->order_number }}</p>
            </div>
        </div>

        <div class="details-grid">
            <div class="details-block">
                <h3>Billed To:</h3>
                <p><strong>{{ $order->user->fullName }}</strong></p>
                <p>{{ $order->user->email }}</p>
                @if($order->user->phone)
                    <p>{{ $order->user->phone }}</p>
                @endif
            </div>
            <div class="details-block" style="text-align: right;">
                <h3>Invoice Details:</h3>
                <p><strong>Date:</strong> {{ $order->order_date->format('F d, Y') }}</p>
                <p><strong>Order Status:</strong> {{ ucfirst($order->status->value) }}</p>
                <p><strong>Payment Status:</strong> {{ ucfirst($order->payment_status->value) }}</p>
            </div>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th class="text-right">Price</th>
                    <th class="text-right">Quantity</th>
                    <th class="text-right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->orderItems as $item)
                    <tr>
                        <td>{{ $item->product ? $item->product->name : 'Deleted Product' }}</td>
                        <td class="text-right">${{ number_format($item->unit_price, 2) }}</td>
                        <td class="text-right">{{ $item->quantity }}</td>
                        <td class="text-right">${{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="total-section">
            <table class="total-table">
                <tr>
                    <td>Subtotal</td>
                    <td class="text-right">${{ number_format($order->total, 2) }}</td>
                </tr>
                <tr class="total-row">
                    <td>Total Due</td>
                    <td class="text-right">${{ number_format($order->total, 2) }}</td>
                </tr>
            </table>
        </div>

        @if($order->notes)
            <div style="margin-top: 32px; padding: 16px; background-color: #f9fafb; border-left: 4px solid #d97706; border-radius: 4px;">
                <p style="margin: 0; font-size: 14px; font-style: italic; color: #4b5563;">
                    <strong>Notes:</strong> {{ $order->notes }}
                </p>
            </div>
        @endif

        <div class="footer">
            <p>Thank you for choosing Gentleman Barber Shop!</p>
            <p>If you have any questions about this invoice, please contact support.</p>
        </div>
    </div>
</body>
</html>
