<?php
date_default_timezone_set('Asia/Kolkata');
$conn = new mysqli("localhost", "root", "", "your_database");

$date = $_GET['date'] ?? date('Y-m-d');
$current_time = new DateTime();
$chosen_date = new DateTime($date);
$is_today = $chosen_date->format('Y-m-d') === $current_time->format('Y-m-d');

$start = new DateTime('18:00');
$end = new DateTime('21:00');
$interval = new DateInterval('PT30M');
$times = [];

while ($start < $end) {
  $slot_start = clone $start;
  $slot_end = (clone $start)->add($interval);
  $slot = $slot_start->format('H:i') . ' - ' . $slot_end->format('H:i');

  if (!$is_today || $slot_start > $current_time) {
    $slot_time = $slot_start->format('H:i');
    $sql = "SELECT COUNT(*) as count FROM appointments WHERE time LIKE '$slot_time%' AND date = '$date'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    if ($row['count'] == 0) {
      $times[] = $slot;
    }
  }
  $start->add($interval);
}

echo json_encode($times);
?>
