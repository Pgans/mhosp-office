<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model common\models\Person */

$this->title = 'แก้ไขบุคลากร: ' . ' ' . $model->firstname.' '.$model->lastname;
$this->params['breadcrumbs'][] = ['label' => 'บุคลากร', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->firstname, 'url' => ['view', 'user_id' => $model->user_id, 'dep_id' => $model->dep_id, 'positions_id' => $model->positions_id]];
$this->params['breadcrumbs'][] = 'แก้ไข';
?>
<div class="box box-warning box-solid">
  <div class ="box-header">
          <h3 class = "box-title"><i class="fa fa-users"></i> <?= Html::encode($this->title) ?></h3>
            </div>
          <div class="box-body">


    <?= $this->render('_form', [
        'model' => $model,
        'user' => $user,
    ]) ?>

</div>
</div>
