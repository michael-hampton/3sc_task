<?php

require 'src/Config.php';
require 'src/DirectoryRepositoryInterface.php';
require 'src/DirectoryRepository.php';
require 'src/FileRepositoryInterface.php';
require 'src/FileRepository.php';
require 'src/FileSystemInterface.php';
require 'src/DirectoryInterface.php';
require 'src/FileInterface.php';
require 'src/File.php';
require 'src/Directory.php';
require 'src/FileSystem.php';

try {
    $objConfig = new \Tsc\CatStorageSystem\Config();
    $objFileSystem = new \Tsc\CatStorageSystem\FileSystem($objConfig);
    $objDirectoryRepository = new Tsc\CatStorageSystem\DirectoryRepository();
    $objFileRepository = new \Tsc\CatStorageSystem\FileRepository();
} catch (Exception $ex) {
    die($ex);
}

if (empty($argv) || empty($argv[1]))
{
    die('you must specify the method you want to run');
}

$method = $argv[1];

if ($method != 'createRootDirectory')
{

    if (empty($argv[2]))
    {
        die('You must specify the directory name');
    }

    $directoryname = $argv[2];
    $objDirectory = $objDirectoryRepository->build($directoryname);

    if (!$objDirectory)
    {
        die('unable to create directory object');
    }
}


switch ($method)
{

    case 'updateFile':
        $objFile = getFileObject($objFileRepository, $objDirectory, $argv);
        $blResponse = $objFileSystem->updateFile($objFile);
        break;

    case 'createDirectory':
        if (empty($argv[3]))
        {
            die('You must specify the new directory name');
        }

        $newDirectory = $objDirectoryRepository->createNewDirectory($argv[3]);
        $blResponse = $objFileSystem->createDirectory($newDirectory, $objDirectory);
        break;

    case 'getFiles':
        $arrFiles = $objFileSystem->getFiles($objDirectory);

        if (!$arrFiles)
        {
            die('Unable to get files');
        }

        echo '<pre>';
        print_r($arrFiles);
        die;

        break;

    case 'getDirectories':
        $arrDirectories = $objFileSystem->getDirectories($objDirectory);

        if (!$arrDirectories)
        {
            die('Unable to get files');
        }

        break;

    case 'getDirectorySize':
        $size = $objFileSystem->getDirectorySize($objDirectory);

        die('The directory size is ' . $size);

        break;

    case'getFileCount':
        $fileCount = $objFileSystem->getFileCount($objDirectory);

        die('The file count is ' . $fileCount);
        break;

    case 'getDirectoryCount':
        $directoryCount = $objFileSystem->getDirectoryCount($objDirectory);

        die('The file count is ' . $directoryCount);
        break;

    case 'renameDirectory':
        if (empty($argv[3]))
        {
            die('You must specify the new directory name');
        }


        $blResponse = $objFileSystem->renameDirectory($objDirectory, $argv[3]);
        break;

    case 'deleteDirectory':
        $blResponse = $objFileSystem->deleteDirectory($objDirectory);
        break;

    case 'createRootDirectory':

        $newDirectoryName = $argv[2];
        $objNewDirectory = $objDirectoryRepository->createNewDirectory($newDirectoryName);
        $blResponse = $objFileSystem->createRootDirectory($objNewDirectory);

        break;

    case 'deleteFile':

        $filename = $argv[3];
        $objFile = getFileObject($objFileRepository, $objDirectory, $argv);

        $blResponse = $objFileSystem->deleteFile($objFile);
        break;

    case 'createFile':

        if (empty($argv[4]))
        {
            die('You must specify the new path');
        }

        $newDirectoryName = $argv[4];
        $objParentDirectory = $objDirectoryRepository->build($newDirectoryName);

        $objFile = getFileObject($objFileRepository, $objDirectory, $argv);

        $blResponse = $objFileSystem->createFile($objFile, $objParentDirectory);
        break;

    case 'renameFile':

        if (empty($argv[4]))
        {
            die('You must specify the new file name');
        }

        $objFile = getFileObject($objFileRepository, $objDirectory, $argv);

        $blResponse = $objFileSystem->renameFile($objFile, $argv[4]);
        break;
}


if (!$blResponse)
{
    $strMessage = 'We were not able to complete the action';

    $arrErrors = $objFileSystem->getErrors();

    if (is_array($arrErrors) && !empty($arrErrors))
    {

        $strMessage .= ' Errors: ' . implode('<br>', $arrErrors);
    }


    echo $strMessage;
    die;
}

/**
 * 
 * @param \Tsc\CatStorageSystem\FileRepositoryInterface $objFileRepository
 * @param Tsc\CatStorageSystem\DirectoryInterface $objDirectory
 * @param array $argv
 * @return type
 */
function getFileObject(\Tsc\CatStorageSystem\FileRepositoryInterface $objFileRepository, Tsc\CatStorageSystem\DirectoryInterface $objDirectory, array $argv) {

    if (empty($argv[3]))
    {
        die('You must specify the file name');
    }

    $filename = $argv[3];

    $objFile = $objFileRepository->build($objDirectory, $filename);

    if (!$objFile)
    {
        die('unable to create file object');
    }

    return $objFile;
}

die('action completed successfully');
