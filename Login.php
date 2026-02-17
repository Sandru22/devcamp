<?php
session_start();
require_once 'conexiune.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = $_POST['email'];
    $password = $_POST['password'];

    

    $stmt = $conn->prepare("SELECT Id, password, role from users where email = ? ");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();

    if($user = $result->fetch_assoc()){
        if(password_verify($password,$user['password'])){
            $_SESSION['user_id']=$user['Id'];
            $_SESSION['role']=$user['role'];

            if($user['role']=='staff'){
                header("Location: staff_dashboard.php");
                exit();
            }else{
                header("Location: client_dashboard.php");
                exit();
            }
        }else{
            echo "Parola incorecta";
        }

    }else{
        echo "Userul nu exista";
    }
 }
?>

<!DOCTYPE html>
<html lang="ro">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <title>Inregistrare Doctor</title>
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6"><div class="card shadow">
                <div class="card-body">
                    <h3 class="card-title text-center">Creare Cont</h3>
                    <form method="POST" action="Login.php">
                        <div class="mb-3">
                            <label class="form-label">Adresa de email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Parola</label>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                        <button class="btn btn-primary w-100" type="submit">Autentificare</button>
                        <a href="Register.php" class="btn btn-primary w-100" type="submit">Inregistrare</a>
                    </form>
                </div>
            </div>
        </div>
    </div>


</body>
</html>