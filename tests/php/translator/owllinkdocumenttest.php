<?php
/**
Test the OWLlink document generator class.

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

PHP version >= 7.2

@category Tests
@package  Crowd
@author   Gimenez Christian <christian.gimenez@fi.uncoma.edu.ar>
@author   Germán Braun <german.braun@fi.uncoma.edu.ar>
@author   GILIA <nomail@fi.uncoma.edu.ar>
@license  GPLv3 https://www.gnu.org/licenses/gpl-3.0.html
@link     http://crowd.fi.uncoma.edu.ar
 */

require_once __DIR__ . '/../common.php';
require_once __DIR__ . '/../../../wicom/translator/documents/owllinkdocument.php';

use Wicom\Translator\Documents\OWLlinkDocument;

/**
Test the OWLlink document generator class.

@testdox OWLlink Document class.

@category Tests
@package  Crowd
@author   Gimenez Christian <christian.gimenez@fi.uncoma.edu.ar>
@author   Germán Braun <german.braun@fi.uncoma.edu.ar>
@author   GILIA <nomail@fi.uncoma.edu.ar>
@license  GPLv3 https://www.gnu.org/licenses/gpl-3.0.html
@link     http://crowd.fi.uncoma.edu.ar
 */
class OWLlinkDocumentTest extends PHPUnit\Framework\TestCase
{

    /**
    Can create a base OWLlink document.

    @testdox Can create a base OWLlink document.

    @return Nothing.
     */
    public function testConstructor()
    {
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

    /**
    Can insert a CreateKB tag.

    @testdox Can insert a CreateKB tag.

    @return Nothing.
     */
    public function testInsertCreateKB()
    {
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
        $d->insert_create_kb(
            [['prefix' => 'crowd',
                              'value' => "http://crowd.fi.uncoma.edu.ar/kb1/"]]
        );
        $d->end_document();

        $actual = $d->to_string();

        $expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);
        $this->assertEqualXMLStructure($expected, $actual, true);
    }

    /**
    Can add BioOnto prefixes

    @testdox Can add BioOnto prefixes

    @return Nothing.
     */
    public function testBioOntoCreateKB()
    {
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
            ["prefix" => "xmlns", "value" => "http://www.owllink.org/owllink#"],
            ["prefix" => "xmlns:owl",
             "value" => "http://www.w3.org/2002/07/owl#"],
            ["prefix" => "xmlns:xsi",
             "value" => "http://www.w3.org/2001/XMLSchema-instance"],
            ["prefix" => "xmlns:rdf",
             "value" => "http://www.w3.org/1999/02/22-rdf-syntax-ns#"],
            ["prefix" => "xmlns:rdfs",
             "value" => "http://www.w3.org/2000/01/rdf-schema#"],
            ["prefix" => "xmlns:xml",
             "value" => "http://www.w3.org/XML/1998/namespace"],
            ["prefix" => "xsi:schemaLocation",
             "value" =>
             "http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd"],
            ["prefix" => "xml:base",
             "value" => "http://www.cenpat-conicet.gob.ar/bioOnto/"],
        ];

        $prefixes = [
            ["prefix" => "wd", "value" => "http://www.wikidata.org/entity/"],
            ["prefix" => "dbr", "value" => "http://dbpedia.org/resource/"],
            ["prefix" => "dwc", "value" => "http://rs.tdwg.org/dwc/terms/"],
            ["prefix" => "owl", "value" => "http://www.w3.org/2002/07/owl#"],
            ["prefix" => "rdf",
             "value" => "http://www.w3.org/1999/02/22-rdf-syntax-ns#"],
            ["prefix" => "wdt", "value" => "http://wikidata.org/prop/direct/"],
            ["prefix" => "xml", "value" => "http://www.w3.org/XML/1998/namespace"],
            ["prefix" => "xsd", "value" => "http://www.w3.org/2001/XMLSchema#"],
            ["prefix" => "envo", "value" => "http://purl.obolibrary.org/obo/"],
            ["prefix" => "foaf", "value" => "http://xmlns.com/foaf/0.1/"],
            ["prefix" => "rdfs",
             "value" => "http://www.w3.org/2000/01/rdf-schema#"],
            ["prefix" => "time", "value" => "http://www.w3.org/2006/time#"],
            ["prefix" => "void", "value" => "http://rdfs.org/ns/void#"],
            ["prefix" => "dcterms", "value" => "http://purl.org/dc/terms/"],
            ["prefix" => "geo-ont",
             "value" => "http://www.geonames.org/ontology#"],
            ["prefix" => "geo-pos",
             "value" => "http://www.w3.org/2003/01/geo/wgs84_pos#"],
            ["prefix" => "bio-onto",
             "value" => "http://www.cenpat-conicet.gob.ar/ontology/"],
        ];

        $ontoIRI = [['prefix' => 'bio-onto',
                     'value' => "http://www.cenpat-conicet.gob.ar/bioOnto/"]];

        $d->start_document($ontoIRI, $reqiris);
        $d->insert_create_kb($ontoIRI, $prefixes);
        $d->end_document();

        $actual = $d->to_string();

        $expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);


        $this->assertEqualXMLStructure($expected, $actual, true);
    }

    /**
    Can insert some classes.

    @testdox Can insert some classes.

    @return Nothing.
     */
    public function testClasses()
    {
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
        $d->insert_create_kb(
            [['prefix' => 'crowd',
              'value' => "http://crowd.fi.uncoma.edu.ar/kb1/"]]
        );
        $d->start_tell();
        $d->insert_class("HiWorld");
        $d->end_tell();
        $d->insert_release_kb("http://crowd.fi.uncoma.edu.ar/kb1/");
        $d->end_document();

        $actual = $d->to_string();

        $expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);
        $this->assertEqualXMLStructure($expected, $actual, true);
    }

    /**
    Can insert subclass relationships

    @testdox Can insert subclass relationships

    @return Nothing.
     */
    public function testSubclass()
    {
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
        $d->insert_create_kb(
            [['prefix' => 'crowd',
              'value' => "http://crowd.fi.uncoma.edu.ar/kb1/"]]
        );
        $d->start_tell();
        $d->insert_subclassof("HiWorld", "owl:Thing");
        $d->end_tell();
        $d->insert_release_kb("http://crowd.fi.uncoma.edu.ar/kb1/");
        $d->end_document();

        $actual = $d->to_string();

        $expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);
        $this->assertEqualXMLStructure($expected, $actual, true);
    }
}

?>
