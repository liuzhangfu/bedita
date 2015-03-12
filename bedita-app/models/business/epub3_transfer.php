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

class Epub3Transfer extends BEAppModel
{
    public $useTable = false;

    protected $result = array();

    private $logFile;
    private $logLevel;

    protected $logLevels = array(
        'ERROR' => 0,
        'WARN' => 1,
        'INFO' => 2,
        'DEBUG' => 3
    );

    protected $export = array(
        'folders' => array(
            'tmp' => null,
            'metainf' => null,
            'resource' => null,
            'oebps' => null,
            'media' => null,
            'mediaImg' => null,
            'mediaCache' => null,
            'missingFile' => null,
            'css' => null
        ),
        'manifest' => null,
        'treeDepth' => 0,
        'rootIds' => array(),
        'media' => array(),
        'epub3filedata' => array(
            'containerXml' => '',
            'oebpsPath' => ''
        )
    );

    protected $smarty = null;

    /**
     * Import from epub3 file to BEdita
     * 
     * @param  string &$epub3Filename   full path to epub3 file
     * @param  array  $options          options for import
     */
    public function import($epub3Filename, array $options = array()) {
        $this->logFile = 'epub3import';
        // setting log level - default ERROR
        $this->import['logLevel'] = (!empty($options['logLevel'])) ? $options['logLevel'] : 0;
        $this->trackInfo('START');
        $this->import['folders']['tmp'] = TMP . md5(time());
        $this->import['filename'] = $epub3Filename;
        $this->trackInfo('temporary folder: ' . $this->import['folders']['tmp']);
        $this->trackInfo('filename: ' . $this->import['filename']);
        try {
            // open epub + extract to tmp folder
            $this->extractEpub($this->import['filename'], $this->import['folders']['tmp']);
            $this->import['folders']['media'] = $this->import['folders']['tmp'] . DS . 'OEBPS' . DS . 'media';
            // look for oebps/data.json
            $jsonFile = $this->import['folders']['tmp'] . DS . 'OEBPS' . DS . 'data.json';
            $data =  @file_get_contents($jsonFile);
            if (!empty($data)) {
                // getting export data
                $dataTransfer = ClassRegistry::init('DataTransfer');
                $opts = array(
                    'sourceMediaRoot' => $this->import['folders']['media']
                );
                // call data_transfer->import
                $dataTransfer->import($data, $opts);
            } else {
                // TODO: parse epub chapters and data...
            }
        } catch(Exception $e) {
            $this->trackError('ERROR: ' . $e->getMessage());
        }
        $this->trackInfo('END');
    }

    /**
     * EPUB3 Export BEdita data to epub3
     * 
     * @param  array  &$objects ids of root elements (publication|section) or ids of objects (document|event|...)
     * @param  array  $options  options for export
     * @return string           full path to epub3 file
     */
    public function export(array &$objects, $options = array()) {
        $this->logFile = 'epub3export';
        // setting log level - default ERROR
        $this->export['logLevel'] = (!empty($options['logLevel'])) ? $options['logLevel'] : 0;
        $this->logLevel = $this->export['logLevel'];
        $this->trackInfo('START');
        $this->export['folders']['tmp'] = TMP . md5(time());
        $this->export['filename'] = (!empty($options['filename'])) ? $options['filename'] : $this->export['folders']['tmp'] . '.zip';
        $this->trackInfo('temporary folder: ' . $this->export['folders']['tmp']);
        $this->trackInfo('filename: ' . $this->export['filename']);
        try {
            // tmp folder
            if(!is_dir($this->export['folders']['tmp'])) {
                if(@mkdir($this->export['folders']['tmp'], 0755, true) === false) {
                    throw new BeditaException('Unable to create ' . $this->export['folders']['tmp']);
                }
            }
            // OEBPS folder
            $this->export['folders']['oebps'] = $this->export['folders']['tmp'] . DS . 'OEBPS';
            if(@mkdir($this->export['folders']['oebps'], 0755, true) === false) {
                throw new BeditaException('Unable to create ' . $this->export['folders']['oebps']);
            }
            $this->export['folders']['fonts'] = $this->export['folders']['oebps'] . DS . 'fonts';
            // media folder
            $this->export['folders']['media'] = $this->export['folders']['oebps'] . DS . 'media';
            if(@mkdir($this->export['folders']['media'], 0755, true) === false) {
                throw new BeditaException('Unable to create ' .$this->export['folders']['media']);
            }
            // getting export data
            $dataTransfer = ClassRegistry::init('DataTransfer');
            $options = array(
                'returnType' => 'ARRAY',
                'destMediaRoot' => $this->export['folders']['media']
            );
            $this->export['source']['data'] = $dataTransfer->export($objects, $options);
            $this->validate();
            if (!empty($this->export['source']['data']['tree']['roots'])) {
                $this->export['rootIds'] = $this->export['source']['data']['tree']['roots'];
            }
            $tmpData = array();
            foreach ($this->export['rootIds'] as $rootId) {
                $tmpData[$rootId] = $this->export['source']['data']['objects'][$rootId];
                if($this->export['source']['treeDepth'] === 5) { // volume / part / chapter / subchapter / content
                    $tmpData[$rootId]['parts'] = array();
                    foreach ($this->export['source']['treeByLevel']['subLevel-1'] as $section) {
                        $tmpData[$rootId]['parts'][$section['id']] = $this->export['source']['data']['objects'][$section['id']];
                    }
                    foreach($tmpData[$rootId]['parts'] as $sectionId => &$part) {
                        $this->loadSectionData($part);
                    }
                } else { // volume / chapter / subchapter / content | volume / chapter / content
                    $tmpData[$rootId]['childSections'] = array();
                    foreach ($this->export['source']['treeByLevel']['subLevel-1'] as $section) {
                        $tmpData[$rootId]['childSections'][$section['id']] = $this->export['source']['data']['objects'][$section['id']];
                    }
                    foreach($tmpData[$rootId]['childSections'] as &$chapter) {
                        $this->loadSectionData($chapter);
                    }
                }
            }
            $epubs = array();
            if (!empty($tmpData)) {
                foreach ($tmpData as $rootId => $data) {
                    if (empty($this->export['firstRootId'])) {
                        $this->export['firstRootId'] = $rootId;
                    }
                    $epubs[$rootId] = $this->export['source']['data']['objects'][$rootId]; // publication data
                    $epubs[$rootId]['uniqid'] = uniqid();
                    $epubs[$rootId]['parts'] = (!empty($data['parts'])) ? $data['parts'] : array();
                    $epubs[$rootId]['chapters'] = array();
                    if(!empty($epubs[$rootId]['parts'])) {
                        foreach($epubs[$rootId]['parts'] as $k => &$part) {
                            $n = '00' . ($k+1);
                            $part['name'] = 'part' . substr($n, strlen($n)-3);
                            $part['chapters'] = $this->chapters($part['childSections'], null, $part['name'] . '_');
                        }
                    } else {
                        $epubs[$rootId]['chapters'] = $this->chapters($data['childSections']);
                    }
                    $epubs[$rootId]['media'] = array();
                }
            }
            // set resource path -> img, tpl, css,....
            $this->export['folders']['resource'] = CAKE_CORE_INCLUDE_PATH . DS . 'vendors' . DS . 'epub3' . DS;
            // 2. create epub3 files structure
            // 2.1 mimetype / create it inside Epub3File class
            // 2.2 META-INF folder
            $this->export['folders']['metainf'] = $this->export['folders']['tmp'] . DS . 'META-INF';
            if(@mkdir($this->export['folders']['metainf'], 0755, true) === false) {
                throw new BeditaException('Unable to create ' . $this->export['folders']['metainf']);
            }
            // 2.3 META-INF/container.xml
            if(copy($this->export['folders']['resource'] . 'container.xml', $this->export['folders']['metainf'] . DS . 'container.xml') === false) {
                throw new BeditaException('Unable to create ' . $this->export['folders']['metainf'] . DS . 'container.xml');
            }
            // 2.4 OEBPS folder - done previously
            // 2.5 media folder - done previously
            $this->export['folders']['mediaImg'] = $this->export['folders']['media'] . DS . 'img';
            if(@mkdir($this->export['folders']['mediaImg'], 0755, true) === false) {
                throw new BeditaException('Unable to create ' . $this->export['folders']['mediaImg']);
            }
            $this->export['folders']['mediaCache'] = $this->export['folders']['media'] . DS . 'cache';
            if(@mkdir($this->export['folders']['mediaCache'], 0755, true) === false) {
                throw new BeditaException('Unable to create ' . $this->export['folders']['mediaCache']);
            }
            $this->export['folders']['missingFile'] = BEDITA_CORE_PATH . DS . 'webroot' . Configure::read('imgMissingFile');
            $missingFile = basename($this->export['folders']['missingFile']);
            if(@copy($this->export['folders']['missingFile'], $this->export['folders']['mediaImg'] . DS . $missingFile) === false) {
                throw new BeditaException('Unable to create ' . $this->export['folders']['mediaImg'] . DS . $missingFile);
            }
            // 2.6 css folder
            $this->export['folders']['css'] = $this->export['folders']['oebps'] . DS . 'css';
            if(@mkdir($this->export['folders']['css'], 0755, true) === false) {
                throw new BeditaException('Unable to create ' . $this->export['folders']['css']);
            }
            if(@copy($this->export['folders']['resource'] . 'epub.css', $this->export['folders']['css'] . DS .  'epub.css') === false) {
                throw new BeditaException('Unable to create ' . $this->export['folders']['css'] . DS . 'epub.css');
            }
            // copy all other file/dir inside $this->export['folders']['resource'] to OEBPS dir
            include(BEDITA_CORE_PATH.DS . 'config' . DS . 'mime.types.php');
            $mm = $config['mimeTypes'];
            $folder = new Folder($this->export['folders']['resource']);
            $webdirs = $folder->read();
            $this->export['manifest'] = array(
                'file' => array()
            );
            foreach ($webdirs[0] as $d) {
                if($d[0] != '.' && $d !== 'validate') {
                    if(@mkdir($this->export['folders']['oebps'] . DS . $d, 0755, true) === false) {
                        throw new BeditaException('Unable to create ' . $this->export['folders']['oebps'] . DS . $d);
                    }
                    $folder2 = new Folder($this->export['folders']['resource'] . DS . $d);
                    $webdirs2 = $folder2->read();
                    foreach($webdirs2[1] as $f) {
                        if($f[0] != '.') {
                            $file = new File($this->export['folders']['resource']. DS . $d . DS . $f);
                            if(copy($this->export['folders']['resource'] . DS . $d . DS . $f, $this->export['folders']['oebps'] . DS . $d . DS . $f)===false) {
                                throw new BeditaException("Unable to create " . $this->export['folders']['oebps'] . DS . $d . DS . $f);
                            }
                            $this->export['manifest']['file'][] = array(
                                'name' => $f,
                                'fullpath' => $this->export['folders']['resource'] . DS . $d . DS . $f,
                                'path' => $d . DS . $f,
                                'ext' => $file->ext(),
                                'mime_type' => $mm[$file->ext()],
                                'nickname' => BeLib::getInstance()->friendlyUrlString($d . '.' . $f)
                            );
                        }
                    }
                }
            }
            // media
            $this->export['uniqueMedia'] = array();
            $mediaRoot = Configure::read('mediaRoot');
            foreach ($this->export['media'] as $id => $media) {
                if (!in_array($id, array_keys($this->export['uniqueMedia']))) {
                    $this->export['uniqueMedia'][$id] = $media;
                }
            }
            // the first publication
            $data = $epubs[$this->export['firstRootId']];
            // manifest
            $data['manifest'] = $this->export['manifest'];
            // media
            $data['media'] = $this->export['uniqueMedia'];
            //2.7 OEBPS/Content.opf
            $this->applyTemplate('content.opf.tpl', $data, $this->export['folders']['oebps'] . DS . 'Content.opf');
            //2.8 OEBPS/nav.xhtml
            $this->applyTemplate('nav.xhtml.tpl', $data, $this->export['folders']['oebps'] . DS . 'nav.xhtml');
            // cover
            if(copy($this->export['folders']['resource'] . 'cover.png', $this->export['folders']['media'] . DS . 'cover.png') === false) {
                throw new BeditaException('Unable to create ' . $this->export['folders']['media'] . DS . 'cover.png');
            }
            $this->applyTemplate('cover.xhtml.tpl', $data, $this->export['folders']['oebps'] . DS . 'cover.xhtml');
            //2.9 OEBPS/chapter_001.xhtml, ...
            if(!empty($data['parts'])) {
                foreach($data['parts'] as $p) {
                    foreach($p['chapters'] as $chapter) {
                        $this->applyTemplate('chapter.xhtml.tpl', $chapter, $this->export['folders']['oebps'] . DS . $chapter['filename'] . '.xhtml');
                    }
                }
            } else {
                if (!empty($data['chapters'])) {
                    foreach($data['chapters'] as $chapter) {
                        $this->applyTemplate('chapter.xhtml.tpl', $chapter, $this->export['folders']['oebps'] . DS . $chapter['filename'] . '.xhtml');
                    }
                }
            }
            // add json structure
            $json = "";
            $jsonFileName = $this->export['folders']['oebps'] . DS . 'data.json';
            if (phpversion() >= '5.4') {
                $json = json_encode($this->export['source']['data'], JSON_PRETTY_PRINT);
            } else {
                $json = json_encode($this->export['source']['data']);
            }
            if (!file_put_contents($jsonFileName, $json)) {
                throw new BeditaException('error saving data to file "' . $jsonFileName . '"');
            }
            // 3 zip, save as epub3, return
            $pos = strrpos($this->export['filename'], '.');
            $this->export['fileextension'] = substr($this->export['filename'], $pos+1);
            $this->export['filename'] = substr($this->export['filename'], 0, $pos);
            $epubFileName = $this->export['filename'] . '.epub';
            $this->export['epub3filedata']['containerXml'] = $this->export['folders']['metainf'] . DS . 'container.xml';
            $this->export['epub3filedata']['oebpsPath'] =  $this->export['folders']['oebps'];
            $epub3file = ClassRegistry::init('Epub3File');
            $epub3file->init($epubFileName, $this->export['epub3filedata']);
            $epub3file->create();
            $this->trackInfo('Created file ' . $epubFileName);
        } catch(Exception $e) {
            $this->trackError('ERROR: ' . $e->getMessage());
        }
        $this->trackInfo('END');
    }

    /**
     * Validation of objects for export epub
     */
    public function validate() {
        $this->organizeTree($this->export['source']['data']['tree']['sections'], 0);
        $this->export['source']['treeDepth'] = count(array_keys($this->export['source']['treeByLevel']))+1;

        // tree validation
        // tree depth
        // 7: latex: part - chapter - section - subsection - subsubsection - paragraph - subparagraph
        // 5: volume / part / chapter / subchapter / content
        // 4: volume / chapter / subchapter / content
        // 3: volume / chapter / content
        if (!empty($options['treeDepth'])) {
            if ($options['treeDepth'] != $this->export['source']['data']['treeDepth']) {
                throw new BeditaException('Tree depth indicated in options ('  . $options['treeDepth'] . ') is different from data tree depth found (' . $this->export['source']['data']['treeDepth'] . ')');
            }
            if ($options['treeDepth'] == 5) { // volume / part / chapter / subchapter / content

            } else if ($options['treeDepth'] == 4) { // volume / chapter / subchapter / content

            } else if ($options['treeDepth'] == 3) { // volume / chapter / content

            } else {
                throw new BeditaException('Tree depth '  . $options['treeDepth'] . ' not allowed / values allowed: 3, 4, 5');
            }
        }
        foreach ($this->export['source']['data']['objects'] as $obj) {
            if (empty($obj['object_type_id'])) {
                $obj['object_type_id'] = Configure::read('objectTypes.' . $obj['objectType'] . '.id');
            }
            if ($obj['objectType'] === Configure::read('objectTypes.section.name')) {
                $obj['parents'][] = $this->export['source']['treeElements'][$obj['id']]['parent'];
            }
            if (!empty($obj['parents'])) {
                if ($obj['objectType'] === Configure::read('objectTypes.section.name')) {
                    $type = 'sectionsChildSections';
                } else {
                    $type = 'sectionsChildContents';
                }
                foreach ($obj['parents'] as $parent) {
                    $parentId = (is_array($parent)) ? $parent['id'] : $parent;
                    if (empty($this->export['source'][$type])) {
                        $this->export['source'][$type] = array();
                    }
                    if (empty($this->export['source'][$type][$parentId])) {
                        $this->export['source'][$type][$parentId] = array();
                    }
                    $this->export['source'][$type][$parentId][$obj['id']] = $obj;
                }
            }
        }
        $parentContentPriority = array();
        $childContents = array();
        foreach ($this->export['source']['sectionsChildContents'] as $parentId => $children) {
            foreach ($children as $child) {
                if (!empty($child['parents'])) {
                    foreach ($child['parents'] as $childParent) {
                        if ($childParent['id'] == $parentId) {
                            if (!empty($childParent['priority'])) {
                                $parentContentPriority[$parentId][] = array(
                                    'id' => $child['id'],
                                    'priority' => $childParent['priority']
                                );
                            } else {
                                $parentContentPriority[$parentId][] = array(
                                    'id' => $child['id']
                                );
                            }
                        }
                    }
                    $childContents[$child['id']] = $child;
                }
            }
        }
        foreach ($parentContentPriority as $sectionId => &$c) {
            usort($c, function($a, $b) {
                $p1 = (!empty($a['priority'])) ? $a['priority'] : 99999;
                $p2 = (!empty($b['priority'])) ? $b['priority'] : 99999;
                return $p1 - $p2;
            });
        }
        foreach ($parentContentPriority as $sectionId => $contents) {
            $orderedContents = array();
            foreach ($contents as $child) {
                $orderedContents[] = $childContents[$child['id']];
            }
            $this->export['source']['sectionsChildContents'][$sectionId] = $orderedContents;
        }
    }

    /* private functions */

    /* private file utils */

    private function extractEpub($fileName, $destFolder) {
        $zip = new ZipArchive();
        $f = $zip->open($fileName);
        if ($f === true) {
            $zip->extractTo($destFolder);
            $zip->close();
        } else {
            throw new BeditaException('Unable to extract file ' . $fileName . ' to destination folder ' . $destFolder);
        }
    }

    /* private arranging data functions */

    private function organizeTree($sections, $subLevel = 0) {
        if ($subLevel === 0) {
            $keys = array_values($this->export['source']['data']['tree']['roots']);
            $this->export['source']['treeByLevel']['subLevel-0'] = $keys;
        } else {
            $keys = array_keys($this->export['source']['treeElements']);
        }
        $subLevel++;
        $sectionsTmp = array();
        foreach ($sections as $section) {
            if (in_array($section['parent'], $keys)) {
                $sectionItem = array(
                    'id' => $section['id'],
                    'parent' => $section['parent'],
                    'depth' => $subLevel
                );
                if (!empty($section['priority'])) {
                    $sectionItem['priority'] = $section['priority'];
                }
                $this->export['source']['treeElements'][$section['id']] = $sectionItem;
                $this->export['source']['treeByLevel']['subLevel-' . $subLevel][] = $this->export['source']['treeElements'][$section['id']];
            } else {
                $sectionsTmp[] = $section;
            }
        }
        if (!empty($sectionsTmp)) {
            $this->organizeTree($sectionsTmp, $subLevel);
        }
    }

    private function loadSectionData(&$obj) {
        if (!empty($obj['id'])) {
            if (!empty($this->export['source']['sectionsChildContents'][$obj['id']])) {
                $obj['childContents'] = $this->export['source']['sectionsChildContents'][$obj['id']];
            }
            if (!empty($this->export['source']['sectionsChildSections'][$obj['id']])) {
                $obj['childSections'] = $this->export['source']['sectionsChildSections'][$obj['id']];
                foreach ($obj['childSections'] as &$child) {
                    $this->loadSectionData($child);
                }
            }
        }
    }

    private function chapters($sections, $parent = null, $filenamePrefix = '') {
        $chapters = array();
        foreach($sections as $k => $section) {
            $counter = ($parent == null) ? intval($k+1) : $parent . '.' . intval($k+1);
            $chapters[$k]['id'] = $section['id'];
            $n = '00' . $counter;
            $chapters[$k]['name'] = substr($n, strlen($n)-3);
            $chapters[$k]['filename'] = $filenamePrefix . 'chapter_' . $chapters[$k]['name'];
            $chapters[$k]['title'] = $section['title'];
            $chapters[$k]['contents'] = !empty($section['childContents'])?  $section['childContents'] : array();
            if (!empty($section['childSections']) ) {
                $chapters[$k]['subchapters'] = $this->chapters($section['childSections'], $counter, $filenamePrefix);
            }
            if (!empty($section['childContents'])) {
                foreach ($section['childContents'] as &$content) {
                    if (!empty($content['uri'])) {
                        //$content['uri'] = '.' . DS . 'media' . $content['uri']; // fix relative uri for media contents
                        $this->export['media'][$content['id']] = $content;
                    }
                }
            }
        }
        return $chapters;
    }

    /* templates */

    private function initSmarty() {
        if(empty($this->smarty)) {
            App::import('Vendor', 'SmartyClass', array('file' => 'smarty' . DS . 'libs' . DS . 'Smarty.class.php'));
            $this->smarty = new Smarty();
            $this->smarty->setCompileDir(BEDITA_CORE_PATH . DS . 'tmp' . DS. 'smarty' . DS . 'compile');
            $this->smarty->setCacheDir(BEDITA_CORE_PATH . DS .  'tmp' . DS. 'smarty' . DS . 'cache' . DS);
            $this->smarty->addPluginsDir(BEDITA_CORE_PATH . DS . 'vendors' . DS . '_smartyPlugins');
            $this->smarty->compile_id = 'EPUB';
        }   
    }

    private function applyTemplate($tplName, $data, $destFile) {
        $this->initSmarty();
        $this->smarty->clearAllAssign();
        
        $appHelper = ClassRegistry::getObject('AppHelper');
        if(!$appHelper) {
            include BEDITA_CORE_PATH . DS . 'app_helper.php';
            $appHelper = new AppHelper();
            ClassRegistry::addObject('AppHelper', $appHelper);
        }
        Configure::write('mediaRoot', $this->export['folders']['media']);
        Configure::write('mediaUrl', 'media');
        $helpers = array('Html', 'Javascript', 'BeEmbedMedia');
        foreach ($helpers as $h) {
            $hObj = $appHelper->getHelper($h);
            $this->smarty->assign(Inflector::variable($h), $hObj);
        }
        $this->smarty->assign('data', $data);
        $this->smarty->assign('conf', Configure::getInstance());
        $content = $this->smarty->fetch($this->export['folders']['resource'] . $tplName);
        $res = file_put_contents($destFile, $content);
        if($res === false) {
            throw new BeditaException('Unable to create file ' . $destFile);
        }
    }

    /* private logging functions */

    private function trackError($message) {
        $this->trackResult('ERROR', $message);
    }

    private function trackWarn($message) {
        $this->trackResult('WARN', $message);
    }

    private function trackInfo($message) {
        $this->trackResult('INFO', $message);
    }

    private function trackDebug($message) {
        $this->trackResult('DEBUG', $message);
    }

    private function trackResult($level = 'INFO', $message) {
        $this->result['log'][$level][] = $message;
        $this->result['log']['ALL'][] = $level . ': ' . $message;
        if ($this->logLevels[$level] <= $this->logLevel) {
            $this->result['log']['filtered'][] = $message;
            $this->log($message, strtolower($level));
            if (!empty($this->logFile)) {
                $this->log($message, $this->logFile);
            }
        }
    }
}