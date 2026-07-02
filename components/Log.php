<?php
class Log {
 
// public static function datethai( ตัวแปรที่รับ ) {}
public static function save_log($url_address,$log_type,$reference) {

    $username=Yii::$app()->user->username;
    //$url_address=$this->getId();
    $ip_address=$_SERVER['REMOTE_ADDR'];
    //$log_type=$this->getAction()->getId();
    $log_date=date('Y-m-d H:i:s');
    //$reference=$this->logMessage;
    // function: insert('ชื่อตาราง', array('ชื่อ Fields' => 'ข้อมูลที่ต้องการ'));
    $conn = Yii::$app()->db->createCommand()
        ->insert('log', array(
            'username' => $username,
            'url_address' => $url_address,
            'ip_address' => $ip_address,
            'log_type' => $log_type,
            'log_date' => $log_date,
            'log_fulltext' => 'User '.$username.' '.$log_type.' [ '.$reference.' ] "'.$url_address.'"   From '.$ip_address,
        ));
    

    return "Log Saved";
   }
}
