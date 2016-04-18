/**
 * Created by xiangyu on 2015/9/8.
 */


function getData(Columns, ID, URL) {
    var postData  = cAppend = {};

    if(arguments.length >= 4){
        postData = arguments[3];
    }
    if(arguments.length >= 5){
        cAppend = arguments[4];
    }
    //基础配置
    var baseObj = {
        "ordering": true,//关闭排序
        "bFilter": false, //关闭搜索框
        'bLengthChange': false, //是否允许自定义每页显示条数
        "bServerSide": true,
        "sPaginationType": "full_numbers",
        "bStateSave": false, //不保存每次选择的数量
        'iDisplayLength':10,
        "bProcessing": true,
        "iCookieDuration": 0,
        "sServerMethod": "POST",
        "order" : [],
        //"sDom": 'lrtip',//上下都有翻页
        "ajax": {
            'url':URL,
            "type": "POST",
            "dataType":"json",
            "data":postData
        },
        "aoColumns":Columns,
        "oLanguage": {
            "sProcessing": "",
            "sLengthMenu": "每页显示 _MENU_ 条 ",
            "sZeroRecords": "没有您要搜索的内容",
            "sInfo": "搜索结果:_TOTAL_ 首",
            "sInfoEmpty": "",
            "sInfoFiltered": "(全部记录数 _MAX_  条)",
            "sInfoPostFix": "",
            "sSearch": '<span class="am-btn am-btn-default am-btn-xs">搜索：</span>',
            "sUrl": "",
            "oPaginate": {
                "sFirst": " 首页 ",
                "sPrevious": "",
                "sNext": "",
                "sLast": " 末页 "
            }
        }
    }

    //组合后的
    var cObj = $.extend({}, baseObj,cAppend);
    datatables = $(ID).dataTable(cObj);
}

/**
 * 格式化时间戳
 * @param format
 * @returns {*}
 */
Date.prototype.format = function(format) {
    var date = {
        "M+": this.getMonth() + 1,
        "d+": this.getDate(),
        "h+": this.getHours(),
        "m+": this.getMinutes(),
        "s+": this.getSeconds(),
        "q+": Math.floor((this.getMonth() + 3) / 3),
        "S+": this.getMilliseconds()
    };
    if (/(y+)/i.test(format)) {
        format = format.replace(RegExp.$1, (this.getFullYear() + '').substr(4 - RegExp.$1.length));
    }
    for (var k in date) {
        if (new RegExp("(" + k + ")").test(format)) {
            format = format.replace(RegExp.$1, RegExp.$1.length == 1 ? date[k] : ("00" + date[k]).substr(("" + date[k]).length));
        }
    }
    return format;
}