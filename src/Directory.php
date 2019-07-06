<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tsc\CatStorageSystem;

/**
 * Description of Directory
 *
 * @author michael.hampton
 */
class Directory implements DirectoryInterface {
    
    private $name;
    private $created;
    private $path;
    private $arrErrors = [];


    public function getName() {
        return $this->name;
    }

    public function getCreatedTime() {
        return $this->created;
    }

    public function getPath() {
        return $this->path;
    }

    /**
     * 
     * @param type $name
     */
    public function setName($name) {
        $this->name = $name;
    }

    /**
     * 
     * @param \Tsc\CatStorageSystem\DateTimeInterface $created
     */
    public function setCreatedTime(\DateTimeInterface $created) {
        $this->created = $created;
    }

    /**
     * 
     * @param type $path
     */
    public function setPath($path) {
        $this->path = $path;
    }
    
    public function getArrErrors() {
        return $this->arrErrors;
    }
}
