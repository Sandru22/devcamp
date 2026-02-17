<?php
session_start();
require_once 'conexiune.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role']!='staff'){
    die("acces denied");
}

if(isset($_GET['id']) && isset($_GET['status'])){
    $id=intval($_GET['id']);
    $new_status=$_GET['status'];

    $allow_Status=['open','peending','closed'];

    if(!in_array($new_status,$allow_Status)){
        die("invalid status");
    }

    $query = "UPDATE tickets set status = ? where id=?";
    $stmt=$conn->prepare($query);

    $stmt->bind_param('si',$new_status,$id);

   if($stmt->execute()){
    header("Location: staff_dashboard.php?msg=TicketActualizat");
    exit();
   }else{
    die("Eroare la rezolvara tichetului");
   }

}

?>