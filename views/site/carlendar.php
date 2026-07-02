<?php
/* @var $this yii\web\View */
use yii\helpers\Html;
use kartik\grid\GridView;
use yii\helpers\Url;

$this->title = Yii::$app->name;
#print_r(Yii::$app->user->identity);
#echo (password_hash("admin01-phingosoft.com", PASSWORD_DEFAULT))."\n";
#echo Yii::$app->request->baseUrl;
#echo Yii::$app->basePath;
#echo \Yii::$app->urlManager->baseUrl;
?>
<div class="site-index">
    <div class="body-content">

        <div class="row">
              <div class="col-md-4 col-lg-4">
                <!-- Widget: user widget style 1 -->
                <div class="box box-widget widget-user-2">
                  <!-- Add the bg color to the header using any of the bg-* classes -->
                  <div class="widget-user-header bg-yellow">
                    <div class="widget-user-image">
                      <img class="img-circle" src="<?=Yii::$app->request->baseUrl.'/user/'.(Yii::$app->user->isGuest? 'imgGuest.png' : Yii::$app->user->identity->image)?>" style="height: 80px;">
                    </div>
                    <!-- /.widget-user-image -->
                    <h3 class="widget-user-username"><?= Yii::$app->user->isGuest ? 'การขอรถทั้งหมด' : Yii::$app->user->identity->fullname;?></h3>
                    <h5 class="widget-user-desc">ออกรถวันนี้</h5>
                  </div>
                  <div class="box-footer no-padding">
                    <ul class="nav nav-stacked">
                      <?php
                        foreach ($Eday as $e) {
                          echo '<li><a href="'.yii\helpers\Url::to(['jongcar/view','id'=>$e['id']]).'">'.$e['station'].' ผดส. '.$e['cno'].' คน <span class="pull-right badge bg-blue">'.date('H:i',  strtotime($e['rab_date'])).'</span></a></li>';
                        }
                      ?>
                    </ul>
                  </div>
                </div>
                <!-- /.widget-user -->
            </div>
            <div class="col-lg-8">

              <div class="box box-info box-solid">
                <div class="box-header with-border">
                  <h3 class="box-title">ปฏิทินการใช้รถ</h3>

                  <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                  </div>
                  <!-- /.box-tools -->
                </div>
                <!-- /.box-header -->
                <div class="box-body" style="display: block;">
                  <?=\yii2fullcalendar\yii2fullcalendar::widget(array(
          				#'events' => $events,
          				'options' => [
          				  'lang' => 'th',
          				],
                  'ajaxEvents' => Url::to(['/site/jsoncalendar'])
          				));
          			  ?>
                </div>
                <!-- /.box-body -->
              </div>
            </div>
        </div>

    </div>
</div>
