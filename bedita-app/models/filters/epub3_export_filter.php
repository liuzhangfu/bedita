<?php

/* -----8<--------------------------------------------------------------------
 * 
 * BEdita - a semantic content management framework
 * 
 * Copyright 2015 ChannelWeb Srl, Chialab Srl
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
 * ------------------------------------------------------------------->8-----
 */

/**
 * Epub3ExportFilter: class to export objects to EPUB3 format
 *
 * @version			$Revision$
 * @modifiedby 		$LastChangedBy$
 * @lastmodified	$LastChangedDate$
 * 
 * $Id$
 */
class Epub3ExportFilter extends BeditaExportFilter 
{
	protected $typeName = "EPUB3";
	protected $mimeTypes = array("application/epub+zip");
	public $label = 'EPUB3 export filter';
	public $options = array();
	
	/**
	 * Export objects in EPUB3 format
	 * 
	 * @param array $objects
	 * @param array $options, export options
	 * @return array containing
	 * 	"content" - export content
	 *  "contentType" - content mime type
	 *  "size" - content length
	 */
	public function export(array &$objects, array $options = array()) {
		$tmpDir = TMP . md5(time());
		if(!is_dir($tmpDir)) {
			if(@mkdir($tmpDir, 0755, true) === false) {
				throw new BeditaException("Unable to create $tmpDir");
			}
		}
		$epubFileName = $tmpDir . DS . $options['filename'] . '.epub';
		$options['filename'] = $epubFileName;
		$epub3transfer = ClassRegistry::init('Epub3Transfer');
		$oo = array();
		foreach ($objects as $o) {
			$oo[] = $o['id'];
		}
		$epub3transfer->export($oo, $options);
		$content = file_get_contents($epubFileName);
		$res = array();
		$res["content"] = $content;
		$res["size"] = strlen($content);
		$res["contentType"] = "application/epub+zip";
		$folder = new Folder($tmpDir);
		$folder->delete($tmpDir);
		return $res;
	}
};
