<?php
use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */
if (Yii::$app->controller->action->id === 'login') {
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    if (class_exists('app\assets\AppAsset')) {
        app\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);

   // $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@app/themes/adminlte');
    ?>
   <?php $this->registerCss("
    div.required label.control-label:after {
        content: \" *\";
        color: red;
    }
    ")?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <link rel="shortcut icon" href="<?php echo Yii::$app->request->baseUrl; ?>/favicon.ico" type="image/x-icon" />
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>

        <link href='https://fonts.googleapis.com/css?family=Kanit:400,300&subset=thai,latin' rel='stylesheet' type='text/css'>
        <style>
            body {
                font-family: 'Kanit', sans-serif;
            }

            h1 {
                font-family: 'Kanit', sans-serif;
            }

            h2 {
                font-family: 'Kanit', sans-serif;
            }

            h3 {
                font-family: 'Kanit', sans-serif;
            }

            h4 {
                font-family: 'Kanit', sans-serif;
            }

            h5 {
                font-family: 'Kanit', sans-serif;
            }

            div {
                font-family: 'Kanit', sans-serif;
            }

            /* a {
                color: #009587;
            } */

            h5.thick {
                font-weight: bold;
            }
        </style> 
        <style>
		.box-title {
		color: white; /* สีของตัวหนังสือเป็นสีขาว */
		font-size: 18px; 
		}

            body {
                font-family: 'Kanit', sans-serif;
            }

            h1 {
                font-family: 'Kanit', sans-serif;
            }

            h2 {
                font-family: 'Kanit', sans-serif;
            }

            h3 {
                font-family: 'Kanit', sans-serif;
            }

            h4 {
                font-family: 'Kanit', sans-serif;
            }

            h5 {
                font-family: 'Kanit', sans-serif;
            }

            div {
                font-family: 'Kanit', sans-serif;
            }


            /* a {
                color: #009587;
            } */

            h5.thick {
                font-weight: bold;
            }
            #grad0 {
  background-image: linear-gradient(to right, violet, cyan);
}
#grad1 {
  background-image: linear-gradient(to right, indigo, cyan);
}
#grad01 {
  background-image: linear-gradient(to right, green , violet);
}
#grad02 {
  background-image: linear-gradient(to right, green , cyan);
}
#grad3 {
  background-image: linear-gradient(to right, violet , purple);
}
#grad-fa {
  background-image: linear-gradient(to right,teal , cyan);
}
#grad4 {
  background-image: linear-gradient(to right, red,orange,yellow,green,blue,indigo,violet);
}
#grad2 {
  background-image: linear-gradient(to right, cyan, yellow);
}
#grad5 {
  background-image: linear-gradient(180deg, red, yellow);
}
#grad6 {
  background-image: linear-gradient(180deg, violet, cyan);
}
#grad7 {
  background-image: linear-gradient(180deg, blue, cyan);
}
#grad8 {
  background-image: linear-gradient(to right, purple, violet );
}

#grad {
  background: red; /* For browsers that do not support gradients */
  background: -webkit-linear-gradient(left,rgba(255,0,0,0),rgba(255,0,0,1)); /*Safari 5.1-6*/
  background: -o-linear-gradient(right,rgba(255,0,0,0),rgba(255,0,0,1)); /*Opera 11.1-12*/
  background: -moz-linear-gradient(right,rgba(255,0,0,0),rgba(255,0,0,1)); /*Fx 3.6-15*/
  background: linear-gradient(to right, rgba(255,0,0,0), rgba(255,0,0,1)); /*Standard*/
}
#grad11 {
    height: 55px;
    background: -webkit-linear-gradient(left, red, orange, yellow, green, blue, indigo, violet); /* For Safari 5.1 to 6.0 */
    background: -o-linear-gradient(left, red, orange, yellow, green, blue, indigo, violet); /* For Opera 11.1 to 12.0 */
    background: -moz-linear-gradient(left, red, orange, yellow, green, blue, indigo, violet); /* For Fx 3.6 to 15 */
    background: linear-gradient(to right, red, orange, yellow, green, blue, indigo, violet); /* Standard syntax (must be last) */
}
  .table-hover tbody tr:hover{
    background-color: #f7c0ba;
    }
	 .table-hover1 tbody tr:hover{
    background-color: #CCE9FB;
    }
	 .table-hover2 tbody tr:hover{
    background-color: #B3F7CE;
    }
        </style>               
    </head>
    <body class="skin-blue sidebar-mini">
    <?php $this->beginBody() ?>
    <div class="wrapper">

        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset]
        )
        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>

    </div>

    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
// In your main layout file (e.g., main.php)
<?php if (Yii::$app->session->hasFlash('alert')): ?>
    <div class="alert <?= Yii::$app->session->getFlash('alert')['options']['class'] ?>">
        <?= Yii::$app->session->getFlash('alert')['body'] ?>
    </div>
<?php endif; ?>

<?php if (Yii::$app->session->hasFlash('error')): ?>
    <div class="alert <?= Yii::$app->session->getFlash('error')['options']['class'] ?>">
        <?= Yii::$app->session->getFlash('error')['body'] ?>
    </div>
<?php endif; ?>

