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
use kartik\select2\Select2;


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
        <!-- <?php
        $items = ArrayHelper::map(Lsubfund::find()->all(), 'SUB_FUND', 'SUB_FUND');
        echo Html::dropDownList('SUB_FUND', $subfund, $items, ['prompt' => '--- กองทุนย่อย ---']);
        ?>         -->
                
                &nbsp;&nbsp;<button class='btn btn-danger'>ค้นหา</button>
                <?= Html::endForm(); ?>
                
            </div>
        </div>
    </div>
</div>
<?php
echo GridView::widget([
        'dataProvider' => $dataProvider,
        //'filterModel' => $searchModel,
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
                        'attribute' => 'rep',
                        'headerOptions'=>[ 'style'=>'background-color:#8d8de3'] ,
                       // 'label'=>'REP',611100035
                       'format'=>'raw',
                       'value' => function ($model, $key, $index, $widget) {
                        return "<font  color='2E86C1'>" . $model['rep'] . "</font>"; 
                },       
                    ],
                    
                    [
                        'attribute' => 'sub_fund',
                        'label'=>'กองทุนย่อย',
                       'headerOptions'=>[ 'style'=>'background-color:#8d8de3'] ,
                       'format'=>'raw',
                        'value' => function ($model, $key, $index, $widget) {
                            return "<font  color='FF9C33'>" . $model['sub_fund'] . "</font>"; 
                    }, 
                       'pageSummary'=> 'รวม',
                    ],
                    [
                        'attribute' => 'amount',
                        'label'=>'จำนวน',
                    'headerOptions'=>[ 'style'=>'background-color:#8d8de3'] ,
                    'format'=> 'raw',
                    'value' => function($model) {
                        $subfund = $model['sub_fund'];
                        $amount = $model['amount'];
                        return Html::a(Html::encode($amount), ['rep/rep_list','subfund' => $subfund],['target'=>'_blank']);
                    },
                    'pageSummary'=> true,
                    ],
                    ]
                    ]);
                    
                      ?>
                       
             <input class="btn btn-primary" name="btnButton" type="button" value="Print Results" onClick="JavaScript:window.print();">
              <!-- <?php echo Html::a('ส่งการเงิน', ['rep/rep2'], ['class' => 'btn btn-success', 'style' => 'margin-left:5px','target'=>'_blank']); ?>
              <?php echo Html::a('ผลงานเคลม', ['rep/rep3'], ['class' => 'btn btn-info', 'style' => 'margin-left:5px','target'=>'_blank']); ?>   -->
            
              </div>
            </div>

            <div class="alert alert-danger"><?=$sql?> </div>
                    
                   