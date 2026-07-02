<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Jobcom */

$this->title = Yii::t('app', 'Create Jobcom');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Jobcoms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="jobcom-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
