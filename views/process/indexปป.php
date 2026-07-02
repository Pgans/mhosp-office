<?php
use yii\helpers\Html;
use yii\grid\GridView;
use yii\helpers\Url;

$this->title = '🔍 MySQL Process Monitor';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
    body {
        background: linear-gradient(to right, #f8f9fa, #e9ecef);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .modern-card {
        background: rgba(255, 255, 255, 0.85);
        border-radius: 20px;
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(7px);
        -webkit-backdrop-filter: blur(7px);
        border: 1px solid rgba(200, 200, 200, 0.3);
    }
    .table th {
        background-color: #e3f2fd;
        color: #0d47a1;
        font-weight: bold;
    }
    .table-hover tbody tr:hover {
        background-color: #f1f8ff;
        transition: background-color 0.2s ease;
    }
    .btn-kill {
        background: linear-gradient(45deg, #ff6b6b, #d32f2f);
        border: none;
        color: white !important;
        padding: 5px 12px;
        border-radius: 12px;
        font-weight: bold;
        transition: transform 0.2s ease;
    }
    .btn-kill:hover {
        transform: scale(1.05);
    }
</style>

<div class="container-fluid py-4">
    <div class="modern-card p-4">
        <h2 class="mb-4 text-primary">
            <i class="fas fa-server"></i> <?= Html::encode($this->title) ?>
        </h2>

        <?= GridView::widget([
            'tableOptions' => [
                'class' => 'table table-hover table-striped table-bordered rounded',
            ],
            'dataProvider' => new \yii\data\ArrayDataProvider([
                'allModels' => $processList,
                'pagination' => ['pageSize' => 15],
            ]),
            'columns' => [
                ['class' => 'yii\grid\SerialColumn'],
                'Id',
                'User',
                'Host',
                'db',
                'Command',
                'Time',
                'State',
                [
                    'attribute' => 'Info',
                    'contentOptions' => [
                        'style' => 'max-width: 300px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;',
                    ],
                ],
                [
                    'class' => 'yii\grid\ActionColumn',
                    'template' => '{kill}',
                    'buttons' => [
                        'kill' => function ($url, $model, $key) {
                            return Html::a('<i class="fas fa-times-circle"></i> Kill', Url::to(['kill', 'id' => $model['Id']]), [
                                'class' => 'btn btn-kill btn-sm',
                                'data' => [
                                    'confirm' => 'คุณแน่ใจหรือไม่ว่าต้องการ Kill Process นี้?',
                                    'method' => 'post',
                                ],
                            ]);
                        },
                    ],
                ],
            ],
            'layout' => "{items}\n{pager}",
            'pager' => [
                'options' => ['class' => 'pagination justify-content-center'],
                'linkContainerOptions' => ['class' => 'page-item'],
                'linkOptions' => ['class' => 'page-link rounded-pill shadow-sm'],
                'activePageCssClass' => 'active bg-primary text-white',
                'disabledPageCssClass' => 'disabled',
                'prevPageLabel' => '<i class="fas fa-chevron-left"></i>',
                'nextPageLabel' => '<i class="fas fa-chevron-right"></i>',
            ],
        ]); ?>
    </div>
</div>
