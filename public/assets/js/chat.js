let currentUser = null;
let typingTimer = null;
let isTyping = false;
let lastMessageId = 0;

/* ================= AUDIO RECORDING ================= */
let mediaRecorder;
let audioChunks = [];
let isRecording = false;

/* ================= SELECT USER ================= */
function selectUser(user) {
    currentUser = user;

    document.getElementById("chatWith").innerText = user.name;

    document.querySelectorAll(".user").forEach(el => el.classList.remove("active"));
    let active = document.querySelector(`[data-user="${user.id}"]`);
    if (active) active.classList.add("active");

    lastMessageId = 0;

    loadMessages(true);
}

/* ================= SEND MESSAGE ================= */
async function sendMessage() {
    if (!currentUser) return;

    let input = document.getElementById("message");
    let text = input.value.trim();

    if (!text) return;

    let formData = new FormData();
    formData.append("receiver_id", currentUser.id);
    formData.append("message", text);

    let res = await fetch("../ajax/chat/send.php", {
        method: "POST",
        body: formData
    });

    let data = await res.json();

    if (data.status === "success") {
        input.value = "";

        renderSingleMessage({
            sender_id: CURRENT_USER_ID,
            message: text,
            is_seen: 0
        });

        loadConversations();
    }
}

/* ================= SINGLE MESSAGE ================= */
function renderSingleMessage(m) {
    let box = document.getElementById("chat-box");

    let div = document.createElement("div");
    div.className = "msg me";

    div.innerHTML = `
        <div class="bubble">${escapeHTML(m.message)}</div>
        <div class="meta">✔</div>
    `;

    box.appendChild(div);
    box.scrollTop = box.scrollHeight;
}

/* ================= LOAD MESSAGES ================= */
async function loadMessages(force = false) {
    if (!currentUser) return;

    let res = await fetch(`../ajax/chat/get.php?receiver_id=${currentUser.id}`);
    let data = await res.json();

    if (data.status !== "success") return;

    let messages = data.messages;
    if (!messages) return;

    if (!force && messages.length && messages[messages.length - 1].id === lastMessageId) {
        return;
    }

    let box = document.getElementById("chat-box");
    box.innerHTML = "";

    messages.forEach(m => {

        let isMe = m.sender_id == CURRENT_USER_ID;

        let div = document.createElement("div");
        div.className = "msg " + (isMe ? "me" : "other");

        let html = "";

        /* TEXT */
        if (m.message) {
            html += `<div class="bubble">${escapeHTML(m.message)}</div>`;
        }

        /* IMAGE */
        if (m.file_path && m.file_type?.includes("image")) {
            html += `<img src="../${m.file_path}" class="msg-img">`;
        }

        /* AUDIO */
        if (m.file_path && m.file_type?.includes("audio")) {
            html += `
                <audio controls class="msg-audio">
                    <source src="../${m.file_path}" type="audio/webm">
                </audio>
            `;
        }

        /* STATUS */
        if (isMe) {
            html += `<div class="meta">${m.is_seen == 1 ? "✔✔" : "✔"}</div>`;
        }

        div.innerHTML = html;
        box.appendChild(div);

        lastMessageId = m.id;
    });

    box.scrollTop = box.scrollHeight;

    if (data.chat_id) markSeen(data.chat_id);
}

/* ================= MARK SEEN ================= */
async function markSeen(chatId) {
    await fetch("../ajax/chat/seen.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `chat_id=${chatId}`
    });
}

/* ================= LOAD CONVERSATIONS ================= */
async function loadConversations() {
    let res = await fetch("../ajax/chat/users.php");
    let users = await res.json();

    let container = document.getElementById("users");
    container.innerHTML = "";

    users.forEach(u => {

        let last = u.last_message
            ? u.last_message.slice(0, 22) + "..."
            : "Start chat";

        let time = u.last_time ? formatTime(u.last_time) : "";

        let div = document.createElement("div");
        div.className = "user";
        div.setAttribute("data-user", u.id);

        div.innerHTML = `
            <div class="avatar">${u.name[0]}</div>

            <div class="user-meta">
                <div class="top">
                    <span>${u.name}</span>
                    <span>${time}</span>
                </div>

                <div class="bottom">
                    <span>${escapeHTML(last)}</span>
                    ${u.unread > 0 ? `<span class="badge">${u.unread}</span>` : ""}
                </div>
            </div>
        `;

        div.onclick = () => selectUser(u);

        container.appendChild(div);
    });
}

/* ================= SEARCH ================= */
document.getElementById("search")?.addEventListener("input", async function () {

    let val = this.value.trim();
    let container = document.getElementById("users");

    if (val.length < 1) {
        loadConversations();
        return;
    }

    let res = await fetch(`../ajax/chat/search.php?q=${val}`);
    let users = await res.json();

    container.innerHTML = "";

    users.forEach(u => {
        let div = document.createElement("div");

        div.className = "user";
        div.innerHTML = `
            <div class="avatar">${u.name[0]}</div>
            <div>${u.name}</div>
        `;

        div.onclick = () => selectUser(u);

        container.appendChild(div);
    });
});

/* ================= TYPING ================= */
document.getElementById("message")?.addEventListener("input", () => {

    if (!currentUser) return;

    if (!isTyping) {
        isTyping = true;
        sendTyping(1);
    }

    clearTimeout(typingTimer);

    typingTimer = setTimeout(() => {
        isTyping = false;
        sendTyping(0);
    }, 1000);
});

async function sendTyping(status) {
    await fetch("../ajax/chat/typing.php", {
        method: "POST",
        headers: { "Content-Type": "application/x-www-form-urlencoded" },
        body: `receiver_id=${currentUser.id}&is_typing=${status}`
    });
}

/* ================= AUDIO RECORDING ================= */
async function toggleRecording() {

    if (!currentUser) return;

    if (!isRecording) {

        let stream = await navigator.mediaDevices.getUserMedia({ audio: true });

        mediaRecorder = new MediaRecorder(stream);
        audioChunks = [];

        mediaRecorder.start();
        isRecording = true;

        mediaRecorder.ondataavailable = e => {
            audioChunks.push(e.data);
        };

        mediaRecorder.onstop = async () => {

            let audioBlob = new Blob(audioChunks, { type: "audio/webm" });

            let formData = new FormData();
            formData.append("receiver_id", currentUser.id);
            formData.append("audio", audioBlob, "voice.webm");

            let res = await fetch("../ajax/chat/send_audio.php", {
                method: "POST",
                body: formData
            });

            let data = await res.json();

            if (data.status === "success") {
                loadMessages(true);
            }
        };

    } else {
        mediaRecorder.stop();
        isRecording = false;
    }
}

/* ================= REALTIME ================= */
setInterval(() => {
    if (currentUser) loadMessages();
}, 2000);

/* ================= HELPERS ================= */
function formatTime(dateStr) {
    if (!dateStr) return "";

    let d = new Date(dateStr);
    return d.getHours().toString().padStart(2, "0") + ":" +
           d.getMinutes().toString().padStart(2, "0");
}

function escapeHTML(str) {
    if (!str) return "";
    return str.replace(/[&<>"']/g, m => ({
        "&": "&amp;",
        "<": "&lt;",
        ">": "&gt;",
        '"': "&quot;",
        "'": "&#039;"
    }[m]));
}

/* ================= INIT ================= */
loadConversations();