 
<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Add New Unit</h5>
        </div>
        <div class="card-body">
            <form action="{{ route('units.store') }}" method="POST">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Unit Name</label>
                    <input type="text" name="name" id="name" class="form-control" placeholder="e.g. Kilogram, Liter, Piece" required>
                </div>

                <div class="mb-3">
                    <label for="short_name" class="form-label">Short Name</label>
                    <input type="text" name="short_name" id="short_name" class="form-control" placeholder="e.g. kg, L, pcs" required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="{{ route('units.index') }}" class="btn btn-secondary">Back</a>
                    <button type="submit" class="btn btn-success">Save Unit</button>
                </div>
            </form>
        </div>
    </div>
</div>

