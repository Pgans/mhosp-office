<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Meetingagenda */

$this->title = Yii::t('app', 'Create Meetingagenda');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Meetingagendas'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="meetingagenda-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
