<?php
$conn = new mysqli("localhost", "root", "", "booking_db");
if ($conn->connect_error) { 
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error); 
}

$name = $_POST['name'];
$room_type = $_POST['room_type'];
$start_date = $_POST['start_date'];
$end_date = $_POST['end_date'];

// ตรวจสอบคิวชนกัน เฉพาะในห้องประเภทเดียวกัน (room_type) เท่านั้น
$check = "SELECT * FROM bookings WHERE room_type = '$room_type' AND (
          ('$start_date' BETWEEN start_date AND end_date) 
          OR ('$end_date' BETWEEN start_date AND end_date)
          OR (start_date BETWEEN '$start_date' AND '$end_date')
         )";

$result = $conn->query($check);

if ($result->num_rows > 0) {
    header("Location: fail.php");
    exit();
} else {
    // บันทึกข้อมูลพร้อมประเภทห้องลงตาราง
    $sql = "INSERT INTO bookings (name, room_type, start_date, end_date, booking_date, booking_time) 
            VALUES ('$name', '$room_type', '$start_date', '$end_date', '$start_date', 'รายวัน/รายสัปดาห์')";
    
    if ($conn->query($sql) === TRUE) {
        header("Location: success.php");
        exit();
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }
}
$conn->close();
?>
