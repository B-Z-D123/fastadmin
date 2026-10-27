define(['jquery', 'bootstrap', 'backend', 'table', 'form'], function ($, undefined, Backend, Table, Form) {

    var Controller = {
        index: function () {
            // 初始化表格参数配置
            Table.api.init({
                extend: {
                    index_url: 'class1/index' + location.search,
                    add_url: 'class1/add',
                    edit_url: 'class1/edit',
                    del_url: 'class1/del',
                    multi_url: 'class1/multi',
                    import_url: 'class1/import',
                    table: 'class1',
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
                        {field: 'cl_no', title: __('Cl_no')},
                        {field: 'cl_name', title: __('Cl_name'), operate: 'LIKE'},
                        // {field: 'cl_note', title: __('Cl_note'), operate: 'LIKE'},
                        {field: 'd_no', title: __('D_no')},
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
