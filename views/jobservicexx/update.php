<?php
use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Jobservice */

$this->title = 'แก้ไข่งซ่อมพัสดุ';
$this->params['breadcrumbs'][] = ['label' => 'แก้ไขส่งซ่อมพัสดุ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jobservice-update">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>

