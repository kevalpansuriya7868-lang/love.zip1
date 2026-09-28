// Real-time AJAX Private Chat Manager
document.addEventListener('DOMContentLoaded', () => {
    const chatMessages = document.getElementById('chatMessages');
    const chatForm     = document.getElementById('chatForm');
    const messageInput = document.getElementById('messageInput');
    const imageInput   = document.getElementById('imageInput');
    const replyBar     = document.getElementById('replyBar');
    const replyText    = document.getElementById('replyText');
    const replyIdInput = document.getElementById('replyIdInput');
    const cancelReply  = document.getElementById('cancelReply');

    if (!chatMessages || !chatForm) return;

    let lastMsgId = 0;
    const msgBubbles = chatMessages.querySelectorAll('[data-message-id]');
    if (msgBubbles.length > 0) {
        lastMsgId = parseInt(msgBubbles[msgBubbles.length - 1].getAttribute('data-message-id')) || 0;
    }

    const scrollToBottom = () => {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    };
    scrollToBottom();

    // Poll for new messages
    const pollMessages = async () => {
        try {
            const res = await fetch(window.APP_URL + '/api/chat/messages?after_id=' + lastMsgId);
            const json = await res.json();

            if (json.success && json.data.length > 0) {
                json.data.forEach(msg => {
                    if (parseInt(msg.id) > lastMsgId) {
                        appendMessageBubble(msg);
                        lastMsgId = parseInt(msg.id);
                    }
                });
                scrollToBottom();
            }
        } catch (e) {
            console.error("Chat poll error", e);
        }
    };

    setInterval(pollMessages, 2500);

    // Send Message AJAX
    chatForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        const text = messageInput.value.trim();
        const image = imageInput ? imageInput.files[0] : null;

        if (!text && !image) return;

        const formData = new FormData();
        formData.append('message', text);
        if (image) formData.append('image', image);
        if (replyIdInput.value) formData.append('reply_to_id', replyIdInput.value);

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        formData.append('csrf_token', csrfToken);

        messageInput.value = '';
        if (imageInput) imageInput.value = '';
        clearReply();

        try {
            const res = await fetch(window.APP_URL + '/api/chat/send', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });

            const json = await res.json();
            if (json.success) {
                appendMessageBubble(json.data);
                lastMsgId = parseInt(json.data.id);
                scrollToBottom();
            } else {
                alert(json.message || 'Failed to send message.');
            }
        } catch (err) {
            alert('Network error. Failed to send message.');
        }
    });

    // Helper to append bubble
    function appendMessageBubble(msg) {
        const currentUserId = parseInt(window.CURRENT_USER_ID);
        const isSent = parseInt(msg.sender_id) === currentUserId;

        const bubble = document.createElement('div');
        bubble.className = `d-flex flex-column ${isSent ? 'align-items-end' : 'align-items-start'} mb-3`;
        bubble.setAttribute('data-message-id', msg.id);

        let contentHtml = '';
        if (msg.message_type === 'image') {
            const imgUrl = window.APP_URL + '/uploads/' + msg.message;
            contentHtml = `<img src="${imgUrl}" class="img-fluid rounded mb-1" style="max-height: 250px; cursor:pointer;" onclick="openLightbox('${imgUrl}')">`;
        } else {
            contentHtml = escapeHtml(msg.message);
        }

        let replyHtml = '';
        if (msg.reply_message) {
            replyHtml = `<div class="p-1 px-2 mb-1 rounded bg-light text-muted small border-start border-3 border-danger" style="font-size:0.8rem;">
                <strong>${escapeHtml(msg.reply_sender_name || 'Partner')}:</strong> ${escapeHtml(msg.reply_message)}
            </div>`;
        }

        const timeStr = new Date(msg.created_at).toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

        const deleteBtn = isSent && msg.message !== 'Message deleted' ? 
            `<button class="btn btn-link btn-sm p-0 text-muted ms-2 opacity-50 hover-100" onclick="deleteMessage(${msg.id})"><i class="fas fa-trash-alt"></i></button>` : '';

        const replyBtn = msg.message !== 'Message deleted' ?
            `<button class="btn btn-link btn-sm p-0 text-muted ms-2 opacity-50 hover-100" onclick="setReply(${msg.id}, '${escapeJsString(msg.message)}')"><i class="fas fa-reply"></i></button>` : '';

        bubble.innerHTML = `
            <div class="message-bubble ${isSent ? 'message-sent' : 'message-received'}">
                ${replyHtml}
                <div>${contentHtml}</div>
                <div class="message-time d-flex align-items-center justify-content-end">
                    <span>${timeStr}</span>
                    ${replyBtn}
                    ${deleteBtn}
                </div>
            </div>
        `;

        chatMessages.appendChild(bubble);
    }

    // Reply Handler
    window.setReply = (id, text) => {
        replyIdInput.value = id;
        replyText.innerText = text.length > 50 ? text.substring(0, 50) + '...' : text;
        replyBar.classList.remove('d-none');
        messageInput.focus();
    };

    function clearReply() {
        replyIdInput.value = '';
        replyBar.classList.add('d-none');
    }

    if (cancelReply) {
        cancelReply.addEventListener('click', clearReply);
    }

    // Delete Message Handler
    window.deleteMessage = async (id) => {
        if (!confirm('Are you sure you want to delete this message?')) return;

        const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        const formData = new FormData();
        formData.append('id', id);
        formData.append('csrf_token', csrfToken);

        try {
            const res = await fetch(window.APP_URL + '/api/chat/delete', {
                method: 'POST',
                headers: { 'X-Requested-With': 'XMLHttpRequest' },
                body: formData
            });

            const json = await res.json();
            if (json.success) {
                const bubble = chatMessages.querySelector(`[data-message-id="${id}"] .message-bubble`);
                if (bubble) {
                    bubble.innerHTML = '<span class="fst-italic text-muted"><i class="fas fa-ban me-1"></i> Message deleted</span>';
                }
            } else {
                alert(json.message || 'Could not delete message.');
            }
        } catch (e) {
            alert('Failed to delete message.');
        }
    };

    function escapeHtml(str) {
        return (str || '').replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;");
    }
    function escapeJsString(str) {
        return (str || '').replace(/'/g, "\\'").replace(/"/g, '\\"');
    }
});
