<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Jobservice */

$this->title = 'เพิ่มส่งซ่อมพัสดุ';
$this->params['breadcrumbs'][] = ['label' => 'ส่งซ่อมพัสดุ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jobservice-create">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
