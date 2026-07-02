<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\helpers\ArrayHelper;
use app\models\Skill;
use app\models\Province;
use app\models\Hospanamai;
use kartik\widgets\DepDrop;
use yii\helpers\Url;
use kartik\select2\Select2;

/* @var $this yii\web\View */
/* @var $model app\models\Orderoils */
/* @var $form yii\widgets\ActiveForm */

$this->registerCss("
@import url('https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&family=Prompt:wght@300;400;600;700&display=swap');

:root {
    --pri:       #0d5c7a;
    --pri-mid:   #1a7fa8;
    --pri-light: #2ba3d0;
    --acc:       #f5a623;
    --acc-light: #ffc55a;
    --bg:        #eaf2f7;
    --card:      #ffffff;
    --border:    #c8dce8;
    --text:      #1a2b38;
    --muted:     #5f7d8e;
    --danger:    #c0392b;
    --shadow:    0 6px 28px rgba(13,92,122,0.13);
    --r:         12px;
}

.page-oils {
    min-height: calc(100vh - 120px);
    background: var(--bg);
    padding: 28px 0 56px;
    font-family: 'Sarabun', sans-serif;
}

.oils-card {
    max-width: 860px;
    margin: 0 auto;
    background: var(--card);
    border-radius: var(--r);
    box-shadow: var(--shadow);
    overflow: hidden;
}

.oils-card-header {
    background: linear-gradient(120deg, var(--pri) 0%, var(--pri-light) 100%);
    padding: 26px 36px 22px;
    display: flex;
    align-items: center;
    gap: 18px;
    position: relative;
    overflow: hidden;
}

.oils-card-header::after {
    content: 'fuel';
    font-family: monospace;
    position: absolute;
    right: 30px; top: 50%;
    transform: translateY(-50%);
    font-size: 72px;
    opacity: 0.06;
    pointer-events: none;
}

.hdr-icon-wrap {
    width: 52px; height: 52px;
    background: rgba(255,255,255,0.18);
    border-radius: 13px;
    display: flex; align-items: center; justify-content: center;
    font-size: 26px; flex-shrink: 0;
}

.hdr-text h2 {
    font-family: 'Prompt', sans-serif;
    font-size: 1.25rem; font-weight: 600;
    color: #fff; margin: 0 0 3px;
}

.hdr-text p {
    font-size: 0.82rem;
    color: rgba(255,255,255,0.68);
    margin: 0;
}

.oils-card-body {
    padding: 32px 36px 40px;
}

.sec {
    font-family: 'Prompt', sans-serif;
    font-size: 0.72rem; font-weight: 700;
    color: var(--pri-mid);
    text-transform: uppercase;
    letter-spacing: 0.1em;
    margin: 32px 0 18px;
    padding-bottom: 10px;
    border-bottom: 2px solid #d4eaf4;
    display: flex; align-items: center; gap: 10px;
}
.sec:first-child { margin-top: 0; }
.sec-bar {
    width: 4px; height: 14px;
    background: linear-gradient(180deg, var(--acc) 0%, var(--acc-light) 100%);
    border-radius: 2px; flex-shrink: 0;
    display: inline-block;
}

.g { display: flex; flex-wrap: wrap; gap: 18px; margin-bottom: 2px; }
.g-full  { flex: 1 1 100%; }
.g-half  { flex: 1 1 calc(50% - 9px);  min-width: 200px; }
.g-third { flex: 1 1 calc(33.33% - 12px); min-width: 170px; }

.oils-card-body label.control-label {
    font-size: 0.8rem; font-weight: 600;
    color: var(--muted);
    margin-bottom: 6px; display: block;
    letter-spacing: 0.025em;
}

.oils-card-body .form-control {
    border: 1.5px solid var(--border);
    border-radius: 8px;
    padding: 9px 14px;
    font-size: 0.9rem;
    font-family: 'Sarabun', sans-serif;
    color: var(--text);
    background: #f5fafd;
    transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
    width: 100%; box-sizing: border-box;
    height: auto;
}
.oils-card-body .form-control:focus {
    border-color: var(--pri-light);
    box-shadow: 0 0 0 3px rgba(43,163,208,0.14);
    background: #fff;
    outline: none;
}

.radio-wrap .radio { display: inline-block; margin-right: 10px; }
.radio-wrap .radio label {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 8px 20px;
    border: 1.5px solid var(--border);
    border-radius: 9px; cursor: pointer;
    font-size: 0.9rem; font-weight: 500;
    background: #f5fafd;
    transition: all 0.18s;
    color: var(--text);
}
.radio-wrap .radio label:hover { border-color: var(--pri-light); background: #eaf5fb; }

.cb-wrap .checkbox { display: inline-block; margin-right: 8px; margin-bottom: 8px; }
.cb-wrap .checkbox label {
    display: inline-flex; align-items: center; gap: 7px;
    padding: 7px 16px;
    border: 1.5px solid var(--border);
    border-radius: 9px; cursor: pointer;
    font-size: 0.87rem; font-weight: 500;
    background: #f5fafd;
    transition: all 0.18s; color: var(--text);
}
.cb-wrap .checkbox label:hover { border-color: var(--acc); background: #fff9ee; }

.select2-container .select2-selection--single {
    border: 1.5px solid var(--border) !important;
    border-radius: 8px !important;
    height: 40px !important;
    background: #f5fafd !important;
}
.select2-container--default .select2-selection--single .select2-selection__rendered {
    line-height: 38px !important;
    font-family: 'Sarabun', sans-serif !important;
    font-size: 0.9rem !important;
    color: var(--text) !important;
    padding-left: 14px !important;
}
.select2-container--default .select2-selection--single .select2-selection__placeholder {
    color: #a0b4bf !important;
}

.help-block { font-size: 0.77rem; color: var(--danger); margin-top: 4px; }
.has-error .form-control { border-color: var(--danger) !important; }

.submit-area {
    display: flex; justify-content: flex-end; align-items: center;
    gap: 12px;
    margin-top: 34px; padding-top: 24px;
    border-top: 1.5px solid #deeaf2;
}

.submit-area .note {
    font-size: 0.78rem; color: var(--muted);
    margin-right: auto;
}

.btn-save {
    display: inline-flex; align-items: center; gap: 9px;
    background: linear-gradient(135deg, var(--pri) 0%, var(--pri-light) 100%);
    color: #fff; border: none;
    border-radius: 10px;
    padding: 11px 36px;
    font-family: 'Prompt', sans-serif;
    font-size: 0.95rem; font-weight: 600;
    cursor: pointer;
    box-shadow: 0 4px 18px rgba(13,92,122,0.28);
    transition: all 0.22s;
}
.btn-save:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 28px rgba(13,92,122,0.35);
    color: #fff;
}
.btn-save.edit {
    background: linear-gradient(135deg, #1a5fa4 0%, #3a8fd4 100%);
}

.btn-back {
    display: inline-flex; align-items: center; gap: 7px;
    background: transparent;
    color: var(--muted); border: 1.5px solid var(--border);
    border-radius: 10px; padding: 11px 24px;
    font-family: 'Sarabun', sans-serif;
    font-size: 0.9rem; font-weight: 500;
    cursor: pointer; transition: all 0.18s;
    text-decoration: none;
}
.btn-back:hover {
    border-color: var(--pri-light); color: var(--pri);
    background: #eaf5fb; text-decoration: none;
}

@media (max-width: 680px) {
    .oils-card-body { padding: 22px 18px 28px; }
    .oils-card-header { padding: 20px 18px; }
    .g-half, .g-third { flex: 1 1 100%; }
}
");
?>

<div class="page-oils">
<div class="container">
<div class="oils-card">

    <div class="oils-card-header">
        <div class="hdr-icon-wrap">&#9981;</div>
        <div class="hdr-text">
            <h2>แบบฟอร์มเบิกจ่ายน้ำมันเชื้อเพลิง</h2>
            <p>กรอกข้อมูลให้ครบถ้วนก่อนกดบันทึก</p>
        </div>
    </div>

    <div class="oils-card-body">
        <?php $form = ActiveForm::begin(); ?>

        <!-- Section 1 -->
        <div class="sec"><span class="sec-bar"></span> ประเภทการดำเนินการ</div>

        <div class="g">
            <div class="g-full radio-wrap">
                <?= $form->field($model, 'spray_id')
                    ->label('ประเภทการพ่น')
                    ->radioList(['1' => 'การพ่นหมอกควัน']) ?>
            </div>
        </div>

        <div class="g">
            <div class="g-full cb-wrap">
                <?= $form->field($model, 'oils')
                    ->label('ประเภทน้ำมัน / อุปกรณ์ที่เบิก')
                    ->checkBoxList(ArrayHelper::map(Skill::find()->all(), 'id', 'name')) ?>
            </div>
        </div>

        <!-- Section 2 -->
        <div class="sec"><span class="sec-bar"></span> ข้อมูลผู้รับบริการ</div>

        <div class="g">
            <div class="g-half">
                <?= $form->field($model, 'fullname')
                    ->label('ชื่อ-นามสกุล')
                    ->textInput(['maxlength' => true, 'placeholder' => 'กรอกชื่อ-นามสกุลผู้รับบริการ']) ?>
            </div>
            <div class="g-half">
                <?= $form->field($model, 'diagnosis')
                    ->label('การวินิจฉัย / อาการสำคัญ')
                    ->textInput(['maxlength' => true, 'placeholder' => 'กรอกการวินิจฉัย']) ?>
            </div>
        </div>

        <!-- Section 3 -->
        <div class="sec"><span class="sec-bar"></span> ที่อยู่ผู้รับบริการ</div>

        <div class="g">
            <div class="g-third">
                <?= $form->field($model, 'province_id')
                    ->label('จังหวัด')
                    ->dropDownList(
                        ArrayHelper::map(Province::find()->all(), 'PROVINCE_ID', 'PROVINCE_NAME'),
                        ['id' => 'ddl-province', 'prompt' => '-- เลือกจังหวัด --', 'class' => 'form-control']
                    ) ?>
            </div>
            <div class="g-third">
                <?= $form->field($model, 'amphur_id')
                    ->label('อำเภอ / เขต')
                    ->widget(DepDrop::classname(), [
                        'options' => ['id' => 'ddl-amphur', 'class' => 'form-control'],
                        'data' => [],
                        'pluginOptions' => [
                            'depends'     => ['ddl-province'],
                            'placeholder' => '-- เลือกอำเภอ --',
                            'url'         => Url::to(['/orderoils/get-amphur']),
                        ],
                    ]) ?>
            </div>
            <div class="g-third">
                <?= $form->field($model, 'district_id')
                    ->label('ตำบล / แขวง')
                    ->widget(DepDrop::classname(), [
                        'options' => ['id' => 'ddl-district', 'class' => 'form-control'],
                        'data' => [],
                        'pluginOptions' => [
                            'depends'     => ['ddl-province', 'ddl-amphur'],
                            'placeholder' => '-- เลือกตำบล --',
                            'url'         => Url::to(['/orderoils/get-district']),
                        ],
                    ]) ?>
            </div>
        </div>

        <div class="g">
            <div class="g-half">
                <?= $form->field($model, 'mooban_id')
                    ->label('หมู่บ้าน')
                    ->widget(DepDrop::classname(), [
                        'options' => ['id' => 'ddl-mooban', 'class' => 'form-control'],
                        'data' => [],
                        'pluginOptions' => [
                            'depends'     => ['ddl-province', 'ddl-amphur', 'ddl-district'],
                            'placeholder' => '-- เลือกหมู่บ้าน --',
                            'url'         => Url::to(['/orderoils/get-mooban']),
                        ],
                    ]) ?>
            </div>
            <div class="g-half">
                <?= $form->field($model, 'anamai_id')
                    ->label('สถานีอนามัย / รพ.สต. ที่เบิก')
                    ->widget(Select2::className(), [
                        'data'          => ArrayHelper::map(Hospanamai::find()->all(), 'anamai_id', 'hospname'),
                        'options'       => ['placeholder' => '-- คลิก/พิมพ์เพื่อเลือก --'],
                        'pluginOptions' => ['allowClear' => true],
                    ]) ?>
            </div>
        </div>

        <!-- Submit -->
        <div class="submit-area">
            <span class="note">* กรุณากรอกข้อมูลที่จำเป็นให้ครบถ้วน</span>
            <?= Html::a(
                '<i class="glyphicon glyphicon-arrow-left"></i> ย้อนกลับ',
                ['index'],
                ['class' => 'btn-back']
            ) ?>
            <?= Html::submitButton(
                $model->isNewRecord
                    ? '<i class="glyphicon glyphicon-floppy-disk"></i> บันทึกข้อมูล'
                    : '<i class="glyphicon glyphicon-pencil"></i> แก้ไขข้อมูล',
                ['class' => 'btn-save' . ($model->isNewRecord ? '' : ' edit')]
            ) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
</div>
</div>