<!DOCTYPE html>
<html>
<head>
    <title>Receipt #{{ $sale->id }}</title>
    <style>
        body {
            font-family: monospace;
            width: 80mm;
            margin: 0;
            padding: 5px;
        }
        .header, .footer {
            text-align: center;
        }
        .items {
            width: 100%;
            border-collapse: collapse;
            margin-top: 5px;
        }
        .items th, .items td {
            padding: 2px 0;
        }
        .items th {
            border-bottom: 1px dashed #000;
        }
        .total {
            border-top: 1px dashed #000;
            font-weight: bold;
        }
        .small {
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
    <img src="{{ asset('images/logo.png') }}" alt="Logo" 
         style="width:40mm; height:auto; margin-bottom:3px;">
    
    <p class="small">
        <strong>Date:</strong> {{ \Carbon\Carbon::parse($sale->sale_date)->format('Y-m-d H:i') }} |
        <strong>Cashier:</strong> {{ $sale->user->name }}
    </p>
    
    <p class="small">
        <strong>Cell:</strong> +265 999 67 27 75 | Area 25
    </p>
    
    <hr>
</div>


    <table class="items">
        <thead>
            <tr>
                <th style="text-align:left;">Item</th>
                <th style="text-align:center;">Qty</th>
                <th style="text-align:right;">Price</th>
                <th style="text-align:right;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->items as $item)
            <tr>
                <td>{{ $item->product->name }}</td>
                <td style="text-align:center;">{{ $item->quantity }}</td>
                <td style="text-align:right;">{{ number_format($item->price,2) }}</td>
                <td style="text-align:right;">{{ number_format($item->total,2) }}</td>
            </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr class="total">
                <td colspan="3" style="text-align:right;">Grand Total:</td>
                <td style="text-align:right;">{{ number_format($sale->total_amount,2) }}</td>
            </tr>
            <tr>
                <td colspan="4" class="small"><strong>Payment Method:</strong> {{ $sale->payment_method }}</td>
            </tr>
        </tfoot>
    </table>

    <div class="footer">
        <hr>
        <p class="small">Thank you for your purchase!</p>
    </div>
</body>
</html>
