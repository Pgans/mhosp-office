<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Logclosevisitsj */

$this->title = Yii::t('app', 'Create Logclosevisitsj');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Logclosevisitsjs'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="logclosevisitsj-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
