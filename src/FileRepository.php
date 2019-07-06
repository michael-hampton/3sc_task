<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tsc\CatStorageSystem;

/**
 * Description of FileRepository
 *
 * @author michael.hampton
 */
class FileRepository implements FileRepositoryInterface {
    
    private $objDirectory;
    
    private $file;

    /**
    * 
    * @param \Tsc\CatStorageSystem\DirectoryInterface $objDirectory
    */
    public function __construct(DirectoryInterface $objDirectory, string $file) {
        $this->objDirectory = $objDirectory;
        $this->file = $file;
    }
    
    /**
     * 
     * @return \Tsc\CatStorageSystem\File
     */
    public function build() {
        
        $filePath = $this->objDirectory->getPath() . '/' . $this->file;
        
        if(!is_file($filePath)) {
            
            return false;
        }
        
        $modified = \DateTime::createFromFormat('d/m/Y', date("d/m/Y", filemtime($filePath)));
        $size = filesize($filePath);
                
        $objFile = new File();
        $objFile->setParentDirectory($this->objDirectory);
        $objFile->setModifiedTime($modified);
        $objFile->setName($this->file);
        $objFile->setSize($size);
        
        return $objFile;
    }
}
