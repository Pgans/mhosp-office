<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model backend\modules\rentals\models\Rental */

$this->title = 'แก้ไขหมายเลขการจองที่: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'การจองพาหนะ', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => 'หมายเลขการจองที่: ' . $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'แก้ไข';

$css = <<<CSS
@import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap');

.rental-update-wrapper {
    font-family: 'Sarabun', sans-serif;
    max-width: 1650px;
    margin: 32px auto;
}

.rental-update-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,.07), 0 10px 28px rgba(0,0,0,.05);
    overflow: hidden;
    border: 1px solid #e8edf3;
}

/* Header */
.rental-update-header {
    background: linear-gradient(135deg, #b45309 0%, #d97706 100%);
    padding: 36px 56px;
    display: flex;
    align-items: center;
    gap: 16px;
    position: relative;
}
.rental-update-header::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, #1a5fa8, #60a5fa, #1a5fa8);
}
.rental-update-header .header-icon {
    width: 52px; height: 52px;
    background: rgba(255,255,255,.2);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 24px;
    color: #fff;
    flex-shrink: 0;
}
.rental-update-header h3 {
    margin: 0;
    color: #fff;
    font-size: 1.55rem;
    font-weight: 600;
}
.rental-update-header .sub-title {
    margin: 3px 0 0;
    color: rgba(255,255,255,.78);
    font-size: 1rem;
}

/* Body */
.rental-update-body {
    padding: 34px 56px 40px;
}

/* Style the inner _form elements */
.rental-update-body .form-group label {
    font-family: 'Sarabun', sans-serif;
    font-size: 1.15rem;
    font-weight: 600;
    color: #344563;
    margin-bottom: 6px;
}
.rental-update-body .form-control {
    font-family: 'Sarabun', sans-serif;
    font-size: 1.1rem;
    border: 1.5px solid #d1dce8;
    border-radius: 8px;
    padding: 10px 14px;
    color: #2d3748;
    transition: border-color .18s, box-shadow .18s;
}
.rental-update-body .form-control:focus {
    border-color: #1a5fa8;
    box-shadow: 0 0 0 3px rgba(26,95,168,.12);
    outline: none;
}
.rental-update-body .btn-primary {
    font-family: 'Sarabun', sans-serif;
    font-size: 1.15rem;
    font-weight: 600;
    background: linear-gradient(135deg, #1a5fa8, #1e7fc2);
    border: none;
    border-radius: 9px;
    padding: 11px 32px;
    box-shadow: 0 3px 10px rgba(26,95,168,.25);
    transition: all .18s ease;
}
.rental-update-body .btn-primary:hover {
    background: linear-gradient(135deg, #154e8f, #1a5fa8);
    box-shadow: 0 5px 14px rgba(26,95,168,.35);
    transform: translateY(-1px);
}
.rental-update-body .help-block {
    font-size: .92rem;
    color: #e53e3e;
    margin-top: 4px;
}
CSS;

$this->registerCss($css);
?>

<div class="rental-update-wrapper">
    <div class="rental-update-card">

        <!-- Header -->
        <div class="rental-update-header">
            <div class="header-icon"><i class="fa fa-pencil-square-o"></i></div>
            <div>
                <h3><?= Html::encode($this->title) ?></h3>
                <div class="sub-title">ระบบจองพาหนะ — แก้ไขข้อมูลการจอง</div>
            </div>
        </div>

        <!-- Form Body -->
        <div class="rental-update-body">
            <?= $this->render('_form', ['model' => $model]) ?>
        </div>

    </div>
</div>