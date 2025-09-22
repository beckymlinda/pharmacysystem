<div class="container py-4">
    <h4 class="text-primary mb-3"><i class="bi bi-plus-circle"></i> Add Expense</h4>

    <!-- AJAX Expense Form -->
    <form id="expenseForm" action="{{ route('expenses.store') }}" method="POST">
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

        <button type="submit" class="btn btn-primary">
            <i class="bi bi-save"></i> Save Expense
        </button>
    </form>
</div>

<!-- AJAX Script -->
<script>
$(document).off("submit", "#expenseForm").on("submit", "#expenseForm", function(e) {
    e.preventDefault();

    $.ajax({
        url: $(this).attr("action"),
        method: "POST",
        data: $(this).serialize(),
        success: function(response) {
            if (response.success) {
                // Replace content with updated expenses list
                $("#main-content").html(response.html);

                // Show a success alert
                $("<div class='alert alert-success alert-dismissible fade show mt-3' role='alert'>" +
                    response.message +
                    "<button type='button' class='btn-close' data-bs-dismiss='alert'></button>" +
                  "</div>").prependTo("#main-content");
            }
        },
        error: function(xhr) {
            let errors = xhr.responseJSON?.errors;
            let message = "Something went wrong. Please try again.";

            if (errors) {
                message = Object.values(errors).flat().join("<br>");
            }

            $("<div class='alert alert-danger alert-dismissible fade show mt-3' role='alert'>" +
                message +
                "<button type='button' class='btn-close' data-bs-dismiss='alert'></button>" +
              "</div>").prependTo("#main-content");
        }
    });
});
</script>
