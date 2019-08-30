<?php
/*

   Copyright 2016 Giménez, Christian

   Author: Giménez, Christian

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
load("owllinkdocument.php","wicom/translator/documents/");


use Wicom\Translator\Documents\OWLlinkDocument;

class OWLlinkDocumentTest extends PHPUnit\Framework\TestCase{

    public function testConstructor(){
        $expected = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<RequestMessage xmlns=\"http://www.owllink.org/owllink#\"
		xmlns:owl=\"http://www.w3.org/2002/07/owl#\"
		xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
		xsi:schemaLocation=\"http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd\"
    xml:base=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
</RequestMessage>";

        $d = new OWLlinkDocument();
        $d->start_document();
        $d->end_document();

        $actual = $d->to_string();

        $expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);
        $this->assertEqualXMLStructure($expected, $actual, true);
    }

    public function testInsertCreateKB(){
        $expected = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<RequestMessage xmlns=\"http://www.owllink.org/owllink#\"
		xmlns:owl=\"http://www.w3.org/2002/07/owl#\"
		xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
		xsi:schemaLocation=\"http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd\"
    xml:base=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <CreateKB kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
    <Prefix name=\"\" fullIRI=\"http://crowd.fi.uncoma.edu.ar/kb1/\" />
    <Prefix name=\"rdf\" fullIRI=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"/>
    <Prefix name=\"rdfs\" fullIRI=\"http://www.w3.org/2000/01/rdf-schema#\"/>
    <Prefix name=\"xsd\" fullIRI=\"http://www.w3.org/2001/XMLSchema#\"/>
    <Prefix name=\"owl\" fullIRI=\"http://www.w3.org/2002/07/owl#\"/>
  </CreateKB>
</RequestMessage>";

        $d = new OWLlinkDocument();
        $d->start_document();
        $d->insert_create_kb(["prefix" => "", "iri" => "http://crowd.fi.uncoma.edu.ar/kb1/"]);
        $d->end_document();

        $actual = $d->to_string();

        $expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);
        $this->assertEqualXMLStructure($expected, $actual, true);
    }

    public function testBioOntoCreateKB(){
        $expected = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<RequestMessage xmlns=\"http://www.owllink.org/owllink#\"
    xmlns:owl=\"http://www.w3.org/2002/07/owl#\"
    xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
    xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"
    xmlns:rdfs=\"http://www.w3.org/2000/01/rdf-schema#\"
    xmlns:xml=\"http://www.w3.org/XML/1998/namespace\"
    xsi:schemaLocation=\"http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd\"
    xml:base=\"http://www.cenpat-conicet.gob.ar/bioOnto/\">
  <CreateKB kb=\"http://www.cenpat-conicet.gob.ar/bioOnto/\">
    <Prefix name=\"\" fullIRI=\"http://www.cenpat-conicet.gob.ar/bioOnto/\"/>
    <Prefix name=\"wd\" fullIRI=\"http://www.wikidata.org/entity/\"/>
    <Prefix name=\"dbr\" fullIRI=\"http://dbpedia.org/resource/\"/>
    <Prefix name=\"dwc\" fullIRI=\"http://rs.tdwg.org/dwc/terms/\"/>
    <Prefix name=\"owl\" fullIRI=\"http://www.w3.org/2002/07/owl#\"/>
    <Prefix name=\"rdf\" fullIRI=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"/>
    <Prefix name=\"wdt\" fullIRI=\"http://wikidata.org/prop/direct/\"/>
    <Prefix name=\"xml\" fullIRI=\"http://www.w3.org/XML/1998/namespace\"/>
    <Prefix name=\"xsd\" fullIRI=\"http://www.w3.org/2001/XMLSchema#\"/>
    <Prefix name=\"envo\" fullIRI=\"http://purl.obolibrary.org/obo/\"/>
    <Prefix name=\"foaf\" fullIRI=\"http://xmlns.com/foaf/0.1/\"/>
    <Prefix name=\"rdfs\" fullIRI=\"http://www.w3.org/2000/01/rdf-schema#\"/>
    <Prefix name=\"time\" fullIRI=\"http://www.w3.org/2006/time#\"/>
    <Prefix name=\"void\" fullIRI=\"http://rdfs.org/ns/void#\"/>
    <Prefix name=\"dcterms\" fullIRI=\"http://purl.org/dc/terms/\"/>
    <Prefix name=\"geo-ont\" fullIRI=\"http://www.geonames.org/ontology#\"/>
    <Prefix name=\"geo-pos\" fullIRI=\"http://www.w3.org/2003/01/geo/wgs84_pos#\"/>
    <Prefix name=\"bio-onto\" fullIRI=\"http://www.cenpat-conicet.gob.ar/ontology/\"/>
  </CreateKB>
</RequestMessage>";

        $d = new OWLlinkDocument();

        $reqiris = [
          ["prefix" => "xmlns", "iri" => "http://www.owllink.org/owllink#"],
          ["prefix" => "xmlns:owl", "iri" => "http://www.w3.org/2002/07/owl#"],
          ["prefix" => "xmlns:xsi", "iri" => "http://www.w3.org/2001/XMLSchema-instance"],
          ["prefix" => "xmlns:rdf", "iri" => "http://www.w3.org/1999/02/22-rdf-syntax-ns#"],
          ["prefix" => "xmlns:rdfs", "iri" => "http://www.w3.org/2000/01/rdf-schema#"],
          ["prefix" => "xmlns:xml", "iri" => "http://www.w3.org/XML/1998/namespace"],
          ["prefix" => "xsi:schemaLocation", "iri" => "http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd"],
          ["prefix" => "xml:base", "iri" => "http://www.cenpat-conicet.gob.ar/bioOnto/"],
        ];
        $ontoIRI = ["prefix" => "", "iri" => "http://www.cenpat-conicet.gob.ar/bioOnto/"];
        $prefixes = [
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
        $d->insert_create_kb($ontoIRI, $prefixes);
        $d->end_document();

        $actual = $d->to_string();

        $expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);


        $this->assertEqualXMLStructure($expected, $actual, true);
    }

    public function testClasses(){
        $expected = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<RequestMessage xmlns=\"http://www.owllink.org/owllink#\"
		xmlns:owl=\"http://www.w3.org/2002/07/owl#\"
		xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
		xsi:schemaLocation=\"http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd\"
    xml:base=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
    <CreateKB kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
      <Prefix name=\"\" fullIRI=\"http://crowd.fi.uncoma.edu.ar/kb1/\" />
      <Prefix name=\"rdf\" fullIRI=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"/>
      <Prefix name=\"rdfs\" fullIRI=\"http://www.w3.org/2000/01/rdf-schema#\"/>
      <Prefix name=\"xsd\" fullIRI=\"http://www.w3.org/2001/XMLSchema#\"/>
      <Prefix name=\"owl\" fullIRI=\"http://www.w3.org/2002/07/owl#\"/>
    </CreateKB>
    <Tell kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
        <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/HiWorld\" />
    </Tell>
    <ReleaseKB kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\" />
</RequestMessage>";

        $d = new OWLlinkDocument();
        $d->start_document();
        $d->insert_create_kb(["prefix" => "", "iri" => "http://crowd.fi.uncoma.edu.ar/kb1/"]);
        $d->start_tell();
        $d->insert_class("HiWorld");
        $d->end_tell();
        $d->insert_release_kb(["prefix" => "", "iri" => "http://crowd.fi.uncoma.edu.ar/kb1/"]);
        $d->end_document();

        $actual = $d->to_string();

        $expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);
        $this->assertEqualXMLStructure($expected, $actual, true);
    }

    public function testSubclass(){
        $expected = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<RequestMessage xmlns=\"http://www.owllink.org/owllink#\"
		xmlns:owl=\"http://www.w3.org/2002/07/owl#\"
		xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
		xsi:schemaLocation=\"http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd\"
    xml:base=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
    <CreateKB kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
    <Prefix name=\"\" fullIRI=\"http://crowd.fi.uncoma.edu.ar/kb1/\" />
    <Prefix name=\"rdf\" fullIRI=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"/>
    <Prefix name=\"rdfs\" fullIRI=\"http://www.w3.org/2000/01/rdf-schema#\"/>
    <Prefix name=\"xsd\" fullIRI=\"http://www.w3.org/2001/XMLSchema#\"/>
    <Prefix name=\"owl\" fullIRI=\"http://www.w3.org/2002/07/owl#\"/>
    </CreateKB>
    <Tell kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
       <owl:SubClassOf>
       <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/HiWorld\" />
       <owl:Class IRI=\"http://www.w3.org/2002/07/owl#Thing\" />
       </owl:SubClassOf>
    </Tell>
    <ReleaseKB kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\" />
</RequestMessage>";

        $d = new OWLlinkDocument();
        $d->start_document();
        $d->insert_create_kb(["prefix" => "", "iri" => "http://crowd.fi.uncoma.edu.ar/kb1/"]);
        $d->start_tell();
        $d->insert_subclassof("HiWorld", "owl:Thing");
        $d->end_tell();
        $d->insert_release_kb(["prefix" => "", "iri" => "http://crowd.fi.uncoma.edu.ar/kb1/"]);
        $d->end_document();

        $actual = $d->to_string();

        $expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);
        $this->assertEqualXMLStructure($expected, $actual, true);
    }
}

?>
