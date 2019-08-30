<?php
/*

   Copyright 2016 Giménez, Christian

   Author: Giménez, Christian

   translatortest.php

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
load("translator.php", "wicom/translator/");

use Wicom\Translator\Strategies\Berardi;
use Wicom\Translator\Strategies\UMLcrowd;
use Wicom\Translator\Builders\OWLlinkBuilder;
use Wicom\Translator\Builders\OWLBuilder;
use Wicom\Translator\Translator;

class OWL2TranslatorTest extends PHPUnit\Framework\TestCase
{

    public function test_to_owl2_namesnotfullexpanded(){

        $json = '{"classes":[{"name":"Class7","attrs":[],"methods":[],"position":{"x":20,"y":20}},
                              {"name":"Class8","attrs":[],"methods":[],"position":{"x":363,"y":174}}],
                   "links":[]}';

        $expected =<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Ontology xmlns="http://www.w3.org/2002/07/owl#"
          xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
          xmlns:xml="http://www.w3.org/XML/1998/namespace"
          xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
          xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
          xml:base="http://crowd.fi.uncoma.edu.ar/kb1/"
          ontologyIRI="http://crowd.fi.uncoma.edu.ar/kb1/">
          <Prefix name="" IRI="http://crowd.fi.uncoma.edu.ar/kb1/"/>
          <Prefix name="rdf" IRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
          <Prefix name="rdfs" IRI="http://www.w3.org/2000/01/rdf-schema#"/>
          <Prefix name="xsd" IRI="http://www.w3.org/2001/XMLSchema#"/>
          <Prefix name="owl" IRI="http://www.w3.org/2002/07/owl#"/>
          <SubClassOf>
            <Class IRI="Class7"/>
            <Class abbreviatedIRI="owl:Thing"/>
          </SubClassOf>
          <SubClassOf>
            <Class IRI="Class8"/>
            <Class abbreviatedIRI="owl:Thing"/>
          </SubClassOf>
</Ontology>
XML;

        $strategy = new UMLcrowd();
        $builder = new OWLBuilder();
        $translator = new Translator($strategy, $builder);

        $actual = $translator->to_owl2($json);

        $expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);
        $this->assertEqualXMLStructure($expected, $actual, true);
    }

    public function test_to_owl2_namesfullexpanded(){

        $json = '{"classes":[{"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class7","attrs":[],"methods":[],"position":{"x":20,"y":20}},
                              {"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class8","attrs":[],"methods":[],"position":{"x":363,"y":174}}],
                   "links":[]}';

        $expected =<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<Ontology xmlns="http://www.w3.org/2002/07/owl#"
          xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
          xmlns:xml="http://www.w3.org/XML/1998/namespace"
          xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
          xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
          xml:base="http://crowd.fi.uncoma.edu.ar/kb1/"
          ontologyIRI="http://crowd.fi.uncoma.edu.ar/kb1/">
          <Prefix name="" IRI="http://crowd.fi.uncoma.edu.ar/kb1/"/>
          <Prefix name="rdf" IRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
          <Prefix name="rdfs" IRI="http://www.w3.org/2000/01/rdf-schema#"/>
          <Prefix name="xsd" IRI="http://www.w3.org/2001/XMLSchema#"/>
          <Prefix name="owl" IRI="http://www.w3.org/2002/07/owl#"/>
          <SubClassOf>
            <Class IRI="Class7"/>
            <Class abbreviatedIRI="owl:Thing"/>
          </SubClassOf>
          <SubClassOf>
            <Class IRI="Class8"/>
            <Class abbreviatedIRI="owl:Thing"/>
          </SubClassOf>
</Ontology>
XML;

        $strategy = new UMLcrowd();
        $builder = new OWLBuilder();
        $translator = new Translator($strategy, $builder);

        $actual = $translator->to_owl2($json);

        $expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);
        $this->assertEqualXMLStructure($expected, $actual, true);
    }
}

?>
