<?php
/*-----8<--------------------------------------------------------------------
 *
 * BEdita - a semantic content management framework
 *
 * Copyright 2008 ChannelWeb Srl, Chialab Srl
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
 *
 *
 * @version			$Revision$
 * @modifiedby 		$LastChangedBy$
 * @lastmodified	$LastChangedDate$
 *
 * $Id$
 */
class RemoveDummyNameBehavior extends ModelBehavior {

	private $associations = array("hasOne", "hasMany", "belongsTo", "hasAndBelongsToMany");

	function setup(&$model, $settings=array()) {
		if ( !$model->Behaviors->enabled('CompactResult') || !empty($model->actsAs["CompactResult"]) ) {

			if (empty($this->settings[$model->alias])) {
				$this->settings[$model->alias] = array();
			}

			foreach ($this->associations as $assocType) {
				if (!empty($model->{$assocType})) {
					foreach ($model->{$assocType} as $modelName => $val) {
						if (substr($modelName, -5) == "Dummy") {
							$this->settings[$model->alias]["dummyModel"][$modelName] = substr($modelName,0,-5);
						}
					}
				}
			}

			if (!is_array($settings)) {
				$settings = array();
			}

			$this->settings[$model->alias] = array_merge($this->settings[$model->alias], $settings);
		}
	}


	function afterFind(&$model, $results, $primary) {
		if (!empty($this->settings[$model->alias]["dummyModel"])) {
			foreach ($results as $key => $value) {
				foreach ($this->settings[$model->alias]["dummyModel"] as $dummy => $realModelName) {
					if (isset($results[$key][$dummy])) {
						$results[$key][$realModelName] = $results[$key][$dummy];
						unset($results[$key][$dummy]);
					}
				}
			}
		}

		return $results ;
	}

}

?>
