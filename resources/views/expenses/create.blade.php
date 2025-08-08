<div class="container py-4">
    <h4 class="text-primary mb-3"><i class="bi bi-plus-circle"></i> Add Expense</h4>

    <form action="{{ route('expenses.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="category" class="form-label">Expense Category</label>
            <input type="text" name="category" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="amount" class="form-label">Amount (MWK)</label>
            <input type="number" name="amount" step="0.01" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description (optional)</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Save Expense</button>
    </form>
</div>
