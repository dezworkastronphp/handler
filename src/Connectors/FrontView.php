<?php

namespace Astronphp\Handler\Connectors;

class FrontView{
    public  $return;
    private $parms      = array();
    private $commands   = array(
                                "front:build-template"      =>      'cd '.PATH_ROOT.'sources/@templatename && yarn build'
                          );
    
    public function __construct($p=array()){
        $this->parms = $p;
        $this->executeShell();
        return $this;
    }

    private function executeShell(){
        if(in_array($this->parms[1],array_flip($this->commands))==true){
            if($this->parms[1]=='front:build-template'){
                if(file_exists(PATH_ROOT.'templates/'.$this->parms[2])){
                    $this->return = system(
                        str_replace('@templatename',$this->parms[2],$this->commands[$this->parms[1]])
                    );
                }else{
                    $this->return = "\e[31mTemplate '".$this->parms[2]."' not fount\e[0m\n";
                }
            }
        }
    }
}