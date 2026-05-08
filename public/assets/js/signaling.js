// public/assets/js/signaling.js
async function sendSignal(data) {
    await fetch("/ajax/calls/signal.php", {
        method: "POST",
        headers: {
            "Content-Type": "application/x-www-form-urlencoded"
        },
        body: new URLSearchParams({
            call_id: window.callId,
            signal_data: JSON.stringify(data)
        })
    });
}

async function createOffer() {
    const offer = await peerConnection.createOffer();
    await peerConnection.setLocalDescription(offer);

    sendSignal({
        offer: offer
    });
}

async function createAnswer(offer) {
    await peerConnection.setRemoteDescription(
        new RTCSessionDescription(offer)
    );

    const answer = await peerConnection.createAnswer();
    await peerConnection.setLocalDescription(answer);

    sendSignal({
        answer: answer
    });
}