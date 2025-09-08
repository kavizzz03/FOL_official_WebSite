<?php
header('Content-Type: application/json');

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Get the raw POST data
$data = json_decode(file_get_contents('php://input'), true);

// Check if data is received correctly
if ($data) {
    // Process the order data
    $total_amount = $data['totalAmount'];
    $email = $data['email'];
    $contact = $data['contact'];
    $address = $data['address'];
    $postalCode = $data['postalCode'];
    $province = $data['province'];
    $district = $data['district'];
    $paymentMethod = $data['paymentMethod'];
    $cartItems = $data['cartItems'];

    // Connect to the database (make sure your credentials are correct)
    $conn = new mysqli('localhost', 'u569550465_bogahawatte', 'Malshan2003#', 'u569550465_fol_clothing');
    if ($conn->connect_error) {
        die(json_encode(['success' => false, 'message' => 'Connection failed: ' . $conn->connect_error]));
    }

    // Insert the order into the database
    // Insert the order into the database
    $stmt = $conn->prepare("INSERT INTO orders (email, contact, address, postal_code, province, district, total_amount, payment_method, order_status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $order_status = 'Pending'; // Default status
    $stmt->bind_param("ssssssds", $email, $contact, $address, $postalCode, $province, $district, $total_amount, $paymentMethod, $order_status);
    
    if ($stmt->execute()) {
        $order_id = $stmt->insert_id; // Get the auto-generated order ID

        // Insert cart items into the order_items table
        foreach ($cartItems as $item) {
            $stmt = $conn->prepare("INSERT INTO order_items (order_id, item_id, quantity, price) VALUES (?, ?, ?, ?)");
            $stmt->bind_param("iiid", $order_id, $item['id'], $item['quantity'], $item['price']);
            $stmt->execute();
        }

        // Return a success response with the order ID and status
        echo json_encode(['success' => true, 'message' => 'Order placed successfully!', 'order_id' => $order_id, 'order_status' => $order_status]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Error placing the order.']);
    }

    // Close the database connection
    $conn->close();
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid data received.']);
}
?>
