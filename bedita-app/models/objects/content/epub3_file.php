<?php
/*-----8<--------------------------------------------------------------------
 *
 * BEdita - a semantic content management framework
 *
 * Copyright 2014 ChannelWeb Srl, Chialab Srl
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
 * Epub3 File Class
 * 
 * Epub filesystem structure sample:
 * 
 * ├── mimetype
 * ├── META-INF
 * │   └── container.xml
 * └── OEBPS
 *     ├── chapter_1.xhtml
 *     ├── chapter_2.xhtml
 *     ...
 *     ├── Content.opf
 *     ├── cover.xhtml
 *     ├── data.json
 *     └── nav.xhtml
 *     ├── css
 *     │   └── epub.css
 *     ├── fonts
 *     │   ├── STIXGeneralBolIta.otf
 *     │   ├── STIXGeneralBol.otf
 *     │   ├── STIXGeneralItalic.otf
 *     │   └── STIXGeneral.otf
 *     ├── media
 *     │   ├── cache
 *     │   │   └── sample.jpg
 *     │   │       └── sample_200x200_9df3591392b4368d7ca4c2b8bbd9d561.jpg
 *     │   ├── cover.png
 *     │   ├── img
 *     │   │   └── iconMissingImage_130x85.gif
 *     │   └── sample.jpg
 */
class Epub3File {

    public $useTable = false;

    protected $filename;
    protected $zipFile;
    protected $epubFile;
    protected $containerXml;
    protected $oebpsPath;

    public function init($filename, $initData = array()) {
        $this->filename = $filename;
        $pos = strrpos($this->filename, '.');
        $fileextension = substr($this->filename, $pos+1);
        $this->filename = substr($this->filename, 0, $pos);
        $this->epubFile = $this->filename . '.epub';
        $this->zipFile = $this->filename . '.zip';
        $this->load($initData);
    }

    public function load($data) {
        if (!empty($data['containerXml'])) {
            $this->containerXml = $data['containerXml'];
        }
        if (!empty($data['oebpsPath'])) {
            $this->oebpsPath = $data['oebpsPath'];
        }
    }

    /**
     * Create zip file with data and rename it to epub
     * 
     * @throws BeditaException
     */
    public function create() {
        try {
            // mimetype uncompressed at the beginning of the epub file
            file_put_contents($this->zipFile, base64_decode("UEsDBAoAAAAAAOmRAT1vYassFAAAABQAAAAIAAAAbWltZXR5cGVhcHBsaWNhdGlvbi9lcHViK3ppcFBLAQIUAAoAAAAAAOmRAT1vYassFAAAABQAAAAIAAAAAAAAAAAAIAAAAAAAAABtaW1ldHlwZVBLBQYAAAAAAQABADYAAAA6AAAAAAA="));
            $zip = new ZipArchive();
            $zip->open($this->zipFile);
            $zip->addEmptyDir('META-INF');
            $zip->addFromString('META-INF' . DS . 'container.xml', file_get_contents($this->containerXml));
            $zip->addEmptyDir('OEBPS');
            if (!empty($this->oebpsPath)) {
                $files = array();
                foreach (new RecursiveIteratorIterator(new RecursiveDirectoryIterator($this->oebpsPath)) as $filename) {
                    if (!$this->startsWith($filename->getFilename(), '.')) { // skip files and dir starting with .
                        $files[$filename->getFilename()] = $filename->getPathname();
                    }
                }
                $baseDir = 'OEBPS';
                $pos = strlen($this->oebpsPath);
                foreach ($files as $filename => $fullUri) {
                    $relPath = substr($fullUri,$pos);
                    $this->addContent($zip, $fullUri, $relPath, $baseDir);
                }
            }
            $zip->close();
            rename($this->zipFile, $this->epubFile);
        } catch (Exception $e) {
            throw new BeditaException($e->getMessage());
        }
    }

    /**
     * Add content inside zip
     * 
     * @param unknown $zip
     * @param unknown $fullPath
     * @param unknown $relPath
     * @param unknown $baseDir
     */
    private function addContent($zip, $fullPath, $relPath, $baseDir) {
        if ($this->startsWith($relPath, DS)) {
            $relPath = substr($relPath, 1);
        }
        $fileName = basename($fullPath);
        if ($fileName != $relPath) {
            $len = strlen($relPath) - strlen($fileName);
            $destPath = substr($relPath, 0, $len);
            $this->addSubFolders($zip, $baseDir, $destPath);
        }
        $zip->addFromString($baseDir . DS . $relPath, file_get_contents($fullPath));
    }

    private function addSubFolders($zip, $baseFolder, $path) {
        $folders = explode(DS, $path);
        $incrementalPath = $baseFolder;
        foreach ($folders as $folder) {
            $incrementalPath .= DS . $folder;
            if (!is_dir($incrementalPath)) {
                $zip->addEmptyDir($incrementalPath);
            }
        }
    }

    private function startsWith($haystack, $needle) {
        return $needle === "" || strrpos($haystack, $needle, -strlen($haystack)) !== FALSE;
    }
}
?>