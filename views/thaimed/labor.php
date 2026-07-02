<?php
use kartik\grid\GridView;
use yii\helpers\Html;
?>
<style>
    @import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&family=Noto+Sans+Thai:wght@400;500&display=swap');
    body, h2, th, td {
        font-family: 'Roboto', 'Noto Sans Thai', sans-serif;
        font-size: 14px;
        color: #333333;
    }
    .kv-grid-table th {
        font-weight: 600;
        font-size: 15px;
        background-color: #2196F3;
        color: white;
        white-space: nowrap; /* ไม่ตัดบรรทัด header */
    }
    .table-bordered { border: 1px solid #ddd; }
    .table td, .table th { padding: 8px 10px; }
    .btn-primary { font-size: 12px; background-color: #d671f5; border-color: #d671f5; }
    .btn-primary:hover { background-color: #ad55d6; border-color: #ad55d6; }
    .card-title { font-size: 24px; font-weight: 700; color: #007bff; }

    /* Search Card */
    .search-card {
        background: #f0f4ff;
        border: 1px solid #c5d3f0;
        border-radius: 8px;
        padding: 16px 20px;
        margin-bottom: 20px;
    }
    .search-card label {
        font-weight: 600;
        font-size: 13px;
        display: block;
        margin-bottom: 4px;
    }

    /* ทำให้ td ไม่ตัดบรรทัด */
    .table td {
        white-space: nowrap;
    }

    /* scrollbar สวย */
    .grid-scroll::-webkit-scrollbar { height: 8px; }
    .grid-scroll::-webkit-scrollbar-thumb {
        background: #2196F3;
        border-radius: 4px;
    }
    .grid-scroll::-webkit-scrollbar-track { background: #f1f1f1; }
</style>

<div class="col-md-12 mb-6">
    <div class="card-body">
        <div class="well">

            <h2 class="card-title text-primary mb-3">ทับหม้อเกลือ-เยี่ยมหลังคลอด</h2>

            <!-- ===== FORM ค้นหา ===== -->
            <div class="search-card">
                <!-- ✅ action="index.php" + hidden r=thaimed/labor -->
                <form method="get" action="index.php" class="form-inline"
                      style="gap:12px; flex-wrap:wrap; align-items:flex-end; display:flex;">

                    <input type="hidden" name="r" value="thaimed/labor">

                    <div>
                        <label>📅 วันเกิดบุตร ตั้งแต่</label>
                        <input type="date" name="date_start"
                               class="form-control"
                               value="<?= Html::encode($dateStart) ?>">
                    </div>

                    <div>
                        <label>📅 ถึงวันที่</label>
                        <input type="date" name="date_end"
                               class="form-control"
                               value="<?= Html::encode($dateEnd) ?>">
                    </div>

                    <div>
                        <label>👤 Provider</label>
                        <select name="provider" class="form-control">
                            <?php foreach ($providerList as $val => $label): ?>
                                <option value="<?= Html::encode($val) ?>"
                                    <?= ($providerSelected === $val) ? 'selected' : '' ?>>
                                    <?= Html::encode($label) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div style="display:flex; gap:8px; margin-top:20px;">
                        <button type="submit" class="btn btn-info">
                            🔍 ค้นหา
                        </button>
                        <a href="index.php?r=thaimed/labor" class="btn btn-secondary">
                            ↩ ล้าง
                        </a>
                        <!-- Export CSV ส่ง filter ปัจจุบันไปด้วย -->
                        
<div style="font-size:12px; color:#888; margin-bottom:10px;">
    📊 เข้าใช้งานทั้งหมด <strong style="color:#e53935;"><?= number_format($totalCount) ?></strong> ครั้ง
    &nbsp;|&nbsp; 🕐 เริ่มนับตั้งแต่ระบบเปิดใช้งาน
</div>
                    </div>
                </form>

                <!-- สรุปจำนวน -->
                <div style="margin-top:10px; font-size:13px; color:#555;">
                    พบข้อมูลทั้งหมด
                    <strong style="color:#e53935; font-size:16px;"><?= $amount ?></strong> ราย
                    &nbsp;|&nbsp; วันเกิดบุตร:
                    <strong><?= Html::encode($dateStart) ?></strong>
                    ถึง
                    <strong><?= Html::encode($dateEnd) ?></strong>
                    <?php if (!empty($providerSelected)): ?>
                        &nbsp;|&nbsp; Provider: <strong style="color:#1565c0;"><?= Html::encode($providerSelected) ?></strong>
                    <?php endif; ?>
                </div>
            </div>
            <!-- ===== END FORM ===== -->

            <!-- ✅ ห่อ GridView ด้วย div เลื่อนแนวนอน -->
            <div class="grid-scroll" style="overflow-x: auto; width: 100%;">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'layout'       => "{items}{pager}",
                    'tableOptions' => [
                        'class' => 'table table-bordered table-hover table-striped',
                        'style' => 'min-width: 1900px; white-space: nowrap;', // ✅ กำหนดความกว้างตาราง
                    ],
                    'panel' => [
                        'before' => 'รายงานทับหม้อเกลือ - เยี่ยมหลังคลอด'
                    ],
                    'columns' => [
                        ['class' => 'kartik\grid\SerialColumn'],

                        [
                            'attribute'      => 'provider',
                            'label'          => 'Provider',
                            'contentOptions' => ['class' => 'text-left', 'style' => 'color:green;'],
                            'headerOptions'  => ['style' => 'background-color:#2196F3;color:white;'],
                        ],
                        [
                            'attribute'      => 'HN',
                            'contentOptions' => ['class' => 'text-left'],
                            'headerOptions'  => ['style' => 'background-color:#2196F3;color:white;'],
                        ],
                        [
                            'attribute'      => 'ข้อมูลมารดาหลังคลอด',
                            'contentOptions' => ['class' => 'text-left'],
                            'headerOptions'  => ['style' => 'background-color:#2196F3;color:white;'],
                        ],
                        [
                            'attribute'      => 'age',
                            'label'          => 'อายุ มารดา',
                            'contentOptions' => ['class' => 'text-center'],
                            'headerOptions'  => ['style' => 'background-color:#2196F3;color:white;'],
                        ],
                        [
                            'attribute'      => 'เบอร์โทรศัพท์มารดา',
                            'contentOptions' => ['class' => 'text-left'],
                            'headerOptions'  => ['style' => 'background-color:#2196F3;color:white;'],
                        ],
                        [
                            'attribute'      => 'HN บุตร',
                            'contentOptions' => ['class' => 'text-left'],
                            'headerOptions'  => ['style' => 'background-color:#2196F3;color:white;'],
                        ],
                        [
                            'attribute'      => 'ชื่อ-สกุล บุตร',
                            'contentOptions' => ['class' => 'text-left'],
                            'headerOptions'  => ['style' => 'background-color:#2196F3;color:white;'],
                        ],
                        [
                            'attribute'      => 'birthdate',
                            'format'         => ['date', 'php:d/m/Y'],
                            'label'          => 'วันเกิดบุตร',
                            'contentOptions' => ['class' => 'text-center'],
                            'headerOptions'  => ['style' => 'background-color:#2196F3;color:white;'],
                        ],
                        [
                            'attribute'      => 'จำนวนครั้ง',
                            'headerOptions'  => ['style' => 'background-color:#2196F3;color:white;'],
                            'format'         => 'raw',
                            'value'          => function ($model) {
                                return '<button class="btn btn-primary" style="background-color:#d671f5;color:white;font-size:12px;">'
                                    . $model['จำนวนครั้ง'] . '</button>';
                            },
                            'contentOptions' => ['class' => 'text-center'],
                        ],
                        [
                            'attribute'      => 'months',
                            'label'          => 'อายุ (เดือน)',
                            'headerOptions'  => ['style' => 'background-color:#2196F3;color:white;'],
                            'format'         => 'raw',
                            'value'          => function ($model) {
                                return '<button class="btn btn-primary" style="background-color:#36a6a8;color:white;font-size:12px;">'
                                    . $model['months'] . '</button>';
                            },
                            'contentOptions' => ['class' => 'text-center'],
                        ],
                        [
                            'attribute'      => 'days',
                            'label'          => 'อายุ (วัน)',
                            'headerOptions'  => ['style' => 'background-color:#2196F3;color:white;'],
                            'format'         => 'raw',
                            'value'          => function ($model) {
                                return '<button class="btn btn-primary" style="background-color:#1fa8ed;color:white;font-size:12px;">'
                                    . $model['days'] . '</button>';
                            },
                            'contentOptions' => ['class' => 'text-center'],
                        ],
                        [
                            'attribute'      => 'บ้านเลขที่',
                            'contentOptions' => ['class' => 'text-left'],
                            'headerOptions'  => ['style' => 'background-color:#2196F3;color:white;'],
                        ],
                        [
                            'attribute'      => 'บ้าน-หมู่ที่',
                            'contentOptions' => ['class' => 'text-left'],
                            'headerOptions'  => ['style' => 'background-color:#2196F3;color:white;'],
                        ],
                        [
                            'attribute'      => 'ตำบล',
                            'contentOptions' => ['class' => 'text-left'],
                            'headerOptions'  => ['style' => 'background-color:#2196F3;color:white;'],
                        ],
                        [
                            'attribute'      => 'อำเภอ',
                            'contentOptions' => ['class' => 'text-left'],
                            'headerOptions'  => ['style' => 'background-color:#2196F3;color:white;'],
                        ],
                        [
                            'attribute'      => 'จังหวัด',
                            'contentOptions' => ['class' => 'text-left'],
                            'headerOptions'  => ['style' => 'background-color:#2196F3;color:white;'],
                        ],
                    ],
                ]) ?>
            </div>
            <!-- ===== END GridView ===== -->

            <div style="margin-top:15px;">
                <?= Html::a('⏪ กลับหน้าหลัก', 'index.php?r=thaimed/index', [
                    'class' => 'btn btn-custom',
                    'style' => 'font-size:1.1rem; background-color:skyblue; color:white;'
                ]) ?>
            </div>

        </div>
    </div>
</div>