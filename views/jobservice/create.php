<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Jobservice */

$this->title = 'เพิ่มส่งซ่อมพัสดุ';
$this->params['breadcrumbs'][] = ['label' => 'ส่งซ่อมพัสดุ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-success box-solid">
<div class ="box-header" id="grad01">
          <h3 class = "box-title"><i class="fa fa-users"></i> <?= Html::encode($this->title) ?></h3>
            </div>
          <div class="box-body">
<div class="jobservice-form">
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
