<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Luxury Room Booking System</title>
    <link href="https://googleapis.com" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', 'Sarabun', sans-serif;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            color: #0f172a;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .booking-container {
            background: #ffffff;
            border-radius: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
            padding: 40px;
            width: 100%;
            max-width: 460px;
            border: 1px solid #f1f5f9;
        }
        .brand-header { text-align: center; margin-bottom: 32px; }
        .brand-header .icon { font-size: 45px; margin-bottom: 8px; display: inline-block; }
        .brand-header h1 { font-size: 24px; font-weight: 600; color: #1e293b; letter-spacing: -0.02em; }
        .brand-header p { font-size: 14px; color: #64748b; margin-top: 4px; }
        
        .form-group { margin-bottom: 24px; }
        label { display: block; color: #475569; font-size: 13px; font-weight: 600; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.05em; }
        
        input[type="text"], input[type="date"], select {
            width: 100%;
            padding: 14px 16px;
            border: 1px solid #cbd5e1;
            border-radius: 12px;
            font-size: 15px;
            background-color: #f8fafc;
            color: #1e293b;
            outline: none;
            transition: all 0.2s ease-in-out;
            appearance: none; /* ลบลูกศรเดิมของบราวเซอร์ */
        }
        /* เพิ่มลูกศรสั่งทำพิเศษสำหรับ select */
        select {
            background-image: url("data:image/svg+xml;utf8,<svg xmlns='http://w3.org' width='24' height='24' viewBox='0 0 24 24' fill='none' stroke='%23475569' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'><polyline points='6 9 12 15 18 9'></polyline></svg>");
            background-repeat: no-repeat;
            background-position: right 16px center;
            background-size: 16px;
        }
        input:focus, select:focus {
            border-color: #2563eb;
            background-color: #ffffff;
            box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1);
        }
        .date-grid { display: flex; gap: 16px; }
        .date-grid .col { flex: 1; }
        
        button {
            width: 100%;
            padding: 16px;
            background: #1e3a8a;
            border: none;
            border-radius: 12px;
            color: #ffffff;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            box-shadow: 0 4px 6px -1px rgba(30, 58, 138, 0.2);
            margin-top: 8px;
        }
        button:hover { background: #1d4ed8; transform: translateY(-1px); }
        button:active { transform: translateY(0); }

        /* สไตล์ปุ่มลิงก์ตรวจสอบการจอง */
        .check-booking-link {
            text-align: center;
            margin-top: 24px;
        }
        .check-booking-link a {
            color: #64748b;
            font-size: 14px;
            text-decoration: none;
            font-weight: 500;
            transition: color 0.2s;
        }
        .check-booking-link a:hover {
            color: #2563eb;
            text-decoration: underline;
        }
    </style>
</head>
<body>

    <div class="booking-container">
        <div class="brand-header">
            <span class="icon">🏨</span>
            <h1>ระบบจองห้องออนไลน์</h1>
            <p>กรุณาระบุข้อมูลเพื่อตรวจสอบห้องว่างและทำการจอง</p>
        </div>
        
        <form action="save_booking.php" method="POST">
            <div class="form-group">
                <label>ชื่อผู้เข้าพัก</label>
                <input type="text" name="name" placeholder="กรุณากรอกชื่อ-นามสกุลของคุณ" required>
            </div>

            <div class="form-group">
                <label>ประเภทห้องพัก</label>
                <select name="room_type" required>
                    <option value="" disabled selected>-- กรุณาเลือกประเภทห้องพัก --</option>
                    <option value="Standard Room">Standard Room (ห้องมาตรฐาน)</option>
                    <option value="Deluxe Room">Deluxe Room (ห้องดีลักซ์)</option>
                    <option value="Suite Room">Suite Room (ห้องสวีท)</option>
                </select>
            </div>

            <div class="date-grid">
                <div class="col form-group">
                    <label>วันที่เริ่มต้น</label>
                    <input type="date" id="start_date" name="start_date" min="<?php echo date('Y-m-d'); ?>" required onchange="updateEndDate()">
                </div>
                <div class="col form-group">
                    <label>วันที่สิ้นสุด</label>
                    <input type="date" id="end_date" name="end_date" min="<?php echo date('Y-m-d'); ?>" required>
                </div>
            </div>

            <button type="submit">ยืนยันการจองห้องพัก</button>
        </form>

        <!-- เพิ่มลิงก์ตรวจสอบสถานะและยกเลิกของลูกค้าตรงนี้ครับ -->
        <div class="check-booking-link">
            <a href="my_booking.php">🔍 ตรวจสอบหรือยกเลิกการจองของคุณ คลิกที่นี่</a>
        </div>
    </div>

    <script>
        function updateEndDate() {
            var startDate = document.getElementById('start_date').value;
            var endDateInput = document.getElementById('end_date');
            endDateInput.min = startDate;
            if(endDateInput.value < startDate) {
                endDateInput.value = startDate;
            }
        }
    </script>

</body>
</html>
