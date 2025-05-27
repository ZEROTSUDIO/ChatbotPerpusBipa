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
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm font-medium">book_recommendation</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate">
                                    I'd be happy to recommend some books for you! What genre are you interested in?
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-01-10 09:30:00</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button class="edit-intent text-blue-600 hover:text-blue-900 mr-3" data-intent="book_recommendation">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium">library_hours</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate">
                                    Our library is open Monday to Friday from 8:00 AM to 8:00 PM, and weekends from 9:00 AM to 5:00 PM.
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-01-08 14:15:00</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button class="edit-intent text-blue-600 hover:text-blue-900 mr-3" data-intent="library_hours">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm font-medium">book_search</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate">
                                    You can search for books by title, author, or ISBN. What specific book are you looking for?
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-01-12 11:45:00</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button class="edit-intent text-blue-600 hover:text-blue-900 mr-3" data-intent="book_search">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm font-medium">general_inquiry</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate">
                                    Hello! I'm here to help you with any questions about our library services. How can I assist you today?
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-01-05 16:20:00</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button class="edit-intent text-blue-600 hover:text-blue-900 mr-3" data-intent="general_inquiry">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm font-medium">account_help</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm text-gray-900 max-w-xs truncate">
                                    I can help you with account-related questions like registration, password reset, or account settings.
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">2024-01-14 13:10:00</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <button class="edit-intent text-blue-600 hover:text-blue-900 mr-3" data-intent="account_help">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>

<!-- Edit Intent Modal -->
<div id="editIntentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white border-2 border-black rounded-lg p-6 w-full max-w-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="handwriting text-2xl font-bold">Edit Intent Response</h3>
                <button id="closeModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="intentForm">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Intent Name</label>
                    <input type="text" id="intentName" class="w-full px-3 py-2 border-2 border-black rounded-lg" readonly>
                </div>

                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Response Content</label>
                    <div id="summernote"></div>
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
        $('#summernote').summernote({
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

        // Intent responses data
        const intentResponses = {
            'book_recommendation': "I'd be happy to recommend some books for you! What genre are you interested in? We have:\n\n- <strong>Fiction:</strong> Novels, short stories, fantasy, sci-fi\n- <strong>Non-fiction:</strong> Biographies, history, science\n- <strong>Academic:</strong> Textbooks and research materials\n\nPlease let me know your preferences!",
            'library_hours': "Our library is open:\n\n<strong>Monday to Friday:</strong> 8:00 AM - 8:00 PM\n<strong>Saturday & Sunday:</strong> 9:00 AM - 5:00 PM\n\n<em>Note: We're closed on public holidays. Please check our website for any schedule changes.</em>",
            'book_search': "You can search for books in several ways:\n\n1. <strong>By Title:</strong> Enter the exact or partial book title\n2. <strong>By Author:</strong> Search using author's name\n3. <strong>By ISBN:</strong> Use the 10 or 13-digit ISBN number\n4. <strong>By Subject:</strong> Browse by category or topic\n\nWhat specific book are you looking for? I'm here to help!",
            'general_inquiry': "Hello! 👋 Welcome to Perpus Bipa!\n\nI'm here to help you with:\n- Book recommendations and searches\n- Library hours and locations\n- Account assistance\n- General information about our services\n\n<strong>How can I assist you today?</strong>",
            'account_help': "I can help you with account-related questions:\n\n<strong>Account Services:</strong>\n- New member registration\n- Password reset\n- Profile updates\n- Borrowing history\n- Membership renewal\n\n<strong>Need immediate help?</strong> Please visit our help desk or contact us at <em>support@perpusbipa.com</em>"
        };

        // Edit intent button click
        $('.edit-intent').click(function() {
            const intentName = $(this).data('intent');
            $('#intentName').val(intentName);
            $('#summernote').summernote('code', intentResponses[intentName] || '');
            $('#editIntentModal').removeClass('hidden');
        });

        // Close modal
        $('#closeModal, #cancelEdit').click(function() {
            $('#editIntentModal').addClass('hidden');
        });

        // Submit form
        $('#intentForm').submit(function(e) {
            e.preventDefault();
            const intentName = $('#intentName').val();
            const responseContent = $('#summernote').summernote('code');

            // Here you would normally send data to your CodeIgniter controller
            console.log('Intent:', intentName);
            console.log('Response:', responseContent);

            // Update the table row
            const row = $(`.edit-intent[data-intent="${intentName}"]`).closest('tr');
            const responseCell = row.find('td:nth-child(2) div');
            const tempDiv = $('<div>').html(responseContent);
            const plainText = tempDiv.text().substring(0, 50) + '...';
            responseCell.text(plainText);

            // Update timestamp
            const now = new Date();
            const timestamp = now.getFullYear() + '-' +
                String(now.getMonth() + 1).padStart(2, '0') + '-' +
                String(now.getDate()).padStart(2, '0') + ' ' +
                String(now.getHours()).padStart(2, '0') + ':' +
                String(now.getMinutes()).padStart(2, '0') + ':' +
                String(now.getSeconds()).padStart(2, '0');
            row.find('td:nth-child(3)').text(timestamp);

            alert('Intent response updated successfully!');
            $('#editIntentModal').addClass('hidden');
        });

        // Add new intent (placeholder)
        $('#addNewIntent').click(function() {
            alert('Add New Intent functionality would be implemented here');
        });
    });
</script>