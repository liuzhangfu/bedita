<?php
/*-----8<--------------------------------------------------------------------
 *
* BEdita - a semantic content management framework
*
* Copyright 2015 ChannelWeb Srl, Chialab Srl
*
*------------------------------------------------------------------->8-----
*/

require_once APP . DS . 'vendors' . DS . 'shells'. DS . 'bedita_base.php';

/**
 * * Epub3 shell script
 */
class Epub3Shell extends BeditaBaseShell {

    private $logLevels = array(
        'ERROR' => 0,
        'WARN' => 1,
        'INFO' => 2,
        'DEBUG' => 3
    );

    private $options = array(
        'import' => array(),
        'export' => array()
    );

    private $logLevel;

    public function import() {
        $this->out('Epub3 Shell Import start');
        if (empty($this->params['f'])) {
            $this->out('Missing filename parameter');
            $this->help();
            return;
        } else {
            echo "\n" . 'EPUB3 Import options - filename: ' . $this->params['f'];
        }
        $inputData = @file_get_contents($this->params['f']);
        if (!$inputData) {
            $this->out('File "' . $this->params['f'] . '" not found');
            return;
        }
        if (isset($this->params['m'])) {
            $this->options['import']['sourceMediaRoot'] = $this->params['m'];
            echo "\n" . 'EPUB3 Import options - sourceMediaRoot: ' . $this->params['m'];
        }
        if (isset($this->params['v'])) {
            $this->options['import']['logLevel'] = 3; // DEBUG
            echo "\n" . 'EPUB3 Import options - logLevel: ' . $this->options['import']['logLevel'] . ' (' . array_search($this->options['import']['logLevel'], $this->logLevels) . ')';
        }
        $epub3transfer = ClassRegistry::init('Epub3Transfer');
        $result = $epub3transfer->import($inputData, $this->options['import']);
        $this->out('Epub3 Shell Import end');
    }
    
    public function export() {
        $this->out('Epub3Transfer Shell Export start');
        $objects = array();
        if (isset($this->params['id'])) {
            $objects[] = $this->params['id'];
            echo "\n" . 'EPUB3 Export options - id: ' . $this->params['id'];
        } else {
            $this->out('Missing id parameter');
            $this->help();
            return;
        }
        if (isset($this->params['f'])) {
            $this->options['export']['filename'] = $this->params['f'];
            echo "\n" . 'EPUB3 Export options - filename: ' . $this->options['export']['filename'];
        }
        if (isset($this->params['d'])) {
            $this->options['export']['treeDepth'] = $this->params['d'];
            echo "\n" . 'EPUB3 Export options - treeDepth: ' . $this->options['export']['treeDepth'];
        }
        if (isset($this->params['v'])) {
            $this->options['export']['logLevel'] = 3; // DEBUG
            echo "\n" . 'EPUB3 Export options - logLevel: ' . $this->options['export']['logLevel'] . ' (' . array_search($this->options['export']['logLevel'], $this->logLevels) . ')';
        }
        echo "\n" . 'See epub3export.log for details' . "\n\n";
        $epub3transfer = ClassRegistry::init('Epub3Transfer');
        $result = $epub3transfer->export($objects, $this->options['export']);
        $this->out('Epub3Transfer Shell Export end');

    }

    public function help() {
        $this->hr();
        $this->out('epub3 script shell usage:');
        $this->out('');
        $this->out('./cake.sh epub3 import -f <filename> [-m <sourceMediaRoot>] [-v]');
        $this->out('./cake.sh epub3 export -id <objectId> [-f <filename>] [-d <treeDepth>] [-v]');
        $this->hr();
    }
}