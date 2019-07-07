<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tsc\CatStorageSystem;

/**
 * Description of RepositoryInterface
 *
 * @author michael.hampton
 */
interface DirectoryFactoryInterface {

    public function build(string $path);

    /**
     * 
     * @param string $newName
     */
    public function createNewDirectory(string $newName): Directory;
}
