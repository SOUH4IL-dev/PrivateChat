<?php
// public/video-call.php
session_start();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Video Call</title>
</head>
<body>
    <video id="localVideo" autoplay muted></video>
    <video id="remoteVideo" autoplay></video>

    <button onclick="initCall(true)">Start Video</button>

    <script src="assets/js/webrtc.js"></script>
    <script src="assets/js/signaling.js"></script>
</body>
</html>