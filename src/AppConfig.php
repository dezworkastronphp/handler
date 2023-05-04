<?php

namespace Astronphp\Handler;

class AppConfig{
    private $jsonApp    =   'tmp/database/app.json';
    public $argv        =   array();
    public $app         =   array();
    public $return      =   '';

    public function __construct($argv=array()){
        $this->argv = $argv;
        $this->defineApp();
        return $this; 
    }

    private function defineApp(){
        foreach ($this->argv as $key => $value) {
            if(substr($value,0,4)=='app:'){
                $this->app =  explode('/',substr($value,4));
                unset($this->argv[$key]);
            }
        }
        if(empty($this->app)){
            return  $this->listAllApps();
        }
        return $this->appJsonGenerate();
    }

    public function listAllApps(){
        $appjson = $this->getAstronjson();
        $this->return = "\e[31m Add an application to your command: \e[0m"."\n";
        foreach ($appjson['Applications'] as $key => $value) {
            foreach ($value as $k => $v) {
                $this->return .= "\e[0;37m    app:$key/".$k." \e[0m"."\n";
            }
        }
        return $this;
    }

    private function getAstronjson(){
        $astronjson = file_get_contents(PATH_ROOT."astronphp.json");
        $astronjson = json_decode($astronjson, true);
        return $astronjson;
    }
    
    private function appJsonGenerate(){
        $appjson = $this->getAstronjson();
        foreach ($this->app as $key => $value) {
            if(isset($appjson['Applications'][$value])){
                $appjson['Applications'] = $appjson['Applications'][$value];
                $appjson['Applications']['app'] = $this->app[0];
            }else{
                return $this->listAllApps();
            }
        }

        if(!file_exists(PATH_ROOT.$this->jsonApp)){
            $dir = explode('/',$this->jsonApp);
            unset($dir[count($dir)-1]);
            $dir = implode('/',$dir);
            if(!file_exists(PATH_ROOT.$dir)){
                mkdir(PATH_ROOT.$dir,0777,true);
            }
        }
        $fp = fopen(PATH_ROOT.$this->jsonApp, 'w');
        fwrite($fp, json_encode($appjson['Applications']));
        fclose($fp);
    }
}