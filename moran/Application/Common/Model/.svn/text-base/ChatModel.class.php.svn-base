<?php

/**
 * @Created by kevin(askyiwang@gmail.com).
 * @User: kevin
 * @Date: 2017/4/5
 * @Time: 19:04
 * @description
 */
namespace Common\Model;
use Think\Model\MongoModel;

class ChatModel extends MongoModel
{
    protected $trueTableName = 'chat';
    public function __contruct($db_name) {
        $this->trueTableName = $db_name;
    }
    protected $dbName = 'love';
    protected $connection = 'MONGO';
    protected $_idType = self::TYPE_INT;
    protected $_autoinc = true;
}