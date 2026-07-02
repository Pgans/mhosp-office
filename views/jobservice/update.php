<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Jobservice */

$this->title = Yii::t('app', 'แก้ไขรายการส่งซ่อม: {name}', [
    'name' => '#' . $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'รายการส่งซ่อม'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => '#' . $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'แก้ไขข้อมูล');
?>

<div class="jobservice-update" style="font-family: 'Sarabun', sans-serif; padding: 20px; background-color: #f8fafb;">

    <div class="box" style="border-radius: 15px; border: none; box-shadow: 0 5px 20px rgba(0,0,0,0.08); overflow: hidden;">
        
        <div class="box-header with-border" style="background: #fff; padding: 20px; border-bottom: 1px solid #f0f3f5;">
            <h3 class="box-title" style="font-weight: bold; font-size: 18px; color: #333;">
                <i class="fa fa-pencil-square-o text-primary" style="margin-right: 10px;"></i> 
                <?= Html::encode($this->title) ?>
            </h3>
            <div class="box-tools pull-right">
                <?= Html::a('<i class="fa fa-reply"></i> ย้อนกลับ', ['view', 'id' => $model->id], [
                    'class' => 'btn btn-default btn-sm',
                    'style' => 'border-radius: 20px; padding: 5px 15px; color: #777;'
                ]) ?>
            </div>
        </div>

        <div class="box-body" style="padding: 30px; background-color: #fff;">
            
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <?= $this->render('_form', [
                        'model' => $model,
                    ]) ?>
                </div>
            </div>

        </div>
    </div>

</div>

<style>
    /* ปรับแต่งความสวยงามเพิ่มเติมสำหรับ Form ภายใน */
    .jobservice-update .form-group {
        margin-bottom: 20px;
    }
    .jobservice-update label {
        font-weight: 500;
        color: #555;
        margin-bottom: 8px;
    }
    /* ถ้าใน _form มีปุ่ม Save ให้มันโค้งมนตามสไตล์หลัก */
    .jobservice-update .btn-success, .jobservice-update .btn-primary {
        border-radius: 25px !important;
        padding: 8px 25px !important;
        font-weight: bold !important;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1) !important;
    }
</style>