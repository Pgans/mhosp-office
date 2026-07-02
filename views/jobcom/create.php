<?php
use yii\helpers\Html;
/* @var $this yii\web\View */
/* @var $model app\models\Jobcom */

$this->title = Yii::t('app', 'รายการส่งซ่อมคอมพิวเตอร์และโสตทัศนศึกษา');
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'Jobcoms'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;

$css = <<<CSS
@import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap');

.jobcom-create-wrapper {
    font-family: 'Sarabun', sans-serif;
    max-width: 1850px;
    margin: 32px auto;
}

/* ── Card ── */
.jobcom-create-card {
    background: #fff;
    border-radius: 16px;
    box-shadow: 0 2px 8px rgba(0,0,0,.07), 0 10px 28px rgba(0,0,0,.05);
    overflow: hidden;
    border: 1px solid #e8edf3;
}

/* ── Header ── */
.jobcom-create-header {
    background: linear-gradient(135deg, #6d28d9 0%, #7c3aed 100%);
    padding: 36px 56px;
    display: flex;
    align-items: center;
    gap: 16px;
    position: relative;
}
.jobcom-create-header::after {
    content: '';
    position: absolute;
    bottom: 0; left: 0; right: 0;
    height: 4px;
    background: linear-gradient(90deg, #f59e0b, #fbbf24, #f59e0b);
}
.jobcom-create-header .header-icon {
    width: 52px; height: 52px;
    background: rgba(255,255,255,.2);
    border-radius: 12px;
    display: flex; align-items: center; justify-content: center;
    font-size: 24px;
    color: #fff;
    flex-shrink: 0;
}
.jobcom-create-header h3 {
    margin: 0;
    color: #fff;
    font-size: 1.5rem;
    font-weight: 600;
}
.jobcom-create-header .sub-title {
    margin: 3px 0 0;
    color: rgba(255,255,255,.75);
    font-size: 1.5rem;
}

/* ── Body ── */
.jobcom-create-body {
    padding: 34px 56px 40px;
}

/* Flash alerts */
.jobcom-create-body .alert {
    font-family: 'Sarabun', sans-serif;
    font-size: 1.5rem;
    border-radius: 10px;
    border: none;
    padding: 13px 18px;
    margin-top: 20px;
}
.jobcom-create-body .alert-success { background: #d1fae5; color: #065f46; }
.jobcom-create-body .alert-danger  { background: #fee2e2; color: #7f1d1d; }
.jobcom-create-body .alert-warning { background: #fef3c7; color: #78350f; }
.jobcom-create-body .alert-info    { background: #dbeafe; color: #1e3a8a; }

/* Form overrides */
.jobcom-create-body .form-group label {
    font-family: 'Sarabun', sans-serif;
    font-size: 1.5rem;
    font-weight: 600;
    color: #344563;
    margin-bottom: 6px;
}
.jobcom-create-body .form-control {
    font-family: 'Sarabun', sans-serif;
    font-size: 1.5rem;
    border: 1.5px solid #d1dce8;
    border-radius: 8px;
    padding: 10px 14px;
    color: #2d3748;
    transition: border-color .18s, box-shadow .18s;
}
.jobcom-create-body .form-control:focus {
    border-color: #7c3aed;
    box-shadow: 0 0 0 3px rgba(124,58,237,.12);
    outline: none;
}
.jobcom-create-body .btn-primary {
    font-family: 'Sarabun', sans-serif;
    font-size: 1.5rem;
    font-weight: 600;
    background: linear-gradient(135deg, #6d28d9, #7c3aed);
    border: none;
    border-radius: 9px;
    padding: 11px 32px;
    box-shadow: 0 3px 10px rgba(109,40,217,.25);
    transition: all .18s ease;
}
.jobcom-create-body .btn-primary:hover {
    background: linear-gradient(135deg, #5b21b6, #6d28d9);
    box-shadow: 0 5px 14px rgba(109,40,217,.35);
    transform: translateY(-1px);
}
.jobcom-create-body .help-block {
    font-size: 1.5rem;
    color: #e53e3e;
    margin-top: 4px;
}
CSS;

$this->registerCss($css);
?>

<div class="jobcom-create-wrapper">
    <div class="jobcom-create-card">

        <!-- Header -->
        <div class="jobcom-create-header">
            <div class="header-icon"><i class="fa fa-wrench"></i></div>
            <div>
                <h3><?= Html::encode($this->title) ?></h3>
                <div class="sub-title">ระบบแจ้งซ่อม — กรอกข้อมูลแจ้งซ่อมใหม่</div>
            </div>
        </div>

        <!-- Form Body -->
        <div class="jobcom-create-body">

            <?= $this->render('_form', ['model' => $model]) ?>

            <?php if (Yii::$app->session->hasFlash('alert')): ?>
                <div class="alert <?= Yii::$app->session->getFlash('alert')['options']['class'] ?>">
                    <i class="fa fa-check-circle"></i>
                    <?= Yii::$app->session->getFlash('alert')['body'] ?>
                </div>
            <?php endif; ?>

            <?php if (Yii::$app->session->hasFlash('error')): ?>
                <div class="alert <?= Yii::$app->session->getFlash('error')['options']['class'] ?>">
                    <i class="fa fa-exclamation-circle"></i>
                    <?= Yii::$app->session->getFlash('error')['body'] ?>
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

<script>
$('form#your-form').on('beforeSubmit', function(e) {
    var form = $(this);
    $.post(
        form.attr('action'),
        form.serialize()
    )
    .done(function(response) {
        if (response.success) {
            $('#createModal').modal('hide');
            $.pjax.reload({container: '#your-pjax-container'});
        } else {
            $('#createContent').html(response.content);
        }
    })
    .fail(function() {
        console.log('server error');
    });
    return false;
});
</script>