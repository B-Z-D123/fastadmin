<?php

namespace app\admin\controller;

use app\admin\model\Class1;
use app\admin\model\Course;
use app\admin\model\Teachers;
use app\common\controller\Backend;
use think\Db;
use think\exception\ValidateException;
use think\exception\PDOException;
use Exception;

/**
 * 授课记录管理
 *
 * @icon fa fa-circle-o
 */
class Teach extends Backend
{

    /**
     * Teach模型对象
     * @var \app\admin\model\Teach
     */
    protected $model = null;
    //教师模型
    protected $teacherModel = null;
    //课程模型
    protected $courseModel = null;
    //班级模型
    protected $classModel = null;

    public function _initialize()
    {
        parent::_initialize();
        $this->model = new \app\admin\model\Teach;
        $this->teacherModel = new Teachers();
        $this->courseModel = new Course();
        $this->classModel = new Class1();
    }



    /**
     * 默认生成的控制器所继承的父类中有index/add/edit/del/multi五个基础方法、destroy/restore/recyclebin三个回收站方法
     * 因此在当前控制器中可不用编写增删改查的代码,除非需要自己控制这部分逻辑
     * 需要将application/admin/library/traits/Backend.php中对应的方法复制到当前控制器,然后进行修改
     */


    /**
     * 查看
     */
    public function index()
    {
        //当前是否为关联查询
        $this->relationSearch = true;
        //设置过滤方法
        $this->request->filter(['strip_tags', 'trim']);
        if ($this->request->isAjax()) {
            //如果发送的来源是Selectpage，则转发到Selectpage
            if ($this->request->request('keyField')) {
                return $this->selectpage();
            }
            list($where, $sort, $order, $offset, $limit) = $this->buildparams();

            $list = $this->model
                    ->with(['teachers', 'course', 'class1'])
                    ->where($where)
                    ->order($sort, $order)
                    ->paginate($limit);

            foreach ($list as $row) {
                $row->visible(['id', 't_no', 'c_no', 'cl_no']);
                $row->visible(['teachers', 'course', 'class1']);
                $teachers = $row->getRelation('teachers');
                if ($teachers) {
                    $teachers->visible(['t_no', 't_name']);
                }
                $course = $row->getRelation('course');
                if ($course) {
                    $course->visible(['c_no', 'c_name']);
                }
                $class1 = $row->getRelation('class1');
                if ($class1) {
                    $class1->visible(['cl_no', 'cl_name']);
                }
            }

            $result = array("total" => $list->total(), "rows" => $list->items());

            return json($result);
        }
        return $this->view->fetch();
    }



    /**
     * 添加
     *
     * @return string
     * @throws \think\Exception
     */
    public function add()
    {
        if (false === $this->request->isPost()) {
            $this->view->assign('teacherList', collection($this->teacherModel->select())->toArray());
            $this->view->assign('courseList', collection($this->courseModel->select())->toArray());
            $this->view->assign('classList', collection($this->classModel->select())->toArray());
            //显示页面
            return $this->view->fetch();
        }
        $params = $this->request->post('row/a');

        if (empty($params)) {
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $params = $this->preExcludeFields($params);

        if ($this->dataLimit && $this->dataLimitFieldAutoFill) {
            $params[$this->dataLimitField] = $this->auth->id;
        }
        $result = false;
        Db::startTrans();
        try {
            //是否采用模型验证
            if ($this->modelValidate) {
                $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.add' : $name) : $this->modelValidate;
                $this->model->validateFailException()->validate($validate);
            }
            $result = $this->model->allowField(true)->save($params);
            Db::commit();
        } catch (ValidateException|PDOException|Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        if ($result === false) {
            $this->error(__('No rows were inserted'));
        }

        $this->success();
    }

    /**
     * 编辑
     *
     * @param $ids
     * @return string
     * @throws DbException
     * @throws \think\Exception
     */
    public function edit($ids = null)
    {
        $row = $this->model->get($ids);
        if (!$row) {
            $this->error(__('No Results were found'));
        }
        $adminIds = $this->getDataLimitAdminIds();
        if (is_array($adminIds) && !in_array($row[$this->dataLimitField], $adminIds)) {
            $this->error(__('You have no permission'));
        }
        if (false === $this->request->isPost()) {
            $this->view->assign('teacherList', collection($this->teacherModel->select())->toArray());
            $this->view->assign('courseList', collection($this->courseModel->select())->toArray());
            $this->view->assign('classList', collection($this->classModel->select())->toArray());
            $this->view->assign('row', $row);
            return $this->view->fetch();
        }
        $params = $this->request->post('row/a');
        if (empty($params)) {
            $this->error(__('Parameter %s can not be empty', ''));
        }
        $params = $this->preExcludeFields($params);
        $result = false;
        Db::startTrans();
        try {
            //是否采用模型验证
            if ($this->modelValidate) {
                $name = str_replace("\\model\\", "\\validate\\", get_class($this->model));
                $validate = is_bool($this->modelValidate) ? ($this->modelSceneValidate ? $name . '.edit' : $name) : $this->modelValidate;
                $row->validateFailException()->validate($validate);
            }
            $result = $row->allowField(true)->save($params);
            Db::commit();
        } catch (ValidateException|PDOException|Exception $e) {
            Db::rollback();
            $this->error($e->getMessage());
        }
        if ($result === false) {
            $this->error(__('No rows were updated'));
        }
        $this->success();
    }

}
