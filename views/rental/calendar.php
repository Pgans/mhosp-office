<?php
use yii\helpers\Html;
use yii2fullcalendar\yii2fullcalendar;

/* @var $this yii\web\View */
$this->title = 'ปฏิทินการจองรถ';
$this->params['breadcrumbs'][] = $this->title;

$css = <<<CSS
@import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Prompt:wght@500;600;700&display=swap');

body, .content-wrapper {
    background: #f0f4f8 !important;
    font-family: 'Sarabun', sans-serif !important;
}

/* ══════════════════════════════
   HERO HEADER
══════════════════════════════ */
.cal-hero {
    background: linear-gradient(135deg, #134e4a 0%, #0d9488 55%, #2dd4bf 100%);
    border-radius: 18px;
    padding: 28px 32px 22px;
    margin-bottom: 20px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 28px rgba(13,148,136,0.28);
}
.cal-hero::before {
    content: '';
    position: absolute;
    top: -55px; right: -55px;
    width: 210px; height: 210px;
    background: rgba(255,255,255,0.07);
    border-radius: 50%;
    pointer-events: none;
}
.cal-hero::after {
    content: '';
    position: absolute;
    bottom: -40px; left: 30%;
    width: 150px; height: 150px;
    background: rgba(255,255,255,0.04);
    border-radius: 50%;
    pointer-events: none;
}
.cal-hero .hero-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    flex-wrap: wrap;
    gap: 12px;
    position: relative;
    z-index: 1;
}
.cal-hero .hero-icon { font-size: 36px; margin-bottom: 6px; }
.cal-hero h2 {
    font-family: 'Prompt', sans-serif;
    font-size: 22px;
    font-weight: 600;
    color: #fff;
    margin: 0 0 3px;
}
.cal-hero .hero-sub { font-size: 12.5px; color: rgba(255,255,255,0.68); margin: 0; }
.cal-hero .hero-right {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 8px;
}
.cal-hero .user-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,0.17);
    border: 1px solid rgba(255,255,255,0.24);
    color: #fff;
    border-radius: 24px;
    padding: 5px 14px;
    font-size: 12.5px;
}
.cal-hero .notice-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,220,100,0.2);
    border: 1px solid rgba(255,220,100,0.38);
    color: #fef08a;
    border-radius: 24px;
    padding: 5px 13px;
    font-size: 11.5px;
    font-weight: 500;
    max-width: 300px;
    line-height: 1.4;
}

/* ══════════════════════════════
   ACTION BAR
══════════════════════════════ */
.cal-action-bar {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
    flex-wrap: wrap;
}
.btn-add-booking {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #0d9488, #0f766e);
    color: #fff !important;
    border: none;
    border-radius: 10px;
    padding: 10px 22px;
    font-family: 'Sarabun', sans-serif;
    font-size: 14.5px;
    font-weight: 600;
    text-decoration: none !important;
    box-shadow: 0 4px 14px rgba(13,148,136,0.35);
    transition: all 0.2s ease;
    cursor: pointer;
}
.btn-add-booking:hover {
    transform: translateY(-2px);
    box-shadow: 0 7px 20px rgba(13,148,136,0.42);
}

/* ══════════════════════════════
   LEGEND
══════════════════════════════ */
.legend-bar {
    display: flex;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 16px;
}
.legend-dot {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 5px 13px;
    font-size: 12px;
    color: #475569;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
.legend-dot .dot-swatch {
    width: 11px; height: 11px;
    border-radius: 50%;
    flex-shrink: 0;
}

/* ══════════════════════════════
   CALENDAR CARD
══════════════════════════════ */
.cal-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.07);
    border: 1px solid #e8edf3;
    overflow: hidden;
}
.cal-card-header {
    background: #f8fafc;
    border-bottom: 1px solid #e8edf3;
    padding: 13px 20px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #64748b;
    font-weight: 500;
}
.cal-card-body { padding: 20px; }

/* ══════════════════════════════
   FULLCALENDAR — TOOLBAR
══════════════════════════════ */
.fc-toolbar {
    margin-bottom: 16px !important;
    flex-wrap: wrap;
    gap: 8px;
}
.fc-toolbar h2 {
    font-family: 'Prompt', sans-serif !important;
    font-size: 19px !important;
    font-weight: 600 !important;
    color: #1e293b !important;
    letter-spacing: -0.2px !important;
}
.fc-button,
.fc-state-default {
    background: #fff !important;
    background-image: none !important;
    border: 1.5px solid #e2e8f0 !important;
    border-radius: 9px !important;
    color: #475569 !important;
    font-family: 'Sarabun', sans-serif !important;
    font-size: 13px !important;
    font-weight: 500 !important;
    padding: 5px 14px !important;
    box-shadow: 0 1px 3px rgba(0,0,0,0.06) !important;
    text-shadow: none !important;
    transition: all 0.18s ease !important;
}
.fc-button:hover, .fc-state-default:hover {
    background: #f1f5f9 !important;
    border-color: #cbd5e1 !important;
    color: #0d9488 !important;
}
.fc-state-active {
    background: linear-gradient(135deg, #0d9488, #0f766e) !important;
    border-color: #0d9488 !important;
    color: #fff !important;
}
.fc-today-button {
    background: linear-gradient(135deg, #0d9488, #0f766e) !important;
    border-color: #0d9488 !important;
    color: #fff !important;
    font-weight: 600 !important;
    box-shadow: 0 3px 10px rgba(13,148,136,0.3) !important;
}
.fc-prev-button, .fc-next-button {
    border-radius: 9px !important;
    font-size: 15px !important;
}

/* ══════════════════════════════
   FULLCALENDAR — GRID
══════════════════════════════ */
.fc-day-header {
    background: #f1f5f9 !important;
    font-family: 'Prompt', sans-serif !important;
    font-size: 12px !important;
    font-weight: 600 !important;
    color: #475569 !important;
    padding: 9px 0 !important;
    text-transform: uppercase !important;
    letter-spacing: 0.5px !important;
}
.fc-today {
    background: #f0fdfa !important;
}
.fc-today .fc-day-number {
    background: linear-gradient(135deg, #0d9488, #14b8a6) !important;
    color: #fff !important;
    border-radius: 50% !important;
    width: 26px !important;
    height: 26px !important;
    line-height: 26px !important;
    text-align: center !important;
    display: inline-block !important;
    font-weight: 700 !important;
    font-size: 13px !important;
    box-shadow: 0 2px 8px rgba(13,148,136,0.35) !important;
}
.fc-day-number {
    font-family: 'Sarabun', sans-serif !important;
    font-size: 13px !important;
    color: #374151 !important;
    padding: 5px 8px !important;
    font-weight: 500 !important;
}
.fc-other-month .fc-day-number { color: #cbd5e1 !important; }
.fc td, .fc th { border-color: #f0f4f8 !important; }

/* ══════════════════════════════
   FULLCALENDAR — EVENT GRADIENT
   ทำงานกับทุกสีโดยอัตโนมัติ
══════════════════════════════ */
.fc-event,
.fc-event:visited {
    border: none !important;
    border-radius: 7px !important;
    padding: 3px 8px 3px 9px !important;
    font-family: 'Sarabun', sans-serif !important;
    font-size: 11.5px !important;
    font-weight: 600 !important;
    cursor: pointer !important;
    position: relative !important;
    overflow: hidden !important;
    margin-bottom: 2px !important;
    isolation: isolate !important;
    /* เงาให้ดูมีมิติ */
    box-shadow:
        0 2px 6px rgba(0,0,0,0.20),
        inset 0 1px 0 rgba(255,255,255,0.20) !important;
    transition: transform 0.15s ease, box-shadow 0.15s ease !important;
}

/* Gradient overlay ทับสีพื้น */
.fc-event::before {
    content: '' !important;
    position: absolute !important;
    inset: 0 !important;
    background: linear-gradient(
        135deg,
        rgba(255,255,255,0.28) 0%,
        rgba(255,255,255,0.06) 45%,
        rgba(0,0,0,0.10) 100%
    ) !important;
    pointer-events: none !important;
    z-index: 0 !important;
    border-radius: inherit !important;
}

/* Shine แถบแนวทแยงบนซ้าย */
.fc-event::after {
    content: '' !important;
    position: absolute !important;
    top: 0 !important; left: 0 !important;
    width: 55% !important; height: 48% !important;
    background: linear-gradient(
        125deg,
        rgba(255,255,255,0.24) 0%,
        transparent 80%
    ) !important;
    pointer-events: none !important;
    z-index: 0 !important;
    border-radius: inherit !important;
}

/* เนื้อหา event อยู่บน gradient */
.fc-event .fc-content,
.fc-event .fc-title,
.fc-event .fc-time {
    position: relative !important;
    z-index: 1 !important;
    color: #fff !important;
    text-shadow: 0 1px 3px rgba(0,0,0,0.28) !important;
}
.fc-event .fc-time {
    font-weight: 700 !important;
    font-size: 10.5px !important;
    opacity: 0.90 !important;
    margin-right: 4px !important;
}
.fc-event .fc-title {
    letter-spacing: 0.1px !important;
    white-space: nowrap !important;
    overflow: hidden !important;
    text-overflow: ellipsis !important;
}

/* Hover lift */
.fc-event:hover {
    transform: translateY(-1px) scale(1.012) !important;
    box-shadow:
        0 5px 14px rgba(0,0,0,0.25),
        inset 0 1px 0 rgba(255,255,255,0.22) !important;
    z-index: 10 !important;
}

.fc-day-grid-event { margin: 1px 3px 2px !important; }

/* ── Multi-day bar ── */
.fc-event.fc-not-start {
    border-radius: 0 7px 7px 0 !important;
}
.fc-event.fc-not-end {
    border-radius: 7px 0 0 7px !important;
}

/* ── "more" link ── */
.fc-more {
    font-family: 'Sarabun', sans-serif !important;
    font-size: 11px !important;
    color: #0d9488 !important;
    font-weight: 600 !important;
    padding: 2px 6px !important;
    background: #f0fdfa !important;
    border-radius: 5px !important;
    border: 1px solid #99f6e4 !important;
    display: inline-block !important;
    margin: 1px 3px !important;
}
.fc-more:hover { background: #ccfbf1 !important; }

/* ── Popover ── */
.fc-popover {
    border-radius: 12px !important;
    border: 1px solid #e2e8f0 !important;
    box-shadow: 0 8px 24px rgba(0,0,0,0.13) !important;
    overflow: hidden !important;
    font-family: 'Sarabun', sans-serif !important;
}
.fc-popover .fc-header {
    background: linear-gradient(135deg, #0d9488, #14b8a6) !important;
    color: #fff !important;
    font-family: 'Prompt', sans-serif !important;
    font-size: 13px !important;
    font-weight: 600 !important;
    padding: 9px 14px !important;
}
.fc-popover .fc-header .fc-close {
    color: rgba(255,255,255,0.8) !important;
    font-size: 16px !important;
}
.fc-popover .fc-body { padding: 10px !important; background: #fff !important; }

@media (max-width: 640px) {
    .cal-hero { padding: 18px; }
    .cal-hero h2 { font-size: 17px; }
    .cal-hero .hero-right { align-items: flex-start; }
    .cal-card-body { padding: 10px; }
    .fc-toolbar { flex-direction: column; align-items: flex-start !important; }
    .fc-toolbar h2 { font-size: 15px !important; }
}
CSS;
$this->registerCss($css);
?>

<div class="orders-index" style="padding: 10px 0;">

    <!-- ══ HERO ══ -->
    <div class="cal-hero">
        <div class="hero-row">
            <div class="hero-left">
                <div class="hero-icon">📅</div>
                <h2><?= Html::encode($this->title) ?></h2>
                <p class="hero-sub">ตรวจสอบและบริหารจัดการการจองรถยนต์รายเดือน</p>
            </div>
            <div class="hero-right">
                <div class="user-pill">
                    <i class="fa fa-user-circle-o"></i>
                    ผู้ใช้งาน: <strong><?= Html::encode(Yii::$app->user->identity->username) ?></strong>
                </div>
                <div class="notice-pill">
                    <i class="fa fa-info-circle" style="flex-shrink:0;margin-top:1px;"></i>
                    ระบบอนุญาตให้ยกเลิก/แก้ไขได้เฉพาะข้อมูลของตนเองเท่านั้น
                </div>
            </div>
        </div>
    </div>

    <!-- ══ ACTION BAR ══ -->
    <div class="cal-action-bar">
        <?= Html::a(
            '<i class="fa fa-plus-circle"></i> เพิ่มการจอง',
            ['create'],
            ['class' => 'btn-add-booking']
        ) ?>
    </div>

    <!-- ══ LEGEND ══ -->
    <div class="legend-bar">
        <div class="legend-dot">
            <span class="dot-swatch" style="background:linear-gradient(135deg,#16a34a,#4ade80)"></span>
            อนุมัติแล้ว
        </div>
        <div class="legend-dot">
            <span class="dot-swatch" style="background:linear-gradient(135deg,#f59e0b,#fcd34d)"></span>
            รอดำเนินการ
        </div>
        <div class="legend-dot">
            <span class="dot-swatch" style="background:linear-gradient(135deg,#2563eb,#60a5fa)"></span>
            กำลังเดินทาง
        </div>
        <div class="legend-dot">
            <span class="dot-swatch" style="background:linear-gradient(135deg,#dc2626,#f87171)"></span>
            ไม่อนุมัติ
        </div>
        <div class="legend-dot">
            <span class="dot-swatch" style="background:linear-gradient(135deg,#7c3aed,#a78bfa)"></span>
            ส่งออก/Outlab
        </div>
        <div class="legend-dot">
            <span class="dot-swatch" style="background:linear-gradient(135deg,#0891b2,#67e8f9)"></span>
            เยี่ยมบ้าน
        </div>
        <div class="legend-dot">
            <span class="dot-swatch" style="background:linear-gradient(135deg,#ec4899,#f9a8d4)"></span>
            PCU / คลินิก
        </div>
        <div class="legend-dot">
            <span class="dot-swatch" style="background:linear-gradient(135deg,#94a3b8,#cbd5e1)"></span>
            ยกเลิก
        </div>
    </div>

    <!-- ══ CALENDAR CARD ══ -->
    <div class="cal-card">
        <div class="cal-card-header">
            <i class="fa fa-calendar" style="color:#0d9488;font-size:14px;"></i>
            ปฏิทินการจองรถยนต์
        </div>
        <div class="cal-card-body">
            <?= yii2fullcalendar::widget([
                'options' => [
                    'lang'             => 'th',
                    'eventBorderColor' => 'transparent',
                ],
                'events' => $events,
                'id'     => 'calendar',
            ]); ?>
        </div>
    </div>

</div>