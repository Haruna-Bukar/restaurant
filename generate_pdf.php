<?php
require __DIR__ . '/dompdf/autoload.inc.php';

include 'db.php';

use Dompdf\Dompdf;

// Get id
$id = intval($_GET['id']);

$sql = "SELECT r.*, CONCAT(c.firstname,' ',c.lastname) AS fullname, 
        c.phone, t.table_type 
        FROM tbl_reservations r 
        JOIN tbl_customers c ON r.customer_id = c.customer_id 
        JOIN tbl_tables t ON r.table_id = t.table_id
        WHERE r.reserve_id = ?";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

if (!$res) {
    die("Invalid ID");
}

$html = "
<h2>Pitti Restaurant Reservation Ticket</h2>
<p><b>Name:</b> {$res['fullname']}</p>
<p><b>Phone:</b> {$res['phone']}</p>
<p><b>Table:</b> {$res['table_type']}</p>
<p><b>Date:</b> {$res['date']}</p>
<p><b>Time:</b> {$res['time']}</p>
<p><b>Code:</b> {$res['reservation_code']}</p>
<p>Thank you for choosing us!</p>
";

$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A5', 'portrait');
$dompdf->render();
$dompdf->stream("ticket.pdf", ["Attachment" => true]);
?>
