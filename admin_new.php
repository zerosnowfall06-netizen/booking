<?php
session_start();

// 1. [ระบบความปลอดภัย] เช็กสิทธิ์ล็อกอิน
if(!isset($_SESSION['admin_logged_in']) || !isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header("Location: login.php");
    exit();
}

// 2. [ระบบจัดการคิว] ระบบกดออกจากระบบ (Logout)
if(isset($_GET['action']) && $_GET['action'] == 'logout') {
    session_destroy();
    header("Location: login.php");
    exit();
}

// 3. เชื่อมต่อฐานข้อมูล
$conn = new mysqli("localhost", "root", "", "booking_db");
if ($conn->connect_error) {
    die("เชื่อมต่อฐานข้อมูลล้มเหลว: " . $conn->connect_error);
}

// 4. ตั้งค่าเดือนและปีปัจจุบัน
$month = isset($_GET['month']) ? intval($_GET['month']) : date('n');
$year = isset($_GET['year']) ? intval($_GET['year']) : date('Y');

// หาวันแรกของเดือน และจำนวนวันทั้งหมดในเดือนนั้น
$first_day_of_month = mktime(0, 0, 0, $month, 1, $year);
$max_days = date('t', $first_day_of_month);
$start_day_of_week = date('w', $first_day_of_month); // 0 (อาทิตย์) ถึง 6 (เสาร์)

// ชื่อเดือนภาษาไทย
$months_th = [1=>"มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม"];

// 5. ดึงข้อมูลการจองห้องทั้งหมดในเดือนนี้
$current_month_str = sprintf("%04d-%02d", $year, $month);
$sql = "SELECT id, name, room_type, start_date, end_date FROM bookings 
        WHERE start_date LIKE '$current_month_str-%' OR end_date LIKE '$current_month_str-%'";
$result = $conn->query($sql);

$bookings = [];
if ($result && $result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $bookings[] = $row;
    }
}
$conn->close();

// 6. คำนวณปุ่มเปลี่ยนเดือนให้ถูกต้อง
$prev_month = $month - 1;
$prev_year = $year;
if ($prev_month == 0) { $prev_month = 12; $prev_year--; }

$next_month = $month + 1;
$next_year = $year;
if ($next_month == 13) { $next_month = 1; $next_year++; }
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ระบบหลังบ้าน - ปฏิทินจองห้อง</title>
    <link rel="preconnect" href="https://googleapis.com">
    <link rel="preconnect" href="https://gstatic.com" crossorigin>
    <link href="https://googleapis.com/css2?family=Inter:wght@400;500;600&family=Sarabun:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; font-family: 'Inter', 'Sarabun', sans-serif; margin: 0; padding: 0; }
        body { background-color: #f8fafc; padding: 30px 20px; }
        .container { max-width: 1100px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 24px; box-shadow: 0 4px 20px rgba(0,0,0,0.05); border: 1px solid #f1f5f9; }
        
        .calendar-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; }
        .calendar-header h1 { font-size: 24px; color: #1e293b; font-weight: 600; }
        
        .btn-nav { background-color: #1e3a8a; color: white; padding: 10px 20px; text-decoration: none; border-radius: 10px; font-weight: 600; font-size: 14px; transition: all 0.2s; }
        .btn-nav:hover { background-color: #1d4ed8; }
        
        .logout-box { text-align: center; margin-bottom: 20px; }
        .btn-logout { color: #ef4444; font-weight: 600; text-decoration: none; font-size: 14px; transition: color 0.2s; }
        .btn-logout:hover { color: #b91c1c; text-decoration: underline; }

        .calendar-table { width: 100%; border-collapse: collapse; table-layout: fixed; }
        .calendar-table th { background-color: #1e3a8a; color: white; padding: 14px; text-align: center; font-weight: 600; font-size: 15px; width: 14.28%; border: none; }
        .calendar-table th:first-child { border-top-left-radius: 12px; }
        .calendar-table th:last-child { border-top-right-radius: 12px; }
        
        .calendar-table td { border: 1px solid #e2e8f0; height: 120px; vertical-align: top; padding: 8px; position: relative; background: #fff; }
        .day-number { font-weight: 600; color: #64748b; margin-bottom: 6px; display: block; font-size: 14px; }
        
        .booking-item { color: #ffffff; font-size: 11px; padding: 5px 8px; border-radius: 6px; margin-bottom: 4px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; cursor: pointer; display: block; text-decoration: none; font-weight: 500; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .booking-item:hover { opacity: 0.85; transform: translateY(-1px); }
        
        .room-standard { background-color: #10b981; }
        .room-deluxe { background-color: #3b82f6; }
        .room-suite { background-color: #8b5cf6; }
        .bg-empty { background-color: #f8fafc; }
    </style>
</head>
<body>

<div class="container">
    <div class="calendar-header">
        <a class="btn-nav" href="admin_new.php?month=<?php echo $prev_month; ?>&year=<?php echo $prev_year; ?>">◀ เดือนก่อนหน้า</a>
        <h1>🗓️ <?php echo $months_th[$month] . " " . ($year + 543); ?></h1>
        <a class="btn-nav" href="admin_new.php?month=<?php echo $next_month; ?>&year=<?php echo $next_year; ?>">เดือนถัดไป ▶</a>
    </div>

    <div class="logout-box">
        <a href="admin_new.php?action=logout" class="btn-logout" onclick="return confirm('คุณต้องการออกจากระบบใช่หรือไม่?')">[ 📴 ออกจากระบบผู้ดูแลระบบ ]</a>
    </div>
    
    <table class="calendar-table">
        <thead>
            <tr>
                <th>อาทิตย์</th>
                <th>จันทร์</th>
                <th>อังคาร</th>
                <th>พุธ</th>
                <th>พฤหัสบดี</th>
                <th>ศุกร์</th>
                <th>เสาร์</th>
            </tr>
        </thead>
        <tbody>
            <tr>
            <?php
            for ($i = 0; $i < $start_day_of_week; $i++) {
                echo "<td class='bg-empty'></td>";
            }

            $current_day = 1;
            $day_of_week = $start_day_of_week;

            while ($current_day <= $max_days) {
                if ($day_of_week == 7) {
                    echo "</tr><tr>";
                    $day_of_week = 0;
                }

                $current_date_str = sprintf("%04d-%02d-%02d", $year, $month, $current_day);
                echo "<td>";
                echo "<span class='day-number'>$current_day</span>";

                foreach ($bookings as $b) {
                    if ($current_date_str >= $b['start_date'] && $current_date_str <= $b['end_date']) {
                        $class_name = 'room-standard';
                        if ($b['room_type'] == 'Deluxe Room') { $class_name = 'room-deluxe'; }
                        if ($b['room_type'] == 'Suite Room') { $class_name = 'room-suite'; }

                        echo "<a href='admin_delete.php?delete_id=" . $b['id'] . "' class='booking-item $class_name' onclick=\"return confirm('คุณต้องการลบข้อมูลการจองห้องของ คุณ " . htmlspecialchars($b['name']) . " หรือไม่?')\">";
                        echo "🏨 " . htmlspecialchars($b['name']) . " [" . htmlspecialchars($b['room_type']) . "]";
                        echo "</a>";
                    }
                }

                echo "</td>";
                $current_day++;
                $day_of_week++;
            }

            while ($day_of_week < 7) {
                echo "<td class='bg-empty'></td>";
                $day_of_week++;
            }
            ?>
            </tr>
        </tbody>
    </table>
</div>

</body>
</html>
