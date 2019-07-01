<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2019/6/5
 * Time: 19:46
 */

namespace libs;
use Emarref\Jwt\Claim;

class Model
{
    protected $table='';
    protected $pdo;
    public function __construct()
    {
        //连接数据源
        $this->pdo = new \PDO('mysql:host=127.0.0.1;dbname=test;charset=utf8','root','root');
        if($this->table==''){
            $this->table=$this->getTableName();
        }
    }
//  获取默认表名
    public function getTableName(){
//        获取表名
        $tableName=get_called_class();
        $tableName=strtolower(substr($tableName,strpos($tableName,'\\')+1));
        return $tableName;
    }
    //查询
    public function query($sql,$data=[]){

        $sql=$this->replaceTableName($sql);
        // 预处理
		$stm = $this->pdo->prepare($sql);
		$stm->execute($data);
		// 返回所有的结果
		$res=$stm->fetchAll(\PDO::FETCH_ASSOC);
		if(count($res)==1){
		    return $res[0];
        }
		return $res;
    }
    //增，删，改
    public function exec($sql,$data=[]){
        $sql=$this->replaceTableName($sql);
        //预处理
        $stm=$this->pdo->prepare($sql);
        $res=$stm->execute($data);
        return $res;
    }
    //替换表名
    public function replaceTableName($sql){
        return str_replace('__TABLE__',$this->table,$sql);
    }
}