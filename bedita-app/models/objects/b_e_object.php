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
 * BEObject class
 *
 */
class BEObject extends BEAppModel {

	public $actsAs = array('Cacheable');
	
	var $name = 'BEObject';
	var $useTable	= "objects" ;
	
	private $defaultIp = "::1"; // default IP addr for saved objects
	
	var $validate = array(
//		'title' => array(
//			'rule' => 'notEmpty'
//		),
		'object_type_id' => array(
			'rule' => 'notEmpty'
		),
		'nickname' => array(
			'rule' => 'notEmpty'
		),
		'lang' => array(
			'rule' => 'notEmpty'
		),
		'ip_created' => array(
			'rule' => 'ip'
		),
        'status' => array(
            'rule' => array('inList', array('on', 'off', 'draft'))
        ),
	) ;

	var $belongsTo = array(
		'ObjectType' =>
			array(
				'className'		=> 'ObjectType',
				'foreignKey'	=> 'object_type_id',
				'conditions'	=> ''
			),
		'UserCreated' =>
			array(
				'className'		=> 'User',
				'fields'		=> 'id, userid, realname',
				'foreignKey'	=> 'user_created',
			),
		'UserModified' =>
			array(
				'className'		=> 'User',
				'fields'		=> 'id, userid, realname',
				'foreignKey'	=> 'user_modified',
			),
	) ;
	
	var $hasMany = array(	
		'Permission',

		'Version' =>
			array(
				'className'		=> 'Version',
				'foreignKey'	=> 'object_id',
				'dependent'		=> true
			),
			
		'ObjectProperty' =>
			array(
				'className'		=> 'ObjectProperty',
				'foreignKey'	=> 'object_id',
				'dependent'		=> true
			),			
		'SearchText' =>
			array(
				'foreignKey'	=> 'object_id',
				'dependent'		=> true
			),
		'LangText' =>
			array(
				'className'		=> 'LangText',
				'foreignKey'	=> 'object_id',
				'dependent'		=> true
			),
		'Annotation' =>
			array(
				'foreignKey'	=> 'object_id',
				'dependent'		=> true
			),
		'RelatedObject' =>
			array(
				'className'				=> 'ObjectRelation',
				'joinTable'    			=> 'object_relations',
				'foreignKey'   			=> 'id',
				'associationForeignKey'	=> 'object_id',
				'order'					=> 'priority'
			),
		'Alias',
		'GeoTag' =>
			array(
				'foreignKey'	=> 'object_id',
				'dependent'		=> true
			)
			
		);
	
	var $hasAndBelongsToMany = array(
		'Category' =>
			array(
				'className'				=> 'Category',
				'joinTable'    			=> 'object_categories',
				'foreignKey'   			=> 'object_id',
				'associationForeignKey'	=> 'category_id',
				'unique'				=> true
			),
		'User' =>
			   array(
			    'className'    			=> 'User',
			    'joinTable'       		=> 'object_users',
			    'foreignKey'      		=> 'object_id',
			    'associationForeignKey' => 'user_id',
			    'unique'    			=> true,
			   	'with' 					=> 'ObjectUser'
			   )
	);	

	/**
	 * Format object data (ObjectProperty, Tag, Category, LangText, Permission)
	 *
	 * If ObjectProperty is populated a simplified customProperties array (useful in frontend apps) is built as
	 *
	 * 	"customProperties" => array(
	 *  	"prop_name" => "prop_val",
	 *   	"prop_name_multiple_choice" => array("prop_val_1", "prop_val_2")
	 *  )
	 */	
	function afterFind($result) {
		
		// format object properties
		if(!empty($result['ObjectProperty'])) {
			$result["customProperties"] = array();
			$propertyModel = ClassRegistry::init("Property");
			$property = $propertyModel->find("all", array(
								"conditions" => array("object_type_id" => $result["object_type_id"]),
								"contain" => array("PropertyOption")
							)
						);

			foreach ($property as $keyProp => $prop) {
				
				foreach ($result["ObjectProperty"] as $k => $value) {
					if ($value["property_id"] == $prop["id"]) {
						if ($prop["multiple_choice"] != 0) {
							$property[$keyProp]["value"][] = $value;
							$result["customProperties"][$prop["name"]][] = $value["property_value"];
						} else { 
							$property[$keyProp]["value"] = $value;
							$result["customProperties"][$prop["name"]] = $value["property_value"];
						}
						
						// set selected to true in PropertyOption array
						if (!empty($prop["PropertyOption"])) {
							foreach ($prop["PropertyOption"] as $n => $option) {
								if ($option["property_option"] == $value["property_value"]) {
									$property[$keyProp]["PropertyOption"][$n]["selected"] = true;
								}
							}
						}
						
						unset($result["ObjectProperty"][$k]);
					}
				}
				
			}
			$result["ObjectProperty"] = $property;
			unset($property);
		}
		
		// set up LangText for view
		if (!empty($result['LangText'])) {
			$langText = array();
			foreach ($result['LangText'] as $lang) {
				if (!empty($lang["name"]) && !empty($lang["lang"])) {
					$langText[$lang["name"]][$lang["lang"]] = $lang["text"];
					$langText[$lang["object_id"]][$lang["lang"]][$lang["name"]] = $lang["id"];
				}
			}
			$result['LangText'] = $langText;
		}
		
		// divide tags from categories
		if (!empty($result["Category"])) {
			
			$tag = array();
			$category = array();
			
			foreach ($result["Category"] as $ot) {
				if (!empty($ot["object_type_id"])) {
					$category[] = $ot;
				} else {
					$tag[] = $ot;
				}
			}
			
			$result["Category"] = $category;
			$result["Tag"] = $tag;
		}

		if (!empty($result["Permission"])) {
			foreach ($result["Permission"] as &$perm) {
				if ($perm["switch"] == "group") {
					$perm["name"] = $this->Permission->Group->field("name", array("id" => $perm["ugid"]));
				} elseif ($perm["switch"] == "user") {
					$perm["name"] = $this->Permission->User->field("name", array("id" => $perm["ugid"]));
				}
			}
		}
		
		return $result ;
	}

	function beforeSave() {
        $data;
        if(isset($this->data[$this->name])) 
            $data = &$this->data[$this->name] ;
        else 
            $data = &$this->data ;

		// format custom properties and searchable text fields
		$labels = array('SearchText');
		foreach ($labels as $label) {
            if(!isset($data[$label]))
                continue;

            if(is_array($data[$label]) && count($data[$label])) {
                $tmps = array();
                foreach($data[$label]  as $k => $v) {
                    $this->_value2array($k, $v, $arr);
                    array_push($tmps, $arr);
                }
                $data[$label] = $tmps;
            }
		}

		// empty GeoTag array if no value is in
		if (!empty($data['GeoTag'])) {
			foreach ($data['GeoTag'] as $key => $geotag) {
				$concat = '';
				$geoTagFields = array('title', 'address', 'latitude', 'longitude');
				foreach ($geoTagFields as $field) {
					if (isset($geotag[$field])) {
						$concat .= trim($geotag[$field]);
					}
				}
				if (strlen($concat) == 0) {
					unset($data['GeoTag'][$key]);
				}
			}
		}

		$this->unbindModel(array("hasMany"=>array("LangText","Version")));
		$this->unbindModel(array("hasAndBelongsToMany"=>array("User")));

		return true;
	}
	
	/**
	 * Save hasMany relations data
	 */
	function afterSave() {
		
		// hasMany relations
		foreach ($this->hasMany as $name => $assoc) {
			// skip specific manage
			if ($name == 'Permission' || $name == 'RelatedObject' || $name == 'Annotation') {
				continue;
			}

			// if not set data array do nothing
			if (!isset($this->data[$this->name][$name])) {
				continue;
			}
			
			$db =& ConnectionManager::getDataSource($this->useDbConfig);
			$model = new $assoc['className']();
			
			// delete previous associations
			$table = (isset($model->useTable))? $model->useTable : ($db->name($db->fullTableName($assoc->className)));
			$id = (isset($this->data[$this->name]['id']))? $this->data[$this->name]['id'] : $this->getInsertID();
			$foreignK = $assoc['foreignKey'];
			// #CUSTOM QUERY
			$db->query("DELETE FROM {$table} WHERE {$foreignK} = '{$id}'");
			
			if (!(is_array($this->data[$this->name][$name]) && count($this->data[$this->name][$name]))) {
				continue;
			}
			
			// save associations
			$size = count($this->data[$this->name][$name]);
			for ($i = 0; $i < $size; $i++) {
				$modelTmp = new $assoc['className']();
				$data = &$this->data[$this->name][$name][$i];
				$data[$foreignK] = $id;
				if (!$modelTmp->save($data)) {
					throw new BeditaException(__("Error saving object", true), "Error saving hasMany relation in BEObject for model " . $assoc['className']);
				}
				
				unset($modelTmp);
			}
						
		}

		// build ObjectUser Relation
		if (isset($this->data['BEObject']["ObjectUser"])) {
			$objectUserModel = ClassRegistry::init("ObjectUser");
			if (empty($this->data['BEObject']["ObjectUser"])) {
				$objectUserModel->deleteAll(array(
						"object_id" => $this->id
					)
				);
			} else {
				foreach ($this->data['BEObject']["ObjectUser"] as $switch => $objUserArr) {
					$objectUserModel->deleteAll(array(
							"object_id" => $this->id,
							"switch" => $switch
						)
					);
					if (is_array($objUserArr)) {
						foreach ($objUserArr as $objUserData) {
							if (!empty($objUserData["user_id"])) {
								if (empty($objUserData["object_id"])) {
									$objUserData["object_id"] = $this->id;
								}
								$objectUserModel->create();
								if (!$objectUserModel->save($objUserData)) {
									throw new BeditaException(__("error saving object_users relations",true));
								}
							}
						}
					}
				}
			}
		}
				
		$permissions = false;
		if (isset($this->data["Permission"])) {
			$permissions = $this->data["Permission"] ;
		} elseif (isset($this->data[$this->name]["Permission"])) {
			$permissions = $this->data[$this->name]["Permission"];
		}
		
		if (is_array($permissions)) {
			$this->Permission->replace($this->{$this->primaryKey}, $permissions);
		}
		// save relations between objects
		if (!empty($this->data['BEObject']['RelatedObject'])) {
			$db = &ConnectionManager::getDataSource($this->useDbConfig);
			$queriesDelete = array();
			$queriesInsert = array();
			$queriesModified = array();
			$lang = (isset($this->data['BEObject']['lang']))? $this->data['BEObject']['lang'] : null;
			
			$allRelations = BeLib::getObject("BeConfigure")->mergeAllRelations();
			$inverseRelations = array();
			foreach ($allRelations as $n => $r) {
				if (!empty($r["inverse"])) {
					$inverseRelations[$r["inverse"]] = $n;
				}
			}
			
			$assoc = $this->hasMany['RelatedObject'] ;
			$table = $db->name($db->fullTableName($assoc['joinTable']));
			$fields = $assoc['foreignKey'] . "," . $assoc['associationForeignKey'] . ", switch, priority, params";

			foreach ($this->data['BEObject']['RelatedObject'] as $switch => $values) {
				
				foreach ($values as $key => $val) {
					$obj_id	= isset($val['id'])? $val['id'] : false;
					$priority = isset($val['priority'])? "'{$val['priority']}'" : 'NULL';
					$params = isset($val['params'])? "'" . json_encode($val['params']) . "'" : 'NULL';
					// Delete old associations
					// #CUSTOM QUERY
					$queriesDelete[] = "DELETE FROM {$table} WHERE {$assoc['foreignKey']} = '{$this->id}' AND switch = '{$switch}' ";

					$inverseSwitch = $switch;
					if (!empty($allRelations[$switch]) && !empty($allRelations[$switch]["inverse"])) {
						$inverseSwitch = $allRelations[$switch]["inverse"];
					} elseif (!empty($inverseRelations[$switch])) {
						$inverseSwitch = $inverseRelations[$switch];
					}

					$queriesDelete[] = "DELETE FROM {$table} WHERE {$assoc['associationForeignKey']} = '{$this->id}'
										AND switch = '{$inverseSwitch}' ";
				
					if (!empty($obj_id)) {
						// #CUSTOM QUERY
						$queriesInsert[] = "INSERT INTO {$table} ({$fields}) VALUES ({$this->id}, {$obj_id}, '{$switch}', {$priority}, {$params})" ;
					
						// find priority of inverse relation
						// #CUSTOM QUERY
						$inverseRel = $this->query("SELECT priority 
													  FROM {$table} 
													  WHERE id={$obj_id} 
													  AND object_id={$this->id} 
													  AND switch='{$inverseSwitch}'");
						
						if (empty($inverseRel[0]["object_relations"]["priority"])) {
							// #CUSTOM QUERY
							$inverseRel = $this->query("SELECT MAX(priority)+1 AS priority FROM {$table} WHERE id={$obj_id} AND switch='{$inverseSwitch}'");
							$inversePriority = (empty($inverseRel[0][0]["priority"]))? 1 : $inverseRel[0][0]["priority"];
						} else {
							$inversePriority = $inverseRel[0]["object_relations"]["priority"];
						}						
						// #CUSTOM QUERY
						$queriesInsert[] = "INSERT INTO {$table} ({$fields}) VALUES ({$obj_id}, {$this->id}, '{$inverseSwitch}', ". $inversePriority  .", {$params})" ;
					}
					
					$modified = (isset($val['modified']))? ((boolean)$val['modified']) : false;
					if ($modified && $obj_id) {
						$title = isset($val['title'])? addslashes($val['title']) : "";
						if($switch == 'link') {
							// #CUSTOM QUERY
							$queriesModified[] = "UPDATE objects  SET title = '{$title}' WHERE id = {$obj_id} ";
							$link = ClassRegistry::init('Link');
							$link->id = $obj_id;
							$link->saveField('url',$val['url']);
						} else {
							$description = isset($val['description'])? addslashes($val['description']) : "";
							// #CUSTOM QUERY
							$queriesModified[] = "UPDATE objects  SET title = '{$title}', description = '{$description}' WHERE id = {$obj_id} " ;
						}
					}
				}
			}

			$queriesDelete = array_unique($queriesDelete);
			foreach ($queriesDelete as $qDel) {
				if ($db->query($qDel) === false) {
					throw new BeditaException(__("Error deleting associations", true), $qDel);
				}
			}
			foreach ($queriesInsert as $qIns) {
				if ($db->query($qIns)  === false) {
					throw new BeditaException(__("Error inserting associations", true), $qIns);
				}
			}
			foreach ($queriesModified as $qMod) {
				if ($db->query($qMod)  === false) {
					throw new BeditaException(__("Error modifying title and description", true), $qMod);
				}
			}
		}

		return true;
	}
	
	/**
	 * Define default values.
	 */		
	function beforeValidate() {
		if(isset($this->data[$this->name])) 
			$data = &$this->data[$this->name] ;
		else 
			$data = &$this->data ;
	
		if(isset($data['title'])) {
			$data['title'] = trim($data['title']);
		}

        if (isset($data['fixed']) && !$this->_isCurrentUserAdmin()) {
            // #590 - Prevent non-admin Users to be able to change fixed property.
            unset($data['fixed']);
        }

		// set language -- disable for comments?
		if(!isset($data['lang'])) {
			$data['lang'] = $this->_getDefaultLang();
		}
		// check/set IP
		if(!isset($data['ip_created']) && !isset($data['id'])) {
			$data['ip_created'] = $this->_getDefaultIP();
		}

        // #650 set always user_modified = current user, set user_created only on new objects
        $currentUserId = $this->_getIDCurrentUser();
        $data['user_modified'] = $currentUserId;
        if (!isset($data['id'])) {
            $data['user_created'] = $currentUserId;
        }

		// nickname: verify nick and status change, object not fixed
		if(isset($data['id'])) {
			$currObj = $this->find("first", array(
											"conditions"=>array("BEObject.id" => $data['id']), 
											"fields" =>array("status", "nickname", "fixed"),
											"contain" => array()
											));
			if($currObj['BEObject']['fixed'] == 1) {  // don't change nickname & status
				// throws exceptions if status/nicknames are changed
				if((!empty($data['status']) && $data['status'] != $currObj['BEObject']['status']) ||
				    (!empty($data['nickname']) && $data['nickname'] != $currObj['BEObject']['nickname'])) {
					throw new BeditaException(__("Error: modifying fixed object!", true));
				}
				$data['nickname'] = $currObj['BEObject']['nickname'];
				$data['status'] = $currObj['BEObject']['status'];
            } else {
                // Check if nickname has changed.
                if (empty($data['nickname']) && !empty($currObj['BEObject']['nickname'])) {
                    $data['nickname'] = $currObj['BEObject']['nickname'];
                } else {
                    $data['nickname'] = $this->_getDefaultNickname($data['nickname']);
                }

                // Check if status has changed.
                if (empty($data['status']) && !empty($currObj['BEObject']['status'])) {
                    $data['status'] = $currObj['BEObject']['status'];
                }
            }
		} else {
			$title = isset($data['title']) ? $data['title'] : null;
			$tmpName = !empty($data['nickname']) ? $data['nickname'] : $title;
			$data['nickname'] = $this->_getDefaultNickname($tmpName);
		}
		
		if(empty($data["user_created"])) unset($data["user_created"]) ;
		
		// format custom properties data type
		if (!empty($data["ObjectProperty"])) {
			foreach ($data["ObjectProperty"] as $key => $val) {
				if (!empty($val["property_type"]) && $val["property_type"] == "date")
					$data["ObjectProperty"][$key]["property_value"] = $this->getDefaultDateFormat($val["property_value"]);
			}
		}
		
		// Se c'e' la chiave primaria vuota la toglie
		if(isset($data[$this->primaryKey]) && empty($data[$this->primaryKey]))
			unset($data[$this->primaryKey]) ;
		
		return true ;
	}

	public function findObjectTypeId($id) {
		$object_type_id = $this->field("object_type_id", array("BEObject.id" => $id));
		return $object_type_id;
	}

	/**
	 * Is object fixed??
	 *
	 * @param int $id
	 * @return boolean
	 */
	public function isFixed($id) {
		$fixed = $this->field("fixed", array("BEObject.id" => $id));
		return ($fixed == 1);
	}	
	
	/**
	 * Model name/type from id
	 *
	 * @param unknown_type $id
	 */
	public function getType($id) {
		$type_id = $this->findObjectTypeId($id);
		if($type_id === false) {
			throw new BeditaException(__("Error: object type not found", true));
		}
		return Configure::getInstance()->objectTypes[$type_id]["model"] ;
	}
	
	/**
	* update title e description only.
	**/
	public function updateTitleDescription($id, $title, $description) {
		if(@empty($id) || @empty($title)) return false ;
		
		$db 		= &ConnectionManager::getDataSource($this->useDbConfig);
		// #CUSTOM QUERY
		$db->query("UPDATE objects  SET title =  '".addslashes($title)."', description = '".addslashes($description)."' WHERE id = {$id} " ) ;
		
		return true ;
	}	
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	////////////////////////////////////////////////////////////////////////////////////////////////
	
	/**
	 * build object unique name
	 * 
	 * @param string $value
	 * @return string 
	 */
	private function _getDefaultNickname($value) {
		$nickname = $nickname_base = BeLib::getInstance()->friendlyUrlString($value);
		$conf = Configure::getInstance() ;
		$nickOk = false;
		$countNick = 1;
		$reservedWords = array_merge ( $conf->defaultReservedWords, $conf->cfgReservedWords );
		if(empty($nickname)) {
			$objTypeId = $this->data['BEObject']['object_type_id'];
			$nickname_base = $conf->objectTypes[$objTypeId]["name"] . "-" . time(); // default name - model type name - timestamp
			$nickname = $nickname_base ;
		};


		$aliasModel = ClassRegistry::init("Alias");		
		while (!$nickOk) {
			
			$cond = "WHERE BEObject.nickname = '{$nickname}'";
			if ($this->id) {
				$cond .= " AND BEObject.id<>".$this->id;
			}
			$numNickDb = $this->find("count", array("conditions" => $cond, "contain" => array()));
			
			// check nickname in db and in reservedWords
			if ($numNickDb == 0 && !in_array($nickname, $reservedWords)) {
				// check aliases
				$object_id = $aliasModel->field("object_id", array("nickname_alias" => $nickname));
				if(empty($object_id)) {
					$nickOk = true;
				}
			}
			if(!$nickOk) {
				$nickname = $nickname_base . "-" . $countNick++;
			}
		}
		
		return $nickname ;
	}
	
	private function _getDefaultLang() {
		$conf = Configure::getInstance() ;
		return ((isset($conf->defaultLang))?$conf->defaultLang:'') ;
	}
	
//	private function _getDefaultPermission($value, $object_type_id) {
//		if(isset($value) && is_array($value)) return $value ;
//		
//		$conf = Configure::getInstance() ;
//		$permissions = &$conf->permissions ;
//		
//		// Aggiunge i permessi di default solo se sta creando un nuovo oggetto
//		if(isset($this->data[$this->name][$this->primaryKey])) return null ;
//		
//		// Seleziona i permessi in base al tipo di oggetti
//		if(isset($permissions[$object_type_id])) 	return $permissions[$object_type_id] ;
//		else if (isset($permissions['all']))		return $permissions['all'] ;
//		
//		return null ;
//	}
	
	private function _getDefaultIP() {
		if(!empty($_SERVER['REMOTE_ADDR'])) {
			$IP = $_SERVER['REMOTE_ADDR'];
		} else {
			$IP = $this->defaultIp;
		}
		return $IP ;
	}

    /**
     * Returns the current user ID. If a unit test is running, the test user ID is returned instead.
     *
     * @return int Current User's ID, or test User's ID. Defaults to system User's ID.
     * @see BeSystem::systemUserId()
     */
    private function _getIDCurrentUser() {
        $conf = Configure::getInstance();
        $systemUserId = BeLib::getObject('BeSystem')->systemUserId();

        if (isset($conf->beditaTestUserId)) {
            // Unit tests.
            return $conf->beditaTestUserId;
        } elseif (class_exists('CakeSession')) {
            $session = new CakeSession();
            if (!$session->started() || $session->valid() === false) {
                return $systemUserId;
            }

            $user = $session->read($conf->session['sessionUserKey']); 
            if (!isset($user['id'])) {
                return $systemUserId;
            }

            return $user['id'];
        }

        return $systemUserId;
    }

    /**
     * Checks whether current User is in Group `administrator` or not.
     *
     * @return bool Current User's administrator permissions.
     */
    private function _isCurrentUserAdmin() {
        return !is_null(Configure::read('beditaTestUserId')) || in_array('administrator', ClassRegistry::init('User')->getGroups($this->_getIDCurrentUser()));
    }

	/**
	 * torna un array con la variabile archiviata in un array
	 */
	private function _value2array($name, &$val, &$arr) {
		$type = null ; 
		switch(gettype($val)) {
			case "integer" : 	{ $type = "integer" ; } break ;
			case "boolean" : 	{ $type = "bool" ; } break ;
			case "double" : 	{ $type = "float" ; } break ;
			case "string" :		{ $type = "string" ; } break ;
					
			default: {
				$type = "stream" ;
				$val = serialize($val) ;
 			}
		}
		$arr = array(
			'name'		=> $name,
			'type'		=> $type,
			$type		=> $val
		) ;	
	}
		
	/**
	 * Get object id from an identifier that could be an id or nickname
	 * @param mixed $val
	 */
	public function objectId($val) {
		$res = 0;
		if(is_numeric($val)) {
			$res = $val;
		} else {
			$res = $this->getIdFromNickname(strtolower($val));
		}
		return $res; 
	}
	
	/**
	 * Get object id from unique name 
	 * @param string $nickname
	 */
	function getIdFromNickname($nickname, $status = null) {
		$id = null;
		if($status != null) {
			$id = $this->field("id", array("nickname" => $nickname, "status" => $status));
		} else {
			$id = $this->field("id", array("nickname" => $nickname));
		}
		if(empty($id)) { // if nickname not found lookup aliases
			$aliasModel = ClassRegistry::init("Alias");
			$id = $aliasModel->field("object_id", array("nickname_alias" => $nickname));
		}
		return $id; 
	}

	/**
	 * Get object nickname from id 
	 * @param integer $id
	 */
	function getNicknameFromId($id) {
		return $this->field("nickname", array("id" => $id));
	}
}
?>