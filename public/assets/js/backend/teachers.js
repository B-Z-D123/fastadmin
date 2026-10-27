define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'teachers/index' + location.search,
                    add_url: 'teachers/add',
                    edit_url: 'teachers/edit',
                    del_url: 'teachers/del',
                    multi_url: 'teachers/multi',
                    import_url: 'teachers/import',
                    table: 'teachers',
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
                        {field: 't_name', title: __('T_name'), operate: 'LIKE'},
                        {field: 'sex', title: __('Sex'), searchList: {"女":__('女'),"男":__('男')}, formatter: Table.api.formatter.normal},
                        {field: 'departments.d_name', title: __('Departments.d_name'), operate: 'LIKE'},
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
