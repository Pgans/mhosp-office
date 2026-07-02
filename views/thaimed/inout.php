<?php
use kartik\grid\GridView;
use yii\helpers\Html;

$this->title ="inout";
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['report/referin']];
//$this->params['breadcrumbs'][] = 'รายงานผู้ปวยส่งต่อเข้ามา';
?>
<div>
<?php
echo GridView::widget([
        'dataProvider' => $insData,
        'panel' => [
            'before'=>'<b style="color:blue ">ผู้ทำหัตถการแพทย์แผนไทย</b>(<b style="color: red">ในสถานบริการ</b>)',
            'after'=>'<b style="color:red">ประมวลผลจากวันที่ </b>'.$date1   .'<b style="color:red">ถึงวันที่</b>' .$date2 
            ]]
        )
        ?>
        </div>
        <div echo $date1></div>
        <div>
<?php
echo GridView::widget([
        'dataProvider' => $outsData,
        'panel' => [
            'before'=>'<b style="color:blue ">ผู้ทำหัตถการแพทย์แผนไทย</b>(<b style="color: red">นอกสถานบริการ</b>)',
            'after'=>'<b style="color:red">ประมวลผลจากวันที่ </b>'.$date1   .'<b style="color:red">ถึงวันที่</b>' .$date2 
            ]]
        )
        ?>
        </div>


        
