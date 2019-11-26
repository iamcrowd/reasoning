<?php
/*

   Copyright 2018

   Author: Giménez, Christian. Braun, Germán

   owllinkdocumenttest.php

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
load("owldocument.php","wicom/translator/documents/");


use Wicom\Translator\Documents\OWLDocument;

class OWL2DocumentTest extends PHPUnit\Framework\TestCase{

    /*    public function testConstructor(){
       $expected = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
       <Ontology
       xmlns=\"http://www.w3.org/2002/07/owl#\"
       xml:base=\"http://crowd.fi.uncoma.edu.ar/kb1/\"
       xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"
       xmlns:xml=\"http://www.w3.org/XML/1998/namespace\"
       xmlns:xsd=\"http://www.w3.org/2001/XMLSchema#\"
       xmlns:rdfs=\"http://www.w3.org/2000/01/rdf-schema#\"
       ontologyIRI=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
       </Ontology>";

       $d = new OWLDocument();
       $d->start_document();
       $d->end_document();
       $actual = $d->to_string();

       $expected = process_xmlspaces($expected);
       $actual = process_xmlspaces($actual);
       $this->assertEqualXMLStructure($expected, $actual, true);
       }

       public function testInsertOWL2ClassDeclaration(){
       $expected = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
       <Ontology
       xmlns=\"http://www.w3.org/2002/07/owl#\"
       xml:base=\"http://crowd.fi.uncoma.edu.ar/kb1/\"
       xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"
       xmlns:xml=\"http://www.w3.org/XML/1998/namespace\"
       xmlns:xsd=\"http://www.w3.org/2001/XMLSchema#\"
       xmlns:rdfs=\"http://www.w3.org/2000/01/rdf-schema#\"
       ontologyIRI=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
       <Declaration>
       <Class IRI=\"Class1\"/>
       </Declaration>
       </Ontology>";

       $d = new OWLDocument();
       $d->start_document(["prefix" => "", "iri" => "http://crowd.fi.uncoma.edu.ar/kb1/"], []);
       $d->insert_class_declaration("Class1");
       $d->end_document();
       $actual = $d->to_string();

       $expected = process_xmlspaces($expected);
       $actual = process_xmlspaces($actual);
       $this->assertEqualXMLStructure($expected, $actual, true);
       }

       public function testInsertOWL2ObjectPropertyDeclaration(){
       $expected = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
       <Ontology
       xmlns=\"http://www.w3.org/2002/07/owl#\"
       xml:base=\"http://crowd.fi.uncoma.edu.ar/kb1/\"
       xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"
       xmlns:xml=\"http://www.w3.org/XML/1998/namespace\"
       xmlns:xsd=\"http://www.w3.org/2001/XMLSchema#\"
       xmlns:rdfs=\"http://www.w3.org/2000/01/rdf-schema#\"
       ontologyIRI=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
       <Declaration>
       <ObjectProperty IRI=\"R1\"/>
       </Declaration>
       </Ontology>";

       $d = new OWLDocument();
       $d->start_document(["prefix" => "", "iri" => "http://crowd.fi.uncoma.edu.ar/kb1/"], []);
       $d->insert_objectProperty_declaration("R1");
       $d->end_document();
       $actual = $d->to_string();

       $expected = process_xmlspaces($expected);
       $actual = process_xmlspaces($actual);
       $this->assertEqualXMLStructure($expected, $actual, true);
       }

     */

    /**
       @testdox Generate an OWL2 Subclass
     */
    public function testOWL2Subclass(){
	$expected = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
        <Ontology
          xmlns=\"http://www.w3.org/2002/07/owl#\"
          xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"
          xmlns:xml=\"http://www.w3.org/XML/1998/namespace\"
          xmlns:xsd=\"http://www.w3.org/2001/XMLSchema#\"
          xmlns:rdfs=\"http://www.w3.org/2000/01/rdf-schema#\"
          xml:base=\"http://crowd.fi.uncoma.edu.ar/kb1/\"
          ontologyIRI=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
          <Prefix name=\"\" IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/\"/>
          <Prefix name=\"rdf\" IRI=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"/>
          <Prefix name=\"rdfs\" IRI=\"http://www.w3.org/2000/01/rdf-schema#\"/>
          <Prefix name=\"xsd\" IRI=\"http://www.w3.org/2001/XMLSchema#\"/>
          <Prefix name=\"owl\" IRI=\"http://www.w3.org/2002/07/owl#\"/>
          <Declaration>
            <Class IRI=\"Class1\"/>
          </Declaration>
          <SubClassOf>
            <Class IRI=\"Class2\"/>
            <Class IRI=\"Class1\"/>
          </SubClassOf>
        </Ontology>";

	$d = new OWLDocument();
	$d->start_document([
            ['prefix' => 'crowd',
             'value' => "http://crowd.fi.uncoma.edu.ar/kb1/"]]);
	$d->set_ontology_prefixes([
	    ["prefix" => "",
	     "value" => "http://crowd.fi.uncoma.edu.ar/kb1/"]
	]);

	$d->insert_class_declaration("Class1");
	//      $d->insert_class_declaration("Class2");
	$d->insert_subclassof("Class2", "Class1");

	$d->end_document();
	$actual = $d->to_string();

	$expected = process_xmlspaces($expected);
	$actual = process_xmlspaces($actual);
	$this->assertEqualXMLStructure($expected, $actual, true);
    }
    /*
       public function testOWL2DisjointClasses(){
       $expected = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
       <Ontology
       xmlns=\"http://www.w3.org/2002/07/owl#\"
       xml:base=\"http://crowd.fi.uncoma.edu.ar/kb1/\"
       xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"
       xmlns:xml=\"http://www.w3.org/XML/1998/namespace\"
       xmlns:xsd=\"http://www.w3.org/2001/XMLSchema#\"
       xmlns:rdfs=\"http://www.w3.org/2000/01/rdf-schema#\"
       ontologyIRI=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
       <Declaration>
       <Class IRI=\"Class1\"/>
       </Declaration>
       <Declaration>
       <Class IRI=\"Class2\"/>
       </Declaration>
       <DisjointClasses>
       <Class IRI=\"Class2\"/>
       <Class IRI=\"Class1\"/>
       </DisjointClasses>
       </Ontology>";

       $d = new OWLDocument();
       $d->start_document(["prefix" => "", "iri" => "http://crowd.fi.uncoma.edu.ar/kb1/"], []);
       $d->insert_class_declaration("Class1");
       $d->insert_class_declaration("Class2");
       $d->begin_disjointclasses();
       $d->insert_class("Class1");
       $d->insert_class("Class2");
       $d->end_disjointclasses();
       $d->end_document();
       $actual = $d->to_string();

       $expected = process_xmlspaces($expected);
       $actual = process_xmlspaces($actual);
       $this->assertEqualXMLStructure($expected, $actual, true);
       }

       public function testOWL2SubobjProp(){
       $expected = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
       <Ontology
       xmlns=\"http://www.w3.org/2002/07/owl#\"
       xml:base=\"http://crowd.fi.uncoma.edu.ar/kb1/\"
       xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"
       xmlns:xml=\"http://www.w3.org/XML/1998/namespace\"
       xmlns:xsd=\"http://www.w3.org/2001/XMLSchema#\"
       xmlns:rdfs=\"http://www.w3.org/2000/01/rdf-schema#\"
       ontologyIRI=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
       <Declaration>
       <ObjectProperty IRI=\"R1\"/>
       </Declaration>
       <Declaration>
       <ObjectProperty IRI=\"R2\"/>
       </Declaration>
       <SubObjectPropertyOf>
       <ObjectProperty IRI=\"R2\"/>
       <ObjectProperty IRI=\"R1\"/>
       </SubObjectPropertyOf>
       </Ontology>";

       $d = new OWLDocument();
       $d->start_document(["prefix" => "", "iri" => "http://crowd.fi.uncoma.edu.ar/kb1/"], []);
       $d->insert_objectProperty_declaration("R1");
       $d->insert_objectProperty_declaration("R2");
       $d->insert_subobjectpropertyof("R2", "R1");
       $d->end_document();
       $actual = $d->to_string();

       $expected = process_xmlspaces($expected);
       $actual = process_xmlspaces($actual);
       $this->assertEqualXMLStructure($expected, $actual, true);
       }

       public function testBioOntoCreateKB(){
       $expected = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
       <Ontology
       xmlns=\"http://www.w3.org/2002/07/owl#\"
       xml:base=\"http://www.cenpat-conicet.gob.ar/bioOnto/\"
       xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"
       xmlns:xml=\"http://www.w3.org/XML/1998/namespace\"
       xmlns:xsd=\"http://www.w3.org/2001/XMLSchema#\"
       xmlns:rdfs=\"http://www.w3.org/2000/01/rdf-schema#\"
       ontologyIRI=\"http://www.cenpat-conicet.gob.ar/bioOnto/\">
       <Prefix name=\"\" IRI=\"http://www.cenpat-conicet.gob.ar/bioOnto/\"/>
       <Prefix name=\"wd\" IRI=\"http://www.wikidata.org/entity/\"/>
       <Prefix name=\"dbr\" IRI=\"http://dbpedia.org/resource/\"/>
       <Prefix name=\"dwc\" IRI=\"http://rs.tdwg.org/dwc/terms/\"/>
       <Prefix name=\"owl\" IRI=\"http://www.w3.org/2002/07/owl#\"/>
       <Prefix name=\"rdf\" IRI=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"/>
       <Prefix name=\"wdt\" IRI=\"http://wikidata.org/prop/direct/\"/>
       <Prefix name=\"xml\" IRI=\"http://www.w3.org/XML/1998/namespace\"/>
       <Prefix name=\"xsd\" IRI=\"http://www.w3.org/2001/XMLSchema#\"/>
       <Prefix name=\"envo\" IRI=\"http://purl.obolibrary.org/obo/\"/>
       <Prefix name=\"foaf\" IRI=\"http://xmlns.com/foaf/0.1/\"/>
       <Prefix name=\"rdfs\" IRI=\"http://www.w3.org/2000/01/rdf-schema#\"/>
       <Prefix name=\"time\" IRI=\"http://www.w3.org/2006/time#\"/>
       <Prefix name=\"void\" IRI=\"http://rdfs.org/ns/void#\"/>
       <Prefix name=\"dcterms\" IRI=\"http://purl.org/dc/terms/\"/>
       <Prefix name=\"geo-ont\" IRI=\"http://www.geonames.org/ontology#\"/>
       <Prefix name=\"geo-pos\" IRI=\"http://www.w3.org/2003/01/geo/wgs84_pos#\"/>
       <Prefix name=\"bio-onto\" IRI=\"http://www.cenpat-conicet.gob.ar/ontology/\"/>
       </Ontology>";

       $d = new OWLDocument();

       $reqiris = [
       ["prefix" => "xmlns", "iri" => "http://www.w3.org/2002/07/owl#"],
       ["prefix" => "xml:base", "iri" => "http://www.cenpat-conicet.gob.ar/bioOnto/"],
       ["prefix" => "xmlns:rdf", "iri" => "http://www.w3.org/1999/02/22-rdf-syntax-ns#"],
       ["prefix" => "xmlns:xml", "iri" => "http://www.w3.org/XML/1998/namespace"],
       ["prefix" => "xmlns:xsd", "iri" => "http://www.w3.org/2001/XMLSchema#"],
       ["prefix" => "xmlns:rdfs", "iri" => "http://www.w3.org/2000/01/rdf-schema#"],
       ];
       $ontoIRI = ["prefix" => "", "iri" => "http://www.cenpat-conicet.gob.ar/bioOnto/"];
       $prefixes = [
       ["prefix" => "", "iri" => "http://www.cenpat-conicet.gob.ar/bioOnto/"],
       ["prefix" => "wd", "iri" => "http://www.wikidata.org/entity/"],
       ["prefix" => "dbr", "iri" => "http://dbpedia.org/resource/"],
       ["prefix" => "dwc", "iri" => "http://rs.tdwg.org/dwc/terms/"],
       ["prefix" => "owl", "iri" => "http://www.w3.org/2002/07/owl#"],
       ["prefix" => "rdf", "iri" => "http://www.w3.org/1999/02/22-rdf-syntax-ns#"],
       ["prefix" => "wdt", "iri" => "http://wikidata.org/prop/direct/"],
       ["prefix" => "xml", "iri" => "http://www.w3.org/XML/1998/namespace"],
       ["prefix" => "xsd", "iri" => "http://www.w3.org/2001/XMLSchema#"],
       ["prefix" => "envo", "iri" => "http://purl.obolibrary.org/obo/"],
       ["prefix" => "foaf", "iri" => "http://xmlns.com/foaf/0.1/"],
       ["prefix" => "rdfs", "iri" => "http://www.w3.org/2000/01/rdf-schema#"],
       ["prefix" => "time", "iri" => "http://www.w3.org/2006/time#"],
       ["prefix" => "void", "iri" => "http://rdfs.org/ns/void#"],
       ["prefix" => "dcterms", "iri" => "http://purl.org/dc/terms/"],
       ["prefix" => "geo-ont", "iri" => "http://www.geonames.org/ontology#"],
       ["prefix" => "geo-pos", "iri" => "http://www.w3.org/2003/01/geo/wgs84_pos#"],
       ["prefix" => "bio-onto", "iri" => "http://www.cenpat-conicet.gob.ar/ontology/"],
       ];

       $d->start_document($ontoIRI, $reqiris);
       $d->insert_prefix($prefixes);
       $d->end_document();

       $actual = $d->to_string();

       $expected = process_xmlspaces($expected);
       $actual = process_xmlspaces($actual);

       $this->assertEqualXMLStructure($expected, $actual, true);
       }

     */

}

?>
