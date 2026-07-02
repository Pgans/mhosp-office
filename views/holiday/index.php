<?php
use yii\helpers\Html;
use yii\grid\GridView;

$this->title = 'วันหยุดราชการ ปี2568';
$this->params['breadcrumbs'][] = $this->title;
?>

<style>
/* ด้านซ้าย: สไตล์ปฏิทิน */
.holiday-box {
    background-color: #e8f5e9;
    border-left: 5px solid #66bb6a;
    border-radius: 12px;
    padding: 15px 20px;
    margin-bottom: 20px;
    box-shadow: 0 2px 8px rgba(0, 128, 0, 0.08);
    transition: 0.3s;
}
.holiday-box:hover {
    box-shadow: 0 4px 12px rgba(0, 128, 0, 0.15);
    transform: translateY(-2px);
}
.holiday-title {
    font-size: 18px;
    font-weight: bold;
    color: #2e7d32;
    margin-bottom: 10px;
}
.holiday-item {
    font-size: 14px;
    margin-bottom: 6px;
    color: #2e7d32;
}
.holiday-item i {
    color: #43a047;
    margin-right: 8px;
}

/* ด้านขวา: GridView สวยงาม */
.grid-view {
    border-radius: 12px;
    background: #e3f2fd;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
    overflow: hidden;
    font-size: 14px;
}
.grid-view table {
    margin-bottom: 0;
    width: 100%;
}
.grid-view thead tr {
    background-color: #bbdefb;
    color: #0d47a1;
}
.grid-view tbody tr:nth-child(odd) {
    background-color: #ffffff;
}
.grid-view tbody tr:nth-child(even) {
    background-color: #e1f5fe;
}
.grid-view tbody tr:hover {
    background-color: #b3e5fc;
}
.grid-view th,
.grid-view td {
    padding: 10px 12px;
    vertical-align: middle;
    border-top: 1px solid #ddd;
}

/* ส่วนกลางหน้าจอ */
.container-centered {
    max-width: 1100px;
    margin: auto;
}
</style>

<p class="text-center">
    <?= Html::a('🔄 โหลดข้อมูลใหม่', ['holiday/fetch'], ['class' => 'btn btn-success']) ?>
</p>

<div class="container-centered">
    <div class="row">
        <!-- ซ้าย: วันหยุดจาก Session -->
        <div class="col-md-6">
            <div class="box box-success">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-calendar-alt text-green"></i> วันหยุดจาก Session
                    </h3>
                </div>
                <div class="box-body">
                    <?php foreach ($groupedHolidays as $month => $holidays): ?>
                        <div class="holiday-box" style="background: #f1f8e9; border-radius: 12px; padding: 15px; margin-bottom: 20px; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                            <h4 style="color: #2e7d32;">
                                <i class="fa fa-calendar-week"></i>
                                <?= Html::encode($holidays[0]['month']) ?>
                            </h4>
                            <ul class="list-unstyled">
                                <?php foreach ($holidays as $holiday): ?>
                                    <li class="holiday-item" style="margin-bottom: 10px;">
                                        <i class="fa fa-calendar-check text-success"></i>
                                        <?= $holiday['weekday'] ?> <?= $holiday['date'] ?> -
                                        <strong><?= Html::encode($holiday['title']) ?></strong>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>


        <!-- ขวา: วันหยุดจากฐานข้อมูล -->
        <div class="col-md-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">
                        <i class="fa fa-database text-blue"></i> วันหยุดจากฐานข้อมูล
                    </h3>
                </div>
                <div class="box-body">
                    <?= GridView::widget([
                        'dataProvider' => $hdaysProvider,
                        'summary' => false,
                        'columns' => [
                            [
                                'attribute' => 'H_DATE',
                                'label' => '<i class="fa fa-calendar-day text-info"></i> วันที่',
                                'format' => ['date', 'php:d/m/Y'],
                                'encodeLabel' => false,
                            ],
                            [
                                'attribute' => 'H_NAME',
                                'label' => '<i class="fa fa-flag text-success"></i> ชื่อวันหยุด',
                                'encodeLabel' => false,
                            ],
                            [
                                'attribute' => 'reg_datetime',
                                'label' => '<i class="fa fa-clock text-warning"></i> บันทึกเมื่อ',
                                'format' => ['date', 'php:d/m/Y H:i:s'],
                                'encodeLabel' => false,
                            ],
                        ],
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
