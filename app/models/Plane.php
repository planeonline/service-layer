<?php
/**
 * Copyright 2014 Takeaway IT Ltd.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 *
 * PHP version 5.4
 *
 * @category Model
 * @package  RESTFulPhalcon
 * @author   Ali Bahman <abn@webit4.me>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @link     http://www.planeonline.co.uk/
 */

use RESTFulPhalcon\RestModel;

/**
 * Class Plane
 *
 * PHP version 5.4
 *
 * @category Model
 * @package  RESTFulPhalcon
 * @author   Ali Bahman <abn@webit4.me>
 * @license  http://www.apache.org/licenses/LICENSE-2.0 Apache License, Version 2.0
 * @link     http://www.planeonline.co.uk/
 */
class Plane extends RestModel
{

    /**
     * to return a hash to be used in the RestModel to map field names
     *
     * @return array
     */
    protected function columnMap()
    {
        return array(
            'id' => 'id',
            'user' => 'user',
            'make' => 'make',
            'title' => 'title',
            'model' => 'model',
            'description' => 'description',
            'created' => 'created',
            'updated' => 'updated',
            'status' => 'status',
            'image' => 'image'
        );
    }

    /**
     * To set dynamic values for certain fields
     *
     * @return null
     */
    public function setDynamicFields()
    {
        $this->image = $this->Image->count();
    }

    /**
     * Overwriting RestModel initialize method to define model's relationships
     *
     * @return null|void
     */
    public function initialize()
    {
        parent::initialize();

        $this->belongsTo('make', 'Make', 'id');
        $this->belongsTo('user', 'User', 'id');
        $this->hasMany(
            'id', 'Image', 'plane', array(
                'foreignKey' => array(
                    'message' => 'Plane cannot be deleted because it has Images'
                )
            )
        );
    }

    /**
     * This method will be called during validation
     * to fetch available validators for this model
     *
     * @return RESTFulPhalcon\RestModel\Validator
     */
    public function getValidators()
    {
        if (is_null($this->validators)) {
            $this->validators = new \PlaneValidator();
        }

        return $this->validators;
    }

}
