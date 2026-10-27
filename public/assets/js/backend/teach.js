define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'teach/index' + location.search,
                    add_url: 'teach/add',
                    edit_url: 'teach/edit',
                    del_url: 'teach/del',
                    multi_url: 'teach/multi',
                    import_url: 'teach/import',
                    table: 'teach',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 'id',
                sortName: 'id',
                columns: [
                    [
                        {checkbox: true},
                        {field: 'id', title: __('Id')},
                        {field: 't_no', title: __('T_no')},
                        {field: 'teachers.t_name', title: __('Teachers.t_name'), operate: 'LIKE'},
                        {field: 'c_no', title: __('C_no')},
                        {field: 'course.c_name', title: __('Course.c_name'), operate: 'LIKE'},
                        {field: 'cl_no', title: __('Cl_no')},
                        {field: 'class1.cl_name', title: __('Class1.cl_name'), operate: 'LIKE'},
                        {field: 'operate', title: __('Operate'), table: table, events: Table.api.events.operate, formatter: Table.api.formatter.operate}
                    ]
                ]
            });

            // 为表格绑定事件
            Table.api.bindevent(table);
        },
        add: function () {
            Controller.api.bindevent();
        },
        edit: function () {
            Controller.api.bindevent();
        },
        api: {
            bindevent: function () {
                Form.api.bindevent($("form[role=form]"));
            }
        }
    };
    return Controller;
});
