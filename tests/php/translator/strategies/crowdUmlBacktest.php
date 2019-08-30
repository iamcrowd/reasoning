<?php
/*

   Copyright 2016 GILIA, Departamento de Teoría de la Computación, Universidad Nacional del Comahue

   Author: GILIA

   crowdUMLtest.php

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
load("crowd_uml.php", "wicom/translator/strategies/");
load("owllinkbuilder.php", "wicom/translator/builders/");
load("umljsonbuilder.php", "wicom/translator/builders/");
load("decoder.php", "wicom/translator/");


use Wicom\Translator\Strategies\UMLcrowd;
use Wicom\Translator\Builders\OWLlinkBuilder;
use Wicom\Translator\Builders\UMLJSONBuilder;
/**
   # Warning!
   Don't use assertEqualXMLStructure()! It won't check for attributes values!

   It will only check for the amount of attributes.

   In order to keep interoperability with Protégé, roles on left sides of owl expressions
   must have fillers (top or class). Look at next role domain expression:

   <owl:SubClassOf>
     <owl:ObjectSomeValuesFrom>
         <owl:ObjectProperty IRI="r1"/>
         <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
     </owl:ObjectSomeValuesFrom>
     <owl:Class IRI="PhoneCall"/>
   </owl:SubClassOf>

   However, right sides accept roles without fillers. Look at following max cardinality expression:

   <owl:SubClassOf>
          <owl:Class IRI="PhoneCall"/>
         <owl:ObjectMaxCardinality cardinality="1">
              <owl:ObjectProperty IRI="r1"/>
         </owl:ObjectMaxCardinality>
   </owl:SubClassOf>

 */


class UMLcrowdBackTest extends PHPUnit\Framework\TestCase
{

  public function testcompareSubsJSONtoJSON(){
  $json_o = <<<EOT
{"namespaces":{"ontologyIRI":[{"prefix":"crowd","value":"http://crowd.fi.uncoma.edu.ar#"}],
               "defaultIRIs":[{"prefix":"rdf","value":"http://www.w3.org/1999/02/22-rdf-syntax-ns#"},
                              {"prefix":"rdfs","value":"http://www.w3.org/2000/01/rdf-schema#"},
                              {"prefix":"xsd","value":"http://www.w3.org/2001/XMLSchema#"},
                              {"prefix":"owl","value":"http://www.w3.org/2002/07/owl#"}],
               "IRIs":[]},
               "classes":[{"name":"http://crowd.fi.uncoma.edu.ar#Class1","attrs":[],"methods":[],"position":{"x":481,"y":137}},
                          {"name":"http://crowd.fi.uncoma.edu.ar#Class2","attrs":[],"methods":[],"position":{"x":499,"y":522}},
                          {"name":"http://crowd.fi.uncoma.edu.ar#Class3","attrs":[],"methods":[],"position":{"x":481,"y":137}},
                          {"name":"http://crowd.fi.uncoma.edu.ar#Class4","attrs":[],"methods":[],"position":{"x":481,"y":137}}],
               "links":[{"name":"http://crowd.fi.uncoma.edu.ar#s1","parent":"http://crowd.fi.uncoma.edu.ar#Class1",
                         "classes":["http://crowd.fi.uncoma.edu.ar#Class2"],
                         "multiplicity":null,
                         "roles":null,
                         "type":"generalization",
                         "constraint":[],
                         "position":{"x":490,"y":329.5}}],
               "owllink":[""]}
EOT;

  $json_new = <<<EOT
{"namespaces":{"ontologyIRI":[],
                 "defaultIRIs":[],
                 "IRIs":[]},
                 "classes":[{"name":"http://crowd.fi.uncoma.edu.ar#Class1","attrs":[],"methods":[]},
                            {"name":"http://crowd.fi.uncoma.edu.ar#Class2","attrs":[],"methods":[]},
                            {"name":"http://crowd.fi.uncoma.edu.ar#Class3","attrs":[],"methods":[]},
                            {"name":"http://crowd.fi.uncoma.edu.ar#Class4","attrs":[],"methods":[]}],
                 "links":[{"name":"http://crowd.fi.uncoma.edu.ar#s1","parent":"http://crowd.fi.uncoma.edu.ar#Class1",
                           "classes":["http://crowd.fi.uncoma.edu.ar#Class2"],
                           "multiplicity":null,
                           "roles":null,
                           "type":"generalization",
                           "constraint":[]},
                           {"name":"http://crowd.fi.uncoma.edu.ar#s2","parent":"http://crowd.fi.uncoma.edu.ar#Class3",
                                     "classes":["http://crowd.fi.uncoma.edu.ar#Class4"],
                                     "multiplicity":null,
                                     "roles":null,
                                     "type":"generalization",
                                     "constraint":[]}],
                 "owllink":[""]}
EOT;

  $answer = <<<EOT
{"subsumptions" : ["http://crowd.fi.uncoma.edu.ar#Class1", "http://crowd.fi.uncoma.edu.ar#Class2"]}
EOT;

      $strategy = new UMLcrowd();
      $builder = new UMLJSONBuilder();

//      var_dump($answer);
//      var_dump(json_decode($answer,true));

      $strategy->compare_subsumptions($answer, $json_o, $json_new, $builder);

/*      $actual = $builder->get_product();
      $actual = $actual->to_string();
      $this->assertXmlStringEqualsXmlString($expected, $actual,true); */
}

}
