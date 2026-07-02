<?php
use yii\helpers\Html;
?>

<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataCenter</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
   
   <style>
    body {
        font-family: 'Arial', sans-serif;
        background: linear-gradient(to right, #f3e5f5, #ede7f6); /* ไล่เฉดม่วงอ่อน */
        padding: 20px;
    }

    .dashboard-title {
        display: inline-block;
        text-align: center;
        font-size: 36px;
        font-weight: bold;
        color: white;
        padding: 10px 30px;
        border-radius: 15px;
        background: linear-gradient(to right, #ab47bc, #ce93d8); /* ไล่เฉดม่วงเข้ม-อ่อน */
        box-shadow: 2px 4px 10px rgba(0, 0, 0, 0.2);
        margin: 20px auto;
    }

    .card-custom {
        border-radius: 15px;
        background: #ffffff;
        box-shadow: 5px 5px 15px rgba(0, 0, 0, 0.15);
        transition: transform 0.3s, box-shadow 0.3s;
        padding: 15px;
        margin-bottom: 20px;
    }

    .card-custom:hover {
        transform: translateY(-5px);
        box-shadow: 5px 5px 20px rgba(0, 0, 0, 0.3);
    }

    .card-body {
        display: flex;
        align-items: center;
        padding: 10px 15px;
        background: linear-gradient(to right, #e1bee7, #f3e5f5); /* ไล่เฉดม่วงอ่อน */
        border-radius: 10px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }

    .card-title {
        font-size: 18px;
        font-weight: bold;
        color: #6a1b9a; /* ม่วงเข้ม */
        margin-bottom: 5px;
        display: flex;
        align-items: center;
    }

    .icon-container {
        width: 70px;
        height: 70px;
        background-color: #ba68c8; /* ม่วงกลาง */
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 20px;
    }

    .icon-container i {
        font-size: 22px;
        color: #ffffff;
    }

    .report-link {
        font-size: 16px;
        font-weight: bold;
        color: #8e24aa; /* ม่วงเข้ม */
        text-decoration: none;
    }

    .report-link:hover {
        text-decoration: underline;
        color: #6a1b9a;
    }
</style>

</head>
<body>

<div class="text-center">
    <h1 class="dashboard-title" ><i class="fas fa-file-invoice-dollar"></i>
       ระบบรายงานโรงพยาบาลม่วงสามสิบ   DataCenter
    </h1>
</div>


</body>
</html>


<div class="container">
    <div class="row">
        <?php
        $reports = [
			['icon' => 'fas fa-file-invoice-dollar', 'title' => 'ศูนย์จัดเก็บรายได้', 'url' => ['mmm/index']],
            ['icon' => 'fas fa-leaf', 'title' => 'ผู้ป่วยนอก-OPD', 'url' => ['opdx/index']],
            ['icon' => 'fas fa-pills', 'title' => 'ผู้ป่วยใน-IPD', 'url' => ['/ipd/index']],
            ['icon' => 'fas fa-seedling', 'title' =>'ต่างด้าว OPD', 'url' => ['/thangopd/index']],
            ['icon' => 'fas fa-user-md', 'title' => 'ต่างด้าว IPD', 'url' => ['/thangipd/index']],
            ['icon' => 'fas fa-stethoscope', 'title' => 'ต่างด้าวแม่มาคลอด', 'url' => ['/thangdown/index']],
            ['icon' => 'fas fa-seedling', 'title' =>'Rep-Admit28', 'url' => ['/readmit/readmit']],
            ['icon' => 'fas fa-user-md', 'title' => 'Rep-Visit48', 'url' => ['/readmit/revisit']],
            ['icon' => 'fas fa-stethoscope', 'title' => 'Unplan-Refer', 'url' => ['/readmit/unplan']],
			['icon' => 'fas fa-calendar-alt', 'title' => 'Refer-ER', 'url' => ['/readmit/referopd']],
			['icon' => 'fas fa-calendar-alt', 'title' => 'สิ้นสุดบริการ', 'url' => ['/readmit/finish']],
			['icon' => 'fas fa-seedling', 'title' =>'VIP-Statement', 'url' => ['/ntip2/vip']],
			['icon' => 'fas fa-seedling', 'title' =>'VVIP-Statement', 'url' => ['/ntip2/vvip']],
			['icon' => 'fas fa-seedling', 'title' =>'Rider', 'url' => ['/ntip2/rider']],
			
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
