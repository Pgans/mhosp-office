<?php
use yii\helpers\Html;
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><h1>หมวดรายงานศูนย์คอมพิวเตอร์ </h1></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
     <!--<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">-->
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .dashboard-title {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            color: #501f8f;
            margin-bottom: 30px;
        }
        .card-custom {
            border-radius: 15px;
            box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s, box-shadow 0.3s;
            padding: 15px;
        }
        .card-custom:hover {
            transform: translateY(-5px);
            box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.3);
        }
        .card-body {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-title {
            font-size: 18px;
            font-weight: bold;
            color: #501f8f;
            margin-bottom: 5px;
        }
        .icon-container {
            width: 50px;
            height: 50px;
            background-color: #dff9fb;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .icon-container i {
            font-size: 22px;
            color: #0984e3;
        }
        .report-link {
            font-size: 16px;
            font-weight: bold;
            color: #e74c3c;
            text-decoration: none;
        }
        .report-link:hover {
            text-decoration: underline;
            color: #c0392b;
        }
    </style>
</head>
<body>
<h1 class="dashboard-title text-center" style="color: blue;">
    หมวดรายงานศูนย์คอมพิวเตอร์ โรงพยาบาลม่วงสามสิบ
    <span style="color: red;"> จำนวนเข้าใช้งาน: <?= $amount ?></span>
</h1>

<div class="container">
    <div class="row">
        <?php
        $reports = [
            ['icon' => 'fas fa-leaf', 'title' => '1. อัตราการซื้ออุปกรณ์ต่อพวงทางคอมพิวเตอร์', 'url' => ['computer/index']],
            ['icon' => 'fas fa-pills', 'title' => '2. อัตราการซื้อหมึก (แยกรายเดือน)', 'url' => ['computer/ink']],
            ['icon' => 'fas fa-seedling', 'title' => '3. อัตราการซื้อแบตเตอรี่ เครื่องสำรองไฟ', 'url' => ['computer/battery']],
        ];

        foreach ($reports as $report) {
            echo '<div class="col-md-6 col-lg-4 mb-4">
                    <div class="card card-custom">
                        <div class="card-body">
                            <div>
                                <h5 class="card-title">'. Html::a($report['title'], $report['url'], ['class' => 'report-link']) .'</h5>
                            </div>
                            <div class="icon-container">
                                <i class="'.$report['icon'].'"></i>
                            </div>
                        </div>
                    </div>
                </div>';
        }
        ?>
    </div>
</div>
