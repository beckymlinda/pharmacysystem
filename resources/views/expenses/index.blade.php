<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="text-primary"><i class="bi bi-wallet2"></i> All Expenses</h4>
        <a href="#" class="btn btn-outline-primary ajax-link" data-url="{{ route('expenses.create') }}">
            <i class="bi bi-plus-circle"></i> Add Expense
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-striped table-bordered shadow-sm">
        <thead class="table-light">
            <tr>
                <th>Date</th>
                <th>Category</th>
                <th>Description</th>
                <th>Amount (MWK)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($expenses as $expense)
                <tr>
                    <td>{{ $expense->date }}</td>
                    <td>{{ $expense->category }}</td>
                    <td>{{ $expense->description ?? '-' }}</td>
                    <td>MWK {{ number_format($expense->amount, 2) }}</td>
                </tr>
            @empty
                <tr><td colspan="4" class="text-center text-muted">No expenses recorded.</td></tr>
            @endforelse
        </tbody>
    </table>
</div>
