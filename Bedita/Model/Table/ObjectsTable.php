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
namespace Bedita\Model\Table;

use Cake\ORM\Table;

/**
 * Represents the objects table
 */
class ObjectsTable extends Table {

    public function initialize(array $config) {
        $this->table('objects');
        $this->entityClass('Bedita\Model\Entity\Object');

        $this->belongsTo('ObjectTypes');

        $this->belongsTo('UserCreated', [
            'className' => 'Bedita\Model\Table\UsersTable',
            'foreignKey' => 'user_created',
            'propertyName' => 'created_by'
        ]);

        $this->belongsTo('UserModified', [
            'className' => 'Bedita\Model\Table\UsersTable',
            'foreignKey' => 'user_modified',
            'propertyName' => 'modified_by'
        ]);

        $this->hasMany('Permissions', [
            'foreignKey' => 'object_id'
        ]);

        $this->hasMany('Versions', [
            'foreignKey' => 'object_id'
        ]);

        $this->hasMany('ObjectProperties', [
            'foreignKey' => 'object_id'
        ]);

        $this->hasMany('SearchTexts', [
            'foreignKey' => 'object_id'
        ]);

        $this->hasMany('LangTexts', [
            'foreignKey' => 'object_id'
        ]);

        $this->hasMany('Annotations', [
            'foreignKey' => 'object_id'
        ]);

        $this->hasMany('ObjectRelations', [
            'foreignKey' => 'id'
        ]);

        $this->hasMany('Aliases', [
            'foreignKey' => 'object_id'
        ]);

        $this->hasMany('GeoTags', [
            'foreignKey' => 'object_id'
        ]);

        $this->belongsToMany('Categories', [
            'joinTable' => 'object_categories',
            'foreignKey' => 'object_id'
        ]);

        $this->belongsToMany('Users', [
            'joinTable' => 'object_users',
            'through' => 'ObjectUsers',
            'foreignKey' => 'object_id'
        ]);
    }

}
