define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'students/index' + location.search,
                    add_url: 'students/add',
                    edit_url: 'students/edit',
                    del_url: 'students/del',
                    multi_url: 'students/multi',
                    import_url: 'students/import',
                    table: 'students',
                }
            });

            var table = $("#table");

            // 初始化表格
            table.bootstrapTable({
                url: $.fn.bootstrapTable.defaults.extend.index_url,
                pk: 's_no',
                sortName: 's_no',
                columns: [
                    [
                        {checkbox: true},
                        {field: 's_no', title: __('S_no')},
                        {field: 's_name', title: __('S_name'), operate: 'LIKE'},
                        {field: 'sex', title: __('Sex'), searchList: {"女":__('女'),"男":__('男')}, formatter: Table.api.formatter.normal},
                        {field: 'birthday', title: __('Birthday')},
                        {field: 'class1.cl_name', title: __('Class1.cl_name'), operate: 'LIKE'},
                        {field: 'phone', title: __('Phone')},
                        {field: 'address', title: __('Address'), operate: 'LIKE'},
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
