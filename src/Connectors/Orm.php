<?php

namespace Astronphp\Handler\Connectors;

class Orm{
    public  $return;
    private $parms      = array();
    private $commands   = array(
                                "orm:create-entity"         =>      'php '.PATH_ROOT.'vendor/astronphp/orm/src/schema/generate.php',
                                "orm:update-db"             =>      PATH_ROOT.'vendor/bin/doctrine orm:schema-tool:update --force'
                          );
    
    public function __construct($p=array()){
        $this->parms = $p;
        $this->moveConfigRoot();
        $this->executeShell();
        $this->removeConfigRoot();
        return $this;
    }

    private function moveConfigRoot(){
        copy(PATH_ROOT.'vendor/astronphp/orm/src/schema/cli-config.php', PATH_ROOT.'cli-config.php');
    }

    private function executeShell(){
        if(in_array($this->parms[1],array_flip($this->commands))==true){
            $this->return = system($this->commands[$this->parms[1]]);
        }else{
            array_shift($this->parms);
            $this->return = system('./vendor/bin/doctrine '.implode(' ',$this->parms));
        }
    }

    private function removeConfigRoot(){
        if(file_exists(PATH_ROOT."cli-config.php")) {
            unlink(PATH_ROOT."cli-config.php"); 
        }
    }
}