<?php
session_start();
require_once 'conexiune.php';

if(!isset($_SESSION['user_id'])|| $_SESSION['role'] !== 'client'){
    header("Location: Login.php");
    exit();
}

if($_SERVER['REQUEST_METHOD'] === 'POST'){
$title=$_POST['title'];
$description=$_POST['description'];
$client_id=$_SESSION['user_id'];

$stmt = $conn->prepare("INSERT INTO tickets (title, description, client_id, status) values (?,?,?,'open')");
$stmt->bind_param('ssi',$title,$description,$client_id);

if($stmt->execute()){
    header("Location: client_dashboard.php?success=1");
    }
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tichetele Mele</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6"><div class="card shadow">
                <div class="card-body">
                    <h3 class="card-title text-center">Create a ticket</h3>
                    <form method="POST">
                        <div class="mb-3">
                            <label class="form-label">Title</label>
                            <input type="text" name="title" class="form-control" required/>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea type="text" name="description" class="form-control" required></textarea>
                        </div>
                    <button class="btn btn-primary w-100" type="submit">Treimite ticket</button>
</form>
</div>
</div>
</div>
</div>
</body>
</html>