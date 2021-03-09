<?php
/** 
JSON V2 validator tests.

Copyright 2020 GILIA

Author: GILIA

v2_validator.php

This program is free software: you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation, either version 3 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program.  If not, see <http://www.gnu.org/licenses/>.

PHP version >= 7.2

@category Tests
@package  Crowd
@author   Gimenez Christian <christian.gimenez@fi.uncoma.edu.ar>
@author   Germán Braun <german.braun@fi.uncoma.edu.ar>
@author   GILIA <nomail@fi.uncoma.edu.ar>
@license  GPLv3 https://www.gnu.org/licenses/gpl-3.0.html
@link     http://crowd.fi.uncoma.edu.ar
 */


require_once __DIR__ . '/../common.php';
require_once __DIR__ . '/../../../json_validators/v2_validator.php';

use JSONValidators\V2Validator;

/**
Tests if the API is capable to validate crowd JSON version 2 documents.

@testdox Convert a UML model from JSON V1 format to V2.

@category Tests
@package  Crowd
@author   Gimenez Christian <christian.gimenez@fi.uncoma.edu.ar>
@author   Germán Braun <german.braun@fi.uncoma.edu.ar>
@author   GILIA <nomail@fi.uncoma.edu.ar>
@license  GPLv3 https://www.gnu.org/licenses/gpl-3.0.html
@link     http://crowd.fi.uncoma.edu.ar
 */
class V2ValidatorTest extends PHPUnit\Framework\TestCase
{

    /**
    Can validate a simple JSON correctly

    @testdox Can validate a simple JSON correctly

    @return Nothing.
     */
    public function test_validate()
    {
        $input = file_get_contents('json_validators/data/simple_v2.json');
        
        $validator = new V2Validator($input);
        $actual = $validator->validate();

        $this->assertTrue($actual);
    }


}

?>
