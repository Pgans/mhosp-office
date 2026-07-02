<?php

use yii\bootstrap\Html;
use yii\helpers\Url;

// $command3 = Yii::$app->db->createCommand("SELECT company FROM setting WHERE id='1'");
// $company = $command3->queryScalar();
//
// $command4 = Yii::$app->db->createCommand("SELECT photo FROM setting WHERE id='1'");
// $logo = $command4->queryScalar();
if (Yii::$app->user->isGuest) {
    $name='Guest';
    $username='Guest';
 }else{
 $user_id = Yii::$app->user->identity->id;
 $command3 = Yii::$app->db->createCommand("SELECT name FROM profile WHERE user_id='$user_id'");
 $name = $command3->queryScalar();

 $username = Yii::$app->user->identity->username;
 }

?>
<style>

/* พื้นหลัง /* hover */
.sidebar-menu > li > a:hover {
    background-color: #d8bce9;
    color: #fff;
}

/* active */
.sidebar-menu > li.active > a {
    background-color: #b07cd7 !important;
    color: #fff !important;
}
/* hover เป็นชมพู */
.main-sidebar .sidebar-menu > li > a:hover {
    background-color: #c25bb7 !important; /* ชมพู */
    color: #fff !important;
}
* Submenu */
.main-sidebar .sidebar-menu .treeview-menu {
    background-color: #f4e8fa !important; /* ม่วงอ่อน */
    padding-left: 10px;
    border-left: 3px solid #b07cd7; /* เส้นนำสายตา */
    border-radius: 6px;
}

/* Submenu item */
.main-sidebar .sidebar-menu .treeview-menu > li > a {
    background-color: #f4e8fa !important;
    color: #333 !important;
    border-radius: 4px;
    margin: 2px 6px;
}
/* Hover submenu */
.main-sidebar .sidebar-menu .treeview-menu > li > a:hover {
    background-color: #f8a5c2 !important; /* ชมพู */
    color: #fff !important;
}
</style>
<aside class="main-sidebar" 
       style="background: linear-gradient(180deg, #750999 0%, #1d67de 100%); 
              color: #4A148C; 
              border-right: none;">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?= Yii::getAlias('@web') . '/images/moph.png' ?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">

                <?php if (Yii::$app->user->isGuest) { ?>

                    <a href="#"><i class="fa fa-circle text-red"></i> Offline</a>
                <?php } else { ?>
                    <p><?= $name ?></p>
                    <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
                <?php } ?>


            </div>
        </div>

        <!-- search form -->
        <form action="#" method="get" class="sidebar-form">
            <div class="input-group">
               <input type="text" name="q" class="form-control" placeholder="Search..." style="background-color: #f4e8fa; color: white;" />

                <span class="input-group-btn">
                    <button type='submit' name='search' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i>
                    </button>
                </span>
            </div>
        </form>
        <!-- /.search form -->

        <?= dmstr\widgets\Menu::widget(
            [
                'options' => ['class' => 'sidebar-menu tree', 'data-widget' => 'tree'],
                'items' => [
                    ['label' => 'Menu', 'options' => ['class' => 'header', 'style' => 'background-color: #35034F; color: white;']],
					 ['label' => 'Dash_ทันตกรรมพระราชทาน','icon'=>'calendar text-orange' ,'url' => ['computerx/dental']],
					 ['label' => 'Dash_หน่วยเคลื่อนที่ พอสว.','icon'=>'calendar text-orange' ,'url' => ['computerx/mobileposw']],
					 ['label' => 'Dash_คัดกรองมะเร็งเต้านม','icon'=>'calendar text-orange' ,'url' => ['computerx/breastcancer']],
                    ['label' => 'Dashboard','icon'=>'calendar text-orange' ,'url' => ['/dashboard/dashboard']],
					//label' => 'E-Meeting','icon'=>'calendar text-orange' ,'url' => ['/meetingagenda/index']],
                    ['label' => 'ข้อมูลบุคลากร','icon' => 'cog text-orange', 'url' => ['/personal/person']],
                    ['label' => 'ระบบเคลม (Claim)', 'options' => ['class' => 'header', 'style' => 'background-color: #35034F; color: white;']],
                    ['label' => 'ยืมเวชระเบียน','icon' => 'cog text-orange', 'url' => ['/opdcard/permits']],
					['label' => 'คืนเวชระเบียน','icon' => 'cog text-orange', 'url' => ['/apdcard/permits']],
					['label' => 'ขอประวัติการรักษา','icon' => 'cog text-orange', 'url' => ['/requesxthistory/index']],
                    ['label' => 'ระบบจอง (USER)', 'options' => ['class' => 'header', 'style' => 'background-color: #35034F; color: white;']],
                    // ['label' => 'ตารางการจอง', 'icon' => 'tasks text-orange', 'url' => ['/booking/index']],
                    // ['label' => 'ปฏิทินการจอง', 'icon' => 'calendar text-orange', 'url' => ['/booking/calendar'],],
					/*
					[
                        'label' => 'E-Meeting', 'icon' => 'cog text-orange', 
                        'items' => [
                           ['label' => 'การประชุม กกบ.', 'icon' => 'fas fa-play text-aqua', 'url' => ['meetingagenda/index']],
                           ['label' => 'แนบไฟล์','icon' => 'fas fa-play text-aqua', 'url' => ['/meetingagenda/admin']],
                            
                        ],
                    ],
					*/
					[
                        'label' => 'ระบบจองห้องประชุม', 'icon' => 'cog text-orange', 
                        'items' => [
                           ['label' => 'ปฏิทินการจองประชุม', 'icon' => 'calendar text-orange', 'url' => ['/booking/calendar'],],
                           ['label' => 'จองห้องประชุม','icon' => 'fas fa-play text-aqua', 'url' => ['/booking/index']],
                           ['label' => 'เพิ่มห้องประชุม', 'icon' => 'circle-o text-blue', 'url' => ['/room/index'],],
                           ['label' => 'อนุมัติการจอง', 'icon' => 'circle-o text-red', 'url' => ['/operator'],],

                            
                        ],
                    ],
                    [
                        'label' => 'ระบบจองรถ', 'icon' => 'cog text-orange', 
                        'items' => [
                           
                            ['label' => 'วิธีการใช้งานการจองรถ','icon' => 'fas fa-play text-aqua', 'url' => ['/rental/index2']],
							//['label' => 'รายการจองทั้งหมด', 'icon' => 'calendar', 'url' => ['/report/index']],
							['label' => 'สรุปงานพนักงานขับรถ ', 'icon' => 'bar-chart', 'url' => ['/rptdriver/report']],
                            ['label' => 'สรุปผลการจองรถ', 'icon' => 'bar-chart', 'url' => ['/report/report']],
                            ['label' => 'ปฏิทินการจองรถ', 'icon' => 'bar-chart', 'url' => ['/rental/calendar']],
                            //['label' => 'การจองรถ','icon' => 'fas fa-play text-aqua', 'url' => ['/rental/index']],
                            #['label' => 'ผู้อนุมัติรถ','icon' => 'fas fa-play text-aqua', 'url' => ['/rental/admin']],
                            ['label' => 'เพิ่มยานพาหนะ','icon' => 'fas fa-play text-aqua', 'url' => ['/vehicle/index']],
                            ['label' => 'เพิ่ม พรข.','icon' => 'fas fa-play text-aqua', 'url' => ['/drivers/index']],
                           // ['label' => 'ผู้อนุมัติการจอง','icon' => 'fas fa-play text-aqua', 'url' => ['/rental/admin']],
                        ],
                    ],
					[   
                        ['label' => 'ระบบส่งซ่อม (USER)', 'options' => ['class' => 'header']],
                        'label' => 'แจ้งซ่อม', 'icon' => 'cog text-orange', 
                        'items' => [
                            
                            ['label' => 'แจ้งซ่อมคอม-โสต','icon' => 'fas fa-play text-aqua', 'url' => ['/jobcom/calendar']],
                            ['label' => 'แจ้งซ่อมพัสดุ','icon' => 'fas fa-play text-aqua', 'url' => ['/jobservice/index']],
							['label' => 'แจ้งซ่อมเครื่องมือการแพทย์','icon' => 'fas fa-play text-aqua', 'url' => ['/jobmedical/index']],
							['label' => 'กราฟรายงานส่งซ่อมพัสดุ','icon' => 'fas fa-play text-aqua', 'url' => ['/rptservice/index']],
                        ],
                    ],
					/*
					[
                        'label' => 'งานเวชระเบียนและสถิติ', 'icon' => 'cog text-orange', 'visible' => !Yii::$app->user->isGuest,
                        'items' => [
                            ['label' => 'คืนเวชระเบียน', 'icon' => 'fas fa-play text-aqua', 'url' => ['/apdcard/permits']],
                            ['label' => 'Repเคลมส่งการเงิน', 'icon' => 'fas fa-play text-aqua', 'url' => ['/rep/rep1']],
							['label' => 'RepMonth', 'icon' => 'fas fa-play text-aqua', 'url' => ['/repmonth/repmonth']],
							['label' => 'AdjRW', 'icon' => 'fas fa-play text-aqua', 'url' => ['/rep/adjrw']],
                            ['label' => 'Rep-Admit28', 'icon' => 'fa fa-check-square-o text-aqua', 'url' => ['/readmit/readmit']],
							['label' => 'Rep-Visit48', 'icon' => 'fa fa-check-square-o text-aqua', 'url' => ['/readmit/revisit']],
							['label' => 'Unplan-Refer', 'icon' => 'fa fa-check-square-o text-aqua', 'url' => ['/readmit/unplan']],
                            
                        ],
                    ],
					*/
                    [
                        'label' => 'งานระบาดวิทยา', 'icon' => 'cog text-orange', #'visible' => !Yii::$app->user->isGuest,
                        'items' => [
                            ['label' =>'โรคเฝ้าระวัง', 'icon' => 'fas fa-play text-aqua', 'url' => ['/dhf/lepto']],
							['label' => 'จ่ายน้ำมันเชื่อเพลิง', 'icon' => 'fas fa-play text-aqua', 'url' => ['/orderoils/index']],
							['label' => 'A150ER', 'icon' => 'fas fa-play text-aqua', 'url' => ['/a15er/a15er']],
                            ['label' => 'การครองเตียง', 'icon' => 'fa fa-check-square-o text-aqua', 'url' => ['/a15er/sharing']],
                        ],
                    ],
					 [
                        'label' => 'Ntip, NAP Plus', 'icon' => 'cog text-orange', #'visible' => !Yii::$app->user->isGuest,
                        'items' => [
							['label' =>'Dashboard Ntip', 'icon' => 'fas fa-play text-aqua', 'url' => ['/ntip/index3']],
							//['label' =>'vip-พิเศษ', 'icon' => 'fas fa-play text-aqua', 'url' => ['/ntip/vip']],
                            //['label' =>'รายชื่อมา ncd นิรนาม (x-ray)', 'icon' => 'fas fa-play text-aqua', 'url' => ['/ntip/index']],
							//['label' => 'xray อายุ65ปีขึ้นไป', 'icon' => 'fas fa-play text-aqua', 'url' => ['/ntip/index2']],
							//['label' => 'คลินิกนิรนาม', 'icon' => 'fas fa-play text-aqua', 'url' => ['/cd4/index']],
                            //['label' => 'ปฏิทินกิจกรรม', 'icon' => 'fa fa-check-square-o text-aqua', 'url' => ['/calendar/calendar']],
                        ],
                    ],
					[
                        'label' => 'NCD', 'icon' => 'cog text-orange', #'visible' => !Yii::$app->user->isGuest,
                        'items' => [
							['label' =>'THIP-Asthma', 'icon' => 'fas fa-play text-aqua', 'url' => ['/ncd/asthma']],
							['label' =>'THIP-COPD', 'icon' => 'fas fa-play text-aqua', 'url' => ['/ncd/copd']],
							['label' =>'Readmit28', 'icon' => 'fas fa-play text-aqua', 'url' => ['/ncd/readmit']],
							//['label' => 'คลินิกนิรนาม', 'icon' => 'fas fa-play text-aqua', 'url' => ['/cd4/index']],
                           // ['label' => 'ปฏิทินกิจกรรม', 'icon' => 'fa fa-check-square-o text-aqua', 'url' => ['/calendar/calendar']],
                        ],
                    ],
					[
                        'label' => 'งานผู้ป่วยใน', 'icon' => 'cog text-orange', //'visible' => !Yii::$app->user->isGuest,
                        'items' => [
                            ['label' => 'รายงานการAdmit', 'icon' => 'fas fa-play text-aqua', 'url' => ['/ipdx/admit']],
							['label' => 'ติดตามTelemed-IPD', 'icon' => 'fas fa-play text-aqua', 'url' => ['/ipd-tracking/index']],
							
                        ],
                    ],
					['label' => 'รายงาน DataCenter','icon' => 'cog text-orange', 'url' => ['/referopd/index3']],
					//['label' => 'งานเภสัชกรรมคุ้มครอง','icon' => 'cog text-orange', 'url' => ['/pharm/index']],
					[
                        'label' => 'บริการปิดสิทธิ์', 'icon' => 'cog text-orange', #'visible' => !Yii::$app->user->isGuest,
                        'items' => [
                            ['label' => 'ปิดสิทธิ์จ่ายยาสมุนไพร','icon' => 'cog text-orange', 'url' => ['/closefdh32/index']],
							['label' => 'ปิดสิทธิ์แพทย์แผนไทย','icon' => 'cog text-orange', 'url' => ['/closefdh/index']],
							//['label' => 'ปิดสิทธิ์กายภาพ','icon' => 'cog text-orange', 'url' => ['/closephysical/index']],
							//['label' => 'ปิดสิทธิ์-Telemed','icon' => 'cog text-orange', 'url' => ['/closetele/index']],
							['label' => 'ปิดสิทธิ์-mBase-PCU','icon' => 'cog text-orange', 'url' => ['/closepcu/index']],
							['label' => 'ปิดสิทธิ์-jhcis','icon' => 'cog text-orange', 'url' => ['/closejhcis/index']],
                        ],
                    ],
					[
                        'label' => 'รายงานกลุ่มงาน', 'icon' => 'cog text-orange', #'visible' => !Yii::$app->user->isGuest,
                        'items' => [
                            //['label' => 'งานเภสัชกรรมคุ้มครอง','icon' => 'cog text-orange', 'url' => ['/pharm/index']],
							['label' => 'งานแพทย์แผนไทย','icon' => 'cog text-orange', 'url' => ['/thaimed/index']],
							['label' => 'งานศูนย์คอมพิวเตอร์','icon' => 'cog text-orange', 'url' => ['/computer/index2']],
							['label' => 'สรุปการขอ AuthenCode','icon' => 'cog text-orange', 'url' => ['/computer/authen']],
                        ],
                    ],
                    //['label' => 'งานเภสัชกรรมคุ้มครอง','icon' => 'cog text-orange', 'url' => ['/pharm/index']],
                    // [
                    //     'label' => 'งานแพทย์แผนไทย', 'icon' => 'cog text-orange', 
                    //     'items' => [
                    //         ['label' => 'รายงานแพทย์แผนไทย', 'icon' => 'fas fa-play text-aqua', 'url' => ['/thaimed/index']],
					// 		//['label' => 'RepMonth', 'icon' => 'fas fa-play text-aqua', 'url' => ['/repmonth/repmonth']],
					// 		//['label' => 'AdjRW', 'icon' => 'fas fa-play text-aqua', 'url' => ['/rep/adjrw']],
                            
                            
                    //     ],
                    // ],
                    // ['label' => 'Login', 'url' => ['/user/security/login'], 'visible' => Yii::$app->user->isGuest],
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
								//['label' => 'จัดการระบบซ่อมคอมพิวเตอร์', 'icon' => 'user-secret', 'url' => ['/jobcom/admin']],
                                ['label' => 'จัดการระบบซ่อมพัสดุ', 'icon' => 'user-secret', 'url' => ['/jobservice/admin']],
								['label' => 'จัดการระบบซ่อมเครื่องมือแแพทย์', 'icon' => 'user-secret', 'url' => ['/jobmedical/admin']],
                                //['label' => 'รายงาน', 'icon' => 'fas fa-play text-aqua', 'url' => ['/report'],],
                               // ['label' => 'สร้าง Chart', 'icon' => 'fas fa-play text-aqua', 'url' => ['/chartbuilder'],],
                                
                            ],
                        ],
                        // [
                        //     'label' => 'จัดการเว็บไซต์', 'icon' => 'cog', 'visible' => !Yii::$app->user->isGuest,
                        //     'icon' => 'cog text-red',
                        //     'url' => '#',
                        //     'items' => [
                        //         ['label' => 'Replication', 'icon' => 'fas fa-play text-green', 'url' => ['/replicate/repli14'],],
                        //         ['label' => 'ยืม-คืนเวชระเบียน','icon' =>'user text-green','url' => ['/opdcard/permits']],
                        //         ['label' => 'Rottery', 'icon' => 'fas fa-play text-aqua', 'url' => ['/lottery']],
                        //         ['label' => 'pdpa', 'icon' => 'file text-green', 'url' => ['/pdpa65/admin'],],
                        //         ['label' => 'gii', 'icon' => 'file text-green', 'url' => ['/gii'],],
                        //         ['label' => 'EasyUpload', 'icon' => 'file text-green', 'url' => ['/easyupload/index'],],
                        //         ['label' => 'CCTV', 'icon' => 'file text-green', 'url' => ['/cctvs/index'],],
                        //         ['label' => 'News2', 'icon' => 'file text-green', 'url' => ['/news2/index'],],
                        //         //['label' => 'สร้าง Chart', 'icon' => 'file text-orange', 'url' => ['/chartbuilder'],],
                        //     ],
                        // ],
                        // [
                        //     'label' => 'ADMIN', 'icon' => 'cog', 'visible' => !Yii::$app->user->isGuest,
                        //     'icon' => 'cog text-red',
                        //     'url' => '#',
                        //     'items' => [
                        //         ['label' => 'จัดการบุคลากร', 'icon' => 'user-secret', 'url' => ['/personal/person/admin']],
                        //     ],
                        // ],
					// [
                    //     'label' => 'นำเข้าไฟล์', 'icon' => 'cog text-orange', 
                    //     'items' => [
                    //         ['label' => 'นำเข้าไฟล์excel', 'icon' => 'fas fa-play text-aqua', 'url' => ['repimport/imports']],
					// 		['label' => 'นำเข้าไฟล์csv', 'icon' => 'fas fa-play text-aqua', 'url' => ['site/import']],
					// 		['label' => 'Upload excel', 'icon' => 'fas fa-play text-aqua', 'url' => ['import/index']],
					// 		['label' => 'นำเข้าไฟล์excelทดสอบ', 'icon' => 'fas fa-play text-aqua', 'url' => ['repimport/index']],
                    //     ],
                    // ],
                    // [
                    //     'label' => 'จัดการเว็บไซต์', 'icon' => 'cog', 'visible' => !Yii::$app->user->isGuest,
                    //     'items' => [
                    //         ['label' => 'หมวดหมู่', 'icon' => 'circle-o text-aqua', 'url' => ['/newscategory/index'], 'visible' => !Yii::$app->user->isGuest],
                    //         ['label' => 'หัวข้อ', 'icon' => 'circle-o text-aqua', 'url' => ['/news/admin'], 'visible' => !Yii::$app->user->isGuest],
                    //     ],
                    // ],
                    Yii::$app->user->isGuest ?
                        ['label' => 'เข้าสู่ระบบ', 'icon' => 'sign-in text-green', 'url' => ['/user/security/login']] : [
                            'label' => 'ยินดีต้อนรับ (' . Yii::$app->user->identity->username . ')',
                            'items' => [
                                ['label' => 'โพรไฟล์', 'icon' => 'user', 'url' => ['/user/profile']],
                                ['label' => 'จัดการผู้ใช้', 'icon' => 'user-secret', 'url' => ['/user/admin/index']],
                                ['label' => 'จัดการสิทธิ์', 'icon' => 'fas fa-play text-aqua', 'url' => ['/admin'],],
                                ['label' => 'ดูสถานะServer','icon'=>'fas fa-play text-aqua','url' => ['/dashboard/dashboard']],
                            ]
                        ],
                ],
            ]
			
        ) ?>
        <ul class="sidebar-menu tree" data-widget="tree">
            <!-- <li class="header"></li> -->
            <?php
            if (Yii::$app->user->isGuest) {
                ?>
                <li>
                    <!-- <a href="<?= Url::to('index.php?r=user/security/login') ?>">
                                    <i class="fa fa-sign-in text-green"></i> <span>เข้าสูระบบ</span>
                                </a> -->
                </li>
            <?php } else { ?>
                <li>
                    <?php
                    echo Html::a(
                        '<i class="fa fa-sign-out text-red"></i>ออกจากระบบ',
                        ['/user/security/logout'],
                        [
                            'data' => [
                                'icon' => 'fa fa-sign-out text-red',
                                'method' => 'post',
                            ],
                        ]
                    );
                    ?>
                </li>
            <?php } ?>
        </ul>
    </section>

</aside>
