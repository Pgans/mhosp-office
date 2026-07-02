<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title ="OUTSTAN-LIST";
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['report/referin']];
//$this->params['breadcrumbs'][] = 'รายงานผู้ปวยส่งต่อเข้ามา';
echo GridView::widget([
        'dataProvider' => $dataProvider,
        'panel' => [
            'before'=>'<b style="color:blue ">รายงานการออกหน่วยนอกสถานบริการ</b>(<b style="color: red">นอกสถานบริการแพทย์ทางเลือก</b>)',
            'after'=>'<b style="color:red">ประมวลผล </b>'.date('Y-m-d H:i:s')
            ]]
        )

        ?>
        <div class="alert alert-danger"><?=$sql?><div>
