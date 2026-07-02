<?php
use kartik\grid\GridView;
use yii\helpers\Html;
use kartik\widgets\ActiveForm;
use yii\helpers\Url;

$this->title = 'DEVICES_ALL';
$this->params['breadcrumbs'][] = ['label' => 'รายงาน', 'url' => ['computer/index']];
$this->params['breadcrumbs'][] = 'รายงานอุปกรณ์คอมพิวเตอร์แยกตามประภทและหมายเลขครุภัณฑ์';
?>
<?php
//return $this->redirect(array('report/dsc_list', ['date1' => $date1, 'date1' => $date2]));
echo GridView::widget([
        'dataProvider' => $dataProvider,
        
        'panel' => [
            'before'=>'รายงานอุปกรณ์คอมพิวเตอร์แยกตามประภทและหมายเลขครุภัณฑ์',
            'after'=>'ประมวลผล '.date('Y-m-d H:i:s')
          ],
               'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                  
                    [
                        'attribute' => 'category_id',
                        'header' => 'รหัส',
                    ],
                    [
                        'attribute' => 'category_name',
                        'header' => 'ประเภท',
                    ],
                    [
                        'attribute' => 'จำนวน',
                        'format' => 'raw',
                        'value' => function($model) {
                            $catid = $model['category_id'];
                            $amount = $model['amount'];
                            return Html::a(Html::encode($amount), ['computer/devices_all_list','catid'=> $catid],['target'=>'_blank']);
                        }
                            ],
                  ]
                ]
                    );
                    
                    ?>
                    
                    <div class="alert alert-danger"><?=$sql?> </div>
