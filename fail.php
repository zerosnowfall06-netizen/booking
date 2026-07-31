<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ขออภัย คิวเต็มแล้ว</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background: linear-gradient(135deg, #e0eafc, #cfdef3); display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .card { background: white; padding: 40px; border-radius: 16px; box-shadow: 0 10px 30px rgba(0,0,0,0.1); text-align: center; max-width: 400px; width: 100%; }
        .icon { font-size: 50px; color: #e74c3c; margin-bottom: 15px; }
        h1 { color: #333; font-size: 22px; margin-bottom: 10px; }
        p { color: #666; margin-bottom: 25px; font-size: 15px; }
        .btn { display: inline-block; padding: 12px 24px; background: #e74c3c; color: white; text-decoration: none; border-radius: 8px; font-weight: 600; transition: background 0.2s; }
        .btn:hover { background: #c0392b; }
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">❌</div>
        <h1>ขออภัย คิวเต็มแล้ว!</h1>
        <p>ช่วงเวลาที่คุณเลือกมีผู้จองแล้วในระบบ<br>กรุณากลับไปเลือกช่วงเวลาอื่น</p>
        <a href="index.php" class="btn">กลับไปเลือกเวลาใหม่</a>
    </div>
</body>
</html>
