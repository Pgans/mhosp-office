<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Jobcom */

$this->title = Yii::t('app', 'รายการส่งซ่อมคอมพิวเตอร์และโสตทัศนศึกษา');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Jobcoms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jobcom-create">

   
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
