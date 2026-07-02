<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Permits */

$this->title = 'เพิ่มการยืมเวชระเบียน';
$this->params['breadcrumbs'][] = ['label' => 'การยืม', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="box box-warning box-solid">
  <div class ="box-header" id="grad01">
          <h3 class = "box-title"><i class="fa fa-home"></i> <?= Html::encode($this->title) ?></h3>
            </div>
          <div class="box-body">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
</div>