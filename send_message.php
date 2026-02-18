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

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['close_ticket'])) {
    if ($_SESSION['role'] == 'staff') { // Doar staff-ul are voie [cite: 59]
        $ticket_id = intval($_POST['ticket_id']);
        
        // Folosim Prepared Statements pentru siguranță [cite: 89, 112]
        $close_stmt = $conn->prepare("UPDATE tickets SET status = 'closed' WHERE id = ?");
        $close_stmt->bind_param("i", $ticket_id);
        
        if ($close_stmt->execute()) {
            $close_stmt->close();
            // Redirecționare conform fluxului de lucru [cite: 105]
            header("Location: view_ticket.php?id=" . $ticket_id);
            exit();
        }
    } else {
        die("Acțiune neautorizată!"); // Protecție simplă [cite: 47]
    }
}
?>