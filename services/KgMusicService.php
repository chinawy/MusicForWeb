<?php
/**
 * Created by PhpStorm.
 * User: 王艺
 * Date: 2016/4/14
 * Time: 18:07
 */
namespace app\services;
use Yii;

class kgMusicService
{
    //关键字
    private $keyWord = '';
    //获取歌曲列表数据接口url
    private $songListUrl = 'http://ioscdn.kugou.com/api/v3/search/song';
    //url必需参数
    private $urlParam = array(
        'page' =>  1,       //默认第一页
        'pagesize' => 20,   //一页获取歌曲数量(条数必须>=20)
        'showtype' => 10,
        'plat' => 2,
        'version' => 7980,
        'tag' => 1,
        'correct' => 1,
        'privilege' => 1,
        'sver' => 5
    );

    //播放地址hash值(mp3/m4a)
    private $hash = '';
    //播放地址key值
    private $key = '';

    //mp3请求地址
    private $mp3Url = "http://trackercdn.kugou.com/i/";
    //mp3请求参数
    private $mp3Param = array(
        'acceptMp3' =>  1,
        'cmd' => 3,
        'pid' => 6,
    );

    //mp4请求地址
    private $mp4Url = "http://trackermv.kugou.com/interface/index/";
    //mp4请求参数
    private $mp4Param = array(
        'cmd' =>  100,
        'pid' => 6,
        'ext' => 'mp4',
        'ismp3'=>0
    );


    /**
     * @param string $keyWord
     * 改变关键字
     */
    public function setKeyWord($keyWord=''){
         $this->keyWord = $keyWord;
    }

    /**
     * @param array $data
     * 改变url参数
     */
    public function setUrlParam($data=array('')){
        if(!empty($data)){
            $list = $this->urlParam;
            foreach($data as $k => $v){
                foreach($list as $kk => $vv){
                    if($k == $kk){
                        $list[$kk] = $v;
                    }
                }
            }
            $this->urlParam = $list;
        }
    }

    /**
     * 改变hash值
     * @param string $hash
     */
    public function setHash($hash=''){
        $this->hash = $hash;
    }

    /**
     * 改变key值
     */
    public function setKey($key=''){
        $this->key = $key;
    }

    /**
     * 根据搜索条件获取歌曲列表
     */
    public function getSongList(){
        $relust = array('');

        $keyWord = $this->keyWord;
        if(!empty($keyWord)){
            $getUrl = $this->songListUrl;
            //添加关键字
            $getUrl .='?keyword='.$keyWord;
            //添加参数
            $getUrl = $this->addUrlParam($getUrl,$this->urlParam);
            //调取接口获得返回数据
            $relust = $this->httpRequest($getUrl);
        }
        return $relust;
    }

    /**
     * 获取mp3播放地址
     */
    public function getMp3Url(){
        $relust = array('');
        if(!empty($this->hash) && !empty($this->key)){
            $getUrl = $this->mp3Url;
            $getUrl.='?key='.$this->key.'&hash='.$this->hash;
            //添加参数
            $getUrl = $this->addUrlParam($getUrl,$this->mp3Param);
            $relust = $this->httpRequest($getUrl);
        }
        return $relust;
    }

    /**
     * 获取mp4播放地址
     */
    public function getMp4Url(){
        $relust = array('');
        if(!empty($this->hash) && !empty($this->key)){
            $getUrl = $this->mp4Url;
            $getUrl.='?key='.$this->key.'&hash='.$this->hash;
            //添加参数
            $getUrl = $this->addUrlParam($getUrl,$this->mp4Param);
            $relust = $this->httpRequest($getUrl);
        }
        return $relust;
    }

    /**
     * 组合链接参数
     */
    public function addUrlParam($url,$arr){
        if(empty($url)||empty($arr)) return false;
        foreach($arr as $k => $v){
            $url.='&'.$k.'='.$v;
        }
        return $url;
    }

    /**
     * 模拟get请求获取数据
     * @param $url
     * @return mixed
     */
    public function httpRequest($url){
        //初始化
        $curl = curl_init();
        //设置抓取的url
        curl_setopt($curl, CURLOPT_URL, $url);
        //设置获取的信息以文件流的形式返回，而不是直接输出。
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        //执行命令
        $data = curl_exec($curl);
        //关闭URL请求
        curl_close($curl);
        //返回
        return json_decode($data,true);
    }

}