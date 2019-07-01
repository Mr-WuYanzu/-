<?php
namespace model;
use libs\Model;
use Emarref\Jwt\Claim;
class UserModel extends Model
{
    protected $table='user';
    protected $key='1810b';
    //根据用户名获取用户信息
    public function getUserInfoByName($user_name){
        $user_info=$this->query("select * from __TABLE__ where user_name='$user_name'");
        if(!$user_info){
            return '';
        }
        return $user_info;
    }
    //根据用户id获取用户信息
    public function getUserInfoById($user_id){
        $userInfo=$this->query('select * from __TABLE__ where uid='."'$user_id'");
        return $userInfo;
    }
    //生成token
    public function createToken($user_id){
        $token = new \Emarref\Jwt\Token();

//      可选参数
        $token->addClaim(new Claim\Audience(['audience_1', 'audience_2']));
        $token->addClaim(new Claim\Expiration(new \DateTime('30 minutes')));//过期时间
        $token->addClaim(new Claim\IssuedAt(new \DateTime('now'))); //当前时间
        $token->addClaim(new Claim\Issuer('wuyanzu')); //颁发者
        $token->addClaim(new Claim\JwtId($user_id)); //ID
        $token->addClaim(new Claim\NotBefore(new \DateTime('now')));
        $token->addClaim(new Claim\Subject('Hahaha'));//主题

//        自定义参数
        $token->addClaim(new Claim\PublicClaim('user_id', $user_id));
        //创建实例
        $jwt = new \Emarref\Jwt\Jwt();
        $algorithm = new \Emarref\Jwt\Algorithm\Hs256($this->key);
        $encryption = \Emarref\Jwt\Encryption\Factory::create($algorithm);
        $serializedToken = $jwt->serialize($token, $encryption);
       return $serializedToken;
    }
    //验证token
    public function checkToken($access_token){
        $jwt = new \Emarref\Jwt\Jwt();
        //反序列化
        $token = $jwt->deserialize($access_token);
        //创建实力化
        $algorithm = new \Emarref\Jwt\Algorithm\Hs256($this->key);
        $encryption = \Emarref\Jwt\Encryption\Factory::create($algorithm);
        //验证
        $context = new \Emarref\Jwt\Verification\Context($encryption);
        $context->setAudience('audience_1');
        $context->setIssuer('wuyanzu');
        $context->setSubject('Hahaha');
        try {
            $jwt->verify($token, $context);
        } catch (\Emarref\Jwt\Exception\VerificationException $e) {
            return $e->getMessage();die;
        }
        //验证成功,返回用户信息
        $user_id=$token->getPayload()->findClaimByName('user_id')->getValue();
        $userInfo=$this->getUserInfoById($user_id);
        if(!$userInfo){
            return null;
        }
        return $userInfo;
    }
}