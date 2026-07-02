<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Orderoils */

$this->title = 'เบิกจ่ายน้ำมันเชื้อเพลิง';
$this->params['breadcrumbs'][] = ['label' => 'รายการเบิกน้ำมัน', 'url' => ['index']];
$this->params['breadcrumbs'][] = 'เพิ่มรายการใหม่';
?>
<div class="orderoils-create">
    <?= $this->render('_form', [
        'model' => $model,
        'amphurList' => $amphurList,
    ]) ?>
</div>