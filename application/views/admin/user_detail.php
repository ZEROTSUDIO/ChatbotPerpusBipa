<!-- Main Wrapper -->
<div id="main-wrapper" class="main-wrapper">
    <!-- Header -->
    <header class="bg-white border-b-2 border-black p-4">
        <div class="breadcrumb">
            <span class="text-gray-500">Dashboard</span>
            <span class="mx-2">/</span>
            <span class="font-semibold">User Profile</span>
        </div>
    </header>

    <!-- Main Content -->
    <main class="p-6 space-y-10">

        <!-- Profile Section -->
        <section>
            <h1 class="handwriting text-4xl font-bold mb-4">Profil Pengguna</h1>
            <div class="bg-white border-2 border-black rounded-lg p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p class="text-gray-500 font-semibold">Nama</p>
                        <p class="text-xl font-bold"><?= htmlentities($user->nama) ?></p>
                    </div>
                    <div>
                        <p class="text-gray-500 font-semibold">Email</p>
                        <p class="text-xl font-bold"><?= htmlentities($user->email) ?></p>
                    </div>
                    <div>
                        <p class="text-gray-500 font-semibold">Tanggal Registrasi</p>
                        <p class="text-xl font-bold"><?= $user->date ?? '-' ?></p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Chat History Table -->
        <section>
            <h2 class="handwriting text-3xl font-bold mb-4">Riwayat Chat</h2>
            <div class="table-wrapper border-2 border-black rounded-lg overflow-hidden">
                <div class="overflow-x-auto">
                    <table id="chatTable" class="w-full text-sm table-auto">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-10">#</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/4">User Message</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/2">Bot Response</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-1/6">Timestamp</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($chats as $i => $chat): ?>
                                <tr>
                                    <td class="px-6 py-4"><?= $i + 1 ?></td>
                                    <td class="px-6 py-4 whitespace-normal break-words"><?= htmlentities($chat['user_message']) ?></td>
                                    <td class="px-6 py-4 whitespace-normal break-words"><?= $chat['bot_response'] ?></td>
                                    <td class="px-6 py-4 text-gray-500"><?= $chat['timestamp'] ?? '-' ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <?php if (empty($chats)): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500 italic">Tidak ada riwayat chat untuk pengguna ini.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>                    
                </div>
            </div>
            <div id="paginationControls" class="flex justify-center items-center gap-2 mt-4"></div>
        </section>
    </main>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    const rowsPerPage = 10;
    const table = document.getElementById("chatTable");
    const tbody = table.querySelector("tbody");
    const rows = Array.from(tbody.querySelectorAll("tr")).filter(row => !row.querySelector("td[colspan]"));
    const pagination = document.getElementById("paginationControls");

    function showPage(pageNumber) {
        const start = (pageNumber - 1) * rowsPerPage;
        const end = start + rowsPerPage;

        rows.forEach((row, index) => {
            row.style.display = (index >= start && index < end) ? "" : "none";
        });

        renderPaginationButtons(pageNumber);
    }

    function renderPaginationButtons(activePage) {
        const totalPages = Math.ceil(rows.length / rowsPerPage);
        pagination.innerHTML = "";

        for (let i = 1; i <= totalPages; i++) {
            const button = document.createElement("button");
            button.textContent = i;
            button.className = `px-3 py-1 border-2 rounded-lg text-sm ${i === activePage ? 'bg-black text-white border-black' : 'border-gray-400 text-gray-700 hover:bg-gray-100'}`;
            button.addEventListener("click", () => showPage(i));
            pagination.appendChild(button);
        }
    }

    // Only paginate if needed
    if (rows.length > rowsPerPage) {
        showPage(1);
    }
});
</script>
