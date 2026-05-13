<?php

require_once __DIR__ . '/../../vendor/autoload.php';

use MongoDB\Client;

$client = new Client("mongodb+srv://<chat_user>:<chatuser@!123456>@cluster0.bxxtxnu.mongodb.net/?appName=Cluster0");

$db = $client->chat_app;

// collections (tables)
$users = $db->users;
$messages = $db->messages;
$chats = $db->chats;