$(document).ready(function () {
    let waitingForRecommendation = false;
    let wait_confirmation = false;

	const chatContainer = document.getElementById("chat-container");
    const scrollBtn = document.getElementById("scrollToBottomBtn");
	
	chatContainer.addEventListener("scroll", function () {
        const nearBottom = chatContainer.scrollHeight - chatContainer.scrollTop - chatContainer.clientHeight < 100;
        scrollBtn.classList.toggle("hidden", nearBottom);
    });

    // Scroll to bottom when clicked
    scrollBtn.addEventListener("click", function () {
        chatContainer.scrollTo({
            top: chatContainer.scrollHeight,
            behavior: 'smooth'
        });
    });

    // Optional: scroll to bottom on initial page load
    window.addEventListener("load", function () {
        setTimeout(() => {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }, 100);
    });
	
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

    $('#chat-form').submit(function (e) {
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
        const timestamp = now.toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

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
                data: JSON.stringify({ message: message }),
                dataType: 'json',
                xhrFields: { withCredentials: true },
                success: function (response) {
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
                error: function (xhr) {
                    $('#typing-indicator').remove();

                    let serverResponse = "Terjadi kesalahan saat merekomendasikan buku. Silakan coba lagi.";
                    try {
                        serverResponse = JSON.parse(xhr.responseText).response || serverResponse;
                    } catch (e) { }

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
            data: JSON.stringify({ message: message, wait_confirmation: wait_confirmation }),
            dataType: 'json',
            xhrFields: { withCredentials: true },
            success: function (response) {
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
            error: function () {
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
	$('#chat-container').off('click', '.detail-toggle-btn').on('click', '.detail-toggle-btn', function () {
		const detail = $(this).closest('.message-bubble').find('.intent-detail');
		detail.toggleClass('hidden');
	});

    // Event delegation for handling "Lihat lebih banyak rekomendasi" button
    $(document).on('click', '#more-recommendation-btn', function () {
        const message = "Lanjutkan rekomendasi buku";
        $('#message').val(message);
        $('#chat-form').submit();
    });

    setInterval(function () {
        var dots = $('.typing-dots');
        if (dots.length > 0) {
            var text = dots.text();
            dots.text(text.length >= 3 ? '' : text + '.');
        }
    }, 500);
});