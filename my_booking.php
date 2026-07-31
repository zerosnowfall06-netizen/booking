<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ตรวจสอบและยกเลิกการจองห้อง</title>
    <link rel="preconnect" href="https://googleapis.com">
    <link rel="preconnect" href="https://gstatic.com" crossorigin>
    <link href="https://googleapis.com/css2?family=Inter:wght@400;500;600&family=Sarabun:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', 'Sarabun', sans-serif; background: linear-gradient(135deg, #f1f5f9, #e2e8f0); padding: 40px 20px; color: #0f172a; }
        .container { max-width: 700px; margin: 0 auto; background: #ffffff; padding: 30px; border-radius: 24px; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05); border: 1px solid #f1f5f9; }
        h1 { font-size: 22px; font-weight: 600; text-align: center; margin-bottom: 20px; color: #1e293b; }
        .search-box { display: flex; gap: 10px; margin-bottom: 30px; }
        input[type="text"] { flex: 1; padding: 14px 16px; border: 1px solid #cbd5e1; border-radius: 12px; font-size: 15px; outline: none; background: #f8fafc; transition: all 0.2s; }
        input:focus { border-color: #2563eb; background: #fff; box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1); }
        .btn-search { padding: 14px 24px; background: #1e3a8a; color: white; border: none; border-radius: 12px; font-weight: 600; cursor: pointer; transition: background 0.2s; }
        .btn-search:hover { background: #1d4ed8; }
        .booking-card { border: 1px solid #e2e8f0; border-radius: 16px; padding: 20px; margin-bottom: 15px; background: #f8fafc; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 4px rgba(0,0,0,0.02); }
        .booking-details p { margin-bottom: 5px; font-size: 15px; }
        .room-tag { display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600; color: white; margin-top: 5px; }
        .room-standard { background: #10b981; } .room-deluxe { background: #3b82f6; } .room-suite { background: #8b5cf6; }
        .btn-cancel { background: #ef4444; color: white; padding: 10px 18px; border-radius: 8px; text-decoration: none; font-size: 14px; font-weight: 600; transition: background 0.2s; box-shadow: 0 2px 4px rgba(239,68,68,0.2); }
        .btn-cancel:hover { background: #dc2626; }
        .no-data { text-align: center; color: #64748b; padding: 20px; font-size: 15px; }
        .home-link { text-align: center; margin-top: 20px; }
        .home-link a { color: #64748b; font-size: 14px; text-decoration: none; }
        .home-link a:hover { color: #1e3a8a; text-decoration: underline; }
    </style>
</head>
<body>

<div class="container">
    <h1>🔍 ตรวจสอบ / ยกเลิกการจองห้องพัก</h1>
    
    <form action="my_booking.php" method="GET" class="search-box">
        <input type="text" name="search_name" placeholder="กรุณากรอกชื่อที่ใช้ในการจอง" value="<?php echo isset($_GET['search_name']) ? htmlspecialchars($_GET['search_name']) : ''; ?>" required>
        <button type="submit" class="btn-search">ค้นหา</button>
    </form>

    <?php
    if (isset($_GET['search_name'])) {
        $conn = new mysqli("localhost", "root", "", "booking_db");
        if ($conn->connect_error) { die("ฐานข้อมูลล้มเหลว: " . $conn->connect_error); }
        
        // 🛠️ ซ่อมแซมคำสั่งคัดกรองความปลอดภัยให้ถูกต้องเรียบร้อยแล้ว
        $search_name = $conn->real_escape_string($_GET['search_name']);
        
        $sql = "SELECT * FROM bookings WHERE name LIKE '%$search_name%' ORDER BY start_date DESC";
        $result = $conn->query($sql);

        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tag_class = 'room-standard';
                if ($row['room_type'] == 'Deluxe Room') { $tag_class = 'room-deluxe'; }
                if ($row['room_type'] == 'Suite Room') { $tag_class = 'room-suite'; }

                echo "<div class='booking-card'>";
                echo "<div class='booking-details'>";
                echo "<p><strong>ผู้จอง:</strong> คุณ " . htmlspecialchars($row['name']) . "</p>";
                echo "<p><strong>วันที่พัก:</strong> " . htmlspecialchars($row['start_date']) . " ถึง " . htmlspecialchars($row['end_date']) . "</p>";
                echo "<span class='room-tag $tag_class'>" . htmlspecialchars($row['room_type']) . "</span>";
                echo "</div>";
                echo "<div><a href='cancel_booking.php?id=" . $row['id'] . "&name=" . urlencode($search_name) . "' class='btn-cancel' onclick=\"return confirm('คุณแน่ใจใช่ไหมว่าต้องการยกเลิกการจองห้องนี้?')\">ยกเลิกการจอง</a></div>";
                echo "</div>";
            }
        } else {
            echo "<p class='no-data'>❌ ไม่พบข้อมูลการจองภายใต้ชื่อนี้</p>";
        }
        $conn->close();
    }
    ?>
    
    <div class="home-link">
        <a href="index.php">◀ กลับหน้าหลักจองห้องพัก</a>
    </div>
</div>

</body>
</html>
