<?php
/* 

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
 */


require_once("common.php");


load("v2_validator.php", "json_validators/");

use JSONValidators\V2Validator;

/**
   @testdox Convert a UML model from JSON V1 format to V2.
 */
class V2ValidatorTest extends PHPUnit\Framework\TestCase{

    /**
       @testdox Can validate a simple JSON correctly
     */
    public function test_validate(){
        $input = file_get_contents('json_validators/data/simple_v2.json');
        
        $validator = new V2Validator($input);
        $actual = $validator->validate();

        $this->assertTrue($actual);
    }


}

?>
