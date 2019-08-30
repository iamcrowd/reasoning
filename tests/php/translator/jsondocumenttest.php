<?php
/*

   Copyright 2018

   Author: GILIA

   jsondocumenttest.php

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

//use function \load;
load("umljsondocument.php","wicom/translator/documents/");


use Wicom\Translator\Documents\UMLJSONDocument;

class JSONDocumentTest extends PHPUnit\Framework\TestCase{

    public function testUMLConstructor(){
        $expected = '{"classes":[], "links":[]}';

        $d = new UMLJSONDocument();
        $actual = $d->to_json();

        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }

    public function testUMLClassWithoutAttrsToJson(){
      $expected = '{"classes":[{"name":"Person","attrs":[], "methods":[]}], "links":[]}';

      $d = new UMLJSONDocument();
      $d->insert_class_without_attr("Person");
      $actual = $d->to_json();

      $this->assertJsonStringEqualsJsonString($expected, $actual, true);

    }

    public function testUMLClassWithAttrsToJson(){
      $expected = '{"classes":[{"name":"Person",
                                "attrs":[{"name":"dni","datatype":"String"},
                                         {"name":"firstname","datatype":"String"}],
                                "methods":[]}],"links":[]}';

      $d = new UMLJSONDocument();
      $d->insert_class_with_attr("Person",[["dni","String"],["firstname","String"]]);
      $actual = $d->to_json();

      $this->assertJsonStringEqualsJsonString($expected, $actual, true);

    }


    public function testUMLGenToJson(){
      $expected = '{"classes":[{"name":"Person","attrs":[],"methods":[]},
                               {"name":"Student","attrs":[],"methods":[]}],
                    "links":[{"name":"","classes":["Student"],
                  							 "multiplicity":null,
                  							 "roles":[null,null],
                  							 "type":"generalization",
                  							 "parent":"Person",
                  							 "constraint":[]}]}';

      $d = new UMLJSONDocument();
      $d->insert_class_without_attr("Person");
      $d->insert_class_without_attr("Student");
      $d->insert_subsumption(["Student"],"Person");
      $actual = $d->to_json();

      $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }


    public function testUMLAssocToJson(){
      $expected = '{"classes":[{"name":"Person","attrs":[],"methods":[]},
                               {"name":"Student","attrs":[],"methods":[]}],
                    "links":[{"name":"R1","classes":["Person","Student"],
                  					  "multiplicity":["2..4","1..*"],
                  						"roles":["e","c"],
                  						"type":"association"}]}';

      $d = new UMLJSONDocument();
      $d->insert_class_without_attr("Person");
      $d->insert_class_without_attr("Student");
      $d->insert_relationship("R1",["Person","Student"],["2..4","1..*"],["e","c"]);
      $actual = $d->to_json();

      $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }

    public function testUMLToJson(){
      $expected = '{"classes":[
      	{"name":"Person","attrs":[{"name":"dni","datatype":"String"},
      														{"name":"firstname","datatype":"String"}],
      									 "methods":[]},
      	{"name":"Student","attrs":[],
      										"methods":[]},
        {"name":"Class1","attrs":[],
      									 "methods":[]}],
      "links":[
      	{"name":"","classes":["Student"],
      							 "multiplicity":null,
      							 "roles":[null,null],
      							 "type":"generalization",
      							 "parent":"Person",
      							 "constraint":[]},
      	{"name":"R1","classes":["Student","Class1"],
      							 "multiplicity":["2..4","1..*"],
      							 "roles":["e","c"],
      							 "type":"association"}]
      }';

      $d = new UMLJSONDocument();
      $d->insert_class_with_attr("Person",[["dni","String"],["firstname","String"]]);
      $d->insert_class_without_attr("Student");
      $d->insert_class_without_attr("Class1");
      $d->insert_subsumption(["Student"],"Person");
      $d->insert_relationship("R1",["Student","Class1"],["2..4","1..*"],["e","c"]);
      $actual = $d->to_json();

      var_dump($actual);

      $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }

}

?>
