<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use MongoDB\Client;

$client = new Client("mongodb+srv://admin:YOUR_PASSWORD@cluster0.xxxx.mongodb.net/");

$db = $client->chat_app;

// collections (tables)
$users = $db->users;
$messages = $db->messages;
$chats = $db->chats;