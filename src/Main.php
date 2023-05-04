<?php

namespace Astronphp\Handler;

class Main{
    private $argv;
    public $return;
    public $app;
    private $configApp;

    public function __construct($argv=array()){
        $this->argv = $argv;
        
        $lib=(strpos($this->argv[1],':')!==false?explode(':',$this->argv[1])[0]:$this->argv[1]);

        switch ($lib){
            case 'orm': 
                $this->defineApp();
                if(!empty($this->return)){
                    return $this;
                }
                $this->return = $this->orm(); 
            break;
            case 'front': $this->return = $this->frontView(); break;
            default : $this->return = "\e[31mCommand not found\e[0m"."\n"; break;
        }
       
        return $this;
    }

    private function defineApp(){
        $this->configApp        = new \Astronphp\Handler\AppConfig($this->argv);
        $this->app              = $this->configApp->app;
        $this->return           = $this->configApp->return;
        $this->argv             = $this->configApp->argv;
        if(empty($this->app)){
            return  $this->configApp->listAllApps();
        }
    }

    private function orm(){
        if(class_exists('\Astronphp\Handler\Connectors\Orm')){
            $this->return = (new \Astronphp\Handler\Connectors\Orm($this->argv))->return;
        }else{
            return "\e[31m composer require astronphp/orm \e[0m"."\n";
        }
    }
    
    private function frontView(){
        if(class_exists('\Astronphp\Handler\Connectors\FrontView')){
            $this->return =  (new \Astronphp\Handler\Connectors\FrontView($this->argv))->return;
        }else{
            $this->return =  "\e[31m composer require astronphp/frontview \e[0m"."\n";
        }
    }

}