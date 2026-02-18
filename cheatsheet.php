session_start();
if(!isset($_SESSION['user_id'])|| $_ssesion['role']!==cleint){
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