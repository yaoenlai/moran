<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: ijry <ijry@qq.com> <http://www.corethink.cn>
// +----------------------------------------------------------------------

namespace Addons\Weather\Controller;
use Home\Controller\AddonController;

class WeatherController extends AddonController{

    //获取天气列表
    public function getList(){
        $lists = S('Weather_content');
        if(!$lists){
            $config = \Common\Controller\Addon::getConfig('Weather');
            $url = "http://api.map.baidu.com/telematics/v2/weather?location=".$config['city']."&ak=".$config['ak']."";
            $result = file_get_contents($url);
            $content = simplexml_load_string($result);
            $lists['city'] = (string)$content->currentCity;
            $lists['showday'] = $config['showday'];
            foreach($content->results->result as $result){
                $lists['date'][] = (string)$result->date;
                $lists['weather'][] = (string)$result->weather;
                $lists['wind'][] = (string)$result->wind;
                $lists['temperature'][] = (string)$result->temperature;
                $lists['pictureUrl'][] = (string)$result->dayPictureUrl;
            }
        }
        if($lists){
            $this->success('成功', '', array('data'=>$lists));
        }else{
            $this->error('天气列表失败');
        }
    }
}
