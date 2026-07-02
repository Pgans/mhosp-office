<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use kartik\grid\GridView;
use backend\models\Jobtype;
use yii\widgets\Pjax;
use yii\bootstrap\Modal;
use yii\helpers\Url;
use backend\models\departmentjob;
use kartik\widgets\Select2;
use yii2fullcalendar\yii2fullcalendar;

/* @var $this yii\web\View */
/* @var $searchModel app\models\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ปฏิทินส่งซ่อมคอมพิวเตอร์และโสต';
$this->params['breadcrumbs'][] = $this->title;

$css = <<<CSS
@import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap');

.repair-calendar-wrapper {
    font-family: 'Sarabun', sans-serif;
    max-width: 1850px;
    margin: 32px auto;
}

/* ── Action Bar ── */
.repair-action-bar {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 18px;
    flex-wrap: wrap;
}

.btn-repair {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 11px 26px;
    font-family: 'Sarabun', sans-serif;
    font-size: 1.5rem;
    font-weight: 600;
    border-radius: 9px;
    border: none;
    text-decoration: none;
    transition: all .18s ease;
    white-space: nowrap;
    cursor: pointer;
}
.btn-repair:hover {
    transform: translateY(-1px);
    text-decoration: none;
}

.btn-repair-add {
    background: linear-gradient(135deg, #059669, #10b981);
    color: #fff;
    box-shadow: 0 3px 10px rgba(5,150,105,.25);
}
.btn-repair-add:hover {
    background: linear-gradient(135deg, #047857, #059669);
    color: #fff;
    box-shadow: 0 5px 14px rgba(5,150,105,.35);
}

.btn-repair-home {
    background: linear-gradient(135deg, #1a5fa8, #1e7fc2);
    color: #fff;
    box-shadow: 0 3px 10px rgba(26,95,168,.25);
}
.btn-repair-home:hover {
    background: linear-gradient(135deg, #154e8f, #1a5fa8);
    color: #fff;
    box-shadow: 0 5px 14px rgba(26,95,168,.35);
}

/* ── Card ── */
.repair-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,.07), 0 10px 28px rgba(0,0,0,.05);
    overflow: hidden;
    border: 1px solid #e8edf3;
}

/* ── Card Header ── */
.repair-card-header {
    background: linear-gradient(135deg, #6d28d9 0%, #7c3aed 100%);
    padding: 24px 36px;
    display: flex;
    align-items: center;
    gap: 16px;
    position: relative;
}
.repair-card-header::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, #f59e0b, #fbbf24, #f59e0b);
}
.repair-card-header .header-icon {
    width: 52px; height: 52px;
    background: rgba(255,255,255,.18);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 24px;
    color: #fff;
    flex-shrink: 0;
}
.repair-card-header h3 {
    margin: 0;
    color: #fff;
    font-size: 1.5rem;
    font-weight: 600;
}
.repair-card-header .sub-title {
    margin: 3px 0 0;
    color: rgba(255,255,255,.75);
    font-size: 1.5rem;
}

/* ── Card Body ── */
.repair-card-body {
    padding: 28px 36px 36px;
}

/* ── FullCalendar overrides ── */
.repair-card-body .fc {
    font-family: 'Sarabun', sans-serif;
}
.repair-card-body .fc-toolbar h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a3a5c;
}
.repair-card-body .fc-button {
    background: linear-gradient(135deg, #6d28d9, #7c3aed) !important;
    border: none !important;
    border-radius: 7px !important;
    font-family: 'Sarabun', sans-serif !important;
    font-size: 1.5rem !important;
    padding: 7px 16px !important;
    box-shadow: 0 2px 6px rgba(109,40,217,.2) !important;
    transition: all .15s ease !important;
}
.repair-card-body .fc-button:hover {
    background: linear-gradient(135deg, #5b21b6, #6d28d9) !important;
    transform: translateY(-1px) !important;
}
.repair-card-body .fc-button-active,
.repair-card-body .fc-button.fc-state-active {
    background: linear-gradient(135deg, #065f46, #059669) !important;
}
.repair-card-body .fc-day-header {
    background: #f5f3ff;
    color: #4c1d95;
    font-weight: 600;
    font-size: 1.5rem;
    padding: 9px 0;
    border-color: #ddd6fe;
}
.repair-card-body .fc-today {
    background: #f5f3ff !important;
}
.repair-card-body .fc-event {
    border-radius: 5px;
    border: none;
    font-size: 1.5rem;
    padding: 2px 6px;
    font-family: 'Sarabun', sans-serif;
}
CSS;

$this->registerCss($css);
?>

<div class="repair-calendar-wrapper">

    <!-- Action Bar -->
    <div class="repair-action-bar">
        <?= Html::a('<i class="fa fa-plus-circle"></i> เพิ่มการจอง', ['create'], ['class' => 'btn-repair btn-repair-add']) ?>
        <?= Html::a('<i class="fa fa-home"></i> หน้าหลัก', ['index'], ['class' => 'btn-repair btn-repair-home']) ?>
    </div>

    <!-- Calendar Card -->
    <div class="repair-card">
        <div class="repair-card-header">
            <div class="header-icon"><i class="fa fa-wrench"></i></div>
            <div>
                <h3><?= Html::encode($this->title) ?></h3>
                <div class="sub-title">ระบบแจ้งซ่อม — ปฏิทินรายการซ่อมทั้งหมด</div>
            </div>
        </div>
        <div class="repair-card-body">

            <?php Pjax::begin(); ?>

            <?= yii2fullcalendar::widget([
                'options' => ['lang' => 'th'],
                'events'  => $events,
                'id'      => 'calendar',
            ]) ?>

            <?php Pjax::end(); ?>

        </div>
    </div>

</div>

<?php
Modal::begin([
    'id'     => 'modal',
    'header' => '<h4><i class="fa fa-wrench"></i> แจ้งซ่อมคอมพิวเตอร์และโสต</h4>',
    'size'   => 'modal-lg',
    'footer' => '<a href="#" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-times"></i> ปิด</a>',
]);
echo "<div id='modalContent'></div>";
Modal::end();
?>