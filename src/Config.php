<?php

namespace Tsc\CatStorageSystem;

class Config {

    private $arrConfig = array(
        'fileSizeLimit'     => 3093363,
        'allowedExtensions' => array('gif'),
        'allowOverwrite' => false
    );

    public function getConfig() {

        return $this->arrConfig;
    }

}
