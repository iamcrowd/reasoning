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

    /**
       @testdox Convert an empty UML into JSON.
     */
    public function testUMLConstructor(){
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
       @testdox Convert a UML class without attributes into JSON.
     */
    public function testUMLClassWithoutAttrsToJson(){
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
       @testdox Convert an UML class with attributes into JSON
     */
    public function testUMLClassWithAttrsToJson(){
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
	    ]);
	$actual = $d->to_json();

	$this->assertJsonStringEqualsJsonString($expected, $actual, true);

    }

    
    /**
       @testdox Convert an UML generalization into JSON.
     */
    public function testUMLGenToJson(){
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
	$d->insert_subsumption(["Student"],"Person");
	$actual = $d->to_json();

	$this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }


    /**
       @testdox Convert an UML association into JSON.
     */
    public function testUMLAssocToJson(){
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
	$d->insert_relationship(["Person","Student"],
			       "R1",
			       ["2..4","1..*"],
			       ["e","c"]);
	$actual = $d->to_json();

	$this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }

    /**
       @testdox Convert UML into a JSON representation.
     */
    public function testUMLToJson(){
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
	$d->insert_class_with_attr("Person",
				  [["name" => "dni",
				    "datatype" => "String"],
				   ["name" => "firstname",
				    "datatype" => "String"]]);
	$d->insert_class_without_attr("Student");
	$d->insert_class_without_attr("Class1");
	$d->insert_subsumption(["Student"],"Person");
	$d->insert_relationship(["Student","Class1"],
			       "R1",
			       ["2..4","1..*"],
			       ["e","c"]);
	$actual = $d->to_json();

	$this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }

}

?>
