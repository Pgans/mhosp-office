<?php
namespace app\components;

use app\models\Requests;
use app\models\Recomment;
use Yii;
use yii\helpers\Url;

class Main
{
    public function simpleSlug($str)
    {
        $slug = preg_replace('@[\s!:;_\?=\\\+\*/%&#]+@', '-', $str);
        $slug = mb_strtolower($slug, Yii::$app->charset);
        $slug = trim($slug, '-');
    
        return $slug;
    }

    public function line($id = null, $type = null)
    {
       if (($model = Requests::findOne($id)) !== null) {
            return $model;
       }

     }

}
        $line_api = 'https://notify-api.line.me/api/notify';
       // $line_token = '7vRd5JQNbxadXQa7trZbK7VTvR6fPFGErqCdJH8ZDyY';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,"https://notify-api.line.me/api/notify");
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $message);
        // follow redirects
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-type: application/x-www-form-urlencoded',
            'Authorization: Bearer '.$line_token,
        ]);
        // receive server response ...
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $server_output = curl_exec ($ch);

        curl_close ($ch);
        //var_dump($server_output);
        //echo Yii::getAlias('@webroot').'/images/programmerthailand_social.jpg';
   


  
