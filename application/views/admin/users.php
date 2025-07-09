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
                <input id="userSearchInput" type="text" placeholder="Search users..." class="px-4 py-2 border-2 border-black rounded-lg w-full sm:w-auto">
                <button id="addNewUser" class="bg-blue-500 text-white px-4 py-2 border-2 border-black rounded-lg hover:bg-blue-600 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Add User
                </button>
            </div>
        </div>

        <div class="table-wrapper">
            <div class="overflow-x-auto">
                <table id="myTableId" class="w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Join Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Chats</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                        <tr id="noUserRow" style="display: none;">
                            <td colspan="7" class="px-6 py-4 text-center text-gray-500">No User found</td>
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
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($user->level == 1): ?>
                                            <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-medium">Admin</span>
                                        <?php else: ?>
                                            <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-medium">User</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= date('Y-m-d', strtotime($user->date)) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="value-colorize" data-value="<?= $user->chats_sum ?>">
                                            <!--span class="status-badge status-online">Active</span-->
                                            <?= htmlspecialchars($user->chats_sum) ?>
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="<?= base_url('admin/user_detail/') . $user->id ?>" class="text-green-600 hover:text-blue-800 mr-3">
                                            <i class="fas fa-user-circle"></i>
                                        </a>
                                        <button class="edit-user text-blue-600 hover:text-blue-900 mr-3" data-id="<?= $user->id ?>">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button class="delete-user text-red-600 hover:text-red-900" data-id="<?= $user->id ?>">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">No User found</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
                <div id="myPaginationId" class="flex justify-center mt-4"></div>
            </div>
        </div>
    </main>
</div>

<div id="addUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white border-2 border-black rounded-lg p-6 w-full max-w-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="handwriting text-2xl font-bold">Add New User</h3>
                <button id="closeAddUserModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="addUserForm">
                <div class="mb-4">
                    <label for="addUserName" class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                    <input type="text" id="addUserName" class="w-full px-3 py-2 border-2 border-black rounded-lg" required>
                </div>

                <div class="mb-4">
                    <label for="addUserEmail" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="addUserEmail" class="w-full px-3 py-2 border-2 border-black rounded-lg" required>
                </div>

                <div class="mb-4">
                    <label for="addUserLevel" class="block text-sm font-medium text-gray-700 mb-2">Level</label>
                    <select id="addUserLevel" class="w-full px-3 py-2 border-2 border-black rounded-lg" required>
                        <option value="1">Admin</option>
                        <option value="2" selected>User</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="addUserPassword" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input type="password" id="addUserPassword" class="w-full px-3 py-2 border-2 border-black rounded-lg" required>
                </div>

                <div class="mb-6">
                    <label for="addUserPasswordConfirm" class="block text-sm font-medium text-gray-700 mb-2">Ulangi Password</label>
                    <input type="password" id="addUserPasswordConfirm" class="w-full px-3 py-2 border-2 border-black rounded-lg" required>
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancelAddUser" class="px-4 py-2 border-2 border-gray-300 rounded-lg hover:bg-gray-100">
                        Cancel
                    </button>
                    <button type="submit" class="bg-green-500 text-white px-4 py-2 border-2 border-black rounded-lg hover:bg-green-600">
                        Add User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<div id="editUserModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="bg-white border-2 border-black rounded-lg p-6 w-full max-w-2xl">
            <div class="flex justify-between items-center mb-4">
                <h3 class="handwriting text-2xl font-bold">Edit User</h3>
                <button id="closeEditUserModal" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <form id="editUserForm">
                <!-- hidden field for ID -->
                <input type="hidden" id="editUserId">

                <div class="mb-4">
                    <label for="editUserName" class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                    <input type="text" id="editUserName" class="w-full px-3 py-2 border-2 border-black rounded-lg" required>
                </div>

                <div class="mb-4">
                    <label for="editUserEmail" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                    <input type="email" id="editUserEmail" class="w-full px-3 py-2 border-2 border-black rounded-lg" required>
                </div>

                <div class="mb-4">
                    <label for="editUserLevel" class="block text-sm font-medium text-gray-700 mb-2">Level</label>
                    <select id="editUserLevel" class="w-full px-3 py-2 border-2 border-black rounded-lg" required>
                        <option value="1">Admin</option>
                        <option value="2">User</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="editUserPassword" class="block text-sm font-medium text-gray-700 mb-2">Password (Opsional)</label>
                    <input type="password" id="editUserPassword" class="w-full px-3 py-2 border-2 border-black rounded-lg" placeholder="Kosongkan jika tidak ingin diubah">
                </div>

                <div class="mb-6">
                    <label for="editUserPasswordConfirm" class="block text-sm font-medium text-gray-700 mb-2">Ulangi Password</label>
                    <input type="password" id="editUserPasswordConfirm" class="w-full px-3 py-2 border-2 border-black rounded-lg" placeholder="Kosongkan jika tidak ingin diubah">
                </div>

                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancelEditUser" class="px-4 py-2 border-2 border-gray-300 rounded-lg hover:bg-gray-100">
                        Cancel
                    </button>
                    <button type="submit" class="bg-blue-500 text-white px-4 py-2 border-2 border-black rounded-lg hover:bg-blue-600">
                        Update User
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script>
    $(document).ready(function() {

        // Show Add User Modal
        $('#addNewUser').click(function() {
            $('#addUserName').val('');
            $('#addUserEmail').val('');
            $('#addUserLevel').val('2'); // Default to User
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
                        $('#editUserLevel').val(response.data.level || '2'); // Default to User if level not set
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
            const level = $('#addUserLevel').val();
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
                    level: level,
                    password: password,
                    confirm: confirm
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
            const level = $('#editUserLevel').val();
            const password = $('#editUserPassword').val();
            const confirm = $('#editUserPasswordConfirm').val();

            if (password !== '' && password !== confirm) {
                alert('Password dan Ulangi Password tidak cocok.');
                return;
            }

            const postData = {
                id: id,
                nama: name,
                email: email,
                level: level,
                confirm: confirm
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
<script>
    document.querySelectorAll('.value-colorize').forEach(el => {
        const value = parseInt(el.dataset.value);
        const min = 0;
        const max = 100; // Change as needed

        // Clamp and normalize
        const normalized = Math.max(0, Math.min(1, (value - min) / (max - min)));

        // Hue from red (0) to green (120)
        const hue = Math.round(normalized * 120);
        const hsl = `hsl(${hue}, 80%, 45%)`;

        el.style.color = hsl;
        el.style.fontWeight = 'bold';
    });
</script>
<script>
    document.getElementById('userSearchInput').addEventListener('keyup', function() {
        const searchQuery = this.value.toLowerCase();
        const tableRows = document.querySelectorAll('#myTableId tbody tr');
        const noResultRow = document.getElementById('noUserRow');
        let visibleCount = 0;

        tableRows.forEach(row => {
            const cells = row.querySelectorAll('td');
            let matchFound = false;

            cells.forEach(cell => {
                if (cell.textContent.toLowerCase().includes(searchQuery)) {
                    matchFound = true;
                }
            });
            if (matchFound) visibleCount++;
            noResultRow.style.display = (visibleCount === 0) ? '' : 'none';

            row.style.display = matchFound ? '' : 'none';
        });
    });

    function paginateTable(tableId, rowsPerPage, paginationId) {
        const table = document.getElementById(tableId);
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        const pagination = document.getElementById(paginationId);
        let currentPage = 1;

        function displayRows(page) {
            const start = (page - 1) * rowsPerPage;
            const end = start + rowsPerPage;
            rows.forEach((row, index) => {
                row.style.display = (index >= start && index < end) ? '' : 'none';
            });
        }

        function updatePagination() {
            pagination.innerHTML = '';
            const totalPages = Math.ceil(rows.length / rowsPerPage);

            for (let i = 1; i <= totalPages; i++) {
                const btn = document.createElement('button');
                btn.textContent = i;
                btn.className = 'px-3 py-1 mx-1 border rounded hover:bg-gray-300';
                if (i === currentPage) btn.classList.add('bg-blue-500', 'text-white');

                btn.addEventListener('click', () => {
                    currentPage = i;
                    displayRows(currentPage);
                    updatePagination();
                });

                pagination.appendChild(btn);
            }
        }

        // Initialize
        displayRows(currentPage);
        updatePagination();
    }
    document.addEventListener('DOMContentLoaded', function() {
        paginateTable('myTableId', 10, 'myPaginationId');
    });
</script>