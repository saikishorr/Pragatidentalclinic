<?php
// DB connection
$conn = new mysqli("localhost", "root", "", "pragati");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

date_default_timezone_set('Asia/Kolkata');

// Get POST values safely
$name = trim($_POST['name']);
$email = trim($_POST['email']);
$phone = trim($_POST['phone']);
$service = trim($_POST['service']);
$time = trim($_POST['time']);
$date = $_POST['date'];
$message = trim($_POST['message']);

// Basic validation (you can expand this)
if (empty($name) || empty($email) || empty($phone) || empty($service) || empty($time) || empty($date)) {
    echo "Please fill all required fields.";
    exit;
}
 
// Check if slot already booked
$slot_time = explode(' - ', $time)[0]; // Extract start time
$check_sql = "SELECT id FROM appointments WHERE date = ? AND time LIKE ?";
$stmt = $conn->prepare($check_sql);
$like_time = $slot_time . '%';
$stmt->bind_param("ss", $date, $like_time);
$stmt->execute();
$stmt->store_result();

if ($stmt->num_rows > 0) {
    echo "This time slot is already booked. Please select another.";
    exit;
}

// Insert new appointment
$sql = "INSERT INTO appointments (name, email, phone, service, time, date, message) VALUES (?, ?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("sssssss", $name, $email, $phone, $service, $time, $date, $message);

if ($stmt->execute()) {
    echo "Appointment booked successfully!";
} else {
    echo "Something went wrong. Please try again.";
}

$conn->close();
?>
