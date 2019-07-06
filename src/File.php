<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tsc\CatStorageSystem;

/**
 * Description of File
 *
 * @author michael.hampton
 */
class File implements FileInterface {

    private $name;
    private $size;
    private $created;
    private $modified;
    private $parent;
    private $arrErrors = [];

    /**
     * 
     * @return type
     */
    public function getName() {
        return $this->name;
    }

    /**
     * 
     * @return type
     */
    public function getSize() {
        return $this->size;
    }

    /**
     * 
     * @return type
     */
    public function getCreatedTime() {
        return $this->created;
    }

    /**
     * 
     * @return type
     */
    public function getModifiedTime() {
        return $this->modified;
    }

    /**
     * 
     * @return type
     */
    public function getParentDirectory() {
        return $this->parent;
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
     * @param type $size
     */
    public function setSize($size) {
        $this->size = $size;
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
     * @param \Tsc\CatStorageSystem\DateTimeInterface $modified
     */
    public function setModifiedTime(\DateTimeInterface $modified) {
        $this->modified = $modified;
    }

    /**
     * 
     * @param \Tsc\CatStorageSystem\DirectoryInterface $parent
     */
    public function setParentDirectory(DirectoryInterface $parent) {
        $this->parent = $parent;
    }

    /**
     * @return string
     */
    public function getPath() {
        $objCurrentFile = $this->getParentDirectory();
        $fileName = $this->getName();
        $currentPath = $objCurrentFile->getPath();
        $fullPath = $currentPath . '/' . $fileName;

        return $fullPath;
    }

    /**
     * 
     * @return type
     */
    public function getErrors() {

        return $this->arrErrors;
    }

}
