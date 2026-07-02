<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\Rental */

$this->title = Yii::t('app', 'เพิ่มการจองรถยนต์');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'การจองรถ'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$css = <<<CSS
@import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap');

.rental-create-wrapper {
    font-family: 'Sarabun', sans-serif;
    max-width: 1750px;
    margin: 32px auto;
}

.rental-create-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,.07), 0 10px 28px rgba(0,0,0,.05);
    overflow: hidden;
    border: 1px solid #e8edf3;
}

/* Header */
.rental-create-header {
    background: linear-gradient(135deg, #065f46 0%, #059669 100%);
    padding: 36px 56px;
    display: flex;
    align-items: center;
    gap: 16px;
    position: relative;
}
.rental-create-header::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, #f59e0b, #fbbf24, #f59e0b);
}
.rental-create-header .header-icon {
    width: 52px; height: 52px;
    background: rgba(255,255,255,.2);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 24px;
    color: #fff;
    flex-shrink: 0;
}
.rental-create-header h3 {
    margin: 0;
    color: #fff;
    font-size: 1.65rem;
    font-weight: 600;
}
.rental-create-header .sub-title {
    margin: 3px 0 0;
    color: rgba(255,255,255,.78);
    font-size: 1rem;
}

/* Body */
.rental-create-body {
    padding: 34px 56px 40px;
}

.rental-create-body .form-group label {
    font-family: 'Sarabun', sans-serif;
    font-size: 1.45rem;
    font-weight: 800;
    color: #344563;
    margin-bottom: 6px;
}
.rental-create-body .form-control {
    font-family: 'Sarabun', sans-serif;
    font-size: 1.3rem;
    border: 1.5px solid #d1dce8;
    border-radius: 8px;
    padding: 10px 14px;
    color: #2d3748;
    transition: border-color .18s, box-shadow .18s;
}
.rental-create-body .form-control:focus {
    border-color: #059669;
    box-shadow: 0 0 0 3px rgba(5,150,105,.12);
    outline: none;
}
.rental-create-body .btn-primary {
    font-family: 'Sarabun', sans-serif;
    font-size: 1.45rem;
    font-weight: 700;
    background: linear-gradient(135deg, #065f46, #059669);
    border: none;
    border-radius: 9px;
    padding: 11px 32px;
    box-shadow: 0 3px 10px rgba(5,150,105,.25);
    transition: all .18s ease;
}
.rental-create-body .btn-primary:hover {
    background: linear-gradient(135deg, #064e3b, #047857);
    box-shadow: 0 5px 14px rgba(5,150,105,.35);
    transform: translateY(-1px);
}
.rental-create-body .help-block {
    font-size: 1.25rem;
    color: #e53e3e;
    margin-top: 4px;
}
CSS;

$this->registerCss($css);
?>

<div class="rental-create-wrapper">
    <div class="rental-create-card">

        <!-- Header -->
        <div class="rental-create-header">
            <div class="header-icon"><i class="fa fa-plus-circle"></i></div>
            <div>
                <h3><?= Html::encode($this->title) ?></h3>
                <div class="sub-title">ระบบจองพาหนะ — กรอกข้อมูลการจองใหม่</div>
            </div>
        </div>

        <!-- Form Body -->
        <div class="rental-create-body">
            <?= $this->render('_form', ['model' => $model]) ?>
        </div>

    </div>
</div>