<?php
/*

   Copyright 2016 Giménez, Christian. Germán Braun.

   Author: Giménez, Christian. Germán Braun.

   testAnswer.php

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

/*
   OWLlink Queries and Answers

   <IsKBSatisfiable kb=""/>                     <BooleanResponse result="true"/>
   -- Not supported by any reasoner --
   <IsKBConsistentlyDeclared kb=""/>            <Error error="OWLlink request IsKBConsistentlyDeclared not yet implemented"/>

   <IsClassSatisfiable kb="">                   <BooleanResponse result="true"/>
   <owl:Class IRI="Person"/>
   </IsClassSatisfiable>
   -- Not supported by Konclude --
   <IsObjectPropertySatisfiable kb="">          <BooleanResponse result="true"/>
   <owl:ObjectProperty IRI="r2"/>
   </IsObjectPropertySatisfiable>

   <IsEntailed kb="">                           <BooleanResponse result="false"/>
   <owl:EquivalentClasses>
   <owl:Class IRI="Student"/>
   <owl:Class IRI="Student_r2_min"/>
   </owl:EquivalentClasses>
   </IsEntailed>

   <GetAllClasses kb=""/>                <SetOfClasses><owl:Class IRI="file://examples/crowd.owllink#Person"/><SetOfClasses/>

   <GetAllObjectProperties kb=""/>       <SetOfObjectProperties><owl:ObjectProperty IRI="file://examples/crowd.owllink#r2"/><SetOfObjectProperties/>

   <GetSubClasses kb="">                 <SetOfClassSynsets>
   <owl:Class IRI="Person"/>            <ClassSynset><owl:Class abbreviatedIRI="owl:Nothing"/></ClassSynset>
   </GetSubClasses>                        <ClassSynset><owl:Class IRI="file://examples/crowd.owllink#Student"/></ClassSynset>
   <SetOfClassSynsets/>

   <GetSuperClasses kb="">               <SetOfClassSynsets>
   <owl:Class IRI="Person"/>             <ClassSynset><owl:Class abbreviatedIRI="owl:Thing"/></ClassSynset>
   </GetSuperClasses>                    </SetOfClassSynsets>

   <GetEquivalentClasses kb="">          <SetOfClasses>
   <owl:Class IRI="Person"/>          <owl:Class IRI="Person"/>
   </GetEquivalentClasses>                 <owl:Class IRI="Person_r2_max"/>
   </SetOfClasses>

   -- Not supported by Konclude --
   <GetDisjointClasses kb="">            <ClassSynsets>
   <owl:Class IRI="Person"/>           <ClassSynset>
   </GetDisjointClasses>                    <owl:Class abbreviatedIRI="owl:Nothing"/>
   </ClassSynset>
   </ClassSynsets>

   <GetSubClassHierarchy kb=""/>         <ClassSubClassesPair>
   <ClassSynset>
   <owl:Class IRI="Person"/>
   <owl:Class IRI="Person_r2_max"/>
   </ClassSynset>
   <SubClassSynsets>
   <ClassSynset>
   <owl:Class IRI="Person_r2_min"/>
   </ClassSynset>
   <ClassSynset>
   <owl:Class IRI="Student_r3_min"/>
   <owl:Class IRI="Student"/>
   </ClassSynset>
   </SubClassSynsets>
   </ClassSubClassesPair>
 */


require_once("common.php");

// use function \load;
load("answer.php", "wicom/translator/strategies/qapackages/answeranalizers/");

// use function \load;
load("owllinkbuilder.php", "wicom/translator/builders/");
load("owlbuilder.php", "wicom/translator/builders/");
load("umljsonbuilder.php", "wicom/translator/builders/");

use Wicom\Translator\Builders\OWLlinkBuilder;
use Wicom\Translator\Builders\OWLBuilder;
use Wicom\Translator\Builders\UMLJSONBuilder;

use Wicom\Translator\Strategies\QAPackages\AnswerAnalizers\Answer;

/**
   @testdox Answer object to create and encapsulate answers
 */
class AnswerTest extends PHPUnit\Framework\TestCase
{


    /**
       @testdox Can create a JSON answer with some classes
     */
    public function testAnswerJsonGraphical(){
        $expected = <<<EOT
{
"satisfiable": {
  "kb" : true,
  "classes" : ["name1", "name2"],
  "objectproperties" : [],
  "dataproperties" : []
  },
"unsatisfiable": {
  "classes" : ["name3", "name4"],
  "objectproperties": [],
  "dataproperties": []
  },
"subsumptions" : [
  { "name" : "suggestion1",
    "classes": ["classname1", "classname2"],
    "multiplicity":null,
    "roles":[null,null],
    "type":"generalization",
    "parent":"classname1",
    "constraint":[]}
  ],
"disjunctions": [],
"equivalences": [],
"reasoner" : {
  "input" : "STRING WITH REASONER INPUT",
  "output" : "STRING WITH REASONER OUTPUT"
  },
"inferredSubs" : [],
"inferredCards" : [],
"inferredDisj" : [],
"inferredEquiv" : []
}
EOT;

	$builder = new UMLJSONBuilder();
        $answer = new Answer($builder);
        $answer->set_kb_satis(true);
        $answer->add_satis_class("name1");
        $answer->add_satis_class("name2");
        $answer->add_unsatis_class("name3");
        $answer->add_unsatis_class("name4");
        $answer->add_subsumption("suggestion1",
				["classname1", "classname2"],
				"classname1", []);
        $answer->set_reasoner_input("STRING WITH REASONER INPUT");
        $answer->set_reasoner_output("STRING WITH REASONER OUTPUT");

        $actual = $answer->to_json();

        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }


    /**
       @testdox Can create a JSON answer with a disjunction
     */
    public function testAnswerJsonNongraphical(){
        $expected = <<<EOT
{
"satisfiable": {
  "kb" : true,
  "classes" : ["name1", "name2"],
  "objectproperties" : [],
  "dataproperties" : []
  },
"unsatisfiable": {
  "classes" : ["name3", "name4"],
  "objectproperties": [],
  "dataproperties": []
  },
"subsumptions" : [],
"disjunctions": [
  { "type" : "disjoint",
    "name": "suggestion1",
    "classes" : ["classname1", "classname2"]
  }
],
"equivalences": [],
"reasoner" : {
  "input" : "STRING WITH REASONER INPUT",
  "output" : "STRING WITH REASONER OUTPUT"
  },
"inferredSubs" : [],
"inferredCards" : [],
"inferredDisj" : [],
"inferredEquiv" : []
}
EOT;

	$builder = new UMLJSONBuilder();
        $answer = new Answer($builder);
        $answer->set_kb_satis(true);
        $answer->add_satis_class("name1");
        $answer->add_satis_class("name2");
        $answer->add_unsatis_class("name3");
        $answer->add_unsatis_class("name4");
        $answer->add_disjoint("suggestion1", ["classname1", "classname2"]);
        $answer->set_reasoner_input("STRING WITH REASONER INPUT");
        $answer->set_reasoner_output("STRING WITH REASONER OUTPUT");

        $actual = $answer->to_json();

        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }


    /**
       @testdox Can create an answer in JSON with a subsumption and a disjoint
     */
    public function testAnswerJsonBoth(){
        $expected = <<<EOT
{
"satisfiable": {
  "kb" : true,
  "classes" : ["name1", "name2"],
  "objectproperties" : [],
  "dataproperties" : []
  },
"unsatisfiable": {
  "classes" : ["name3", "name4"],
  "objectproperties": [],
  "dataproperties": []
  },
"subsumptions" : [
  { "name" : "suggestion1",
    "classes": ["classname1", "classname2"],
    "multiplicity":null,
    "roles":[null,null],
    "type":"generalization",
    "parent":"classname1",
    "constraint":[]}
  ],
"disjunctions": [
  { "type" : "disjoint",
    "name": "suggestion1",
    "classes" : ["classname1", "classname2"]
  }
],
"equivalences": [],
"reasoner" : {
  "input" : "STRING WITH REASONER INPUT",
  "output" : "STRING WITH REASONER OUTPUT"
  },
"inferredSubs" : [],
"inferredCards" : [],
"inferredDisj" : [],
"inferredEquiv" : []
}
EOT;

	$builder = new UMLJSONBuilder();
        $answer = new Answer($builder);
        $answer->set_kb_satis(true);
        $answer->add_satis_class("name1");
        $answer->add_satis_class("name2");
        $answer->add_unsatis_class("name3");
        $answer->add_unsatis_class("name4");
        $answer->add_subsumption(
	    "suggestion1", ["classname1", "classname2"],"classname1",[]);
        $answer->add_disjoint(
	    "suggestion1", ["classname1", "classname2"]);
        $answer->set_reasoner_input("STRING WITH REASONER INPUT");
        $answer->set_reasoner_output("STRING WITH REASONER OUTPUT");

        $actual = $answer->to_json();

        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }


    /**
       @testdox Can create an answer in OWL 2 format
     */
    public function testAnswerOWL2(){
        $expected = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
<Ontology xmlns="http://www.w3.org/2002/07/owl#"
        ontologyIRI="http://crowd.fi.uncoma.edu.ar/kb1#"
        xml:base="http://crowd.fi.uncoma.edu.ar/kb1#"
        xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
        xmlns:xml="http://www.w3.org/XML/1998/namespace"
        xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
        xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#">
  <Prefix IRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#" name="rdf"/>
  <Prefix IRI="http://www.w3.org/2000/01/rdf-schema#" name="rdfs"/>
  <Prefix IRI="http://www.w3.org/2001/XMLSchema#" name="xsd"/>
  <Prefix IRI="http://www.w3.org/2002/07/owl#" name="owl"/>

  <DisjointClasses>
    <Class IRI="A"/>
    <Class IRI="B"/>
  </DisjointClasses>
</Ontology>
EOT;

        $owl = new OWLBuilder();
        $answer = new Answer($owl);

        $answer->translate_responses([["disjointclasses" => [
            ["class" => "A"],
            ["class" => "B"]]]]);
        $actual = $answer->get_new_owl2()->to_string();

        $this->assertXmlStringEqualsXmlString($expected, $actual, true);
    }
}
