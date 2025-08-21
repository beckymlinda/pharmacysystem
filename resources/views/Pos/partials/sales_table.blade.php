<table class="table table-bordered">
    <thead>
        <tr>
            <th>ID</th>
            <th>Cashier</th>
            <th>Total Amount</th>
            <th>Payment Method</th>
            <th>Sale Date</th>
        </tr>
    </thead>
    <tbody>
        @forelse($sales as $sale)
            <tr>
                <td>{{ $sale->id }}</td>
                <td>{{ $sale->user ? $sale->user->name : 'Unknown' }}</td>
                <td>{{ number_format($sale->total_amount, 2) }}</td>
                <td>{{ $sale->payment_method }}</td>
                <td>{{ optional($sale->sale_date)->format('Y-m-d H:i') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center">No sales found</td>
            </tr>
        @endforelse
    </tbody>
</table>
