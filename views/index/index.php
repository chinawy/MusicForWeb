<?php
use yii\helpers\Url;
use yii\helpers\Html;
/* @var $this yii\web\View */

$this->title = '数据列表';
?>
<?= Html::cssFile('@web/public/datatable/css/jquery.dataTables.min.css') ?>
<?= Html::jsFile('@web/public/datatable/js/jquery.dataTables.min.js') ?>
<?= Html::jsFile('@web/public/jquery/data.js') ?>
<?= Html::jsFile('@web/public/jquery/template-native.js') ?>
<!--音频播放插件-->
<?= Html::cssFile('@web/public/jquery-Aplayer/Aplayer.min.css') ?>
<?= Html::jsFile('@web/public/jquery-Aplayer/Aplayer.min.js') ?>
<style>
    body{
        /* 加载背景图 */
        background-image : url("/public/images/bg2.jpg");
        /* 背景图垂直、水平均居中 */
        background-position: center center;
        /* 背景图不平铺 */
        background-repeat: no-repeat;
        /* 当内容高度大于图片高度时，背景图像的位置相对于viewport固定 */
        background-attachment: fixed;
        /* 让背景图基于容器大小伸缩 */
        background-size: cover;
        /* 设置背景颜色，背景图加载过程中会显示背景色 */
        background-color: #464646;
    }
    .oust{
        border-radius: 40%;
        margin-left: 10px;
    }
    .paginate_button:hover{
        background: #ccc!important;
        border-color: #ccc!important;
    }
    .aplayer-icon-pause{
        top:0px!important;
        left: 0px!important;
    }
    .aplayer-icon-play{
        top:0px!important;
        left: 2px!important;
    }

    .vertical-center{
        position: absolute;
        top: 75px;
        left: 50%;
        transform: translate(-50%, -50%);
    }
    .table{
        width: 625px!important;
        height: 300px!important;
        margin-top: 123px!important;
    }
    .table tbody tr{
        opacity: 0.8;
    }
    .table>tbody>tr>td{
        border:none;
        padding: 8px!important;
    }
    .table>tbody>tr>td{
        cursor: default;
    }
    .onlink{
        color: #333;
        text-decoration: none;
    }
    .onlink:hover{
        text-decoration: none;
    }
    .table thead tr{
        opacity: 0.8;
    }
    .aplayer{
        width: 350px!important;
        margin-top:0px;
        margin-right: -8px;
        float: right;
        border-style:none;
        display: none;
        position: absolute;
        top: 40px;
        right: 15px;
    }
    .panel{
        position: fixed!important;
        top:0px!important;
        width: 100%!important;
        z-index: 100;
        opacity: 0.8;
    }
    .panel-body{
        height: 100px!important;
    }
    .search{
        margin-top: -1px;
    }
    .keyword{
        font-family: "微软雅黑 Light";
    }
    .dataTables_paginate{
        padding: 5px!important;
    }
    .dataTables_wrapper .dataTables_length, .dataTables_wrapper .dataTables_filter, .dataTables_wrapper .dataTables_info, .dataTables_wrapper .dataTables_processing, .dataTables_wrapper .dataTables_paginate{
        color: #fff;
        padding-top:1.5em;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button{color: #fff!important;}
    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover, .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active{color: #fff!important;}
    #DataTables_Table_0_wrapper{display: none}
    #logo{width: 240px;height: 70px;background-image: url("/public/images/logo.png");margin-top: -2px}
    .dataTables_wrapper .dataTables_processing{
        position: absolute;
        top: 30%;
        left: 50%;
        width: 40px!important;
        height: 40px!important;
        margin-left: auto!important;
        margin-top: auto!important;
        padding-top: auto!important;
        background: url("/public/svg-loaders/rings.svg") no-repeat 0px 0px;
    }
    .audio-loading{
        width: 15px;
        height:15px;
        background: #ADADB8;
        margin-left: 5px;
        display: none;
    }
    #videoBox{
        display: none;
        width: 600px;
        min-height: 340px;
        position:fixed;
        z-index: 100;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: rgba(0,0,0,0.4);
    }
    #videoBox .close{
        position: absolute;
        top:0px;
        right: -20px;
        font-size: 15px;
    }
</style>
<div class="panel panel-default">
    <div class="panel-heading"></div>
    <div class="panel-body">
        <div id="logo"></div>
        <div class="col-lg-5 vertical-center">
            <div class="input-group input-group-lg">
                <input type="text" class="form-control keyword" placeholder="(～￣▽￣)～...">
        <span class="input-group-btn">
            <a href="javascript:;" class="btn btn-default search glyphicon glyphicon-search"></a>
        </span>
            </div>
        </div>
        <!--mp3-->
        <div id="player1" class="aplayer">
            <div class="aplayer-pic">
                <img src="/public/images/disc.jpg">
            </div>
        </div>
    </div>
</div>
<!--video-->
<div id="videoBox">
    <div id="video"></div>
    <a href="javascript:;" class="close glyphicon glyphicon-remove"></a>
</div>
<table class="table" style="display: none">
    <thead>
        <tr>
            <th>音乐标题</th>
            <th>歌手</th>
            <th>专辑</th>
            <th>时长</th>
            <th>操作</th>
        </tr>
    </thead>
    <tbody id="list"></tbody>
</table>
<script>
    $(function(){
        //表单
        var Columns = [
            {
                "data": "songname",
                "sName": "songname",
                "bSortable": false,
                "bSearchable":false,
                "sTitle": "音乐标题",
                "mRender": function (f, display, data) {
                    var html = '';
                    if(data.songname){
                        html = "<span  title='"+data.songname.oldsongname+"'>"+data.songname.shortsongname+"</span>";
                        html +='<img class="audio-loading" src="/public/svg-loaders/audio.svg" alt="">';
                    }
                    return html;
                }
            },
            {
                "data":'singername',
                "sName": "singername",
                "bSearchable": false,
                "bSortable": false,
                "sTitle": "歌手",
                "mRender": function (f, display, data) {
                    var html = '';
                    if(data.singername){
                        html = "<a class='onlink' href='javascript:;' title='"+data.singername.oldsingername+"'>"+data.singername.shortsingername+"</a>";
                    }
                    return html;
                }
            },
            {
                "data":'albumname',
                "sName": "albumname",
                "bSearchable": false,
                "bSortable": false,
                "sTitle": "专辑",
                "mRender": function (f, display, data) {
                    var html = '';
                    if(data.albumname){
                        html = "<a class='onlink' href='javascript:;' title='"+data.albumname.oldalbumname+"'>"+data.albumname.shortalbuname+"</a>";
                    }
                    return html;
                }
            },
            {
                "data":'time',
                "sName": "time",
                "bSearchable": false,
                "bSortable": false,
                "sTitle": "时长",
                "mRender": function (f, display, data) {
                    var html = '';
                    if(data.time) html = data.time;
                    return html;
                }
            },
            {
                "data":'hash',
                "sName": "hash",
                "bSearchable": false,
                "bSortable": false,
                "sTitle": "操作",
                "mRender": function (f, display, data) {
                    var html = '';
                        if(data.hash){
                            if(data.hash.songhash != 0){
                                html+="<a href='javascript:;' title='试听' data-hash="+data.hash.songhash+" class='oust to-listen btn btn-xs btn-default glyphicon glyphicon-play-circle'></a>";
                            }
                            if(data.hash.mvhash != 0){
                                html+="<a href='javascript:;' title='mv' data-hash="+data.hash.mvhash+" class='oust to-watch btn btn-xs btn-default glyphicon glyphicon-film'></a>";
                            }
                        }
                    return html;
                }
            }
        ];
        var tableVar = '.table';
        var option = {};
        option.iDisplayLength = '20';
        //默认获取数据
        getData(Columns, tableVar, "<?php echo Url::to(['index/index']);?>",{},option);

        $(".keyword").keyup(function(){
            if(event.keyCode == 13){
                run();
            }
        });
        $('.search').click(function(){
            run();
        })
        run = function(){
            var keyword = $('.keyword').val();
            if(keyword != ''){
                var oSettings = datatables.fnSettings();
                //设置参数
                //激活时间
                oSettings.ajax.data.keyword = keyword;
                $(tableVar).dataTable().fnDraw(oSettings);
                $('.table').fadeIn("2000");
                $('#DataTables_Table_0_wrapper').fadeIn("2000");
                $('.dataTables_processing').show();
            }
        }

        $(document).on('click','.onlink',function(){
            var keyword = $(this).attr('title');
            $('.keyword').val(keyword);
            run();

        })

        //mp3
        $(document).on('click','.to-listen',function(){
            //暂停正在播放的歌曲
            $(".aplayer-pause").trigger("click");
            if($(this).hasClass('active')) {
                //开启/暂停歌曲
                if($(this).hasClass('glyphicon-signal')){
                    $(this).removeClass('glyphicon-signal');
                    $(this).addClass('glyphicon-pause');
                }else{
                    $(".aplayer-play").trigger("click");
                    $(this).removeClass('glyphicon-pause');
                    $(this).addClass('glyphicon-signal');
                }
            }else{
                //试听别的歌曲
                $(this).parents('#list tr').siblings('tr').find('.to-listen').removeClass('active');
                $(this).parents('#list tr').siblings('tr').find('.to-listen').removeClass('glyphicon-signal');
                $(this).parents('#list tr').siblings('tr').find('.to-listen').removeClass('glyphicon-pause');
                $(this).parents('#list tr').siblings('tr').find('.audio-loading').fadeOut();
                $(this).parents('#list tr').siblings('tr').find('.to-listen').addClass('glyphicon-play-circle');
                var hash = $(this).attr('data-hash');
                var _songname = $(this).parents('tr').find('td').eq(0).html();
                var _songername = $(this).parents('tr').find('td').eq(1).html();
                var _albumname = $(this).parents('tr').find('td').eq(2).html();
                var _this = $(this);
                if(hash != ''){
                    $.ajax({
                        url : "<?php echo Url::to(['index/getmp3']);?>",
                        type:"post",
                        dataType:'json',
                        data:{'hash':hash},
                        success:function(data){
                            if(data['status']){
                                if(_albumname.indexOf('title=""')>=0){
                                    title = _songname;
                                    author = _songername;
                                }else{
                                    title = _songname+'-'+_songername;
                                    author = '<'+_albumname+'>';
                                }
                                var ap1 = new APlayer({
                                    element: document.getElementById('player1'),
                                    narrow: false,
                                    autoplay: true,
                                    showlrc: false,
                                    music: {
                                        title: title,
                                        author: author,
                                        url: data['url'],
                                        pic: '/public/images/disc.jpg'
                                    }
                                });
                                ap1.init();
                                _this.addClass('active');
                                _this.addClass('glyphicon-signal');
                                _this.parents('#list tr').find('.audio-loading').fadeIn('1000');
                                _this.removeClass('glyphicon-play-circle');
                                _this.removeClass('glyphicon-pause');
                                //播放器显示
                                $('.aplayer').show();
                            }else{
                                layer.msg(data.msg);
                            }
                        }
                    })
                }
            }
        })

        //mp4
        $(document).on('click','.to-watch',function(){
            var hash = $(this).attr('data-hash');
            if(hash != ''){
                $.ajax({
                    url : "<?php echo Url::to(['index/getmp4']);?>",
                    type:"post",
                    dataType:'json',
                    data:{'hash':hash},
                    success:function(data){
                        if(data['status']){
                            $('#video').html(template('videoUrl',{videoUrl:data['url']}));
                            $('#videoBox').show();
                        }else{
                            layer.msg(data['msg']);
                        }
                    }

                })
            }
        })

        $('#videoBox .close').click(function(){
            $('#videoBox').hide();
        })

        $(window).scroll(function() {
            // 当滚动到最底部以上100像素时， 加载新内容
            if ($(this).scrollTop() > 0) {
                $(".panel").css("opacity",'1');
                $(".panel").css("background",'#f4f5f9');
            }else{
                $(".panel").css("opacity",'0.8');
                $(".panel").css("background",'#fff');
            }
        });
    })
</script>
<!--视频播放-->
<script id="videoUrl" type="text/html">
    <video width="100%" height="100%" controls="controls">
        <source src="<%=videoUrl%>" type="video/mp4" ></source>
    </video>
</script>
