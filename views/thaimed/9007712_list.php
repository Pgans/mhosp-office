<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title ="9007712-LIST";
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['report/referin']];
//$this->params['breadcrumbs'][] = 'รายงานผู้ปวยส่งต่อเข้ามา';
echo GridView::widget([
        'dataProvider' => $dataProvider,
        'panel' => [
            'before'=>'<b style="color:blue ">การบริบาลหญิงหลังคลอกด้วยทับหม้อเกลือ</b>(<b style="color: red">แยกตามสิทธิ์การรักษา</b>)',
            'after'=>'<b style="color:red">ประมวลผลจากวันที่ </b>'.$date1   .'<b style="color:red">ถึงวันที่</b>' .$date2 
            ]]
            )
            ?>
            <div class="alert alert-info"><?=$sql?></div>
        
        