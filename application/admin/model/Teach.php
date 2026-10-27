<?php

namespace app\admin\model;

use think\Model;


class Teach extends Model
{



    // 表名
    protected $name = 'teach';

    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [

    ];



    public function teachers()
    {
        return $this->belongsTo('Teachers', 't_no', 't_no', [], 'LEFT')->setEagerlyType(0);
    }

    public function course()
    {
        return $this->belongsTo('Course', 'c_no', 'c_no', [], 'LEFT')->setEagerlyType(0);
    }

    public function class1()
    {
        return $this->belongsTo('Class1', 'cl_no', 'cl_no', [], 'LEFT')->setEagerlyType(0);
    }
}
