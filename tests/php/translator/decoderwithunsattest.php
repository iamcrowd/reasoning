<?php
/*

   Copyright 2018 GILIA

   Author: GILIA

   decoderwithunsattest.php

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
load("crowd_uml.php", "wicom/translator/strategies/");
load("berardistrat.php", "wicom/translator/strategies/");
load("owllinkbuilder.php", "wicom/translator/builders/");
load("umljsonbuilder.php", "wicom/translator/builders/");
load("decoder.php", "wicom/translator/");
load("translator.php", "wicom/translator/");

use Wicom\Translator\Strategies\Berardi;
use Wicom\Translator\Strategies\UMLcrowd;
use Wicom\Translator\Builders\OWLlinkBuilder;
use Wicom\Translator\Builders\UMLJSONBuilder;
use Wicom\Translator\Decoder;
use Wicom\Translator\Translator;

class DecoderTest extends PHPUnit\Framework\TestCase
{


  public function testToJsonUnsatClassUML(){

      $json =<<<EOT
      {"namespaces":{"ontologyIRI":[{"prefix":"crowd","value":"http://crowd.fi.uncoma.edu.ar#"}],
                     "defaultIRIs":[{"prefix":"rdf","value":"http://www.w3.org/1999/02/22-rdf-syntax-ns#"},
                                    {"prefix":"rdfs","value":"http://www.w3.org/2000/01/rdf-schema#"},
                                    {"prefix":"xsd","value":"http://www.w3.org/2001/XMLSchema#"},
                                    {"prefix":"owl","value":"http://www.w3.org/2002/07/owl#"}],
                     "IRIs":[]},
                     "classes":[{"name":"http://crowd.fi.uncoma.edu.ar#Class5",
                                  "attrs":[],
                                  "methods":[]}],
                     "links":[]}
EOT;
      $expected = json_encode(json_decode($json));

      $owl2 = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
            <Ontology xmlns="http://www.w3.org/2002/07/owl#"
            xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
            xmlns:xml="http://www.w3.org/XML/1998/namespace"
            xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
            xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
            xml:base="http://crowd.fi.uncoma.edu.ar#"
            ontologyIRI="http://crowd.fi.uncoma.edu.ar#">
            <Prefix name="rdf" IRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
            <Prefix name="rdfs" IRI="http://www.w3.org/2000/01/rdf-schema#"/>
            <Prefix name="xsd" IRI="http://www.w3.org/2001/XMLSchema#"/>
            <Prefix name="owl" IRI="http://www.w3.org/2002/07/owl#"/>
            <Prefix name="crowd" IRI="http://crowd.fi.uncoma.edu.ar#"/>
            <Declaration>
              <Class IRI="http://crowd.fi.uncoma.edu.ar#Class1"/>
            </Declaration>
            <Declaration>
              <Class IRI="http://crowd.fi.uncoma.edu.ar#Class2"/>
              </Declaration>
              <Declaration>
                <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#casa"/>
                </Declaration>
                <Declaration>
                  <Class IRI="http://crowd.fi.uncoma.edu.ar#Class3"/>
                  </Declaration>
                    <Declaration>
                      <Class IRI="http://crowd.fi.uncoma.edu.ar#Class6"/>
                      </Declaration>
                      <Declaration>
                        <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#r6"/>
                        </Declaration>
                        <DataPropertyDomain>
                          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#casa"/>
                          <Class IRI="http://crowd.fi.uncoma.edu.ar#Class2"/></DataPropertyDomain>
                          <DataPropertyRange>
                            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#casa"/>
                            <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
                            </DataPropertyRange><SubClassOf><Class IRI="http://crowd.fi.uncoma.edu.ar#Class2"/>
                              <DataMaxCardinality cardinality="1"><DataProperty IRI="http://crowd.fi.uncoma.edu.ar#casa"/>
                              </DataMaxCardinality></SubClassOf>
                              <SubClassOf><ObjectSomeValuesFrom>
                                <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#r6"/>
                                <Class abbreviatedIRI="owl:Thing"/></ObjectSomeValuesFrom>
                                <Class IRI="http://crowd.fi.uncoma.edu.ar#Class6"/>
                                </SubClassOf>
                                <SubClassOf><ObjectSomeValuesFrom>  /*
                                  <ObjectInverseOf><ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#r6"/></ObjectInverseOf>
                                  <Class abbreviatedIRI="owl:Thing"/></ObjectSomeValuesFrom>
                                  <Class IRI="http://crowd.fi.uncoma.edu.ar#Class2"/>
                                  </SubClassOf>
                                  <SubClassOf><Class IRI="http://crowd.fi.uncoma.edu.ar#Class2"/>
                                    <Class IRI="http://crowd.fi.uncoma.edu.ar#Class1"/></SubClassOf>
                                    <SubClassOf><Class IRI="http://crowd.fi.uncoma.edu.ar#Class3"/>
                                      <Class IRI="http://crowd.fi.uncoma.edu.ar#Class1"/></SubClassOf>
                                      <SubClassOf><ObjectUnionOf><Class IRI="http://crowd.fi.uncoma.edu.ar#Class2"/>
                                        <Class IRI="http://crowd.fi.uncoma.edu.ar#Class3"/>
                                        </ObjectUnionOf><Class IRI="http://crowd.fi.uncoma.edu.ar#Class1"/></SubClassOf>
                                        <SubClassOf><Class IRI="http://crowd.fi.uncoma.edu.ar#Class1"/>
                                          <ObjectUnionOf><Class IRI="http://crowd.fi.uncoma.edu.ar#Class2"/>
                                            <Class IRI="http://crowd.fi.uncoma.edu.ar#Class3"/></ObjectUnionOf>
                                            </SubClassOf><DisjointClasses><Class IRI="http://crowd.fi.uncoma.edu.ar#Class2"/>
                                              <Class IRI="http://crowd.fi.uncoma.edu.ar#Class3"/></DisjointClasses>
                                                <SubClassOf><Class IRI="http://crowd.fi.uncoma.edu.ar#Class2"/>
                                                  <Class IRI="http://crowd.fi.uncoma.edu.ar#Class3"/></SubClassOf>
      </Ontology>
XML;

      $strategy = new UMLcrowd();
      $jsonbuilder = new UMLJSONBuilder();
      $decoder = new Decoder($strategy, $jsonbuilder);

      $ontologyIRI = ["prefix" => "crowd", "value" => "http://crowd.fi.uncoma.edu.ar#"];
      $prefix = [["prefix" => "rdf", "value" => "http://www.w3.org/1999/02/22-rdf-syntax-ns#"],
                 ["prefix" => "rdfs","value" => "http://www.w3.org/2000/01/rdf-schema#"],
                 ["prefix" => "xsd","value" => "http://www.w3.org/2001/XMLSchema#"],
                 ["prefix" => "owl","value" => "http://www.w3.org/2002/07/owl#"]];

      $actual = $decoder->to_json($owl2, $ontologyIRI, $prefix);

      var_dump($actual);

  //    $this->assertJsonStringEqualsJsonString($actual, $expected, true);
  }


}

?>
