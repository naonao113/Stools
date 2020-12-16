<?php



class Crmsign extends CI_Controller{

   
    public static $appkey="";
    public static $token="";
    public static $host="";

    public function __construct()
    {
        self::$token="ETOWEMALL@CRM2019";
        self::$appkey="ETO-108";
        self::$host="https://bestseller-center-integration.best.wmask.net:4501/call_crm";

    }
    public function getsign(){
        $time=time();
        $integrationId="MB14";
        echo self::getMd5Sign(self::$token,self::$appkey,$time,$integrationId);
    }
    public function getMememberInfo(){

        //var_dump($input);
        $params["platform"]="H5";
        $params['memberno']="";
        $params['unionid']="";
        $params['brand']="ONLY";
        $params["phone"]="18612463258";

        try {
            $timestamp = date('Y-m-d H:i:s');
            $sign = self::getMd5Sign(self::$token, self::$appkey, $timestamp, 'MB14');
            $appKey = self::$appkey;
            $integrationId = "MB14";
            $rs =self::http_post(self::$host, json_encode($params), [CURLOPT_HTTPHEADER => ["Content-type: application/json", "app_key: $appKey", "integration_id:$integrationId", "timestamp:$timestamp", "sign:$sign"]]);
             var_dump($rs);
        } catch (\Exception $ex) {
            throw new \Exception($ex->getMessage(), $ex->getCode(), $ex->getPrevious());
        }
        
        //print_r($rs);
    }

    private static function getMd5Sign($token, $appKey, $timestamp, $integrationId, $contentType = 'application/json')
    {
      $params = [
        "app_key" => $appKey,
        "Content-Type" => "application/json",
        "timestamp" => $timestamp,
        "integration_id" => $integrationId
      ];
      var_dump($params);
      ksort($params);
  
      $str = $token;
      foreach ($params as $key => $value) {
        $str .= $key . $value;
      }
      $str .= $token;
      // echo $str;die;
      // $sign_str = $token . 'Content-Type' . $contentType . 'app_key' . $appKey . 'timestamp' . $timestamp . 'integration_id' . $integrationId . $token;
      return md5($str);
    }
}
