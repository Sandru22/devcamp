<?php
session_start();
require_once 'conexiune.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_SESSION['user_id'])) {
    $ticket_id = intval($_POST['ticket_id']);
    $user_id = $_SESSION['user_id'];
    $message = $_POST['message'];

    $stmt = $conn->prepare("INSERT INTO message (ticket_id, user_id, message) VALUES (?, ?, ?)");
    $stmt->bind_param("iis", $ticket_id, $user_id, $message);
    
    if ($stmt->execute()) {
        if($_SESSION['role'] == 'staff'){
        $update_stmt = $conn->prepare("UPDATE tickets SET status = 'peending' WHERE id = ?");
                $update_stmt->bind_param("i", $ticket_id);
                $update_stmt->execute();
        }
        header("Location: view_ticket.php?id=" . $ticket_id);
        exit();
    }
}
?>