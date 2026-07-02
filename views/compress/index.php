<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\JsExpression;

$this->title = 'GZIP / PIGZ Monitor';
?>

<h1><?= Html::encode($this->title) ?></h1>

<table class="table table-bordered table-hover">
    <thead>
        <tr>
            <th>Host</th>
            <th>PID</th>
            <th>Elapsed Time</th>
            <th>Command</th>
            <th>Action</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($processes as $p): ?>
            <tr>
                <td><?= Html::encode($p['host']) ?></td>
                <td><?= Html::encode($p['pid']) ?></td>
                <td><?= Html::encode($p['etime']) ?></td>
                <td><?= Html::encode($p['cmd']) ?></td>
                <td>
                    <?= Html::button('Kill', [
                        'class' => 'btn btn-danger btn-sm',
                        'onclick' => new JsExpression("
                            if (confirm('Kill process {$p['pid']} on {$p['host']}?')) {
                                $.post('" . Url::to(['compress/kill']) . "', {
                                    host: '{$p['host']}',
                                    user: '{$p['user']}',
                                    pid: '{$p['pid']}'
                                }, function(res) {
                                    alert(res.message);
                                    location.reload();
                                });
                            }
                        ")
                    ]) ?>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
