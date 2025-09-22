<table class="table table-hover mb-0 align-middle">
    <thead class="table-light">
        <tr>
            <th>Date</th>
            <th>Category</th>
            <th>Description</th>
            <th class="text-end">Amount (MWK)</th>
            <th>Cashier</th>
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
                    <i class="bi bi-exclamation-circle"></i> No expenses found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>
