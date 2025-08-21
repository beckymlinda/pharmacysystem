<!-- Low Stock Modal -->
<div class="modal fade" id="lowStockModal" tabindex="-1">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header bg-warning">
        <h5 class="modal-title">Low Stock Products</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body" id="lowStockModalBody">
        <div class="text-center py-5">
          <div class="spinner-border text-primary"></div>
          <div class="mt-2">Loading...</div>
        </div>
      </div>
      <div class="modal-footer"><button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button></div>
    </div>
  </div>
</div>

<!-- Check Low Stock Button -->
<button id="checkLowStockBtn" class="btn btn-warning">
    <i class="bi bi-exclamation-triangle"></i> Check Low Stock
</button>

<script>
document.getElementById('checkLowStockBtn').addEventListener('click', function () {
    fetch("{{ route('products.check-low-stock') }}")
        .then(response => {
            if(!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            let modalBody = document.getElementById('lowStockModalBody');
            if (data.count > 0) {
                let listHtml = '<ul class="list-group">';
                data.products.forEach(p => {
                    listHtml += `<li class="list-group-item d-flex justify-content-between align-items-center">
                                    ${p.name} <span class="badge bg-danger">Qty: ${p.quantity}</span>
                                 </li>`;
                });
                listHtml += '</ul>';
                modalBody.innerHTML = listHtml;
            } else {
                modalBody.innerHTML = '<p class="text-success">✅ No low stock products.</p>';
            }
            new bootstrap.Modal(document.getElementById('lowStockModal')).show();
        })
        .catch(error => {
            console.error('Error loading low stock products:', error);
            alert("Failed to load low stock products.");
        });
});
</script>
