<?php
/**
Test the UML JSON document generator.

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

require_once __DIR__ . '/../../../wicom/translator/documents/umljsondocument.php';


use Wicom\Translator\Documents\UMLJSONDocument;

/**
Test the UML JSON document generator.

@testdox UMLJSonDocument tests

@category Tests
@package  Crowd
@author   Gimenez Christian <christian.gimenez@fi.uncoma.edu.ar>
@author   Germán Braun <german.braun@fi.uncoma.edu.ar>
@author   GILIA <nomail@fi.uncoma.edu.ar>
@license  GPLv3 https://www.gnu.org/licenses/gpl-3.0.html
@link     http://crowd.fi.uncoma.edu.ar
 */
class UMLJSONDocumentTest extends PHPUnit\Framework\TestCase
{

    /**
    Convert an empty UML into JSON.

    @testdox Convert an empty UML into JSON.

    @return Nothing.
     */
    public function testUMLConstructor()
    {
        $expected = <<<'EOT'
	{
    "namespaces": {
	"ontologyIRI": "http://crowd.fi.uncoma.edu.ar/kb1#",
	"defaultIRIs": [],
	"IRIs": []
    },
    "classes":[],
    "links":[],
    "owllink": []
}
EOT;

        $d = new UMLJSONDocument();
        $actual = $d->to_json();

        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }

    /**
    Convert a UML class without attributes into JSON.

    @testdox Convert a UML class without attributes into JSON.

    @return Nothing.
     */
    public function testUMLClassWithoutAttrsToJson()
    {
        $expected = <<<'EOT'
{
    "namespaces": {
	"ontologyIRI": "http://crowd.fi.uncoma.edu.ar/kb1#",
	"defaultIRIs": [],
	"IRIs": []
    },
    "classes":[{"name":"Person","attrs":[], "methods":[]}],
    "links":[],
    "owllink": []
}
EOT;

        $d = new UMLJSONDocument();
        $d->insert_class_without_attr("Person");
        $actual = $d->to_json();

        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }

    /**
    Convert an UML class with attributes into JSON

    @testdox Convert an UML class with attributes into JSON

    @return Nothing.
     */
    public function testUMLClassWithAttrsToJson()
    {
        $expected = <<<'EOT'
{
    "namespaces": {
	"ontologyIRI": "http://crowd.fi.uncoma.edu.ar/kb1#",
	"defaultIRIs": [],
	"IRIs": []
    },
    "classes":[{
	"name": "Person",
        "attrs":[
	    {"name": "dni", "datatype": "String"},
            {"name": "firstname", "datatype": "String"}
	],
       "methods": []
    }],
    "links":[],
    "owllink": []
}
EOT;

        $d = new UMLJSONDocument();
        $d->insert_class_with_attr(
            "Person",
            [
                ["name" => "dni",
                 "datatype" => "String"],
                ["name" => "firstname",
                 "datatype" => "String"]
            ]
        );
        $actual = $d->to_json();

        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }

    
    /**
    Convert an UML generalization into JSON.

    @testdox Convert an UML generalization into JSON.

    @return Nothing.
     */
    public function testUMLGenToJson()
    {
        $expected = <<<'EOT'
{
    "namespaces": {
	"ontologyIRI": "http://crowd.fi.uncoma.edu.ar/kb1#",
	"defaultIRIs": [],
	"IRIs": []
    },
    "classes":[{"name":"Person","attrs":[],"methods":[]},
               {"name":"Student","attrs":[],"methods":[]}],
    "links":[{"name":"http://crowd.fi.uncoma.edu.ar/kb1#s1",
              "classes":["Student"],
              "multiplicity":null,
              "roles":[null,null],
              "type":"generalization",
              "parent":"Person",
              "constraint":[]}],
    "owllink": []
}
EOT;

        $d = new UMLJSONDocument();
        $d->insert_class_without_attr("Person");
        $d->insert_class_without_attr("Student");
        $d->insert_subsumption(["Student"], "Person");
        $actual = $d->to_json();

        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }


    /**
    Convert an UML association into JSON.

    @testdox Convert an UML association into JSON.

    @return Nothing.
     */
    public function testUMLAssocToJson()
    {
        $expected = <<<'EOT'
{
    "namespaces": {
	"ontologyIRI": "http://crowd.fi.uncoma.edu.ar/kb1#",
	"defaultIRIs": [],
	"IRIs": []
    },
 "classes":[{"name":"Person","attrs":[],"methods":[]},
            {"name":"Student","attrs":[],"methods":[]}],
 "links":[{"name":"R1","classes":["Person","Student"],
           "multiplicity":["2..4","1..*"],
           "roles":["e","c"],
           "type":"association"}],
 "owllink": []
}
EOT;

        $d = new UMLJSONDocument();
        $d->insert_class_without_attr("Person");
        $d->insert_class_without_attr("Student");
        $d->insert_relationship(
            ["Person","Student"],
            "R1",
            ["2..4","1..*"],
            ["e","c"]
        );
        $actual = $d->to_json();

        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }

    /**
    Convert UML into a JSON representation.

    @testdox Convert UML into a JSON representation.

    @return Nothing.
     */
    public function testUMLToJson()
    {
        $expected = <<<'EOT'
{
    "namespaces": {
	"ontologyIRI": "http://crowd.fi.uncoma.edu.ar/kb1#",
	"defaultIRIs": [],
	"IRIs": []
    },
    "classes":[
      	{"name":"Person","attrs":[{"name":"dni","datatype":"String"},
				  {"name":"firstname","datatype":"String"}],
	 "methods":[]},
      	{"name":"Student","attrs":[],
	 "methods":[]},
        {"name":"Class1","attrs":[],
	 "methods":[]}],
    "links":[
      	{"name":"http://crowd.fi.uncoma.edu.ar/kb1#s1",
         "classes":["Student"],
	 "multiplicity":null,
	 "roles":[null,null],
	 "type":"generalization",
	 "parent":"Person",
	 "constraint":[]},
      	{"name":"R1",
         "classes":["Student","Class1"],
	 "multiplicity":["2..4","1..*"],
      	 "roles":["e","c"],
      	 "type":"association"}],
  "owllink": []
}
  
EOT;

        $d = new UMLJSONDocument();
        $d->insert_class_with_attr(
            "Person",
            [["name" => "dni",
              "datatype" => "String"],
             ["name" => "firstname",
              "datatype" => "String"]]
        );
        $d->insert_class_without_attr("Student");
        $d->insert_class_without_attr("Class1");
        $d->insert_subsumption(["Student"], "Person");
        $d->insert_relationship(
            ["Student","Class1"],
            "R1",
            ["2..4","1..*"],
            ["e","c"]
        );
        $actual = $d->to_json();

        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }
}

?>
