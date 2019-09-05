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

class BackingFromReasoningTest extends PHPUnit\Framework\TestCase
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

/*
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
        $actual = $answer->to_json();
//		var_dump($actual);

//        $answer->set_reasoner_input("");
//        $answer->set_reasoner_output("");
//        $actual = $answer->to_json();

//        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }

    public function test_full_reasoning_UML_2BinaryAssocWithoutClass11_CompareJSON(){
        $input = '{"namespaces":{"ontologyIRI":[{"prefix":"crowd","value":"http://crowd.fi.uncoma.edu.ar#"}],
								 "defaultIRIs":[{"prefix":"rdf","value":"http://www.w3.org/1999/02/22-rdf-syntax-ns#"},
												{"prefix":"rdfs","value":"http://www.w3.org/2000/01/rdf-schema#"},
												{"prefix":"xsd","value":"http://www.w3.org/2001/XMLSchema#"},
												{"prefix":"owl","value":"http://www.w3.org/2002/07/owl#"}],
								 "IRIs":[]},
								 "classes":[{"name":"http://crowd.fi.uncoma.edu.ar#Class1","attrs":[],"methods":[],"position":{"x":282,"y":316}},
											{"name":"http://crowd.fi.uncoma.edu.ar#Class2","attrs":[],"methods":[],"position":{"x":780,"y":307}}],
								 "links":[{"name":"http://crowd.fi.uncoma.edu.ar#r2",
										   "classes":["http://crowd.fi.uncoma.edu.ar#Class1","http://crowd.fi.uncoma.edu.ar#Class2"],
										   "multiplicity":["1..1","2..3"],
										   "roles":["http://crowd.fi.uncoma.edu.ar#class1","http://crowd.fi.uncoma.edu.ar#class2"],
										   "type":"association"},
										 {"name":"http://crowd.fi.uncoma.edu.ar#r3",
										  "classes":["http://crowd.fi.uncoma.edu.ar#Class1","http://crowd.fi.uncoma.edu.ar#Class2"],
										  "multiplicity":["0..1","4..*"],
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
        $actual = $answer->to_json();
//		var_dump($actual);

//        $answer->set_reasoner_input("");
//        $answer->set_reasoner_output("");

//        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }


    public function test_full_reasoning_UML_BinaryAssocWithClassMN_CompareJSON(){
        $input = '{"namespaces":{"ontologyIRI":[{"prefix":"crowd","value":"http://crowd.fi.uncoma.edu.ar#"}],
								 "defaultIRIs":[{"prefix":"rdf","value":"http://www.w3.org/1999/02/22-rdf-syntax-ns#"},
												{"prefix":"rdfs","value":"http://www.w3.org/2000/01/rdf-schema#"},
												{"prefix":"xsd","value":"http://www.w3.org/2001/XMLSchema#"},
												{"prefix":"owl","value":"http://www.w3.org/2002/07/owl#"}],
								 "IRIs":[]},
				   "classes":[{"name":"http://crowd.fi.uncoma.edu.ar#Class1","attrs":[],"methods":[],"position":{"x":279,"y":258}},
							  {"name":"http://crowd.fi.uncoma.edu.ar#Class2","attrs":[],"methods":[],"position":{"x":779,"y":250}},
							  {"name":"http://crowd.fi.uncoma.edu.ar#assocClass","attrs":[],"methods":[],"position":{"x":513,"y":355}}],
				   "links":[{"name":"http://crowd.fi.uncoma.edu.ar#assocClass",
							 "classes":["http://crowd.fi.uncoma.edu.ar#Class1","http://crowd.fi.uncoma.edu.ar#Class2"],
							 "multiplicity":["1..1","2..3"],
							 "roles":["http://crowd.fi.uncoma.edu.ar#home","http://crowd.fi.uncoma.edu.ar#dog"],
							 "associated_class":{"name":"http://crowd.fi.uncoma.edu.ar#assocClass","attrs":[],"methods":[],"position":{"x":513,"y":355}},
							 "type":"association with class",
							 "position":{"x":529,"y":254}}]}';

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
        $actual = $answer->to_json();
		    var_dump($actual);

//        $answer->set_reasoner_input("");
//        $answer->set_reasoner_output("");
//        $actual = $answer->to_json();

//        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }

    */
    public function test_full_reasoning_UML_SubsumptionInferred_CompareJSON(){
        $input = '{"namespaces":{"ontologyIRI":[{"prefix":"crowd","value":"http://crowd.fi.uncoma.edu.ar#"}],
        "defaultIRIs":[{"prefix":"rdf","value":"http://www.w3.org/1999/02/22-rdf-syntax-ns#"},
        {"prefix":"rdfs","value":"http://www.w3.org/2000/01/rdf-schema#"},
        {"prefix":"xsd","value":"http://www.w3.org/2001/XMLSchema#"},
        {"prefix":"owl","value":"http://www.w3.org/2002/07/owl#"}],
        "IRIs":[]},"classes":[{"name":"http://crowd.fi.uncoma.edu.ar#Class1","attrs":[],"methods":[],"position":{"x":496,"y":101}},
        {"name":"http://crowd.fi.uncoma.edu.ar#Class2","attrs":[],"methods":[],"position":{"x":328,"y":331}},
        {"name":"http://crowd.fi.uncoma.edu.ar#Class3","attrs":[],"methods":[],"position":{"x":598,"y":320}},
        {"name":"http://crowd.fi.uncoma.edu.ar#Class4","attrs":[],"methods":[],"position":{"x":866,"y":317}}],
        "links":[{"name":"http://crowd.fi.uncoma.edu.ar#s1","parent":"http://crowd.fi.uncoma.edu.ar#Class1",
          "classes":["http://crowd.fi.uncoma.edu.ar#Class2","http://crowd.fi.uncoma.edu.ar#Class3"],
          "multiplicity":null,"roles":null,"type":"generalization","constraint":["disjoint","covering"],"position":{"x":412,"y":216}},
          {"name":"http://crowd.fi.uncoma.edu.ar#s2","parent":"http://crowd.fi.uncoma.edu.ar#Class1",
            "classes":["http://crowd.fi.uncoma.edu.ar#Class4"],"multiplicity":null,"roles":null,"type":
            "generalization","constraint":[],"position":{"x":681,"y":209}}],
            "owllink":"<owl:DisjointClasses><owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar#Class2\"/><owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar#Class4\"/></owl:DisjointClasses>"}';

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


	/*
	TODO: Should I erase this?
		  - Christian. September, 5 2019
	
        $wicom = new UML_Wicom();
        $answer = $wicom->full_reasoning($input);
        $actual = $answer->to_json();

	      var_dump($actual);
	 */
//        $answer->set_reasoner_input("");
//        $answer->set_reasoner_output("");
//        $actual = $answer->to_json();

//        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }

    public function test_full_reasoning_UML_CardinalityInferred_CompareJSON(){
        $input = '{"namespaces":{"ontologyIRI":[{"prefix":"crowd","value":"http://crowd.fi.uncoma.edu.ar#"}],
        "defaultIRIs":[{"prefix":"rdf","value":"http://www.w3.org/1999/02/22-rdf-syntax-ns#"},
        {"prefix":"rdfs","value":"http://www.w3.org/2000/01/rdf-schema#"},
        {"prefix":"xsd","value":"http://www.w3.org/2001/XMLSchema#"},
        {"prefix":"owl","value":"http://www.w3.org/2002/07/owl#"}],
        "IRIs":[]},"classes":[{"name":"http://crowd.fi.uncoma.edu.ar#Class1","attrs":[],"methods":[],"position":{"x":652,"y":227}},{"name":"http://crowd.fi.uncoma.edu.ar#Class2","attrs":[],"methods":[],"position":{"x":270,"y":237}},{"name":"http://crowd.fi.uncoma.edu.ar#Class3","attrs":[],"methods":[],"position":{"x":659,"y":457}},{"name":"http://crowd.fi.uncoma.edu.ar#Class4","attrs":[],"methods":[],"position":{"x":277,"y":456}}],"links":[{"name":"http://crowd.fi.uncoma.edu.ar#r1","classes":["http://crowd.fi.uncoma.edu.ar#Class2","http://crowd.fi.uncoma.edu.ar#Class1"],"multiplicity":["0..*","1..1"],"roles":["http://crowd.fi.uncoma.edu.ar#class2","http://crowd.fi.uncoma.edu.ar#class1"],"type":"association"},{"name":"http://crowd.fi.uncoma.edu.ar#r2","classes":["http://crowd.fi.uncoma.edu.ar#Class4","http://crowd.fi.uncoma.edu.ar#Class3"],"multiplicity":["0..*","0..*"],"roles":["http://crowd.fi.uncoma.edu.ar#class4","http://crowd.fi.uncoma.edu.ar#class3"],"type":"association"},
{"name":"http://crowd.fi.uncoma.edu.ar#s1","parent":"http://crowd.fi.uncoma.edu.ar#Class2","classes":["http://crowd.fi.uncoma.edu.ar#Class4"],"multiplicity":null,"roles":null,"type":"generalization","constraint":[],"position":{"x":274,"y":367.5}},{"name":"http://crowd.fi.uncoma.edu.ar#s2","parent":"http://crowd.fi.uncoma.edu.ar#Class1","classes":["http://crowd.fi.uncoma.edu.ar#Class3"],"multiplicity":null,"roles":null,"type":"generalization","constraint":[],"position":{"x":635,"y":364}}],
        "owllink":"<owl:SubObjectPropertyOf><owl:ObjectProperty IRI=\"http://crowd.fi.uncoma.edu.ar#r2\"/><owl:ObjectProperty IRI=\"http://crowd.fi.uncoma.edu.ar#r1\"/></owl:SubObjectPropertyOf>"}';


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

	/*
	   TODO: Should I erase this?
	   - Christian. September, 5 2019
        $wicom = new UML_Wicom();
        $answer = $wicom->full_reasoning($input);
        $actual = $answer->to_json();

	      var_dump($actual);
	 */
//        $answer->set_reasoner_input("");
//        $answer->set_reasoner_output("");
//        $actual = $answer->to_json();

//        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }
/*   {"namespaces":{"ontologyIRI":[{"prefix":"crowd","value":"http://crowd.fi.uncoma.edu.ar#"}],"defaultIRIs":[{"prefix":"rdf","value":"http://www.w3.org/1999/02/22-rdf-syntax-ns#"},{"prefix":"rdfs","value":"http://www.w3.org/2000/01/rdf-schema#"},{"prefix":"xsd","value":"http://www.w3.org/2001/XMLSchema#"},{"prefix":"owl","value":"http://www.w3.org/2002/07/owl#"}],"IRIs":[]},"classes":[{"name":"http://crowd.fi.uncoma.edu.ar#Class1","attrs":[],"methods":[],"position":{"x":652,"y":227}},{"name":"http://crowd.fi.uncoma.edu.ar#Class2","attrs":[],"methods":[],"position":{"x":264,"y":224}},{"name":"http://crowd.fi.uncoma.edu.ar#Class3","attrs":[],"methods":[],"position":{"x":627,"y":503}},{"name":"http://crowd.fi.uncoma.edu.ar#Class4","attrs":[],"methods":[],"position":{"x":258,"y":486}},{"name":"http://crowd.fi.uncoma.edu.ar#Class5","attrs":[],"methods":[],"position":{"x":844,"y":506}},{"name":"http://crowd.fi.uncoma.edu.ar#Class6","attrs":[],"methods":[],"position":{"x":1140,"y":492}}],"links":[{"name":"http://crowd.fi.uncoma.edu.ar#r1","classes":["http://crowd.fi.uncoma.edu.ar#Class2","http://crowd.fi.uncoma.edu.ar#Class1"],"multiplicity":["0..*","2..8"],"roles":["http://crowd.fi.uncoma.edu.ar#class2","http://crowd.fi.uncoma.edu.ar#class1"],"type":"association"},{"name":"http://crowd.fi.uncoma.edu.ar#r2","classes":["http://crowd.fi.uncoma.edu.ar#Class4","http://crowd.fi.uncoma.edu.ar#Class3"],"multiplicity":["0..*","0..9"],"roles":["http://crowd.fi.uncoma.edu.ar#class4","http://crowd.fi.uncoma.edu.ar#class3"],"type":"association"},{"name":"http://crowd.fi.uncoma.edu.ar#s1","parent":"http://crowd.fi.uncoma.edu.ar#Class2","classes":["http://crowd.fi.uncoma.edu.ar#Class4"],"multiplicity":null,"roles":null,"type":"generalization","constraint":[],"position":{"x":274,"y":367.5}},{"name":"http://crowd.fi.uncoma.edu.ar#s2","parent":"http://crowd.fi.uncoma.edu.ar#Class1","classes":["http://crowd.fi.uncoma.edu.ar#Class3","http://crowd.fi.uncoma.edu.ar#Class5"],"multiplicity":null,"roles":null,"type":"generalization","constraint":["disjoint","covering"],"position":{"x":635,"y":364}},{"name":"http://crowd.fi.uncoma.edu.ar#s3","parent":"http://crowd.fi.uncoma.edu.ar#Class1","classes":["http://crowd.fi.uncoma.edu.ar#Class6"],"multiplicity":null,"roles":null,"type":"generalization","constraint":[],"position":{"x":912,"y":284}}],"owllink":["<owl:SubObjectPropertyOf><owl:ObjectProperty IRI=\"http://crowd.fi.uncoma.edu.ar#r2\"/><owl:ObjectProperty IRI=\"http://crowd.fi.uncoma.edu.ar#r1\"/></owl:SubObjectPropertyOf><owl:DisjointClasses><owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar#Class3\"/><owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar#Class6\"/></owl:DisjointClasses>"]}  */
}
