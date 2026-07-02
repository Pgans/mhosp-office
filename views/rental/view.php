<?php

use yii\helpers\Html;
use yii\widgets\DetailView;
use app\models\User;

/* @var $this yii\web\View */
/* @var $model backend\modules\rentals\models\Rental */

$this->title = 'หมายเลขการจองที่ ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'การจองพาหนะ', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$isUserLoggedIn = !Yii::$app->user->isGuest;
if ($isUserLoggedIn) {
    $currentUser     = Yii::$app->user->identity;
    $currentUserId   = $currentUser->id;
    $currentUserName = $currentUser->username;
} else {
    $currentUserId   = 'Not Logged In';
    $currentUserName = 'Guest';
}

$allowedUserIds = [6, 189, 385, 190, 52];
$isOwner = $isUserLoggedIn && ($currentUserId == $model->user_id || in_array($currentUserId, $allowedUserIds));

$css = <<<CSS
@import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap');

.rental-view-wrapper {
    font-family: 'Sarabun', sans-serif;
    max-width: 1850px;
    margin: 32px auto;
}

.rental-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,.07), 0 10px 28px rgba(0,0,0,.05);
    overflow: hidden;
    border: 1px solid #e8edf3;
}

/* Header */
.rental-card-header {
    background: linear-gradient(135deg, #1a5fa8 0%, #1e7fc2 100%);
    padding: 36px 56px;
    display: flex;
    align-items: center;
    gap: 16px;
    position: relative;
}
.rental-card-header::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, #f59e0b, #fbbf24, #f59e0b);
}
.rental-card-header .header-icon {
    width: 50px; height: 50px;
    background: rgba(255,255,255,.18);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 22px;
    color: #fff;
    flex-shrink: 0;
}
.rental-card-header h3 {
    margin: 0;
    color: #fff;
    font-size: 1.65em;
    font-weight: 600;
}
.rental-card-header .booking-id {
    margin: 3px 0 0;
    color: rgba(255,255,255,.75);
    font-size: 1rem;
}

/* User Bar */
.user-info-bar {
    background: #f0f6ff;
    border-bottom: 1px solid #dce8f6;
    padding: 16px 56px;
    display: flex;
    gap: 28px;
    flex-wrap: wrap;
    font-size: 1.60m;
    color: #4a5568;
}
.user-info-bar .info-item {
    display: flex; align-items: center; gap: 7px;
}
.user-info-bar .info-item i { color: #1a5fa8; }
.user-info-bar strong { color: #1a3a5c; font-weight: 600; }

/* Action Buttons */
.rental-actions {
    padding: 26px 56px 22px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    border-bottom: 1px solid #edf2f7;
}

.btn-rental {
    display: inline-flex;
    align-items: center;
    gap: 8px;
    padding: 9px 20px;
    border-radius: 8px;
    font-family: 'Sarabun', sans-serif;
    font-size: 1.6rem;
    font-weight: 600;
    cursor: pointer;
    border: none;
    text-decoration: none;
    transition: all .18s ease;
    line-height: 1;
    white-space: nowrap;
}
.btn-rental:hover { transform: translateY(-1px); text-decoration: none; }
.btn-rental:active { transform: translateY(0); }

.btn-approve  { background: linear-gradient(135deg,#059669,#10b981); color:#fff; box-shadow:0 3px 10px rgba(5,150,105,.25); }
.btn-approve:hover  { background: linear-gradient(135deg,#047857,#059669); color:#fff; }
.btn-edit     { background: linear-gradient(135deg,#d97706,#f59e0b); color:#fff; box-shadow:0 3px 10px rgba(217,119,6,.25); }
.btn-edit:hover     { background: linear-gradient(135deg,#b45309,#d97706); color:#fff; }
.btn-cancel   { background: linear-gradient(135deg,#0284c7,#0ea5e9); color:#fff; box-shadow:0 3px 10px rgba(2,132,199,.25); }
.btn-cancel:hover   { background: linear-gradient(135deg,#0369a1,#0284c7); color:#fff; }
.btn-print    { background: linear-gradient(135deg,#059669,#10b981); color:#fff; box-shadow:0 3px 10px rgba(5,150,105,.2); }
.btn-print:hover    { background: linear-gradient(135deg,#047857,#059669); color:#fff; }
.btn-back-cal  { background:#fff; color:#d97706; border:1.5px solid #f59e0b; }
.btn-back-cal:hover  { background:#fffbeb; color:#b45309; }
.btn-back-list { background:#fff; color:#1a5fa8; border:1.5px solid #60a5fa; }
.btn-back-list:hover { background:#eff6ff; color:#1e40af; }

/* Detail Table */
.rental-card-body {
    padding: 36px 56px;
}
.detail-view-custom table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 10px;
    overflow: hidden;
    border: 1px solid #e2e8f0;
    font-family: 'Sarabun', sans-serif;
    font-size: 1.58rem;
}
.detail-view-custom table th {
    background: #f1f5fb;
    color: #344563;
    font-weight: 600;
    padding: 17px 26px;
    border-bottom: 1px solid #dce8f6;
    border-right: 1px solid #e2e8f0;
    width: 28%;
    vertical-align: middle;
}
.detail-view-custom table td {
    padding: 17px 26px;
    color: #2d3748;
    background: #fff;
    border-bottom: 1px solid #edf2f7;
    vertical-align: middle;
}
.detail-view-custom table tr:last-child th,
.detail-view-custom table tr:last-child td { border-bottom: none; }
.detail-view-custom table tr:hover td,
.detail-view-custom table tr:hover th { background: #f8fbff; }

/* Footer */
.rental-footer {
    padding: 22px 56px 34px;
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
    border-top: 1px solid #edf2f7;
    background: #fafbfd;
}
CSS;

$this->registerCss($css);
?>

<div class="rental-view-wrapper">
    <div class="rental-card">

        <!-- Header -->
        <div class="rental-card-header">
            <div class="header-icon"><i class="fa fa-car"></i></div>
            <div>
                <h3><?= Html::encode($this->title) ?></h3>
                <div class="booking-id">ระบบจองพาหนะ — รายละเอียดการจอง</div>
            </div>
        </div>

        <!-- User Info Bar -->
        <div class="user-info-bar">
            <div class="info-item">
                <i class="fa fa-user-circle"></i>
                <span>ผู้ใช้งานขณะนี้: <strong><?= Html::encode($currentUserName) ?></strong></span>
            </div>
            <div class="info-item">
                <i class="fa fa-id-badge"></i>
                <span>ผู้แจ้งขอรถ (ID): <strong><?= Html::encode($model->user_id) ?></strong></span>
            </div>
        </div>

        <!-- Action Buttons (Top) -->
        <div class="rental-actions">
            <?= Html::a('<i class="fa fa-check-circle"></i> อนุมัติ', ['accept', 'id' => $model->id], ['class' => 'btn-rental btn-approve']) ?>
            <?php if ($isOwner): ?>
                <?= Html::a('<i class="fa fa-pencil"></i> แก้ไข', ['update', 'id' => $model->id], ['class' => 'btn-rental btn-edit']) ?>
                <?= Html::a('<i class="fa fa-times-circle"></i> ยกเลิก', ['update', 'id' => $model->id], ['class' => 'btn-rental btn-cancel']) ?>
            <?php endif; ?>
        </div>

        <!-- Detail View -->
        <div class="rental-card-body">
            <div class="detail-view-custom">
                <?= DetailView::widget([
                    'model' => $model,
                    'attributes' => [
                        [
                            'attribute' => 'status',
                            'format'    => 'html',
                            'value'     => Yii::$app->rentalStatus->getRentalStatus($model->status),
                        ],
                        [
                            'attribute' => 'user_id',
                            'value'     => $model->user->firstname . ' ' . $model->user->lastname,
                        ],
                        'destination',
                        'passenger',
                        'description:ntext',
                        'date_start',
                        'date_end',
                        [
                            'attribute' => 'dep_id',
                            'value'     => $model->departments->dep_name,
                        ],
                        [
                            'attribute' => 'vehicle_id',
                            'value'     => $model->vehicle->license,
                        ],
                        [
                            'attribute' => 'driver_id',
                            'value'     => $model->driver->driver_name,
                        ],
                        'create_at',
                    ],
                ]) ?>
            </div>
        </div>

        <!-- Footer Buttons -->
        <div class="rental-footer">
            <?= Html::a('<i class="glyphicon glyphicon-print"></i> พิมพ์เอกสาร', ['print', 'id' => $model->id], ['class' => 'btn-rental btn-print']) ?>
            <a class="btn-rental btn-back-cal" href="localhost/mhosp-office/web/index.php?r=rental/calendar">
                <i class="glyphicon glyphicon-calendar"></i> กลับหน้าปฏิทิน
            </a>
            <a class="btn-rental btn-back-list" href="localhost/mhosp-office/web/index.php?r=rental/index">
                <i class="glyphicon glyphicon-list"></i> กลับหน้าจอง
            </a>
        </div>

    </div>
</div>