GIT:
Preia repo:
git clone <link_repository>

setare identitate:
git config --global user.name "Numele Tau"

git config --global user.email "email@exemplu.com"


preia:
git pull origin main

adauga:
git status
git add .(tot)
git commit -m "Mesaj clar si scurt"
git push origin main


PHP:
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ?"); // [cite: 89]
$stmt->bind_param("s", $email); // "s" pentru string, "i" pentru integer
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$stmt->close();

$stmt = $conn->prepare("INSERT INTO tickets (title, description) VALUES (?, ?)");
$stmt->bind_param("ss", $title, $description);
$stmt->execute();
$stmt->close();

$query = "UPDATE tickets SET status = ? WHERE id = ?";
$stmt = $conn->prepare($query);


$noul_status = 'closed';
$ticket_id = intval($_GET['id']); // Conversie în int pentru siguranță 
$stmt->bind_param("si", $noul_status, $ticket_id);
if ($stmt->execute()) {
    echo "Tichetul a fost actualizat cu succes! [cite: 185]";
} else {
    echo "Eroare la actualizare: " . $conn->error; [cite: 131]
}
$stmt->close();

//transmitere parametru prin link:

<a href="view_ticket.php?id=<?php echo $row['Id']; ?>" class="btn btn-primary">
    Vezi Tichet
</a>

$ticket_id = intval($_GET['id']); // Folosim intval pentru securitate conform ghidului [cite: 47]
echo "Vezi tichetul cu numărul: " . $ticket_id;

//proalore parametru din html:
<form action="save_message.php" method="POST">
    <input type="hidden" name="ticket_id" value="123">

    <label>Mesajul tău:</label>
    <textarea name="continut_mesaj" required></textarea>

    <button type="submit">Trimite</button>
</form>

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['ticket_id']; // Vine din name="ticket_id"
    $mesaj = $_POST['continut_mesaj']; // Vine din name="continut_mesaj"

    // Acum poți folosi $mesaj într-un Prepared Statement [cite: 89, 153]
}


//Selecturi:
<form method="POST" action="procesare.php">
    <label for="status">Schimbă Status:</label>
    <select name="status_nou" class="form-select" id="status">
        <option value="open">Deschis</option>
        <option value="pending">În așteptare</option>
        <option value="closed">Închis</option>
    </select>
    
    <button type="submit" class="btn btn-primary mt-2">Actualizează</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificăm dacă cheia există în POST
    if (isset($_POST['status_nou'])) {
        $status_ales = $_POST['status_nou']; // Va fi 'open', 'pending' sau 'closed'
        
        // RECOMANDARE DEVCAMP: Folosește Prepared Statements pentru a salva valoarea
        $stmt = $conn->prepare("UPDATE tickets SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status_ales, $ticket_id);
        $stmt->execute();
        $stmt->close();
    }
}
?>

//Header html:
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
                <div class="card-body"></div>


//form:
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

//tabel:
<div class="container mt-5">
        <h2 class="mb-4">Tichetele</h2>
        <a href="logout.php" class="btn btn-outline-danger">Logout</a>
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
