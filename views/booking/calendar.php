<?php
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii2fullcalendar\yii2fullcalendar;
/* @var $this yii\web\View */
/* @var $searchModel app\models\OrdersSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'ปฏิทินการจอง';
$this->params['breadcrumbs'][] = $this->title;

$css = <<<CSS
@import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap');

.calendar-wrapper {
    font-family: 'Sarabun', sans-serif;
    max-width: 1950px;
    margin: 32px auto;
}

/* ── Notice Bar ── */
.calendar-notice {
    background: linear-gradient(135deg, #fffbeb, #fef3c7);
    border: 1.5px solid #f59e0b;
    border-radius: 12px;
    padding: 14px 24px;
    margin-bottom: 18px;
    display: flex;
    align-items: flex-start;
    gap: 12px;
}
.calendar-notice .notice-icon {
    color: #d97706;
    font-size: 1.5rem;
    margin-top: 2px;
    flex-shrink: 0;
}
.calendar-notice .notice-text {
    font-size: 1.5rem;
    color: #78350f;
    line-height: 1.7;
}
.calendar-notice .notice-text strong {
    color: #b45309;
    font-weight: 700;
}

/* ── Top Action Bar ── */
.calendar-action-bar {
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

/* ── Calendar Card ── */
.calendar-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,.07), 0 10px 28px rgba(0,0,0,.05);
    overflow: hidden;
    border: 1px solid #e8edf3;
}

.calendar-card-header {
    background: linear-gradient(135deg, #1a5fa8 0%, #1e7fc2 100%);
    padding: 22px 36px;
    display: flex;
    align-items: center;
    gap: 14px;
    position: relative;
}
.calendar-card-header::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, #f59e0b, #fbbf24, #f59e0b);
}
.calendar-card-header .header-icon {
    width: 48px; height: 48px;
    background: rgba(255,255,255,.18);
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-size: 1.5rem;
    color: #fff;
    flex-shrink: 0;
}
.calendar-card-header h3 {
    margin: 0;
    color: #fff;
    font-size: 1.5rem;
    font-weight: 600;
}
.calendar-card-header .sub-title {
    margin: 3px 0 0;
    color: rgba(255,255,255,.75);
    font-size: 1.5rem;
}

.calendar-card-body {
    padding: 28px 36px 36px;
}

/* ── FullCalendar overrides ── */
.calendar-card-body .fc {
    font-family: 'Sarabun', sans-serif;
}
.calendar-card-body .fc-toolbar h2 {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1a3a5c;
}
.calendar-card-body .fc-button {
    background: linear-gradient(135deg, #1a5fa8, #1e7fc2) !important;
    border: none !important;
    border-radius: 7px !important;
    font-family: 'Sarabun', sans-serif !important;
    font-size: 1.5rem !important;
    padding: 6px 14px !important;
    box-shadow: 0 2px 6px rgba(26,95,168,.2) !important;
    transition: all .15s ease !important;
}
.calendar-card-body .fc-button:hover {
    background: linear-gradient(135deg, #154e8f, #1a5fa8) !important;
    transform: translateY(-1px) !important;
}
.calendar-card-body .fc-button-active,
.calendar-card-body .fc-button.fc-state-active {
    background: linear-gradient(135deg, #065f46, #059669) !important;
}
.calendar-card-body .fc-day-header {
    background: #f1f5fb;
    color: #344563;
    font-weight: 600;
    font-size: 1.5rem;
    padding: 8px 0;
    border-color: #dce8f6;
}
.calendar-card-body .fc-today {
    background: #eff6ff !important;
}
.calendar-card-body .fc-event {
    border-radius: 5px;
    border: none;
    font-size: 1.5rem;
    padding: 2px 6px;
    font-family: 'Sarabun', sans-serif;
}
CSS;

$this->registerCss($css);
?>

<div class="calendar-wrapper">

    <!-- Notice Bar -->
    <div class="calendar-notice">
        <div class="notice-icon"><i class="fa fa-info-circle"></i></div>
        <div class="notice-text">
            <strong>หมายเหตุ:</strong>
            จองห้องประชุมได้ไม่เกิน <strong>3 วันต่อครั้ง</strong> &nbsp;|&nbsp;
            ยกเลิกห้องประชุม: Login แล้วแก้ไขสถานะเป็น <strong>"ยกเลิก"</strong> — ระบบจะไม่แสดงในปฏิทิน และไม่แจ้งเตือนในไลน์
        </div>
    </div>

    <!-- Action Bar -->
    <div class="calendar-action-bar">
        <?= Html::a('<i class="fa fa-plus-circle"></i> เพิ่มการจอง', ['create'], ['class' => 'btn-add-booking']) ?>
    </div>

    <!-- Calendar Card -->
    <div class="calendar-card">
        <div class="calendar-card-header">
            <div class="header-icon"><i class="fa fa-calendar"></i></div>
            <div>
                <h3><?= Html::encode($this->title) ?></h3>
                <div class="sub-title">ระบบจองพาหนะ — ปฏิทินรายการจองทั้งหมด</div>
            </div>
        </div>
        <div class="calendar-card-body">
            <?= yii2fullcalendar::widget([
                'options' => ['lang' => 'th'],
                'events'  => $events,
                'id'      => 'calendar',
            ]) ?>
        </div>
    </div>

</div>