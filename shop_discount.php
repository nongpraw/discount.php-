<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <title>โปรแกรมคำนวณส่วนลดร้านค้า</title>
    <style>
        body {
            font-family: Tahoma, sans-serif;
            background: #f2f2f2;
            padding: 30px;
        }
        .box {
            background: #fff;
            padding: 20px;
            width: 500px;
            margin: auto;
            border-radius: 8px;
            box-shadow: 0 0 10px #ccc;
        }
        label {
            display: block;
            margin-top: 10px;
        }
        input, select, button {
            width: 100%;
            padding: 8px;
            margin-top: 5px;
        }
        button {
            margin-top: 15px;
            background: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        .result {
            margin-top: 20px;
            background: #eef;
            padding: 15px;
            border-radius: 5px;
        }
        .error {
            color: red;
        }
    </style>
</head>
<body>

<div class="box">
    <h2>โปรแกรมคำนวณส่วนลดร้านค้า</h2>

    <form method="post">
        <label>ยอดซื้อสินค้า (บาท)</label>
        <input type="number" step="0.01" name="amount" required>

        <label>สถานะลูกค้า</label>
        <select name="member">
            <option value="no">ลูกค้าทั่วไป</option>
            <option value="yes">สมาชิก</option>
        </select>

        <button type="submit">คำนวณส่วนลด</button>
    </form>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $amount = $_POST["amount"];
    $member = $_POST["member"];

    if ($amount < 0) {
        echo "<p class='error'>ยอดซื้อห้ามเป็นค่าติดลบ</p>";
        exit;
    }

    $discountRate = 0;
    $level = "ไม่ได้รับส่วนลด";
    $recommend = "";

    if ($amount >= 5000) {
        $discountRate = 20;
        $level = "Platinum";
    } elseif ($amount >= 3000) {
        $discountRate = 15;
        $level = "Gold";
        $recommend = "ซื้อเพิ่มอีก " . number_format(5000 - $amount, 2) . " บาท รับส่วนลด 20%";
    } elseif ($amount >= 1000) {
        $discountRate = 10;
        $level = "Silver";
        $recommend = "ซื้อเพิ่มอีก " . number_format(3000 - $amount, 2) . " บาท รับส่วนลด 15%";
    } elseif ($amount >= 500) {
        $discountRate = 5;
        $level = "Bronze";
        $recommend = "ซื้อเพิ่มอีก " . number_format(1000 - $amount, 2) . " บาท รับส่วนลด 10%";
    } else {
        $recommend = "ซื้อเพิ่มอีก " . number_format(500 - $amount, 2) . " บาท รับส่วนลด 5%";
    }

    // ส่วนลดสมาชิก
    $memberDiscount = 0;
    if ($member == "yes" && $amount >= 500) {
        $memberDiscount = 5;
    }

    $totalDiscount = $discountRate + $memberDiscount;
    $discountMoney = $amount * $totalDiscount / 100;
    $netPrice = $amount - $discountMoney;

    echo "<div class='result'>";
    echo "<p>ยอดซื้อ: " . number_format($amount, 2) . " บาท</p>";

    if ($discountRate > 0) {
        echo "<p>ได้รับส่วนลดระดับ $level {$discountRate}%</p>";
    } else {
        echo "<p>ไม่ได้รับส่วนลด</p>";
    }

    if ($memberDiscount > 0) {
        echo "<p>ได้รับส่วนลดพิเศษสมาชิก +5%</p>";
    }

    if ($totalDiscount > 0) {
        echo "<p>ส่วนลดรวม $totalDiscount%</p>";
        echo "<p>ส่วนลดที่ได้รับ: " . number_format($discountMoney, 2) . " บาท</p>";
    }

    echo "<p><strong>ราคาที่ต้องจ่าย: " . number_format($netPrice, 2) . " บาท</strong></p>";

    if ($recommend && $discountRate < 20) {
        echo "<p>แนะนำ: $recommend</p>";
    }

    echo "</div>";
}
?>

</div>

</body>
</html>
