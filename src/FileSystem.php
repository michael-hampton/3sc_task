<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Tsc\CatStorageSystem;

/**
 * Description of FileSystem
 *
 * @author michael.hampton
 */
class FileSystem implements FileSystemInterface {

    private $arrConfig;
    private $arrErrors = [];
    private $count = 0;

    /**
     * 
     * @param \Tsc\CatStorageSystem\Config $objConfig
     */
    public function __construct(Config $objConfig) {

        $this->arrConfig = $objConfig->getConfig();
    }

    /**
     * @param FileInterface   $file
     * @param DirectoryInterface $parent
     *
     * @return FileInterface
     */
    public function createFile(FileInterface $file, DirectoryInterface $parent) {

        $this->arrErrors = [];

        if ($file->getSize() > $this->arrConfig['fileSizeLimit'])
        {
            $this->arrErrors[] = 'The file you are trying to create is to big';
            return false;
        }

        // we check if extension is allowed regarding the security Policy settings
        if (!$this->is_allowed_file_type($file))
        {
            $this->arrErrors[] = 'The file has an invalid file type';
            return false;
        }

        $path = $parent->getPath();
        $fileName = $file->getName();
        $objCurrentFile = $file->getParentDirectory();
        $currentPath = $objCurrentFile->getPath() . '/' . $fileName;
        $newPath = $path . '/' . $fileName;

        if (!is_dir($path))
        {

            $this->arrErrors[] = 'Invalid file path given';
            return false;
        }

        if (file_exists($newPath) && !$this->arrConfig['allowOverwrite'])
        {
            $this->arrErrors[] = 'The file you are trying to create already exists';
            return false;
        }

        if (!rename($currentPath, $newPath))
        {
            $this->arrErrors[] = 'We were unable to move the file';
            return false;
        }

        return true;
    }

    /**
     * it is unclear as to what this method should do , so for now just updating the modified time on the file
     * @param FileInterface $file
     *
     * @return FileInterface
     */
    public function updateFile(FileInterface $file) {

        $this->arrErrors = [];
        $full_path = $file->getPath();

        if (!touch($full_path))
        {
            $this->arrErrors[] = 'Unable to update file';
            return false;
        }
        
        return true;
    }

    /**
     * @param FileInterface $file
     * @param string $newName
     *
     * @return FileInterface
     */
    public function renameFile(FileInterface $file, $newName) {

        $this->arrErrors = [];
        $objCurrentFile = $file->getParentDirectory();
        $fileName = $file->getName();
        $currentPath = $objCurrentFile->getPath();
        $fullPath = $currentPath . '/' . $fileName;
        $ext = $this->getFileExtension($file);

        $cleanString = $this->cleanString($newName);
        $newName = $cleanString . '.' . $ext;
        $newPath = $currentPath . '/' . $newName;

        if (!is_dir($currentPath))
        {
            $this->arrErrors[] = 'Invalid file path given';
            return false;
        }

        if (file_exists($currentPath . '/' . $newName) && !$this->arrConfig['allowOverwrite'])
        {
            $this->arrErrors[] = 'The file you are trying to create already exists';
            return false;
        }

        if (!rename($fullPath, $newPath))
        {

            $this->arrErrors[] = 'Unable to rename file';
            return false;
        }

        return true;
    }

    /**
     * @param FileInterface $file
     *
     * @return bool
     */
    public function deleteFile(FileInterface $file) {

        $this->arrErrors = [];

        $objDirectory = $file->getParentDirectory();
        $current_path = $objDirectory->getPath();
        $file_name = $file->getName();
        $file_path = $current_path . '/' . $file_name;

        if (!is_dir($current_path))
        {

            $this->arrErrors[] = 'The directory doesnt exist';
            return false;
        }

        if (!is_file($file_path))
        {

            $this->arrErrors[] = 'You cannot delete the file, it doesnt exist';
            return false;
        }

        if (!unlink($file_path))
        {

            $this->arrErrors[] = 'We were unable to delete the file';
            return false;
        }

        return true;
    }

    /**
     * I have made the assumption here that the root directory is 3sc-php-task and not the images directory
     * @param DirectoryInterface $directory
     *
     * @return DirectoryInterface
     */
    public function createRootDirectory(DirectoryInterface $directory) {

        $this->arrErrors = [];

        $current_path = getcwd();

        if (!$current_path || !is_dir($current_path))
        {

            $this->arrErrors[] = 'Unable to get root directory';
            return false;
        }

        $directory_name = $this->cleanString($directory->getName());
        $new_directory = $current_path . '/' . $directory_name;

        if (is_dir($new_directory))
        {
            $this->arrErrors[] = 'The directory you are trying to create already exists';
            return false;
        }
        if (!mkdir($new_directory, 0755))
        {
            $this->arrErrors[] = 'Unable to create new directory';
            return false;
        }

        return true;
    }

    /**
     * @param DirectoryInterface $directory
     * @param DirectoryInterface $parent
     *
     * @return DirectoryInterface
     */
    public function createDirectory(
    DirectoryInterface $directory, DirectoryInterface $parent
    ) {

        $this->arrErrors = [];

        $current_path = $parent->getPath();

        if (!is_dir($current_path))
        {

            $this->arrErrors[] = 'Unable to get parent directory';
            return false;
        }

        $directory_name = $this->cleanString($directory->getName());
        $new_directory = $current_path . '/' . $directory_name;
        
        if (is_dir($new_directory))
        {
            $this->arrErrors[] = 'The directory you are trying to create already exists';
            return false;
        }

        if (!mkdir($new_directory, 0755))
        {
            $this->arrErrors[] = 'Unable to create new directory';
            return false;
        }

        return true;
    }

    /**
     * only allows empty directories to be deleted
     * @param DirectoryInterface $directory
     *
     * @return bool
     */
    public function deleteDirectory(DirectoryInterface $directory) {

        $this->arrErrors = [];

        $delete_directory = $directory->getPath();
        $current_path = getcwd();

        if (trim($delete_directory) === trim($current_path))
        {
            $this->arrErrors[] = 'Are you joking?';
            return false;
        }

        if (!is_dir($delete_directory))
        {

            $this->arrErrors[] = 'Unable to get directory';
            return false;
        }

        if (!$this->is_dir_empty($delete_directory))
        {
            $this->arrErrors[] = 'The file cannot be deleted it is not empty';
            return false;
        }

        if (!rmdir($delete_directory))
        {
            $this->arrErrors[] = 'Unable to create new directory';
            return false;
        }

        return true;
    }

    /**
     * @param DirectoryInterface $directory
     * @param string $newName
     *
     * @return DirectoryInterface
     */
    public function renameDirectory(DirectoryInterface $directory, $newName) {

        $this->arrErrors = [];
        $directory_path = $directory->getPath();
        $current_path = getcwd();

        if (trim($directory_path) === trim($current_path))
        {
            $this->arrErrors[] = 'Are you joking?';
            return false;
        }

        if (!is_dir($directory_path))
        {

            $this->arrErrors[] = 'Unable to get directory';
            return false;
        }

        // Get the directory name
        $old_dir_name = substr($directory_path, strrpos($directory_path, '/') + 1);

        $new_dir_name = $this->cleanString($newName);
        $new_path = str_replace($old_dir_name, $new_dir_name, $directory_path);

        if (!rename($directory_path, $new_path))
        {
            $this->arrErrors[] = 'Unable to rename directory';
            return false;
        }

        return true;
    }

    /**
     * @param DirectoryInterface $directory
     *
     * @return int
     */
    public function getDirectoryCount(DirectoryInterface $directory) {

        $this->arrErrors = [];

        $current_path = $directory->getPath();
        $directory_count = 0;
        $dh = opendir($current_path);

        if (!is_dir($current_path))
        {

            $this->arrErrors[] = 'Unable to get directory';
            return false;
        }

        if (!$dh)
        {
            $this->arrErrors[] = 'Unable to read directory';
            return false;
        }

        while (($file = readdir($dh)) !== false)
        {
            if ($file != "." && $file != ".." && is_dir($current_path . DIRECTORY_SEPARATOR . $file))
            {
                $directory_count++;
            }
        }
        closedir($dh);

        return $directory_count;
    }

    /**
     * @param DirectoryInterface $directory
     *
     * @return int
     */
    public function getFileCount(DirectoryInterface $directory) {

        $this->arrErrors = [];
        $file_count = 0;
        $dir = $directory->getPath();

        if (!is_dir($dir))
        {

            $this->arrErrors[] = 'Unable to get directory';
            return false;
        }


        if ($handle = opendir($dir))
        {
            while (($file = readdir($handle)) !== false)
            {
                if (is_dir($dir . '/' . $file) || in_array($file, array('.', '..')))
                {
                    continue;
                }

                $file_count++;
            }
        }

        return $file_count;
    }

    /**
     * @param DirectoryInterface $directory
     *
     * @return int
     */
    public function getDirectorySize(DirectoryInterface $directory) {

        $this->arrErrors = [];
        $directory_path = $directory->getPath();

        if (!is_dir($directory_path))
        {

            $this->arrErrors[] = 'Unable to get directory';
            return false;
        }
    }

    /**
     * @param DirectoryInterface $directory
     *
     * @return DirectoryInterface[]
     */
    public function getDirectories(DirectoryInterface $directory) {

        $this->arrErrors = [];
        $dir = $directory->getPath();

        if (!is_dir($dir))
        {

            $this->arrErrors[] = 'Unable to get directory';
            return false;
        }

        $handle = opendir($dir);

        if (!$handle)
        {

            $this->arrErrors[] = 'Unable to read from directory';
            return false;
        }

        $arrDirectories = [];

        while (($file = readdir($handle)) !== false)
        {
            if (is_dir($dir . '/' . $file) && !in_array($file, array('.', '..')))
            {
                $arrDirectories[] = $file;
            }
        }

        return $arrDirectories;
    }

    /**
     * @param DirectoryInterface $directory
     *
     * @return FileInterface[]
     */
    public function getFiles(DirectoryInterface $directory) {

        $this->arrErrors = [];
        $dir = $directory->getPath();

        if (!is_dir($dir))
        {

            $this->arrErrors[] = 'Unable to get directory';
            return false;
        }

        $handle = opendir($dir);

        if (!$handle)
        {

            $this->arrErrors[] = 'Unable to read from directory';
            return false;
        }

        $arrFiles = [];

        while (($file = readdir($handle)) !== false)
        {
            if (is_file($dir . '/' . $file))
            {
                $arrFiles[] = $file;
            }
        }

        return $arrFiles;
    }

    public function getErrors() {

        return $this->arrErrors;
    }

    /**
     * 
     * @param \Tsc\CatStorageSystem\FileInterface $file
     * @return string
     */
    private function getFileExtension(FileInterface $file): string {

        $ext = pathinfo($file->getName(), PATHINFO_EXTENSION);

        if (!$ext)
        {

            return false;
        }

        return strtolower($ext);
    }

    /**
     * 
     * @param \Tsc\CatStorageSystem\FileInterface $file
     * @return boolean
     */
    private function is_allowed_file_type(FileInterface $file) {

        $ext = $this->getFileExtension($file);

        if (!$ext)
        {

            return false;
        }

        if (!in_array($ext, $this->arrConfig['allowedExtensions']))
        {

            return false;
        }

        return true;
    }

    /**
     * https://stackoverflow.com/questions/2021624/string-sanitizer-for-filename
     * @param string $str
     * @return type
     */
    public static function cleanString(string $str) {
        $str = strip_tags($str);
        $str = preg_replace('/[\r\n\t ]+/', ' ', $str);
        $str = preg_replace('/[\"\*\/\:\<\>\?\'\|]+/', ' ', $str);
        $str = strtolower($str);
        $str = html_entity_decode($str, ENT_QUOTES, "utf-8");
        $str = htmlentities($str, ENT_QUOTES, "utf-8");
        $str = preg_replace("/(&)([a-z])([a-z]+;)/i", '$2', $str);
        $str = str_replace(' ', '-', $str);
        $str = rawurlencode($str);
        $str = str_replace('%', '-', $str);
        return $str;
    }

    /**
     * 
     * @param type $dir
     * @return boolean
     */
    private function is_dir_empty($dir) {
        if (!is_readable($dir))
        {

            return false;
        }

        return (count(glob("$dir/*")) === 0) ? true : false;
    }

}
