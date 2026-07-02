<?php

// ===== บันทึก access log =====
$logFile = Yii::getAlias('@runtime/ipd_access_log.txt');
$username = Yii::$app->user->isGuest ? 'guest' : Yii::$app->user->identity->username;
file_put_contents(
    $logFile,
    date('Y-m-d H:i:s') . ' | ' . $username . PHP_EOL,
    FILE_APPEND | LOCK_EX
);

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\grid\GridView;
use yii\widgets\Pjax;

/* @var $this         yii\web\View                   */
/* @var $searchModel  app\models\IpdTrackingSearch   */
/* @var $dataProvider yii\data\ArrayDataProvider     */

$this->title = 'ติดตามผู้ป่วย IPD';
?>
<style>
/* ===== Header Gradient ===== */
.ipd-header {
    background: linear-gradient(135deg, #1a6fc4 0%, #16a085 100%);
    border-radius: 12px;
    padding: 20px 28px;
    margin-bottom: 20px;
    color: #fff;
    display: flex;
    align-items: center;
    gap: 14px;
}
.ipd-header h1 {
    margin: 0;
    font-size: 22px;
    font-weight: 600;
    color: #fff;
    letter-spacing: 0.5px;
}
.ipd-header p {
    margin: 4px 0 0;
    font-size: 13px;
    opacity: 0.85;
}
.ipd-header-icon {
    width: 48px; height: 48px;
    background: rgba(255,255,255,0.2);
    border-radius: 50%;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    flex-shrink: 0;
}

/* ===== Search Card ===== */
.ipd-search-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 20px 24px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.ipd-search-card .card-title {
    font-size: 14px;
    font-weight: 600;
    color: #4a5568;
    margin-bottom: 16px;
    padding-bottom: 10px;
    border-bottom: 2px solid #ebf4ff;
    display: flex; align-items: center; gap: 8px;
}
.btn-search {
    background: linear-gradient(135deg, #1a6fc4, #2196f3);
    color: #fff !important;
    border: none;
    border-radius: 8px;
    padding: 8px 22px;
    font-weight: 500;
    box-shadow: 0 3px 8px rgba(33,150,243,0.35);
    transition: opacity .2s;
}
.btn-search:hover { opacity: .88; }
.btn-reset {
    background: #f1f5f9;
    color: #64748b !important;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 8px 18px;
}

/* ===== Print Button ===== */
.btn-print {
    background: #1a6fc4;
    color: #fff;
    border: none;
    border-radius: 8px;
    padding: 8px 20px;
    font-size: 13px;
    cursor: pointer;
    transition: opacity .2s;
}
.btn-print:hover { opacity: .85; }

/* ===== Grid Wrapper ===== */
.ipd-grid-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
}
.ipd-grid-card .grid-title {
    background: linear-gradient(90deg, #f0f7ff 0%, #e8f5f0 100%);
    padding: 12px 20px;
    font-size: 13px;
    font-weight: 600;
    color: #2d6a9f;
    border-bottom: 1px solid #dbeafe;
    display: flex; align-items: center; gap: 8px;
}

/* ===== Table ===== */
.ipd-grid-card .table thead th {
    background: linear-gradient(135deg, #dbeafe 0%, #e0f2fe 100%);
    color: #1e40af;
    font-size: 12px;
    font-weight: 600;
    padding: 10px 10px;
    white-space: nowrap;
    border-color: #bfdbfe;
}
.ipd-grid-card .table tbody tr:nth-child(even) {
    background: #f8fbff;
}
.ipd-grid-card .table tbody tr:hover {
    background: #eff6ff;
    transition: background .15s;
}

/* ===== Ward Badge ===== */
.ward-badge {
    display: inline-block;
    padding: 4px 10px;
    border-radius: 20px;
    font-size: 11.5px;
    font-weight: 600;
    white-space: nowrap;
}
.ward-IPD1    { background: linear-gradient(135deg,#667eea,#764ba2); color:#fff; }
.ward-IPD2    { background: linear-gradient(135deg,#f857a6,#ff5858); color:#fff; }
.ward-LR      { background: linear-gradient(135deg,#11998e,#38ef7d); color:#fff; }
.ward-HomeWard{ background: linear-gradient(135deg,#f7971e,#ffd200); color:#6b4700; }
.ward-Ward5ER { background: linear-gradient(135deg,#e53935,#b71c1c); color:#fff; }
.ward-Ward4   { background: linear-gradient(135deg,#1976d2,#0288d1); color:#fff; }
.ward-default { background: #e2e8f0; color:#4a5568; }

/* ===== Status Badge ===== */
.badge-ipd {
    display: inline-block;
    padding: 3px 9px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: 600;
}
.badge-success  { background:#d1fae5; color:#065f46; }
.badge-info     { background:#dbeafe; color:#1e40af; }
.badge-warning  { background:#fef3c7; color:#92400e; }
.badge-dark     { background:#1f2937; color:#f9fafb; }
.badge-secondary{ background:#e5e7eb; color:#374151; }

/* ===== Print Header (ซ่อนบนหน้าจอ) ===== */
.print-header { display: none; }

/* ===== Print Styles ===== */
@media print {

    /* ซ่อนส่วนที่ไม่ต้องการ */
    .ipd-header,
    .ipd-search-card,
    .ipd-grid-card .grid-title,
    .btn-print,
    nav, .navbar, .sidebar, footer,
    .pagination { display: none !important; }

    /* Reset layout */
    body { font-size: 13px !important; margin: 0; }
    .ipd-tracking-index { margin: 0; padding: 0; }

    .ipd-grid-card {
        border: none !important;
        box-shadow: none !important;
        border-radius: 0 !important;
        overflow: visible !important;
    }

    /* ===== Table พอดีหน้า A4 landscape ===== */
    .table-responsive { overflow: visible !important; }

    .table {
        width: 100% !important;
        table-layout: fixed !important;
        font-size: 13px !important;
        border-collapse: collapse !important;
    }

    .table thead th {
        background: #dbeafe !important;
        color: #1e40af !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
        font-size: 13px !important;
        font-weight: 700 !important;
        padding: 5px 4px !important;
        border: 0.5px solid #bfdbfe !important;
        white-space: nowrap !important;
        word-break: normal;
        line-height: 1.3;
        vertical-align: middle !important;
    }

    .table tbody td {
        padding: 4px 4px !important;
        border: 0.5px solid #e2e8f0 !important;
        font-size: 13px !important;
        vertical-align: middle !important;   /* ← กึ่งกลางแนวตั้ง */
        line-height: 1.4;
        /* ไม่ word-break เพื่อให้ข้อมูลสั้นไม่ตัดบรรทัด */
        overflow: hidden;
    }

    /* ซ่อน Serial column (#) ตอนพิมพ์ */
    .table th:first-child,
    .table td:first-child { display: none !important; }

    /* ===== แถวสลับสี ===== */
    .table tbody tr:nth-child(even) {
        background: #f0f7ff !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .table tbody tr:nth-child(odd) {
        background: #ffffff !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }

    /* Ward Badge */
    .ward-badge {
        font-size: 11px !important;
        padding: 2px 6px !important;
        border-radius: 8px !important;
        white-space: nowrap !important;
        display: inline-block !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .ward-IPD1    { background: #667eea !important; color:#fff !important; }
    .ward-IPD2    { background: #f857a6 !important; color:#fff !important; }
    .ward-LR      { background: #11998e !important; color:#fff !important; }
    .ward-HomeWard{ background: #f7971e !important; color:#6b4700 !important; }
    .ward-Ward5ER { background: #e53935 !important; color:#fff !important; }
    .ward-Ward4   { background: #1976d2 !important; color:#fff !important; }
    .ward-default { background: #e2e8f0 !important; color:#4a5568 !important; }

    /* Status Badge */
    .badge-ipd {
        font-size: 11px !important;
        padding: 2px 6px !important;
        border-radius: 8px !important;
        white-space: nowrap !important;
        display: inline-block !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .badge-success  { background:#d1fae5 !important; color:#065f46 !important; }
    .badge-info     { background:#dbeafe !important; color:#1e40af !important; }
    .badge-warning  { background:#fef3c7 !important; color:#92400e !important; }
    .badge-dark     { background:#1f2937 !important; color:#f9fafb !important; }
    .badge-secondary{ background:#e5e7eb !important; color:#374151 !important; }

    /*
     * ความกว้างคอลัมน์ (รวม 100%) — A4 landscape
     * col ที่แสดงจริงเริ่มที่ nth-child(2) เพราะซ่อน Serial ไว้
     *
     *  (2) หอผู้ป่วย  (3) HN  (4) AN  (5) วันAdmit  (6) วันจำหน่าย
     *  (7) ชื่อ-สกุล  (8) วินิจฉัย  (9) เบอร์โทร  (10) สิทธิ์
     *  (11) ผู้ดูแล  (12) สถานะจำหน่าย  (13) ประเภทจำหน่าย
     */
    .table th:nth-child(2)  { width: 6%;   }  /* หอผู้ป่วย */
    .table th:nth-child(3)  { width: 5%;   }  /* HN */
    .table th:nth-child(4)  { width: 5%;   }  /* AN */
    .table th:nth-child(5)  { width: 10%;  }  /* วัน Admit */
    .table th:nth-child(6)  { width: 10%;  }  /* วันจำหน่าย */
    .table th:nth-child(7)  { width: 13%;  }  /* ชื่อ-สกุล / อายุ */
    .table th:nth-child(8)  { width: 13%;  }  /* วินิจฉัย */
    .table th:nth-child(9)  { width: 8%;   }  /* เบอร์โทร ← พอดี 10 หลัก */
    .table th:nth-child(10) { width: 7%;   }  /* สิทธิ์ */
    .table th:nth-child(11) { width: 11%;  }  /* ผู้ดูแล */
    .table th:nth-child(12) { width: 6%;   }  /* สถานะจำหน่าย ← พอดีตัวอักษร */
    .table th:nth-child(13) { width: 6%;   }  /* ประเภทจำหน่าย */

    /* td ตามคอลัมน์ที่ควร nowrap เพื่อไม่ให้ตัดบรรทัด */
    .table td:nth-child(2),   /* ward */
    .table td:nth-child(3),   /* HN */
    .table td:nth-child(4),   /* AN */
    .table td:nth-child(12),  /* สถานะ */
    .table td:nth-child(13) { /* ประเภท */
        white-space: nowrap !important;
    }

    /* เบอร์โทร — แสดงในบรรทัดเดียว ตัดด้วย ellipsis ถ้าเกิน */
    .table td:nth-child(9) {
        white-space: nowrap !important;
        overflow: hidden !important;
        text-overflow: ellipsis !important;
    }

    /* Print Header */
    .print-header {
        display: block !important;
        text-align: center;
        margin-bottom: 10px;
        border-bottom: 1.5px solid #1a6fc4;
        padding-bottom: 6px;
    }
    .print-header h2 {
        font-size: 16px;
        font-weight: 700;
        margin: 0;
        color: #1a3a5c;
    }
    .print-header p {
        font-size: 11px;
        color: #555;
        margin: 2px 0 0;
    }

    @page {
        size: A4 landscape;
        margin: 6mm 5mm;
    }
}
</style>
<div class="ipd-tracking-index">
    <!-- Header (หน้าจอเท่านั้น) -->
    <div class="ipd-header">
        <div class="ipd-header-icon">&#128203;</div>
        <div>
            <h1>ติดตามผู้ป่วย IPD</h1>
            <p>ระบบติดตามการรับ-จำหน่ายผู้ป่วยใน | IPD Tracking System</p>
            <p style="font-size:12px; opacity:0.8; margin:4px 0 0;">
                &#128202; เข้าใช้งานทั้งหมด: 
                <?php
                    $count = file_exists($logFile)
                        ? count(file($logFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES))
                        : 0;
                    echo $count;
                ?> ครั้ง
            </p>
        </div>
    </div>

    <!-- Search Card (หน้าจอเท่านั้น) -->
    <div class="ipd-search-card">
        <div class="card-title">&#128269; ค้นหาข้อมูล</div>

        <?php $form = ActiveForm::begin([
            'method' => 'get',
            'action' => ['ipd-tracking/index'],
            'id'     => 'search-form',
        ]); ?>

        <div class="row g-3 align-items-end">

            <div class="col-md-3">
                <?= $form->field($searchModel, 'adm_id', [
                    'template' => '<label class="form-label fw-semibold" style="font-size:13px;">{label}</label>{input}{error}',
                ])->textInput([
                    'placeholder' => 'กรอก AN เพื่อค้นหา',
                    'class'       => 'form-control',
                    'style'       => 'border-radius:8px; font-size:13px;',
                ])->label('&#128275; AN (ADM_ID)') ?>
            </div>

            <div class="col-md-2">
                <?= $form->field($searchModel, 'date_from', [
                    'template' => '<label class="form-label fw-semibold" style="font-size:13px;">{label}</label>{input}{error}',
                ])->input('date', [
                    'value' => $searchModel->date_from ?: date('Y-m-d'),
                    'class' => 'form-control',
                    'style' => 'border-radius:8px; font-size:13px;',
                ])->label('&#128197; วันจำหน่าย (เริ่ม)') ?>
            </div>

            <div class="col-md-2">
                <?= $form->field($searchModel, 'date_to', [
                    'template' => '<label class="form-label fw-semibold" style="font-size:13px;">{label}</label>{input}{error}',
                ])->input('date', [
                    'value' => $searchModel->date_to ?: date('Y-m-d'),
                    'class' => 'form-control',
                    'style' => 'border-radius:8px; font-size:13px;',
                ])->label('&#128197; วันจำหน่าย (สิ้นสุด)') ?>
            </div>

            <div class="col-md-3 d-flex gap-2 pt-2">
                <?= Html::submitButton('&#128269; ค้นหา', ['class' => 'btn btn-search']) ?>
                <?= Html::a('&#8635; ล้าง', ['ipd-tracking/index'], ['class' => 'btn btn-reset']) ?>
            </div>

        </div>

        <?php ActiveForm::end(); ?>
    </div>

    <!-- Print Header (แสดงเฉพาะตอนพิมพ์) -->
    <div class="print-header">
        <h2>รายงานติดตามผู้ป่วย IPD</h2>
        <p>
            วันที่พิมพ์: <?= date('d/m/Y H:i') ?>
            <?php if ($searchModel->date_from || $searchModel->date_to): ?>
                &nbsp;|&nbsp; ช่วงวันที่จำหน่าย: <?= $searchModel->date_from ?> ถึง <?= $searchModel->date_to ?>
            <?php endif; ?>
        </p>
    </div>

    <!-- ปุ่มพิมพ์ (หน้าจอเท่านั้น) -->
    <div style="text-align:right; margin-bottom:10px;">
        <button class="btn-print" onclick="window.print()">
            &#128438; พิมพ์รายงาน
        </button>
		
    </div>

    <!-- Grid Card -->
    <div class="ipd-grid-card">
        <div class="grid-title">&#128203; รายการผู้ป่วย IPD</div>

        <?php Pjax::begin(['id' => 'ipd-pjax']); ?>

        <?= GridView::widget([
            'dataProvider' => $dataProvider,
            'tableOptions' => ['class' => 'table table-bordered mb-0'],
            'options'      => ['class' => 'table-responsive'],
            'columns'      => [

                /* ---- # Serial ---- */
                [
                    'class'          => 'yii\grid\SerialColumn',
                    'headerOptions'  => ['style' => 'width:45px; text-align:center;'],
                    'contentOptions' => ['style' => 'text-align:center; color:#94a3b8; font-size:12px;'],
                ],

                /* ---- หอผู้ป่วย ---- */
                [
                    'attribute'     => 'ward_name',
                    'label'         => 'หอผู้ป่วย',
                    'enableSorting' => false,
                    'format'        => 'raw',
                    'value'         => function ($row) {
                        $ward   = $row['ward_name'];
                        $cssMap = [
                            'IPD1'     => 'ward-IPD1',
                            'IPD2'     => 'ward-IPD2',
                            'LR'       => 'ward-LR',
                            'HomeWard' => 'ward-HomeWard',
                            'Ward5ER'  => 'ward-Ward5ER',
                            'Ward4'    => 'ward-Ward4',
                        ];
                        $css = isset($cssMap[$ward]) ? $cssMap[$ward] : 'ward-default';
                        return '<span class="ward-badge ' . $css . '">' . htmlspecialchars($ward) . '</span>';
                    },
                    'headerOptions'  => ['style' => 'text-align:center;'],
                    'contentOptions' => ['style' => 'text-align:center;'],
                ],

                /* ---- HN ---- */
                [
                    'attribute'      => 'hn',
                    'label'          => 'HN',
                    'enableSorting'  => false,
                    'contentOptions' => ['style' => 'font-weight:600; color:#1e40af; font-size:16.5px;'],
                ],

                /* ---- AN ---- */
                [
                    'attribute'      => 'adm_id',
                    'label'          => 'AN',
                    'enableSorting'  => false,
                    'contentOptions' => ['style' => 'font-weight:600; color:#6d28d9; font-size:15.5px;'],
                ],

                /* ---- วัน Admit ---- */
                [
                    'attribute'      => 'adm_dt',
                    'label'          => 'วัน Admit',
                    'enableSorting'  => false,
                    'format'         => 'datetime',
                    'contentOptions' => ['style' => 'white-space:nowrap; font-size:14px;'],
                ],
			   

                /* ---- ชื่อ-สกุล / อายุ ---- */
                [
                    'attribute'      => 'patient_name',
                    'label'          => 'ชื่อ-สกุล / อายุ',
                    'enableSorting'  => false,
                    'contentOptions' => ['style' => 'min-width:160px;'],
                ],

                /* ---- วินิจฉัยโรคหลัก ---- */
                [
                    'attribute'      => 'diagnosis',
                    'label'          => 'วินิจฉัยโรคหลัก',
                    'enableSorting'  => false,
                    'contentOptions' => ['style' => 'color:#047857; font-size:14px; min-width:120px;'],
                ],

                /* ---- เบอร์โทร (รวมคนไข้ + ญาติ) ---- */
                [
                    'label'  => 'เบอร์โทร',
                    'format' => 'raw',
                    'value'  => function ($row) {
                        $parts = [];
                        if (!empty($row['patient_tel']))
                            $parts[] = '<span style="color:#64748b;font-size:14px;">คนไข้:</span> ' . htmlspecialchars($row['patient_tel']);
                        if (!empty($row['relative_tel']))
                            $parts[] = '<span style="color:#64748b;font-size:14px;">ญาติ:</span> ' . htmlspecialchars($row['relative_tel']);
                        return implode('<br>', $parts);
                    },
                    'contentOptions' => ['style' => 'font-size:16px; white-space:nowrap;'],
                ],

                /* ---- สิทธิ์การรักษา ---- */
                [
                    'attribute'      => 'inscl_name',
                    'label'          => 'สิทธิ์การรักษา',
                    'enableSorting'  => false,
                    'contentOptions' => ['style' => 'font-size:16px; color:#4b5563;'],
                ],

                /* ---- ผู้ดูแล ---- */
                [
                    'label'  => 'ผู้ดูแล',
                    'value'  => function ($row) {
                        return implode(' ', array_filter([
                            $row['caretaker_name'],
                            $row['caretaker_relation'] ? '(' . $row['caretaker_relation'] . ')' : '',
                            $row['caretaker_tel'],
                        ]));
                    },
                    'contentOptions' => ['style' => 'font-size:16px; min-width:140px;'],
                ],

                /* ---- สถานะจำหน่าย ---- */
                [
                    'attribute'     => 'dsc_status_text',
                    'label'         => 'สถานะจำหน่าย',
                    'enableSorting' => false,
                    'format'        => 'raw',
                    'value'         => function ($row) {
                        $status = $row['dsc_status_text'];
                        $map = [
                            'Complete Recovery' => 'badge-success',
                            'Improved'          => 'badge-info',
                            'Transfer'          => 'badge-warning',
                            'Dead'              => 'badge-dark',
                        ];
                        $css = isset($map[$status]) ? $map[$status] : 'badge-secondary';
                        return '<span class="badge-ipd ' . $css . '">' . htmlspecialchars($status) . '</span>';
                    },
                    'headerOptions'  => ['style' => 'text-align:center;'],
                    'contentOptions' => ['style' => 'text-align:center; white-space:nowrap;'],
                ],

                /* ---- ประเภทจำหน่าย ---- */
                [
                    'attribute'      => 'dsc_type_text',
                    'label'          => 'ประเภทจำหน่าย',
                    'enableSorting'  => false,
                    'contentOptions' => ['style' => 'font-size:16px; color:#4b5563;'],
                ],

            ],
        ]); ?>

        <?php Pjax::end(); ?>
    </div>

</div>