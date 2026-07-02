<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use yii\data\ArrayDataProvider;
use common\models\RContributionIpd;
use yii\data\ActiveDataProvider;
use yii\widgets\Pjax;
use \miloschuman\highcharts\Highcharts;
use yii\web\JsExpression;
use kartik\export\ExportMenu;


$this->title = 'ck_operations';
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['computer/index']];
$this->params['breadcrumbs'][] = 'รายงานการทำหัตถการแพทย์แผนไทยแยกตามผู้ทำหัตถการ';
?>
<br>
        <?php $form = ActiveForm::begin([ ]);
     echo Html::a('เทียบรหัสหัตถการmBase', ['thaimed/check_operation'], ['class' => 'btn btn-primary', 'id'=>'modalButton','target'=>'_blank']);
    echo Html::a('เทียบรหัสหัตถการ43แฟ้ม', ['thaimed/check_procudure'], ['class' => 'btn btn-info', 'id'=>'modalButton','target'=>'_blank']);
    echo Html::a('รหัสที่ไม่เข้า43แฟ้ม', ['thaimed/no_procudure'], ['class' => 'btn btn-warning', 'style' => 'margin-left:px','target'=>'_blank']);
    echo Html::a('เปรียบเทียบ', ['thaimed/surgeon_inout'], ['class' => 'btn btn-info', 'style' => 'margin-left:5px','target'=>'_blank']);
    ActiveForm::end();?>
    
</div>
<div>
