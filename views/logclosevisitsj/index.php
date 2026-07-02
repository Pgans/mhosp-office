<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\LogclosevisitsjSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Jhcis-จองเคลม');
$this->params['breadcrumbs'][] = $this->title;
?>
<d!-- CSS สำหรับ gradient สีฟ้าอ่อน -->
<style>
    .gradient-bg {
        background: linear-gradient(to right, #f8f9f9  , #f4f6f6  ); /* ไล่สีฟ้าอ่อน */
         border-radius: 8px; /* ขอบมน */
        padding: 15px; /* ระยะห่างภายใน */
        border: 2px solid #b2babb ; /* เส้นขอบสีฟ้าเข้ม */
        box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2); /* เพิ่มความชัดของเงา */
    }
</style>
	
<!-- ########################  ปุ่มเมนู ########################################-->
<style>
    .btn-group-modern {
        display: flex;
        gap: 10px;
        justify-content: center;
        margin-top: 10px;
    }
    
    .btn-modern {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 12px 20px;
        border: none;
        border-radius: 8px;
        font-size: 16px;
        font-weight: bold;
        text-transform: uppercase;
        text-decoration: none;
        color: white;
        transition: all 0.3s ease-in-out;
        box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
    }

    .btn-modern i {
        font-size: 18px;
    }

    /* ปรับสีปุ่ม */
    .btn-cidhn {
        background: linear-gradient(135deg, #6a11cb, #2575fc);
    }

    .btn-opd {
        background: linear-gradient(135deg, #0ba360, #3cba92);
    }

    .btn-ipd {
        background: linear-gradient(135deg, #ff512f, #dd2476);
    }
	.btn-refers {
        background: linear-gradient(135deg, #18abab, #35e8d7
	);
	
	
    }
    /* Hover Effect */
    .btn-modern:hover {
        transform: translateY(-3px);
        box-shadow: 0px 6px 15px rgba(0, 0, 0, 0.3);
    }
</style>
<style>
/* จัดกล่องและตารางให้อยู่ตรงกลาง */
.center-container {
    width: 1200px;
    margin: 0 auto;
}

/* สลับสีแถวขาว-เขียวอ่อน */
.table-striped > tbody > tr:nth-of-type(odd) {
    background-color: #ffffff;
}
.table-striped > tbody > tr:nth-of-type(even) {
    background-color: #e8f9fa;
}

/* เพิ่ม hover effect */
.table-hover tbody tr:hover {
    background-color: #d0f0c0;
}

</style>

<div class="btn-group-modern">
    
    <a href="<?= Url::to(['/closevisit1/index']) ?>" class="btn-modern btn-ipd">
        <i class="fa fa-check-square-o"></i> จองเก็บตก
    </a>
	<a href="<?= Url::to(['/logclosevisits/index']) ?>" class="btn-modern btn-refers">
        <i class="fa fa-check-square-o"></i> mBase-ตรวจการจอง
    </a>
	 <a href="<?= Url::to(['/closevisitjhcis/index']) ?>" class="btn-modern btn-refers">
        <i class="fa fa-check-square-o"></i> Jhcis-จองเคลม
    </a>
	 <a href="<?= Url::to(['/logclosevisitsj/index']) ?>" class="btn-modern btn-refers">
        <i class="fa fa-check-square-o"></i> Jhcis-ตรวจการจอง
    </a>
</div>
<!-- ########################  จบปุ่มเมนู ########################################-->
<br>
<div class="logclosevisits-index center-container">
    <div class="box box-info box-solid gradient-bg">
        <div class="box-header">
            <h3 class="box-title"><i class="fa fa-file-text-o"></i> <?= Html::encode($this->title) ?></h3>
        </div>
         <div class="box-body table-responsive">
            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'tableOptions' => [
                    'class' => 'table table-striped table-hover', // ใส่คลาสตรงนี้
                ],
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    'visit_id',
                    'pid',
                    'response',
                    'transaction_uid',
                    'users',
                    'send_date',
                    'regdate',
                ],
            ]); ?>
        </div>
    </div>
</div>
<?php
$this->registerCss("
.centered-container {
    max-width: 1000px;
    margin: 0 auto;
    padding: 15px;
}

.zebra-table tbody tr:nth-child(odd) {
    background-color: #ffffff;
}
.zebra-table tbody tr:nth-child(even) {
    background-color: #e6f9e6;
}
.zebra-table tbody tr:hover {
    background-color: #c0f0c0 !important;
    cursor: pointer;
}

.table {
    width: 100%;
    font-size: 14px;
}

@media (max-width: 768px) {
    .table {
        font-size: 12px;
    }
}
");
?>