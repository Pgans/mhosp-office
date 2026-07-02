<?php
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel app\models\RentalSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'การจองพาหนะ';
$this->params['breadcrumbs'][] = $this->title;

// ลงทะเบียน CSS inline
$css = <<<CSS
/* ==============================
   RENTAL INDEX — Clean Corporate
   ============================== */

/* Google Font */
@import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Prompt:wght@400;500;600;700&display=swap');

body, .content-wrapper {
    background: #f0f4f8 !important;
    font-family: 'Sarabun', sans-serif !important;
}

/* ── Hero Header Card ── */
.rental-hero {
    background: linear-gradient(135deg, #1a3a5c 0%, #0e6ebd 60%, #38b2f7 100%);
    border-radius: 18px;
    padding: 32px 36px 28px;
    margin-bottom: 24px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(14, 110, 189, 0.28);
}
.rental-hero::before {
    content: '';
    position: absolute;
    top: -60px; right: -60px;
    width: 220px; height: 220px;
    background: rgba(255,255,255,0.07);
    border-radius: 50%;
}
.rental-hero::after {
    content: '';
    position: absolute;
    bottom: -40px; left: 30%;
    width: 160px; height: 160px;
    background: rgba(255,255,255,0.05);
    border-radius: 50%;
}
.rental-hero .hero-icon {
    font-size: 44px;
    color: rgba(255,255,255,0.85);
    margin-bottom: 8px;
}
.rental-hero h2 {
    font-family: 'Prompt', sans-serif;
    font-size: 24px;
    font-weight: 600;
    color: #fff;
    margin: 0 0 4px;
    letter-spacing: 0.3px;
}
.rental-hero .hero-sub {
    font-size: 13px;
    color: rgba(255,255,255,0.72);
    margin: 0;
}
.rental-hero .hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    background: rgba(255,255,255,0.18);
    backdrop-filter: blur(6px);
    border: 1px solid rgba(255,255,255,0.25);
    color: #fff;
    border-radius: 30px;
    padding: 5px 14px;
    font-size: 12.5px;
    margin-top: 12px;
}

/* ── Action Bar ── */
.rental-action-bar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 18px;
    flex-wrap: wrap;
    gap: 10px;
}
.btn-book {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    background: linear-gradient(135deg, #16a34a, #15803d);
    color: #fff !important;
    border: none;
    border-radius: 10px;
    padding: 10px 22px;
    font-family: 'Sarabun', sans-serif;
    font-size: 15px;
    font-weight: 600;
    text-decoration: none !important;
    box-shadow: 0 4px 14px rgba(22,163,74,0.35);
    transition: all 0.22s ease;
    cursor: pointer;
}
.btn-book:hover {
    transform: translateY(-2px);
    box-shadow: 0 7px 20px rgba(22,163,74,0.42);
    background: linear-gradient(135deg, #15803d, #166534);
}
.btn-book i { font-size: 16px; }

.rental-count-pill {
    background: #fff;
    border: 1.5px solid #e2e8f0;
    border-radius: 30px;
    padding: 6px 16px;
    font-size: 13px;
    color: #64748b;
    display: flex;
    align-items: center;
    gap: 6px;
}
.rental-count-pill strong { color: #0e6ebd; }

/* ── Grid Card ── */
.rental-grid-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.07);
    overflow: hidden;
    border: 1px solid #e8edf3;
}
.rental-grid-card .grid-card-header {
    background: #f8fafc;
    border-bottom: 1px solid #e8edf3;
    padding: 14px 20px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 13px;
    color: #64748b;
    font-weight: 500;
}
.rental-grid-card .grid-card-body {
    padding: 0;
}

/* ── Table override ── */
.rental-grid-card .table {
    margin: 0;
    font-size: 13.5px;
    font-family: 'Sarabun', sans-serif;
}
.rental-grid-card .table > thead > tr > th {
    background: #f1f5f9;
    color: #374151;
    font-family: 'Prompt', sans-serif;
    font-weight: 600;
    font-size: 12.5px;
    letter-spacing: 0.4px;
    text-transform: uppercase;
    border-bottom: 2px solid #e2e8f0;
    padding: 12px 14px;
    white-space: nowrap;
}
.rental-grid-card .table > tbody > tr > td {
    padding: 12px 14px;
    vertical-align: middle;
    border-bottom: 1px solid #f1f5f9;
    color: #374151;
}
.rental-grid-card .table > tbody > tr:hover > td {
    background: #f8fbff;
}
.rental-grid-card .table > tbody > tr:last-child > td {
    border-bottom: none;
}

/* ── Status Badges ── */
.status-badge {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    border-radius: 20px;
    padding: 4px 12px;
    font-size: 12px;
    font-weight: 600;
    white-space: nowrap;
}
.status-pending   { background:#fef9c3; color:#854d0e; border:1px solid #fde68a; }
.status-approved  { background:#dcfce7; color:#166534; border:1px solid #86efac; }
.status-rejected  { background:#fee2e2; color:#991b1b; border:1px solid #fca5a5; }
.status-completed { background:#dbeafe; color:#1e40af; border:1px solid #93c5fd; }
.status-cancelled { background:#f3f4f6; color:#4b5563; border:1px solid #d1d5db; }

/* ── View Action Button ── */
.btn-view-rental {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    background: #eff6ff;
    color: #2563eb !important;
    border: 1px solid #bfdbfe;
    border-radius: 8px;
    padding: 5px 12px;
    font-size: 12.5px;
    font-weight: 500;
    text-decoration: none !important;
    transition: all 0.18s ease;
}
.btn-view-rental:hover {
    background: #2563eb;
    color: #fff !important;
    border-color: #2563eb;
}

/* ── Pagination & Filter ── */
.kv-grid-container .pagination > li > a,
.kv-grid-container .pagination > li > span {
    border-radius: 8px !important;
    margin: 0 2px;
    color: #0e6ebd;
    border-color: #dbeafe;
    font-family: 'Sarabun', sans-serif;
}
.kv-grid-container .pagination > .active > a {
    background: #0e6ebd !important;
    border-color: #0e6ebd !important;
    color: #fff !important;
}
.kv-grid-container .summary {
    font-size: 12.5px;
    color: #94a3b8;
    font-family: 'Sarabun', sans-serif;
}

/* Filter inputs */
.kv-grid-container .filters input,
.kv-grid-container .filters select {
    border-radius: 7px;
    border: 1px solid #e2e8f0;
    font-size: 12.5px;
    font-family: 'Sarabun', sans-serif;
    padding: 5px 8px;
    background: #f8fafc;
    transition: border-color 0.18s;
}
.kv-grid-container .filters input:focus,
.kv-grid-container .filters select:focus {
    border-color: #0e6ebd;
    background: #fff;
    outline: none;
    box-shadow: 0 0 0 3px rgba(14,110,189,0.12);
}

/* ── Destination Cell ── */
.dest-cell {
    font-weight: 500;
    color: #1e293b;
}
.dest-cell .dest-sub {
    font-size: 11.5px;
    color: #94a3b8;
    margin-top: 1px;
}

/* ── Passenger pill ── */
.passenger-pill {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    background: #f0fdf4;
    color: #15803d;
    border: 1px solid #bbf7d0;
    border-radius: 20px;
    padding: 3px 10px;
    font-size: 12.5px;
    font-weight: 600;
}

/* ── Responsive ── */
@media (max-width: 768px) {
    .rental-hero { padding: 20px; }
    .rental-hero h2 { font-size: 18px; }
    .rental-action-bar { flex-direction: column; align-items: flex-start; }
}
CSS;
$this->registerCss($css);
?>

<div class="rental-index" style="padding: 10px 0;">

    <!-- ══ HERO HEADER ══ -->
    <div class="rental-hero">
        <div class="hero-icon"><i class="fa fa-car"></i></div>
        <h2><?= Html::encode($this->title) ?></h2>
        <p class="hero-sub">บริหารจัดการการจองพาหนะของหน่วยงาน</p>
        <div class="hero-badge">
            <i class="fa fa-user-circle-o"></i>
            ผู้ใช้งาน: <strong><?= Html::encode(Yii::$app->user->identity->username) ?></strong>
        </div>
    </div>

    <!-- ══ ACTION BAR ══ -->
    <div class="rental-action-bar">
        <?= Html::a(
            '<i class="fa fa-plus-circle"></i> จองพาหนะใหม่',
            ['create'],
            ['class' => 'btn-book']
        ) ?>

        <div class="rental-count-pill">
            <i class="fa fa-list-ul" style="color:#0e6ebd;"></i>
            รายการทั้งหมด: <strong><?= $dataProvider->totalCount ?></strong> รายการ
        </div>
    </div>

    <!-- ══ GRID CARD ══ -->
    <div class="rental-grid-card">
        <div class="grid-card-header">
            <i class="fa fa-table" style="color:#0e6ebd;"></i>
            รายการจองพาหนะ
        </div>
        <div class="grid-card-body">

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel'  => $searchModel,
        'hover'        => true,
        'pjax'         => true,
        'tableOptions' => [
            'class' => 'table table-striped table-hover',
        ],
        'containerOptions' => ['style' => 'overflow:auto'],
        'panelTemplate'    => '{items}{footer}',   // ไม่ใช้ panel wrapper ของ kartik ใช้ card เราเอง
        'columns' => [

            /* ลำดับ */
            [
                'class' => 'kartik\grid\SerialColumn',
                'header' => '#',
                'headerOptions' => ['style' => 'width:45px; text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center; color:#94a3b8; font-size:12px;'],
            ],

            /* สถานะ */
            [
                'attribute' => 'status',
                'format'    => 'html',
                'headerOptions' => ['style' => 'width:130px;'],
                'value' => function ($model) {
                    $raw = Yii::$app->rentalStatus->getRentalStatus($model->status);
                    // map สถานะเป็น CSS class
                    $map = [
                        0 => ['class' => 'status-pending',   'icon' => 'fa-clock-o',       'label' => $raw],
                        1 => ['class' => 'status-approved',  'icon' => 'fa-check-circle',  'label' => $raw],
                        2 => ['class' => 'status-rejected',  'icon' => 'fa-times-circle',  'label' => $raw],
                        3 => ['class' => 'status-completed', 'icon' => 'fa-flag-checkered','label' => $raw],
                        4 => ['class' => 'status-cancelled', 'icon' => 'fa-ban',           'label' => $raw],
                    ];
                    $s = $map[$model->status] ?? ['class' => 'status-pending', 'icon' => 'fa-circle', 'label' => $raw];
                    return '<span class="status-badge '.$s['class'].'"><i class="fa '.$s['icon'].'"></i>'.$s['label'].'</span>';
                },
            ],

            /* ปลายทาง */
            [
                'attribute' => 'destination',
                'format'    => 'html',
                'value' => function ($model) {
                    $desc = \yii\helpers\StringHelper::truncate($model->description ?? '', 35, '…');
                    return '<div class="dest-cell">'
                        . \yii\helpers\Html::encode($model->destination)
                        . ($desc ? '<div class="dest-sub">'.\yii\helpers\Html::encode($desc).'</div>' : '')
                        . '</div>';
                },
            ],

            /* วันเริ่ม */
            [
                'attribute' => 'date_start',
                'format'    => 'html',
                'headerOptions' => ['style' => 'white-space:nowrap;'],
                'value' => function ($model) {
                    return '<span style="white-space:nowrap;"><i class="fa fa-calendar-o" style="color:#0e6ebd;margin-right:4px;"></i>'
                        . \yii\helpers\Html::encode($model->date_start) . '</span>';
                },
            ],

            /* วันสิ้นสุด */
            [
                'attribute' => 'date_end',
                'format'    => 'html',
                'headerOptions' => ['style' => 'white-space:nowrap;'],
                'value' => function ($model) {
                    return '<span style="white-space:nowrap;"><i class="fa fa-calendar-check-o" style="color:#16a34a;margin-right:4px;"></i>'
                        . \yii\helpers\Html::encode($model->date_end) . '</span>';
                },
            ],

            /* ผู้โดยสาร */
            [
                'attribute'     => 'passenger',
                'format'        => 'html',
                'headerOptions' => ['style' => 'width:90px; text-align:center;'],
                'contentOptions'=> ['style' => 'text-align:center;'],
                'value' => function ($model) {
                    return '<span class="passenger-pill"><i class="fa fa-users"></i>'.(int)$model->passenger.'</span>';
                },
            ],

            /* ชื่อผู้จอง */
            [
                'attribute' => 'user_id',
                'label'     => 'ผู้จอง',
                'format'    => 'html',
                'value' => function ($model) {
                    $name = \yii\helpers\Html::encode($model->user->firstname . ' ' . $model->user->lastname);
                    return '<span style="display:flex;align-items:center;gap:6px;">'
                        . '<span style="width:28px;height:28px;border-radius:50%;background:linear-gradient(135deg,#0e6ebd,#38b2f7);color:#fff;display:inline-flex;align-items:center;justify-content:center;font-size:11px;font-weight:700;flex-shrink:0;">'
                        . mb_substr($model->user->firstname, 0, 1)
                        . '</span>'
                        . $name
                        . '</span>';
                },
            ],

            /* หน่วยงาน */
            [
                'attribute'      => 'dep_id',
                'label'          => 'หน่วยงาน',
                'contentOptions' => ['style' => 'max-width:160px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;'],
                'value' => function ($model) {
                    return $model->departments->dep_name;
                },
            ],

            /* ปุ่มดูรายละเอียด */
            [
                'class'    => 'yii\grid\ActionColumn',
                'template' => '{view}',
                'header'   => '',
                'headerOptions' => ['style' => 'width:80px; text-align:center;'],
                'contentOptions' => ['style' => 'text-align:center;'],
                'buttons'  => [
                    'view' => function ($url, $model, $key) {
                        return \yii\helpers\Html::a(
                            '<i class="fa fa-eye"></i> ดู',
                            ['rental/view', 'id' => $model->id],
                            ['class' => 'btn-view-rental', 'title' => 'ดูรายละเอียด']
                        );
                    },
                ],
            ],

        ],
    ]); ?>

        </div><!-- /.grid-card-body -->
    </div><!-- /.rental-grid-card -->

</div><!-- /.rental-index -->