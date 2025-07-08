<style>
    /* Tambahan class Tailwind-like jika dibutuhkan manual */
    .btn-switch {
        @apply bg-blue-500 text-white px-4 py-2 border-2 border-black rounded-lg hover:bg-blue-600 transition-colors;
    }
</style>
<link href="<?php echo base_url(); ?>assets/css/main.css" rel="stylesheet">
<!-- Main Wrapper -->
<div id="main-wrapper" class="main-wrapper">
    <!-- Header -->
    <header class="bg-white border-b-2 border-black p-4">
        <div class="breadcrumb">
            <span class="text-gray-500">Dashboard</span>
            <span class="mx-2">/</span>
            <span class="font-semibold">Chat Test</span>
        </div>
    </header>

    <!-- Main Content -->
    <main class="p-6">
        <div class="mb-6">
            <h1 class="handwriting text-4xl font-bold mb-4">Chat Tests</h1>
            <div class="flex flex-col sm:flex-row gap-4 justify-start items-start sm:items-center">
                <button id="btnGantiA" class="text-white px-4 py-2 border-2 border-black rounded-lg hover:bg-black-500 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Test model
                </button>

                <button id="btnGantiB" class="text-white px-4 py-2 border-2 border-black rounded-lg hover:bg-black-600 transition-colors">
                    <i class="fas fa-plus mr-2"></i>Use Model
                </button>
            </div>
        </div>
        <div class="bg-white border-2 border-black rounded-lg p-10 w-full">
            <div class="flex flex-col space-y-4 flex-grow overflow-y-auto bg-white/80 p-4 rounded-lg" id="chat-container">
                <!-- Welcome message -->
                <div class="flex items-start space-x-3 message-container">
                    <div class="w-10 h-10 rounded-full border-2 border-black flex-shrink-0 flex items-center justify-center">
                        <i class="fas fa-book"></i>
                    </div>
                    <div class="bg-white p-4 rounded-lg border-2 border-black message-bubble max-w-[80%]">
                        <p class="font-bold handwriting text-lg">Perpus Bina Patria</p>
                        <p>Halo <?= $user->nama; ?>, Ada yang bisa saya bantu dengan rekomendasikan buku?</p>
                        <div class="timestamp">Hari ini, <?php echo date('H:i'); ?></div>
                    </div>
                </div>

                <?php if (empty($chats)): ?>
                    <!-- Chat suggestions only shown when no chat history -->
                    <div class="flex flex-col space-y-3 mt-4 ml-12 items-end">
                        <h2>Contoh untuk memulai</h2>
                        <?php foreach ($suggestions as $text): ?>
                            <div class="chat-suggestion bg-white/90 p-3 rounded-lg border-2 border-black max-w-[80%] hover:bg-gray-50">
                                <p class="handwriting"><?= htmlspecialchars($text) ?></p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>


                <?php foreach ($chats as $chat): ?>
                    <div class="flex items-end justify-end space-x-3 message-container">
                        <div class="bg-white p-4 rounded-lg border-2 border-black message-bubble max-w-[80%]">
                            <p class="text-right font-bold handwriting text-lg"><?= $user->nama; ?></p>
                            <p><?= $chat['user_message'] ?></p>
                            <div class="timestamp text-right"><?= $chat['timestamp'] ?></div>
                        </div>
                        <div class="w-10 h-10 rounded-full border-2 border-black flex-shrink-0 flex items-center justify-center">
                            <i class="fas fa-user"></i>
                        </div>
                    </div>
                    <div class="flex items-start space-x-3 message-container">
                        <div class="w-10 h-10 rounded-full border-2 border-black flex-shrink-0 flex items-center justify-center">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="bg-white p-4 rounded-lg border-2 border-black message-bubble max-w-[80%]">
                            <p class="font-bold handwriting text-lg">Perpus Bina Patria</p>
                            <p><?= $chat['bot_response'] ?></p>

                            <?php if (!empty($chat['intent']) || !empty($chat['confident_score']) || !empty($chat['energy']) || !empty($chat['class_probabilities'])): ?>
                                <div class="absolute top-2 right-2">
                                    <button class="detail-toggle-btn text-gray-500 hover:text-black focus:outline-none">
                                        <i class="fas fa-ellipsis-v"></i>
                                    </button>
                                </div>
                                <div class="intent-detail hidden mt-2 text-sm border-t border-gray-300 pt-2">
                                    <p><strong>Intent:</strong> <?= $chat['intent'] ?? '-' ?></p>
                                    <p><strong>Confidence:</strong> <?= isset($chat['confident_score']) ? number_format($chat['confident_score'], 4) : '-' ?></p>
                                    <p><strong>Energy:</strong> <?= isset($chat['energy']) ? number_format($chat['energy'], 4) : '-' ?></p>
                                    <div class="mt-2">
                                        <p class="font-semibold">Class Probabilities:</p>
                                        <table class="text-sm mt-1">
                                            <?php if (!empty($chat['class_probabilities'])): ?>
                                                <?php foreach ($chat['class_probabilities'] as $prob): ?>
                                                    <tr>
                                                        <td class="pr-4 font-semibold"><?= $prob['intent_class'] ?></td>
                                                        <td><?= number_format($prob['probability'], 4) ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            <?php else: ?>
                                                <tr>
                                                    <td colspan="2">-</td>
                                                </tr>
                                            <?php endif; ?>
                                        </table>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <div class="timestamp"><?= $chat['timestamp'] ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Input form -->
            <div class="mt-5 border-t-2 border-gray-200 pt-4">
                <form id="chat-form" class="flex items-center space-x-3">
                    <input type="text" id="message" name="message" placeholder="Tulis pesan Anda disini..." class="flex-grow p-3 border-2 border-black rounded-xl focus:outline-none focus:ring-2 focus:ring-gray-300">
                    <button type="submit" class="p-3 rounded-xl bg-white border-2 border-black hover:bg-gray-100 transition-colors h-12 w-12 flex items-center justify-center">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </main>
</div>
<script>
    $(document).ready(function() {
        let waitingForRecommendation = false;
        let wait_confirmation = false;

        function scrollToBottom() {
            var chatContainer = document.getElementById('chat-container');
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        scrollToBottom();

        // Handle chat suggestion clicks
        $('.chat-suggestion').on('click', function() {
            const suggestionText = $(this).find('p').text();
            $('#message').val(suggestionText);
            $('#chat-form').submit();
        });

        $('#chat-form').submit(function(e) {
            e.preventDefault();

            // Prevent user input while bot is typing
            if ($('#typing-indicator').length > 0) {
                console.log("Bot masih mengetik, kirim pesan diblokir.");
                return;
            }

            var message = $('#message').val();
            if (message.trim() === '') return;

            // Hide suggestion bubbles when user sends a message
            $('.chat-suggestion').slideUp(300, function() {
                $(this).remove();
            });

            const now = new Date();
            const timestamp = now.toLocaleTimeString([], {
                hour: '2-digit',
                minute: '2-digit'
            });

            $('#chat-container').append(`
            <div class="flex items-end justify-end space-x-3 message-container">
                <div class="bg-white p-4 rounded-lg border-2 border-black message-bubble max-w-[80%]">
                    <p class="text-right font-bold handwriting text-lg">${userName}</p>
                    <p>${message}</p>
                    <div class="timestamp text-right">${timestamp}</div>
                </div>
                <div class="w-10 h-10 rounded-full border-2 border-black flex-shrink-0 flex items-center justify-center">
                    <i class="fas fa-user"></i>
                </div>
            </div>
        `);

            $('#message').val('');
            scrollToBottom();

            $('#chat-container').append(`
            <div class="flex items-start space-x-3" id="typing-indicator">
                <div class="w-10 h-10 rounded-full border-2 border-black flex-shrink-0 flex items-center justify-center">
                    <i class="fas fa-book"></i>
                </div>
                <div class="bg-white p-4 rounded-lg border-2 border-black max-w-[80%]">
                    <p class="font-bold handwriting text-lg">Perpus Bina Patria</p>
                    <p>Mengetik<span class="typing-dots">...</span></p>
                </div>
            </div>
        `);
            scrollToBottom();

            console.log("waitingForRecommendation:", waitingForRecommendation);
            console.log("wait_confirmation:", wait_confirmation);

            if (waitingForRecommendation) {
                $.ajax({
                    url: `${baseUrl}${activeController}/sendbook`,
                    type: 'POST',
                    contentType: 'application/json',
                    data: JSON.stringify({
                        message: message
                    }),
                    dataType: 'json',
                    xhrFields: {
                        withCredentials: true
                    },
                    success: function(response) {
                        $('#typing-indicator').remove();

                        let responseHtml = `
                        <div class="flex items-start space-x-3 message-container">
                            <div class="w-10 h-10 rounded-full border-2 border-black flex-shrink-0 flex items-center justify-center">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="bg-white p-4 rounded-lg border-2 border-black message-bubble max-w-[80%]">
                                <p class="font-bold handwriting text-lg">Perpus Bina Patria</p>
                                <div>${response.response}</div>
                                <div class="timestamp">${timestamp}</div>
                            </div>
                        </div>`;

                        // Tambahkan tombol "Lihat lebih banyak" jika low_recommendation
                        if (response.low_recommendation) {
                            responseHtml += `
                            <div class="flex justify-start mb-3 ml-14 mt-2">
                                <button id="more-recommendation-btn" class="handwriting bg-white px-4 py-2 border-2 border-black rounded-lg shadow-sm hover:bg-gray-50 transition-all">
                                    Lihat lebih banyak rekomendasi
                                </button>
                            </div>`;
                        }

                        $('#chat-container').append(responseHtml);

                        waitingForRecommendation = false;
                        wait_confirmation = true;
                        scrollToBottom();
                    },
                    error: function(xhr) {
                        $('#typing-indicator').remove();

                        let serverResponse = "Terjadi kesalahan saat merekomendasikan buku. Silakan coba lagi.";
                        try {
                            serverResponse = JSON.parse(xhr.responseText).response || serverResponse;
                        } catch (e) {}

                        $('#chat-container').append(`
                        <div class="flex items-start space-x-3 message-container">
                            <div class="w-10 h-10 rounded-full border-2 border-black flex-shrink-0 flex items-center justify-center">
                                <i class="fas fa-book"></i>
                            </div>
                            <div class="bg-white p-4 rounded-lg border-2 border-black message-bubble max-w-[80%]">
                                <p class="font-bold handwriting text-lg">Perpus Bina Patria</p>
                                <div>${serverResponse}</div>
                                <div class="timestamp">${timestamp}</div>
                            </div>
                        </div>
                    `);
                        waitingForRecommendation = false;
                        wait_confirmation = false;
                        scrollToBottom();
                    }
                });
                return;
            }

            // Normal intent
            $.ajax({
                url: `${baseUrl}${activeController}/send`,
                type: 'POST',
                contentType: 'application/json',
                data: JSON.stringify({
                    message: message,
                    wait_confirmation: wait_confirmation
                }),
                dataType: 'json',
                xhrFields: {
                    withCredentials: true
                },
                success: function(response) {
                    $('#typing-indicator').remove();

                    let intentDetailHtml = '';

                    if (response.intent || response.confidence || response.energy || response.class_probabilities) {
                        const probs = response.class_probabilities || {};
                        let probRows = '';
                        for (const [cls, score] of Object.entries(probs)) {
                            probRows += `<tr><td class="pr-4 font-semibold">${cls}</td><td>${score !== null ? score.toFixed(4) : '-'}</td></tr>`;
                        }

                        intentDetailHtml = `
						<div class="absolute top-2 right-2">
							<button class="detail-toggle-btn text-gray-500 hover:text-black focus:outline-none">
								<i class="fas fa-ellipsis-v"></i>
							</button>
						</div>
						<div class="intent-detail hidden mt-2 text-sm border-t border-gray-300 pt-2">
							<p><strong>Intent:</strong> ${response.intent || '-'}</p>
							<p><strong>Confidence:</strong> ${response.confidence?.toFixed(4) || '-'}</p>
							<p><strong>Energy:</strong> ${response.energy?.toFixed(4) || '-'}</p>
							<div class="mt-2">
								<p class="font-semibold">Class Probabilities:</p>
								<table class="text-sm mt-1">
									${probRows || '<tr><td colspan="2">-</td></tr>'}
								</table>
							</div>
						</div>
					`;
                    }

                    let responseHtml = `
				<div class="flex items-start space-x-3 message-container">
					<div class="w-10 h-10 rounded-full border-2 border-black flex-shrink-0 flex items-center justify-center">
						<i class="fas fa-book"></i>
					</div>
					<div class="bg-white p-4 rounded-lg border-2 border-black message-bubble max-w-[80%] relative">
						<p class="font-bold handwriting text-lg">Perpus Bina Patria</p>
						<div>${response.response}</div>
						${intentDetailHtml}
						<div class="timestamp">${timestamp}</div>
					</div>
				</div>`;


                    // Tambah tombol "Lihat lebih banyak rekomendasi" jika low_recommendation
                    if (response.low_recommendation) {
                        responseHtml += `
                        <div class="flex justify-start mb-3 ml-14 mt-2">
                            <button id="more-recommendation-btn" class="handwriting bg-white px-4 py-2 border-2 border-black rounded-lg shadow-sm hover:bg-gray-50 transition-all">
                                Lihat lebih banyak rekomendasi
                            </button>
                        </div>`;
                    }

                    $('#chat-container').append(responseHtml);
                    scrollToBottom();

                    // Handle next action
                    if (response.next_action === 'wait_book_recommendation') {
                        waitingForRecommendation = true;
                        wait_confirmation = false;
                    } else if (response.next_action === 'confirmation') {
                        waitingForRecommendation = true;
                        wait_confirmation = true;
                    } else {
                        waitingForRecommendation = false;
                        wait_confirmation = false;
                    }
                },
                error: function() {
                    $('#typing-indicator').remove();
                    $('#chat-container').append(`
                    <div class="flex items-start space-x-3 message-container">
                        <div class="w-10 h-10 rounded-full border-2 border-black flex-shrink-0 flex items-center justify-center">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="bg-white p-4 rounded-lg border-2 border-black message-bubble max-w-[80%]">
                            <p class="font-bold handwriting text-lg">Perpus Bina Patria</p>
                            <p>Maaf, terjadi kesalahan. Silakan coba lagi.</p>
                            <div class="timestamp">${timestamp}</div>
                        </div>
                    </div>
                `);
                    scrollToBottom();
                }
            });
        });

        // Toggle intent detail
        $('#chat-container').off('click', '.detail-toggle-btn').on('click', '.detail-toggle-btn', function() {
            const detail = $(this).closest('.message-bubble').find('.intent-detail');
            detail.toggleClass('hidden');
        });

        // Event delegation for handling "Lihat lebih banyak rekomendasi" button
        $(document).on('click', '#more-recommendation-btn', function() {
            const message = "Lanjutkan rekomendasi buku";
            $('#message').val(message);
            $('#chat-form').submit();
        });

        setInterval(function() {
            var dots = $('.typing-dots');
            if (dots.length > 0) {
                var text = dots.text();
                dots.text(text.length >= 3 ? '' : text + '.');
            }
        }, 500);
    });
</script>
<script>
    const baseUrl = "<?= base_url() ?>";
    const userName = <?= json_encode($user->nama); ?>;
    var activeController = "chat_test";
    var variable1 = "A";

    // Ambil elemen tombol
    const btnA = document.getElementById("btnGantiA");
    const btnB = document.getElementById("btnGantiB");

    // Fungsi helper untuk set tombol ON style
    function setButtonOn(btn) {
        btn.classList.remove("bg-white", "text-black", "border", "border-black");
        btn.classList.add("bg-black", "text-white");
    }

    // Fungsi helper untuk set tombol OFF style
    function setButtonOff(btn) {
        btn.classList.remove("bg-black", "text-white");
        btn.classList.add("bg-white", "text-black", "border", "border-black");
    }

    // Fungsi untuk handle toggle
    function setActive(value) {
        variable1 = value;

        if (value === "A") {
            activeController = "chat_test";
            console.log("Active controller:", activeController);
            setButtonOn(btnA);
            setButtonOff(btnB);
        } else {
            activeController = "chat";
            console.log("Active controller:", activeController);
            setButtonOn(btnB);
            setButtonOff(btnA);
        }
    }

    // Initial state saat halaman load
    setButtonOn(btnA);
    setButtonOff(btnB);

    // Event listeners
    btnA.addEventListener("click", () => setActive("A"));
    btnB.addEventListener("click", () => setActive("B"));
</script>