<!-- Main Wrapper -->
<div id="main-wrapper" class="main-wrapper">
    <!-- Header -->
    <header class="bg-white border-b-2 border-black p-4">
        <div class="breadcrumb">
            <span class="text-gray-500">Dashboard</span>
            <span class="mx-2">/</span>
            <span class="font-semibold">Users</span>
        </div>
    </header>

    <!-- Main Content -->
    <main class="p-6">
        <div class="mb-6">
            <h1 class="handwriting text-4xl font-bold mb-4">User Management</h1>
            <div class="flex flex-col sm:flex-row gap-4 justify-between items-start sm:items-center">
                <input type="text" placeholder="Search users..." class="px-4 py-2 border-2 border-black rounded-lg w-full sm:w-auto">
                <button class="bg-blue-500 text-white px-4 py-2 border-2 border-black rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Add User
                </button>
            </div>
        </div>

        <div class="table-wrapper">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Join Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chats</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php if (!empty($users)): ?>
                            <?php $colors = ['blue', 'green', 'purple', 'yellow', 'indigo', 'pink', 'red', 'cyan']; ?>
                            <?php foreach ($users as $key => $user): ?>
                                <?php $color = $colors[$key % count($colors)]; ?>
                                <tr data-id="<?= $user->id ?>">
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                        <div class="flex items-center">
                                            <span class="bg-<?= $color ?>-100 text-<?= $color ?>-800 px-3 py-1 rounded-full text-sm font-medium">
                                                <?= htmlspecialchars($user->id) ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <span class="font-medium">
                                                <?= htmlspecialchars($user->nama) ?>
                                            </span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= htmlspecialchars($user->email) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= date('Y-m-d', strtotime($user->date)) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="status-badge status-online">Active</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button class="text-blue-600 hover:text-blue-900 mr-3">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="text-red-600 hover:text-red-900">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-gray-500">No User found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </main>
</div>
<script>
    $(document).ready(function() {
        // Show Add User Modal
        $('#addNewUser').click(function() {
            $('#addUserName').val('');
            $('#addUserEmail').val('');
            $('#addUserPassword').val('');
            $('#addUserPasswordConfirm').val('');
            $('#addUserModal').removeClass('hidden');
        });

        // Show Edit Modal with Data
        $('.edit-user').click(function() {
            const id = $(this).data('id');

            $.ajax({
                url: '<?= base_url("admin/get_user") ?>',
                type: 'POST',
                data: {
                    id: id
                },
                dataType: 'json',
                success: function(response) {
                    if (response.success) {
                        $('#editUserId').val(response.data.id);
                        $('#editUserName').val(response.data.nama);
                        $('#editUserEmail').val(response.data.email);
                        $('#editUserPassword').val('');
                        $('#editUserPasswordConfirm').val('');
                        $('#editUserModal').removeClass('hidden');
                    } else {
                        alert('Error loading user data.');
                    }
                },
                error: function() {
                    alert('Failed to connect to server.');
                }
            });
        });

        // Delete User
        $('.delete-user').click(function() {
            const id = $(this).data('id');

            if (confirm('Are you sure you want to delete this user?')) {
                $.ajax({
                    url: '<?= base_url("admin/delete_user") ?>',
                    type: 'POST',
                    data: {
                        id: id
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
                        alert('Error connecting to server.');
                    }
                });
            }
        });

        // Close Modals
        $('#closeAddUserModal, #cancelAddUser').click(function() {
            $('#addUserModal').addClass('hidden');
        });

        $('#closeEditUserModal, #cancelEditUser').click(function() {
            $('#editUserModal').addClass('hidden');
        });

        // Submit Add Form
        $('#addUserForm').submit(function(e) {
            e.preventDefault();
            const name = $('#addUserName').val();
            const email = $('#addUserEmail').val();
            const password = $('#addUserPassword').val();
            const confirm = $('#addUserPasswordConfirm').val();

            if (password !== confirm) {
                alert('Password dan Ulangi Password tidak cocok.');
                return;
            }

            $.ajax({
                url: '<?= base_url("admin/create_user") ?>',
                type: 'POST',
                data: {
                    nama: name,
                    email: email,
                    password: password
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
                    alert('Server error.');
                }
            });
        });

        // Submit Edit Form
        $('#editUserForm').submit(function(e) {
            e.preventDefault();
            const id = $('#editUserId').val();
            const name = $('#editUserName').val();
            const email = $('#editUserEmail').val();
            const password = $('#editUserPassword').val();
            const confirm = $('#editUserPasswordConfirm').val();

            if (password !== '' && password !== confirm) {
                alert('Password dan Ulangi Password tidak cocok.');
                return;
            }

            const postData = {
                id: id,
                nama: name,
                email: email
            };

            if (password !== '') {
                postData.password = password;
            }

            $.ajax({
                url: '<?= base_url("admin/update_user") ?>',
                type: 'POST',
                data: postData,
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
                    alert('Server error.');
                }
            });
        });
    });
</script>