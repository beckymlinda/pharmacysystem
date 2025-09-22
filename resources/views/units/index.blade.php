<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Units List</h5>
            <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addUnitModal">
                <i class="bi bi-plus-circle"></i> Add Unit
            </button>
        </div>
        <div class="card-body">
            <div id="alertBox"></div>

            <table class="table table-bordered" id="unitsTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Unit Name</th>
                        <th>Short Name</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($units as $unit)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $unit->name }}</td>
                            <td>{{ $unit->short_name }}</td>
                            <td>{{ $unit->created_at->format('Y-m-d') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Unit Modal -->
<div class="modal fade" id="addUnitModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form id="unitForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title">Add Unit</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="unitName" class="form-label">Unit Name</label>
                        <input type="text" name="name" id="unitName" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="shortName" class="form-label">Short Name</label>
                        <input type="text" name="short_name" id="shortName" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Unit</button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- AJAX Script -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function () {
    $('#unitForm').on('submit', function (e) {
        e.preventDefault();

        $.ajax({
            url: "{{ route('units.store') }}",
            method: "POST",
            data: $(this).serialize(),
            success: function (res) {
                if (res.success) {
                    // Add new row to table
                    let rowCount = $('#unitsTable tbody tr').length + 1;
                    $('#unitsTable tbody').append(`
                        <tr>
                            <td>${rowCount}</td>
                            <td>${res.unit.name}</td>
                            <td>${res.unit.short_name}</td>
                            <td>${new Date(res.unit.created_at).toISOString().split('T')[0]}</td>
                        </tr>
                    `);

                    // Show success message
                    $('#alertBox').html(`
                        <div class="alert alert-success alert-dismissible fade show mt-2" role="alert">
                            ${res.message}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    `);

                    // Reset form & close modal
                    $('#unitForm')[0].reset();
                    $('#addUnitModal').modal('hide');
                }
            },
            error: function (xhr) {
                let errors = xhr.responseJSON.errors;
                let errorMsg = '';
                $.each(errors, function (key, value) {
                    errorMsg += value[0] + '<br>';
                });
                $('#alertBox').html(`
                    <div class="alert alert-danger alert-dismissible fade show mt-2" role="alert">
                        ${errorMsg}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                `);
            }
        });
    });
});
</script>
