<?php

namespace app\index\controller;

use app\common\controller\Frontend;
use think\Db;

/**
 * 学校官网首页
 */
class Index extends Frontend
{
    protected $noNeedLogin = '*';
    protected $noNeedRight = '*';
    protected $layout = 'site';

    public function index()
    {
        $newsList = Db::name('news')
            ->order('createtime desc,id desc')
            ->limit(4)
            ->select();
        foreach ($newsList as &$item) {
            $item = $this->formatNews($item);
        }
        unset($item);

        $featured = $newsList ? array_shift($newsList) : null;
        $newsRest = array_slice($newsList, 0, 3);

        $courses = Db::name('course')->order('id asc')->limit(6)->select();
        foreach ($courses as &$course) {
            $hours = (int)$course['hours'];
            $course['percent'] = $hours > 0 ? min(100, (int)round($hours / 160 * 100)) : 0;
        }
        unset($course);

        $teachers = Db::name('teachers')
            ->alias('t')
            ->join('departments d', 't.d_no=d.d_no', 'LEFT')
            ->field('t.*,d.d_name')
            ->order('t.id asc')
            ->limit(4)
            ->select();
        foreach ($teachers as &$teacher) {
            $teacher['initial'] = $this->firstChar($teacher['t_name']);
            $teacher['d_name'] = $teacher['d_name'] ?: '未分配系部';
        }
        unset($teacher);

        $departments = Db::name('departments')->order('id asc')->select();
        $classes = Db::name('class1')->order('cl_no asc')->select();
        $teacherRows = Db::name('teachers')->field('d_no')->select();

        $classMap = [];
        foreach ($classes as $class) {
            $classMap[$class['d_no']][] = $class['cl_name'];
        }
        $teacherCount = [];
        foreach ($teacherRows as $row) {
            $teacherCount[$row['d_no']] = isset($teacherCount[$row['d_no']]) ? $teacherCount[$row['d_no']] + 1 : 1;
        }
        foreach ($departments as &$department) {
            $names = isset($classMap[$department['d_no']]) ? $classMap[$department['d_no']] : [];
            $department['class_count'] = count($names);
            $department['teacher_count'] = isset($teacherCount[$department['d_no']]) ? $teacherCount[$department['d_no']] : 0;
            $department['class_text'] = $names ? implode(' / ', array_slice($names, 0, 3)) : '暂无班级';
        }
        unset($department);

        // 首页不展示暂无班级、暂无教师的空系部
        $departments = array_values(array_filter($departments, function ($department) {
            return $department['class_count'] > 0 || $department['teacher_count'] > 0;
        }));

        $stats = [
            'departments' => count($departments),
            'teachers'    => Db::name('teachers')->count(),
            'courses'     => Db::name('course')->count(),
            'students'    => Db::name('students')->count(),
        ];

        $this->view->assign([
            'title'       => '首页',
            'nav'         => 'home',
            'featured'    => $featured,
            'newsRest'    => $newsRest,
            'courses'     => $courses,
            'teachers'    => $teachers,
            'departments' => $departments,
            'stats'       => $stats,
        ]);

        return $this->view->fetch();
    }

    /**
     * 格式化新闻数据
     */
    private function formatNews($item)
    {
        $types = [
            1 => '关于我们',
            2 => '学术课程',
            3 => '校园生活',
            4 => '招生入学',
            5 => '联系我们',
        ];

        $type = (int)$item['typelist'];
        $item['type_name'] = isset($types[$type]) ? $types[$type] : '新闻动态';
        $item['summary'] = $this->excerpt(isset($item['remark']) && $item['remark'] ? $item['remark'] : $item['content']);
        $item['date'] = $item['createtime'] ? date('Y-m-d', $item['createtime']) : date('Y-m-d');
        $item['day'] = $item['createtime'] ? date('d', $item['createtime']) : '--';
        $item['month'] = $item['createtime'] ? date('Y.m', $item['createtime']) : '';

        $image = isset($item['image']) ? $item['image'] : '';
        $item['has_image'] = $image && is_file(ROOT_PATH . 'public' . $image);
        $item['image_url'] = $item['has_image'] ? cdnurl($image) : '';

        return $item;
    }

    /**
     * 截取摘要
     */
    private function excerpt($text, $length = 90)
    {
        $text = strip_tags((string)$text);
        $text = str_replace(['\\r\\n', '\\n', '\\r', "\r", "\n"], ' ', $text);
        $text = trim(preg_replace('/\s+/u', ' ', $text));
        if ($text === '') {
            return '暂无摘要';
        }
        if (function_exists('mb_strlen') && mb_strlen($text, 'utf-8') > $length) {
            return mb_substr($text, 0, $length, 'utf-8') . '…';
        }
        return $text;
    }

    /**
     * 取姓名首字
     */
    private function firstChar($text)
    {
        $text = trim((string)$text);
        if ($text === '') {
            return '师';
        }
        return function_exists('mb_substr') ? mb_substr($text, 0, 1, 'utf-8') : substr($text, 0, 1);
    }
}
