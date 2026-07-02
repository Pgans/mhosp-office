<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model app\models\Repimport */

$this->title = 'Create Repimport';
$this->params['breadcrumbs'][] = ['label' => 'Repimports', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="repimport-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
