<div class="container py-4">

    <div class="alert alert-info shadow-sm mb-4 d-flex justify-content-between align-items-center">
    <div>
        <i class="bi bi-cash-stack me-2"></i>
        Total Expenses from <strong>{{ $start->format('d M Y') }}</strong> 
        to <strong>{{ $end->format('d M Y') }}</strong>:
    </div>
    <div class="fw-bold fs-5 text-primary">
        MWK {{ number_format($total, 2) }}
    </div>
</div>

    <!-- Header & Add Button -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="mb-0 text-primary">
            <i class="bi bi-wallet2 me-2"></i> All Expenses
        </h4>
        <a href="#" class="btn btn-sm btn-outline-primary ajax-link" data-url="{{ route('expenses.create') }}">
            <i class="bi bi-plus-circle me-1"></i> Add Expense
        </a>
    </div>

    <!-- Success Message -->
    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-3" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <!-- Expenses Table -->
    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <table class="table table-hover mb-0 align-middle">
                <thead class="table-light">
                    <tr>
                        <th scope="col">Date</th>
                        <th scope="col">Category</th>
                        <th scope="col">Description</th>
                        <th scope="col" class="text-end">Amount (MWK)</th>
                        <th scope="col">Cashier</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($expenses as $expense)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($expense->date)->format('d M Y') }}</td>
                            <td>{{ $expense->category }}</td>
                            <td>{{ $expense->description ?? '-' }}</td>
                            <td class="text-end fw-semibold">MWK {{ number_format($expense->amount, 2) }}</td>
                            <td>{{ $expense->user->name ?? 'N/A' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted py-4">
                                <i class="bi bi-exclamation-circle"></i> No expenses recorded.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
