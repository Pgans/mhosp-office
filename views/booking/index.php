<?php

use yii\helpers\Html;
use kartik\grid\GridView;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\Room;
use app\models\Usefor;
use app\models\BookingStatus;
use yii\bootstrap\Modal;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\BookingSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ระบบจองห้องประชุม';
$this->params['breadcrumbs'][] = $this->title;

$css = <<<CSS
@import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap');

.booking-index-wrapper {
    font-family: 'Sarabun', sans-serif;
    max-width: 1950px;
    margin: 32px auto;
}

/* ── Action Bar ── */
.booking-action-bar {
    display: flex;
    align-items: center;
    gap: 14px;
    margin-bottom: 18px;
}
.btn-add-booking {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 26px;
    background: linear-gradient(135deg, #059669, #10b981);
    color: #fff;
    font-family: 'Sarabun', sans-serif;
    font-size: 1.5rem;
    font-weight: 600;
    border-radius: 9px;
    border: none;
    text-decoration: none;
    box-shadow: 0 3px 10px rgba(5,150,105,.25);
    transition: all .18s ease;
    white-space: nowrap;
}
.btn-add-booking:hover {
    background: linear-gradient(135deg, #047857, #059669);
    color: #fff;
    text-decoration: none;
    transform: translateY(-1px);
    box-shadow: 0 5px 14px rgba(5,150,105,.35);
}

/* ── Flash Alert ── */
.booking-alerts {
    margin-bottom: 16px;
}
.booking-alerts .alert {
    font-family: 'Sarabun', sans-serif;
    font-size: 1.5rem;
    border-radius: 10px;
    border: none;
    padding: 13px 18px;
}
.booking-alerts .alert-success { background: #d1fae5; color: #065f46; }
.booking-alerts .alert-danger  { background: #fee2e2; color: #7f1d1d; }
.booking-alerts .alert-warning { background: #fef3c7; color: #78350f; }
.booking-alerts .alert-info    { background: #dbeafe; color: #1e3a8a; }

/* ── Card ── */
.booking-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,.07), 0 10px 28px rgba(0,0,0,.05);
    overflow: hidden;
    border: 1px solid #e8edf3;
}

/* ── Card Header ── */
.booking-card-header {
    background: linear-gradient(135deg, #1a5fa8 0%, #1e7fc2 100%);
    padding: 22px 36px;
    display: flex;
    align-items: center;
    gap: 14px;
    position: relative;
}
.booking-card-header::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, #f59e0b, #fbbf24, #f59e0b);
}
.booking-card-header .header-icon {
    width: 48px; height: 48px;
    background: rgba(255,255,255,.18);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    color: #fff;
    flex-shrink: 0;
}
.booking-card-header h3 {
    margin: 0;
    color: #fff;
    font-size: 1.5rem;
    font-weight: 600;
}
.booking-card-header .sub-title {
    margin: 3px 0 0;
    color: rgba(255,255,255,.75);
    font-size: 1.5rem;
}

/* ── Card Body ── */
.booking-card-body {
    padding: 28px 36px 36px;
}

/* ── GridView ── */
.booking-card-body .grid-view table {
    font-family: 'Sarabun', sans-serif;
    font-size: 1.5rem;
    border-collapse: separate;
    border-spacing: 0;
    width: 100%;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    overflow: hidden;
}
.booking-card-body .grid-view table th {
    background: #f1f5fb;
    color: #344563;
    font-weight: 600;
    font-size: 1.5rem;
    padding: 13px 16px;
    border-bottom: 1px solid #dce8f6;
    border-right: 1px solid #e2e8f0;
    vertical-align: middle;
    white-space: nowrap;
}
.booking-card-body .grid-view table td {
    padding: 12px 16px;
    color: #2d3748;
    border-bottom: 1px solid #edf2f7;
    vertical-align: middle;
    font-size: 1.5rem;
}
.booking-card-body .grid-view table tr:last-child td { border-bottom: none; }
.booking-card-body .grid-view table tr:hover td { background: #f8fbff; }

/* Filter inputs */
.booking-card-body .grid-view .filters td { background: #f8fafc; padding: 8px 10px; }
.booking-card-body .grid-view .filters .form-control {
    font-family: 'Sarabun', sans-serif;
    font-size: 1.5rem;
    border: 1.5px solid #d1dce8;
    border-radius: 7px;
    padding: 6px 10px;
    color: #2d3748;
}
.booking-card-body .grid-view .filters .form-control:focus {
    border-color: #1a5fa8;
    box-shadow: 0 0 0 3px rgba(26,95,168,.1);
    outline: none;
}

/* Pagination */
.booking-card-body .pagination > li > a,
.booking-card-body .pagination > li > span {
    font-family: 'Sarabun', sans-serif;
    font-size: 1.5rem;
    border-radius: 7px !important;
    margin: 0 2px;
    color: #1a5fa8;
    border-color: #dce8f6;
}
.booking-card-body .pagination > .active > a {
    background: linear-gradient(135deg, #1a5fa8, #1e7fc2);
    border-color: #1a5fa8;
    color: #fff;
}

/* Action buttons in grid */
.booking-card-body .btn-default {
    font-family: 'Sarabun', sans-serif;
    font-size: 1.5rem;
    border-radius: 6px;
    border: 1.5px solid #d1dce8;
    color: #344563;
    background: #f8fafc;
    padding: 5px 10px;
    transition: all .15s;
}
.booking-card-body .btn-default:hover {
    background: #eff6ff;
    border-color: #60a5fa;
    color: #1a5fa8;
}

/* Summary bar */
.booking-card-body .summary {
    font-family: 'Sarabun', sans-serif;
    font-size: 1.5rem;
    color: #64748b;
    margin-bottom: 10px;
}
CSS;

$this->registerCss($css);
?>

<div class="booking-index-wrapper">

    <!-- Action Bar -->
    <div class="booking-action-bar">
        <?= Html::a('<i class="fa fa-plus-circle"></i> เพิ่มการจอง', ['create'], ['class' => 'btn-add-booking']) ?>
    </div>

    <!-- Flash Messages -->
    <div class="booking-alerts">
        <?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
            <div class="alert alert-<?= $type ?> alert-dismissible fade in" role="alert">
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <i class="fa fa-info-circle"></i> <?= $message ?>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Main Card -->
    <div class="booking-card">
        <div class="booking-card-header">
            <div class="header-icon"><i class="fa fa-list-alt"></i></div>
            <div>
                <h3><?= Html::encode($this->title) ?></h3>
                <div class="sub-title">รายการจองห้องประชุมทั้งหมด</div>
            </div>
        </div>
        <div class="booking-card-body">

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel'  => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    [
                        'attribute' => 'booking_room',
                        'format'    => 'html',
                        'value'     => function ($model) {
                            return $model->bookingRoom->room_name;
                        },
                        'filter' => Html::activeDropDownList(
                            $searchModel, 'booking_room',
                            ArrayHelper::map(Room::find()->all(), 'room_id', 'room_name'),
                            ['class' => 'form-control', 'prompt' => 'ทั้งหมด...']
                        ),
                    ],
                    'booking_start',
                    'booking_end',
                    [
                        'attribute' => 'booking_usefor',
                        'format'    => 'html',
                        'value'     => function ($model) {
                            return $model->bookingUsefor->usefor_name;
                        },
                        'filter' => Html::activeDropDownList(
                            $searchModel, 'booking_usefor',
                            ArrayHelper::map(Usefor::find()->all(), 'usefor_id', 'usefor_name'),
                            ['class' => 'form-control', 'prompt' => 'ทั้งหมด...']
                        ),
                    ],
                    'booking_user',
                    'booking_title',
                    [
                        'attribute' => 'booking_status',
                        'format'    => 'html',
                        'value'     => function ($model) {
                            return '<span class="badge" style="background-color:' . $model->bookingStatus->booking_statust_color . '; font-size:1.5rem; padding:5px 10px; border-radius:6px;">'
                                . '<b>' . $model->bookingStatus->booking_status_name . '</b></span>';
                        },
                        'filter' => Html::activeDropDownList(
                            $searchModel, 'booking_status',
                            ArrayHelper::map(Bookingstatus::find()->all(), 'booking_status_id', 'booking_status_name'),
                            ['class' => 'form-control', 'prompt' => 'ทั้งหมด...']
                        ),
                    ],
                    [
                        'class'         => 'kartik\grid\ActionColumn',
                        'options'       => ['style' => 'width:100px;'],
                        'buttonOptions' => ['class' => 'btn btn-default'],
                        'template'      => '<div class="btn-group btn-group-xs text-center" role="group">{view}</div>',
                    ],
                ],
            ]); ?>

        </div>
    </div>

</div>

<?php
Modal::begin([
    'header' => '<h4>ระบบจองห้องประชุม</h4>',
    'id'     => 'modal',
    'size'   => 'modal-lg',
]);
echo "<div id='modalContent'></div>";
?>