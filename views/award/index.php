<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;




/* @var $this yii\web\View */
/* @var $searchModel app\models\awardSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'รางวัลAward';
$this->params['breadcrumbs'][] = $this->title;
?>
  <div class="award-index">

<div class="panel panel-danger">
                    <div class="panel-heading"><h3><i class="glyphicon glyphicon-user"></i> ระบบการบันทึกรางวัลAwards</h3></div>
                    <div class="panel-body">
                    <div class="row">

 <p>
    <?= Html::button('เพิ่มข้อมูลรางวัล', ['value'=>Url::to(['award/create']), 'class' =>
     'btn btn-success btn-lg','id'=>'modalButton']); ?> 
     </p>

<?php Modal::begin([
    'id' => 'modal',
    'header' => '<h4><a color-blue>CREATE AWARD</a></h4>',
    'size'=>'modal-lg',
    'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal">ปิด</a>',
    ]);
    echo "<div id='modalContent'></div>";
    Modal::end();
    ?>
    <?php Pjax::begin() ?>
  <?= GridView::widget([
      'dataProvider' => $dataProvider,
      'filterModel' => $searchModel,
      'columns' => [
          ['class' => 'yii\grid\SerialColumn'],
          [
            'options'=>['style'=>'width:150px;'],
            'format'=>'raw',
            'attribute'=>'photo',
            'value'=>function($model){
              return Html::tag('div','',[
                'style'=>'width:150px;height:95px;
                          border-top: 10px solid rgba(255, 255, 255, .46);
                          background-image:url('.$model->photoViewer.');
                          background-size: cover;
                          background-position:center center;
                          background-repeat:no-repeat;
                          ']);
            }
        ],
        'name',
         'surname:ntext',
          
         // 'title',
          ['attribute'=>'covenant','value'=>function($model){return $model->listDownloadFiles('covenant');},'format'=>'html'],
          'create_date',
          ['class' => 'yii\grid\ActionColumn',
          'header'=>'คลิกดู',
          'headerOptions' => ['style' => 'width:13%'],
          'template'=>'<div class="btn-group btn-group-sm text-center" role="group"> {detail} {edit} {del} </div>',
          'buttons'=>[
              'detail' => function($url,$model,$key){
                  return Html::a('ดู',
                      ['view', 'id' => $model->id],
                      ['class' => 'btn btn-info'],
                      $url);
              },
              'edit' => function($url,$model,$key){
                  return Html::a('ปรับปรุง',
                      ['update', 'id' => $model->id],
                      ['class' => 'btn btn-success'],
                      $url);
              },
              'del' => function($url,$model,$key){
                  return Html::a('ลบ',
                      ['delete', 'id' => $model->id],
                      ['class' => 'btn btn-danger'],
                      $url);
              },
          ],
      ],

     ],
 ]); ?>
 <? Pjax::end() ?>
</div>
<?php
$this->registerJsFile('@web/js/main.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
?>
