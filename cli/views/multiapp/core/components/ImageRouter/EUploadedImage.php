<?php
/**
 * EUploadedImage class file.
 *
 * @author Nicola Puddu
 */

/**
 * EUploadedFile represents the information for an uploaded image.
 *
 * Call {@link getInstance} to retrieve the instance of an uploaded image,
 * and then use {@link saveAs} to save it on the server.
 * You may also query other information about the file, including {@link name},
 * {@link tempName}, {@link type}, {@link size} and {@link error}.
 * If you need to resize the image this class does it in the most simple way possible
 *
 * @author Nicola Puddu
 */
class EUploadedImage extends CComponent
{
	static private $_files;

	private $_name;
	private $_tempName;
	private $_type;
	private $_size;
	private $_error;
	
	/**
	 * @var image resource identifier
	 */
	private $_image;
	/**
	 * @var int rappresent the image type
	 */
	private $_imageType;
	/**
	 * @var int rappresent the image current width
	 */
	private $_width;
	/**
	 * @var int rappresent the image current height
	 */
	private $_height;
	
	/**
	 * @var string default thumbnails prefix
	 */
	private $_thumb_prefix = 'thumb_';
	
	public $thumb = false;
	/**
	 * rappresent the max width and height of the image
	 * if this attributes are false no check will be made on the
	 * image.
	 * if this attributes have a value the image will be resized
	 * to them if it's bigger.
	 * if just one of them is setted and the image needs resizing the
	 * other parameter will be resized as well maintaing the image proportions
	 * @var mixed
	 */
	public $maxWidth = false;
	public $maxHeight = false;

	/**
	 * Returns an instance of the specified uploaded image.
	 * The file should be uploaded using {@link CHtml::activeFileField}.
	 * @param CModel $model the model instance
	 * @param string $attribute the attribute name. For tabular file uploading, this can be in the format of "[$i]attributeName", where $i stands for an integer index.
	 * @return CUploadedFile the instance of the uploaded file.
	 * Null is returned if no file is uploaded for the specified model attribute.
	 * @see getInstanceByName
	 */
	public static function getInstance($model, $attribute)
	{
                    try{
                        return self::getInstanceByName(CHtml::resolveName($model, $attribute));
                    }
                    catch (Exception $ex)
                    {
                        return CUploadedFile::getInstance($model, $attribute);
                    }
	}

	/**
	 * Returns all uploaded files for the given model attribute.
	 * @param CModel $model the model instance
	 * @param string $attribute the attribute name. For tabular file uploading, this can be in the format of "[$i]attributeName", where $i stands for an integer index.
	 * @return array array of CUploadedFile objects.
	 * Empty array is returned if no available file was found for the given attribute.
	 */
	public static function getInstances($model, $attribute)
	{
		return self::getInstancesByName(CHtml::resolveName($model, $attribute));
	}

	/**
	 * Returns an instance of the specified uploaded image.
	 * The name can be a plain string or a string like an array element (e.g. 'Post[imageFile]', or 'Post[0][imageFile]').
	 * @param string $name the name of the file input field.
	 * @return CUploadedFile the instance of the uploaded file.
	 * Null is returned if no file is uploaded for the specified name.
	 */
	public static function getInstanceByName($name)
	{
		if(null===self::$_files)
			self::prefetchFiles();

		return isset(self::$_files[$name]) && self::$_files[$name]->getError()!=UPLOAD_ERR_NO_FILE ? self::$_files[$name] : null;
	}

	/**
	 * Returns an array of instances for the specified array name.
	 *
	 * If multiple files were uploaded and saved as 'Files[0]', 'Files[1]',
	 * 'Files[n]'..., you can have them all by passing 'Files' as array name.
	 * @param string $name the name of the array of files
	 * @return array the array of CUploadedFile objects. Empty array is returned
	 * if no adequate upload was found. Please note that this array will contain
	 * all files from all subarrays regardless how deeply nested they are.
	 */
	public static function getInstancesByName($name)
	{
		if(null===self::$_files)
			self::prefetchFiles();

		$len=strlen($name);
		$results=array();
		foreach(array_keys(self::$_files) as $key)
			if(0===strncmp($key, $name, $len) && self::$_files[$key]->getError()!=UPLOAD_ERR_NO_FILE)
				$results[] = self::$_files[$key];
		return $results;
	}

	/**
	 * Cleans up the loaded CUploadedFile instances.
	 * This method is mainly used by test scripts to set up a fixture.
	 * @since 1.1.4
	 */
	public static function reset()
	{
		self::$_files=null;
	}

	/**
	 * Initially processes $_FILES superglobal for easier use.
	 * Only for internal usage.
	 */
	protected static function prefetchFiles()
	{
		self::$_files = array();
		if(!isset($_FILES) || !is_array($_FILES))
			return;

		foreach($_FILES as $class=>$info)
			self::collectFilesRecursive($class, $info['name'], $info['tmp_name'], $info['type'], $info['size'], $info['error']);
	}
	/**
	 * Processes incoming files for {@link getInstanceByName}.
	 * @param string $key key for identifiing uploaded file: class name and subarray indexes
	 * @param mixed $names file names provided by PHP
	 * @param mixed $tmp_names temporary file names provided by PHP
	 * @param mixed $types filetypes provided by PHP
	 * @param mixed $sizes file sizes provided by PHP
	 * @param mixed $errors uploading issues provided by PHP
	 */
	protected static function collectFilesRecursive($key, $names, $tmp_names, $types, $sizes, $errors)
	{
		if(is_array($names))
		{
			foreach($names as $item=>$name)
				self::collectFilesRecursive($key.'['.$item.']', $names[$item], $tmp_names[$item], $types[$item], $sizes[$item], $errors[$item]);
		}
		else
			self::$_files[$key] = new EUploadedImage($names, $tmp_names, $types, $sizes, $errors);
	}

	/**
	 * Constructor.
	 * Use {@link getInstance} to get an instance of an uploaded file.
	 * @param string $name the original name of the file being uploaded
	 * @param string $tempName the path of the uploaded file on the server.
	 * @param string $type the MIME-type of the uploaded file (such as "image/gif").
	 * @param integer $size the actual size of the uploaded file in bytes
	 * @param integer $error the error code
	 */
	protected function __construct($name,$tempName,$type,$size,$error)
	{
		$this->_name=$name;
		$this->_tempName=$tempName;
		$this->_type=$type;
		$this->_size=$size;
		$this->_error=$error;
		// load image info
		$image_info = getimagesize($tempName);
		if ($image_info[2] === IMAGETYPE_JPEG || $image_info[2] === IMAGETYPE_PNG || $image_info[2] === IMAGETYPE_GIF)
			$this->_imageType = $image_info[2];
		else
			throw new CException('not supported image', '400');
		
		switch ($this->_imageType) {
			case IMAGETYPE_JPEG:
				$this->_image = imagecreatefromjpeg($tempName);
				break;
			case IMAGETYPE_PNG:
				$this->_image = imagecreatefrompng($tempName);
				break;
			case IMAGETYPE_GIF:
				$this->_image = imagecreatefromgif($tempName);
				break;
		}
		$this->_width = $image_info[0];
		$this->_height = $image_info[1];
		
	}

	/**
	 * String output.
	 * This is PHP magic method that returns string representation of an object.
	 * The implementation here returns the uploaded file's name.
	 * @return string the string representation of the object
	 * @since 1.0.2
	 */
	public function __toString()
	{
		return $this->_name;
	}

	/**
	 * Saves the uploaded image.
	 * @param string $file the file path used to save the uploaded image
	 * @return boolean true whether the image is saved successfully
	 */
	public function saveAs($file)
	{
		if($this->_error==UPLOAD_ERR_OK)
		{
			// save the original
			$this->imagePrepare($this->maxWidth, $this->maxHeight);
			if ($this->saveImage($file)) {
				// create the thumbnail
				if ($this->thumb) {
					// create the thumbnail of the requested dimensions
					$thumb_max_width = isset($this->thumb['maxWidth']) ? $this->thumb['maxWidth'] : false;
					$thumb_max_height = isset($this->thumb['maxHeight']) ? $this->thumb['maxHeight'] : false; 
					$this->imagePrepare($thumb_max_width, $thumb_max_height);
					// get the thumb folder
					$thumb_dir = isset($this->thumb['dir']) ? $this->thumb['dir'].DIRECTORY_SEPARATOR : NULL;
					// get the thumb prefix
					$thumb_prefix = isset($this->thumb['prefix']) ? $this->thumb['prefix'] : $this->_thumb_prefix;
					// save the thumbnail
					if ($this->saveImage(substr_replace($file, $thumb_dir.$thumb_prefix, strrpos($file, DIRECTORY_SEPARATOR) + 1, 0)))
						return true;
				} else
					return true;
			}
		}
		
		return false;
	}
	
	/**
	 * 
	 * @param int $maxWidth image max width. is false if none is given
	 * @param int $maxHeight image max height. is false if none is given
	 */
	private function imagePrepare($maxWidth, $maxHeight)
	{
//            PrintAndDie::_(is_int($maxWidth),true );
		if (is_int($maxWidth) && $this->_width > $maxWidth) {
			$this->resizeImage($maxWidth, ($maxWidth * $this->_height / $this->_width));
		}
		if (is_int($maxHeight) && $this->_height > $maxHeight) {
			$this->resizeImage(($maxHeight * $this->_width / $this->_height), $maxHeight);
		}
                $this->resizeImage($this->_width, $this->_height);
	}
	
	/**
	 * resize the image according to the given parameters
	 * @param int $width
	 * @param int $height
	 */
	private function resizeImage($width, $height)
	{
		$new_image = imagecreatetruecolor($width, $height);
		imagealphablending($new_image, false);
		$transparent_color = imagecolorallocatealpha( $new_image, 0, 0, 0, 127 );
		imagefill($new_image, 0, 0, $transparent_color);
		imagecopyresampled($new_image, $this->_image, 0, 0, 0, 0, $width, $height, $this->_width, $this->_height);
		imagesavealpha($new_image, true);
		$this->_image = $new_image;
		$this->_width = $width;
		$this->_height = $height;
	}
	
	/**
	 * save the image on the server
	 * @param string $file the name that will be given to the image
	 */
	private function saveImage($file_name)
	{
		switch ($this->_imageType) {
			case IMAGETYPE_JPEG:
				return imagejpeg($this->_image,$file_name);
				break;
			case IMAGETYPE_PNG:
				return imagepng($this->_image,$file_name);
				break;
			case IMAGETYPE_GIF:
				return imagegif($this->_image,$file_name);
				break;
		}
	}

	/**
	 * @return string the original name of the file being uploaded
	 */
	public function getName()
	{
		return $this->_name;
	}

	/**
	 * @return string the path of the uploaded file on the server.
	 * Note, this is a temporary file which will be automatically deleted by PHP
	 * after the current request is processed.
	 */
	public function getTempName()
	{
		return $this->_tempName;
	}

	/**
	 * @return string the MIME-type of the uploaded file (such as "image/gif").
	 * Since this MIME type is not checked on the server side, do not take this value for granted.
	 * Instead, use {@link CFileHelper::getMimeType} to determine the exact MIME type.
	 */
	public function getType()
	{
		return $this->_type;
	}

	/**
	 * @return integer the actual size of the uploaded file in bytes
	 */
	public function getSize()
	{
		return $this->_size;
	}

	/**
	 * Returns an error code describing the status of this file uploading.
	 * @return integer the error code
	 * @see http://www.php.net/manual/en/features.file-upload.errors.php
	 */
	public function getError()
	{
		return $this->_error;
	}

	/**
	 * @return boolean whether there is an error with the uploaded file.
	 * Check {@link error} for detailed error code information.
	 */
	public function getHasError()
	{
		return $this->_error!=UPLOAD_ERR_OK;
	}

	/**
	 * @return string the file extension name for {@link name}.
	 * The extension name does not include the dot character. An empty string
	 * is returned if {@link name} does not have an extension name.
	 */
	public function getExtensionName()
	{
		if(($pos=strrpos($this->_name,'.'))!==false)
			return (string)substr($this->_name,$pos+1);
		else
			return '';
	}
}
