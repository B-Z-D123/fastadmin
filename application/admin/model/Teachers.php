<?php

namespace app\admin\model;

use think\Model;


class Teachers extends Model
{

    

    

    // 表名
    protected $name = 'teachers';
    
    // 自动写入时间戳字段
    protected $autoWriteTimestamp = false;

    // 定义时间戳字段名
    protected $createTime = false;
    protected $updateTime = false;
    protected $deleteTime = false;

    // 追加属性
    protected $append = [
        'sex_text'
    ];
    

    
    public function getSexList()
    {
        return ['女' => __('女'), '男' => __('男')];
    }


    public function getSexTextAttr($value, $data)
    {
        $value = $value ?: ($data['sex'] ?? '');
        $list = $this->getSexList();
        return $list[$value] ?? '';
    }




    public function departments()
    {
        return $this->belongsTo('Departments', 'd_no', 'd_no', [], 'LEFT')->setEagerlyType(0);
    }
}
