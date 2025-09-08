<?php
include 'db.php';

// Set timezone to Sri Lanka
date_default_timezone_set('Asia/Colombo');

// Generate unique order ID
$orderId = uniqid('ORD');

// Read items from POST
$items = isset($_POST['items']) ? $_POST['items'] : '[]';
$decoded = json_decode($items, true) ?? [];

// Read customer details safely
$email      = $conn->real_escape_string($_POST['email'] ?? '');
$contact    = $conn->real_escape_string($_POST['contact'] ?? '');
$postalCode = $conn->real_escape_string($_POST['postalCode'] ?? '');
$province   = $conn->real_escape_string($_POST['province'] ?? '');
$district   = $conn->real_escape_string($_POST['district'] ?? '');
$payment    = $conn->real_escape_string($_POST['payment'] ?? '');

// Handle bank slip upload
$bankSlipPath = null;
if($payment === "Bank Deposit" && isset($_FILES['bankSlip']) && $_FILES['bankSlip']['error'] === UPLOAD_ERR_OK){
    $uploadDir = __DIR__ . "/uploads/bank_slips/";
    if(!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    $fileName = time() . "_" . preg_replace("/[^a-zA-Z0-9_\.-]/", "_", basename($_FILES['bankSlip']['name']));
    $target = $uploadDir . $fileName;

    if(move_uploaded_file($_FILES['bankSlip']['tmp_name'], $target)) {
        $bankSlipPath = "uploads/bank_slips/" . $fileName;
    }
}

// Ensure user exists in DB
function ensureUserExists($conn,$email,$contact,$postalCode,$province,$district){
    $stmt = $conn->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
    $stmt->bind_param("s",$email);
    $stmt->execute();
    $result = $stmt->get_result();
    if($result->num_rows===0){
        $insert=$conn->prepare("INSERT INTO users (email, phone, postal, province, district) VALUES (?,?,?,?,?)");
        $insert->bind_param("sssss",$email,$contact,$postalCode,$province,$district);
        $insert->execute();
    }else{
        $update=$conn->prepare("UPDATE users SET phone=?, postal=?, province=?, district=? WHERE email=?");
        $update->bind_param("sssss",$contact,$postalCode,$province,$district,$email);
        $update->execute();
    }
}
ensureUserExists($conn,$email,$contact,$postalCode,$province,$district);

// Encode items JSON safely for DB
$itemsJson = json_encode($decoded, JSON_UNESCAPED_UNICODE);

// Insert order with Sri Lanka timestamp
$createdAt = date('Y-m-d H:i:s'); // Colombo local time
$stmt = $conn->prepare("INSERT INTO orders 
(order_id, items, email, contact, postal_code, province, district, payment_method, bank_slip, created_at)
VALUES (?,?,?,?,?,?,?,?,?,?)");
$stmt->bind_param("ssssssssss",$orderId,$itemsJson,$email,$contact,$postalCode,$province,$district,$payment,$bankSlipPath,$createdAt);

if($stmt->execute()){
    // Build order table HTML
    $orderTable = "<table border='1' cellpadding='8' cellspacing='0' style='border-collapse:collapse; width:100%;'>
    <tr style='background:#f4f4f4;'><th>Item</th><th>Qty</th><th>Price</th><th>Total</th></tr>";

    $grandTotal = 0;
    foreach($decoded as $item){
        $name  = htmlspecialchars($item['name']);
        $qty   = intval($item['quantity'] ?? 1); 
        $price = floatval($item['price']);
        $total = $qty * $price;
        $grandTotal += $total;
        $orderTable .= "<tr>
            <td>$name</td>
            <td style='text-align:center;'>$qty</td>
            <td style='text-align:right;'>LKR ".number_format($price,2)."</td>
            <td style='text-align:right;'>LKR ".number_format($total,2)."</td>
        </tr>";
    }
    $orderTable .= "<tr style='font-weight:bold;'>
        <td colspan='3' style='text-align:right;'>Grand Total</td>
        <td style='text-align:right;'>LKR ".number_format($grandTotal,2)."</td>
    </tr></table>";

    // Common headers for HTML email
    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: FOL Clothing <noreply@folclothing.com>\r\n";
    $headers .= "Reply-To: noreply@folclothing.com\r\n";

    // ------------------- Customer Email -------------------
    $subjectCustomer = "🎉 Your FOL Clothing Order #$orderId is Confirmed!";
    $bodyCustomer = "
    <html><body style='font-family:Arial,sans-serif;'>
    <div style='background:#1abc9c; color:white; padding:15px; border-radius:10px 10px 0 0;'><h2>FOL Clothing</h2></div>
    <div style='background:#fff; padding:20px; border-radius:0 0 10px 10px;'>
        <p>Hi there 👋,</p>
        <p>Your order <strong>#$orderId</strong> has been received and is being processed.</p>
        <h4>Order Details:</h4>$orderTable
        <h4>Payment Method:</h4><p>".htmlspecialchars($payment)."</p>
        <p style='color:#d35400;'><strong>Note:</strong> If you don’t receive your order within <strong>7 days</strong>, please contact us at support@folclothing.com.</p>
    </div></body></html>";
    @mail($email, $subjectCustomer, $bodyCustomer, $headers);

    // ------------------- Admin Email -------------------
    $adminEmail = "support@folclothing.com";
    $res = $conn->query("SELECT owner_email FROM owner_details LIMIT 1");
    if($res && $res->num_rows>0) $adminEmail = $res->fetch_assoc()['owner_email'];

    $subjectAdmin = "📦 New Order Received: #$orderId";
    $bodyAdmin = "
    <html><body style='font-family:Arial,sans-serif;'>
    <div style='background:#e67e22; color:white; padding:15px; border-radius:10px 10px 0 0;'><h2>New Order Alert</h2></div>
    <div style='background:#fff; padding:20px; border-radius:0 0 10px 10px;'>
        <p>A new order has been placed.</p>
        <h4>Customer Info:</h4>
        <p>Email: ".htmlspecialchars($email)."<br>Phone: ".htmlspecialchars($contact)."</p>
        <h4>Payment Method:</h4><p>".htmlspecialchars($payment)."</p>";
    if($bankSlipPath) $bodyAdmin .= "<p><strong>Bank Slip:</strong> <a href='".htmlspecialchars($bankSlipPath)."' target='_blank'>View Slip</a></p>";
    $bodyAdmin .= "<h4>Order Items:</h4>$orderTable</div></body></html>";
    @mail($adminEmail, $subjectAdmin, $bodyAdmin, $headers);

    echo json_encode(['success'=>true,'order_id'=>$orderId]);
} else {
    echo json_encode(['success'=>false,'error'=>$stmt->error]);
}

$conn->close();
?>
