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
  <!-- <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">-->
  <style>
    body {
        font-family: 'Arial', sans-serif;
        background-color: #fde2e4;
        padding: 20px;
    }
    .dashboard-title {
        display: inline-block;
        text-align: center;
        font-size: 36px; /* ขนาดฟอนต์ */
        font-weight: bold;
        color: white;
        padding: 10px 30px; /* ขนาด padding */
        border-radius: 15px; /* ทำให้ขอบมน */
        background: linear-gradient(to right, #ede1fa, #d3a1e0); /* พื้นหลังไล่เฉดสีม่วงอ่อน */
        box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.2);
        margin: 20px auto; /* จัดให้อยู่ตรงกลาง */
    }
    .card-custom {
        border-radius: 15px;
        background: #fff;
        box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.15);
        transition: transform 0.3s, box-shadow 0.3s;
        padding: 15px;
        margin-bottom: 20px; /* เพิ่มระยะห่างระหว่างแถว */
    }
    .card-custom:hover {
        transform: translateY(-5px);
        box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.3);
    }
    .card-body {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        background: linear-gradient(to right, #e9aaf8, #f7d7f1); /* พื้นหลัง Gradient */
        border-radius: 10px; /* ทำให้ขอบมน */
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* เพิ่มเงาเพื่อความลึก */
    }
    .card-title {
        font-size: 20px;
        font-weight: bold;
        color: #e84393; /* สีชมพู */
        margin-bottom: 5px;
        display: flex;
        align-items: center;
    }
    .icon-container {
        width: 70px;
        height: 70px;
        background-color: #ffccd5;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px; /* เพิ่มระยะห่างจากข้อความ */
    }
    .icon-container i {
        font-size: 24px;
        color: #e84393;
    }
    .report-link {
        font-size: 16px;
        font-weight: bold;
        color: #d63384;
        text-decoration: none;
    }
    .report-link:hover {
        text-decoration: underline;
        color: #b81d62;
    }
</style>

<body>
<div class="text-center">
    <h1 class="dashboard-title" style="color: purple;">
       รายงานแพทย์แผนไทย (mBase)  
       <span style="color: yellow;">จำนวนเข้าใช้งาน: <?= $amount ?></span>
    </h1>
</div>



<div class="container">
    <div class="row">
        <?php
        $reports = [
            ['icon' => 'fas fa-leaf', 'title' => '1. นับการทำหัตถการแพทย์ทางเลือก', 'url' => ['thaimed/operation']],
            ['icon' => 'fas fa-pills', 'title' => '2. รายงานการจ่ายยาสมุนไพรฟ้าทะลายโจรในคนที่เป็นโรครหัส J00-J99', 'url' => ['thaimed/cormore']],
            ['icon' => 'fas fa-seedling', 'title' => '3. รายงานการจ่ายยาสมุนไพรทดแทน 6 ชนิด (แยกรายเดือน)', 'url' => ['thaimed/smonpri_replace']],
            ['icon' => 'fas fa-user-md', 'title' => '4. รายงานผู้ทำหัตถการแพทย์แผนไทยแยกตามผู้ทำหัตการ (ผู้ทำ)', 'url' => ['thaimed/surgeon_operation']],
            ['icon' => 'fas fa-stethoscope', 'title' => '5. ตรวจสอบรหัสหัตการแพทย์แผนไทย (9007810)', 'url' => ['thaimed/surgeon_9007810']],
            ['icon' => 'fas fa-file-medical', 'title' => '6. รหัสโรคแพทย์แผนไทยประเภท U (ยกเว้น U778)', 'url' => ['thaimed/u_thaimed']],
            ['icon' => 'fas fa-female', 'title' => '7. การบริบาลหญิงหลังคลอดด้วยการทับหม้อเกลือทั่วร่างกาย (9007712)', 'url' => ['thaimed/u_9007712']],
            ['icon' => 'fas fa-spa', 'title' => '8. การอบไอน้ำสมุนไพรทั่วร่างกาย (9007800)', 'url' => ['thaimed/u_9007800']],
            ['icon' => 'fas fa-clinic-medical', 'title' => '9. การจ่ายยาสมุนไพร 6 ชนิดแยกตามสิทธิ์การรักษา (Non_UC)', 'url' => ['thaimed/inscl_smonpai6']],
            ['icon' => 'fas fa-cannabis', 'title' => '10. การจ่ายยาสมุนไพรกัญชาแยกตามสิทธิ์การรักษา', 'url' => ['thaimed/marijuana']],
            ['icon' => 'fas fa-capsules', 'title' => '11. กลุ่มยาลดปวดอักเสบกล้ามเนื้ออักเสบ', 'url' => ['thaimed/muscles']],
            ['icon' => 'fas fa-prescription-bottle-alt', 'title' => '12. กลุ่มยาระบบทางเดินอาหาร', 'url' => ['thaimed/gastro']],
            ['icon' => 'fas fa-lungs', 'title' => '13. กลุ่มยาระบบทางเดินหายใจ', 'url' => ['thaimed/respiratory']],
            ['icon' => 'fas fa-briefcase-medical', 'title' => '14. ยาสมุนไพร 10 กลุ่มโรค', 'url' => ['thaimed/group10']],
            ['icon' => 'fas fa-briefcase-medical', 'title' => '15. เครื่องนวดไฟฟ้า ICD9=9007810', 'url' => ['opd/index']],
            ['icon' => 'fas fa-briefcase-medical', 'title' => '16. รายงานหัตถการแผนไทยทั้งหมด', 'url' => ['operations15/index']],
			['icon' => 'fas fa-briefcase-medical', 'title' => '17. ดึงข้อมูลเยี่ยมหลังคลอด', 'url' => ['thaimed/labor']],
			['icon' => 'fas fa-briefcase-medical', 'title' => '18. บริการปิดสิทธิ์', 'url' => ['closefdh/index']],
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