<h3>Expiry Alerts</h3>

@if($expiring->count())
    <h4>Expiring Soon (Within 30 Days)</h4>
    <ul>
        @foreach($expiring as $item)
            <li>{{ $item->product->name }} - {{ $item->expiry_date->format('d M Y') }}</li>
        @endforeach
    </ul>
@endif

@if($expired->count())
    <h4 style="color:red;">Expired Products</h4>
    <ul>
        @foreach($expired as $item)
            <li>{{ $item->product->name }} - Expired on {{ $item->expiry_date->format('d M Y') }}</li>
        @endforeach
    </ul>
@endif
@else
    <p>No expiry alerts at the moment.</p>