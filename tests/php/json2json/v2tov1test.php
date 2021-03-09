<?php
/**
JSON V2 to V1 API tests.

Copyright 2019 GILIA
   
Author: GILIA   

v2tov1test.php
   
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
require_once __DIR__ . '/../../../json2json/v2tov1.php';

use Json2Json\v2tov1;

/**
Test if the API is capable to convert crowd JSON version 2 documents into version 1.

@testdox Convert a UML model from JSON V1 format to V2.

@category Tests
@package  Crowd
@author   Gimenez Christian <christian.gimenez@fi.uncoma.edu.ar>
@author   Germán Braun <german.braun@fi.uncoma.edu.ar>
@author   GILIA <nomail@fi.uncoma.edu.ar>
@license  GPLv3 https://www.gnu.org/licenses/gpl-3.0.html
@link     http://crowd.fi.uncoma.edu.ar
 */
class V2toV1Test extends PHPUnit\Framework\TestCase
{

    /**
    Can convert a set of classes
    
    @testdox Can convert a set of classes
    
    @return Nothing.
     */
    public function testClasses()
    {
        $input = file_get_contents('json2json/data/classes2.json');
        $expected = file_get_contents('json2json/data/classes1.json');

        $conv = new V2tov1($input);
        $conv->without_prefix_iris();
        $actual = $conv->classes_str();
        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }

    /**
    Can convert different kind of associations

    @testdox Can convert different kind of associations

    @return Nothing.
     */
    public function testAssociacions()
    {
        $input = file_get_contents('json2json/data/assoc2.json');
        $expected = file_get_contents('json2json/data/assoc1.json');

        $conv = new V2tov1($input);
        $conv->without_prefix_iris();
        $actual = $conv->associations_str();
        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }

    /**
    Can convert generalizations (disjoint, covering, etc.).

    @testdox Can convert generalizations (disjoint, covering, etc.).
    
    @return Nothing.
     */
    public function testGeneralizations()
    {
        $input = file_get_contents('json2json/data/gen2.json');
        $expected = file_get_contents('json2json/data/gen1.json');

        $conv = new V2tov1($input);
        $conv->without_prefix_iris();
        $actual = $conv->gen_str();
        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }

    /**
    Can convert a complete sample model

    @testdox Can convert a complete sample model
    
    @return Nothing.
     */
    public function testAll()
    {
        $input = file_get_contents('json2json/data/v2_model.json');
        $expected = file_get_contents('json2json/data/v1_model.json');

        $conv = new V2tov1($input);
        $conv->without_prefix_iris();
        $actual = $conv->convert_str();
        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }

    
}
?>
