<?php
session_start();

// แก้ไขจุดนี้: ถ้าล็อกอินค้างไว้และเป็นแอดมินจริง ถึงจะให้ไปหน้า admin.php
if(isset($_SESSION['admin_logged_in']) && isset($_SESSION['role']) && $_SESSION['role'] === 'admin') {
    header("Location: admin.php");
    exit();
}

$error = "";

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // ตรวจสอบ Username และ Password
    if($username === "admin" && $password === "1234") {
        // บันทึกค่าเซสชันให้สมบูรณ์
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['username'] = "admin";
        $_SESSION['role'] = "admin";
        
        header("Location: admin.php");
        exit();
    } else {
        $error = "ชื่อผู้ใช้หรือรหัสผ่านไม่ถูกต้อง!";
    }
}
?>
<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบจัดการหลังบ้าน</title>
    <link rel="preconnect" href="https://googleapis.com">
    <link rel="preconnect" href="https://gstatic.com" crossorigin>
    <link href="https://googleapis.com/css2?family=Inter:wght@400;500;600&family=Sarabun:wght@400;500;600&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: 'Inter', 'Sarabun', sans-serif;
            background: linear-gradient(135deg, #f1f5f9, #e2e8f0);
            display: flex; justify-content: center; align-items: center; min-height: 100vh;
        }
        .login-container {
            background: #ffffff; padding: 40px; border-radius: 24px;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.05);
            width: 100%; max-width: 400px; border: 1px solid #f1f5f9; text-align: center;
        }
        h1 { font-size: 22px; font-weight: 600; color: #1e293b; margin-bottom: 8px; }
        p { font-size: 14px; color: #64748b; margin-bottom: 24px; }
        .form-group { margin-bottom: 20px; text-align: left; }
        label { display: block; color: #475569; font-size: 13px; font-weight: 600; margin-bottom: 8px; }
        input[type="text"], input[type="password"] {
            width: 100%; padding: 14px 16px; border: 1px solid #cbd5e1; border-radius: 12px;
            font-size: 15px; background-color: #f8fafc; outline: none; transition: all 0.2s;
        }
        input:focus { border-color: #2563eb; background-color: #ffffff; box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1); }
        .error-msg { color: #ef4444; font-size: 14px; margin-bottom: 16px; text-align: left; }
        button {
            width: 100%; padding: 14px; background: #1e3a8a; border: none; border-radius: 12px;
            color: #ffffff; font-size: 16px; font-weight: 600; cursor: pointer; transition: all 0.2s;
        }
        button:hover { background: #1d4ed8; }
    </style>
</head>
<body>

    <div class="login-container">
        <h1>🔒 ผู้ดูแลระบบ</h1>
        <p>กรุณาเข้าสู่ระบบเพื่อจัดการคิวจองห้อง</p>
        
        <?php if(!empty($error)): ?>
            <div class="error-msg">⚠️ <?php echo $error; ?></div>
        <?php endif; ?>

        <form action="login.php" method="POST">
            <div class="form-group">
                <label>ชื่อผู้ใช้งาน (Username)</label>
                <input type="text" name="username" placeholder="กรอกชื่อผู้ใช้งาน" required>
            </div>
            <div class="form-group">
                <label>รหัสผ่าน (Password)</label>
                <input type="password" name="password" placeholder="กรอกรหัสผ่าน" required>
            </div>
            <button type="submit">เข้าสู่ระบบ</button>
        </form>
    </div>

</body>
</html>
