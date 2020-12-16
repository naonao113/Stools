<?php
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2017, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2017, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Application Controller Class
 *
 * This class object is the super class that every library in
 * CodeIgniter will be assigned to.
 *
 * @package		CodeIgniter
 * @subpackage	Libraries
 * @category	Libraries
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/general/controllers.html
 */
class CI_Controller {

	/**
	 * Reference to the CI singleton
	 *
	 * @var	object
	 */
	private static $instance;

	/**
	 * Class constructor
	 *
	 * @return	void
	 */
	public function __construct()
	{
		self::$instance =& $this;

		// Assign all the class objects that were instantiated by the
		// bootstrap file (CodeIgniter.php) to local class variables
		// so that CI can run as one big super object.
		foreach (is_loaded() as $var => $class)
		{
			$this->$var =& load_class($class);
		}

		$this->load =& load_class('Loader', 'core');
		$this->load->initialize();
		log_message('info', 'Controller Class Initialized');
	}

	// --------------------------------------------------------------------

	/**
	 * Get the CI singleton
	 *
	 * @static
	 * @return	object
	 */
	public static function &get_instance()
	{
		return self::$instance;
	}

	/**
	 * curl post请求
	 * @param $url
	 * @param $param
	 * @param bool $post_file
	 * @return bool|mixed
	 */
	static function http_post($url, $param, $post_file = false, $timeout = 20,$is_json=false)
	{
		$path = 'curl/curlAccess-' . date('Ymd') . '.txt';
		$data = '[request] ' . date('Y-m-d H:i:s') . ' # ' . $url . '***' . json_encode($param) . "\r\n";
		//self::requestLog($path, $data);

		$oCurl = curl_init();
		if (stripos($url, "https://") !== FALSE) {
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($oCurl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($oCurl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
		}
		if (is_string($param) || $post_file) {
			$strPOST = $param;
		} else {
			$strPOST = json_encode($param, JSON_UNESCAPED_UNICODE);
		}

		if( $is_json == true){
            $header = array(
                "Accept:application/json",
                "Content-Type:application/json;charset=utf-8",
                "Cache-Control:no-cache",
                "Pragma:no-cache"
            );
            $strPOST = json_encode($param, JSON_UNESCAPED_UNICODE);
            curl_setopt($oCurl, CURLOPT_HTTPHEADER, $header);
        }
		curl_setopt($oCurl, CURLOPT_URL, $url);
		curl_setopt($oCurl, CURLOPT_TIMEOUT, $timeout);   //只需要设置一个秒的数量就可以
		curl_setopt($oCurl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($oCurl, CURLOPT_POST, true);
		curl_setopt($oCurl, CURLOPT_POSTFIELDS, $strPOST);
		$sContent = curl_exec($oCurl);
		var_dump($sContent);
		$aStatus = curl_getinfo($oCurl);
		curl_close($oCurl);
		if (intval($aStatus["http_code"]) == 200 ) {
//			$path = 'curl/curlAccess-' . date('Ymd') . '.txt';
//			$data = '[return] ' . date('Y-m-d H:i:s') . ' # ' . json_encode($sContent) . "\r\n";
//			self::requestLog($path, $data);
			return $sContent;
		} else {
			//var_dump($aStatus);
			$path = 'curl/curlAccess-' . date('Ymd') . '.txt';
			$data = '[error] ' . date('Y-m-d H:i:s') . ' # ' . json_encode($aStatus) . "\r\n" . json_encode($sContent) . "\r\n";
			//self::requestLog($path, $data);
//            var_dump($aStatus);
//            echo ($sContent);
			return false;
		}
	}


	static function http_get($url, $timeout = 3)
	{

		$path = 'curl/curlAccess-' . date('Ymd') . '.txt';
		$data = '[request] ' . date('Y-m-d H:i:s') . ' # ' . $url . "\r\n";
		//self::requestLog($path, $data);
		$header = array(
			"Accept:application/json",
			"Content-Type:application/json;charset=utf-8",
			"Cache-Control:no-cache",
			"Pragma:no-cache"
		);

		$curl = curl_init();

		if (stripos($url, "https://") !== FALSE) {
			curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
			curl_setopt($curl, CURLOPT_SSLVERSION, 1); //CURL_SSLVERSION_TLSv1
		}

		//设置抓取的url
		curl_setopt($curl, CURLOPT_URL, $url);
		//设置头文件的信息作为数据流输出
		curl_setopt($curl, CURLOPT_HEADER, 0);
		//设置获取的信息以文件流的形式返回，而不是直接输出。
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);   //只需要设置一个秒的数量就可以
		//执行命令
		curl_setopt($curl, CURLOPT_HTTPHEADER, $header);

//        //关闭URL请求
//        curl_errno($curl);
		$sContent = curl_exec($curl);
		$aStatus = curl_getinfo($curl);
//        var_dump($aStatus);
		curl_close($curl);
		//显示获得的数据

		if (intval($aStatus["http_code"]) == 200) {
//			$path = 'curl/curlAccess-' . date('Ymd') . '.txt';
//			$data = '[return] ' . date('Y-m-d H:i:s') . ' # ' . json_encode($sContent) . "\r\n";
//			self::requestLog($path, $data);
            return $sContent;
        } else {
            $path = 'curl/curlAccess-' . date('Ymd') . '.txt';
            $data = '[error] ' . date('Y-m-d H:i:s') . ' # ' . json_encode($aStatus) . "\r\n" . json_encode($sContent) . "\r\n";
           // self::requestLog($path, $data);
            return false;
        }
    }


}
