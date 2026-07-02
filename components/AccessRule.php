<?php

namespace app\components;
use app\models\User;

class AccessRule extends \yii\filters\AccessRule {
 
    protected function matchRole($user){
         
        if(empty($this->roles)){
            return true;
        }
        foreach ($this->roles as $role){
             
            /* ถ้า role เท่ากับ ? และ ผู้ใช้ยังไม่ได้ล๊อกอิน */
            if($role == '?' && $user->getIsGuest()){
                return true;
            }
             
            /*
             * ถ้า role เท่ากับ @ และ ผู้ใช้ยังล๊อกอินสำเร็จ
             */
             elseif($role == User::ROLE_USER && !$user->getIsGuest()){
                    return true;
            // elseif($role == '@' && !$user->getIsGuest()){
            //     return true;
            }
             
            /*
             * หรือ ถ้า ผู้ใช้ล๊อกอินสำเร็จ และ role เท่ากับ role ของ ผู้ใช้ที่ล๊อกอินอยู่
             */
            elseif(!$user->getIsGuest() && $role == $user->identity->level_user){
                return true;
            }
             
        }
        return false;
    }
}                       
