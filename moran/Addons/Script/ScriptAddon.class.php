<?php
// +----------------------------------------------------------------------
// | wiera [ Simple Efficient Excellent ]
// +----------------------------------------------------------------------
// | Copyright (c) 2014 http://www.uera.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: zxq
// +----------------------------------------------------------------------
namespace Addons\Script;
use Common\Controller\Addon;
/**
 * 额外JS插件
 * @zxq
 */
class ScriptAddon extends Addon {
    /**
     * 插件信息
     * @author zxq
     */
    public $info = array(
        'name'        => 'Script',
        'title'       => '额外JS插件',
        'description' => '额外JS插件',
        'status'      => 1,
        'author'      => 'Wiera',
        'version'     => '1.4.0',
    );

    /**
     * 插件安装方法
     * @author zxq
     */
    public function install() {
        return true;
    }

    /**
     * 插件卸载方法
     * @author zxq
     */
    public function uninstall() {
        return true;
    }

    /**
     * 实现的PageFooter钩子方法
     * @author zxq
     */
    public function PageFooter($param) {
        $addons_config = $this->getConfig();
        $deny = \Common\Util\Think\Str::parseAttr($addons_config['deny']);
        if ($addons_config['status']) {
            if (!in_array($_SERVER['REQUEST_URI'], $deny)) {
                echo $addons_config['script'];
            }
        }
    }
}
