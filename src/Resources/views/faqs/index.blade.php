<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All FAQs</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="p-4">

<div class="container">

    <div class="d-flex justify-content-between">
        <h3>All FAQs</h3>
        <button type="button" class="btn btn-primary mb-3" id="showAddEditFaqs">+ Add New FAQ</button>
    </div>
    <table id="faq-table" class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Question</th>
                <th>Answer</th>
                <th>Status</th>
                <th>Order</th>
                <th class="text-center">Action</th>
            </tr>
        </thead>
    </table>
</div>

<!-- Add/Edit FAQ Modal -->
<div class="modal fade" id="addEditFaqs" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="modalTitle">Create New FAQ</h5>
            <button type="button" class="btn-close" id="closeAddEditFaqs"></button>
        </div>
        <form id="faqForm">
            <input type="hidden" id="id">
            <input type="hidden" id="_method" value="POST">
            <div class="modal-body">
                <div class="mb-3">
                    <label for="question" class="form-label">Question *</label>
                    <textarea class="form-control" id="question" name="question" rows="2"></textarea>
                </div>
                <div class="mb-3">
                    <label for="answer" class="form-label">Answer *</label>
                    <textarea class="form-control" id="answer" name="answer" rows="3"></textarea>
                </div>
                <div class="mb-3">
                    <label for="faqorder" class="form-label">Display Order</label>
                    <input type="number" class="form-control" id="faqorder" name="faqorder" value="0" min="0">
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="status" name="status" value="Active" checked>
                    <label class="form-check-label" for="status">Active</label>
                </div>
            </div>
            <div class="modal-footer">
                <button type="submit" class="btn btn-primary">Save FAQ</button>
                <button type="button" class="btn btn-secondary" id="cancelModal">Cancel</button>
            </div>
        </form>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="confirmationModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content text-center p-3">
            <p>Are you sure you want to delete this FAQ?</p>
            <button type="button" class="btn btn-danger mt-2 mb-2" id="confirmDeleteBtn">Yes, delete</button>
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
$(document).ready(function() {
    const addEditModal = new bootstrap.Modal(document.getElementById('addEditFaqs'));
    const confirmationModal = new bootstrap.Modal(document.getElementById('confirmationModal'));
    let deleteId = null;

    const table = $('#faq-table').DataTable({
        ajax: '/beft/faqs', // replace with your API endpoint
        columns: [
            { data: 'question' },
            { data: 'answer' },
            // { data: 'status' },
            { data: 'status', render: function(data, type, row) {
                    const statusClass = data == 'Active' ? 'btn-info' : 'btn-danger';
                    return `<span class="status-toggle btn btn-sm ${statusClass}" style="cursor:pointer" data-id="${row.id}">${data}</span>`;
                }
            },
            { data: 'faqorder' },
            { data: 'id', render: data => `
                <button class="btn btn-sm btn-success edit-btn" data-id="${data}">Edit</button>
                <button class="btn btn-sm btn-danger delete-btn" data-id="${data}">Delete</button>
            ` }
        ]
    });
    $('#faq-table').on('click', '.status-toggle', function() {
        const id = $(this).data('id');
        $.ajax({
            url: `/beft/faqs/${id}/toggle-status`,
            method: 'GET', // your route is GET
            success: function(res) {
                table.ajax.reload();
            },
            error: function() {
                alert("Something went wrong");
            }
        });
    });
    $('#showAddEditFaqs').click(function() {
        $('#faqForm')[0].reset();
        $('#_method').val('POST');
        $('#id').val('');
        $('#status').prop('checked', true);
        $('#modalTitle').text("Create New FAQ");
        addEditModal.show();
    });

    $('#closeAddEditFaqs, #cancelModal').click(() => addEditModal.hide());

    $('#faq-table').on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        $.get(`/beft/faqs/${id}/edit`, function(data) {
            console.log(data);
            $('#question').val(data.question);
            $('#answer').val(data.answer);
            $('#faqorder').val(data.faqorder);
            $('#status').prop('checked', data.status === 'Active');
            $('#id').val(data.id);
            $('#_method').val('PUT');
            $('#modalTitle').text("Edit FAQ");
            addEditModal.show();
        });
    });

    $('#faq-table').on('click', '.delete-btn', function() {
        deleteId = $(this).data('id');
        confirmationModal.show();
    });

    $('#confirmDeleteBtn').click(function() {
        $.ajax({
            url: `/beft/faqs/${deleteId}`,
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: () => { table.ajax.reload(); confirmationModal.hide(); }
        });
    });

    $('#faqForm').submit(function(e) {
        e.preventDefault();
        const id = $('#id').val();
        const url = id ? `/beft/faqs/${id}` : '/beft/faqs';
        const method = id ? 'PUT' : 'POST';

        $.ajax({
            url: url,
            method: method,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: $(this).serialize(),
            success: () => {
                table.ajax.reload();
                addEditModal.hide();
            }
        });
    });
});
</script>

</body>
</html>
