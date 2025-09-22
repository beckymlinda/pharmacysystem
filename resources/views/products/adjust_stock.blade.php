 
<div class="container">
    <h4>Adjust Stock</h4>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Current Qty</th>
                <th>Unit</th>
                <th>Adjust</th>
                <th>Reason</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
            <tr data-id="{{ $product->id }}">
                <td>{{ $product->name }}</td>
                <td class="current-qty">{{ $product->quantity }}</td>
                <td>{{ $product->unit->short_name ?? '-' }}</td>
                <td><input type="number" class="form-control adjustment" placeholder="e.g. 5 or -3"></td>
                <td><input type="text" class="form-control reason" placeholder="Reason"></td>
                <td>
                    <button class="btn btn-sm btn-primary adjust-btn">Update</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>

<script>
$(document).ready(function(){
    $('.adjust-btn').on('click', function(){
        let row = $(this).closest('tr');
        let productId = row.data('id');
        let adjustment = parseInt(row.find('.adjustment').val());
        let reason = row.find('.reason').val();

        if(!adjustment || !reason) {
            alert('Please enter adjustment and reason.');
            return;
        }

        $.post("{{ route('products.update-stock') }}", {
            _token: "{{ csrf_token() }}",
            product_id: productId,
            adjustment: adjustment,
            reason: reason
        }).done(function(res){
            if(res.success){
                row.find('.current-qty').text(res.new_quantity);
                row.find('.adjustment, .reason').val('');
                alert(res.message);
            }
        }).fail(function(err){
            alert('Failed to update stock.');
        });
    });
});
</script> 
