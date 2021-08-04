<?php
/**
 * 请在下面放置任何您需要的应用配置
 *
 * @license     http://www.phalapi.net/license GPL 协议
 * @link        http://www.phalapi.net/
 * @author dogstar <chanzonghuang@gmail.com> 2017-07-13
 */

return array(

    /**
     * 应用接口层的统一参数
     */
    'apiCommonRules' => array(
        //'sign' => array('name' => 'sign', 'require' => true),
    ),

    /**
     * 接口服务白名单，格式：接口服务类名.接口服务方法名
     *
     * 示例：
     * - *.*         通配，全部接口服务，慎用！
     * - Site.*      Api_Default接口类的全部方法
     * - *.Index     全部接口类的Index方法
     * - Site.Index  指定某个接口服务，即Api_Default::Index()
     */
    'service_whitelist' => array(
        'Site.Index',
    ),
    
    /**
     * 七牛相关配置
     */
    'Qiniu' =>  array(
        //统一的key
        'access_key' => '',
        'secret_key' => '',
        //自定义配置的空间
        'space_bucket' => '',
        'space_host' => '', // 如果有配置此项，则优先使用此域名
        'preffix' => '', // 上传文件名前缀
        'upload_url' => '', // 提示：incorrect region, please use up-z2.qiniup.com，请配置此域名
        //必须配置
        
        
    ),
    
);
