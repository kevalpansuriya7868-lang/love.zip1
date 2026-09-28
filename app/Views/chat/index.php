<div class="row justify-content-center">
    <div class="col-lg-10">
        <div class="chat-container">
            <!-- Chat Header -->
            <div class="chat-header">
                <div class="d-flex align-items-center gap-3">
                    <?php if (!empty($partner['profile_photo'])): ?>
                        <img src="<?= upload_url($partner['profile_photo']); ?>" class="rounded-circle border" style="width: 44px; height: 44px; object-fit: cover;">
                    <?php else: ?>
                        <div class="rounded-circle bg-danger bg-opacity-10 text-danger d-flex align-items-center justify-content-center fw-bold" style="width: 44px; height: 44px;">
                            <?= strtoupper(substr($partner['name'] ?? 'P', 0, 1)); ?>
                        </div>
                    <?php endif; ?>
                    <div>
                        <h6 class="mb-0 fw-bold brand-font"><?= e($partner['name'] ?? 'Partner'); ?></h6>
                        <span class="small text-success"><i class="fas fa-circle fs-8 me-1"></i> Active in Couple Space</span>
                    </div>
                </div>
                <div class="d-flex align-items-center">
                    <button type="button" class="btn btn-light rounded-circle me-2 text-danger shadow-sm border" onclick="startCall('voice')" title="Voice Call" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-phone-alt"></i>
                    </button>
                    <button type="button" class="btn btn-light rounded-circle me-3 text-danger shadow-sm border" onclick="startCall('video')" title="Video Call" style="width: 38px; height: 38px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-video"></i>
                    </button>
                    <span class="badge bg-light text-muted border rounded-pill px-3 py-2 d-none d-md-inline-block">
                        <i class="fas fa-shield-alt text-danger me-1"></i> End-to-End Private Space
                    </span>
                </div>
            </div>

            <!-- Chat Messages Container -->
            <div id="chatMessages" class="chat-messages">
                <?php if (empty($messages)): ?>
                    <div class="empty-state my-auto">
                        <div class="empty-state-icon"><i class="fas fa-paper-plane"></i></div>
                        <h5>Your story starts here.</h5>
                        <p class="small text-muted">Send your first message to <?= e($partner['name'] ?? 'your partner'); ?> and make it a memory.</p>
                    </div>
                <?php else: ?>
                    <?php foreach ($messages as $msg): 
                        $isSent = ($msg['sender_id'] == $_SESSION['user']['id']);
                    ?>
                        <div class="d-flex flex-column <?= $isSent ? 'align-items-end' : 'align-items-start'; ?> mb-3" data-message-id="<?= $msg['id']; ?>">
                            <div class="message-bubble <?= $isSent ? 'message-sent' : 'message-received'; ?>">
                                <?php if (!empty($msg['reply_message'])): ?>
                                    <div class="p-1 px-2 mb-1 rounded bg-light text-muted small border-start border-3 border-danger" style="font-size:0.8rem;">
                                        <strong><?= e($msg['reply_sender_name'] ?? 'Partner'); ?>:</strong> <?= e($msg['reply_message']); ?>
                                    </div>
                                <?php endif; ?>

                                <div>
                                    <?php if ($msg['message_type'] === 'image'): ?>
                                        <img src="<?= upload_url($msg['message']); ?>" class="img-fluid rounded mb-1" style="max-height: 250px; cursor:pointer;" onclick="openLightbox('<?= upload_url($msg['message']); ?>')">
                                    <?php else: ?>
                                        <?= e($msg['message']); ?>
                                    <?php endif; ?>
                                </div>

                                <div class="message-time d-flex align-items-center justify-content-end">
                                    <span><?= date('h:i A', strtotime($msg['created_at'])); ?></span>
                                    <?php if ($msg['message'] !== 'Message deleted'): ?>
                                        <button class="btn btn-link btn-sm p-0 text-muted ms-2 opacity-50 hover-100" onclick="setReply(<?= $msg['id']; ?>, '<?= e(addslashes($msg['message'])); ?>')">
                                            <i class="fas fa-reply"></i>
                                        </button>
                                        <?php if ($isSent): ?>
                                            <button class="btn btn-link btn-sm p-0 text-muted ms-2 opacity-50 hover-100" onclick="deleteMessage(<?= $msg['id']; ?>)">
                                                <i class="fas fa-trash-alt"></i>
                                            </button>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

            <!-- Reply Preview Bar -->
            <div id="replyBar" class="p-2 px-3 bg-light border-top d-flex justify-content-between align-items-center d-none">
                <div class="small text-truncate">
                    <i class="fas fa-reply text-danger me-2"></i> Replying to: <span id="replyText" class="fw-semibold"></span>
                </div>
                <input type="hidden" id="replyIdInput" value="">
                <button type="button" id="cancelReply" class="btn btn-link btn-sm text-muted p-0"><i class="fas fa-times"></i></button>
            </div>

            <!-- Chat Input Bar -->
            <div class="chat-input-container">
                <form id="chatForm" class="d-flex align-items-center gap-2">
                    <label for="imageInput" class="btn btn-light rounded-circle text-muted" style="width: 42px; height: 42px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                        <i class="fas fa-paperclip"></i>
                        <input type="file" id="imageInput" accept="image/*" class="d-none">
                    </label>

                    <!-- Quick Emoji insertion -->
                    <div class="dropdown">
                        <button class="btn btn-light rounded-circle text-muted" type="button" data-bs-toggle="dropdown" style="width: 42px; height: 42px;">
                            <i class="far fa-smile"></i>
                        </button>
                        <div class="dropdown-menu p-2 fs-5" style="min-width: 180px;">
                            <span class="p-1 cursor-pointer" onclick="document.getElementById('messageInput').value += '❤️'">❤️</span>
                            <span class="p-1 cursor-pointer" onclick="document.getElementById('messageInput').value += '🥰'">🥰</span>
                            <span class="p-1 cursor-pointer" onclick="document.getElementById('messageInput').value += '😘'">😘</span>
                            <span class="p-1 cursor-pointer" onclick="document.getElementById('messageInput').value += '✨'">✨</span>
                            <span class="p-1 cursor-pointer" onclick="document.getElementById('messageInput').value += '🌹'">🌹</span>
                            <span class="p-1 cursor-pointer" onclick="document.getElementById('messageInput').value += '🥂'">🥂</span>
                        </div>
                    </div>

                    <input type="text" id="messageInput" class="form-control chat-input" placeholder="Write a message to your love..." autocomplete="off">

                    <button type="submit" class="btn btn-romantic rounded-circle p-0" style="width: 44px; height: 44px; display: flex; align-items: center; justify-content: center;">
                        <i class="fas fa-paper-plane"></i>
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- WebRTC Call Modal / Overlay -->
<div id="callOverlay" class="position-fixed top-0 start-0 w-100 h-100 bg-dark z-3 d-none flex-column align-items-center justify-content-center" style="z-index: 1050;">
    <div class="text-center text-white mb-4">
        <h4 id="callStatusText" class="mb-2">Incoming Video Call</h4>
        <p id="callPartnerName" class="text-white-50"><?= e($partner['name'] ?? 'Partner'); ?></p>
    </div>
    
    <div class="position-relative w-100 h-100" style="max-width: 800px; max-height: 600px; background: #000; border-radius: 12px; overflow: hidden;">
        <!-- Remote Video -->
        <video id="remoteVideo" class="w-100 h-100" autoplay playsinline style="object-fit: cover;"></video>
        
        <!-- Local Video -->
        <video id="localVideo" class="position-absolute bottom-0 end-0 m-3 border border-2 border-white rounded shadow" autoplay playsinline muted style="width: 150px; height: 200px; object-fit: cover;"></video>
        
        <!-- Profile Picture for Voice Calls -->
        <div id="voiceCallAvatar" class="position-absolute top-50 start-50 translate-middle d-none text-center">
            <?php if (!empty($partner['profile_photo'])): ?>
                <img src="<?= upload_url($partner['profile_photo']); ?>" class="rounded-circle border border-4 border-danger" style="width: 150px; height: 150px; object-fit: cover;">
            <?php else: ?>
                <div class="rounded-circle bg-danger text-white d-flex align-items-center justify-content-center fw-bold border border-4 border-white shadow" style="width: 150px; height: 150px; font-size: 60px;">
                    <?= strtoupper(substr($partner['name'] ?? 'P', 0, 1)); ?>
                </div>
            <?php endif; ?>
            <div id="voiceWave" class="mt-4 text-danger fs-3" style="animation: pulse 1s infinite alternate;"><i class="fas fa-microphone"></i></div>
        </div>
    </div>
    
    <div class="d-flex gap-4 mt-4 pb-4">
        <button id="btnAcceptCall" class="btn btn-success rounded-circle shadow-lg d-none" style="width: 60px; height: 60px; font-size: 24px;" onclick="acceptCall()">
            <i class="fas fa-phone"></i>
        </button>
        <button id="btnEndCall" class="btn btn-danger rounded-circle shadow-lg" style="width: 60px; height: 60px; font-size: 24px;" onclick="endCall()">
            <i class="fas fa-phone-slash"></i>
        </button>
        <button id="btnToggleAudio" class="btn btn-secondary rounded-circle shadow-lg" style="width: 60px; height: 60px; font-size: 24px;" onclick="toggleAudio()">
            <i class="fas fa-microphone"></i>
        </button>
        <button id="btnToggleVideo" class="btn btn-secondary rounded-circle shadow-lg" style="width: 60px; height: 60px; font-size: 24px;" onclick="toggleVideo()">
            <i class="fas fa-video"></i>
        </button>
    </div>
</div>

<style>
@keyframes pulse {
    0% { transform: scale(1); opacity: 0.8; }
    100% { transform: scale(1.2); opacity: 1; }
}
</style>

<script src="<?= asset('js/chat.js'); ?>"></script>
<script src="<?= asset('js/webrtc.js?v=' . time()); ?>"></script>
