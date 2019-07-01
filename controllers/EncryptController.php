<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2019/6/21
 * Time: 14:02
 */

namespace controllers;


class EncryptController
{
	public function actionIndex(){
			var_dump($_GET);
	}
    public function actionHash(){
    	$key='hhghghghhhghghgh';
        echo md5('123456',false);echo "使用md5加密<br>";
        echo md5('123456',true);echo "<br>";
        echo md5_file('./upload/123.jpg');echo "<br>";
        echo sha1('123456',false);echo "使用sha1加密<br>";
        echo sha1('123456',true);echo "<br>";
        echo sha1_file('./upload/123.jpg');echo "<br>";
        echo hash('md5','1111');echo "<br>";
        echo hash('sha1','111');echo "<br>";
        echo hash('sha256','11');echo "<br>";
        echo hash_hmac('md5','1111',$key);echo "<br>";
        echo hash_hmac('sha1','111',$key);echo "<br>";
        echo hash_hmac('sha256','11',$key);echo "<br>";
        $iv=random_bytes(8);
        $av=bin2hex(random_bytes(8));
        //使用openssl的ECB加密
        echo $pwd=openssl_encrypt('ssss', "DES-ECB", $key);echo "<br>";
        echo openssl_decrypt($pwd, "DES-ECB", $key);echo "<br>";
        //使用openssl的CBC加密
        echo $pwd = openssl_encrypt('gggg', "DES-CBC", $key,0,$iv);echo "<br>";
        echo openssl_decrypt($pwd, "DES-CBC",$key,0,$iv);echo "<br>";
        //使用openssl的ECB3des加密
        echo "3DES ECB进行加密<br>";
        echo $pwd = openssl_encrypt('ssss', "DES-EDE3", $key);echo "<br>";
        echo openssl_decrypt($pwd, "DES-EDE3", $key);echo "<br>";
        echo "使用3DES CBC进行加密解密<br>";
        echo $pwd = openssl_encrypt('sss',"DES-EDE3-CBC",$key,0,$iv);echo "<br>";
        echo openssl_decrypt($pwd,"DES-EDE3-CBC",$key,0,$iv);echo "<br>";
        echo "使用AES ECB加密解密<br>";
        echo $pwd=openssl_encrypt('ssss', "AES-128-ECB", $key);echo "<br>";
        echo openssl_decrypt($pwd, "AES-128-ECB", $key);echo "<br>";
        echo "使用AES CBC进行加密解密<br>";
        echo $pwd = openssl_encrypt('gggg', "AES-128-CBC", $key,0,$av);echo "<br>";
        echo openssl_decrypt($pwd, "AES-128-CBC",$key,0,$av);echo "<br>";

        dd(openssl_get_cipher_methods());
    }
    public function actionRsa(){
		list($publicKey,$privateKey) = $this->rsa();

		var_dump($publicKey);
		var_dump($privateKey);
		echo "<br>待加密的数据为:","101<br>";
		echo "<br>加密后的数据为:","<br>";
		$encrypt = $this->rsaEncrypt("101",$publicKey);

		echo $encrypt;
		echo "<br>解密后的数据为:","<br>";
		$decrypt = $this->rsaDecrypt($encrypt,$privateKey);
		echo $decrypt;

		// $config=[
		// 	'config'=>'E:\phpstudys\PHPTutorial\Apache\conf\openssl.cnf',
		// 	'openssl_key_bits'=>'2048'
		// ];
		// $pk=openssl_pkey_new($config);

		// openssl_pkey_export($pk,$privateKey,null,$config);
		// $publicKey=openssl_pkey_get_details($pk)['key'];echo "<br>";
		// //使用公钥加密
		// echo "使用公钥加密后的数据<br>";
		// openssl_public_encrypt('哈哈哈',$encrypt,$publicKey);
		// echo $encrypt;echo "<br>";
		// echo "使用公钥解密后的数据<br>";
		// openssl_private_decrypt($encrypt,$decrypt,$privateKey);
		// echo $decrypt;
	}

	// 判断一个数字是否为质数
	protected function isPrime($value){
		$is = true;
		for($i=2;$i<=floor($value/2);$i++){
				if($value%$i == 0){
					$is = false;
					break;
				}
		}
		return $is;
	}

	protected function createPrime(){
		while(true){
			$key = mt_rand(2,100);
			if($this->isPrime($key)){
				break;
			}
		}
		return $key;
	}

	protected function isPrimePair($value1,$value2){
		$is = true;
		$min = min($value1,$value2);
		// 
		for($i=2;$i<=$min;$i++){
			if($value1%$i == 0 && $value2%$i==0){
				$is = false;
				break;
			}
		}
		return $is;
	}

	protected function getPrivateKey($N,$publicKey){

		for($privateKey=2;;$privateKey++){
			$product = gmp_mul($privateKey,$publicKey);
			if( gmp_mod($product,$N) == 1){
				break;
			}
		}
		return $privateKey;
	}
	// 自己生成公钥和私钥
	public function rsa(){
		// 1. 自定义两个数字，p  q (质数/素数)
		// $p = $this->createPrime();
		// $q = $this->createPrime();
		$p = 7;
		$q = 13;

		$N = $p * $q;
		$num = ($p-1)*($q-1);
		// 计算($p-1)*($q-1)
		while(true){
			$publicKey = mt_rand(2,$num-1);
			if($this->isPrimePair($publicKey,$num)){
				break;
			}
		}
		$privateKey = $this->
		getPrivateKey($num,$publicKey);
		
		return [[$N,$publicKey],[$N,$privateKey]];

	}

	protected function rsaEncrypt($data,$key){
		$res = gmp_strval(gmp_pow($data,$key[1]));
		//echo "<br>",$res,"<br>";
		return gmp_strval(gmp_mod($res,$key[0]));
	}

	protected function rsaDecrypt($data,$key){
		$res = gmp_strval(gmp_pow($data,$key[1]));
		var_dump($res);
		//echo "<br>",$res,"<br>";
		return gmp_strval(gmp_mod($res,$key[0]));
	}
}