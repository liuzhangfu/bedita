<?php
/**-----8<--------------------------------------------------------------------
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
namespace BEdita\Controller;

class UsersController extends AppController {

    public function login() {
        if ($this->request->is('post')) {
            $user = $this->Auth->identify();
            if ($user) {
                $this->Auth->setUser($user);
                // migrate old hashed password to new algorithm
                if ($this->Auth->authenticationProvider()->needsPasswordRehash()) {
                    $userEntity = $this->Users->get($this->Auth->user('id'));
                    $userEntity->passwd = $this->request->data('passwd');
                    $this->Users->save($userEntity);
                }
                return $this->redirect($this->Auth->redirectUrl());
            } else {
                $this->Flash->error(
                    __('Wrong username/password or session expired'),
                    'default',
                    [],
                    'auth'
                );
            }
        }
    }

    public function logout() {
        $this->redirect($this->Auth->logout());
    }

}
