<?php

/**
 * Handle file uploads via XMLHttpRequest
 */
class qqUploadedFileXhr 
{
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {    
        $input = fopen("php://input", "r");
        $temp = tmpfile();
        $realSize = stream_copy_to_stream($input, $temp);
        fclose($input);
        
        if ($realSize != $this->getSize()){            
            return false;
        }
        
        $target = fopen($path, "w");        
        fseek($temp, 0, SEEK_SET);
        stream_copy_to_stream($temp, $target);
        fclose($target);
        
        return true;
    }
    function getName() {
        return $_GET['qqfile'];
    }
    function getSize() {
        if (isset($_SERVER["CONTENT_LENGTH"])){
            return (int)$_SERVER["CONTENT_LENGTH"];            
        } else {
            throw new Exception('Getting content length is not supported.');
        }      
    }   
}

/**
 * Handle file uploads via regular form post (uses the $_FILES array)
 */
class qqUploadedFileForm {  
    /**
     * Save the file to the specified path
     * @return boolean TRUE on success
     */
    function save($path) {
        if(!move_uploaded_file($_FILES['qqfile']['tmp_name'], $path)){
            return false;
        }
        return true;
    }
    function getName() {
        return $_FILES['qqfile']['name'];
    }
    function getSize() {
        return $_FILES['qqfile']['size'];
    }
}

class FileXhrUpload 
{
    private $allowedExtensions = array();
    private $sizeLimit = 10485760;
    private $file;
    

    function __construct($allowedExtensions = array(), $sizeLimit = 10485760)
    {        
        $allowedExtensions = array_map("strtolower", $allowedExtensions);
            
        $this->allowedExtensions = $allowedExtensions;        
        $this->sizeLimit = $sizeLimit;
        
        $this->checkServerSettings();       

        if (isset($_GET['qqfile'])) {
            $this->file = new qqUploadedFileXhr();
        } elseif (isset($_FILES['qqfile'])) {
            $this->file = new qqUploadedFileForm();
        } else 
        {
            $this->file = false;
        }
        
    }
    
  
    
    /**
     * 
     * Returns array('success'=> true | 'success'=> false
     *                  'error'=>'' | 'error'=>'error message' )
     */
    function handleUpload($uploadDirectory, $replaceOldFile = true)
    {
        if (!FileUtils::setPathPermissions($uploadDirectory))
        {
            return array(
                'success'=> false,
                'name' => '',
                'file' => '',
                'error' =>  __("Server error. Upload directory isn't writable.", null, 'wall') );
        }
        
        if (!$this->file)
        {
            return array(
                'success'=> false,
                'name' => '',
                'file' => '',
                'error' => __("No files were uploaded.", null, 'wall'));
        }
        
        $size = $this->file->getSize();
        
        if ($size == 0)
        {
            return array(
                'success'=> false,
                'name' => '',
                'file' => '',
                'error' => __("File is empty", null, 'wall'));
        }
        
        if ($size > $this->sizeLimit) {
            return array(
                'success'=> false,
                'name' => '',
                'file' => '',
                'error' => __("File is too large", null, 'wall'));
        }
        
        $pathinfo = pathinfo($this->file->getName());
        $filename = FileUtils::generateFilename();
        //$filename = md5(uniqid());
        $ext = false;
        if (isset($pathinfo['extension']))
        {
           $ext = $pathinfo['extension']; 
        }
        
        $basename = false;
        if (isset($pathinfo['basename']))
        {
           $basename = $pathinfo['basename']; 
        }
        
        if (!$ext)
        {
            return array(
                'success'=> false,
                'name' => '',
                'file' => '',
                'error' => __("File has no extension.", array(), 'wall'));
        }
        else if($this->allowedExtensions && !in_array(strtolower($ext), $this->allowedExtensions)){
            $these = implode(', ', $this->allowedExtensions);
            return array(
                'success'=> false,
                'name' => '',
                'file' => '',
                'error' => __("File has an invalid extension, it should be one of %%these%%.", array($these), 'wall'));
        }
        
        if(!$replaceOldFile)
        {
            /// don't overwrite previous files that were uploaded
            while (file_exists($uploadDirectory . $filename . '.' . $ext)) {
                $filename .= rand(10, 99);
            }
        }
        
        if ($this->file->save($uploadDirectory .DIRECTORY_SEPARATOR. $filename . '.' . $ext))
        {
            $pathFile = $uploadDirectory .DIRECTORY_SEPARATOR. $filename . '.' . $ext;
            $ret = FileUtils::setFilePermissions($pathFile);
            return array(
                'success' => $ret,
                'name' => $basename,
                'file' => $filename . '.' . $ext,
                'error'=> '');
        } 
        else 
        {
            return array(
                'success'=> false,
                'name' => '',
                'file' => '',
                'error'=> __("Could not save uploaded file. The upload was cancelled.",array(),'wall'));
        }
        
    }
    
    private function checkServerSettings()
    {        
        $postSize = $this->toBytes(ini_get('post_max_size'));
        $uploadSize = $this->toBytes(ini_get('upload_max_filesize'));        
        
        if ($postSize < $this->sizeLimit || $uploadSize < $this->sizeLimit){
            $size = max(1, $this->sizeLimit / 1024 / 1024) . 'M';             
            die("{'error':'increase post_max_size and upload_max_filesize to $size'}");    
        }        
    }
    
    private function toBytes($str)
    {
        $val = trim($str);
        $last = strtolower($str[strlen($str)-1]);
        switch($last) {
            case 'g': $val *= 1024;
            case 'm': $val *= 1024;
            case 'k': $val *= 1024;        
        }
        return $val;
    }
    
}