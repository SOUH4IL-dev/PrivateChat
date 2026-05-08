-- database/calls.sql
CREATE TABLE calls (
    id INT AUTO_INCREMENT PRIMARY KEY,
    caller_id INT NOT NULL,
    receiver_id INT NOT NULL,
    call_type ENUM('voice','video') NOT NULL,
    status ENUM('ringing','accepted','rejected','ended','missed') DEFAULT 'ringing',
    room_id VARCHAR(255) NOT NULL,
    started_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    ended_at DATETIME NULL,
    FOREIGN KEY (caller_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (receiver_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE call_signals (
    id INT AUTO_INCREMENT PRIMARY KEY,
    call_id INT NOT NULL,
    sender_id INT NOT NULL,
    signal_data LONGTEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (call_id) REFERENCES calls(id) ON DELETE CASCADE
);