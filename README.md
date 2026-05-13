chat-app/
│
├── public/                      # 🌍 entry point (browser only)
│   ├── index.php              # redirect / home
│   ├── login.php
│   ├── register.php
│   ├── logout.php
│   ├── chat.php               # 💬 chat UI
│   ├── profile.php
│   ├── calls.php
│   ├── edit.profil.php
│   ├──
│   │
│   └── assets/
│       ├── css/
│       │   ├── app.css
│       │   └── chat.css
│       │   └── calls.css
│       │
│       ├── js/
│       │   ├── app.js
│       │   └── chat.js
│       │
│       └── images/
│
│
├── app/                        # 🧠 logic (clean & simple)
│   ├── config/
│   │   └── db.php             # DB connection
│   │
│   ├── core/
│   │   ├── auth.php           # requireLogin()
│   │   └── helpers.php
│   │
│   ├── services/              # ⭐ BEST PART (logic organized)
│   │   ├── AuthService.php
│   │   ├── UserService.php
│   │   └── ChatService.php
│   │
│   └── models/                # DB queries only
│       ├── User.php
│       ├── Message.php
│       └── Chat.php
│
│
├── ajax/                      # ⚡ API (frontend ↔ backend)
│   ├── auth/
│   │   ├── login.php
│   │   └── register.php
│   │
│   ├── chat/
│   │   ├── send.php
│   │   ├── get.php
│   │   ├── seen.php
│   │   ├── typing.php
│   │   ├──users.php
│   │   ├──delete.php
│   │   ├──get_typing.php
│   │   ├──search.php
│   │   ├──seend_audio.php
│   │   ├──
│   │
│   └── user/
│       ├── get.online.php
│       └── online.php
│
│
├── storage/
│   └── uploads/
│
│
├── database/ 
│   └── schema.sql
│
│
└── README.md
