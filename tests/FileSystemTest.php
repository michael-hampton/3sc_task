<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tsc\CatStorageSystem;

use PHPUnit\Framework\TestCase;

$root = getcwd();

require $root . '/src/Config.php';
require $root . '/src/DirectoryRepositoryInterface.php';
require $root . '/src/DirectoryRepository.php';
require $root . '/src/FileRepositoryInterface.php';
require $root . '/src/FileRepository.php';
require $root . '/src/FileSystemInterface.php';
require $root . '/src/DirectoryInterface.php';
require $root . '/src/FileInterface.php';
require $root . '/src/File.php';
require $root . '/src/Directory.php';
require $root . '/src/FileSystem.php';

/**
 * Description of FileSystemTest
 *
 * @author michael.hampton
 */
class FileSystemTest extends TestCase {

    private $objFileSystem;
    private $objDirectoryRepository;
    private $objFileRepository;

    public function __construct($name = null, array $data = array(), $dataName = '') {
        parent::__construct($name, $data, $dataName);

        $objConfig = new Config();
        $this->objFileRepository = new FileRepository();
        $this->objFileSystem = new FileSystem($objConfig);
        $this->objDirectoryRepository = new DirectoryRepository();
    }

    public function test_update_file() {

        $filename = 'marbles.gif';
        $directoryname = 'images';
        $objDirectory = $this->objDirectoryRepository->build($directoryname);


        $objFile = $this->objFileRepository->build($objDirectory, $filename);
        $blResponse = $this->objFileSystem->updateFile($objFile);
        $this->assertTrue($blResponse);
    }

    public function test_create_directory() {
        $directoryname = 'images';
        $objDirectory = $this->objDirectoryRepository->build($directoryname);
        $newDirectory = $this->objDirectoryRepository->createNewDirectory('test_uan');
        $blResponse = $this->objFileSystem->createDirectory($newDirectory, $objDirectory);
        $this->assertTrue($blResponse);
    }

    public function test_get_files() {
        $directoryname = 'images';
        $objDirectory = $this->objDirectoryRepository->build($directoryname);
        $arrFiles = $this->objFileSystem->getFiles($objDirectory);
        $blResponse = is_array($arrFiles);
        $this->assertTrue($blResponse);
    }

    public function test_get_directories() {
        $directoryname = 'images';
        $objDirectory = $this->objDirectoryRepository->build($directoryname);
        $arrDirectories = $this->objFileSystem->getDirectories($objDirectory);
        $blResponse = is_array($arrDirectories);
        $this->assertTrue($blResponse);
    }

    public function test_get_file_count() {
        $directoryname = 'images';
        $objDirectory = $this->objDirectoryRepository->build($directoryname);
        $fileCount = $this->objFileSystem->getFileCount($objDirectory);
        $this->assertInternalType("int", $fileCount);
    }

    public function test_get_directory_count() {
        $directoryname = 'images';
        $objDirectory = $this->objDirectoryRepository->build($directoryname);
        $directoryCount = $this->objFileSystem->getDirectoryCount($objDirectory);
        $this->assertInternalType("int", $directoryCount);
    }

    public function test_rename_directory() {

        $directoryname = 'images/test_tamara';
        $objDirectory = $this->objDirectoryRepository->build($directoryname);
        $blResponse = $this->objFileSystem->renameDirectory($objDirectory, 'test_tamara_2');
        $this->assertTrue($blResponse);
    }

    public function test_directory_not_existing() {

        $directoryname = 'images/test_lexie2';
        $objDirectory = $this->objDirectoryRepository->build($directoryname);
        $this->assertFalse($objDirectory);
    }

    public function test_create_root_directory() {
        $newDirectoryName = 'mikes_root_directory';
        $objNewDirectory = $this->objDirectoryRepository->createNewDirectory($newDirectoryName);
        $blResponse = $this->objFileSystem->createRootDirectory($objNewDirectory);
        $this->assertTrue($blResponse);
    }

    public function test_delete_file() {
        $filename = 'test.txt';
        $directoryname = 'images';
        $objDirectory = $this->objDirectoryRepository->build($directoryname);

        $objFile = $this->objFileRepository->build($objDirectory, $filename);

        $blResponse = $this->objFileSystem->deleteFile($objFile);
        $this->assertTrue($blResponse);
    }

    public function test_create_file() {
        $directoryname = 'images';
        $objDirectory = $this->objDirectoryRepository->build($directoryname);
        $objParentDirectory = $this->objDirectoryRepository->build('images/test_lexie');

        $objFile = $this->objFileRepository->build($objDirectory, 'marbles.gif');

        $blResponse = $this->objFileSystem->createFile($objFile, $objParentDirectory);
        $this->assertTrue($blResponse);
    }

    public function test_rename_file() {
        $directoryname = 'images';
        $objDirectory = $this->objDirectoryRepository->build($directoryname);

        $objFile = $this->objFileRepository->build($objDirectory, 'test.txt');
        $blResponse = $this->objFileSystem->renameFile($objFile, 'test_mike');
        $this->assertTrue($blResponse);
    }
}
