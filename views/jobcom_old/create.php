<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model backend\models\Jobcom */

$this->title = 'เพิ่มแจ้งซ่อม';
$this->params['breadcrumbs'][] = ['label' => 'ระบบแจ้งซ่อมคอมพิวเตอร์', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jobcom-create">

    

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
