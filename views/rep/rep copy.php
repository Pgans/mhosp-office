<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;
use kartik\editable\Editable;
use yii\bootstrap\Modal;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use app\models\Lsubfund;
use app\models\Msumsubfund;
use prawee\widgets\ButtonAjax;




$this->title = 'rep E-Claim';
//$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['rep/index']];
//$this->params['breadcrumbs'][] = 'รายงานข้อมูลE-Claim แยกตามREP';
?>
<div class="row">
    <div class="col-md-12">
        <div class="panel panel-info">
            <div class="panel-heading"><i class="fa fa-search"></i> ค้นหาRep ตัวอย่างเช่น 611100035 </div>
            <div class="panel-body">

                <?= Html::beginForm(); ?>

                <label for="pwd">RepNo : &nbsp;&nbsp; </label>
                <input type="text"  name="rep1"  placeholder="">
                <input type="text"  name="rep2"  placeholder="">
                <input type="text"  name="rep3"  placeholder="">  
                <input type="text"  name="rep4"  placeholder="">
                <input type="text"  name="rep5"  placeholder="">
                <input type="text"  name="rep6"  placeholder="">
                <input type="text"  name="rep7"  placeholder="">
                <input type="text"  name="rep8"  placeholder="">
                <input type="text"  name="rep9"  placeholder=""> 
        <?php
        $items = ArrayHelper::map(Lsubfund::find()->all(), 'SUB_FUND', 'SUB_FUND');
        echo Html::dropDownList('SUB_FUND', $subfund, $items, ['prompt' => '--- กองทุนย่อย ---']);
        ?>        
                
                &nbsp;&nbsp;<button class='btn btn-danger'>ค้นหา</button>
                <?= Html::endForm(); ?>
                
            </div>
        </div>
    </div>
</div>
<!-- <p>
    <?= Html::button('<i class="glyphicon glyphicon-plus"></i>แจ้งส่งซ่อมคอมและโสตทัศนศึกษา', ['value'=>Url::to(['rep/rep2']), 'class' => 'btn btn-success btn-lg','id'=>'modalButton']); ?>
        <?= Html::a(Yii::t('app','เมื่อบันทึกระบบจะส่งเข้าไลน์ถึงผู้รับผิดชอบทันที....ขอบคุณครับ'))?>
    </p> -->
    <?php
    Modal::begin([
            'header' => '<h4>Добавить ставку</h4>',
            'id' => 'modal',
            'size' => 'modal-lg',
        ]);
        echo "<div id='modalContent'></div>";
        Modal::end();?>

    <?php //Pjax::begin(); ?>
<?php
echo GridView::widget([
        'dataProvider' => $dataProvider,
       // 'filterModel' => $searchModel,
        'panel' => [
            'before'=>'<a>รายงานข้อมูลE-Claim แยกตามREP  ประจำเดือน</a> '.date('Y-m'),
            'after'=>'ประมวลผล '.date('Y-m-d H:i:s')
            ],
            'showPageSummary' => true,
            'columns' => [
                   // ['class' => 'yii\grid\SerialColumn'],
                    ['class' => 'kartik\grid\SerialColumn'],
                    [
                        //'class' => 'kartik\grid\EditableColumn',
                        'attribute' => 'REP',
                        'headerOptions'=>[ 'style'=>'background-color:#8d8de3'] ,
                       // 'label'=>'REP',611100035
                       'format'=>'raw',
                       'value' => function ($model, $key, $index, $widget) {
                        return "<font  color='2E86C1'>" . $model['REP'] . "</font>"; 
                },       
                    ],
                    [
                        'attribute' => 'HN',
                       'headerOptions'=>[ 'style'=>'background-color:#8d8de3'] ,
                       
                    ],
                    
                    [
                        'attribute' => 'DATEADM',
                        'label'=>'วันที่รับบริการ',
                    'headerOptions'=>[ 'style'=>'background-color:#8d8de3'] ,
                    ],
                    [
                        'attribute' => 'FULLNAME',
                        'label'=>'ชื่อ-สกุล',
                        'headerOptions'=>[ 'style'=>'background-color:#8d8de3'] ,
                        'format'=>'raw',
                        'value' => function ($model, $key, $index, $widget) {
                            return "<font  color='2E86C1'>" . $model['FULLNAME'] . "</font>"; 
                    }, 
                    ],
                    [
                        'attribute' => 'SUB_FUND',
                        'label'=>'กองทุนย่อย',
                       'headerOptions'=>[ 'style'=>'background-color:#8d8de3'] ,
                       'format'=>'raw',
                        'value' => function ($model, $key, $index, $widget) {
                            return "<font  color='FF9C33'>" . $model['SUB_FUND'] . "</font>"; 
                    }, 
                       'pageSummary'=> 'รวม',
                    ],
                    [
                       // 'class' => 'kartik\grid\EditableColumn',
                        'attribute' => 'SUMS_SERVICEITEM',
                        'label'=>'เรียกเก็บ',
                        'format'=>'raw',
                       // 'value' => function ($model, $key, $index, $widget) {
                          //  return "<font  color='FF9C33'>" . $model['SUMS_SERVICEITEM'] . "</font>"; 
                   // }, 
                        'headerOptions'=>[ 'style'=>'background-color:#8d8de3'] ,
                        'pageSummary' => true,
                    ],//FF9C33  42E908  
                    [
                        'attribute' => 'TOTL_AMT',
                        'label'=>'ชดเชย',
                    'headerOptions'=>[ 'style'=>'background-color:#8d8de3'] ,
                    'pageSummary'=> true,
                    ],
                    [
                        'attribute' => 'ACT_AMT',
                        'label'=>'ชดเชย2',
                    'headerOptions'=>[ 'style'=>'background-color:#8d8de3'] ,
                    'pageSummary'=> true,
                    'format' => ['decimal',2] 
                    ],
                    ]
                    ]);
                    
                    
                      ?>
                       <?php// Pjax::end() ?> 
             <input class="btn btn-primary" name="btnButton" type="button" value="Print Results" onClick="JavaScript:window.print();">
              <?php echo Html::a('ส่งการเงิน', ['rep/rep2'], ['class' => 'btn btn-success', 'style' => 'margin-left:5px','id'=>'modalButton']); ?>
     <?= Html::button(Yii::t('app', 'Create Stavka'), ['value' => Url::to('index.php?r=rep/rep'),'class' => 'btn btn-success', 'id' => 'modalButton']) ?>
 
              <?php echo Html::a('ผลงานเคลม', ['rep/rep3'], ['class' => 'btn btn-info', 'style' => 'margin-left:5px','target'=>'_blank']); ?>  
            
              </div>
            </div>
 <p>
<?= Html::button('เพิ่มรางวัลดีเด่น', ['value'=>Url::to(['rep/rep']), 'class' =>'btn btn-danger','id'=>'modalButton']); ?> 


</p>
            <div class="alert alert-danger"><?=$sql?> </div>

            <?php
$this->registerJsFile('@web/js/main.js', ['depends' => [\yii\web\JqueryAsset::className()]]);
$this->registerJs("$(function() {
    $('#popupModal').click(function(e) {
      e.preventDefault();
      $('#modal').modal('show').find('.modal-content')
      .load($(this).attr('href'));
    });
 });");
?>
                    
                   