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

    /**
     * 
     * @param \Tsc\CatStorageSystem\DirectoryInterface $objDirectory
     * @param string $file
     * @return boolean|\Tsc\CatStorageSystem\File
     */
    public function build(DirectoryInterface $objDirectory, string $file) {

        $filePath = $objDirectory->getPath() . '/' . $file;

        if (!is_file($filePath))
        {

            return false;
        }

        $modified = \DateTime::createFromFormat('d/m/Y', date("d/m/Y", filemtime($filePath)));
        $size = filesize($filePath);

        $objFile = new File();
        $objFile->setParentDirectory($objDirectory);
        $objFile->setModifiedTime($modified);
        $objFile->setName($file);
        $objFile->setSize($size);

        return $objFile;
    }

}
