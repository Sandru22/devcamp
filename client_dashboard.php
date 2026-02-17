<?php
session_start();
require_once 'conexiune.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client' ){
    header("Location: Login.php");
    exit();
}

$client_id = $_SESSION['user_id'];

$sql = "SELECT * FROM TICKETS WHERE client_id = ? order by CREATED_AT";

$stmt=$conn->prepare($sql);

if($stmt==false){
    die("eroare la incarcarea inregistrarilor" . $conn->error);
}

$stmt->bind_param("i",$client_id);

$stmt->execute();

$result = $stmt->get_result();
?>

<!DOCTYPE HTML>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tichetele Mele</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2 class="mb-4">Tichetele Mele</h2>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Titlu</th>
                        <th>Descriere</th>
                        <th>Status</th>
                        <th>Creat la</th>
                        <th>actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td>
                                    <span class="badge <?php echo ($row['status'] == 'open') ? 'bg-success' : 'bg-secondary'; ?>">
                                        <?php echo htmlspecialchars($row['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo $row['created_at']; ?></td>
                                <td>
    <a href="view_ticket.php?id=<?php echo $row['Id']; ?>" class="btn btn-sm btn-success">
        deschide
    </a>
</td>
                            </tr>

                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="4" class="text-center">Nu ai tichete deschise.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
        <a href="create_ticket.php" class="btn btn-primary">Crează Tichet Nou</a>
    </div>
</body>
</html>

<?php
if ($result) $result->close();
$conn->close();
?>
