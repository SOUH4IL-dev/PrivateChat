<?php
// app/config/webrtc.php
return [
    "iceServers" => [
        ["urls" => "stun:stun.l.google.com:19302"],
        ["urls" => "stun:global.stun.twilio.com:3478"]
    ]
];