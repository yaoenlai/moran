<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Love\Shell;
use Love\Shell\Shell;
use Addons\Pay\ThinkPay\Pay;
/**
 * 聚合接口公共控制器
 * @author zxq
 */
class PublicShell extends Shell {


	/**
     * 参数配置接口
	 * >php.exe shell.php Love/Public/userFormat/file/1
     * @author zxq
     */
	public function userFormat(){
		$startTime =time();
		//准备地区数据
		$areaModel = D('Admin/Area');
		$areaData = $areaModel->select();
		foreach ($areaData as $key => $value) {
			$areaArr[$value['areaname']] = $value;
		}//dump($areaArr);exit;
		//准备预设参数数据
		/*
		$ParamterModel = D('Love/Paramter');
		$ParamterData = $ParamterModel->select();
		foreach ($ParamterData as $key => $value) {
			$ParamterArr[$value['ptname']] = array(
				'ptvalue' => \Common\Util\Think\Str::parseAttr($value['ptvalue']),
				'ptdec' => $value['ptdec'], 
				'pttype' => $value['pttype'], 
			);
		}
		*/
		$filePath = APP_PATH.'Love/Shell/json';
		$file = I('file');
		if(empty($file) && is_dir($filePath)){
			$fileArr = scandir($filePath);
			if(empty($fileArr))
				exit(" 无可处理的文件！\r\n<br />");
			$allUsers = 0;
			foreach ($fileArr as $key => $value) {
				if(is_file($filePath.'/'.$value)){
					//echo "{$filePath}/{$value} \r\n<br />"; 开始解析json
					$jsonfile = "{$filePath}/{$value}";
					$json = file_get_contents($jsonfile);
					$jsonArr = json_decode($json,true);
					$listData = $jsonArr['listData'];
					$onUsers = count($listData);
					//print_r($listData);exit;
					foreach ($listData as $listk => $listv) {
						//开始注册用户admin_user
						$userModel = D('Love/User');
						//组装写死数据
						$userData['user_type'] = 1;
						$userData['username'] = 'Love_'.date('ymdhis').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), -7);
						$userData['password'] = 'qqq111';
						$userData['reg_type'] = 'caiji';
						
						$userData['nickname'] = $listv['userBase']['nickName'];//昵称
						//头像处理
						$thumbnailUrl = $listv['userBase']['image']['thumbnailUrl'];
						$imageUrl = $listv['userBase']['image']['imageUrl'];
						$res = D('Admin/Upload')->saveUrlfile($imageUrl);
						$userData['avatar'] = $res['id'];
						$userData['gender'] = '-1';//$listv['userBase']['gender'];//性别 女-1 男 1
						$data = $userModel->create($userData);
			            if ($data) {
			                $uid = $userModel->add();
			                $profileData['uid'] = $uid;//uid
			                $profileData['sid'] = 0;//siteID 0时为系统导入用户
							$profileData['astro'] = $listv['userBase']['constellation'];//星座
							$profileData['age'] = $listv['userBase']['age'];//年龄
							$profileData['ageyear'] = date('Y')-$listv['userBase']['age'];//年
							$profileData['agemonth'] = rand(1, 12);
							$profileData['ageday'] = rand(1,28);
							$profileData['birthday'] = "{$profileData['ageyear']}-{$profileData['agemonth']}-{$profileData['ageday']}";
							$profileData['height'] = $listv['userBase']['height'];//身高
							$profileData['weight'] = $listv['userBase']['weight'];//体重
							$profileData['info'] = $listv['info'];//一句话介绍
							$profileData['monolog'] = $listv['userBase']['monologue'];//独白
							$profileData['molstatus'] = empty($profileData['monolog']) ? 0 : 1;//独白
							$profileData['salary'] = $listv['userBase']['income'];//收入
							$profileData['jobs'] = $listv['userBase']['work']<1 ? 1:$listv['userBase']['work'] ;//工作
							$profileData['education'] = $listv['userBase']['diploma'];//学历
							$provinceid = $areaArr[$listv['userBase']['area']['provinceName']]['id'];
							$profileData['provinceid'] = $provinceid;//地区
							$profileData['cityid'] = $provinceid;//城市
							$profile = D('Love/Profile')->add($profileData);
							
							$attrData['uid'] = $uid;//uid
							$attrData['recall'] = $listv['userBase']['isAuthentication'];//是否认证
							$attr = D('Love/Attr')->add($attrData);
                        }else{
                        	echo " 创建新用户失败\r\n<br />";
							var_dump($userModel);exit;
                        }
						/* 
	            [userBase] => Array
	                (
	                    [constellation] => 3//星座
	                    [isAuthentication] => 1//是否认证
	                    [nickName] => 鱼丸没有粗面
	                    [age] => 25
	                    [height] => 162
	                    [monologue] => //独白
	                    [image] => Array
	                        (
	                            [thumbnailUrl] => http://ptw.youyuan.com/resize/photo/n/n/n/y/150/150/201701/30/12/08/1485749337903A6E90FB_c.jpg
	                            [imageUrl] => http://ptw.youyuan.com/resize/photo/n/n/n/y/300/300/201701/30/12/08/1485749337903A6E90FB_c.jpg
	                           
	                        )
	                    [gender] => 1//1女
	                    [distance] => //距离
	                    [income] => 5//收入
	                    [work] => 0//工作
	                    [diploma] => 2//文平 学历
	                    [area] => Array
	                        (
	                            [provinceName] => 北京
	                            [cityName] => 市区
	                            [areaName] => 
	                        )
	
	                    [weight] => 196//体重
	                )
	
	            [info] => 我叫鱼丸没有粗面
	            */
					}
					$allUsers += $onUsers;
					//处理完删除文件
					if(unlink($jsonfile)){
						echo " 删除文件{$jsonfile}成功\r\n<br />";
					}else{
						echo " 删除文件{$jsonfile}失败\r\n<br />";
					}
				}else{
					echo "'{$value}'不是文件\r\n<br />";
				}
			}
		}else{
			$jsonfile = $filePath.'/'.I('file').'.txt';
			if(is_file($filePath.'/'.$value)){
				$json = file_get_contents();
				dump(json_decode($json));
			}else{
				echo "文件不存在\r\n<br />";
			}
		}
		$useTime =(time()-$startTime)/60;
		echo "耗时{$useTime}分钟,导入了{$allUsers}个用户\r\n";
	}
}
