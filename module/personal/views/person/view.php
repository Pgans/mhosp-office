<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
//use common\models\Positions;
//use common\models\Departments;

/* @var $this yii\web\View */
/* @var $model common\models\Person */

$this->title = $model->firstname.' '.$model->lastname;
$this->params['breadcrumbs'][] = ['label' => 'บุคลากร', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-warning box-solid">
  <div class ="box-header">
          <h3 class = "box-title"><i class="fa fa-users"></i> <?= Html::encode($this->title) ?></h3>
            </div>
          <div class="box-body">

    <p>
        <?= Html::a('Update', ['update', 'user_id' => $model->user_id, 'dep_id' => $model->dep_id, 'positions_id' => $model->positions_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'user_id' => $model->user_id, 'dep_id' => $model->dep_id, 'positions_id' => $model->positions_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>
      <?= Html::img('uploads/person/'. $model->photo, ['class'=>'thumbnail', 'width'=>200])?>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'user.username',
            'user.email',
            'firstname',
            'lastname',
            'photo',
            'birthdate',
            'start_date',
            'stop_date',
            'department.dep_name',
            'positions.position_name',
        ],
    ]) ?>

</div>
</div>
