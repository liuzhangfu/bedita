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
 * Epub3ImportFilter: class to import objects from XML
 *
 * @version            $Revision$
 * @modifiedby         $LastChangedBy$
 * @lastmodified       $LastChangedDate$
 * 
 * $Id$
 */
class Epub3ImportFilter extends BeditaImportFilter 
{
    protected $typeName = "EPUB3";
    protected $mimeTypes = array("application/epub+zip");

    /**
     * Import BE objects from EPUB3
     * @param string $epub3FileName
     * @param array $options, import options 
     * @return array , result array containing 
     *  "objects" => number of imported objects
     *  "message" => generic message (optional)
     *  "error" => error message (optional)
     * @throws BeditaException
     */
    public function import($epub3FileName, array $options = array()) {
        $epub3transfer = ClassRegistry::init('Epub3Transfer');
        $result = $epub3transfer->import($epub3FileName, $options);
        return $result;
    }
};
