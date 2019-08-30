<?php
/*

   Copyright 2016 Giménez, Christian. Germán Braun.

   Author: Giménez, Christian

   wicomtest.php

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

// use function \load;
load("wicom.php", "common/");
load("uml.php", "common/");
load("config.php", "config/");

use Wicom\Wicom;
use Wicom\UML_Wicom;

class WicomTest extends PHPUnit\Framework\TestCase
{

/*
    public function test_is_satisfiable_UML(){
        $input = '{"classes": [{"attrs":[], "methods":[], "name": "Hi World"}]}';
        $expected = <<<EOT
       {
           "satisfiable": {
               "kb" : true,
               "classes" : ["Hi World"]
           },
           "unsatisfiable": {
              	"classes" : []
           },
           "suggestions" : {
              	"links" : []
           },
           "reasoner" : {
              	"input" : "",
              	"output" : ""
           }
       }
EOT;


        $wicom = new Wicom();
        $answer = $wicom->is_satisfiable($input);

        $answer->set_reasoner_input("");
        $answer->set_reasoner_output("");
        $actual = $answer->to_json();

        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }


    public function test_full_reasoning_UML(){
        $input = '{"classes":[{"name":"Person","attrs":[
                                                {"name":"dni","datatype":"String"},
                                                {"name":"firstname","datatype":"String"},
                                                {"name":"surname","datatype":"String"},
                                                {"name":"birthdate","datatype":"Date"}],
                                                "methods":[],"position":{"x":287,"y":38}},
                              {"name":"Student","attrs":[
                                                {"name":"id","datatype":"String"},
                                                {"name":"enrolldate","datatype":"Date"}],
                                                "methods":[],"position":{"x":538,"y":251}}],
                    "links":[{"name":"r1","classes":["Student"],
                                          "multiplicity":null,
                                          "roles":[null,null],
                                          "type":"generalization",
                                          "parent":"Person",
                                          "constraint":[]}]
                  }';
        $expected = <<<EOT
       {
           "satisfiable": {
               "kb" : true,
               "classes" : ["Person", "Student"]
           },
           "unsatisfiable": {
                "classes" : []
           },
           "suggestions" : {
                "links" : []
           },
           "reasoner" : {
                "input" : "",
                "output" : ""
           }
       }
EOT;


        $wicom = new UML_Wicom();
        $answer = $wicom->full_reasoning($input);

        $answer->set_reasoner_input("");
        $answer->set_reasoner_output("");
        $actual = $answer->to_json();

        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }


    public function test_full_reasoning_UML_Inconsistent(){
        $input = '{"classes":[{"name":"Person","attrs":[
                                                {"name":"dni","datatype":"String"},
                                                {"name":"firstname","datatype":"String"},
                                                {"name":"surname","datatype":"String"},
                                                {"name":"birthdate","datatype":"Date"}],
                                                "methods":[],"position":{"x":287,"y":38}},
                              {"name":"Student","attrs":[
                                                {"name":"id","datatype":"String"},
                                                {"name":"enrolldate","datatype":"Date"}],
                                                "methods":[],"position":{"x":538,"y":251}},
                              {"name":"NoStudent",
                                                "attrs":[],
                                                "methods":[],
                                                "position":{"x":164,"y":274}}],
                    "links":[{"name":"r1","classes":["NoStudent","Student"],
                                  "multiplicity":null,
                                  "roles":[null,null],
                                  "type":"generalization","parent":"Person",
                                  "constraint":["disjoint","covering"]},
                              {"name":"r2","classes":["Student"],
                                  "multiplicity":null,
                                  "roles":[null,null],
                                  "type":"generalization","parent":"NoStudent",
                                  "constraint":[]}]
                  }';
        $expected = <<<EOT
       {
           "satisfiable": {
               "kb" : true,
               "classes" : ["Person", "NoStudent"]
           },
           "unsatisfiable": {
                "classes" : ["Student"]
           },
           "suggestions" : {
                "links" : []
           },
           "reasoner" : {
                "input" : "",
                "output" : ""
           }
       }
EOT;


        $wicom = new UML_Wicom();
        $answer = $wicom->full_reasoning($input);

        $answer->set_reasoner_input("");
        $answer->set_reasoner_output("");
        $actual = $answer->to_json();

        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }


    public function test_full_reasoning_UML_Class_CompareJSON(){
        $input = '{"namespaces":
          {"ontologyIRI":[{"prefix":"crowd","value":"http://crowd.fi.uncoma.edu.ar#"}],
           "defaultIRIs":[{"prefix":"rdf","value":"http://www.w3.org/1999/02/22-rdf-syntax-ns#"},
                          {"prefix":"rdfs","value":"http://www.w3.org/2000/01/rdf-schema#"},
                          {"prefix":"xsd","value":"http://www.w3.org/2001/XMLSchema#"},
                          {"prefix":"owl","value":"http://www.w3.org/2002/07/owl#"}],
            "IRIs":[]},
            "classes":[{"name":"http://crowd.fi.uncoma.edu.ar#Class1","attrs":[],"methods":[],"position":{"x":458,"y":217}}],
            "links":[]}';

        $expected = <<<EOT
       {
           "satisfiable": {
               "kb" : true,
               "classes" : ["Person", "NoStudent"]
           },
           "unsatisfiable": {
                "classes" : ["Student"]
           },
           "suggestions" : {
                "links" : []
           },
           "reasoner" : {
                "input" : "",
                "output" : ""
           }
       }
EOT;


        $wicom = new UML_Wicom();
        $answer = $wicom->full_reasoning($input);


//        $answer->set_reasoner_input("");
//        $answer->set_reasoner_output("");
//        $actual = $answer->to_json();

//        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }

    public function test_full_reasoning_UML_Subsumption_CompareJSON(){
        $input = '{"namespaces":
				{"ontologyIRI":[{"prefix":"crowd","value":"http://crowd.fi.uncoma.edu.ar#"}],
				 "defaultIRIs":[{"prefix":"rdf","value":"http://www.w3.org/1999/02/22-rdf-syntax-ns#"},
								{"prefix":"rdfs","value":"http://www.w3.org/2000/01/rdf-schema#"},
								{"prefix":"xsd","value":"http://www.w3.org/2001/XMLSchema#"},
								{"prefix":"owl","value":"http://www.w3.org/2002/07/owl#"}],
				"IRIs":[]},
				"classes":[{"name":"http://crowd.fi.uncoma.edu.ar#Class1","attrs":[],"methods":[],"position":{"x":298,"y":102}},
						   {"name":"http://crowd.fi.uncoma.edu.ar#Class2","attrs":[],"methods":[],"position":{"x":297,"y":431}}],
				"links":[{"name":"http://crowd.fi.uncoma.edu.ar#s1",
						  "parent":"http://crowd.fi.uncoma.edu.ar#Class1",
						  "classes":["http://crowd.fi.uncoma.edu.ar#Class2"],
						  "multiplicity":null,
						  "roles":null,
						  "type":"generalization",
						  "constraint":[],"position":{"x":297.5,"y":266.5}}]}';

        $expected = <<<EOT
       {
           "satisfiable": {
               "kb" : true,
               "classes" : ["Person", "NoStudent"]
           },
           "unsatisfiable": {
                "classes" : ["Student"]
           },
           "suggestions" : {
                "links" : []
           },
           "reasoner" : {
                "input" : "",
                "output" : ""
           }
       }
EOT;


        $wicom = new UML_Wicom();
        $answer = $wicom->full_reasoning($input);


//        $answer->set_reasoner_input("");
//        $answer->set_reasoner_output("");
//        $actual = $answer->to_json();

//        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }
*/
    public function test_full_reasoning_UML_BinaryAssocWithoutClass0N_CompareJSON(){
        $input = '{"namespaces":
				{"ontologyIRI":[{"prefix":"crowd","value":"http://crowd.fi.uncoma.edu.ar#"}],
				 "defaultIRIs":[{"prefix":"rdf","value":"http://www.w3.org/1999/02/22-rdf-syntax-ns#"},
								{"prefix":"rdfs","value":"http://www.w3.org/2000/01/rdf-schema#"},
								{"prefix":"xsd","value":"http://www.w3.org/2001/XMLSchema#"},
								{"prefix":"owl","value":"http://www.w3.org/2002/07/owl#"}],
				"IRIs":[]},
				"classes":[{"name":"http://crowd.fi.uncoma.edu.ar#Class1","attrs":[],"methods":[],"position":{"x":298,"y":102}},
						   {"name":"http://crowd.fi.uncoma.edu.ar#Class2","attrs":[],"methods":[],"position":{"x":297,"y":431}}],
				"links":[{"name":"http://crowd.fi.uncoma.edu.ar#r1",
						  "classes":["http://crowd.fi.uncoma.edu.ar#Class1","http://crowd.fi.uncoma.edu.ar#Class2"],
						  "multiplicity":["0..*","0..*"],
						  "roles":["http://crowd.fi.uncoma.edu.ar#class1","http://crowd.fi.uncoma.edu.ar#class2"],
						  "type":"association"}]}';

        $expected = <<<EOT
       {
           "satisfiable": {
               "kb" : true,
               "classes" : ["Person", "NoStudent"]
           },
           "unsatisfiable": {
                "classes" : ["Student"]
           },
           "suggestions" : {
                "links" : []
           },
           "reasoner" : {
                "input" : "",
                "output" : ""
           }
       }
EOT;


        $wicom = new UML_Wicom();
        $answer = $wicom->full_reasoning($input);


//        $answer->set_reasoner_input("");
//        $answer->set_reasoner_output("");
//        $actual = $answer->to_json();

//        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }

}
