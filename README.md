# 🏨 ระบบจองห้องออนไลน์ (Luxury Room Booking System)

ระบบจองห้องพัก/ห้องประชุมออนไลน์รายวันและรายสัปดาห์ ดีไซน์พรีเมียมหรูหรา พร้อมระบบปฏิทินแยกสีประเภทห้องพักสำหรับผู้ดูแลระบบ (Admin) และระบบตรวจสอบ/ยกเลิกการจองสำหรับลูกค้า

## 🛠️ ฟังก์ชันการทำงาน
* **หน้าบ้าน (Client):** ฟอร์มจองห้องพักดีไซน์ทันสมัย เลือกรองรับห้องพัก 3 รูปแบบ (Standard, Deluxe, Suite) บล็อกการเลือกวันที่ย้อนหลังอัตโนมัติ
* **ระบบเช็กคิวซ้ำ:** ตรวจสอบและป้องกันการจองวันคร่อมหรือวันชนกันเฉพาะในประเภทห้องเดียวกัน
* **ระบบลูกค้ายกเลิกเอง:** ค้นหาประวัติการจองด้วยชื่อ และกดยกเลิกคิวได้เองจากหน้าบ้าน
* **หลังบ้าน (Admin):** หน้าต่างปฏิทินภาพรวมแยกสีตามประเภทห้องพัก (เขียว/น้ำเงิน/ม่วง) พร้อมระบบกั้นความปลอดภัยด้วยล็อกอิน Session และปุ่มกดลบคิว

## 💾 วิธีติดตั้งฐานข้อมูล (MySQL)
สร้างฐานข้อมูลชื่อ `booking_db` ใน phpMyAdmin แล้วนำโค้ด SQL ด้านล่างนี้ไปรันในแท็บ SQL:

```sql
CREATE TABLE bookings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    room_type VARCHAR(50) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    booking_date DATE NOT NULL,
    booking_time VARCHAR(20) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

## 🔑 บัญชีผู้ดูแลระบบ (Default Admin)
* **Username:** admin
* **Password:** cdhw-F8hf
