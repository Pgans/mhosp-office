<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;
use app\models\UploadCSV;
use yii\web\UploadedFile;
use yii2fullcalendar\yii2fullcalendar;


class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
  public function actions()
{
    return [
        // ลบ 'error' ออก ไม่ต้องมีแล้ว
        'captcha' => [
            'class' => 'yii\captcha\CaptchaAction',
            'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
        ],
    ];
}

public function actionError()
{
    $exception = Yii::$app->errorHandler->exception;
    if ($exception !== null) {
        if ($exception instanceof \yii\web\ForbiddenHttpException) {
            return $this->render('error', [
                'name'    => 'ไม่มีสิทธิ์เข้าถึง (#403)',
                'message' => 'คุณไม่ได้รับอนุญาตให้ดำเนินการนี้',
            ]);
        }
        return $this->render('error', [
            'name'    => $exception->getName(),
            'message' => $exception->getMessage(),
        ]);
    }
}

    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionCarlendar()
    {
        //echo Yii::getAlias('@webroot');
        $sql = null;
        // if(!Yii::$app->user->isGuest && Yii::$app->user->identity->leveled=='3'){
        //   $sql = " AND m.us_car='".Yii::$app->user->identity->id."'";
        // }
        $sql = Yii::$app->db->createCommand("SELECT r.id, r.description, r.destination,
        r.date_start AS start,
        r.date_end AS end,
        p.firstname AS fn,
        p.lastname AS ln
        FROM rental r
        LEFT JOIN vehicle v ON v.vehicle_id = r.vehicle_id
        LEFT JOIN person p ON p.user_id = r.user_id
        WHERE r.date_start LIKE'".date('Y-m-d')."%' ".$sql." 
        AND r.status = '1'");
        //$sql = Yii::$app->db->createCommand("SELECT j.id,j.station,j.cno,j.rab_date FROM jong_car as j INNER JOIN map_car as m ON(j.id=m.jongid) WHERE j.rab_date LIKE'".date('Y-m-d')."%' ".$sql." AND j.status='1'");
        $Eday = $sql->queryAll();
        #return $this->render('index', ['events'=>$task, 'Eday'=>$Eday]);
        return $this->render('carlendar', ['Eday'=>$Eday]);
    }

    public function actionJsoncalendar($start=NULL,$end=NULL,$_=NULL){

      \Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

      $c = null;

      if(!Yii::$app->user->isGuest && Yii::$app->user->identity->level_user=='USER'){
        $c = " AND us_car='".Yii::$app->user->identity->id."' ";
      }
      $c = Yii::$app->db->createCommand("SELECT * FROM rental WHERE DATE_FORMAT(date_start,'%Y-%m-%d') BETWEEN '{$start}' AND '{$end}' AND status='1' {$c}");
      $events = $c->queryAll();
      $task=[];
      foreach ($events as $eve) {
          $event = new \yii2fullcalendar\models\Event();
          $event->id = $eve['id'];
          $event->title = $eve['type'].'('.$eve['regis'].'): '.$eve['description'];
          $event->start = date('Y-m-d\TH:i:s\Z',strtotime($eve['date_start']));
          $event->end = date('Y-m-d\TH:i:s\Z',strtotime($eve['date_end']));
          #$event->end = date('Y-m-d\TH:i:s\Z',strtotime($eve['song_date'] . "+1 days"));
          $event->allDay = true;
          $event->url = Url::to(['/rental/view', 'id'=>$eve['id']]);
          if($eve['area']=='I') $event->color = '#378006';
          $task[] = $event;

      }

      return $task;
  }

  ############################################################
	public function actionInfo()
    {
        return $this->render('phpinfo');
    }


    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
	
	public function actionImport()
    {
        $model = new UploadCSV();
			$filename='';
			if (Yii::$app->request->isPost) {
					$model->file = UploadedFile::getInstance($model, 'file');
					if ($model->validate()) {
						$model->file->saveAs('upload/'. $model->file->baseName.'.'.$model->file->extension);
						$filename=$_FILES['UploadCSV']['name']['file']; //phpinfo();
					}
			}
		return $this->render('import', ['model' => $model,'filename'=>$filename]);
	}
}
?>