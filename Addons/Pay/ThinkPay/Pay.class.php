<?php
/**
 * 通用支付接口类
 * @author yunwuxin<448901948@qq.com>
 * @alter jry <59821125@qq.com> <http://www.corethink.cn>
 */
namespace Addons\Pay\ThinkPay;
 /**
 * 通用支付接口类
 */
class Pay {
    /**
     * 支付驱动实例
     * @var Object
     */
    private $payer;

    /**
     * 配置参数
     * @var type
     */
    private $config;

    /**
     * 构造方法，用于构造支付实例
     * @param string $driver 要使用的支付驱动
     * @param array  $config 配置
     */
    public function __construct($driver, $config = array()) {
        /* 配置 */
        if (\Common\Util\Device::isWap() && $driver === 'alipay' && !C('IS_API')) {
            $driver = 'aliwappay';
        }
        $pos = strrpos($driver, '\\');
        $pos = $pos === false ? 0 : $pos + 1;
        $apitype = strtolower(substr($driver, $pos));
        /* 设置支付驱动 */
        $class = strpos($driver, '\\') ? $driver : 'Addons\\Pay\\ThinkPay\\Pay\\Driver\\' . ucfirst(strtolower($driver));
        $this->setDriver($class, $config);
    }

    /**
	 * @param int $module 模块id，最大值99，例如6为充值模块
     * @param int $aggregation 渠道id,最大为999
	 * 生成订单号
     * 可根据自身的业务需求更改
     */
    public static function createOrderNo($module = 6,$aggregation = 1) {
        /*
        $year_code = array('A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J');
        return 'CT'.$year_code[intval(date('Y')) - 2010] .strtoupper(dechex(date('m'))) .
            date('d') .substr(time(), -5) . substr(microtime(), 2, 5) . sprintf('d', rand(0, 99));
		*/
		//1606211651285051102
		$orderid = date('ymd').sprintf('%02s', $module).sprintf('%03s', $aggregation).date('His').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), -7);
		return $orderid;
    }

    /**
     * 设置支付页面
     * @param array $pay_data 支付参数
     */
    public function buildRequestForm($pay_data) {
        $check = $this->payer->check();
        if ($check !== false) {
            return $this->payer->buildRequestForm($pay_data);
        } else {
            E(M("Pay")->getDbError());
        }
    }

    /**
     * 设置支付驱动
     * @param string $class 驱动类名称
     */
    private function setDriver($class, $config) {
        $this->payer = new $class($config);
        if (!$this->payer) {
            E("不存在支付驱动：{$class}");
        }
    }

    public function __call($method, $arguments) {
        if (method_exists($this, $method)) {
            return call_user_func_array(array(&$this, $method), $arguments);
        } elseif (!empty($this->payer) && $this->payer instanceof Pay\Pay && method_exists($this->payer, $method)) {
            return call_user_func_array(array(&$this->payer, $method), $arguments);
        }
    }
}
