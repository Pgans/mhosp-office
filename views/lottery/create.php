<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\data\ArrayDataProvider;

/* @var $this yii\web\View */
/* @var $model app\models\Lottery */

$this->title = 'Create Lottery';
$this->params['breadcrumbs'][] = ['label' => 'Lotteries', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="panel panel-info">
                        <div class="panel-heading"><i class="glyphicon glyphicon-user"></i> Create HUAY</div>
                        <div class="panel-body">
                        <div class="row">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>

</div>
