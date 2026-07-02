<?php

namespace app\modules\apdcard\controllers;

use yii\web\Controller;

/**
 * Default controller for the `apdcard` module
 */
class DefaultController extends Controller
{
    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }
}
