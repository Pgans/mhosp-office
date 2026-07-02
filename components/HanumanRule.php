<?php
/**
 * Created by HanumanIT Co., Ltd.
 * User: Manop Kongoon
 * Date: 21/1/2560
 * Time: 16:41
 */

namespace app\components;


use yii\filters\AccessRule;

class HanumanRule extends AccessRule
{
    protected function matchRole($user)
    {
        if(empty($this->roles)){
            return true;
        }
        foreach($this->roles as $role){
            if($role === '?'){
                if($user->getIsGuest()){
                    return true;
                }
            }else if($role === '@'){
                if(!$user->getIsGuest()){
                    return true;
                }
            }else if(!$user->getIsGuest() && $role === $user->identity->role){
                return true;
            }
        }
        return false;
    }
}