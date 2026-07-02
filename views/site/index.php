<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use kartik\grid\GridView;
use kartik\export\ExportMenu;

/* @var $this yii\web\View */

$this->title = 'M30hospital(045489064)';

// ====== เช็คสถานะผู้ใช้งานของระบบเดิม ======
if (Yii::$app->user->isGuest) {
    $name = 'Guest';
    $username = 'Guest';
} else {
    $user_id = Yii::$app->user->identity->id;
    $command3 = Yii::$app->db->createCommand("SELECT name FROM profile WHERE user_id='$user_id'");
    $name = $command3->queryScalar();
    $username = Yii::$app->user->identity->username;
}

// ====== รวบรวมเมนูจากระบบเดิมมาจัดกลุ่มสไตล์ iLovePDF ======
$mhospMenus = [
    // หมวดหมู่: บริการสำหรับเจ้าหน้าที่และผู้ป่วย (USER)
    [
        'cat' => 'ระบบบริการทั่วไป (USER)',
        'title' => 'แจ้งซ่อมคอมพิวเตอร์',
        'desc' => 'แจ้งซ่อมคอมพิวเตอร์และโสตทัศนศึกษา',
        'icon' => 'fa-desktop',
        'color' => '7c3aed', // สีม่วงพาสเทล
        'url' => 'http://192.168.200.9/mhosp-office/web/index.php?r=jobcom%2Fcalendar',
        'target' => '_blank'
    ],
    [
        'cat' => 'ระบบบริการทั่วไป (USER)',
        'title' => 'แจ้งหน่วยซ่อมบำรุง',
        'desc' => 'ระบบแจ้งเตือนเข้าไลน์กลุ่มผู้ดูแลหน่วยซ่อมบำรุง',
        'icon' => 'fa-wrench',
        'color' => '0f766e', // สีเขียวหัวเป็ด
        'url' => 'http://192.168.200.9/mhosp-office/web/index.php?r=jobservice%2Findex',
        'target' => '_blank'
    ],
    [
        'cat' => 'ระบบบริการทั่วไป (USER)',
        'title' => 'จองรถยนต์',
        'desc' => 'โปรแกรมบันทึกการใช้รถยนต์ส่วนกลาง',
        'icon' => 'fa-car',
        'color' => '1d4ed8', // สีน้ำเงิน
        'url' => 'http://192.168.200.9/mhosp-office/web/index.php?r=rental%2Fcalendar',
        'target' => '_blank'
    ],
    [
        'cat' => 'ระบบบริการทั่วไป (USER)',
        'title' => 'เบิกน้ำมันเชื้อเพลิง',
        'desc' => 'โปรแกรมเบิกน้ำมันเชื้อเพลิงควบคุมโรค SRRT',
        'icon' => 'fa-tint',
        'color' => 'c2410c', // สีส้มอิฐ
        'url' => 'http://192.168.200.9/yii2a-services/frontend/web/index.php?r=orderoils%2Findex',
        'target' => '_blank'
    ],
    [
        'cat' => 'ระบบบริการทั่วไป (USER)',
        'title' => 'ยืมเวชระเบียน',
        'desc' => 'ระบบมีการ Login และกำหนดคืนภายใน 7 วัน',
        'icon' => 'fa-folder-open',
        'color' => 'b45309', // สีเหลืองมัสตาร์ด
        'url' => 'http://192.168.200.9/mhosp-office/web/index.php?r=opdcard%2Fpermits',
        'target' => '_blank'
    ],
    [
        'cat' => 'ระบบบริการทั่วไป (USER)',
        'title' => 'ขอประวัติการรักษา',
        'desc' => 'ระบบมีการ Login และกำหนดคืนภายใน 7 วัน',
        'icon' => 'fa-history',
        'color' => '6d28d9', // สีม่วงเข้ม
        'url' => 'http://192.168.200.9/mhosp-office/web/index.php?r=requesxthistory%2Findex',
        'target' => '_blank'
    ],
    [
        'cat' => 'ระบบบริการทั่วไป (USER)',
        'title' => 'ซ่อมเครื่องมือแพทย์',
        'desc' => 'โปรแกรมส่งซ่อมเครื่องมือทางการแพทย์',
        'icon' => 'fa-heartbeat',
        'color' => 'b91c1c', // สีแดง
        'url' => 'http://192.168.200.9/mhosp-office/web/index.php?r=jobmedical%2Findex',
        'target' => '_blank'
    ],

    // หมวดหมู่: สำหรับผู้ดูแลระบบ (ADMIN)
    [
        'cat' => 'สำหรับดูแลระบบ (ADMIN)',
        'title' => 'จัดการงานคอมพิวเตอร์',
        'desc' => 'for เจ้าหน้าที่คอมพิวเตอร์และโสตฯ บันทึกรับทราบงาน',
        'icon' => 'fa-cogs',
        'color' => '4338ca',
        'url' => 'http://192.168.200.9/yii2a-services/backend/web/index.php?r=jobcom%2Findex',
        'target' => '_blank'
    ],
    [
        'cat' => 'สำหรับดูแลระบบ (ADMIN)',
        'title' => 'จัดการงานซ่อมบำรุง',
        'desc' => 'สำหรับเจ้าหน้าที่งานซ่อมบำรุงไว้บันทึกรับทราบงาน',
        'icon' => 'fa-sliders',
        'color' => '0369a1',
        'url' => 'http://192.168.200.9/yii2a-services/backend/web/index.php?r=jobservice%2Findex',
        'target' => '_blank'
    ],
    [
        'cat' => 'สำหรับดูแลระบบ (ADMIN)',
        'title' => 'คืนเวชระเบียน',
        'desc' => 'โปรแกรมคืนเวชระเบียนสำหรับเจ้าหน้าที่ห้องบัตร',
        'icon' => 'fa-check-square',
        'color' => 'be123c',
        'url' => 'http://192.168.200.9/mhosp-office/web/index.php?r=apdcard%2Fpermits',
        'target' => '_blank'
    ],
    [
        'cat' => 'untukดูแลระบบ (ADMIN)',
        'title' => 'ข้อมูลบุคลากร',
        'desc' => 'ข้อมูลบุคลากรโรงพยาบาลม่วงสามสิบ',
        'icon' => 'fa-id-card',
        'color' => '0e7490',
        'url' => 'http://192.168.200.66/datacenter/web/index.php?r=staff%2Freport',
        'target' => '_blank'
    ],
];

// จัดกลุ่มตาม Category
$groupedMenus = [];
foreach ($mhospMenus as $menu) {
    $groupedMenus[$menu['cat']][] = $menu;
}
?>

<style>
:root {
  --ilovepdf-bg: #eef2f5; 
  /* กำหนดรหัสสีชมพูจาง Gradient สำหรับ Card */
  --card-gradient-start: #fff0f5; /* LavenderBlush ชมพูระเรื่อ */
  --card-gradient-end: #ffffff;   /* ตัดด้วยขาวสะอาด */
  --text-dark: #1e293b;
  --text-muted: #64748b;
}

/* ครอบพื้นที่เนื้อหาหลัก */
.mhosp-container {
  background-color: var(--ilovepdf-bg);
  padding: 30px 20px;
  border-radius: 16px;
  font-family: 'Prompt', sans-serif;
}

/* ===== SECTION BLOCK ===== */
.ilovepdf-section {
  margin-bottom: 45px;
}
.ilovepdf-sec-header {
  text-align: center;
  margin-bottom: 25px;
}
.ilovepdf-sec-header h2 {
  font-size: 1.25rem;
  font-weight: 700;
  color: #475569;
  text-transform: uppercase;
  letter-spacing: 0.5px;
  margin: 0;
}

/* ===== GRID SYSTEM ===== */
.ilovepdf-grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap: 20px;
}

/* ===== CARD STYLE (ปรับเป็นเฉดสีชมพูจาง Gradient) ===== */
.ilovepdf-card {
  /* เปลี่ยนพื้นหลังเป็น Linear Gradient จากชมพูจางลงไปขาว */
  background: linear-gradient(135deg, var(--card-gradient-start) 0%, var(--card-gradient-end) 100%);
  border: 1px solid #fbcfe8; /* ปรับขอบให้เป็นสีชมพูพาสเทลบางๆ แทนสีเทา */
  border-radius: 20px;
  padding: 35px 20px 30px;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  justify-content: center;
  gap: 20px;
  text-decoration: none !important;
  transition: transform 0.2s cubic-bezier(0.16, 1, 0.3, 1), box-shadow 0.2s ease;
  box-shadow: 0 4px 6px -1px rgba(219, 39, 119, 0.03); /* ใส่เงาอมชมพูจางๆ */
}
.ilovepdf-card:hover {
  transform: translateY(-5px);
  /* เมื่อ Hover ให้พื้นหลังไล่สีชมพูชัดขึ้นเล็กน้อยแบบนุ่มนวล */
  background: linear-gradient(135deg, #fce7f3 0%, var(--card-gradient-end) 100%);
  box-shadow: 0 20px 25px -5px rgba(219, 39, 119, 0.08), 0 10px 10px -5px rgba(0, 0, 0, 0.02);
}

/* ===== PASTEL ICON BOX ===== */
.ilovepdf-icon-box {
  width: 85px;
  height: 85px;
  border-radius: 22px;
  display: flex;
  align-items: center;
  justify-content: center;
  font-size: 32px;
  flex-shrink: 0;
  transition: transform 0.2s ease;
}
.ilovepdf-card:hover .ilovepdf-icon-box {
  transform: scale(1.05);
}

/* ===== TYPOGRAPHY ===== */
.ilovepdf-label {
  font-size: 1.85rem;  
  font-weight: 700;
  color: var(--text-dark);
  line-height: 1.3;
}
.ilovepdf-desc {
  font-size: 0.95rem;
  color: var(--text-muted);
  line-height: 1.4;
  margin-top: 6px;
}

/* ===== INFO BOX ===== */
.ilovepdf-instruction {
  background: #ffffff;
  border-left: 5px solid #2563eb;
  border-radius: 12px;
  padding: 24px;
  margin-top: 40px;
  box-shadow: 0 4px 12px rgba(0,0,0,0.02);
}
.ilovepdf-instruction h3 {
  font-size: 1.15rem;
  font-weight: 700;
  color: #1e40af;
  margin-bottom: 12px;
}
.ilovepdf-instruction p {
  font-size: 0.95rem;
  margin-bottom: 6px;
  color: #334155;
}
.ilovepdf-instruction .alert-text {
  color: #dc2626;
  font-weight: 600;
  margin-top: 10px;
}
</style>

<div class="mhosp-container">

    <?php foreach ($groupedMenus as $catName => $items): ?>
    <div class="ilovepdf-section">
        <div class="ilovepdf-sec-header">
            <h2><?= Html::encode($catName) ?></h2>
        </div>

        <div class="ilovepdf-grid">
            <?php foreach ($items as $item): 
                // ดึงรหัสสีแปลงเป็นค่า RGB เพื่อทำสีพื้นหลังพาสเทลจางๆ (ความโปร่งแสง 12%)
                $hex = $item['color'];
                $r = hexdec(substr($hex, 0, 2));
                $g = hexdec(substr($hex, 2, 2));
                $b = hexdec(substr($hex, 4, 2));
                $bgPastel = "rgba($r, $g, $b, 0.12)";
                $iconColor = "rgba($r, $g, $b, 1)";
            ?>
            <a href="<?= $item['url'] ?>" target="<?= $item['target'] ?>" class="ilovepdf-card">
                
                <div class="ilovepdf-icon-box" style="background-color: <?= $bgPastel ?>; color: <?= $iconColor ?>;">
                    <i class="fa <?= $item['icon'] ?>"></i>
                </div>
                
                <div>
                    <div class="ilovepdf-label"><?= Html::encode($item['title']) ?></div>
                    <div class="ilovepdf-desc"><?= Html::encode($item['desc']) ?></div>
                </div>
                
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endforeach; ?>

    <div class="ilovepdf-instruction">
        <h3><i class="fa fa-info-circle"></i> ขั้นตอนการเข้าใช้งาน</h3>
        <p><strong>Username:</strong> เลข 13 หลักบัตรประจำตัวประชาชน เช่น 3341400051222</p> 
        <p><strong>Password:</strong> 6 หลักสุดท้ายเลขบัตรประจำตัวประชาชน เช่น 051222</p> 
        <p class="alert-text">*** สิทธิ์การใช้งานโปรแกรมยืมเวชระเบียนเฉพาะ ตำแหน่งแพทย์หรือพยาบาล เท่านั้น *** หากพบปัญหาการใช้งานกรุณาโทรแจ้ง ศูนย์คอมพิวเตอร์เบอร์ 508</p>
    </div>

</div>


<aside class="main-sidebar" style="background: linear-gradient(180deg, #750999 0%, #1d67de 100%); color: #4A148C; border-right: none;">
    <section class="sidebar">
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= Yii::getAlias('@web') . '/images/moph.png' ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <?php if (Yii::$app->user->isGuest) { ?>
                    <a href="#"><i class="fa fa-circle text-red"></i> Offline</a>
                <?php } else { ?>
                    <p><?= Html::encode($name) ?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                <?php } ?>
            </div>
        </div>

        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
               <input type="text" name="q" class="form-control" placeholder="Search..." style="background-color: #f4e8fa; color: white;" />
                <span class="input-group-btn">
                    <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
                </span>
            </div>
        </form>

        <?= dmstr\widgets\Menu::widget([
            'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
            'items' => [
                ['label' => 'Menu', 'options' => ['class' => 'header', 'style' => 'background-color: #35034F; color: white;']],
                ['label' => 'Dash_ทันตกรรมพระราชทาน','icon'=>'calendar text-orange' ,'url' => ['computerx/dental']],
                ['label' => 'Dash_หน่วยเคลื่อนที่ พอสว.','icon'=>'calendar text-orange' ,'url' => ['computerx/mobileposw']],
                ['label' => 'Dash_คัดกรองมะเร็งเต้านม','icon'=>'calendar text-orange' ,'url' => ['computerx/breastcancer']],
                ['label' => 'Dashboard','icon'=>'calendar text-orange' ,'url' => ['/dashboard/dashboard']],
                ['label' => 'ข้อมูลบุคลากร','icon' => 'cog text-orange', 'url' => ['/personal/person']],
                ['label' => 'ระบบเคลม (Claim)', 'options' => ['class' => 'header', 'style' => 'background-color: #35034F; color: white;']],
                ['label' => 'ยืมเวชระเบียน','icon' => 'cog text-orange', 'url' => ['/opdcard/permits']],
                ['label' => 'คืนเวชระเบียน','icon' => 'cog text-orange', 'url' => ['/apdcard/permits']],
                ['label' => 'ขอประวัติการรักษา','icon' => 'cog text-orange', 'url' => ['/requesxthistory/index']],
                ['label' => 'ระบบจอง (USER)', 'options' => ['class' => 'header', 'style' => 'background-color: #35034F; color: white;']],
                [
                    'label' => 'ระบบจองห้องประชุม', 'icon' => 'cog text-orange', 
                    'items' => [
                       ['label' => 'ปฏิทินการจองประชุม', 'icon' => 'calendar text-orange', 'url' => ['/booking/calendar']],
                       ['label' => 'จองห้องประชุม','icon' => 'fas fa-play text-aqua', 'url' => ['/booking/index']],
                       ['label' => 'เพิ่มห้องประชุม', 'icon' => 'circle-o text-blue', 'url' => ['/room/index']],
                       ['label' => 'อนุมัติการจอง', 'icon' => 'circle-o text-red', 'url' => ['/operator']],
                    ],
                ],
                [
                    'label' => 'ระบบจองรถ', 'icon' => 'cog text-orange', 
                    'items' => [
                        ['label' => 'วิธีการใช้งานการจองรถ','icon' => 'fas fa-play text-aqua', 'url' => ['/rental/index2']],
                        ['label' => 'สรุปงานพนักงานขับรถ ', 'icon' => 'bar-chart', 'url' => ['/rptdriver/report']],
                        ['label' => 'สรุปผลการจองรถ', 'icon' => 'bar-chart', 'url' => ['/report/report']],
                        ['label' => 'ปฏิทินการจองรถ', 'icon' => 'bar-chart', 'url' => ['/rental/calendar']],
                        ['label' => 'เพิ่มยานพาหนะ','icon' => 'fas fa-play text-aqua', 'url' => ['/vehicle/index']],
                        ['label' => 'เพิ่ม พรข.','icon' => 'fas fa-play text-aqua', 'url' => ['/drivers/index']],
                    ],
                ],
                [   
                    'label' => 'แจ้งซ่อม', 'icon' => 'cog text-orange', 
                    'items' => [
                        ['label' => 'แจ้งซ่อมคอม-โสต','icon' => 'fas fa-play text-aqua', 'url' => ['/jobcom/calendar']],
                        ['label' => 'แจ้งซ่อมพัสดุ','icon' => 'fas fa-play text-aqua', 'url' => ['/jobservice/index']],
                        ['label' => 'แจ้งซ่อมเครื่องมือการแพทย์','icon' => 'fas fa-play text-aqua', 'url' => ['/jobmedical/index']],
                        ['label' => 'กราฟรายงานส่งซ่อมพัสดุ','icon' => 'fas fa-play text-aqua', 'url' => ['/rptservice/index']],
                    ],
                ],
                [
                    'label' => 'งานระบาดวิทยา', 'icon' => 'cog text-orange',
                    'items' => [
                        ['label' => 'โรคเฝ้าระวัง', 'icon' => 'fas fa-play text-aqua', 'url' => ['/dhf/lepto']],
                        ['label' => 'จ่ายน้ำมันเชื่อเพลิง', 'icon' => 'fas fa-play text-aqua', 'url' => ['/orderoils/index']],
                        ['label' => 'A150ER', 'icon' => 'fas fa-play text-aqua', 'url' => ['/a15er/a15er']],
                        ['label' => 'การครองเตียง', 'icon' => 'fa fa-check-square-o text-aqua', 'url' => ['/a15er/sharing']],
                    ],
                ],
                [
                    'label' => 'Ntip, NAP Plus', 'icon' => 'cog text-orange',
                    'items' => [
                        ['label' => 'Dashboard Ntip', 'icon' => 'fas fa-play text-aqua', 'url' => ['/ntip/index3']],
                    ],
                ],
                [
                    'label' => 'NCD', 'icon' => 'cog text-orange',
                    'items' => [
                        ['label' => 'THIP-Asthma', 'icon' => 'fas fa-play text-aqua', 'url' => ['/ncd/asthma']],
                        ['label' => 'THIP-COPD', 'icon' => 'fas fa-play text-aqua', 'url' => ['/ncd/copd']],
                        ['label' => 'Readmit28', 'icon' => 'fas fa-play text-aqua', 'url' => ['/ncd/readmit']],
                    ],
                ],
                [
                    'label' => 'งานผู้ป่วยใน', 'icon' => 'cog text-orange',
                    'items' => [
                        ['label' => 'รายงานการAdmit', 'icon' => 'fas fa-play text-aqua', 'url' => ['/ipdx/admit']],
                        ['label' => 'ติดตามTelemed-IPD', 'icon' => 'fas fa-play text-aqua', 'url' => ['/ipd-tracking/index']],
                    ],
                ],
                ['label' => 'รายงาน DataCenter','icon' => 'cog text-orange', 'url' => ['/referopd/index3']],
                [
                    'label' => 'บริการปิดสิทธิ์', 'icon' => 'cog text-orange',
                    'items' => [
                        ['label' => 'ปิดสิทธิ์จ่ายยาสมุนไพร','icon' => 'cog text-orange', 'url' => ['/closefdh32/index']],
                        ['label' => 'ปิดสิทธิ์แพทย์แผนไทย','icon' => 'cog text-orange', 'url' => ['/closefdh/index']],
                        ['label' => 'ปิดสิทธิ์-mBase-PCU','icon' => 'cog text-orange', 'url' => ['/closepcu/index']],
                        ['label' => 'ปิดสิทธิ์-jhcis','icon' => 'cog text-orange', 'url' => ['/closejhcis/index']],
                    ],
                ],
                [
                    'label' => 'รายงานกลุ่มงาน', 'icon' => 'cog text-orange',
                    'items' => [
                        ['label' => 'งานแพทย์แผนไทย','icon' => 'cog text-orange', 'url' => ['/thaimed/index']],
                        ['label' => 'งานศูนย์คอมพิวเตอร์','icon' => 'cog text-orange', 'url' => ['/computer/index2']],
                        ['label' => 'สรุปการขอ AuthenCode','icon' => 'cog text-orange', 'url' => ['/computer/authen']],
                    ],
                ],
                ['label' => 'ตั้งค่าระบบ (ADMIN)', 'options' => ['class' => 'header', 'style' => 'background-color: #35034F; color: white;']],
                [
                    'label' => 'ผู้ดูแลระบบ','icon' => 'cog text-red', 'url' => '#','visible' => !Yii::$app->user->isGuest,
                    'items' => [
                        ['label' => 'ขอAuthen', 'icon' => 'user-secret', 'url' => ['/authen/index']],
                        ['label' => 'ขอAuthen-ปิดสิทธิ์ทุกแผนก', 'icon' => 'user-secret', 'url' => ['/closeall/index']],
                        ['label' => 'ปิดสิทธิ์ฟอกไตเทียม', 'icon' => 'user-secret', 'url' => ['/closeallhd/index']],
                        ['label' => 'จองเคลม', 'icon' => 'user-secret', 'url' => ['/closevisit1/index']],
                        ['label' => 'วันหยุดราชการ', 'icon' => 'user-secret', 'url' => ['/holiday/index']],
                        ['label' => 'Kills Process', 'icon' => 'user-secret', 'url' => ['/process/index']],
                        ['label' => 'Monitor Replication', 'icon' => 'user-secret', 'url' => ['/dashboardx/dashboard']],
                        ['label' => 'E-meetig-Admin', 'icon' => 'user-secret', 'url' => ['/meetingagenda/admin']],
                        ['label' => 'จัดการบุคลากร', 'icon' => 'user-secret', 'url' => ['/personal/person/admin']],
                        ['label' => 'เพิ่มตำแหน่ง','icon' => 'user-secret', 'url' => ['/positions/index']],
                        ['label' => 'จัดการระบบซ่อมคอมพิวเตอร์', 'icon' => 'user-secret', 'url' => ['/jobcomad/index']],
                        ['label' => 'จัดการระบบซ่อมพัสดุ', 'icon' => 'user-secret', 'url' => ['/jobservice/admin']],
                        ['label' => 'จัดการระบบซ่อมเครื่องมือแแพทย์', 'icon' => 'user-secret', 'url' => ['/jobmedical/admin']],
                    ],
                ],
                Yii::$app->user->isGuest ?
                    ['label' => 'เข้าสู่ระบบ', 'icon' => 'sign-in text-green', 'url' => ['/user/security/login']] : [
                        'label' => 'ยินดีต้อนรับ (' . Yii::$app->user->identity->username . ')',
                        'items' => [
                            ['label' => 'โพรไฟล์', 'icon' => 'user', 'url' => ['/user/profile']],
                            ['label' => 'จัดการผู้ใช้', 'icon' => 'user-secret', 'url' => ['/user/admin/index']],
                            ['label' => 'จัดการสิทธิ์', 'icon' => 'fas fa-play text-aqua', 'url' => ['/admin']],
                            ['label' => 'ดูสถานะServer','icon'=>'fas fa-play text-aqua','url' => ['/dashboard/dashboard']],
                        ]
                    ],
            ],
        ]) ?>
        
        <ul class="sidebar-menu tree" data-widget="tree">
            <?php if (!Yii::$app->user->isGuest) { ?>
                <li>
                    <?= Html::a(
                        '<i class="fa fa-sign-out text-red"></i>ออกจากระบบ',
                        ['/user/security/logout'],
                        ['data' => ['method' => 'post']]
                    ); ?>
                </li>
            <?php } ?>
        </ul>
    </section>
</aside>