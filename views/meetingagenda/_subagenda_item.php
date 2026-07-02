<?php  

use app\models\Agendasubs;
?>
<!-- views/meeting/_subagenda_list.php -->

<h3>รายการ Subagenda ของวาระการประชุม: <?= $agendaitem->title ?></h3>

<?= \yii\grid\GridView::widget([
    'dataProvider' => new \yii\data\ActiveDataProvider([
        'query' => Agendasubs::find()->where(['agenda_id' => $model->agenda_id]),
    ]),
    'columns' => [
        'sub_topic',
        'sub_description',
        // เพิ่มคอลัมน์อื่น ๆ ของ subagenda ที่คุณต้องการแสดง
    ],
]) ?>


