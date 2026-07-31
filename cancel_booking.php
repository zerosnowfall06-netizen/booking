<?php
if (isset($_GET['id'])) {
    $conn = new mysqli("localhost", "root", "", "booking_db");
    if ($conn->connect_error) { die("ฐานข้อมูลล้มเหลว: " . $conn->connect_error); }

    $id = intval($_GET['id']);
    $search_name = isset($_GET['name']) ? $_GET['name'] : '';

    // ลบข้อมูลออกตาม ID ที่ลูกค้าเลือก
    $sql = "DELETE FROM bookings WHERE id = $id";
    
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('ยกเลิกการจองห้องพักของคุณเรียบร้อยแล้ว'); window.location.href='my_booking.php?search_name=" . urlencode($search_name) . "';</script>";
    } else {
        echo "เกิดข้อผิดพลาด: " . $conn->error;
    }
    $conn->close();
} else {
    header("Location: my_booking.php");
    exit();
}
?>
