<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tsc\CatStorageSystem;

/**
 * Description of DirectoryRepository
 *
 * @author michael.hampton
 */
class DirectoryFactory implements DirectoryFactoryInterface {

    /**
     * 
     * @return \Tsc\CatStorageSystem\Directory
     */
    public function build(string $path) {

        if (!is_dir($path) || empty($path))
        {

            return false;
        }

        $name = basename($path);
        $createdDate = \DateTime::createFromFormat('d/m/Y', date("d/m/Y", filectime($path)));

        $fullPath = strpos($path, getcwd()) === false ? getcwd() . '/' . $path : $path;

        $objDirectory = new Directory();
        $objDirectory->setCreatedTime($createdDate);
        $objDirectory->setName($name);
        $objDirectory->setPath($fullPath);

        return $objDirectory;
    }

    /**
     * 
     * @param string $newName
     * @return \Tsc\CatStorageSystem\Directory
     */
    public function createNewDirectory(string $newName): Directory {

        $objDirectory = new Directory();
        $objDirectory->setName($newName);

        return $objDirectory;
    }

}
