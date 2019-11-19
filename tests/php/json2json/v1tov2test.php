<?php 
/* 

   Copyright 2019 GILIA
   
   Author: GILIA   

   v1tov2test.php
   
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


load("v1tov2.php", "json2json/");

use Json2Json\v1tov2;

/**
   @testdox Convert a UML model from JSON V1 format to V2.
 */
class v1tov2Test extends PHPUnit\Framework\TestCase{

    /**
       @testdox Can convert a set of classes
     */
    public function testClasses(){
        $input = file_get_contents('json2json/data/classes1.json');
        $expected = file_get_contents('json2json/data/classes2.json');

        $conv = new V1toV2($input);
        $actual = $conv->classes_str();
        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }
    
    /**
       @testdox Can convert different kind of associations
     */
    
    public function testAssociacions(){
        $input = file_get_contents('json2json/data/assoc1.json');
        $expected = file_get_contents('json2json/data/assoc2.json');

        $conv = new V1toV2($input);
        $actual = $conv->associations_str();
        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }
    
    /**
       @testdox Can convert generalizations (disjoint, covering, etc.).
     */
    public function testGeneralizations(){
        $input = file_get_contents('json2json/data/gen1.json');
        $expected = file_get_contents('json2json/data/gen2.json');

        $conv = new V1toV2($input);
        $actual = $conv->gen_str();
        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }

//    /**
//       @testdox Can convert a complete sample model
//     */
//    public function testAll(){
//        $input = file_get_contents('json2json/data/v1_model.json');
//        $expected = file_get_contents('json2json/data/v2_model.json');
//
//        $conv = new V1toV2($input);
//        $actual = $conv->convert_str();
//        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
//    }
    
}
?>
