<?php
namespace PhalApi;

use PhalApi\ApiFactory;
use PhalApi\Exception;

/**
 * PhalApi 应用类
 *
 * - 实现远程服务的响应、调用等操作
 * 
 * <br>使用示例：<br>
```
 * $api = new PhalApi();
 * $rs = $api->response();
 * $rs->output();
```
 *
 * @package     PhalApi\Response
 * @license     http://www.phalapi.net/license GPL 协议
 * @link        http://www.phalapi.net/
 * @author      dogstar <chanzonghuang@gmail.com> 2014-12-17
 */

class PhalApi {
    
    /**
     * 响应操作
     *
     * 通过工厂方法创建合适的控制器，然后调用指定的方法，最后返回格式化的数据。
     *
     * @return mixed 根据配置的或者手动设置的返回格式，将结果返回
     *  其结果包含以下元素：
```
     *  array(
     *      'ret'   => 200,	            //服务器响应状态
     *      'data'  => array(),	        //正常并成功响应后，返回给客户端的数据	
     *      'msg'   => '',		        //错误提示信息
     *  );
```
     */
    public function response() {
        $di = DI();

        // 开始响应接口请求
        $di->tracer->mark('PHALAPI_RESPONSE');

        $rs = $di->response;
        try {
            // 接口调度与响应
            $api    = ApiFactory::generateService(); 
            $action = $di->request->getServiceAction();
            $data   = call_user_func(array($api, $action));
            
            
            $mark =  json_decode( json_encode( $api),true)['mark']; // 如果mark为空也不加密
            if($mark){
                $encrypt = \PhalApi\DI()->uniapp[$mark]['encrypt'];
                if($encrypt['radio'] == '1'){//等于1加密  
                  $data = $this->CryptoCrypt(json_encode($data),$encrypt['iv'],$encrypt['key']);
                  $rs->setData($data);
               
                }else {
                  $rs->setData($data);
                }
            }else{
                $rs->setData($data);
            }
            
        } catch (Exception $ex) {
            // 框架或项目可控的异常
            $rs->setRet($ex->getCode());
            $rs->setMsg($ex->getMessage());
        } catch (\Exception $ex) {
            // 不可控的异常
            $di->logger->error(DI()->request->getService(), strval($ex));

            if ($di->debug) {
                $rs->setRet($ex->getCode());
                $rs->setMsg($ex->getMessage());
                $rs->setDebug('exception', $ex->getTrace());
            } else {
                throw $ex;
            }
        }

        // 结束接口调度
        $di->tracer->mark('PHALAPI_FINISH');

        $rs->setDebug('stack', $di->tracer->getStack());
        $rs->setDebug('sqls', $di->tracer->getSqls());
        $rs->setDebug('version', PHALAPI_VERSION);

        return $rs;
    }
    
    /**
     * 返回数据全部加密
     * @param array/string $data 待返回给客户端的数据，建议使用数组，方便扩展升级
     * @return Response
     */
    
    public function CryptoCrypt($items,$iv,$key){
        return base64_encode(openssl_encrypt($items,"AES-128-CBC",$key,OPENSSL_RAW_DATA,$iv));
    }
    
}
