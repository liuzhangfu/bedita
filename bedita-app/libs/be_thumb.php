<?php
/*-----8<--------------------------------------------------------------------
 *
 * BEdita - a semantic content management framework
 *
 * Copyright 2013 ChannelWeb Srl, Chialab Srl
 *
 * This file is part of BEdita: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published
 * by the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * BEdita is distributed WITHOUT ANY WARRANTY; without even the implied
 * warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 * See the GNU Lesser General Public License for more details.
 * You should have received a copy of the GNU Lesser General Public License
 * version 3 along with BEdita (see LICENSE.LGPL).
 * If not, see <http://gnu.org/licenses/lgpl-3.0.html>.
 *
 *------------------------------------------------------------------->8-----
 */

/**
 * Thumbnail utilities class
 */
class BeThumb {

	// supported image types,  "mime type" => "img type for internal use"
	private $supportedTypes = array(
					"image/gif" => "gif", 
					"image/jpeg" => "jpg", 
					"image/pjpeg" => "jpg",
					"image/png" => "png",
					"image/svg+xml" => "svg",
	);
	
	/**
	 * Source image data
	 * @var array
	 */
	private $imageInfo   = array();


	/**
	 * Target image data (thumbnail)
	 * @var array
	 */
	private $imageTarget = array();
		
	
	/**
	 * Link to "missing image" url img (could be different in backend o frontend)
	 */
	private $imgMissingFile = null;

	/**
	 * Link to "unsupported mime" url image (could be different in backend o frontend)
	 */
	private $imgUnsupported = null;
	
	
	/**
	 * All known mime types
	 * internal use (if needed), read from config/mime.types.php
	 */
	private $knownMimeTypes = array();
	
	function __construct() {
		$this->imgMissingFile = Configure::read('imgMissingFile');
		if (!BACKEND_APP) {
			if (!file_exists(WWW_ROOT . $this->imgMissingFile)) {
				$this->imgMissingFile = Configure::read('beditaUrl') . $this->imgMissingFile;
			}
		}
		$this->imgUnsupported = Configure::read('imgUnsupported');
		if (!BACKEND_APP) {
		    if (!file_exists(WWW_ROOT . $this->imgUnsupported)) {
		        $this->imgUnsupported = Configure::read('beditaUrl') . $this->imgUnsupported;
		    }
		}
	}

	public function getValidImplementations() {
		App::import ('Vendor', 'phpthumb', array ('file' => 'php_thumb' . DS . 'ThumbLib.inc.php') );
		return PhpThumbFactory::getValidImplementations();
	}

	/**
	 * Reset internal arrays
	 */
	private function reset() {
		
		$this->imageInfo = array (
				"filename"		=> "", // filename 
				"filenameBase"	=> "", // filename without extension
				"ext"			=> "", // file extension
				"path"			=> "", // relative path, included file
				"filepath"		=> "", // absolute file path
				"cachePath"		=> "", // relative cache dir path
				"cacheDirectory" => "",// absolute cache dir path
				
				"filesize"		=> "",
				"w"				=> "",
				"h"				=> "",
				"orientation"	=> "",
				"type"			=> "", // "gif", "jpg", "png", "jpeg", "svg"
				"mime_type"		=> "",
		);
	
		$this->imageTarget = array (
				"filename"		=> "",
				"filepath"		=> "",
				"uri"			=> "",
				"w"				=> "",
				"h"				=> "",
				"offsetx"		=> 0,
				"offsety"		=> 0,
				"type"			=> "", // "gif", "jpg", "png", "jpeg", "svg"
				"mode"			=> "",
				"fillcolor"		=> "",
				"cropmode"		=> "",
				"upscale"		=> false,
		);
	
	}
	
	
	/**
	 * image public method: embed an image after resample and cache
	 *
	 * @param: array $data, required, source image data (may be BE media object or other source) - required "uri"
	 * @param: array $params
	 * extra info about $params:
	 *
	 *         width, height, longside, optional: [integer]
	 *         if no parameters is set, use default width & height in bedita.ini.php (['image']['thumbWidth'], ['image']['thumbHeight'])
	 *         if longside is set, width & height are ignored and mode is forced to resize.
	 *         if only width or only height is specified, the mode and modeparam are ignored and forced to 'resize'.
	 *
	 *         mode, optional: [crop, croponly, resize, 'fill', 'stretch']
	 *         if not specified default bedita.ini.php (['image']['thumbMode']) is used (but always overrided by the rules above).
	 *         'fill' is legacy options: it will set mode always to 'resize' but modeparam to 'fill' only if longside is not set.
	 *         'stretch' is legacy options: it will set mode always to 'resize' and modeparam to 'stretch'.
	 *
	 *         modeparam, optional: [fill, stretch, 'C', 'T', 'B', 'L', 'R', 'TL', 'TR', 'BL', 'BR']
	 *         depends on mode:
	 *             if resize: 'fill' or 'stretch' and if left empty do simple resize.
	 *             if crop, a string describing crop zone 'C', 'T', 'B', 'L', 'R', 'TL', 'TR', 'BL', 'BR' (default: ['image']['thumbCrop'] )
	 *
	 *         cache, optional: [true, false]
	 *         allow the caching of images, default in bedita.ini (['image']['cache'] )
	 *
	 *         bgcolor, optional: [string]
	 *         string representing hex color, i.e. 'FFFFFF' for the background (only on mode=resize and modeparam=fill)
	 *         default in bedita.ini (['image']['background'] )
	 *
	 *         upscale, optional: [true, false]
	 *         allow or not upscale. default bedita.ini.php (['image']['thumbUpscale'])
	 *
	 *         type, optional: ['gif'/'png'/'jpg']
	 *         force image target type (default the same as original)
	 *
	 *         watermark, optional: [array: 'text', 'font', 'fontSize', 'textColor', 'background', 'file', 'align', 'opacity' ]
     *         add simple watermark to image, the missing parameters are replaced with the defaults in bedita.ini (['image']['wmi']* )
	 *
	 *
	 * @return: string, resampled and cached image URI (using $html helper)
	 *
	 */
	public function image($data, $params = array()) {
		
		$this->reset();
		
		// setup internal imageInfo array
		if (!$this->setupImageInfo($data)) {
            if (!empty($data['error']) && $data['error'] == 'unsupported') {
                return $this->imgUnsupported;
            } else {
                return $this->imgMissingFile;
            }
		}
		
		// if svg or animated gif skip and return image without any resize
		if($data['mime_type'] === 'image/svg+xml' || $this->isAnimatedGif()) {
			if ($this->imageInfo["remote"]) {
				return $this->imageInfo['path'];
			} else {
				return Configure::read('mediaUrl') . $this->imageInfo['path'];
			}
		}
		
		// test source file available
		if (!$this->imageInfo["remote"] && !$this->checkSourceFile()) {
			return $this->imgMissingFile;
		}
		
		// setup internal image target array
		$this->setupImageTarget($params);

		// Manage cache and resample if caching option is true
		// and the image it's not alredy cached
		$cacheExists = file_exists($this->imageTarget['filepath']);
		
		if (!$cacheExists && $this->imageTarget['type'] != 'svg' && !$this->isAnimatedGif() ) {
			if ( !$this->resample() ) {
				return $this->imgMissingFile;
			}
		}

		// return HTML <img> tag
		return $this->imageTarget['uri'];
	}

	/**
	 * Setup internal imageInfo data array
	 * On error $data['error'] is populated with:
	 *    - 'notFund' if image is missing or unreachable
	 *    - 'fileSys' on a local filesystem related error
	 *    - 'unsupported' if image format is not supported 
	 * 
	 * @param array $data
	 * @return true on success, false on error
	 */
	public function setupImageInfo(array &$data) {

	    // check uri
	    if (empty($data['uri'])) {
	        $source = !empty($data['id']) ? ' - obj id: ' . $data['id'] : '';
	        $this->triggerError("Missing image 'uri'" . $source);
	        $data['error'] = 'notFund';
	        return false;
	    }
	    
	    // "uri" could have "/" seaparator on a system with "\" as separator
	    $this->imageInfo["remote"] = ($data['uri'][0] !== "/" && $data['uri'][0] !== DS);
	    if ($this->imageInfo["remote"]) {
	        $uriParts = parse_url($data['uri']);
	        if (!$uriParts || (!empty($uriParts["scheme"]) && !in_array($uriParts["scheme"], array("http", "https")))) {
	            $this->triggerError("'" . $data['uri'] . "' unsupported uri protocol (only http/https)");
	            $data['error'] = 'notFund';
	            return false;
	        }
	        if (empty($data['path'])) {
	            $data['path'] = $uriParts["path"];
	        }
	    } else {
	        $data['path'] = $data['uri'];
	    }
	     
		// relative path (local files and remote uri)
		$this->imageInfo['path'] = $data['path'];
		
		// thumbnail setup
		$pathParts =  pathinfo($data['path']);

		// complete file name
		$this->imageInfo['filename'] = $pathParts['basename'];
		// file name without extension
		$this->imageInfo['filenameBase'] = $pathParts['filename'];
		// file extension
		$this->imageInfo['ext']	= (!empty($pathParts['extension']))? $pathParts['extension'] : "";
		$this->imageInfo['dirname']	= $pathParts['dirname'];
		$mediaRoot  = Configure::read('mediaRoot');
	    if (!$this->imageInfo["remote"]) {
			$this->imageInfo['filepath'] = $mediaRoot . $this->imageInfo['path'];  // absolute
			if (DS != "/") {
				$this->imageInfo['filepath'] = str_replace("/", DS, $this->imageInfo['filepath']);
			}
		} else {
			$this->imageInfo['filepath'] = $data['uri'];
		}
		// relative cachePath
		$cachePrefix = DS . "cache" . ($this->imageInfo["remote"] ? DS . "ext" : "");
		$this->imageInfo['cachePath'] = $cachePrefix . 
			BeLib::getInstance()->friendlyUrlString($this->imageInfo['path'], "\.\/");
		// absolute cache dir path
		$this->imageInfo['cacheDirectory'] = $mediaRoot . $this->imageInfo['cachePath'];
		if (!$this->checkCacheDirectory()) {
			$this->triggerError("Error creating/reading cache directory " . $this->imageInfo['cacheDirectory']);
	        $data['error'] = 'fileSys';
			return false;
		}
		
		$cacheData = $this->readCacheImageInfo();
		$data = array_merge($data, $cacheData);
		
		// check mime type
		if (empty($data['mime_type'])) {
			$data['mime_type'] = $this->mimeTypeByExtension($this->imageInfo['ext']);
		}
		if (!in_array($data["mime_type"], array_keys($this->supportedTypes))) {
			$this->triggerError("'" . $data['uri'] . "' mime type not supported: " . $data['mime_type']);
	        $data['error'] = 'unsupported';
			return false;
		}
		$this->imageInfo['mime_type'] = $data['mime_type'];
		$this->imageInfo['type'] = $this->supportedTypes[$data['mime_type']];		

		// if SVG skip thumbnail and other info
		if ($data['mime_type'] === 'image/svg+xml') { 
			return true;
		}

		if (empty($data['width']) || empty($data['height']) ) {

		    $imageFilePath = $this->imagePathCached();
		    $imageData = @getimagesize($imageFilePath);
            if (!$imageData) {
                $this->triggerError("'" . $this->imageInfo['filepath'] . "' is not a valid image file");
                $data['error'] = 'unsupported';
                return false;
            }
			// set up the rest of image info array
			$this->imageInfo['w'] = $imageData[0];
			$this->imageInfo['h'] = $imageData[1];
			// http://www.php.net/manual/en/function.getimagesize.php -- 
			// http://www.php.net/manual/en/function.exif-imagetype.php (constants)
			$types = array(1 => 'gif', 2 => 'jpg', 3 => 'png');
			if ($imageData[2] < 1 || $imageData[2] > 3) {
				$this->triggerError('Image type not supported [' . $imageData[2] . '] ' 
							. $this->imageInfo['filepath']);
                $data['error'] = 'unsupported';
				return false;
			}
			$this->imageInfo['type'] = $types[$imageData[2]];
			$this->imageInfo['mime_type'] = $imageData['mime'];
			$this->storeCacheImageInfo();
				
		} else {

			$this->imageInfo['w'] = $data['width'];
			$this->imageInfo['h'] = $data['height'];
		}
		
		return true;
	}

	
	/**
	 * Get image file path, get cached local copy behind a proxy 
	 */
	public function imagePathCached($imageFilePath = null) {
	    if (!empty($imageFilePath)) {
	        $data["uri"] = str_replace(Configure::read('mediaRoot'), "", $imageFilePath);
	        $this->setupImageInfo($data);
	    }
	    if ($this->imageInfo["remote"] && Configure::read("proxyOptions") != null) {
	        $imageFilePath = $this->remoteImageCachePathProxy();
	    } else {
	        $imageFilePath = $this->imageInfo["filepath"];
	    }
	    return $imageFilePath;
	}
	
	
	/**************************************************************************
	** private methods follow
	**************************************************************************/

	/**
	 * Get internal img info cache file name
	 */
	private function cacheImageInfoFilePath() {
		return $this->imageInfo['cacheDirectory'] . DS . ".imginfo";
	}
	
	/**
	 * Read cached image (i.e. if under proxy)
	 */
    private function remoteImageCachePathProxy() {
        $path =  $this->imageInfo['cacheDirectory'] . DS . ".source";
        if (!file_exists($path)) {
            if (!mkdir($path, 0777, true)) {
                $this->triggerError("Error creating cache directory: " . $path);
                return false;
            }
        }
        $path =  $path . DS . $this->imageInfo['filename'];
        if (!file_exists($path)) {
            $cont = file_get_contents($this->imageInfo['filepath'], false);
            if($cont) {
                file_put_contents($path, $cont);
            }
        }
        return $path;
    }
	
	/**
	 * Read cached img info, if present 
	 * @return array
	 */
	private function readCacheImageInfo() {
		$res = array();
		$imgInfoFile = $this->cacheImageInfoFilePath();
		if(file_exists($imgInfoFile)) {
			$cacheData = file_get_contents($imgInfoFile);
			$res = unserialize($cacheData);
		}
		return $res;
	}
	
	/**
	 * Store img info in cache file
	 */
	private function storeCacheImageInfo() {
		$imgInfoFile = $this->cacheImageInfoFilePath();
		if(!file_exists($imgInfoFile)) {
			$imgData = array( 
				"width" => $this->imageInfo["w"],
				"height" => $this->imageInfo["h"],
				"type" => $this->imageInfo["type"],
				"mime_type" => $this->imageInfo["mime_type"],
			);
			file_put_contents($imgInfoFile, serialize($imgData));
		}
	}
	
	/**
	 * Setup internal imageInfo data array
	 * 
	 */
	private function setupImageTarget(array $params) {
	    
		// read params as an associative array or multiple variable
		$expectedArgs = array ('width', 'height', 'longside', 'mode', 'modeparam', 'type', 'upscale',
				'cache', "watermark", );
		extract ($params);

		$imageConf = Configure::read('media.image');
		
		if (isset($watermark))	{
			$this->imageTarget['watermark'] = array_merge($imageConf["watermark"], $watermark);
		}
		
		// upscale
		if (isset($upscale))	{
			$this->imageTarget['upscale'] = $upscale;
		} else {
			$this->imageTarget['upscale'] = $imageConf['thumbUpscale'];
		}
		
		//background
		if ( isset ($bgcolor) ) {
			$this->imageTarget['fillcolor'] = $bgcolor;
		} else {
			$this->imageTarget['fillcolor'] = $imageConf['background'];
		}
		
		// cropmode
		$this->imageTarget['cropmode'] = $imageConf['thumbCrop'];
		
		// default mode, if not specified
		if ( !isset ($mode) ) {
			$mode = $imageConf['thumbMode'];
		}
		
		// target image type
		if ( !@empty($type) ) {
			$this->imageTarget['type'] = $type;
		} else {
			$this->imageTarget['type'] = $this->imageInfo['type'];
		}
		
		// Target image size: imageTarget['w'] & imageTarget['h']
		// [unused $this->_targetSize ($width, $height)]
		if ( empty($width) && empty($height) && !isset($longside) ) { //no parameter set, use default
			$width  = $imageConf['thumbWidth'];
			$height = $imageConf['thumbHeight'];
		
		} else if( !empty($longside) && is_numeric($longside) ) { // if is set longside, ignore the others parameters
			if ($this->imageInfo["w"] > $this->imageInfo["h"]) {
				$width  = $longside;
				$height = 0;
			}else {
				$width  = 0;
				$height = $longside;
			}
			//forcing the mode to "resize" if longside
			$mode = "resize";
			$modeparam = null;
		} else if(empty($width) || empty($height) ) { // case with only one parameter w/h
		
			if ( empty($width) ){
				$width  = 0;
			}
			if ( empty($height) ){
				$height = 0;
			}
			//forcing the mode to "resize" if one coordinate is empty
			$mode = "resize";
			$modeparam = null;
		}
		
		$this->imageTarget['w'] = $width;
		$this->imageTarget['h'] = $height;
		
		//Set the embed mode
		switch ($mode) {
			case "croponly":  //general crop
				$this->imageTarget['mode'] = 0;
				if ( isset ($modeparam) ) {
					$this->imageTarget['cropmode'] = $modeparam; // overwrite crop mode
				}
				break;
		
			case "crop": //adaptive crop
				$this->imageTarget['mode'] = 1;
				//if ( isset ($modeparam) )	$this->imageTarget['cropmode'] = $modeparam; // overwrite crop mode
				break;
		
			case "resize":
			default:
				$this->imageTarget['mode'] = 2;
				if ( isset ($modeparam) ) {
					$this->imageTarget['resizetype'] = $modeparam;
				}
		
				break;
		
				// legacy methods
			case "fill":
				$this->imageTarget['mode'] = 2;
		
				if (empty($longside)) {
					$this->imageTarget['resizetype'] = 'fill';
					if ( isset ($modeparam) )	{
						$this->imageTarget['fillcolor'] = $modeparam;
					}else {
						$this->imageTarget['fillcolor'] = $imageConf['thumbFill'];
					}
				}
				break;
		
			case "stretch":
				$this->imageTarget['mode'] = 2;
				$this->imageTarget['resizetype'] = 'stretch';
				break;
		}
		
		// target filename, filepath, uri
		$this->imageTarget['filename'] = $this->targetFileName();
		$this->imageTarget['filepath'] =  $this->imageInfo['cacheDirectory'] . DS . $this->imageTarget['filename'];
		$this->imageTarget['uri'] = Configure::read('mediaUrl') . 
				$this->imageInfo['cachePath'] . "/" . $this->imageTarget['filename'];
	}
	
	/**
	 * Resample image using PhpThumb
	 *
	 * @return boolean
	 */
	private function resample() {

	    $imageFilePath = $this->imageInfo['filepath'];
	    if($this->imageInfo["remote"] && Configure::read("proxyOptions") != null) {
	        $imageFilePath = $this->remoteImageCachePathProxy();
	    }
	    App::import ('Vendor', 'phpthumb', array ('file' => 'php_thumb' . DS . 'ThumbLib.inc.php') );
	    
	    try {
	        
    		$thumbnail = PhpThumbFactory::create($imageFilePath, Configure::read('media.image'));
    		$thumbnail->setDestination ( $this->imageTarget['filepath'], $this->imageTarget['type'] );
    		
    		//set upscale
    		if ($this->imageTarget['upscale']) {
    			$thumbnail->setOptions(array("resizeUp" => true));
    		}

    		// more params about resample mode
    		switch ( $this->imageTarget['mode'] ) {
    			// croponly
    			case 0:
    				list ($starX, $startY)  = $this->getCropCoordinates($this->imageInfo['w'], 
    						$this->imageInfo['h'], $this->imageTarget['w'], $this->imageTarget['h'], 
    						$this->imageTarget['cropmode'] );
    				$thumbnail->crop($starX, $startY, $this->imageTarget['w'], $this->imageTarget['h']);
    
    				break;
    			//crop: adaptive crop
    			case 1:
    			default:
    				$thumbnail->adaptiveResize($this->imageTarget['w'], $this->imageTarget['h']);
    				break;
    
    			// resize
    			case 2:
    
    				//stretch or fill of simple resize
    				if (empty($this->imageTarget['resizetype'])){
    
    					$thumbnail->resize($this->imageTarget['w'], $this->imageTarget['h']);
    
    				}else if ($this->imageTarget['resizetype'] == 'stretch') {
    
    					$thumbnail->resizeStretch($this->imageTarget['w'], $this->imageTarget['h']);
    
    				} else if ($this->imageTarget['resizetype'] == 'fill') {
    
    					$thumbnail->resizeFill($this->imageTarget['w'], $this->imageTarget['h'],  
    							$this->imageTarget['fillcolor']);
    				}
    				break;
    
    		}

            // add watermark
            if (isset($this->imageTarget['watermark'])) {
                $thumbnail->wmark($this->imageTarget['filepath'], $this->imageTarget['watermark']);
            }

    		if ($thumbnail->save($this->imageTarget['filepath'], $this->imageTarget['type'])) {
    			return true;
    		} else {
    			$this->triggerError("phpThumb error");
    			return false;
    		}
		} catch (Exception $e) {
		    $this->triggerError($e->getMessage());
		    return false;
		}
	}
	// end _resample

	/**
	 * Check source file existence/correctness
	 *
	 * @return boolean
	 */
	private function checkSourceFile() {
		if(!file_exists($this->imageInfo['filepath']) ) {
			$this->triggerError("file '" . $this->imageInfo['filepath'] . "' does not exist");
			return false;
		} else if(!is_readable ($this->imageInfo['filepath'])) {
			// cannot access source file on filesystem
			$this->triggerError("cannot read file '" . $this->imageInfo['filepath'] . "' on filesystem");
			return false;
		}
		return true;
	}

	/**
	 * Build target filename
	 *
	 * @return string
	 */
	private function targetFileName() {
		// build hash on file path, modification time and mode
		if($this->imageInfo["remote"]) {
			$modified = 0;
		} else {
			$modified = filemtime ($this->imageInfo['filepath']);
		}
		
		$wmString = "";
		$wm = null;
		if(isset($this->imageTarget["watermark"])) {
			$wm = $this->imageTarget["watermark"];
			$wmString = implode($wm);
			unset($this->imageTarget["watermark"]);
		}
		$targetStr = implode($this->imageTarget) . $wmString;
		if(!empty($wmString)) {
			$this->imageTarget["watermark"] = $wm;
		}
		
		$this->imageInfo['hash']  = md5 ( $this->imageInfo['filename'] . $modified . $targetStr);

		// target filename = orig_filename + "_" + w + "x" + h + "_" + hash + "." + ext
		$target = $this->imageInfo['filenameBase'] . "_" .
							$this->imageTarget['w'] . "x" . $this->imageTarget['h'] . "_" .
							$this->imageInfo['hash'] . "." . $this->imageTarget['type'];
		return BeLib::getInstance()->friendlyUrlString($target, "\.");
	}

	/**
	 * build target filepath
	 *
	 * @return string
	 */
	private function checkCacheDirectory() {
		if (!file_exists($this->imageInfo['cacheDirectory'])) {
			if (!mkdir($this->imageInfo['cacheDirectory'], 0777, true)) {
				$this->triggerError("Error creating cache directory: " . $this->imageInfo['cacheDirectory']);
				return false;
			}
		} elseif (!is_dir($this->imageInfo['cacheDirectory'])) {
			$this->triggerError("Not a direcotory: " . $this->imageInfo['cacheDirectory']);
			return false;
		}
		return true;
	}

	/**
	 * calculate cropping coordinates (top, left)
	 *
	 * @param int $origW, original width
	 * @param int $origH, original height
	 * @param int $targetW, target object width
	 * @param int $targetH, target object height
	 * @param string $position: TL (top left), T (top), TR (top right), L (left), R (right), BL (bottom left), B (bottom), BR (bottom right), C (center), default center
	 * @return array
	 */
	private function getCropCoordinates ( $origW, $origH, $targetW, $targetH, $position ) {
		$coordinates = array ();

		switch ($position) {
			case "TL":
				$coordinates['x']  = 0;
				$coordinates['y']  = 0;
				break;

			case "T":
				$coordinates['x']  = ($origW / 2) - ($targetW / 2);
				$coordinates['y']  = 0;
				break;

			case "TR":
				$coordinates['x']  = $origW - $targetW;
				$coordinates['y']  = 0;
				break;

			case "L":
				$coordinates['x']  = 0;
				$coordinates['y']  = ($origH / 2) - ($targetH / 2);
				break;

			case "R":
				$coordinates['x']  = $origW - $targetW;
				$coordinates['y']  = ($origH / 2) - ($targetH / 2);
				break;

			case "BL":
				$coordinates['x']  = 0;
				$coordinates['y']  = $origH - $targetH;
				break;

			case "B":
				$coordinates['x']  = ($origW / 2) - ($targetW / 2);
				$coordinates['y']  = $origH - $targetH;
				break;

			case "BR":
				$coordinates['x']  = $origW - $targetW;
				$coordinates['y']  = $origH - $targetH;
				break;

			case "C":
			case 1:
			default:
				$coordinates['x']  = ($origW / 2) - ($targetW / 2);
				$coordinates['y']  = ($origH / 2) - ($targetH / 2);
				break;
		}

		return array ($coordinates['x'], $coordinates['y']);
	}

	/**
	 * Log/report error
	 *
	 * @param string $errorMsg
	 */
	private function triggerError($errorMsg) {
        if (!class_exists('CakeLog')) {
            return;
        }
		CakeLog::write('error', get_class($this) . ": " . $errorMsg);
	}

    /**
     * Check whether current image is an animated GIF.
     *
     * @return bool
     * @see http://php.net/manual/en/function.imagecreatefromgif.php#59787
     */
    private function isAnimatedGif() {
        if ($this->imageInfo['type'] != 'gif') {
            return false;
        }

        $data = file_get_contents($this->imageInfo['filepath']);

        $pointer = 0;
        $frames = 0;
        while ($frames < 2) {
            $pos1 = strpos($data, "\x00\x21\xF9\x04", $pointer);
            if ($pos1 === FALSE) {
                break;
            } else {
                $pointer = $pos1 + 1;
                $pos2 = min(strpos($data, "\x00\x2C", $pointer), strpos($data, "\x00\x21", $pointer));
                if ($pos2 === FALSE) {
                    break;
                } else {
                    if ($pos1 + 8 == $pos2) {
                        $frames++;
                    }
                    $pointer = $pos2 + 1;
                }
            }
        }

        return $frames >= 2;
    }

	/***************************/
	/* minor private functions */
	/***************************/

	/**
	 * Mime type by file extension
	 *
	 * @param $ext, extension
	 * @return string or false if mime_type not found
	 */
	private function mimeTypeByExtension($ext) {
		$mime_type = false;
		if (empty($this->knownMimeTypes)) {
			include(BEDITA_CORE_PATH.DS.'config'.DS.'mime.types.php');
			$this->knownMimeTypes = $config["mimeTypes"];
		}
		$ext = strtolower($ext);
		if (!empty($ext) && array_key_exists($ext, $this->knownMimeTypes)) {
				$mime_type = $this->knownMimeTypes[$ext];
		}
		return $mime_type;
	}
}
