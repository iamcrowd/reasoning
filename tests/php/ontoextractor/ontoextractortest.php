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

class OntoExtractorTest extends PHPUnit\Framework\TestCase{


    # Extracting owl class axioms from an owl file

   public function testClassExtractor(){
      $input = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
  <Ontology xmlns="http://www.w3.org/2002/07/owl#"
        xml:base="http://localhost/kb1/"
        xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
        xmlns:xml="http://www.w3.org/XML/1998/namespace"
        xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
        xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
        ontologyIRI="http://localhost/kb1/">
      <Prefix name="" IRI="http://localhost/kb1/"/>
      <SubClassOf>
        <Class IRI="Class1"/>
        <Class abbreviatedIRI="owl:Thing"/>
      </SubClassOf>
      <SubClassOf>
        <Class IRI="Class3"/>
        <Class IRI="Class1"/>
      </SubClassOf>
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

      $this->assertJsonStringEqualsJsonString($actual, json_encode(json_decode($expected)), true);
  }

  # Extracting object properties from an owl file. This test also extracts domain and range for each object property

  public function testObjectPropertyDomainRangeExtractor(){
    $input = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Ontology xmlns="http://www.w3.org/2002/07/owl#"
      xml:base="http://localhost/kb1/"
      xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
      xmlns:xml="http://www.w3.org/XML/1998/namespace"
      xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
      xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
      ontologyIRI="http://localhost/kb1/">
    <Prefix name="" IRI="http://localhost/kb1/"/>
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
      <Class IRI="Class2"/>
    </SubClassOf>
    <SubClassOf>
      <ObjectSomeValuesFrom>
        <ObjectInverseOf>
          <ObjectProperty IRI="R"/>
        </ObjectInverseOf>
        <Class abbreviatedIRI="owl:Thing"/>
      </ObjectSomeValuesFrom>
      <Class IRI="Class3"/>
    </SubClassOf>
    <DataPropertyDomain>
        <DataProperty IRI="value"/>
        <Class IRI="Class1"/>
    </DataPropertyDomain>
    <DataPropertyRange>
        <DataProperty IRI="value"/>
        <Datatype abbreviatedIRI="xsd:integer"/>
    </DataPropertyRange>
</Ontology>
XML;

    $expected = '[
      {"Class":
        ["http://localhost/kb1/Class1","http://localhost/kb1/Class2","http://localhost/kb1/Class3",
        "http://www.w3.org/2002/07/owl#Thing","http://www.w3.org/2002/07/owl#Nothing"]},
      {"ObjectProperty":["http://localhost/kb1/R"]},
      {"Domain":
        [{"http://localhost/kb1/R":"http://localhost/kb1/Class2"},
        {"http://localhost/kb1/R":"http://www.w3.org/2002/07/owl#Thing"}]},
      {"Range":
        [{"http://localhost/kb1/R":"http://localhost/kb1/Class1"},
        {"http://localhost/kb1/R":"http://www.w3.org/2002/07/owl#Thing"},
        {"http://localhost/kb1/R":"http://localhost/kb1/Class3"}]},
      {"DataProperty":
        ["http://localhost/kb1/value"]},
      {"DataPropertyDomain":
        [{"http://localhost/kb1/value":"http://localhost/kb1/Class1"},
        {"http://localhost/kb1/value":"http://www.w3.org/2002/07/owl#Thing"}]},
      {"DataPropertyRange":
        [{"http://localhost/kb1/value":"http://www.w3.org/2001/XMLSchema#integer"}]},
      {"StrictSubClassOf":
        [{"subclass":["http://localhost/kb1/Class1","http://www.w3.org/2002/07/owl#Thing"]},
         {"subclass":["http://localhost/kb1/Class3","http://localhost/kb1/Class1"]},
         {"subclass":["http://localhost/kb1/Class2","http://www.w3.org/2002/07/owl#Thing"]},
         {"subclass":["http://www.w3.org/2002/07/owl#Nothing","http://localhost/kb1/Class3"]},
         {"subclass":["http://www.w3.org/2002/07/owl#Nothing","http://localhost/kb1/Class2"]}]},
      {"StrictSubPropertyOf":
          [{"subobjectproperty":["http://localhost/kb1/value","http://www.w3.org/2002/07/owl#topDataProperty"]},
          {"subobjectproperty":["http://localhost/kb1/R","http://www.w3.org/2002/07/owl#topObjectProperty"]}]},
      [],
      {"DisjointWithClass":
          [{"disjointclasses":["http://www.w3.org/2002/07/owl#Nothing","http://www.w3.org/2002/07/owl#Thing"]},
          {"disjointclasses":["http://www.w3.org/2002/07/owl#Nothing","http://localhost/kb1/Class3"]},
          {"disjointclasses":["http://localhost/kb1/Class1","http://www.w3.org/2002/07/owl#Nothing"]},
          {"disjointclasses":["http://www.w3.org/2002/07/owl#Nothing","http://localhost/kb1/Class2"]}]},
      [],[]
      ]';

    $sparqldl = new OntoExtractor();
    $sparqldl->extractor($input);
    $graphaxioms = $sparqldl->getIntermediateSparqldl();
    $actual = $graphaxioms->to_json();

    $this->assertJsonStringEqualsJsonString($actual, json_encode(json_decode($expected)), true);
}


  # Extracting object properties from an owl file. This test also extracts domain and range for each object property

  public function testEquivalentAndDisjointExtractor(){
    $input = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Ontology xmlns="http://www.w3.org/2002/07/owl#"
    xml:base="http://localhost/kb1/"
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:xml="http://www.w3.org/XML/1998/namespace"
    xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
    xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
    ontologyIRI="http://localhost/kb1/">
  <Prefix name="" IRI="http://localhost/kb1/"/>
  <EquivalentClasses>
    <Class IRI="http://www.w3.org/2002/07/owl#Nothing"/>
    <Class IRI="Class2"/>
  </EquivalentClasses>
  <DisjointClasses>
    <Class IRI="Class3"/>
    <Class IRI="Class4"/>
  </DisjointClasses>
  <EquivalentObjectProperties>
    <ObjectProperty IRI="r1"/>
    <ObjectProperty IRI="r2"/>
  </EquivalentObjectProperties>
  <DisjointObjectProperties>
    <ObjectProperty IRI="r1"/>
    <ObjectProperty IRI="r3"/>
  </DisjointObjectProperties>
</Ontology>
XML;

  $expected = '[
    {"Class":
      ["http://localhost/kb1/Class1","http://localhost/kb1/Class2","http://localhost/kb1/Class3",
      "http://localhost/kb1/Class4","http://www.w3.org/2002/07/owl#Thing","http://www.w3.org/2002/07/owl#Nothing"]},
    {"ObjectProperty":
      ["http://localhost/kb1/r1","http://localhost/kb1/r2","http://localhost/kb1/r3"]},
    {"Domain":
      [{"http://localhost/kb1/r2":"http://www.w3.org/2002/07/owl#Thing"},
      {"http://localhost/kb1/r3":"http://www.w3.org/2002/07/owl#Thing"},
      {"http://localhost/kb1/r1":"http://www.w3.org/2002/07/owl#Thing"}]},
    {"Range":
      [{"http://localhost/kb1/r3":"http://www.w3.org/2002/07/owl#Thing"},
      {"http://localhost/kb1/r1":"http://www.w3.org/2002/07/owl#Thing"},
      {"http://localhost/kb1/r2":"http://www.w3.org/2002/07/owl#Thing"}]},
    [],[],[],
    {"StrictSubClassOf":
      [{"subclass":["http://localhost/kb1/Class3","http://www.w3.org/2002/07/owl#Thing"]},
        {"subclass":["http://localhost/kb1/Class2","http://www.w3.org/2002/07/owl#Thing"]},
        {"subclass":["http://localhost/kb1/Class1","http://www.w3.org/2002/07/owl#Thing"]},
        {"subclass":["http://www.w3.org/2002/07/owl#Nothing","http://localhost/kb1/Class3"]},
        {"subclass":["http://www.w3.org/2002/07/owl#Nothing","http://localhost/kb1/Class4"]},
        {"subclass":["http://www.w3.org/2002/07/owl#Nothing","http://localhost/kb1/Class1"]},
        {"subclass":["http://localhost/kb1/Class4","http://www.w3.org/2002/07/owl#Thing"]},
        {"subclass":["http://www.w3.org/2002/07/owl#Nothing","http://localhost/kb1/Class2"]}]},
    {"StrictSubPropertyOf":
      [{"subobjectproperty":["http://localhost/kb1/r2","http://www.w3.org/2002/07/owl#topObjectProperty"]},
        {"subobjectproperty":["http://localhost/kb1/r1","http://www.w3.org/2002/07/owl#topObjectProperty"]},
        {"subobjectproperty":["http://localhost/kb1/r3","http://www.w3.org/2002/07/owl#topObjectProperty"]}]},
    {"EquivalentClass":
      [{"equivalentclasses":["http://www.w3.org/2002/07/owl#Nothing","http://localhost/kb1/Class2"]}]},
    {"DisjointWithClass":
      [{"disjointclasses":["http://localhost/kb1/Class4","http://www.w3.org/2002/07/owl#Nothing"]},
      {"disjointclasses":["http://localhost/kb1/Class4","http://localhost/kb1/Class3"]},
      {"disjointclasses":["http://www.w3.org/2002/07/owl#Thing","http://www.w3.org/2002/07/owl#Nothing"]},
      {"disjointclasses":["http://localhost/kb1/Class2","http://www.w3.org/2002/07/owl#Nothing"]},
      {"disjointclasses":["http://localhost/kb1/Class3","http://www.w3.org/2002/07/owl#Nothing"]},
      {"disjointclasses":["http://localhost/kb1/Class1","http://www.w3.org/2002/07/owl#Nothing"]}]},
    {"EquivalentProperty":
      [{"equivalentobjectproperty":["http://localhost/kb1/r2","http://localhost/kb1/r1"]},
      {"equivalentobjectproperty":["http://localhost/kb1/r1","http://localhost/kb1/r2"]}]},
    []
  ]';

  $sparqldl = new OntoExtractor();
  $sparqldl->extractor($input);
  $graphaxioms = $sparqldl->getIntermediateSparqldl();
  $actual = $graphaxioms->to_json();

  $this->assertJsonStringEqualsJsonString($actual, json_encode(json_decode($expected)), true);

}

  /**
  Extracting object properties from an owl file. This test also extracts domain and range for each object property
  */
/*
  public function testRealDataPropertywithIRIsExtractor(){
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
                <Prefix name="bio-onto" IRI="http://www.cenpat-conicet.gob.ar/ontology/"/>
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
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Occurrence"/>
                    <ObjectExactCardinality cardinality="1">
                        <ObjectProperty IRI="associated"/>
                        <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Organism"/>
                    </ObjectExactCardinality>
                </SubClassOf>
                <SubClassOf>
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Occurrence"/>
                    <ObjectExactCardinality cardinality="1">
                        <ObjectProperty IRI="has_event"/>
                        <Class IRI="BioEvent"/>
                    </ObjectExactCardinality>
                </SubClassOf>
                <SubClassOf>
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Occurrence"/>
                    <ObjectExactCardinality cardinality="1">
                        <ObjectProperty IRI="has_location"/>
                        <Class abbreviatedIRI="dcterms:Location"/>
                    </ObjectExactCardinality>
                </SubClassOf>
                <SubClassOf>
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Occurrence"/>
                    <ObjectExactCardinality cardinality="1">
                        <ObjectProperty IRI="memberOf"/>
                        <Class abbreviatedIRI="void:Dataset"/>
                    </ObjectExactCardinality>
                </SubClassOf>
                <SubClassOf>
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Occurrence"/>
                    <ObjectExactCardinality cardinality="1">
                        <ObjectProperty IRI="recorded_by"/>
                        <Class abbreviatedIRI="foaf:Agent"/>
                    </ObjectExactCardinality>
                </SubClassOf>
                <SubClassOf>
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Organism"/>
                    <ObjectExactCardinality cardinality="1">
                        <ObjectProperty IRI="belongsTo"/>
                        <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Taxon"/>
                    </ObjectExactCardinality>
                </SubClassOf>
                <SubClassOf>
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Taxon"/>
                    <ObjectMinCardinality cardinality="1">
                        <ObjectInverseOf>
                            <ObjectProperty IRI="belongsTo"/>
                        </ObjectInverseOf>
                        <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Organism"/>
                    </ObjectMinCardinality>
                </SubClassOf>
                <SubClassOf>
                    <Class IRI="BioEvent"/>
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Event"/>
                </SubClassOf>
                <SubClassOf>
                    <Class IRI="BioEvent"/>
                    <ObjectExactCardinality cardinality="1">
                        <ObjectInverseOf>
                            <ObjectProperty IRI="has_event"/>
                        </ObjectInverseOf>
                        <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Occurrence"/>
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
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Occurrence"/>
                </ObjectPropertyDomain>
                <ObjectPropertyDomain>
                    <ObjectProperty IRI="belongsTo"/>
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Organism"/>
                </ObjectPropertyDomain>
                <ObjectPropertyDomain>
                    <ObjectProperty IRI="caraterises"/>
                    <Class IRI="Environment"/>
                </ObjectPropertyDomain>
                <ObjectPropertyDomain>
                    <ObjectProperty IRI="has_event"/>
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Occurrence"/>
                </ObjectPropertyDomain>
                <ObjectPropertyDomain>
                    <ObjectProperty IRI="has_location"/>
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Occurrence"/>
                </ObjectPropertyDomain>
                <ObjectPropertyDomain>
                    <ObjectProperty IRI="memberOf"/>
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Occurrence"/>
                </ObjectPropertyDomain>
                <ObjectPropertyDomain>
                    <ObjectProperty IRI="recorded_by"/>
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Occurrence"/>
                </ObjectPropertyDomain>
                <ObjectPropertyRange>
                    <ObjectProperty IRI="associated"/>
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Organism"/>
                </ObjectPropertyRange>
                <ObjectPropertyRange>
                    <ObjectProperty IRI="belongsTo"/>
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Taxon"/>
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
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Occurrence"/>
                </DataPropertyDomain>
                <DataPropertyDomain>
                    <DataProperty abbreviatedIRI="dwc:catalogNumber"/>
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Occurrence"/>
                </DataPropertyDomain>
                <DataPropertyDomain>
                    <DataProperty abbreviatedIRI="dwc:class"/>
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Taxon"/>
                </DataPropertyDomain>
                <DataPropertyDomain>
                    <DataProperty abbreviatedIRI="dwc:collectionCode"/>
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Occurrence"/>
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
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Taxon"/>
                </DataPropertyDomain>
                <DataPropertyDomain>
                    <DataProperty abbreviatedIRI="dwc:genus"/>
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Taxon"/>
                </DataPropertyDomain>
                <DataPropertyDomain>
                    <DataProperty abbreviatedIRI="dwc:individualCount"/>
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Occurrence"/>
                </DataPropertyDomain>
                <DataPropertyDomain>
                    <DataProperty abbreviatedIRI="dwc:kingdom"/>
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Taxon"/>
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
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Occurrence"/>
                </DataPropertyDomain>
                <DataPropertyDomain>
                    <DataProperty abbreviatedIRI="dwc:occurrenceRemarks"/>
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Occurrence"/>
                </DataPropertyDomain>
                <DataPropertyDomain>
                    <DataProperty abbreviatedIRI="dwc:order"/>
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Taxon"/>
                </DataPropertyDomain>
                <DataPropertyDomain>
                    <DataProperty abbreviatedIRI="dwc:phylum"/>
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Taxon"/>
                </DataPropertyDomain>
                <DataPropertyDomain>
                    <DataProperty abbreviatedIRI="dwc:scientificName"/>
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Taxon"/>
                </DataPropertyDomain>
                <DataPropertyDomain>
                    <DataProperty abbreviatedIRI="dwc:scientificNameAuthorship"/>
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Taxon"/>
                </DataPropertyDomain>
                <DataPropertyDomain>
                    <DataProperty abbreviatedIRI="dwc:sex"/>
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Occurrence"/>
                </DataPropertyDomain>
                <DataPropertyDomain>
                    <DataProperty abbreviatedIRI="dwc:specificEpithet"/>
                    <Class IRI="http://rs.tdwg.org/dwc/terms/dwc:Taxon"/>
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

    $expected = '[{"Class":
      ["http:\/\/purl.obolibrary.org\/obo\/ENVO_00000428","http:\/\/purl.obolibrary.org\/obo\/ENVO_00002297",
      "http:\/\/purl.obolibrary.org\/obo\/ENVO_00010483","http:\/\/purl.org\/dc\/terms\/Location",
      "http:\/\/rdfs.org\/ns\/void#Dataset","http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Event",
      "http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Occurrence","http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Organism",
      "http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Taxon","http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/BioEvent",
      "http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Environment","http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region",
      "http:\/\/xmlns.com\/foaf\/0.1\/Agent","http:\/\/www.w3.org\/2002\/07\/owl#Thing",
      "http:\/\/www.w3.org\/2002\/07\/owl#Nothing"]},
    {"ObjectProperty":
      ["http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/associated","http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/belongsTo",
      "http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/caraterises","http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/has_event",
      "http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/has_location","http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/memberOf",
      "http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/recorded_by"]},
    {"Domain":
      [{"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/recorded_by":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/associated":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/belongsTo":"http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Organism"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/has_event":"http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Occurrence"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/memberOf":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/belongsTo":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/has_location":"http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Occurrence"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/has_event":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/has_location":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/caraterises":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/associated":"http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Occurrence"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/caraterises":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Environment"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/memberOf":"http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Occurrence"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/recorded_by":"http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Occurrence"}]},
    {"Range":
      [{"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/associated":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/has_event":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/BioEvent"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/has_location":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/has_event":"http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Event"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/belongsTo":"http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Taxon"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/memberOf":"http:\/\/rdfs.org\/ns\/void#Dataset"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/belongsTo":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/has_event":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/caraterises":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/has_location":"http:\/\/purl.org\/dc\/terms\/Location"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/caraterises":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/has_location":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/memberOf":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/recorded_by":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/recorded_by":"http:\/\/xmlns.com\/foaf\/0.1\/Agent"},
      {"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/associated":"http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Organism"}]},
    {"DataProperty":
      ["http:\/\/rs.tdwg.org\/dwc\/terms\/phylum","http:\/\/rs.tdwg.org\/dwc\/terms\/kingdom",
      "http:\/\/rs.tdwg.org\/dwc\/terms\/family","http:\/\/rs.tdwg.org\/dwc\/terms\/waterBody",
      "http:\/\/rs.tdwg.org\/dwc\/terms\/catalogNumber","http:\/\/rs.tdwg.org\/dwc\/terms\/decimalLongitude",
      "http:\/\/rs.tdwg.org\/dwc\/terms\/eventDate","http:\/\/rs.tdwg.org\/dwc\/terms\/decimalLatitude",
      "http:\/\/rs.tdwg.org\/dwc\/terms\/stateProvince","http:\/\/rs.tdwg.org\/dwc\/terms\/country",
      "http:\/\/www.w3.org\/2003\/01\/geo\/wgs84_pos#lat","http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimEventDate",
      "http:\/\/rs.tdwg.org\/dwc\/terms\/occurrenceID","http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimLongitude",
      "http:\/\/www.w3.org\/2003\/01\/geo\/wgs84_pos#long","http:\/\/rs.tdwg.org\/dwc\/terms\/locality",
      "http:\/\/rs.tdwg.org\/dwc\/terms\/minimumDepthInMeters","http:\/\/rs.tdwg.org\/dwc\/terms\/scientificNameAuthorship",
      "http:\/\/rs.tdwg.org\/dwc\/terms\/individualCount","http:\/\/rs.tdwg.org\/dwc\/terms\/scientificName",
      "http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimLatitude","http:\/\/rs.tdwg.org\/dwc\/terms\/order",
      "http:\/\/rs.tdwg.org\/dwc\/terms\/collectionCode","http:\/\/rs.tdwg.org\/dwc\/terms\/locationID",
      "http:\/\/rs.tdwg.org\/dwc\/terms\/occurrenceRemarks","http:\/\/rs.tdwg.org\/dwc\/terms\/genus",
      "http:\/\/rs.tdwg.org\/dwc\/terms\/class","http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimCoordinates",
      "http:\/\/rs.tdwg.org\/dwc\/terms\/basisOfRecord","http:\/\/purl.org\/dc\/terms\/source",
      "http:\/\/rs.tdwg.org\/dwc\/terms\/maximumDepthInMeters","http:\/\/rs.tdwg.org\/dwc\/terms\/sex",
      "http:\/\/rs.tdwg.org\/dwc\/terms\/specificEpithet"]},
    {"DataPropertyDomain":
      [{"http:\/\/rs.tdwg.org\/dwc\/terms\/phylum":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/www.w3.org\/2003\/01\/geo\/wgs84_pos#lat":"http:\/\/purl.org\/dc\/terms\/Location"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/stateProvince":"http:\/\/purl.org\/dc\/terms\/Location"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimLongitude":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/locationID":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/family":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/maximumDepthInMeters":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/collectionCode":"http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Occurrence"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/scientificNameAuthorship":"http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Taxon"},
      {"http:\/\/www.w3.org\/2003\/01\/geo\/wgs84_pos#lat":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/country":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/waterBody":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/locality":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimLatitude":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/stateProvince":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/occurrenceRemarks":"http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Occurrence"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/decimalLongitude":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimLatitude":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/www.w3.org\/2003\/01\/geo\/wgs84_pos#long":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/eventDate":"http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Event"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/decimalLatitude":"http:\/\/purl.org\/dc\/terms\/Location"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/order":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimEventDate":"http:\/\/purl.org\/dc\/terms\/Location"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/catalogNumber":"http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Occurrence"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/individualCount":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimLatitude":"http:\/\/purl.org\/dc\/terms\/Location"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/order":"http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Taxon"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimLongitude":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/class":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/collectionCode":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/class":"http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Taxon"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/stateProvince":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/locationID":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/phylum":"http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Taxon"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/sex":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/basisOfRecord":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/country":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region"},
      {"http:\/\/purl.org\/dc\/terms\/source":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/waterBody":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/eventDate":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/BioEvent"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/locationID":"http:\/\/purl.org\/dc\/terms\/Location"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/catalogNumber":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/kingdom":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/decimalLatitude":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/www.w3.org\/2003\/01\/geo\/wgs84_pos#lat":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/eventDate":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/decimalLongitude":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/minimumDepthInMeters":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/locality":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/individualCount":"http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Occurrence"},
      {"http:\/\/purl.org\/dc\/terms\/source":"http:\/\/rdfs.org\/ns\/void#Dataset"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimCoordinates":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimEventDate":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/www.w3.org\/2003\/01\/geo\/wgs84_pos#long":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/maximumDepthInMeters":"http:\/\/purl.org\/dc\/terms\/Location"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/decimalLatitude":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimCoordinates":"http:\/\/purl.org\/dc\/terms\/Location"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/basisOfRecord":"http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Occurrence"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/occurrenceID":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/scientificName":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/sex":"http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Occurrence"},
      {"http:\/\/www.w3.org\/2003\/01\/geo\/wgs84_pos#long":"http:\/\/purl.org\/dc\/terms\/Location"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/decimalLongitude":"http:\/\/purl.org\/dc\/terms\/Location"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/minimumDepthInMeters":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/scientificNameAuthorship":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/waterBody":"http:\/\/purl.org\/dc\/terms\/Location"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/country":"http:\/\/purl.org\/dc\/terms\/Location"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/scientificName":"http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Taxon"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/maximumDepthInMeters":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimEventDate":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/kingdom":"http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Taxon"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimCoordinates":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/family":"http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Taxon"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/genus":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/occurrenceID":"http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Occurrence"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/genus":"http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Taxon"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/locality":"http:\/\/purl.org\/dc\/terms\/Location"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/occurrenceRemarks":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/minimumDepthInMeters":"http:\/\/purl.org\/dc\/terms\/Location"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimLongitude":"http:\/\/purl.org\/dc\/terms\/Location"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/specificEpithet":"http:\/\/www.w3.org\/2002\/07\/owl#Thing"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/specificEpithet":"http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Taxon"}]},
    {"DataPropertyRange":
      [{"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimLongitude":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/individualCount":"http:\/\/www.w3.org\/2001\/XMLSchema#decimal"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/phylum":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/catalogNumber":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/kingdom":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/waterBody":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/decimalLatitude":"http:\/\/www.w3.org\/2001\/XMLSchema#decimal"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/decimalLongitude":"http:\/\/www.w3.org\/2001\/XMLSchema#decimal"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/stateProvince":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/locality":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/maximumDepthInMeters":"http:\/\/www.w3.org\/2001\/XMLSchema#decimal"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/minimumDepthInMeters":"http:\/\/www.w3.org\/2001\/XMLSchema#int"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/occurrenceID":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/eventDate":"http:\/\/www.w3.org\/2001\/XMLSchema#dateTime"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimEventDate":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/occurrenceRemarks":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},
      {"http:\/\/purl.org\/dc\/terms\/source":"http:\/\/www.w3.org\/2001\/XMLSchema#anyURI"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/country":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/scientificName":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/scientificNameAuthorship":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimLatitude":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},
      {"http:\/\/www.w3.org\/2003\/01\/geo\/wgs84_pos#long":"http:\/\/www.w3.org\/2001\/XMLSchema#decimal"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/class":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/basisOfRecord":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimCoordinates":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/genus":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/maximumDepthInMeters":"http:\/\/www.w3.org\/2001\/XMLSchema#int"},
      {"http:\/\/www.w3.org\/2003\/01\/geo\/wgs84_pos#lat":"http:\/\/www.w3.org\/2001\/XMLSchema#decimal"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/specificEpithet":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/family":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/minimumDepthInMeters":"http:\/\/www.w3.org\/2001\/XMLSchema#decimal"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/sex":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/individualCount":"http:\/\/www.w3.org\/2001\/XMLSchema#int"},
      {"http:\/\/rs.tdwg.org\/dwc\/terms\/locationID":"http:\/\/www.w3.org\/2001\/XMLSchema#string"}]},
    {"StrictSubClassOf":
      [{"subclass":["http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Environment","http:\/\/www.w3.org\/2002\/07\/owl#Thing"]},
      {"subclass":["http:\/\/purl.obolibrary.org\/obo\/ENVO_00002297","http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Environment"]},
      {"subclass":["http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/BioEvent","http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Event"]},
      {"subclass":["http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Taxon","http:\/\/www.w3.org\/2002\/07\/owl#Thing"]},
      {"subclass":["http:\/\/www.w3.org\/2002\/07\/owl#Nothing","http:\/\/purl.obolibrary.org\/obo\/ENVO_00002297"]},
      {"subclass":["http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region","http:\/\/www.w3.org\/2002\/07\/owl#Thing"]},
      {"subclass":["http:\/\/purl.org\/dc\/terms\/Location","http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region"]},
      {"subclass":["http:\/\/purl.obolibrary.org\/obo\/ENVO_00000428","http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Environment"]},
      {"subclass":["http:\/\/www.w3.org\/2002\/07\/owl#Nothing","http:\/\/purl.org\/dc\/terms\/Location"]},
      {"subclass":["http:\/\/www.w3.org\/2002\/07\/owl#Nothing","http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Organism"]},
      {"subclass":["http:\/\/xmlns.com\/foaf\/0.1\/Agent","http:\/\/www.w3.org\/2002\/07\/owl#Thing"]},
      {"subclass":["http:\/\/www.w3.org\/2002\/07\/owl#Nothing","http:\/\/xmlns.com\/foaf\/0.1\/Agent"]},
      {"subclass":["http:\/\/purl.obolibrary.org\/obo\/ENVO_00010483","http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Environment"]},
      {"subclass":["http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Occurrence","http:\/\/www.w3.org\/2002\/07\/owl#Thing"]},
      {"subclass":["http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Organism","http:\/\/www.w3.org\/2002\/07\/owl#Thing"]},
      {"subclass":["http:\/\/www.w3.org\/2002\/07\/owl#Nothing","http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Occurrence"]},
      {"subclass":["http:\/\/www.w3.org\/2002\/07\/owl#Nothing","http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/BioEvent"]},
      {"subclass":["http:\/\/www.w3.org\/2002\/07\/owl#Nothing","http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Taxon"]},
      {"subclass":["http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Event","http:\/\/www.w3.org\/2002\/07\/owl#Thing"]},
      {"subclass":["http:\/\/rdfs.org\/ns\/void#Dataset","http:\/\/www.w3.org\/2002\/07\/owl#Thing"]},
      {"subclass":["http:\/\/www.w3.org\/2002\/07\/owl#Nothing","http:\/\/purl.obolibrary.org\/obo\/ENVO_00010483"]},
      {"subclass":["http:\/\/www.w3.org\/2002\/07\/owl#Nothing","http:\/\/rdfs.org\/ns\/void#Dataset"]},
      {"subclass":["http:\/\/www.w3.org\/2002\/07\/owl#Nothing","http:\/\/purl.obolibrary.org\/obo\/ENVO_00000428"]}]},
    {"StrictSubPropertyOf":
      [{"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/scientificNameAuthorship","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},{"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimEventDate","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},{"subobjectproperty":["http:\/\/www.w3.org\/2003\/01\/geo\/wgs84_pos#long","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},{"subobjectproperty":["http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/recorded_by","http:\/\/www.w3.org\/2002\/07\/owl#topObjectProperty"]},
      {"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/individualCount","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},{"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/catalogNumber","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},{"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/stateProvince","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},{"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/country","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},
      {"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimLatitude","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},{"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimLongitude","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},{"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/specificEpithet","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},{"subobjectproperty":["http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/has_event","http:\/\/www.w3.org\/2002\/07\/owl#topObjectProperty"]},
      {"subobjectproperty":["http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/caraterises","http:\/\/www.w3.org\/2002\/07\/owl#topObjectProperty"]},{"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/kingdom","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},{"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/locality","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},{"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/scientificName","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},
      {"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/collectionCode","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},{"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/minimumDepthInMeters","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},{"subobjectproperty":["http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/memberOf","http:\/\/www.w3.org\/2002\/07\/owl#topObjectProperty"]},{"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/order","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},
      {"subobjectproperty":["http:\/\/www.w3.org\/2003\/01\/geo\/wgs84_pos#lat","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},{"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/locationID","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},{"subobjectproperty":["http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/associated","http:\/\/www.w3.org\/2002\/07\/owl#topObjectProperty"]},{"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimCoordinates","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},
      {"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/waterBody","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},{"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/maximumDepthInMeters","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},{"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/genus","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},{"subobjectproperty":["http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/has_location","http:\/\/www.w3.org\/2002\/07\/owl#topObjectProperty"]},
      {"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/basisOfRecord","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},{"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/class","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},{"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/occurrenceRemarks","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},{"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/sex","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},{"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/occurrenceID","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},{"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/eventDate","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},{"subobjectproperty":["http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/belongsTo","http:\/\/www.w3.org\/2002\/07\/owl#topObjectProperty"]},{"subobjectproperty":["http:\/\/purl.org\/dc\/terms\/source","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},
      {"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/decimalLatitude","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},{"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/decimalLongitude","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},{"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/family","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]},{"subobjectproperty":["http:\/\/rs.tdwg.org\/dwc\/terms\/phylum","http:\/\/www.w3.org\/2002\/07\/owl#topDataProperty"]}]},[],
    {"DisjointWithClass":
      [{"disjointclasses":["http:\/\/purl.obolibrary.org\/obo\/ENVO_00010483","http:\/\/purl.obolibrary.org\/obo\/ENVO_00000428"]},
      {"disjointclasses":["http:\/\/www.w3.org\/2002\/07\/owl#Nothing","http:\/\/purl.obolibrary.org\/obo\/ENVO_00002297"]},
      {"disjointclasses":["http:\/\/purl.obolibrary.org\/obo\/ENVO_00000428","http:\/\/www.w3.org\/2002\/07\/owl#Nothing"]},
      {"disjointclasses":["http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Environment","http:\/\/www.w3.org\/2002\/07\/owl#Nothing"]},
      {"disjointclasses":["http:\/\/purl.obolibrary.org\/obo\/ENVO_00010483","http:\/\/purl.obolibrary.org\/obo\/ENVO_00002297"]},
      {"disjointclasses":["http:\/\/www.w3.org\/2002\/07\/owl#Nothing","http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Taxon"]},
      {"disjointclasses":["http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region","http:\/\/www.w3.org\/2002\/07\/owl#Nothing"]},
      {"disjointclasses":["http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Organism","http:\/\/www.w3.org\/2002\/07\/owl#Nothing"]},
      {"disjointclasses":["http:\/\/purl.org\/dc\/terms\/Location","http:\/\/www.w3.org\/2002\/07\/owl#Nothing"]},
      {"disjointclasses":["http:\/\/www.w3.org\/2002\/07\/owl#Nothing","http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/BioEvent"]},
      {"disjointclasses":["http:\/\/purl.obolibrary.org\/obo\/ENVO_00002297","http:\/\/purl.obolibrary.org\/obo\/ENVO_00000428"]},
      {"disjointclasses":["http:\/\/www.w3.org\/2002\/07\/owl#Nothing","http:\/\/purl.obolibrary.org\/obo\/ENVO_00010483"]},
      {"disjointclasses":["http:\/\/www.w3.org\/2002\/07\/owl#Nothing","http:\/\/rdfs.org\/ns\/void#Dataset"]},
      {"disjointclasses":["http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Occurrence","http:\/\/www.w3.org\/2002\/07\/owl#Nothing"]},
      {"disjointclasses":["http:\/\/www.w3.org\/2002\/07\/owl#Nothing","http:\/\/xmlns.com\/foaf\/0.1\/Agent"]},
      {"disjointclasses":["http:\/\/www.w3.org\/2002\/07\/owl#Nothing","http:\/\/www.w3.org\/2002\/07\/owl#Thing"]},
      {"disjointclasses":["http:\/\/www.w3.org\/2002\/07\/owl#Nothing","http:\/\/rs.tdwg.org\/dwc\/terms\/dwc:Event"]}]},[],[]]';

    $sparqldl = new OntoExtractor();
    $sparqldl->extractor($input);
    $graphaxioms = $sparqldl->getIntermediateSparqldl();

    $actual = $graphaxioms->to_json();

    $this->assertJsonStringEqualsJsonString($actual, json_encode(json_decode($expected)), true);

}*/

}
