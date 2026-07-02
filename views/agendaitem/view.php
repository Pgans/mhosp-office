<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model app\models\Agendaitem */

$this->title = $model->agenda_id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Agendaitems'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="agendaitem-view">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a(Yii::t('app', 'Update'), ['update', 'id' => $model->agenda_id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app', 'Delete'), ['delete', 'id' => $model->agenda_id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'agenda_id',
            'meeting_agenda_id',
            'ref',
            'topic',
            'discription:ntext',
            'covenant',
            'docs',
            'create_date',
            'view',
        ],
    ]) ?>

</div>
<h1>รายการวาระการประชุม</h1>

<?php if (!empty($agendaItem)) : ?>
    <?php foreach ($agendaItem as $agenda) : ?>
        <h3><?= $agenda->sub_topic ?></h3>
        <?php if (!empty($agenda->subagendas)) : ?>
            <?= \yii\grid\GridView::widget([
                'dataProvider' => new \yii\data\ActiveDataProvider([
                    'query' => $agenda->getSubagendas(),
                ]),
                'columns' => [
                    'sub_topic',
                    'sub_description',
                    // เพิ่มคอลัมน์อื่น ๆ ของ subagenda ที่คุณต้องการแสดง
                ],
            ]) ?>
        <?php else : ?>
            <p>ไม่มีรายการ subagenda</p>
        <?php endif; ?>
    <?php endforeach; ?>
<?php else : ?>
    <p>ไม่มีรายการวาระการประชุม</p>
<?php endif; ?>