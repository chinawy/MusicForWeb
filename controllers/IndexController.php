<?php

namespace app\controllers;

use yii\base\ErrorException;
use yii\helpers\Url;
use yii\web\Controller;
use YII;
use app\services\KgMusicService;

class IndexController extends Controller
{
    public $layout = "main"; //设置使用的布局文件
    public $enableCsrfValidation = false;

    public $appRequest = null;

    public function init(){
        parent::init();
        //test1
        $app = YII::$app;
        $this->appRequest = $app->request;
    }

    public function actions(){
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex(){
        $request = $this->appRequest;
        if($request->isAjax){
            $draw = $request->post('draw');
            $start = $request->post('start');
            $limit = $request->post('length');
            $page = 1;
            if($start>=$limit){
                $page = $page+($start/$limit);
            }
            $keyword = $request->post('keyword');

            //获取数据
            $info = [];
            $total = 0;
            if(!empty($keyword)) {
                $kgMusic = new KgMusicService();
                $kgMusic->setKeyWord(str_replace(' ','',$keyword));//关键字(去掉空格)
                $kgMusic->setUrlParam(['pagesize' => $limit, 'page' => $page]);
                $relust = $kgMusic->getSongList();
                //组合数据
                if ($relust['data']['total'] > 0) {
//                    $this->p($relust,0);
                    foreach ($relust['data']['info'] as $k => $v) {
                        //音乐标题
                        $info[$k]['songname']['shortsongname'] = $this->substrForCn($v['songname'],36);
                        $info[$k]['songname']['oldsongname'] = $v['songname'];
                        //歌手
                        $info[$k]['singername']['shortsingername'] = $this->substrForCn($v['singername'],24);
                        $info[$k]['singername']['oldsingername'] = $v['singername'];
                        //专辑
                        $info[$k]['albumname']['shortalbuname'] = $this->substrForCn($v['album_name'],24);
                        $info[$k]['albumname']['oldalbumname'] = $v['album_name'];
                        //歌曲时长
                        $info[$k]['time'] = gmstrftime('%M:%S', $v['duration']);
                        //播放地址哈希值
                        $info[$k]['hash']['songhash'] = !empty($v['hash']) ? $v['hash'] : 0;
                        $info[$k]['hash']['mvhash'] = !empty($v['mvhash']) ? $v['mvhash'] : 0;
                    }
                    $total = $relust['data']['total'];
                }
            }
//            $this->p($info,0);
            $arr = array('status' => 'ok','draw' => $draw, 'recordsTotal' => $total, 'recordsFiltered' => $total, 'data' => $info);
            die (json_encode( $arr ));
        }else{
            return $this->render('index');
        }
    }

    /**
     * 获取mp3播放地址
     */
    public function actionGetmp3(){
        $request = $this->appRequest;
        if($request->isAjax){
            //获取hash
            $hash = $request->post('hash');
            //获取key
            $key = md5($hash.'kgcloud');
            //获取地址
            $kgMusic = new KgMusicService();
            $kgMusic->setHash($hash);//hash
            $kgMusic->setKey($key);//key
            $relust = $kgMusic->getMp3Url();
            if($relust['url']){
                die (json_encode( ['status'=>1,'msg'=>'success','url'=>$relust['url']] ));
            }else{
                die (json_encode( ['status'=>0,'msg'=>'未找到播放地址','url'=>''] ));
            }
        }
    }

    /**
     * 获取mp4播放地址
     */
    public function actionGetmp4(){
        $request = $this->appRequest;
        if($request->isAjax){
            //获取hash
            $hash = $request->post('hash');
            //获取key
            $key = md5($hash.'kugoumvcloud');
            //获取地址
            $kgMusic = new KgMusicService();
            $kgMusic->setHash($hash);//hash
            $kgMusic->setKey($key);//key
            $relust = $kgMusic->getMp4Url();
            if($relust['status']){
                die (json_encode( ['status'=>1,'msg'=>'success','url'=>$relust['mvdata']['sd']['downurl']] ));
            }else{
                die (json_encode( ['status'=>0,'msg'=>'未找到播放地址','url'=>''] ));
            }
        }
    }

    /**
     * 中文截取字符串
     */
    public function substrForCn($str,$length,$append='...',$start=0){
        if(strlen($str)<$start+1){
            return '';
        }
        preg_match_all("/./su",$str,$ar);
        $str2='';
        $tstr='';
        for($i=0;isset($ar[0][$i]);$i++){
            if(strlen($tstr)<$start){
                $tstr.=$ar[0][$i];
            }else{
                if(strlen($str2)<$length + strlen($ar[0][$i])){
                    $str2.=$ar[0][$i];
                }else{
                    break;
                }
            }
        }
        return $str==$str2?$str2:$str2.$append;
    }

}
