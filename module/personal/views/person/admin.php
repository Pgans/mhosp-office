<?php

use yii\helpers\Html;
//use yii\grid\GridView;
use yii\helpers\ArrayHelper;
use app\models\Departments;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel backend\modules\personal\models\PersonSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'บุคลากร';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-warning box-solid">
  <div class ="box-header">
          <h3 class = "box-title"><i class="fa fa-users"></i> <?= Html::encode($this->title) ?></h3>
            </div>
          <div class="box-body">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <p>
        <?= Html::a('เพิ่่มบุคลากร', ['create'], ['class' => 'btn btn-success']) ?>
    </p>
    <!--<?php Modal::begin([
        'id' => 'modal',
        'header' => '<h4>เพิ่มบุคลากรโดยระบุ Username:Passwordและชื่อ-สกุล</h4>',
        'size'=>'modal-lg',
        'footer' => '<a href="#" class="btn btn-danger" data-dismiss="modal">ปิด</a>',
        ]);
        echo "<div id='modalContent'></div>";
        Modal::end();
        ?>-->
     
    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
          'panel' =>[
            'before'=> ''
          ],
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
              'attribute'=> 'photo',
              'format'=> 'html',
                'value' => function($model) {
                  return Html::img('uploads/person/'. $model->photo, ['class'=>'thumbnail', 'width'=>80]);
                }
            ],

            //'photo',
            'user.username',
            'user.email',
            'firstname',
            'lastname',
            'birthdate',
             'start_date',
             'stop_date',
             [
               'attribute' => 'department.dep_id',
               'value'=> 'department.dep_name',
               'filter' => Html::activeDropDownList($searchModel, 'dep_id',
               ArrayHelper::map(Departments::find()->all(), 'dep_id', 'dep_name'),
               ['class' => 'form-control'])
             ],

             //'dep_id',
             'positions.position_name',

            ['class' => 'yii\grid\ActionColumn'],
        ],
    ]); ?>
   
</div>
</div>
