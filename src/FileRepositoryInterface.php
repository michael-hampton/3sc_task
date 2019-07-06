<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tsc\CatStorageSystem;

/**
 * Description of FileRepositoryInterface
 *
 * @author michael.hampton
 */
interface FileRepositoryInterface {

    public function build(DirectoryInterface $objDirectory, string $file);
}
