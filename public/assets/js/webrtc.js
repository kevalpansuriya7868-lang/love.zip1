let localStream;
let remoteStream;
let peerConnection;
let currentCallId = null;
let currentCallType = 'video';
let isCaller = false;
let callPollInterval = null;

const configuration = {
    iceServers: [
        { urls: 'stun:stun.l.google.com:19302' },
        { urls: 'stun:stun1.l.google.com:19302' }
    ]
};

const els = {};
function getEls() {
    if (els.callOverlay) return els;
    els.callOverlay = document.getElementById('callOverlay');
    els.localVideo = document.getElementById('localVideo');
    els.remoteVideo = document.getElementById('remoteVideo');
    els.voiceCallAvatar = document.getElementById('voiceCallAvatar');
    els.callStatusText = document.getElementById('callStatusText');
    els.btnAcceptCall = document.getElementById('btnAcceptCall');
    els.btnToggleAudio = document.getElementById('btnToggleAudio');
    els.btnToggleVideo = document.getElementById('btnToggleVideo');
    return els;
}

// API helper
async function callApi(endpoint, data) {
    const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
    try {
        const res = await fetch(window.APP_URL + '/api/call/' + endpoint, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify(data)
        });
        return await res.json();
    } catch (e) {
        console.error('API Error:', e);
        return { success: false };
    }
}

async function getMediaStream(type) {
    try {
        if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
            alert('Your browser does not support media devices, or you are not using a secure connection (HTTPS or localhost is required for calling).');
            return null;
        }
        const constraints = {
            audio: true,
            video: type === 'video'
        };
        const stream = await navigator.mediaDevices.getUserMedia(constraints);
        const e = getEls();
        localStream = stream;
        e.localVideo.srcObject = stream;
        return stream;
    } catch (e) {
        alert('Could not access camera/microphone. Please allow permissions.');
        console.error(e);
        return null;
    }
}

function setupPeerConnection() {
    peerConnection = new RTCPeerConnection(configuration);
    
    // Add local tracks
    if (localStream) {
        localStream.getTracks().forEach(track => {
            peerConnection.addTrack(track, localStream);
        });
    }

    const e = getEls();
    // Handle remote tracks
    peerConnection.ontrack = event => {
        e.remoteVideo.srcObject = event.streams[0];
        e.callStatusText.innerText = 'Connected';
    };

    // Handle ICE candidates
    peerConnection.onicecandidate = event => {
        if (event.candidate && currentCallId) {
            callApi('candidate', {
                call_id: currentCallId,
                type: isCaller ? 'caller' : 'receiver',
                candidate: JSON.stringify(event.candidate)
            });
        }
    };
}

async function startCall(type) {
    currentCallType = type;
    isCaller = true;
    
    const stream = await getMediaStream(type);
    if (!stream) return;

    const e = getEls();
    showCallUI('Calling...', type);
    e.btnAcceptCall.classList.add('d-none'); // Hide accept for caller
    
    setupPeerConnection();
    
    const offer = await peerConnection.createOffer();
    await peerConnection.setLocalDescription(offer);
    
    const res = await callApi('initiate', {
        type: type,
        offer: JSON.stringify(offer)
    });
    
    if (res.success) {
        currentCallId = res.call_id;
        startPolling();
    } else {
        endCall();
        alert('Could not start call.');
    }
}

async function acceptCall() {
    if (!currentCallId) return;
    
    const stream = await getMediaStream(currentCallType);
    if (!stream) {
        rejectCall();
        return;
    }
    
    const e = getEls();
    e.btnAcceptCall.classList.add('d-none');
    e.callStatusText.innerText = 'Connecting...';
    
    setupPeerConnection();
    
    // The offer should be set from the polling loop that showed the accept UI
    // we assume setRemoteDescription is already called or will be called with the offer
    // Let's fetch the latest call details to get the offer safely
    const res = await fetch(window.APP_URL + '/api/call/poll', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    const json = await res.json();
    if (json.call && json.call.offer) {
        await peerConnection.setRemoteDescription(new RTCSessionDescription(JSON.parse(json.call.offer)));
        
        const answer = await peerConnection.createAnswer();
        await peerConnection.setLocalDescription(answer);
        
        await callApi('answer', {
            call_id: currentCallId,
            answer: JSON.stringify(answer)
        });
        
        startPolling();
    }
}

async function rejectCall() {
    if (currentCallId) {
        await callApi('reject', { call_id: currentCallId });
    }
    cleanupCall();
}

async function endCall() {
    if (currentCallId) {
        await callApi('end', { call_id: currentCallId });
    }
    cleanupCall();
}

function toggleAudio() {
    const e = getEls();
    if (localStream) {
        const audioTrack = localStream.getAudioTracks()[0];
        if (audioTrack) {
            audioTrack.enabled = !audioTrack.enabled;
            e.btnToggleAudio.classList.toggle('btn-secondary');
            e.btnToggleAudio.classList.toggle('btn-danger');
            e.btnToggleAudio.innerHTML = audioTrack.enabled ? '<i class="fas fa-microphone"></i>' : '<i class="fas fa-microphone-slash"></i>';
        }
    }
}

function toggleVideo() {
    const e = getEls();
    if (localStream && currentCallType === 'video') {
        const videoTrack = localStream.getVideoTracks()[0];
        if (videoTrack) {
            videoTrack.enabled = !videoTrack.enabled;
            e.btnToggleVideo.classList.toggle('btn-secondary');
            e.btnToggleVideo.classList.toggle('btn-danger');
            e.btnToggleVideo.innerHTML = videoTrack.enabled ? '<i class="fas fa-video"></i>' : '<i class="fas fa-video-slash"></i>';
        }
    }
}

function showCallUI(status, type) {
    const e = getEls();
    e.callOverlay.classList.remove('d-none');
    e.callOverlay.classList.add('d-flex');
    e.callStatusText.innerText = status;
    
    if (type === 'voice') {
        e.localVideo.classList.add('d-none');
        e.remoteVideo.classList.add('d-none');
        e.voiceCallAvatar.classList.remove('d-none');
        e.btnToggleVideo.classList.add('d-none');
    } else {
        e.localVideo.classList.remove('d-none');
        e.remoteVideo.classList.remove('d-none');
        e.voiceCallAvatar.classList.add('d-none');
        e.btnToggleVideo.classList.remove('d-none');
    }
}

function cleanupCall() {
    const e = getEls();
    if (localStream) {
        localStream.getTracks().forEach(track => track.stop());
        localStream = null;
    }
    if (peerConnection) {
        peerConnection.close();
        peerConnection = null;
    }
    currentCallId = null;
    isCaller = false;
    
    e.callOverlay.classList.remove('d-flex');
    e.callOverlay.classList.add('d-none');
    
    e.localVideo.srcObject = null;
    e.remoteVideo.srcObject = null;
    
    if (callPollInterval) {
        clearInterval(callPollInterval);
        callPollInterval = null;
    }
}

// Call Polling
let lastCandidatesCount = 0;

function startPolling() {
    if (callPollInterval) return;
    callPollInterval = setInterval(pollCallStatus, 2000);
}

async function pollCallStatus() {
    try {
        const res = await fetch(window.APP_URL + '/api/call/poll', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const json = await res.json();
        
        if (!json.call) {
            if (currentCallId) {
                // Call ended abruptly
                cleanupCall();
            }
            return;
        }
        
        const call = json.call;
        const userId = json.user_id;
        const e = getEls();
        
        // Incoming Call
        if (call.status === 'ringing' && call.receiver_id == userId && !currentCallId) {
            currentCallId = call.id;
            currentCallType = call.type;
            isCaller = false;
            
            showCallUI('Incoming ' + (call.type === 'video' ? 'Video' : 'Voice') + ' Call...', call.type);
            e.btnAcceptCall.classList.remove('d-none');
            startPolling();
            return;
        }
        
        if (!currentCallId || call.id != currentCallId) return;
        
        if (call.status === 'ended' || call.status === 'rejected') {
            cleanupCall();
            return;
        }
        
        if (isCaller && call.status === 'answered' && call.answer) {
            if (!peerConnection.currentRemoteDescription) {
                await peerConnection.setRemoteDescription(new RTCSessionDescription(JSON.parse(call.answer)));
                e.callStatusText.innerText = 'Connected';
            }
        }
        
        // Handle ICE Candidates
        const candidatesStr = isCaller ? call.receiver_candidates : call.caller_candidates;
        if (candidatesStr && peerConnection && peerConnection.remoteDescription) {
            const candidates = JSON.parse(candidatesStr);
            if (candidates.length > lastCandidatesCount) {
                for (let i = lastCandidatesCount; i < candidates.length; i++) {
                    await peerConnection.addIceCandidate(new RTCIceCandidate(candidates[i]));
                }
                lastCandidatesCount = candidates.length;
            }
        }
    } catch (e) {
        console.error('Call poll error', e);
    }
}

// Global poll for incoming calls even when not in a call
setInterval(pollCallStatus, 3000);

// Expose functions globally for inline onclick handlers
window.startCall = startCall;
window.acceptCall = acceptCall;
window.rejectCall = rejectCall;
window.endCall = endCall;
window.toggleAudio = toggleAudio;
window.toggleVideo = toggleVideo;
