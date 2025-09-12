<?php
$db = new PDO("sqlite:chat.db");
$db->exec("CREATE TABLE IF NOT EXISTS messages (
  id INTEGER PRIMARY KEY AUTOINCREMENT,
  username TEXT,
  message TEXT,
  created_at DATETIME DEFAULT CURRENT_TIMESTAMP
)");

$db->exec("DELETE FROM messages WHERE created_at < datetime('now', '-12 hours')");

$action = $_GET["action"] ?? "";

if ($action === "send") {
    $data = json_decode(file_get_contents("php://input"), true);
    $stmt = $db->prepare("INSERT INTO messages (username, message) VALUES (?, ?)");
    $stmt->execute([$data["username"], $data["message"]]);
    echo json_encode(["status" => "ok"]);
}

if ($action === "get") {
    $res = $db->query("SELECT username, message FROM messages ORDER BY id DESC LIMIT 50");
    echo json_encode(array_reverse($res->fetchAll(PDO::FETCH_ASSOC)));
}
