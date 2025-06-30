<!-- Main Wrapper -->
<div id="main-wrapper" class="main-wrapper">
    <!-- Header -->
    <header class="bg-white border-b-2 border-black p-4">
        <div class="breadcrumb">
            <span class="text-gray-500">Dashboard</span>
            <span class="mx-2">/</span>
            <span class="font-semibold">Intent Responses</span>
        </div>
    </header>

    <!-- Main Content -->
    <main class="p-6">
        <div class="mb-6">
            <h1 class="handwriting text-4xl font-bold mb-4">Intent Response Management</h1>
            <button id="addNewIntent" class="bg-green-500 text-white px-4 py-2 border-2 border-black rounded-lg hover:bg-green-600 transition-colors">
                <i class="fas fa-plus mr-2"></i>Add New Intent
            </button>
        </div>

        <div class="table-wrapper">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Intent</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Responses</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Updated At</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php if (!empty($responses)): ?>
                            <?php $colors = ['blue', 'green', 'purple', 'yellow', 'indigo', 'pink', 'red', 'cyan', 'teal', 'orange']; ?>
                            <?php foreach ($responses as $key => $response): ?>
                                <?php $color = $colors[$key % count($colors)]; ?>
                                <tr data-id="<?= $response->id ?>">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="bg-<?= $color ?>-100 text-<?= $color ?>-800 px-3 py-1 rounded-full text-sm font-medium">
                                                <?= htmlspecialchars($response->intent) ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900 max-w-xs truncate">
                                            <?= htmlspecialchars(strip_tags(substr($response->response, 0, 100))) ?>
                                            <?= strlen($response->response) > 100 ? '...' : '' ?>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= date('Y-m-d H:i:s', strtotime($response->updated_at)) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button class="edit-intent text-blue-600 hover:text-blue-900 mr-3" data-id="<?= $response->id ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="delete-intent text-red-600 hover:text-red-900" data-id="<?= $response->id ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No responses found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Add Intent Modal -->
<div id="addIntentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white border-2 border-black rounded-lg p-6 w-full max-w-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="handwriting text-2xl font-bold">Add New Intent</h3>
                <button id="closeAddModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="addIntentForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Intent Name</label>
                    <input type="text" id="addIntentName" class="w-full px-3 py-2 border-2 border-black rounded-lg" required>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Response Content</label>
                    <div id="addSummernote"></div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancelAdd" class="px-4 py-2 border-2 border-gray-300 rounded-lg hover:bg-gray-100">
                        Cancel
                    </button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 border-2 border-black rounded-lg hover:bg-green-600">
                        Add Intent
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Intent Modal -->
<div id="editIntentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white border-2 border-black rounded-lg p-6 w-full max-w-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="handwriting text-2xl font-bold">Edit Intent Response</h3>
                <button id="closeEditModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="editIntentForm">
                <input type="hidden" id="editIntentId">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Intent Name</label>
                    <input type="text" id="editIntentName" class="w-full px-3 py-2 border-2 border-black rounded-lg" required>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Response Content</label>
                    <div id="editSummernote"></div>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancelEdit" class="px-4 py-2 border-2 border-gray-300 rounded-lg hover:bg-gray-100">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 border-2 border-black rounded-lg hover:bg-blue-600">
                        Save Changes
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Initialize Summernote
    $('#addSummernote, #editSummernote').summernote({
        height: 200,
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['insert', ['link']],
            ['view', ['fullscreen', 'codeview', 'help']]
        ]
    });

    // Add new intent
    $('#addNewIntent').click(function() {
        $('#addIntentName').val('');
        $('#addSummernote').summernote('code', '');
        $('#addIntentModal').removeClass('hidden');
    });

    // Edit intent
    $('.edit-intent').click(function() {
        const id = $(this).data('id');
        
        $.ajax({
            url: '<?= base_url("admin/get_response") ?>',
            type: 'POST',
            data: { id: id },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    $('#editIntentId').val(response.data.id);
                    $('#editIntentName').val(response.data.intent);
                    $('#editSummernote').summernote('code', response.data.response);
                    $('#editIntentModal').removeClass('hidden');
                } else {
                    alert('Error loading response data');
                }
            },
            error: function() {
                alert('Error connecting to server');
            }
        });
    });

    // Delete intent
    $('.delete-intent').click(function() {
        const id = $(this).data('id');
        
        if (confirm('Are you sure you want to delete this intent response?')) {
            $.ajax({
                url: '<?= base_url("admin/delete") ?>',
                type: 'POST',
                data: { id: id },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        alert(response.message);
                        location.reload();
                    } else {
                        alert(response.message);
                    }
                },
                error: function() {
                    alert('Error connecting to server');
                }
            });
        }
    });

    // Close modals
    $('#closeAddModal, #cancelAdd').click(function() {
        $('#addIntentModal').addClass('hidden');
    });

    $('#closeEditModal, #cancelEdit').click(function() {
        $('#editIntentModal').addClass('hidden');
    });

    // Submit add form
    $('#addIntentForm').submit(function(e) {
        e.preventDefault();
        
        const intentName = $('#addIntentName').val();
        const responseContent = $('#addSummernote').summernote('code');

        $.ajax({
            url: '<?= base_url("admin/create") ?>',
            type: 'POST',
            data: {
                intent: intentName,
                response: responseContent
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Error connecting to server');
            }
        });
    });

    // Submit edit form
    $('#editIntentForm').submit(function(e) {
        e.preventDefault();
        
        const id = $('#editIntentId').val();
        const intentName = $('#editIntentName').val();
        const responseContent = $('#editSummernote').summernote('code');

        $.ajax({
            url: '<?= base_url("admin/update") ?>',
            type: 'POST',
            data: {
                id: id,
                intent: intentName,
                response: responseContent
            },
            dataType: 'json',
            success: function(response) {
                if (response.success) {
                    alert(response.message);
                    location.reload();
                } else {
                    alert(response.message);
                }
            },
            error: function() {
                alert('Error connecting to server');
            }
        });
    });
});
</script>