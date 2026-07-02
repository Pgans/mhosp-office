<?php

class BookingModels extends CActiveRecord
{

static function model($className = __CLASS__)
{
return parent::model($className);
}

function tableName()
{
return 'booking';
}

public function rules()
{
return array(
//array('old_password, new_password, repeat_password', 'required', 'on' => 'changePwd'),
array('booking_date_start,booking_time_start,booking_date_end,booking_time_end', 'required', 'on' => 'checkDate'),
array('booking_date_start,booking_time_start,booking_date_end,booking_time_end', 'findDdate', 'on' => 'checkDate'),
//array('repeat_password', 'compare', 'compareAttribute' => 'new_password', 'on' => 'changePwd'),
//array('new_username', 'required', 'on' => 'createUser'),
//array('new_username', 'unique', 'message' => 'username is already exist, please change'),
//array('new_username', 'findUsername', 'on' => 'createUser'),
//array('member_username,', 'required', 'on' => 'createUser'),
//array('member_username', 'unique', 'message' => 'Username is already exist', 'on' => 'createUser'),
);
}

function relations()
{
return array('join_department' => array(self::BELONGS_TO, 'DepartmentModels', 'booking_department'),
'join_booking_room' => array(self::BELONGS_TO, 'Booking_roomModels', 'booking_room_id'),
'join_booking_type' => array(self::BELONGS_TO, 'Booking_typeModels', 'booking_type_id'),
'join_booking_manage_type' => array(self::BELONGS_TO, 'Booking_manage_typeModels', 'booking_manage_type_id'),
'join_booking_budget' => array(self::BELONGS_TO, 'Booking_budgetModels', 'booking_budget'),
'join_booking_equipment' => array(self::BELONGS_TO, 'Booking_equipmentModels', 'booking_equipment_id'),
'join_booking_table_type' => array(self::BELONGS_TO, 'Booking_table_typeModels', 'booking_table_type_id'),
'join_member' => array(self::BELONGS_TO, 'MemberModels', 'booking_member'),);
}

public function findDdate($attribute, $params)
{
//$date_start = BookingModels::model()->findByAttributes(array('booking_date_start' => $this->booking_date_start));
$check_Ddate = Yii::app()->db->createCommand('select * from booking where concat(booking_date_start," ",booking_time_start) between "' . $this->booking_date_start . ' ' . $this->booking_time_start . '" and "' . $this->booking_date_start . ' ' . $this->booking_time_start . '"
or concat(booking_date_end," ",booking_time_end) between "' . $this->booking_date_end . ' ' . $this->booking_time_end . '" and "' . $this->booking_date_end . ' ' . $this->booking_time_end . '"')->queryAll();
if ($check_Ddate)
{
$this->addError($attribute, 'ช่วงเวลานี้ถูกจองไปแล้ว กรุณาเลือกใหม่!');
}
}

/* static function getComputername()
{
$getModel = ComputerModels::model()->findall();
$arr = array();
foreach ($getModel as $r)
{
$arr[$r->computer_id] = $r->computer_name;
}
return $arr;
}

public function getComname()
{
return $this->computer_name;
} */
}
