<?php
use yii\helpers\Html;
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ntip NAP Plus</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
   
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #fde2e4;
            padding: 20px;
        }
        .dashboard-title {
            text-align: center;
            font-size: 28px;
            font-weight: bold;
            color: #c0392b;
            margin-bottom: 30px;
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
    padding: 10px 15px; /* ใช้ padding ที่ถูกต้อง */
    background: linear-gradient(to right, #ffe4e1, #f8bbd0); /* ใช้พื้นหลังแบบ Gradient */
    border-radius: 10px; /* ทำให้ขอบมน */
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1); /* เพิ่มเงาเพื่อความลึก */
}

        .card-title {
            font-size: 18px;
            font-weight: bold;
            color: #6c757d;
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
            font-size: 22px;
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
	 .dashboard-title {
            display: inline-block;
            text-align: center;
            font-size: 36px; /* ขนาดฟอนต์ */
            font-weight: bold;
            color: white;
            padding: 10px 30px; /* ขนาด padding */
            border-radius: 15px; /* ทำให้ขอบมน */
            background: linear-gradient(to right, #ff9a9e, #fad0c4); /* พื้นหลังไล่เฉดสีชมพูอ่อน */
            box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.2);
            margin: 20px auto; /* จัดให้อยู่ตรงกลาง */
        }

    </style>
</head>
<body>

<div class="text-center">
    <h1 class="dashboard-title">
        รายงาน Ntip NAP Plus
    </h1>
</div>


<div class="container">
    <div class="row">
        <?php
        $reports = [
            ['icon' => 'fas fa-leaf', 'title' => '1. NCD Xray แยกราย รพสต.', 'url' => ['opdntip/index']],
            ['icon' => 'fas fa-pills', 'title' => '2. AFB-TB', 'url' => ['/afb/index']],
            ['icon' => 'fas fa-seedling', 'title' =>'3. VIP-พิเศษ', 'url' => ['/ntip/vip']],
            ['icon' => 'fas fa-user-md', 'title' => '4. รายชื่อ NCD นิรนาม(X-ray)', 'url' => ['/ntip/index']],
            ['icon' => 'fas fa-stethoscope', 'title' => '5. X-ray อายุ 65 ปีขึ้นไป', 'url' => ['/ntip/index2']],
            ['icon' => 'fas fa-file-medical', 'title' => '6. คลินิกนิรนาม', 'url' => ['/cd4/index']],
            ['icon' => 'fas fa-calendar-alt', 'title' => '7. ปฏิทินกิจกรรม', 'url' => ['/calendar/calendar']],
			['icon' => 'fas fa-calendar-alt', 'title' => '8. Excel Ntip ไตเรื้อรัง', 'url' => ['/ntip/exportntip']],
			['icon' => 'fas fa-calendar-alt', 'title' => '9. Excel Ntip เบาหวาน', 'url' => ['/ntip/exportdm']],
			['icon' => 'fas fa-calendar-alt', 'title' => '10.Excel Ntip บุคลากรสาธารณสุข', 'url' => ['/ntip/exportm30']],
			['icon' => 'fas fa-calendar-alt', 'title' => '11.Excel Ntip คลินิกหอบหืด', 'url' => ['/ntip/exportcopd']],
			['icon' => 'fas fa-calendar-alt', 'title' => '12.Excel Ntip นิรนาม', 'url' => ['/ntip/exporthiv']],
			['icon' => 'fas fa-calendar-alt', 'title' => '13.สูตรคำนวณยา', 'url' => ['/tbmonthlytreatment/index']],
			[
				'icon' => 'fas fa-calendar-alt',
				'title' => '14.ใบคัดกรองวัณโรค',
				'url' => 'http://192.168.200.9/mhosp-office/ใบเชิญคัดกรองวัณโรค%20CXR_IGRA%20สำหรับผู้สัมผัสร่วมบ้าน.htm',
				'target' => '_blank' 
			],
			[
				'icon' => 'fas fa-calendar-alt',
				'title' => '15.เครื่องคำนวณการให้ยา',
				'url' => 'http://192.168.200.9/mhosp-office/เครื่องคำนวณการให้ยา INH + Rifapentine.htm',
				'target' => '_blank' 
			],
        ];

        foreach ($reports as $report) {
            echo '<div class="col-md-6 col-lg-4 mb-4">
                    <div class="card card-custom">
                        <div class="card-body">
                            <div class="icon-container">
                                <i class="'.$report['icon'].'"></i>
                            </div>
                            <h5 class="card-title">'. Html::a($report['title'], $report['url'], ['class' => 'report-link']) .'</h5>
                        </div>
                    </div>
                </div>';
        }
        ?>
    </div>
</div>

</body>
</html>
