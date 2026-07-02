<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Registerdata */

$this->title = 'Create Registerdata';
$this->params['breadcrumbs'][] = ['label' => 'Registerdatas', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="registerdata-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
