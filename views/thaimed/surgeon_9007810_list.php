<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;

$this->title ="Surgeon-9007810";
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['report/referin']];
//$this->params['breadcrumbs'][] = 'รายงานผู้ปวยส่งต่อเข้ามา';
?>

 <?php echo GridView::widget([
        'dataProvider' => $dataProvider,
        'panel' => [
            'before'=>'<b style="color:blue ">ผู้ทำหัตถการแพทย์แผนไทย</b>(<b style="color: red">xx</b>)',
            ]]
        )

        ?>
        
        <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; flex-wrap: wrap;">
    <!-- กลับหน้าหลัก -->
    <div>
        <?= Html::a('⏪ กลับหน้าหลัก', ['thaimed/index'], [
            'class' => 'btn btn-custom',
            'style' => 'font-size: 1.2rem; background-color: skyblue; color: white;'
        ]) ?>
    </div>
</div>
