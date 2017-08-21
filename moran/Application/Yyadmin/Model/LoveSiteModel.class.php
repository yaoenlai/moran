<?php
namespace Yyadmin\Model;
use Think\Model;

class LoveSiteModel extends Model
{
    public function wheres($data = array())
    {
        //sid
        if(!empty($data['sid']))
        {
            $where['ue_love_site.id'] = array("EQ", $data['sid']);
        } else {
            if(session("admin_user.grade") != '1')
            {
                $where['ue_love_site.id'] = array('IN', session("admin_user.sid"));
            }
        }
        if(!empty($data['create_date']) || !empty($data['end_date'])){
            //开始时间
            if(!empty($data['create_date'])){
                $create_time = strtotime($data['create_date']);
            } else {
                $create_time = '';
            }
            //结束时间
            if(!empty($data['end_date'])){
                $end_time = strtotime($data['end_date']);
            } else {
                $end_time = time();
            }
            $where['_complex'][]['ue_admin_user.create_time'] = array("BETWEEN", array($create_time,$end_time));
            $where['_complex'][]['ue_recharge_index.create_time'] = array("BETWEEN", array($create_time,$end_time));
            $where['_complex']['_logic'] = 'OR';
        }
        return $where;
    }
    
}