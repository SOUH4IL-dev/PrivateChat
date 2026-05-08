<?php
// public/voice-call.php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Voice Call</title>
</head>
<body>
    <audio id="remoteVideo" autoplay></audio>

    <button onclick="initCall(false)">Start Voice</button>

    <script src="assets/js/webrtc.js"></script>
    <script src="assets/js/signaling.js"></script>
</body>
</html>