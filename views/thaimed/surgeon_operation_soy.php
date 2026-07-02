<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title ="Surgeon-SOY";
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['report/referin']];
//$this->params['breadcrumbs'][] = 'รายงานผู้ปวยส่งต่อเข้ามา';
echo GridView::widget([
        'dataProvider' => $dataProvider,
        'panel' => [
            'before'=>'<b style="color:blue ">ผู้ทำหัตถการแพทย์แผนไทยSOYxxxxx</b>(<b style="color: red">แยกตามProver</b>)',
            'after'=>'<b style="color:red">ประมวลผล </b>'.date('Y-m-d H:i:s')
            ]]
        )

        ?>
        