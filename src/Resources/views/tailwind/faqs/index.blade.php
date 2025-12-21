<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All FAQs</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- DataTables -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.dataTables.css" />

    <!-- Tailwind CDN (for demo/testing only) -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 p-6">

<div class="max-w-7xl mx-auto bg-white p-6 rounded shadow">

    <div class="flex justify-between items-center mb-4">
        <h3 class="text-xl font-semibold">All FAQs</h3>
        <button
            id="showAddEditFaqs"
            class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
            + Add New FAQ
        </button>
    </div>

    <table id="faq-table" class="display w-full">
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

<!-- Add/Edit Modal -->
<div id="addEditFaqs" class="fixed inset-0 bg-black/50 hidden items-center justify-center">
    <div class="bg-white w-full max-w-2xl rounded shadow-lg">
        <div class="flex justify-between items-center px-6 py-4 border-b">
            <h5 class="font-semibold" id="modalTitle">Create New FAQ</h5>
            <button id="closeAddEditFaqs" class="text-gray-500 hover:text-black">&times;</button>
        </div>

        <form id="faqForm" class="p-6 space-y-4">
            <input type="hidden" id="id">
            <input type="hidden" id="_method" value="POST">

            <div>
                <label class="block font-medium mb-1">Question *</label>
                <textarea id="question" name="question"
                    class="w-full border rounded p-2"></textarea>
            </div>

            <div>
                <label class="block font-medium mb-1">Answer *</label>
                <textarea id="answer" name="answer"
                    class="w-full border rounded p-2"></textarea>
            </div>

            <div>
                <label class="block font-medium mb-1">Display Order</label>
                <input type="number" id="faqorder" name="faqorder" value="0"
                    class="w-full border rounded p-2">
            </div>

            <div class="flex items-center gap-2">
                <input type="checkbox" id="status" name="status" value="Active" checked>
                <label for="status">Active</label>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t">
                <button type="submit"
                    class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">
                    Save FAQ
                </button>
                <button type="button" id="cancelModal"
                    class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="confirmationModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center">
    <div class="bg-white p-6 rounded shadow text-center w-full max-w-sm">
        <p class="mb-4">Are you sure you want to delete this FAQ?</p>
        <button id="confirmDeleteBtn"
            class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 mb-2">
            Yes, delete
        </button>
        <button id="cancelDelete"
            class="bg-gray-300 px-4 py-2 rounded hover:bg-gray-400">
            Cancel
        </button>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.0.8/js/dataTables.js"></script>

<script>
$(document).ready(function() {

    const addEditModal = $('#addEditFaqs');
    const confirmationModal = $('#confirmationModal');
    let deleteId = null;

    function openModal(modal) {
        modal.removeClass('hidden').addClass('flex');
    }

    function closeModal(modal) {
        modal.addClass('hidden').removeClass('flex');
    }

    const table = $('#faq-table').DataTable({
        ajax: '/beft/faqs',
        columns: [
            { data: 'question' },
            { data: 'answer' },
            {
                data: 'status',
                render: function(data, type, row) {
                    const cls = data === 'Active'
                        ? 'bg-blue-500'
                        : 'bg-red-500';
                    return `<span class="px-2 py-1 text-white rounded cursor-pointer status-toggle ${cls}" data-id="${row.id}">
                        ${data}
                    </span>`;
                }
            },
            { data: 'faqorder' },
            {
                data: 'id',
                render: data => `
                    <button class="bg-green-600 text-white px-2 py-1 rounded edit-btn" data-id="${data}">Edit</button>
                    <button class="bg-red-600 text-white px-2 py-1 rounded delete-btn" data-id="${data}">Delete</button>
                `
            }
        ]
    });

    $('#showAddEditFaqs').click(() => {
        $('#faqForm')[0].reset();
        $('#id').val('');
        $('#_method').val('POST');
        $('#modalTitle').text('Create New FAQ');
        openModal(addEditModal);
    });

    $('#closeAddEditFaqs, #cancelModal').click(() => closeModal(addEditModal));
    $('#cancelDelete').click(() => closeModal(confirmationModal));

    $('#faq-table').on('click', '.edit-btn', function() {
        const id = $(this).data('id');
        $.get(`/beft/faqs/${id}/edit`, function(data) {
            $('#question').val(data.question);
            $('#answer').val(data.answer);
            $('#faqorder').val(data.faqorder);
            $('#status').prop('checked', data.status === 'Active');
            $('#id').val(data.id);
            $('#_method').val('PUT');
            $('#modalTitle').text('Edit FAQ');
            openModal(addEditModal);
        });
    });

    $('#faq-table').on('click', '.delete-btn', function() {
        deleteId = $(this).data('id');
        openModal(confirmationModal);
    });

    $('#confirmDeleteBtn').click(function() {
        $.ajax({
            url: `/beft/faqs/${deleteId}`,
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            success: () => {
                table.ajax.reload();
                closeModal(confirmationModal);
            }
        });
    });

    $('#faqForm').submit(function(e) {
        e.preventDefault();
        const id = $('#id').val();
        const url = id ? `/beft/faqs/${id}` : '/beft/faqs';
        const method = id ? 'PUT' : 'POST';

        $.ajax({
            url,
            method,
            headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
            data: $(this).serialize(),
            success: () => {
                table.ajax.reload();
                closeModal(addEditModal);
            }
        });
    });

});
</script>

</body>
</html>
