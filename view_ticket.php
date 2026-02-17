<?php
session_start();
require_once 'conexiune.php';

$ticket_id = intval($_GET['id']);

// 1. Preluăm detaliile tichetului
$stmt = $conn->prepare("SELECT tickets.*, users.name FROM tickets JOIN users ON tickets.client_id = users.id WHERE tickets.id = ?");
$stmt->bind_param("i", $ticket_id);
$stmt->execute();
$ticket = $stmt->get_result()->fetch_assoc();

// 2. Preluăm mesajele (răspunsurile)
$msg_stmt = $conn->prepare("SELECT message.*, users.name, users.role FROM message JOIN users ON message.user_id = users.id WHERE ticket_id = ? ORDER BY created_at ASC");
$msg_stmt->bind_param("i", $ticket_id);
if ($msg_stmt === false) {
    die("Eroare SQL la mesaje: " . $conn->error);
}
$msg_stmt->execute();
$messages = $msg_stmt->get_result();
?>


<!DOCTYPE HTML>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tichetele</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-4">
    <div class="card mb-4 border-primary">
        <div class="card-body">
            <h3><?php echo htmlspecialchars($ticket['title']); ?></h3>
            <p class="text-muted">Deschis de: <?php echo htmlspecialchars($ticket['name']); ?> | Status: <?php echo $ticket['status']; ?></p>
            <hr>
            <p><?php echo htmlspecialchars($ticket['description']); ?></p>
        </div>
    </div>

    <div class="chat-box mb-4">
        <?php while($m = $messages->fetch_assoc()): ?>
            <div class="card mb-2 <?php echo ($m['role'] == 'staff') ? 'bg-light ms-5' : 'me-5'; ?>">
                <div class="card-body p-2">
                    <strong><?php echo htmlspecialchars($m['name']); ?> 
                        <small>(<?php echo $m['role']; ?>)</small>:</strong>
                    <p class="mb-0"><?php echo htmlspecialchars($m['message']); ?></p>
                </div>
            </div>
        <?php endwhile; ?>
    </div>

    <form action="send_message.php" method="POST">
        <input type="hidden" name="ticket_id" value="<?php echo $ticket_id; ?>">
        <textarea name="message" class="form-control mb-2" placeholder="Scrie un răspuns..." required></textarea>
        <button type="submit" class="btn btn-primary">Trimite Răspuns</button>
    </form>
</div>
        </body>
        </html>