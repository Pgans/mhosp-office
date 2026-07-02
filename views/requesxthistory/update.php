<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model app\models\Requesxthistory */

$this->title = Yii::t('app', 'แก้ไข การขอประวัติการรักษา: {name}', [
    'name' => $model->id,
]);
$this->params['breadcrumbs'][] = ['label' => Yii::t('app', 'ขอประวัติ'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('app', 'แก้ไข');
?>
<div class="requesxthistory-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
<!-- view-modal -->
<div class="modal fade" id="view-modal" tabindex="-1" role="dialog" aria-labelledby="view-modal-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="view-modal-label">View Details</h4>
            </div>
            <div class="modal-body">
                <!-- Content loaded dynamically using jQuery -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- edit-modal -->
<div class="modal fade" id="edit-modal" tabindex="-1" role="dialog" aria-labelledby="edit-modal-label">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                <h4 class="modal-title" id="edit-modal-label">Edit Details</h4>
            </div>
            <div class="modal-body">
                <!-- Content loaded dynamically using jQuery -->
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
