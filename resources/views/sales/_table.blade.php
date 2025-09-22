<div class="card shadow-sm">
    <div class="card-body">
        <div class="mb-2">
    <input type="search" id="searchInput" class="form-control" placeholder="Search sales...">
</div>

        <table class="table table-bordered table-hover" id="salesTableContainerInner">
            <thead class="table-light">
                <tr>
                    <th>Sale ID</th>
                    <th>Product</th>
                    <th>Unit Price</th>
                    <th>Quantity</th>
                    <th>Total</th>
                    <th>Sale Date</th>
                    <th>Seller</th>
                    <th>Payment Methods</th>
                </tr>
            </thead>
            <tbody>
                @php $grandTotal = 0; @endphp
                @foreach($sales as $sale)
                    @php
                        $saleTotal = $sale->items->sum(fn($item) => $item->price * $item->quantity);
                        $grandTotal += $saleTotal;
                    @endphp
                    @foreach($sale->items as $item)
                        <tr>
                            <td>{{ $sale->id }}</td>
                            <td>{{ $item->product->name ?? 'N/A' }}</td>
                            <td>{{ number_format($item->price, 2) }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->price * $item->quantity, 2) }}</td>
                            <td>{{ $sale->sale_date?->format('Y-m-d H:i') ?? 'N/A' }}</td>
                            <td>{{ $sale->user->name ?? 'N/A' }}</td>
                            <td>
                                @foreach($sale->payments as $payment)
                                    <span class="badge bg-info text-dark">
                                        {{ $payment->method }}: {{ number_format($payment->amount,2) }}
                                    </span>
                                @endforeach
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="4" class="text-end">Grand Total:</th>
                    <th>{{ number_format($grandTotal, 2) }}</th>
                    <th colspan="3"></th>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<script>
document.getElementById('filterForm').addEventListener('submit', function(e) {
    e.preventDefault();

    let formData = new FormData(this);
    let params = new URLSearchParams(formData).toString();

    fetch("{{ route('sales.fetch') }}?" + params)
        .then(res => res.text())
        .then(html => {
            document.querySelector('#salesTableContainer').innerHTML = html;
        });
});

// ✅ Live Search// Search functionality
$('#searchInput').on('keyup', function () {
    var value = $(this).val().toLowerCase();
    $("#salesTableContainerInner tbody tr").filter(function () {
        $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
    });

    // ✅ Update Grand Total for visible rows
    let newTotal = 0;
    $("#salesTableContainerInner tbody tr:visible").each(function () {
        let rowTotal = parseFloat($(this).find('td').eq(4).text().replace(/,/g, '')) || 0;
        newTotal += rowTotal;
    });

    $("#salesTableContainerInner tfoot th").eq(1).text(newTotal.toFixed(2));
});


</script>