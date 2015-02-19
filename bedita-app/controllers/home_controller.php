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
 * BEdita main page
 *
 *
 * @version			$Revision: 3903 $
 * @modifiedby 		$LastChangedBy: ste $
 * @lastmodified	$LastChangedDate: 2012-08-08 18:17:49 +0200 (Wed, 08 Aug 2012) $
 *
 * $Id: home_controller.php 3903 2012-08-08 16:17:49Z ste $
 *  */
class HomeController extends AppController {

	var $uses = array('BEObject', 'Tree') ;
	var $helpers 	= array('BeTree');
	var $components = array('BeUploadToObj', 'BeSecurity');


	public function index() {
	 	$conf  = Configure::getInstance();

	 	$user = $this->Session->read("BEAuthUser");
	 	$lastModBYUser = array();
	 	$lastMod = array();
	 	$userObjectTypes = $this->userObjectTypeIds();

	 	$lastModFilter = array('object_type_id' => $userObjectTypes);
	 	$lastMod = $this->BEObject->findObjects(null, $user['userid'], null, $lastModFilter, 'modified', false, 1, 10);
	 	foreach ($lastMod['items'] as &$item) {
	 		$item['module_name'] = $conf->objectTypes[$item['object_type_id']]['module_name'];
	 	}

	 	$lastModFilter['user_modified'] = $user['id'];
	 	$lastModBYUser = $this->BEObject->findObjects(null, $user['userid'], null, $lastModFilter, 'modified', false, 1, 5);
	 	foreach ($lastModBYUser['items'] as &$item) {
	 		$item['module_name'] = $conf->objectTypes[$item['object_type_id']]['module_name'];
	 	}

	 	$filter = array();
	 	$filter["object_type_id"] = $conf->objectTypes['comment']["id"];
		$filter["ref_object_details"] = "Comment";
		$filter["ref_object_types"] = $userObjectTypes;
		$lastComments = $this->BEObject->findObjects(null, null, null, $filter, "modified", false, 1, 10);

	 	$filter["object_type_id"] = $conf->objectTypes['editor_note']["id"];
	 	$filter["ref_object_details"] = "EditorNote";
		$filter["user_created"] = "";
		$filter["ref_object_types"] = $userObjectTypes;
		$lastNotes = $this->BEObject->findObjects(null, null, null, $filter,  "modified", false, 1, 10);

	 	$connectedUser = $this->BeAuth->connectedUser();
	 	$this->set("lastModBYUser", $lastModBYUser['items']);
	 	$this->set("lastMod", $lastMod['items']);
	 	$this->set("lastNotes", $lastNotes["items"]);
	 	$this->set("lastComments", $lastComments["items"]);
	 	$this->set("connectedUser", $connectedUser);
		$this->set("noFooter", true);
		$this->set("bodyClass", "home");

		// get publications
		$treeModel = ClassRegistry::init("Tree");
		$user = $this->BeAuth->getUserSession();
		$expandBranch = array();
		if (!empty($filter['parent_id'])) {
			$expandBranch[] = $filter['parent_id'];
		} elseif (!empty($id)) {
			$expandBranch[] = $id;
		}
		$tree = $treeModel->getAllRoots($user['userid'], null, array('count_permission' => true), $expandBranch);

		$this->set('tree', $tree);
	 }

	/**
	 * Get object type ids that current user can view
	 */
	private function userObjectTypeIds() {
		$excludedObjectTypes = Configure::read("objectTypes.nodashboard.id");
		$userObjectTypes = ClassRegistry::init("ObjectType")->typesIdFromModules(array_keys($this->moduleList));
		return array_diff($userObjectTypes, $excludedObjectTypes);
	} 
	 
	/**
	 * Generic view methods redirects to specific module controller checking object type
	 *
	 * @param integer $id - object id to view
	 */
	public function view($id) {

		$this->action = "index";
		$id = $this->BEObject->objectId($id);
		$typeId = $this->BEObject->findObjectTypeId($id);
		$conf  = Configure::getInstance();
		if(!isset($conf->objectTypes[$typeId]["module_name"])) {
	 		throw new BeditaException(__("No module found for object", true));
		}
		$module = $conf->objectTypes[$typeId]["module_name"];
		$this->redirect("/".$module . "/view/" . $id);
	}


	public function search() {
		$this->layout = null;
		if (empty($this->params["form"]["filter"]["query"])) {
			$this->SessionFilter->clean();
		} else {
			$filter["object_type_id"] = $this->userObjectTypeIds();
			$filter = array_merge($filter, $this->SessionFilter->read());
			$user = $this->Session->read("BEAuthUser");
			$page = (!empty($this->params['form']['page']))? $this->params['form']['page'] : 1;
			$dim = (!empty($this->params['form']['dim']))? $this->params['form']['dim'] : 5;
			$objects = $this->BEObject->findObjects(null, $user["userid"], null, $filter, null, true, $page, $dim);
			// get objects module
			foreach ($objects["items"] as $key => $o) {
				$condition = "id=".$o['object_type_id'];
				$objects["items"][$key]["module_name"] = $this->BEObject->ObjectType->field("module_name", $condition);
			}
			$this->set("objects", $objects);
		}
	}


	public function profile() {
	}

	public function editProfile() {
		if (empty($this->data['User']['id'])) {
	 		throw new BeditaException(__("No user data", true));
	 	}

	 	$oldPwd = trim($this->params['form']['oldpwd']);
	 	$pwd = trim($this->data['User']['passwd']);
		$confirmPwd = trim($this->params['form']['pwd']);

		$userModel = ClassRegistry::init("User");

		if(empty($pwd) && empty($confirmPwd)) {
			unset($this->data['User']['passwd']);
		} else {
			$user = $userModel->find("first", array(
		 			"conditions" => array(
		 				"id" => $this->data["User"]["id"],
		 				"passwd" => md5($oldPwd)
		 			),
		 			"contain" => array()
		 		)
		 	);

		 	if (!$user) {
		 		throw new BeditaException(__("Wrong old user password", true));
		 	}

			if (!$this->BeAuth->checkConfirmPassword($pwd, $confirmPwd)) {
				throw new BeditaException(__("Passwords mismatch",true));
			}
		}

		if (empty($this->data["User"]["notify_changes"])) {
			$this->data["User"]["notify_changes"] = null;
		}
	 	if (empty($this->data["User"]["lang"])) {
			$this->data["User"]["lang"] = null;
		}
		if (empty($this->params["form"]["comments"])) {
			$this->data["User"]["comments"] = "never";
		}
		 if (empty($this->params["form"]["notes"])) {
			$this->data["User"]["notes"] = "never";
		}
	 	$this->Transaction->begin();
	 	$this->BeAuth->updateUser($this->data);
	 	$this->Transaction->commit();
	 	$userModel->containLevel("default");
	 	$user = $userModel->findById($this->data["User"]["id"]);
	 	$userModel->compact($user);
	 	$this->Session->write($this->BeAuth->sessionKey, $user);
	 	if (!empty($user["lang"])) {
	 		$this->Session->write('Config.language',$user["lang"]);
	 	}
		$this->eventInfo("user ".$this->data['User']['userid']." updated");
		$this->userInfoMessage(__("User updated",true));
	}


    protected function forward($action, $result) {
        $redirect = array(
            'editProfile' => array(
                'OK' => '/home/profile',
                'ERROR' => '/home/profile'
            ),
            'view' => array(
                'ERROR' => '/home/index'
            )
        );
        if (isset($redirect[$action][$result])) {
            return $redirect[$action][$result];
        }
        return false;
    }

}
