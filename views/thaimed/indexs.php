<?php
use yii\helpers\Html;
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานแพทย์แผนไทย (mBase)</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
   <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
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
            color: #2c3e50;
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
            color: #333;
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

<h1 class="dashboard-title text-center">
    รายงานแพทย์แผนไทย (mBase)  
    <span style="color: red;">จำนวนเข้าใช้งาน: <?= $amount ?></span>
</h1>




<div class="container">
    <div class="row">
        <?php
        $reports = [
            ['icon' => 'fas fa-leaf', 'title' => '1. นับการทำหัตถการแพทย์ทางเลือก', 'url' => ['thaimed/operation']],
            ['icon' => 'fas fa-pills', 'title' => '2. รายงานการจ่ายยาสมุนไพรฟ้าทะลายโจรในคนที่เป็นโรครหัส J00-J99', 'url' => ['thaimed/cormore']],
            ['icon' => 'fas fa-seedling', 'title' => '3. รายงานการจ่ายยาสมุนไพรทดแทน 6 ชนิด (แยกรายเดือน)', 'url' => ['thaimed/smonpri_replace']],
            ['icon' => 'fas fa-user-md', 'title' => '4. รายงานผู้ทำหัตถการแพทย์แผนไทยแยกตามผู้ทำหัตการ (ผู้ทำ)', 'url' => ['thaimed/surgeon_operation']],
            ['icon' => 'fas fa-stethoscope', 'title' => '6. ตรวจสอบรหัสหัตการแพทย์แผนไทย (9007810)', 'url' => ['thaimed/surgeon_9007810']],
            ['icon' => 'fas fa-file-medical', 'title' => '7. รหัสโรคแพทย์แผนไทยประเภท U (ยกเว้น U778)', 'url' => ['thaimed/u_thaimed']],
            ['icon' => 'fas fa-female', 'title' => '8. การบริบาลหญิงหลังคลอดด้วยการทับหม้อเกลือทั่วร่างกาย (9007712)', 'url' => ['thaimed/u_9007712']],
            ['icon' => 'fas fa-spa', 'title' => '9. การอบไอน้ำสมุนไพรทั่วร่างกาย (9007800)', 'url' => ['thaimed/u_9007800']],
            ['icon' => 'fas fa-clinic-medical', 'title' => '10. การจ่ายยาสมุนไพร 6 ชนิดแยกตามสิทธิ์การรักษา (Non_UC)', 'url' => ['thaimed/inscl_smonpai6']],
            ['icon' => 'fas fa-cannabis', 'title' => '11. การจ่ายยาสมุนไพรกัญชาแยกตามสิทธิ์การรักษา', 'url' => ['thaimed/marijuana']],
            ['icon' => 'fas fa-capsules', 'title' => '12. กลุ่มยาลดปวดอักเสบกล้ามเนื้ออักเสบ', 'url' => ['thaimed/muscles']],
            ['icon' => 'fas fa-prescription-bottle-alt', 'title' => '12. กลุ่มยาระบบทางเดินอาหาร', 'url' => ['thaimed/gastro']],
            ['icon' => 'fas fa-lungs', 'title' => '13. กลุ่มยาระบบทางเดินหายใจ', 'url' => ['thaimed/respiratory']],
            ['icon' => 'fas fa-briefcase-medical', 'title' => '14. ยาสมุนไพร 10 กลุ่มโรค', 'url' => ['thaimed/group10']],
			['icon' => 'fas fa-briefcase-medical', 'title' => '15. เครื่องนวดไฟฟ้า ICD9=9007810', 'url' => ['opd/index']],
			['icon' => 'fas fa-briefcase-medical', 'title' => '16. รายงานหัตถการแผนไทยทั้งหมด', 'url' => ['operations15/index']],
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

</body>
</html>
