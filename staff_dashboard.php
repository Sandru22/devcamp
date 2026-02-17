<?php
session_start();
require_once 'conexiune.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'staff' ){
    header("Location: Login.php");
    exit();
}

$status_filter= isset($_GET['filter_status']) ? $_GET['filter_status'] : '';
$sql = "SELECT tickets.*, users.name FROM TICKETS JOIN users on tickets.client_id = users.id ";




if($status_filter !== ''){
    $sql .= " where tickets.status = ?";
    $sql .= " order by tickets.created_at desc";
    $stmt=$conn->prepare($sql);
    $stmt->bind_param("s",$status_filter);
}else{
    $sql .= " order by tickets.created_at desc";
    $stmt=$conn->prepare($sql);
}

if($stmt==false){
    die("eroare la incarcarea inregistrarilor" . $conn->error);
}

$stmt->execute();

$result = $stmt->get_result();
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
    <div class="container mt-5">
        <h2 class="mb-4">Tichetele</h2>
        <div class="row mb-4">
            <div class="col mb-6">
                <form method="GET" class="d-flex gap-2">
                    <select name="filter_status" class="form-select">
                        <option value=''>Toate ticketele</option>
                        <option value='open'>deschise</option>
                        <option value='peending'>peending</option>
                        <option value='closed'>closed</option>

</select>
<button type="submit" class="btn btn-secondary">filtreaza</button>
<a href="staff_dashboard.php" class="btn btn-outline-secondary">Reset</a>
</form>
</div>
</div>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>Nume</th>
                        <th>Titlu</th>
                        <th>Descriere</th>
                        <th>Status</th>
                        <th>Creat la</th>
                        <th>Actiuni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['name']); ?></td>
                                <td><?php echo htmlspecialchars($row['title']); ?></td>
                                <td><?php echo htmlspecialchars($row['description']); ?></td>
                                <td>
                                    <span class="badge <?php echo ($row['status'] == 'open') ? 'bg-success' : 'bg-secondary'; ?>">
                                        <?php echo htmlspecialchars($row['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo $row['created_at']; ?></td>
                                <td>
        <a href="view_ticket.php?id=<?php echo $row['Id']; ?>" 
           class="btn btn-sm btn-success">
           deschide
        </a>
</td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr><td colspan="6" class="text-center">Nu sunt tichete deschise.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>

<?php
if ($result) $result->close();
$conn->close();
?>
