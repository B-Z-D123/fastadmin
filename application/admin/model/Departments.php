<?php

namespace app\admin\model;

use think\Model;


class Departments extends Model
{

    

    

    // 表名
    protected $name = 'departments';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];

    public function getDepartmentList()
    {
        //select *from departments
        $list = collection($this->select())->toArray();
        // var_dump($list);die;
        return $list;
    }

    







}
