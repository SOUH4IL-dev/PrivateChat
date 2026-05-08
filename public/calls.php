<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Calls</title>

<link rel="stylesheet" href="../public/assets/css/chat.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
:root{
    --bg:#0b1220;
    --panel:#0f172a;
    --text:#e2e8f0;
    --muted:#94a3b8;
    --primary:#3b82f6;
    --border:#1e293b;
    --green:#22c55e;
    --red:#ef4444;
}

*{margin:0;padding:0;box-sizing:border-box}

body{
    font-family:Segoe UI;
    background:var(--bg);
    color:var(--text);
}

/* APP LAYOUT */
.app{
    display:flex;
    height:100vh;
}

/* NAV */
.nav-bar{
    width:70px;
    background:var(--panel);
    display:flex;
    flex-direction:column;
    align-items:center;
    padding:20px 0;
    gap:25px;
    border-right:1px solid var(--border);
}

.nav-bar i{
    font-size:18px;
    color:var(--muted);
    cursor:pointer;
    transition:0.2s;
}

.nav-bar i:hover{
    color:var(--primary);
    transform:scale(1.1);
}

/* SIDEBAR */
.sidebar{
    width:300px;
    background:#0c162a;
    padding:20px;
    border-right:1px solid var(--border);
}

.search{
    width:100%;
    padding:10px;
    border-radius:10px;
    border:1px solid var(--border);
    background:#0b1220;
    color:var(--text);
    margin-bottom:15px;
}

/* CALL LIST */
.calls-list{
    display:flex;
    flex-direction:column;
    gap:12px;
}

.call{
    display:flex;
    align-items:center;
    gap:10px;
    padding:10px;
    border-radius:12px;
    background:rgba(255,255,255,0.02);
    cursor:pointer;
    transition:0.2s;
}

.call:hover{
    background:rgba(59,130,246,0.1);
}

.avatar{
    width:40px;
    height:40px;
    border-radius:50%;
    background:var(--primary);
    display:flex;
    align-items:center;
    justify-content:center;
    font-weight:bold;
}

.call-info{
    flex:1;
}

.name{
    font-weight:600;
}

.status{
    font-size:12px;
    color:var(--muted);
    display:flex;
    align-items:center;
    gap:5px;
}

.status.missed{
    color:var(--red);
}

.status.outgoing{
    color:var(--green);
}

.call-icon{
    color:var(--primary);
    cursor:pointer;
}

/* MAIN */
.chat{
    flex:1;
    display:flex;
    flex-direction:column;
}

.chat-header{
    padding:20px;
    border-bottom:1px solid var(--border);
    background:var(--panel);
}

.calls-main{
    flex:1;
    padding:20px;
    overflow-y:auto;
}

/* BIG CALL CARD */
.call-card{
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:15px;
    background:rgba(255,255,255,0.03);
    border:1px solid var(--border);
    border-radius:16px;
    margin-bottom:15px;
}

.avatar.big{
    width:60px;
    height:60px;
    font-size:22px;
}

.info h4{
    margin-bottom:5px;
}

.info p{
    font-size:13px;
    color:var(--muted);
}

.info .missed{
    color:var(--red);
}

.btn-call{
    width:40px;
    height:40px;
    border:none;
    border-radius:50%;
    background:var(--green);
    color:white;
    cursor:pointer;
}

/* FLOATING BUTTON */
.floating-call{
    position:fixed;
    bottom:20px;
    right:20px;
    width:55px;
    height:55px;
    border-radius:50%;
    border:none;
    background:var(--primary);
    color:white;
    font-size:18px;
    cursor:pointer;
    box-shadow:0 10px 25px rgba(0,0,0,0.4);
}
</style>

</head>

<body>

<div class="app">

    <!-- NAV -->
    <div class="nav-bar">
        <i class="fa-solid fa-message" onclick="location.href='chat.php'"></i>
        <i class="fa-solid fa-phone" onclick="location.href='calls.php'"></i>
        <i class="fa-solid fa-user" onclick="location.href='profile.php'"></i>
        <i class="fa-solid fa-gear" onclick="location.href='settings.php'"></i>
    </div>

    <!-- SIDEBAR -->
    <div class="sidebar">

        <h2 style="margin-bottom:15px;">Calls</h2>

        <input type="text" class="search" placeholder="Search calls...">

        <div class="calls-list">

            <div class="call">
                <div class="avatar">A</div>

                <div class="call-info">
                    <div class="name">Ali</div>
                    <div class="status missed">
                        <i class="fa-solid fa-arrow-down"></i> Missed call
                    </div>
                </div>

                <i class="fa-solid fa-phone call-icon"></i>
            </div>

            <div class="call">
                <div class="avatar">S</div>

                <div class="call-info">
                    <div class="name">Sara</div>
                    <div class="status outgoing">
                        <i class="fa-solid fa-arrow-up"></i> Outgoing
                    </div>
                </div>

                <i class="fa-solid fa-phone call-icon"></i>
            </div>

        </div>

    </div>

    <!-- MAIN -->
    <div class="chat">

        <div class="chat-header">
            <h3>Recent Calls</h3>
        </div>

        <div class="calls-main">

            <div class="call-card">
                <div class="avatar big">M</div>

                <div class="info">
                    <h4>Mohamed</h4>
                    <p class="missed">
                        <i class="fa-solid fa-phone-slash"></i> Missed video call
                    </p>
                </div>

                <button class="btn-call">
                    <i class="fa-solid fa-phone"></i>
                </button>
            </div>

        </div>

        <button class="floating-call">
            <i class="fa-solid fa-phone"></i>
        </button>

    </div>

</div>

</body>
</html>