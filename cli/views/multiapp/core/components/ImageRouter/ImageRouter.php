<?php

class ImageRouter extends CApplicationComponent
{
    public $imagesFolderAbsoluteUrl = '';
    public $imagesFolderAllias = 'images';
    public $maxWidth = 300;
    public $maxHeight = 300;
    public $thumb = array();
    public $thumbDir = '';
    
    private $subfolder = '';
    private $uploadedImagePath = null;
    private $pathToImagesFolder = null;
    private $imageUploader = null;
    
    public function getImageAbsoluteUrl( $path = '' )
    {
        return $this->imagesFolderAbsoluteUrl.'/'.$path;
    }
    
    public function getImageAbsoluteThumbUrl( $path = '' )
    {
        return $this->imagesFolderAbsoluteUrl.'/'.$this->thumbDir.'/'.$path;
    }
    
    public function setSubfolder($subfolder)
    {
        $this->subfolder = $subfolder;
    }
    
    public function setImageMaxSize($maxWidth, $maxHeight)
    {
        $this->maxHeight = $maxHeight;
        $this->maxWidth = $maxWidth;
    }
    public function setImageThumb($thumb)
    {
        if ($thumb === false)
            $this->thumb = null;
        else
        {
            $this->thumb['prefix'] = '';
            $this->thumb = CMap::mergeArray($this->thumb,$thumb);
        }
    }
       
    public function getUploadedImagePath()
    {
        return $this->uploadedImagePath;
    }
    
    public function uploadImage( $model, $attribute )
    {
        if( $this->isFileUploadedWithoutError($model, $attribute) )
        {
            $this->imageUploader = EUploadedImage::getInstance($model, $attribute);
            $this->imageUploader->maxWidth = $this->maxWidth;
            $this->imageUploader->maxHeight = $this->maxHeight;
            $filePath = $this->getFilePath();
            if ($this->createFolder())
            {
                if ($this->thumb !== null)
                {
                    $this->thumb['dir'] = '../'.$this->thumbDir.'/'.$this->subfolder;
                }
                $this->imageUploader->thumb = $this->thumb;
                if($this->imageUploader->saveAs($filePath) )
                {
                    return true;
                }
                else
                {
                    $this->setUploadedImagePath('');
                }
            }
        }
        else
        {
            return false;
        }
    }
    
    private function getFilePath()
    {
        $this->pathToImagesFolder = Yii::getPathOfAlias($this->imagesFolderAllias);
        $fileName = uniqid('', TRUE) . '.png';
        $this->setUploadedImagePath( $this->subfolder . '/' . $fileName );
        
        return $this->pathToImagesFolder .'/'. $this->subfolder . '/' . $fileName;
    }
    
    private function createFolder()
    {
        if (!is_dir($this->pathToImagesFolder .'/'. $this->subfolder ))
        {
            if (!mkdir($this->pathToImagesFolder.'/'. $this->subfolder , 0777, true))
                return false;
        }
        if ($this->thumb !== null)
        {
            $thumbfolder = $this->thumbDir.'/'. $this->subfolder;
            if (!is_dir($this->pathToImagesFolder .'/'.$thumbfolder))
            {
                if (!mkdir($this->pathToImagesFolder .'/'.$thumbfolder , 0777, true))
                    return false;
            }
        }
        return true;
    }
    
    private function isFileUploadedWithoutError( $model, $attribute )
    {
        $modelClassName = get_class( $model );
        return isset($_FILES[$modelClassName]) && $_FILES[$modelClassName]['error'][$attribute] == 0;
    }
    
    private function setUploadedImagePath( $path )
    {
        $this->uploadedImagePath = $path;
    }
}

?>
