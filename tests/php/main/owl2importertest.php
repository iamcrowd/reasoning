<?php
/*

   Copyright 2018

   Author: GILIA

   owl2importertest.php

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
load("owl2Importer.php", "common/");

load("owllinkbuilder.php", "wicom/translator/builders/");
load("owlbuilder.php", "wicom/translator/builders/");

use Wicom\Translator\Builders\OWLlinkBuilder;
use Wicom\Translator\Builders\OWLBuilder;

use Wicom\Wicom;
use Wicom\OWL2Importer;

class OWL2ImporterTest extends PHPUnit\Framework\TestCase
{
/*
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
<Declaration><Class IRI="http://crowd.fi.uncoma.edu.ar#Class1"/>
</Declaration>
</Ontology>



public function testowl2owllinkGen(){
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
    <Declaration><Class IRI="http://crowd.fi.uncoma.edu.ar#Class1"/>
    </Declaration>
    </Ontology>

XML;

    $expected = '{"namespaces":{"ontologyIRI":[{"prefix":"", "value":"http://crowd.fi.uncoma.edu.ar/kb1"}],
          "defaultIRIs":[{"prefix":"crowd", "value":"http://crowd.fi.uncoma.edu.ar/kb1#"},
                         {"prefix":"owl", "value":"http://www.w3.org/2002/07/owl#"}],
          "IRIs":[]},
      "classes":[
    {"name":"http://crowd.fi.uncoma.edu.ar/kb1#Class1","attrs":[],"methods":[]},
    {"name":"http://crowd.fi.uncoma.edu.ar/kb1#Class3","attrs":[],"methods":[]}],
    "links":[{"name":"http://crowd.fi.uncoma.edu.ar/kb1#s1",
      "classes":["http://crowd.fi.uncoma.edu.ar/kb1#Class3"],
      "multiplicity":null,"roles":[null,null],
      "type":"generalization","parent":"http://crowd.fi.uncoma.edu.ar/kb1#Class1","constraint":[]}]}';

    $importer = new OWL2Importer();

    $actual = $importer->owl2importer($input);

//	var_dump($actual);
//    $this->assertJsonStringEqualsJsonString($actual, json_encode(json_decode($expected)), true);
}

*/
  public function testowl2owllinkGen(){
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
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Class3"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#A"/>
        </Declaration>
        <SubClassOf>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#A"/>
            <Class abbreviatedIRI="owl:Thing"/>
          </ObjectSomeValuesFrom>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Class1"/>
        </SubClassOf>
        <SubClassOf>
          <ObjectSomeValuesFrom>
            <ObjectInverseOf>
              <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#A"/>
            </ObjectInverseOf>
            <Class abbreviatedIRI="owl:Thing"/>
          </ObjectSomeValuesFrom>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Class3"/>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Class1"/>
          <ObjectMinCardinality cardinality="1">
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#A"/>
          </ObjectMinCardinality>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Class1"/>
          <ObjectMaxCardinality cardinality="1">
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#A"/>
          </ObjectMaxCardinality>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Class3"/>
          <ObjectMinCardinality cardinality="1">
            <ObjectInverseOf>
              <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#A"/>
              </ObjectInverseOf>
          </ObjectMinCardinality>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Class3"/>
          <ObjectMaxCardinality cardinality="1">
            <ObjectInverseOf>
              <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#A"/>
            </ObjectInverseOf>
          </ObjectMaxCardinality>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Class2"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Class1"/>
        </SubClassOf>
</Ontology>
XML;

      $expected = '{"namespaces":{"ontologyIRI":[{"prefix":"", "value":"http://crowd.fi.uncoma.edu.ar/kb1"}],
            "defaultIRIs":[{"prefix":"crowd", "value":"http://crowd.fi.uncoma.edu.ar/kb1#"},
                           {"prefix":"owl", "value":"http://www.w3.org/2002/07/owl#"}],
            "IRIs":[]},
        "classes":[
      {"name":"http://crowd.fi.uncoma.edu.ar/kb1#Class1","attrs":[],"methods":[]},
      {"name":"http://crowd.fi.uncoma.edu.ar/kb1#Class3","attrs":[],"methods":[]}],
      "links":[{"name":"http://crowd.fi.uncoma.edu.ar/kb1#s1",
        "classes":["http://crowd.fi.uncoma.edu.ar/kb1#Class3"],
        "multiplicity":null,"roles":[null,null],
        "type":"generalization","parent":"http://crowd.fi.uncoma.edu.ar/kb1#Class1","constraint":[]}]}';

      $importer = new OWL2Importer();

      $actual = $importer->owl2importer($input);

   //   $this->assertJsonStringEqualsJsonString($actual, json_encode(json_decode($expected)), true);
  }

/*
    public function testowl2owllink11rel(){
        $input = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
      <Ontology xmlns="http://www.w3.org/2002/07/owl#"
      xml:base="http://crowd.fi.uncoma.edu.ar/kb1"
      xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
      xmlns:xml="http://www.w3.org/XML/1998/namespace"
      xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
      xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
      ontologyIRI="http://crowd.fi.uncoma.edu.ar/kb1">
      <Prefix name="crowd" IRI="http://crowd.fi.uncoma.edu.ar/kb1#"/>
      <SubClassOf>
        <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1#Class3"/>
        <Class IRI="http://www.w3.org/2002/07/owl#Thing"/>
      </SubClassOf>
      <SubClassOf>
        <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1#Class2"/>
        <Class IRI="http://www.w3.org/2002/07/owl#Thing"/>
      </SubClassOf>

      <SubClassOf>
        <ObjectSomeValuesFrom>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1#R"/>
          <Class IRI="http://www.w3.org/2002/07/owl#Thing"/>
        </ObjectSomeValuesFrom>
        <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1#Class3"/>
      </SubClassOf>

      <SubClassOf>
        <ObjectSomeValuesFrom>
          <ObjectInverseOf>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1#R"/>
          </ObjectInverseOf>
          <Class IRI="http://www.w3.org/2002/07/owl#Thing"/>
        </ObjectSomeValuesFrom>
        <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1#Class2"/>
      </SubClassOf>

      <SubClassOf>
        <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1#Class3"/>
        <ObjectMaxCardinality cardinality="1">
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1#R"/>
        </ObjectMaxCardinality>
      </SubClassOf>

      <SubClassOf>
        <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1#Class3"/>
        <ObjectMinCardinality cardinality="1">
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1#R"/>
        </ObjectMinCardinality>
      </SubClassOf>
   </Ontology>
XML;

        $expected = '{"namespaces":{"ontologyIRI":[{"prefix":"", "value":"http://crowd.fi.uncoma.edu.ar/kb1"}],
              "defaultIRIs":[{"prefix":"crowd", "value":"http://crowd.fi.uncoma.edu.ar/kb1#"},
                             {"prefix":"owl", "value":"http://www.w3.org/2002/07/owl#"}],
              "IRIs":[]},
          "classes":[
        {"name":"http://crowd.fi.uncoma.edu.ar/kb1#Class2","attrs":[],"methods":[]},
        {"name":"http://crowd.fi.uncoma.edu.ar/kb1#Class3","attrs":[],"methods":[]}],
        "links":[{"name":"http://crowd.fi.uncoma.edu.ar/kb1#R",
          "classes":["http://crowd.fi.uncoma.edu.ar/kb1#Class3","http://crowd.fi.uncoma.edu.ar/kb1#Class2"],
          "multiplicity":[null,"1..1"],
          "roles":["http://crowd.fi.uncoma.edu.ar/kb1#class3","http://crowd.fi.uncoma.edu.ar/kb1#class2"],
          "type":"association"}]}';

        $importer = new OWL2Importer();

        $actual = $importer->owl2importer($input);

        $this->assertJsonStringEqualsJsonString($actual, json_encode(json_decode($expected)), true);
    }


    public function testowl2owllink01rel(){

    $input = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
  <Ontology xmlns="http://www.w3.org/2002/07/owl#"
  xml:base="http://crowd.fi.uncoma.edu.ar/kb1/"
  xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
  xmlns:xml="http://www.w3.org/XML/1998/namespace"
  xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
  xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
  ontologyIRI="http://crowd.fi.uncoma.edu.ar/kb1/">
  <Prefix name="" IRI="http://crowd.fi.uncoma.edu.ar/kb1/"/>
  <SubClassOf>
    <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1"/>
    <Class abbreviatedIRI="owl:Thing"/>
  </SubClassOf>
  <SubClassOf>
    <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
    <Class abbreviatedIRI="owl:Thing"/>
  </SubClassOf>
  <SubClassOf>
    <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class3"/>
    <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1"/>
  </SubClassOf>
  <SubClassOf>
    <ObjectSomeValuesFrom>
      <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/R"/>
      <Class abbreviatedIRI="owl:Thing"/>
    </ObjectSomeValuesFrom>
    <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class3"/>
  </SubClassOf>
  <SubClassOf>
    <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class3"/>
    <ObjectMaxCardinality cardinality="1">
      <ObjectProperty IRI="R"/>
    </ObjectMaxCardinality>
  </SubClassOf>
  <SubClassOf>
    <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
    <ObjectMaxCardinality cardinality="1">
      <ObjectInverseOf>
        <ObjectProperty IRI="R"/>
      </ObjectInverseOf>
    </ObjectMaxCardinality>
  </SubClassOf>
  <SubClassOf>
    <ObjectSomeValuesFrom>
      <ObjectInverseOf>
        <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/R"/>
      </ObjectInverseOf>
      <Class abbreviatedIRI="owl:Thing"/>
    </ObjectSomeValuesFrom>
    <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
  </SubClassOf>
</Ontology>
XML;

      $expected = '{"metadata":[],
        "graphops":[],
        "header":[],
        "prefix":[{"prefix":"","iri":"http://crowd.fi.uncoma.edu.ar/kb1/"}],
        "ontologyIRI":[{"prefix":"","iri":"http://crowd.fi.uncoma.edu.ar/kb1/"}],
        "classes":[{"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class1","attrs":[],"methods":[]},
      {"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class2","attrs":[],"methods":[]},
      {"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class3","attrs":[],"methods":[]}],
      "links":[{"name":"http://crowd.fi.uncoma.edu.ar/kb1/g1",
        "classes":["http://crowd.fi.uncoma.edu.ar/kb1/Class3"],
        "multiplicity":null,"roles":[null,null],
        "type":"generalization",
        "parent":"http://crowd.fi.uncoma.edu.ar/kb1/Class1",
        "constraint":[]},
      {"name":"http://crowd.fi.uncoma.edu.ar/kb1/R",
        "classes":["http://crowd.fi.uncoma.edu.ar/kb1/Class3",
        "http://crowd.fi.uncoma.edu.ar/kb1/Class2"],
        "multiplicity":["0..1","0..1"],"roles":["",""],
        "type":"association"}]}';

      $importer = new OWL2Importer();

      $actual = $importer->owl2importer($input);

      $this->assertJsonStringEqualsJsonString($actual, json_encode(json_decode($expected)), true);
    }

    public function testowl2owllink1Nrel(){
        $input = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
      <Ontology xmlns="http://www.w3.org/2002/07/owl#"
      xml:base="http://crowd.fi.uncoma.edu.ar/kb1/"
      xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
      xmlns:xml="http://www.w3.org/XML/1998/namespace"
      xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
      xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
      ontologyIRI="http://crowd.fi.uncoma.edu.ar/kb1/">
      <Prefix name="" IRI="http://crowd.fi.uncoma.edu.ar/kb1/"/>
      <SubClassOf>
        <Class IRI="Class1"/>
        <Class abbreviatedIRI="owl:Thing"/>
      </SubClassOf>
      <SubClassOf>
        <Class IRI="Class2"/>
        <Class abbreviatedIRI="owl:Thing"/>
      </SubClassOf>
      <SubClassOf>
        <Class IRI="Class3"/>
        <Class IRI="Class1"/>
      </SubClassOf>
      <SubClassOf>
        <ObjectSomeValuesFrom>
          <ObjectProperty IRI="R"/>
          <Class abbreviatedIRI="owl:Thing"/>
        </ObjectSomeValuesFrom>
        <Class IRI="Class3"/>
      </SubClassOf>

      <SubClassOf>
        <Class IRI="Class3"/>
        <ObjectMinCardinality cardinality="1">
          <ObjectProperty IRI="R"/>
        </ObjectMinCardinality>
      </SubClassOf>
      <SubClassOf>
        <Class IRI="Class2"/>
        <ObjectMinCardinality cardinality="1">
          <ObjectInverseOf>
            <ObjectProperty IRI="R"/>
          </ObjectInverseOf>
        </ObjectMinCardinality>
      </SubClassOf>

      <SubClassOf>
        <ObjectSomeValuesFrom>
          <ObjectInverseOf>
            <ObjectProperty IRI="R"/>
          </ObjectInverseOf>
          <Class abbreviatedIRI="owl:Thing"/>
        </ObjectSomeValuesFrom>
        <Class IRI="Class2"/>
      </SubClassOf>
   </Ontology>
XML;

        $expected = '{"metadata":[],
          "graphops":[],
          "header":[],
          "prefix":[{"prefix":"","iri":"http://crowd.fi.uncoma.edu.ar/kb1/"}],
          "ontologyIRI":[{"prefix":"","iri":"http://crowd.fi.uncoma.edu.ar/kb1/"}],
          "classes":[{"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class1","attrs":[],"methods":[]},
                    {"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class2","attrs":[],"methods":[]},
                    {"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class3","attrs":[],"methods":[]}],
          "links":[{"name":"http://crowd.fi.uncoma.edu.ar/kb1/g1","classes":["http://crowd.fi.uncoma.edu.ar/kb1/Class3"],
          "multiplicity":null,"roles":[null,null],"type":"generalization","parent":"http://crowd.fi.uncoma.edu.ar/kb1/Class1","constraint":[]},
        {"name":"http://crowd.fi.uncoma.edu.ar/kb1/R","classes":["http://crowd.fi.uncoma.edu.ar/kb1/Class3","http://crowd.fi.uncoma.edu.ar/kb1/Class2"],"multiplicity":["1..*","1..*"],"roles":["",""],"type":"association"}]}';

        $importer = new OWL2Importer();

        $actual = $importer->owl2importer($input);

        $this->assertJsonStringEqualsJsonString($actual, json_encode(json_decode($expected)), true);
    }



    public function testowl2owllink0Nrel(){
        $input = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
      <Ontology xmlns="http://www.w3.org/2002/07/owl#"
      xml:base="http://crowd.fi.uncoma.edu.ar/kb1"
      xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
      xmlns:xml="http://www.w3.org/XML/1998/namespace"
      xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
      xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
      ontologyIRI="http://crowd.fi.uncoma.edu.ar/kb1">
      <Prefix name="crowd" IRI="http://crowd.fi.uncoma.edu.ar/kb1#"/>
      <SubClassOf>
        <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1#Class1"/>
        <Class IRI="http://www.w3.org/2002/07/owl#Thing"/>
      </SubClassOf>
      <SubClassOf>
        <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1#Class2"/>
        <Class IRI="http://www.w3.org/2002/07/owl#Thing"/>
      </SubClassOf>
      <SubClassOf>
        <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1#Class3"/>
        <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1#Class1"/>
      </SubClassOf>
      <SubClassOf>
        <ObjectSomeValuesFrom>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1#R"/>
          <Class IRI="http://www.w3.org/2002/07/owl#Thing"/>
        </ObjectSomeValuesFrom>
        <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1#Class3"/>
      </SubClassOf>

      <SubClassOf>
        <ObjectSomeValuesFrom>
          <ObjectInverseOf>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1#R"/>
          </ObjectInverseOf>
          <Class abbreviatedIRI="http://www.w3.org/2002/07/owl#Thing"/>
        </ObjectSomeValuesFrom>
        <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1#Class2"/>
      </SubClassOf>
   </Ontology>
XML;

        $expected = '{{"namespaces":{"ontologyIRI":[{"prefix":"", "value":"http://crowd.fi.uncoma.edu.ar/kb1"}],
              "defaultIRIs":[{"prefix":"crowd", "value":"http://crowd.fi.uncoma.edu.ar/kb1#"},
                             {"prefix":"owl", "value":"http://www.w3.org/2002/07/owl#"}],
              "IRIs":[]},
          "classes":[{"name":"http://crowd.fi.uncoma.edu.ar/kb1#Class1","attrs":[],"methods":[]},
        {"name":"http://crowd.fi.uncoma.edu.ar/kb1#Class2","attrs":[],"methods":[]},
        {"name":"http://crowd.fi.uncoma.edu.ar/kb1#Class3","attrs":[],"methods":[]}],
        "links":[{"name":"http://crowd.fi.uncoma.edu.ar/kb1#s1","classes":["http://crowd.fi.uncoma.edu.ar/kb1#Class3"],
          "multiplicity":null,"roles":[null,null],"type":"generalization","parent":"http://crowd.fi.uncoma.edu.ar/kb1#Class1","constraint":[]},
        {"name":"http://crowd.fi.uncoma.edu.ar/kb1#R","classes":["http://crowd.fi.uncoma.edu.ar/kb1#Class3","http://crowd.fi.uncoma.edu.ar/kb1#Class2"],
          "multiplicity":[null,null],"roles":["http://crowd.fi.uncoma.edu.ar/kb1#class3","http://crowd.fi.uncoma.edu.ar/kb1#class2"],"type":"association"}]}';

        $importer = new OWL2Importer();

        $actual = $importer->owl2importer($input);

        $this->assertJsonStringEqualsJsonString($actual, json_encode(json_decode($expected)), true);
    }




    public function testowl2owllinkBioOnto(){

        $input = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
        <Ontology xmlns="http://www.w3.org/2002/07/owl#"
             xml:base="http://www.cenpat-conicet.gob.ar/bioOnto/"
             xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
             xmlns:xml="http://www.w3.org/XML/1998/namespace"
             xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
             xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
             ontologyIRI="http://www.cenpat-conicet.gob.ar/bioOnto/">
            <Prefix name="" IRI="http://www.cenpat-conicet.gob.ar/bioOnto/"/>
            <Prefix name="wd" IRI="http://www.wikidata.org/entity/"/>
            <Prefix name="dbr" IRI="http://dbpedia.org/resource/"/>
            <Prefix name="dwc" IRI="http://rs.tdwg.org/dwc/terms/"/>
            <Prefix name="owl" IRI="http://www.w3.org/2002/07/owl#"/>
            <Prefix name="rdf" IRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
            <Prefix name="wdt" IRI="http://wikidata.org/prop/direct/"/>
            <Prefix name="xml" IRI="http://www.w3.org/XML/1998/namespace"/>
            <Prefix name="xsd" IRI="http://www.w3.org/2001/XMLSchema#"/>
            <Prefix name="envo" IRI="http://purl.obolibrary.org/obo/"/>
            <Prefix name="foaf" IRI="http://xmlns.com/foaf/0.1/"/>
            <Prefix name="rdfs" IRI="http://www.w3.org/2000/01/rdf-schema#"/>
            <Prefix name="time" IRI="http://www.w3.org/2006/time#"/>
            <Prefix name="void" IRI="http://rdfs.org/ns/void#"/>
            <Prefix name="dcterms" IRI="http://purl.org/dc/terms/"/>
            <Prefix name="geo-ont" IRI="http://www.geonames.org/ontology#"/>
            <Prefix name="geo-pos" IRI="http://www.w3.org/2003/01/geo/wgs84_pos#"/>
            <EquivalentClasses>
                <Class IRI="Environment"/>
                <ObjectUnionOf>
                    <Class abbreviatedIRI="envo:ENVO_00000428"/>
                    <Class abbreviatedIRI="envo:ENVO_00002297"/>
                    <Class abbreviatedIRI="envo:ENVO_00010483"/>
                </ObjectUnionOf>
            </EquivalentClasses>
            <SubClassOf>
                <Class abbreviatedIRI="envo:ENVO_00000428"/>
                <Class IRI="Environment"/>
            </SubClassOf>
            <SubClassOf>
                <Class abbreviatedIRI="envo:ENVO_00002297"/>
                <Class IRI="Environment"/>
            </SubClassOf>
            <SubClassOf>
                <Class abbreviatedIRI="envo:ENVO_00010483"/>
                <Class IRI="Environment"/>
            </SubClassOf>
            <SubClassOf>
                <Class abbreviatedIRI="dcterms:Location"/>
                <Class IRI="Region"/>
            </SubClassOf>
            <SubClassOf>
                <Class abbreviatedIRI="dwc:Occurrence"/>
                <ObjectExactCardinality cardinality="1">
                    <ObjectProperty IRI="associated"/>
                    <Class abbreviatedIRI="dwc:Organism"/>
                </ObjectExactCardinality>
            </SubClassOf>
            <SubClassOf>
                <Class abbreviatedIRI="dwc:Occurrence"/>
                <ObjectExactCardinality cardinality="1">
                    <ObjectProperty IRI="has_event"/>
                    <Class IRI="BioEvent"/>
                </ObjectExactCardinality>
            </SubClassOf>
            <SubClassOf>
                <Class abbreviatedIRI="dwc:Occurrence"/>
                <ObjectExactCardinality cardinality="1">
                    <ObjectProperty IRI="has_location"/>
                    <Class abbreviatedIRI="dcterms:Location"/>
                </ObjectExactCardinality>
            </SubClassOf>
            <SubClassOf>
                <Class abbreviatedIRI="dwc:Occurrence"/>
                <ObjectExactCardinality cardinality="1">
                    <ObjectProperty IRI="memberOf"/>
                    <Class abbreviatedIRI="void:Dataset"/>
                </ObjectExactCardinality>
            </SubClassOf>
            <SubClassOf>
                <Class abbreviatedIRI="dwc:Occurrence"/>
                <ObjectExactCardinality cardinality="1">
                    <ObjectProperty IRI="recorded_by"/>
                    <Class abbreviatedIRI="foaf:Agent"/>
                </ObjectExactCardinality>
            </SubClassOf>
            <SubClassOf>
                <Class abbreviatedIRI="dwc:Organism"/>
                <ObjectExactCardinality cardinality="1">
                    <ObjectProperty IRI="belongsTo"/>
                    <Class abbreviatedIRI="dwc:Taxon"/>
                </ObjectExactCardinality>
            </SubClassOf>
            <SubClassOf>
                <Class abbreviatedIRI="dwc:Taxon"/>
                <ObjectMinCardinality cardinality="1">
                    <ObjectInverseOf>
                        <ObjectProperty IRI="belongsTo"/>
                    </ObjectInverseOf>
                    <Class abbreviatedIRI="dwc:Organism"/>
                </ObjectMinCardinality>
            </SubClassOf>
            <SubClassOf>
                <Class IRI="BioEvent"/>
                <Class abbreviatedIRI="dwc:Event"/>
            </SubClassOf>
            <SubClassOf>
                <Class IRI="BioEvent"/>
                <ObjectExactCardinality cardinality="1">
                    <ObjectInverseOf>
                        <ObjectProperty IRI="has_event"/>
                    </ObjectInverseOf>
                    <Class abbreviatedIRI="dwc:Occurrence"/>
                </ObjectExactCardinality>
            </SubClassOf>
            <SubClassOf>
                <Class IRI="Environment"/>
                <ObjectExactCardinality cardinality="1">
                    <ObjectProperty IRI="caraterises"/>
                    <Class IRI="Region"/>
                </ObjectExactCardinality>
            </SubClassOf>
            <SubClassOf>
                <Class IRI="Region"/>
                <ObjectExactCardinality cardinality="1">
                    <ObjectInverseOf>
                        <ObjectProperty IRI="caraterises"/>
                    </ObjectInverseOf>
                    <Class IRI="Environment"/>
                </ObjectExactCardinality>
            </SubClassOf>
            <DisjointClasses>
                <Class abbreviatedIRI="envo:ENVO_00000428"/>
                <Class abbreviatedIRI="envo:ENVO_00002297"/>
            </DisjointClasses>
            <DisjointClasses>
                <Class abbreviatedIRI="envo:ENVO_00000428"/>
                <Class abbreviatedIRI="envo:ENVO_00010483"/>
            </DisjointClasses>
            <DisjointClasses>
                <Class abbreviatedIRI="envo:ENVO_00002297"/>
                <Class abbreviatedIRI="envo:ENVO_00010483"/>
            </DisjointClasses>
            <ObjectPropertyDomain>
                <ObjectProperty IRI="associated"/>
                <Class abbreviatedIRI="dwc:Occurrence"/>
            </ObjectPropertyDomain>
            <ObjectPropertyDomain>
                <ObjectProperty IRI="belongsTo"/>
                <Class abbreviatedIRI="dwc:Organism"/>
            </ObjectPropertyDomain>
            <ObjectPropertyDomain>
                <ObjectProperty IRI="caraterises"/>
                <Class IRI="Environment"/>
            </ObjectPropertyDomain>
            <ObjectPropertyDomain>
                <ObjectProperty IRI="has_event"/>
                <Class abbreviatedIRI="dwc:Occurrence"/>
            </ObjectPropertyDomain>
            <ObjectPropertyDomain>
                <ObjectProperty IRI="has_location"/>
                <Class abbreviatedIRI="dwc:Occurrence"/>
            </ObjectPropertyDomain>
            <ObjectPropertyDomain>
                <ObjectProperty IRI="memberOf"/>
                <Class abbreviatedIRI="dwc:Occurrence"/>
            </ObjectPropertyDomain>
            <ObjectPropertyDomain>
                <ObjectProperty IRI="recorded_by"/>
                <Class abbreviatedIRI="dwc:Occurrence"/>
            </ObjectPropertyDomain>
            <ObjectPropertyRange>
                <ObjectProperty IRI="associated"/>
                <Class abbreviatedIRI="dwc:Organism"/>
            </ObjectPropertyRange>
            <ObjectPropertyRange>
                <ObjectProperty IRI="belongsTo"/>
                <Class abbreviatedIRI="dwc:Taxon"/>
            </ObjectPropertyRange>
            <ObjectPropertyRange>
                <ObjectProperty IRI="caraterises"/>
                <Class IRI="Region"/>
            </ObjectPropertyRange>
            <ObjectPropertyRange>
                <ObjectProperty IRI="has_event"/>
                <Class IRI="BioEvent"/>
            </ObjectPropertyRange>
            <ObjectPropertyRange>
                <ObjectProperty IRI="has_location"/>
                <Class abbreviatedIRI="dcterms:Location"/>
            </ObjectPropertyRange>
            <ObjectPropertyRange>
                <ObjectProperty IRI="memberOf"/>
                <Class abbreviatedIRI="void:Dataset"/>
            </ObjectPropertyRange>
            <ObjectPropertyRange>
                <ObjectProperty IRI="recorded_by"/>
                <Class abbreviatedIRI="foaf:Agent"/>
            </ObjectPropertyRange>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dcterms:source"/>
                <Class abbreviatedIRI="void:Dataset"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:basisOfRecord"/>
                <Class abbreviatedIRI="dwc:Occurrence"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:catalogNumber"/>
                <Class abbreviatedIRI="dwc:Occurrence"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:class"/>
                <Class abbreviatedIRI="dwc:Taxon"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:collectionCode"/>
                <Class abbreviatedIRI="dwc:Occurrence"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:country"/>
                <Class abbreviatedIRI="dcterms:Location"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:decimalLatitude"/>
                <Class abbreviatedIRI="dcterms:Location"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:decimalLongitude"/>
                <Class abbreviatedIRI="dcterms:Location"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:eventDate"/>
                <Class IRI="BioEvent"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:family"/>
                <Class abbreviatedIRI="dwc:Taxon"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:genus"/>
                <Class abbreviatedIRI="dwc:Taxon"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:individualCount"/>
                <Class abbreviatedIRI="dwc:Occurrence"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:kingdom"/>
                <Class abbreviatedIRI="dwc:Taxon"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:locality"/>
                <Class abbreviatedIRI="dcterms:Location"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:locationID"/>
                <Class abbreviatedIRI="dcterms:Location"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:maximumDepthInMeters"/>
                <Class abbreviatedIRI="dcterms:Location"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:minimumDepthInMeters"/>
                <Class abbreviatedIRI="dcterms:Location"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:occurrenceID"/>
                <Class abbreviatedIRI="dwc:Occurrence"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:occurrenceRemarks"/>
                <Class abbreviatedIRI="dwc:Occurrence"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:order"/>
                <Class abbreviatedIRI="dwc:Taxon"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:phylum"/>
                <Class abbreviatedIRI="dwc:Taxon"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:scientificName"/>
                <Class abbreviatedIRI="dwc:Taxon"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:scientificNameAuthorship"/>
                <Class abbreviatedIRI="dwc:Taxon"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:sex"/>
                <Class abbreviatedIRI="dwc:Occurrence"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:specificEpithet"/>
                <Class abbreviatedIRI="dwc:Taxon"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:stateProvince"/>
                <Class abbreviatedIRI="dcterms:Location"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:verbatimCoordinates"/>
                <Class abbreviatedIRI="dcterms:Location"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:verbatimEventDate"/>
                <Class abbreviatedIRI="dcterms:Location"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:verbatimLatitude"/>
                <Class abbreviatedIRI="dcterms:Location"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:verbatimLongitude"/>
                <Class abbreviatedIRI="dcterms:Location"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="dwc:waterBody"/>
                <Class abbreviatedIRI="dcterms:Location"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="geo-pos:lat"/>
                <Class abbreviatedIRI="dcterms:Location"/>
            </DataPropertyDomain>
            <DataPropertyDomain>
                <DataProperty abbreviatedIRI="geo-pos:long"/>
                <Class abbreviatedIRI="dcterms:Location"/>
            </DataPropertyDomain>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dcterms:source"/>
                <Datatype abbreviatedIRI="xsd:anyURI"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:basisOfRecord"/>
                <Datatype abbreviatedIRI="xsd:string"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:catalogNumber"/>
                <Datatype abbreviatedIRI="xsd:string"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:class"/>
                <Datatype abbreviatedIRI="xsd:string"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:country"/>
                <Datatype abbreviatedIRI="xsd:string"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:decimalLatitude"/>
                <Datatype abbreviatedIRI="xsd:decimal"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:decimalLongitude"/>
                <Datatype abbreviatedIRI="xsd:decimal"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:eventDate"/>
                <Datatype abbreviatedIRI="xsd:dateTime"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:family"/>
                <Datatype abbreviatedIRI="xsd:string"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:genus"/>
                <Datatype abbreviatedIRI="xsd:string"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:individualCount"/>
                <Datatype abbreviatedIRI="xsd:int"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:kingdom"/>
                <Datatype abbreviatedIRI="xsd:string"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:locality"/>
                <Datatype abbreviatedIRI="xsd:string"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:locationID"/>
                <Datatype abbreviatedIRI="xsd:string"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:maximumDepthInMeters"/>
                <Datatype abbreviatedIRI="xsd:int"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:minimumDepthInMeters"/>
                <Datatype abbreviatedIRI="xsd:int"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:occurrenceID"/>
                <Datatype abbreviatedIRI="xsd:string"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:occurrenceRemarks"/>
                <Datatype abbreviatedIRI="xsd:string"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:phylum"/>
                <Datatype abbreviatedIRI="xsd:string"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:scientificName"/>
                <Datatype abbreviatedIRI="xsd:string"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:scientificNameAuthorship"/>
                <Datatype abbreviatedIRI="xsd:string"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:sex"/>
                <Datatype abbreviatedIRI="xsd:string"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:specificEpithet"/>
                <Datatype abbreviatedIRI="xsd:string"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:stateProvince"/>
                <Datatype abbreviatedIRI="xsd:string"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:verbatimCoordinates"/>
                <Datatype abbreviatedIRI="xsd:string"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:verbatimEventDate"/>
                <Datatype abbreviatedIRI="xsd:string"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:verbatimLatitude"/>
                <Datatype abbreviatedIRI="xsd:string"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:verbatimLongitude"/>
                <Datatype abbreviatedIRI="xsd:string"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="dwc:waterBody"/>
                <Datatype abbreviatedIRI="xsd:string"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="geo-pos:lat"/>
                <Datatype abbreviatedIRI="xsd:decimal"/>
            </DataPropertyRange>
            <DataPropertyRange>
                <DataProperty abbreviatedIRI="geo-pos:long"/>
                <Datatype abbreviatedIRI="xsd:decimal"/>
            </DataPropertyRange>
</Ontology>
XML;

        $expected = '{"metadata":[],"graphops":[],"header":[],"prefix":[{"prefix":"","iri":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/"},{"prefix":"wd","iri":"http:\/\/www.wikidata.org\/entity\/"},{"prefix":"dbr","iri":"http:\/\/dbpedia.org\/resource\/"},{"prefix":"dwc","iri":"http:\/\/rs.tdwg.org\/dwc\/terms\/"},{"prefix":"owl","iri":"http:\/\/www.w3.org\/2002\/07\/owl#"},{"prefix":"rdf","iri":"http:\/\/www.w3.org\/1999\/02\/22-rdf-syntax-ns#"},{"prefix":"wdt","iri":"http:\/\/wikidata.org\/prop\/direct\/"},{"prefix":"xml","iri":"http:\/\/www.w3.org\/XML\/1998\/namespace"},{"prefix":"xsd","iri":"http:\/\/www.w3.org\/2001\/XMLSchema#"},{"prefix":"envo","iri":"http:\/\/purl.obolibrary.org\/obo\/"},{"prefix":"foaf","iri":"http:\/\/xmlns.com\/foaf\/0.1\/"},{"prefix":"rdfs","iri":"http:\/\/www.w3.org\/2000\/01\/rdf-schema#"},{"prefix":"time","iri":"http:\/\/www.w3.org\/2006\/time#"},{"prefix":"void","iri":"http:\/\/rdfs.org\/ns\/void#"},{"prefix":"dcterms","iri":"http:\/\/purl.org\/dc\/terms\/"},{"prefix":"geo-ont","iri":"http:\/\/www.geonames.org\/ontology#"},{"prefix":"geo-pos","iri":"http:\/\/www.w3.org\/2003\/01\/geo\/wgs84_pos#"}],"ontologyIRI":[{"prefix":"","iri":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/"}],"classes":[{"name":"http:\/\/purl.obolibrary.org\/obo\/ENVO_00000428","attrs":[],"methods":[]},{"name":"http:\/\/purl.obolibrary.org\/obo\/ENVO_00002297","attrs":[],"methods":[]},{"name":"http:\/\/purl.obolibrary.org\/obo\/ENVO_00010483","attrs":[],"methods":[]},{"name":"http:\/\/purl.org\/dc\/terms\/Location","attrs":[{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimLongitude","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/locality","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/minimumDepthInMeters","datatype":""},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/stateProvince","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/www.w3.org\/2003\/01\/geo\/wgs84_pos#lat","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#decimal"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/country","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimLatitude","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/www.w3.org\/2003\/01\/geo\/wgs84_pos#long","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#decimal"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimEventDate","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/decimalLongitude","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#decimal"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/decimalLatitude","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#decimal"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/locationID","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimCoordinates","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/maximumDepthInMeters","datatype":""},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/waterBody","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"}],"methods":[]},{"name":"http:\/\/rdfs.org\/ns\/void#Dataset","attrs":[{"name":"http:\/\/purl.org\/dc\/terms\/source","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#anyURI"}],"methods":[]},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/Event","attrs":[],"methods":[]},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/Occurrence","attrs":[{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/occurrenceID","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/individualCount","datatype":""},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/basisOfRecord","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/occurrenceRemarks","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/sex","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/collectionCode","datatype":""},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/catalogNumber","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"}],"methods":[]},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/Organism","attrs":[],"methods":[]},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/Taxon","attrs":[{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/scientificNameAuthorship","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/scientificName","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/order","datatype":""},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/family","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/genus","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/specificEpithet","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/class","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/kingdom","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/phylum","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"}],"methods":[]},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/BioEvent","attrs":[{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/eventDate","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#dateTime"}],"methods":[]},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Environment","attrs":[],"methods":[]},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region","attrs":[],"methods":[]},{"name":"http:\/\/xmlns.com\/foaf\/0.1\/Agent","attrs":[],"methods":[]}],"links":[{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/g1","classes":["http:\/\/purl.obolibrary.org\/obo\/ENVO_00010483"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Environment","constraint":[]},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/g1","classes":["http:\/\/purl.org\/dc\/terms\/Location"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region","constraint":[]},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/g1","classes":["http:\/\/purl.obolibrary.org\/obo\/ENVO_00002297"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Environment","constraint":[]},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/g1","classes":["http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/BioEvent"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"http:\/\/rs.tdwg.org\/dwc\/terms\/Event","constraint":[]},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/g1","classes":["http:\/\/purl.obolibrary.org\/obo\/ENVO_00000428"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Environment","constraint":[]},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/associated","classes":["http:\/\/rs.tdwg.org\/dwc\/terms\/Occurrence","http:\/\/rs.tdwg.org\/dwc\/terms\/Organism"],"multiplicity":["1..1","1..1"],"roles":["",""],"type":"association"},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/belongsTo","classes":["http:\/\/rs.tdwg.org\/dwc\/terms\/Organism","http:\/\/rs.tdwg.org\/dwc\/terms\/Taxon"],"multiplicity":["1..*","1..1"],"roles":["",""],"type":"association"},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/caraterises","classes":["http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Environment","http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region"],"multiplicity":["1..1","1..1"],"roles":["",""],"type":"association"},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/has_event","classes":["http:\/\/rs.tdwg.org\/dwc\/terms\/Occurrence","http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/BioEvent"],"multiplicity":["1..1","1..1"],"roles":["",""],"type":"association"},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/has_location","classes":["http:\/\/rs.tdwg.org\/dwc\/terms\/Occurrence","http:\/\/purl.org\/dc\/terms\/Location"],"multiplicity":[null,"1..1"],"roles":["",""],"type":"association"},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/memberOf","classes":["http:\/\/rs.tdwg.org\/dwc\/terms\/Occurrence","http:\/\/rdfs.org\/ns\/void#Dataset"],"multiplicity":[null,"1..1"],"roles":["",""],"type":"association"},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/recorded_by","classes":["http:\/\/rs.tdwg.org\/dwc\/terms\/Occurrence","http:\/\/xmlns.com\/foaf\/0.1\/Agent"],"multiplicity":[null,"1..1"],"roles":["",""],"type":"association"}]}';

        $importer = new OWL2Importer();

        $actual = $importer->owl2importer($input);

        $this->assertJsonStringEqualsJsonString($actual, json_encode(json_decode($expected)), true);
    }


    public function testowl2owllinkBioOntoNoDataProp(){

        $input = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
        <Ontology xmlns="http://www.w3.org/2002/07/owl#"
             xml:base="http://www.cenpat-conicet.gob.ar/bioOnto/"
             xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
             xmlns:xml="http://www.w3.org/XML/1998/namespace"
             xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
             xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
             ontologyIRI="http://www.cenpat-conicet.gob.ar/bioOnto/">
            <Prefix name="" IRI="http://www.cenpat-conicet.gob.ar/bioOnto/"/>
            <Prefix name="wd" IRI="http://www.wikidata.org/entity/"/>
            <Prefix name="dbr" IRI="http://dbpedia.org/resource/"/>
            <Prefix name="dwc" IRI="http://rs.tdwg.org/dwc/terms/"/>
            <Prefix name="owl" IRI="http://www.w3.org/2002/07/owl#"/>
            <Prefix name="rdf" IRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
            <Prefix name="wdt" IRI="http://wikidata.org/prop/direct/"/>
            <Prefix name="xml" IRI="http://www.w3.org/XML/1998/namespace"/>
            <Prefix name="xsd" IRI="http://www.w3.org/2001/XMLSchema#"/>
            <Prefix name="envo" IRI="http://purl.obolibrary.org/obo/"/>
            <Prefix name="foaf" IRI="http://xmlns.com/foaf/0.1/"/>
            <Prefix name="rdfs" IRI="http://www.w3.org/2000/01/rdf-schema#"/>
            <Prefix name="time" IRI="http://www.w3.org/2006/time#"/>
            <Prefix name="void" IRI="http://rdfs.org/ns/void#"/>
            <Prefix name="dcterms" IRI="http://purl.org/dc/terms/"/>
            <Prefix name="geo-ont" IRI="http://www.geonames.org/ontology#"/>
            <Prefix name="geo-pos" IRI="http://www.w3.org/2003/01/geo/wgs84_pos#"/>
            <EquivalentClasses>
                <Class IRI="Environment"/>
                <ObjectUnionOf>
                    <Class abbreviatedIRI="envo:ENVO_00000428"/>
                    <Class abbreviatedIRI="envo:ENVO_00002297"/>
                    <Class abbreviatedIRI="envo:ENVO_00010483"/>
                </ObjectUnionOf>
            </EquivalentClasses>
            <SubClassOf>
                <Class abbreviatedIRI="envo:ENVO_00000428"/>
                <Class IRI="Environment"/>
            </SubClassOf>
            <SubClassOf>
                <Class abbreviatedIRI="envo:ENVO_00002297"/>
                <Class IRI="Environment"/>
            </SubClassOf>
            <SubClassOf>
                <Class abbreviatedIRI="envo:ENVO_00010483"/>
                <Class IRI="Environment"/>
            </SubClassOf>
            <SubClassOf>
                <Class abbreviatedIRI="dcterms:Location"/>
                <Class IRI="Region"/>
            </SubClassOf>
            <SubClassOf>
                <Class abbreviatedIRI="dwc:Occurrence"/>
                <ObjectExactCardinality cardinality="1">
                    <ObjectProperty IRI="associated"/>
                    <Class abbreviatedIRI="dwc:Organism"/>
                </ObjectExactCardinality>
            </SubClassOf>
            <SubClassOf>
                <Class abbreviatedIRI="dwc:Occurrence"/>
                <ObjectExactCardinality cardinality="1">
                    <ObjectProperty IRI="has_event"/>
                    <Class IRI="BioEvent"/>
                </ObjectExactCardinality>
            </SubClassOf>
            <SubClassOf>
                <Class abbreviatedIRI="dwc:Occurrence"/>
                <ObjectExactCardinality cardinality="1">
                    <ObjectProperty IRI="has_location"/>
                    <Class abbreviatedIRI="dcterms:Location"/>
                </ObjectExactCardinality>
            </SubClassOf>
            <SubClassOf>
                <Class abbreviatedIRI="dwc:Occurrence"/>
                <ObjectExactCardinality cardinality="1">
                    <ObjectProperty IRI="memberOf"/>
                    <Class abbreviatedIRI="void:Dataset"/>
                </ObjectExactCardinality>
            </SubClassOf>
            <SubClassOf>
                <Class abbreviatedIRI="dwc:Occurrence"/>
                <ObjectExactCardinality cardinality="1">
                    <ObjectProperty IRI="recorded_by"/>
                    <Class abbreviatedIRI="foaf:Agent"/>
                </ObjectExactCardinality>
            </SubClassOf>
            <SubClassOf>
                <Class abbreviatedIRI="dwc:Organism"/>
                <ObjectExactCardinality cardinality="1">
                    <ObjectProperty IRI="belongsTo"/>
                    <Class abbreviatedIRI="dwc:Taxon"/>
                </ObjectExactCardinality>
            </SubClassOf>
            <SubClassOf>
                <Class abbreviatedIRI="dwc:Taxon"/>
                <ObjectMinCardinality cardinality="1">
                    <ObjectInverseOf>
                        <ObjectProperty IRI="belongsTo"/>
                    </ObjectInverseOf>
                    <Class abbreviatedIRI="dwc:Organism"/>
                </ObjectMinCardinality>
            </SubClassOf>
            <SubClassOf>
                <Class IRI="BioEvent"/>
                <Class abbreviatedIRI="dwc:Event"/>
            </SubClassOf>
            <SubClassOf>
                <Class IRI="BioEvent"/>
                <ObjectExactCardinality cardinality="1">
                    <ObjectInverseOf>
                        <ObjectProperty IRI="has_event"/>
                    </ObjectInverseOf>
                    <Class abbreviatedIRI="dwc:Occurrence"/>
                </ObjectExactCardinality>
            </SubClassOf>
            <SubClassOf>
                <Class IRI="Environment"/>
                <ObjectExactCardinality cardinality="1">
                    <ObjectProperty IRI="caraterises"/>
                    <Class IRI="Region"/>
                </ObjectExactCardinality>
            </SubClassOf>
            <SubClassOf>
                <Class IRI="Region"/>
                <ObjectExactCardinality cardinality="1">
                    <ObjectInverseOf>
                        <ObjectProperty IRI="caraterises"/>
                    </ObjectInverseOf>
                    <Class IRI="Environment"/>
                </ObjectExactCardinality>
            </SubClassOf>
            <DisjointClasses>
                <Class abbreviatedIRI="envo:ENVO_00000428"/>
                <Class abbreviatedIRI="envo:ENVO_00002297"/>
            </DisjointClasses>
            <DisjointClasses>
                <Class abbreviatedIRI="envo:ENVO_00000428"/>
                <Class abbreviatedIRI="envo:ENVO_00010483"/>
            </DisjointClasses>
            <DisjointClasses>
                <Class abbreviatedIRI="envo:ENVO_00002297"/>
                <Class abbreviatedIRI="envo:ENVO_00010483"/>
            </DisjointClasses>
            <ObjectPropertyDomain>
                <ObjectProperty IRI="associated"/>
                <Class abbreviatedIRI="dwc:Occurrence"/>
            </ObjectPropertyDomain>
            <ObjectPropertyDomain>
                <ObjectProperty IRI="belongsTo"/>
                <Class abbreviatedIRI="dwc:Organism"/>
            </ObjectPropertyDomain>
            <ObjectPropertyDomain>
                <ObjectProperty IRI="caraterises"/>
                <Class IRI="Environment"/>
            </ObjectPropertyDomain>
            <ObjectPropertyDomain>
                <ObjectProperty IRI="has_event"/>
                <Class abbreviatedIRI="dwc:Occurrence"/>
            </ObjectPropertyDomain>
            <ObjectPropertyDomain>
                <ObjectProperty IRI="has_location"/>
                <Class abbreviatedIRI="dwc:Occurrence"/>
            </ObjectPropertyDomain>
            <ObjectPropertyDomain>
                <ObjectProperty IRI="memberOf"/>
                <Class abbreviatedIRI="dwc:Occurrence"/>
            </ObjectPropertyDomain>
            <ObjectPropertyDomain>
                <ObjectProperty IRI="recorded_by"/>
                <Class abbreviatedIRI="dwc:Occurrence"/>
            </ObjectPropertyDomain>
            <ObjectPropertyRange>
                <ObjectProperty IRI="associated"/>
                <Class abbreviatedIRI="dwc:Organism"/>
            </ObjectPropertyRange>
            <ObjectPropertyRange>
                <ObjectProperty IRI="belongsTo"/>
                <Class abbreviatedIRI="dwc:Taxon"/>
            </ObjectPropertyRange>
            <ObjectPropertyRange>
                <ObjectProperty IRI="caraterises"/>
                <Class IRI="Region"/>
            </ObjectPropertyRange>
            <ObjectPropertyRange>
                <ObjectProperty IRI="has_event"/>
                <Class IRI="BioEvent"/>
            </ObjectPropertyRange>
            <ObjectPropertyRange>
                <ObjectProperty IRI="has_location"/>
                <Class abbreviatedIRI="dcterms:Location"/>
            </ObjectPropertyRange>
            <ObjectPropertyRange>
                <ObjectProperty IRI="memberOf"/>
                <Class abbreviatedIRI="void:Dataset"/>
            </ObjectPropertyRange>
            <ObjectPropertyRange>
                <ObjectProperty IRI="recorded_by"/>
                <Class abbreviatedIRI="foaf:Agent"/>
            </ObjectPropertyRange>
</Ontology>
XML;

        $expected = '{"metadata":[],"graphops":[],"header":[],"prefix":[{"prefix":"","iri":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/"},{"prefix":"wd","iri":"http:\/\/www.wikidata.org\/entity\/"},{"prefix":"dbr","iri":"http:\/\/dbpedia.org\/resource\/"},{"prefix":"dwc","iri":"http:\/\/rs.tdwg.org\/dwc\/terms\/"},{"prefix":"owl","iri":"http:\/\/www.w3.org\/2002\/07\/owl#"},{"prefix":"rdf","iri":"http:\/\/www.w3.org\/1999\/02\/22-rdf-syntax-ns#"},{"prefix":"wdt","iri":"http:\/\/wikidata.org\/prop\/direct\/"},{"prefix":"xml","iri":"http:\/\/www.w3.org\/XML\/1998\/namespace"},{"prefix":"xsd","iri":"http:\/\/www.w3.org\/2001\/XMLSchema#"},{"prefix":"envo","iri":"http:\/\/purl.obolibrary.org\/obo\/"},{"prefix":"foaf","iri":"http:\/\/xmlns.com\/foaf\/0.1\/"},{"prefix":"rdfs","iri":"http:\/\/www.w3.org\/2000\/01\/rdf-schema#"},{"prefix":"time","iri":"http:\/\/www.w3.org\/2006\/time#"},{"prefix":"void","iri":"http:\/\/rdfs.org\/ns\/void#"},{"prefix":"dcterms","iri":"http:\/\/purl.org\/dc\/terms\/"},{"prefix":"geo-ont","iri":"http:\/\/www.geonames.org\/ontology#"},{"prefix":"geo-pos","iri":"http:\/\/www.w3.org\/2003\/01\/geo\/wgs84_pos#"}],"ontologyIRI":[{"prefix":"","iri":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/"}],"classes":[{"name":"http:\/\/purl.obolibrary.org\/obo\/ENVO_00000428","attrs":[],"methods":[]},{"name":"http:\/\/purl.obolibrary.org\/obo\/ENVO_00002297","attrs":[],"methods":[]},{"name":"http:\/\/purl.obolibrary.org\/obo\/ENVO_00010483","attrs":[],"methods":[]},{"name":"http:\/\/purl.org\/dc\/terms\/Location","attrs":[],"methods":[]},{"name":"http:\/\/rdfs.org\/ns\/void#Dataset","attrs":[],"methods":[]},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/Event","attrs":[],"methods":[]},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/Occurrence","attrs":[],"methods":[]},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/Organism","attrs":[],"methods":[]},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/Taxon","attrs":[],"methods":[]},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/BioEvent","attrs":[],"methods":[]},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Environment","attrs":[],"methods":[]},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region","attrs":[],"methods":[]},{"name":"http:\/\/xmlns.com\/foaf\/0.1\/Agent","attrs":[],"methods":[]}],"links":[{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/g1","classes":["http:\/\/purl.obolibrary.org\/obo\/ENVO_00010483"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Environment","constraint":[]},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/g1","classes":["http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/BioEvent"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"http:\/\/rs.tdwg.org\/dwc\/terms\/Event","constraint":[]},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/g1","classes":["http:\/\/purl.org\/dc\/terms\/Location"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region","constraint":[]},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/g1","classes":["http:\/\/purl.obolibrary.org\/obo\/ENVO_00002297"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Environment","constraint":[]},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/g1","classes":["http:\/\/purl.obolibrary.org\/obo\/ENVO_00000428"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Environment","constraint":[]},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/associated","classes":["http:\/\/rs.tdwg.org\/dwc\/terms\/Occurrence","http:\/\/rs.tdwg.org\/dwc\/terms\/Organism"],"multiplicity":["1..1","1..1"],"roles":["",""],"type":"association"},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/belongsTo","classes":["http:\/\/rs.tdwg.org\/dwc\/terms\/Organism","http:\/\/rs.tdwg.org\/dwc\/terms\/Taxon"],"multiplicity":["1..*","1..1"],"roles":["",""],"type":"association"},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/caraterises","classes":["http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Environment","http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region"],"multiplicity":["1..1","1..1"],"roles":["",""],"type":"association"},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/has_event","classes":["http:\/\/rs.tdwg.org\/dwc\/terms\/Occurrence","http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/BioEvent"],"multiplicity":["1..1","1..1"],"roles":["",""],"type":"association"},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/has_location","classes":["http:\/\/rs.tdwg.org\/dwc\/terms\/Occurrence","http:\/\/purl.org\/dc\/terms\/Location"],"multiplicity":[null,"1..1"],"roles":["",""],"type":"association"},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/memberOf","classes":["http:\/\/rs.tdwg.org\/dwc\/terms\/Occurrence","http:\/\/rdfs.org\/ns\/void#Dataset"],"multiplicity":[null,"1..1"],"roles":["",""],"type":"association"},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/recorded_by","classes":["http:\/\/rs.tdwg.org\/dwc\/terms\/Occurrence","http:\/\/xmlns.com\/foaf\/0.1\/Agent"],"multiplicity":[null,"1..1"],"roles":["",""],"type":"association"}]}';

        $importer = new OWL2Importer();

        $actual = $importer->owl2importer($input); var_dump($actual);

        $this->assertJsonStringEqualsJsonString($actual, json_encode(json_decode($expected)), true);
    }
*/
}
