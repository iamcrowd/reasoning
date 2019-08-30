<?php
/*

   Copyright 2017 GILIA

   Author: GILIA

   ontoextractortest.php

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
load("config.php", "config/");
load("ontoextractor.php", "wicom/translator/strategies/sparqldl/");

use Wicom\Translator\Strategies\Sparqldl\OntoExtractor;

class OntoExtractorWithUnsatTest extends PHPUnit\Framework\TestCase{


    # Extracting owl class axioms from an owl file

   public function testDomainAndRangeUnsatExtractor(){
      $input = <<<XML
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

      $expected = '[
      {"Class":
              ["http://localhost/kb1/Class1",
              "http://localhost/kb1/Class3",
              "http://www.w3.org/2002/07/owl#Thing",
              "http://www.w3.org/2002/07/owl#Nothing"]},
        [],[],[],[],[],[],
        {"StrictSubClassOf":
              [
                {"subclass":
                  ["http://www.w3.org/2002/07/owl#Nothing",
                  "http://localhost/kb1/Class3"]},
                  {"subclass":
                    ["http://localhost/kb1/Class1",
                    "http://www.w3.org/2002/07/owl#Thing"]},
                {"subclass":
                  ["http://localhost/kb1/Class3",
                  "http://localhost/kb1/Class1"]}]},
        [],[],
        {"DisjointWithClass":
              [{"disjointclasses":
                ["http://localhost/kb1/Class3","http://www.w3.org/2002/07/owl#Nothing"]},
                {"disjointclasses":
                ["http://www.w3.org/2002/07/owl#Nothing","http://www.w3.org/2002/07/owl#Thing"]},
              {"disjointclasses":
                ["http://www.w3.org/2002/07/owl#Nothing","http://localhost/kb1/Class1"]}]},
        [],[]
      ]';

      $sparqldl = new OntoExtractor();
      $sparqldl->extractor($input);
      $graphaxioms = $sparqldl->getIntermediateSparqldl();
      $actual = $graphaxioms->to_json();

      var_dump($sparqldl->returnDomain());

      var_dump($sparqldl->returnRange());

//      $this->assertJsonStringEqualsJsonString($actual, json_encode(json_decode($expected)), true);
  }

}
