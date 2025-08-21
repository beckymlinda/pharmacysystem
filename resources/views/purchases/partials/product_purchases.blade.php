<h6 class="mb-3">Purchases for: <strong>{{ $product->name }}</strong></h6>

@if($purchases->isEmpty())
    <div class="alert alert-info">No purchases recorded for this product.</div>
@else
    <div class="table-responsive">
        <table class="table table-sm table-bordered">
            <thead class="table-light">
                <tr>
                    <th>Date</th>
                    <th>Supplier</th>
                    <th>Batch</th>
                    <th>Invoice</th>
                    <th>Qty</th>
                    <th>Price</th>
                    <th>Total Cost</th>
                    <th>Expiry</th>
                </tr>
            </thead>
            <tbody>
                @foreach($purchases as $p)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($p->purchase_date)->format('Y-m-d') }}</td>
                        <td>{{ $p->supplier ?? '-' }}</td>
                        <td>{{ $p->batch_number ?? '-' }}</td>
                        <td>{{ $p->invoice_number ?? '-' }}</td>
                        <td>{{ $p->quantity }}</td>
                        <td>{{ number_format($p->price, 2) }}</td>
                        <td>{{ number_format($p->total_cost, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($p->expiry_date)->format('Y-m-d') }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif
