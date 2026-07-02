<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title ="operation_month";
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['report/referin']];
//$this->params['breadcrumbs'][] = 'รายงานผู้ปวยส่งต่อเข้ามา';
    ?>
 <div>
   <?php
echo GridView::widget([
        'dataProvider' => $imonthData,
        'panel' => [
            'before'=>'<b style="color:blue ">หัตถการแพทย์แผนไทยในสถานบริการ</b>(<b style="color: red">แยกตามเดือน</b>)',
            'after'=>'<b style="color:red">ประมวลผลจากวันที่ </b>'.$date1   .'<b style="color:red">ถึงวันที่</b>' .$date2 
            ]]
        )
        ?>
        </div>
     <div>
        <?php
        echo GridView::widget([
        'dataProvider' => $omonthData,
        'panel' => [
            'before'=>'<b style="color:blue ">หัตถการแพทย์แผนไทยนอกสถานบริการ</b>(<b style="color: red">แยกตามเดือน</b>)',
            'after'=>'<b style="color:red">ประมวลผลจากวันที่ </b>'.$date1   .'<b style="color:red">ถึงวันที่</b>' .$date2 
            ]]
        )
        ?>
        </div>
       