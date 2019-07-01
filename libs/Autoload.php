<?php
/**
 * Created by PhpStorm.
 * User: HP
 * Date: 2019/6/5
 * Time: 16:45
 */
namespace libs;
class Autoload
{
    function __construct()
    {

        spl_autoload_register([$this,"_autoload"]);//注册自动加载类
        $this->loadfuns('./functions');//自动加载函数
        
        // set_exception_handler([$this,'exception']);
        // set_error_handler([$this,'errorHandler']);
        // register_shutdown_function([$this,'shutDown']);
    }
    protected function _autoload($className){
        $ext='.php';
        $file=str_replace("\\",DIRECTORY_SEPARATOR,$className).$ext;
        if(file_exists($file)){
            include_once $file;
        }
    }
    //自动加载函数
    protected function loadfuns($path){
        if(is_dir($path)){
            if($dir=opendir($path)){
                while(($file = readdir($dir))!==false){
                    if($file!='.' && $file!='..'){
                        include_once $path.DIRECTORY_SEPARATOR.$file;
                    }
                }
            }
        }
    }
    public function exception(Throwable $exception){
        $message = $exception->getMessage().' in '.$exception->getFile().' : '.$exception->getLine();
        setLog('myapp',$message); 
    }
    public function errorHandler($errno,$errstr,$errfile,$errline){
        throw new ErrorException($errstr,0,$errno,$errfile,$errline);
    }
    public function shutDown(){
        if(!is_null($error = error_get_last()) && $this->fatal($error['type'])){
            $this->exception(new ErrorException($error['message'],$error['type'],0,$error['file'],$error['line']));
        }
    }
    public function fatal($type){
        return in_array($type,[E_COMPILE_ERROR,E_CORE_ERROR,E_ERROR,E_PARSE]);
    }
}
new Autoload();