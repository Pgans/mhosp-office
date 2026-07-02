<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Requesxthistory */

$this->title = Yii::t('app', 'Create Requesxthistory');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Requesxthistories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="requesxthistory-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
