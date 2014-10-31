<?php
/*-----8<--------------------------------------------------------------------
 *
 * BEdita - a semantic content management framework
 *
 * Copyright 2008-2014 ChannelWeb Srl, Chialab Srl
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

namespace BEdita\Lib\Configure;

use Cake\Core\Configure;
use Cake\Cache\Cache;
use Cake\ORM\TableRegistry;
use Cake\Utility\Inflector;

/**
 * BeConfigure class
 *
 * Configuation class for handle specific BEdita items as object types, etc...
 */
class BeConfigure  {

    public static function initConfig() {
         $cachedConfig = Cache::read('beConfig');
		if ($cachedConfig  === false) {
			$cachedConfig = self::cacheConfig();
		} else {
			//$this->addModulesPaths($cachedConfig);
		}
		Configure::write($cachedConfig);
    }

    public static function cacheConfig() {
        $objectTypes = TableRegistry::get('ObjectTypes');
        $query = $objectTypes->find();
        $configurations['objectTypes'] = array();
        foreach ($query as $type) {
            $modelName = Inflector::camelize($type['name']) . 'Objects';
            $configurations['objectTypes'][$type['id']] = $configurations['objectTypes'][$type['name']] = [
                'id' => $type['id'],
                'name' => $type['name'],
                'module_name' => $type['module_name'],
                'model' => $modelName
            ];

            $objModel = TableRegistry::get($modelName);
            if (!empty($objModel->objectTypesGroups)) {
                foreach($objModel->objectTypesGroups as $group) {
                    $configurations['objectTypes'][$group]['id'][] = $type['ObjectType']['id'];
                }
            }
        }

        Cache::write('beConfig', $configurations);
        return $cachedConfig;
    }

}
