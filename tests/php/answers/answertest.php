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

use Wicom\Translator\Builders\OWLlinkBuilder;
use Wicom\Translator\Builders\OWLBuilder;

use Wicom\Translator\Strategies\QAPackages\AnswerAnalizers\Answer;

class AnswerTest extends PHPUnit\Framework\TestCase
{

    public function testAnswerJsonGraphical(){
        $expected = <<<EOT
       {
           "satisfiable": {
               "kb" : true,
               "classes" : ["name1", "name2"]
           },
           "unsatisfiable": {
              	"classes" : ["name3", "name4"]
           },
           "graphical_suggestions" : {
              	"links" : [
              	    {"name" : "suggestion1",
              	     "classes": ["classname1", "classname2"],
                     "multiplicity":null,
                     "roles":[null,null],
                     "type":"generalization",
                     "parent":"classname1",
                     "constraint":[]}
              	]
           },
           "non_graphical_suggestion" : {
                "links" : []
           },
           "reasoner" : {
              	"input" : "STRING WITH REASONER INPUT",
              	"output" : "STRING WITH REASONER OUTPUT"
           }
       }
EOT;

        $answer = new Answer();
        $answer->set_kb_satis(true);
        $answer->add_satis_class("name1");
        $answer->add_satis_class("name2");
        $answer->add_unsatis_class("name3");
        $answer->add_unsatis_class("name4");
        $answer->add_subsumption_link_sugges("suggestion1", ["classname1", "classname2"],"classname1",[]);
        $answer->set_reasoner_input("STRING WITH REASONER INPUT");
        $answer->set_reasoner_output("STRING WITH REASONER OUTPUT");

        $actual = $answer->to_json();

        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }


    public function testAnswerJsonNongraphical(){
        $expected = <<<EOT
       {
           "satisfiable": {
               "kb" : true,
               "classes" : ["name1", "name2"]
           },
           "unsatisfiable": {
              	"classes" : ["name3", "name4"]
           },
           "graphical_suggestions" : {
              	"links" : []
           },
           "non_graphical_suggestion" : {
                "links" : [
                    {"name" : "suggestion1",
                     "classes": ["classname1", "classname2"],
                     "type":"disjoint"}
                 ]
           },
           "reasoner" : {
              	"input" : "STRING WITH REASONER INPUT",
              	"output" : "STRING WITH REASONER OUTPUT"
           }
       }
EOT;

        $answer = new Answer();
        $answer->set_kb_satis(true);
        $answer->add_satis_class("name1");
        $answer->add_satis_class("name2");
        $answer->add_unsatis_class("name3");
        $answer->add_unsatis_class("name4");
        $answer->add_disjoint_link_sugges("suggestion1", ["classname1", "classname2"]);
        $answer->set_reasoner_input("STRING WITH REASONER INPUT");
        $answer->set_reasoner_output("STRING WITH REASONER OUTPUT");

        $actual = $answer->to_json();

        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }


    public function testAnswerJsonBoth(){
        $expected = <<<EOT
       {
           "satisfiable": {
               "kb" : true,
               "classes" : ["name1", "name2"]
           },
           "unsatisfiable": {
                "classes" : ["name3", "name4"]
           },
           "graphical_suggestions" : {
                "links" : [
                    {"name" : "suggestion1",
                     "classes": ["classname1", "classname2"],
                     "multiplicity":null,
                     "roles":[null,null],
                     "type":"generalization",
                     "parent":"classname1",
                     "constraint":[]}
                ]
           },
           "non_graphical_suggestion" : {
                "links" : [
                    {"name" : "suggestion1",
                     "classes": ["classname1", "classname2"],
                     "type":"disjoint"}
                 ]
           },
           "reasoner" : {
                "input" : "STRING WITH REASONER INPUT",
                "output" : "STRING WITH REASONER OUTPUT"
           }
       }
EOT;

        $answer = new Answer();
        $answer->set_kb_satis(true);
        $answer->add_satis_class("name1");
        $answer->add_satis_class("name2");
        $answer->add_unsatis_class("name3");
        $answer->add_unsatis_class("name4");
        $answer->add_subsumption_link_sugges("suggestion1", ["classname1", "classname2"],"classname1",[]);
        $answer->add_disjoint_link_sugges("suggestion1", ["classname1", "classname2"]);
        $answer->set_reasoner_input("STRING WITH REASONER INPUT");
        $answer->set_reasoner_output("STRING WITH REASONER OUTPUT");

        $actual = $answer->to_json();

        $this->assertJsonStringEqualsJsonString($expected, $actual, true);
    }


    public function testAnswerOWL2(){
        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Ontology xmlns="http://www.w3.org/2002/07/owl#"
          xml:base=""
          xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
          xmlns:xml="http://www.w3.org/XML/1998/namespace"
          xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
          xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
          ontologyIRI="http://localhost/kb1">
    <DisjointClasses>
      <Class IRI="#A"/>
      <Class IRI="#B"/>
    </DisjointClasses>
</Ontology>
XML;

        $owl = new OWLBuilder();
        $answer = new Answer($owl);

        $answer->translate_responses([["disjointclasses" => [
            ["class" => "A"],
            ["class" => "B"]]]]);
        $actual = $answer->get_new_owl2()->to_string();

        var_dump($actual);

        $this->assertXmlStringEqualsXmlString($expected, $actual, true);
    }
}
