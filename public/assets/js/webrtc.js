// public/assets/js/webrtc.js
let localStream;
let peerConnection;

const config = {
    iceServers: [
        { urls: "stun:stun.l.google.com:19302" }
    ]
};

async function initCall(video = true) {
    localStream = await navigator.mediaDevices.getUserMedia({
        video: video,
        audio: true
    });

    document.getElementById("localVideo").srcObject = localStream;

    peerConnection = new RTCPeerConnection(config);

    localStream.getTracks().forEach(track => {
        peerConnection.addTrack(track, localStream);
    });

    peerConnection.ontrack = event => {
        document.getElementById("remoteVideo").srcObject = event.streams[0];
    };

    peerConnection.onicecandidate = event => {
        if (event.candidate) {
            sendSignal({
                candidate: event.candidate
            });
        }
    };
}