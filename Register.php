<?php 
require('conexiune.php');

if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $namse = $_POST['name'];
    $email= $_POST['email'];
    $role = $_POST['role'];

    if (strlen($password) < 8) {
    echo "Eroare: Parola trebuie să aibă cel puțin 8 caractere pentru siguranța contului tău!";
    }

    $hashed_password= password_hash($_POST['password'], PASSWORD_DEFAULT);

    $stmt = $conn->prepare("INSERT INTO USERS (name,email,password, role) values(?,?,?,?)");
    $stmt->bind_param("ssss", $namse,$email,$hashed_password,$role);

    if($stmt->execute()){
        header("Location: ;ogin.php");
        exit();
    }else{
        echo "Eroare la inregistraere" . $conn->error;
    }
   $stmt->close();
}
?>

<!DOCTYPE HTML>
<HTML lang='ro'>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-6"><div class="card shadow">
                <div class="card-body">
                    <h3 class="card-title text-center">Creare Cont</h3>
                    <form method="POST" action="Register.php">
                        <div class="mb-3">
                            <label class="form-label">Nume complet</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Adresa de email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Parola</label>
                            <input type="password" name="password" class="form-control" minlength=8 pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W_]).{8,}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">rolul</label>
                            <select class="form-select" name="role">
                                <option value="client">Client</option>
                                <option value="staff">Staff</option>
                            </select>
                        </div>
                        <button class="btn btn-primary w-100" type="submit">Inregistrare</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</body>
</html>