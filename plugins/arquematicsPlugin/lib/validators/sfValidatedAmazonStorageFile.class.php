<?php
/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 * (c) 2010-2010 Henrik Bjornskov <henrik@bearwoods.dk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Extends sfValidatedFile to allow saving on S3. Requires a streamwrapper to be registere
 * to s3.
 *
 * @package    symfony
 * @subpackage validator
 * @author     Henrik Bjornskov <henrik@bearwoods.dk>
 */
class sfValidatedAmazonStorageFile extends sfValidatedFile
{
    /**
     * Saves the file to Amazon Simple Storage Service (S3)
     *
     * @param string $file filename or null
     * @param integer $fileMode (is ignored)
     * @param boolean $create should we create the directory if it dosent exists
     * @param integer $dirMode (is ignored)
     * @return string
     * @throws Exception
     */
    public function save($file = null, $fileMode = 0666, $create = true, $dirMode = 0777)
    {
        if (is_null($file)) {
            $file = $this->generateFilename();
        }
        
        $path = rtrim($this->path, '/') . DIRECTORY_SEPARATOR;
        
       
       
        if (!preg_match('/(s3([a-zA-Z0-9]+)?\:\/\/)([a-zA-Z0-9.]+)(\/)(.*)/', $path)) {
            throw new Exception('File path is not a valid s3 bucket.');
        }
        
        if (!@is_writeable($path) && $create && !@mkdir($path)) {
            throw new Exception(sprintf('File upload path "%s" is not writable.', $path));
        }
        
        $file = str_replace($path, '', $file);
        
        file_put_contents($path . $file, file_get_contents($this->getTempName()));
        
        $this->savedFile = $path . $file;
        
        return null === $this->path ? $file : str_replace($this->path.DIRECTORY_SEPARATOR, '', $file);
    }
}