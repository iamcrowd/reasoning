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
use Wicom\Translator\Translator;

class TranslatorTest extends PHPUnit\Framework\TestCase
{

    public function test_to_owllink_with_thing(){
        //TODO: Complete JSON!
        $json = <<<EOT
{"namespaces":{"ontologyIRI":[{"prefix":"crowd","value":"http://crowd.uncoma.edu.ar/kb1/"}],
                      "defaultIRIs":[{"prefix":"rdf","value":"http://www.w3.org/1999/02/22-rdf-syntax-ns#"},
                                     {"prefix":"rdfs","value":"http://www.w3.org/2000/01/rdf-schema#"},
                                     {"prefix":"xsd","value":"http://www.w3.org/2001/XMLSchema#"},
                                     {"prefix":"owl","value":"http://www.w3.org/2002/07/owl#"}],
                      "IRIs":[]},"classes": [{"name": "HiWorld", "attrs":[], "methods":[]}], "links" : []}
EOT;
        $expected = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<RequestMessage xmlns=\"http://www.owllink.org/owllink#\"
		xmlns:owl=\"http://www.w3.org/2002/07/owl#\"
		xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"
		xsi:schemaLocation=\"http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd\"
    xml:base=\"http://crowd.uncoma.edu.ar/kb1/\">
<CreateKB kb=\"http://crowd.uncoma.edu.ar/kb1/\">
<Prefix name=\"\" fullIRI=\"http://crowd.uncoma.edu.ar/kb1/\" />
<Prefix name=\"rdf\" fullIRI=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"/>
<Prefix name=\"rdfs\" fullIRI=\"http://www.w3.org/2000/01/rdf-schema#\"/>
<Prefix name=\"xsd\" fullIRI=\"http://www.w3.org/2001/XMLSchema#\"/>
<Prefix name=\"owl\" fullIRI=\"http://www.w3.org/2002/07/owl#\"/>
</CreateKB>
<Set kb=\"http://crowd.uncoma.edu.ar/kb1/\" key=\"abbreviatesIRIs\"><Literal>false</Literal></Set>
<Tell kb=\"http://crowd.uncoma.edu.ar/kb1/\">
  <owl:SubClassOf>
    <owl:Class IRI=\"http://crowd.uncoma.edu.ar/kb1/HiWorld\" />
    <owl:Class IRI=\"http://www.w3.org/2002/07/owl#Thing\" />
  </owl:SubClassOf>
</Tell>
<IsKBSatisfiable kb=\"http://crowd.uncoma.edu.ar/kb1/\" />
<IsClassSatisfiable kb=\"http://crowd.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.uncoma.edu.ar/kb1/HiWorld\" />
</IsClassSatisfiable>
<GetSubClassHierarchy kb=\"http://crowd.uncoma.edu.ar/kb1/\"/>
<GetDisjointClasses kb=\"http://crowd.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.uncoma.edu.ar/kb1/HiWorld\" />
</GetDisjointClasses>
<GetEquivalentClasses kb=\"http://crowd.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.uncoma.edu.ar/kb1/HiWorld\" />
</GetEquivalentClasses>
<GetPrefixes kb=\"http://crowd.uncoma.edu.ar/kb1/\"/>
</RequestMessage>";

        $strategy = new UMLcrowd();
        $builder = new OWLlinkBuilder();
        $translator = new Translator($strategy, $builder);

        $actual = $translator->to_owllink($json);

        $expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);
        $this->assertEqualXMLStructure($expected, $actual, true);
    }

    public function test_to_owlink_with_fullIRIs(){
        //TODO: Complete JSON!
        $json = '{"metadata":[],"graphops":[],"header":[],"prefix":[],"ontologyIRI":[{"prefix":"","iri":"http://crowd.fi.uncoma.edu.ar/kb1/"}],"classes": [{"name": "http://crowd.fi.uncoma.edu.ar/kb1/HiWorld", "attrs":[], "methods":[]}], "links" : []}';
        //TODO: Complete XML!
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
<Set kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\" key=\"abbreviatesIRIs\"><Literal>false</Literal></Set>
<Tell kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:SubClassOf>
    <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/HiWorld\" />
    <owl:Class IRI=\"http://www.w3.org/2002/07/owl#Thing\" />
  </owl:SubClassOf>
</Tell>
<IsKBSatisfiable kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\" />
<IsClassSatisfiable kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/HiWorld\" />
</IsClassSatisfiable>
<GetSubClassHierarchy kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\"/>
<GetDisjointClasses kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/HiWorld\" />
</GetDisjointClasses>
<GetEquivalentClasses kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/HiWorld\" />
</GetEquivalentClasses>
<GetPrefixes kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\"/>
</RequestMessage>";

        $strategy = new UMLcrowd();
        $builder = new OWLlinkBuilder();
        $translator = new Translator($strategy, $builder);

        $actual = $translator->to_owllink($json);

        $expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);
        $this->assertEqualXMLStructure($expected, $actual, true);
    }

    public function test_to_owlink_with_Prefixes_and_Namespaces(){
      $json = <<<EOT
      {"namespaces":{"ontologyIRI":[{"prefix":"crowd","value":"http://crowd.uncoma.edu.ar/kb1/"}],
                    "defaultIRIs":[{"prefix":"rdf","value":"http://www.w3.org/1999/02/22-rdf-syntax-ns#"},
                                   {"prefix":"rdfs","value":"http://www.w3.org/2000/01/rdf-schema#"},
                                   {"prefix":"xsd","value":"http://www.w3.org/2001/XMLSchema#"},
                                   {"prefix":"owl","value":"http://www.w3.org/2002/07/owl#"}],
                    "IRIs":[{"prefix":"ovm","value":"http://ovm/kb/"}]},
      "classes": [{"name": "HiWorld", "attrs":[], "methods":[]},
      {"name": "http://ovm/kb/Class", "attrs":[], "methods":[]}], "links" : []}
EOT;

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
<Prefix name=\"ovm\" fullIRI=\"http://ovm/kb/\" />
</CreateKB>
<Set kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\" key=\"abbreviatesIRIs\"><Literal>false</Literal></Set>
<Tell kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:SubClassOf>
    <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/HiWorld\" />
    <owl:Class IRI=\"http://www.w3.org/2002/07/owl#Thing\" />
  </owl:SubClassOf>
  <owl:SubClassOf>
    <owl:Class IRI=\"http://ovm/kb/Class\" />
    <owl:Class IRI=\"http://www.w3.org/2002/07/owl#Thing\" />
  </owl:SubClassOf>
</Tell>
<IsKBSatisfiable kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\" />
<IsClassSatisfiable kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/HiWorld\" />
</IsClassSatisfiable>
<IsClassSatisfiable kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://ovm/kb/Class\" />
</IsClassSatisfiable>
<GetSubClassHierarchy kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\"/>
<GetDisjointClasses kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/HiWorld\" />
</GetDisjointClasses>
<GetDisjointClasses kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://ovm/kb/Class\" />
</GetDisjointClasses>
<GetEquivalentClasses kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/HiWorld\" />
</GetEquivalentClasses>
<GetEquivalentClasses kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://ovm/kb/Class\" />
</GetEquivalentClasses>
<GetPrefixes kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\"/>
</RequestMessage>";

        $strategy = new UMLcrowd();
        $builder = new OWLlinkBuilder();
        $translator = new Translator($strategy, $builder);

        $actual = $translator->to_owllink($json);

        $expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);
        $this->assertEqualXMLStructure($expected, $actual, true);
    }

    public function test_to_owlink_with_thing_and_sub(){

        $json = '{"metadata":[],"graphops":[],"header":[],"prefix":[],"ontologyIRI":[{"prefix":"","iri":"http://crowd.fi.uncoma.edu.ar/kb1/"}],"classes": [{"name":"MobilePhone", "attrs":[], "methods":[]},
        			 {"name":"Phone", "attrs":[], "methods":[]}],
        "links": [{"classes":["MobilePhone"], "name": "r1", "multiplicity":null,
                   "type":"generalization", "parent" : "Phone", "constraint" : []}]}';

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
    <Set kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\" key=\"abbreviatesIRIs\"><Literal>false</Literal></Set>
<Tell kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:SubClassOf>
    <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/Phone\" />
    <owl:Class IRI=\"http://www.w3.org/2002/07/owl#Thing\" />
  </owl:SubClassOf>
  <owl:SubClassOf>
    <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/MobilePhone\" />
    <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/Phone\" />
  </owl:SubClassOf>
</Tell>
<IsKBSatisfiable kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\" />
<IsClassSatisfiable kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/Phone\" />
</IsClassSatisfiable>
<IsClassSatisfiable kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/MobilePhone\" />
</IsClassSatisfiable>
<GetSubClassHierarchy kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\" />
<GetDisjointClasses kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/Phone\" />
</GetDisjointClasses>
<GetDisjointClasses kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/MobilePhone\" />
</GetDisjointClasses>
<GetEquivalentClasses kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/Phone\" />
</GetEquivalentClasses>
<GetEquivalentClasses kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/MobilePhone\" />
</GetEquivalentClasses>
<GetPrefixes kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\"/>
</RequestMessage>";

        $strategy = new UMLcrowd();
        $builder = new OWLlinkBuilder();
        $translator = new Translator($strategy, $builder);

        $actual = $translator->to_owllink($json);

        $expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);
        $this->assertEqualXMLStructure($expected, $actual, true);
    }


    public function test_to_owlink_with_thing_and_sub_no_compose(){

        $json = '{"metadata":[],"graphops":[],"header":[],"prefix":[],"ontologyIRI":[{"prefix":"","iri":"http://crowd.fi.uncoma.edu.ar/kb1/"}],"classes": [{"name":"MobilePhone", "attrs":[], "methods":[]},
        			 {"name":"Phone", "attrs":[], "methods":[]},
               {"name":"FixedPhone", "attrs":[], "methods":[]}],
        "links": [{"classes":["MobilePhone"], "name": "r1", "multiplicity":null,
                   "type":"generalization", "parent" : "Phone", "constraint" : []},
                   {"classes":["FixedPhone"], "name": "r2", "multiplicity":null,
                              "type":"generalization", "parent" : "Phone", "constraint" : []}]}';

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
    <Set kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\" key=\"abbreviatesIRIs\"><Literal>false</Literal></Set>
<Tell kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:SubClassOf>
    <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/Phone\" />
    <owl:Class IRI=\"http://www.w3.org/2002/07/owl#Thing\" />
  </owl:SubClassOf>
  <owl:SubClassOf>
    <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/MobilePhone\" />
    <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/Phone\" />
  </owl:SubClassOf>
  <owl:SubClassOf>
    <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/FixedPhone\" />
    <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/Phone\" />
  </owl:SubClassOf>
</Tell>
<IsKBSatisfiable kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\" />
<IsClassSatisfiable kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/Phone\" />
</IsClassSatisfiable>
<IsClassSatisfiable kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/MobilePhone\" />
</IsClassSatisfiable>
<IsClassSatisfiable kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/FixedPhone\" />
</IsClassSatisfiable>
<GetSubClassHierarchy kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\" />
<GetDisjointClasses kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/Phone\" />
</GetDisjointClasses>
<GetDisjointClasses kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/MobilePhone\" />
</GetDisjointClasses>
<GetDisjointClasses kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/FixedPhone\" />
</GetDisjointClasses>
<GetEquivalentClasses kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"Phone\" />
</GetEquivalentClasses>
<GetEquivalentClasses kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/MobilePhone\" />
</GetEquivalentClasses>
<GetEquivalentClasses kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/FixedPhone\" />
</GetEquivalentClasses>
<GetPrefixes kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\"/>
</RequestMessage>";

        $strategy = new UMLcrowd();
        $builder = new OWLlinkBuilder();
        $translator = new Translator($strategy, $builder);

        $actual = $translator->to_owllink($json);

        $expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);
        $this->assertEqualXMLStructure($expected, $actual, true);
    }

    public function test_to_owlink_with_thing_and_sub_compose(){

        $json = '{"metadata":[],"graphops":[],"header":[],"prefix":[],"ontologyIRI":[{"prefix":"","iri":"http://crowd.fi.uncoma.edu.ar/kb1/"}],"classes": [{"name":"MobilePhone", "attrs":[], "methods":[]},
        			 {"name":"Phone", "attrs":[], "methods":[]},
               {"name":"FixedPhone", "attrs":[], "methods":[]}],
        "links": [{"classes":["MobilePhone","FixedPhone"], "name": "r1", "multiplicity":null,
                   "type":"generalization", "parent" : "Phone", "constraint" : ["covering"]}]}';

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
    <Set kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\" key=\"abbreviatesIRIs\"><Literal>false</Literal></Set>
<Tell kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:SubClassOf>
    <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/Phone\" />
    <owl:Class IRI=\"http://www.w3.org/2002/07/owl#Thing\" />
  </owl:SubClassOf>
  <owl:SubClassOf>
    <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/MobilePhone\" />
    <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/Phone\" />
  </owl:SubClassOf>
  <owl:SubClassOf>
    <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/FixedPhone\" />
    <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/Phone\" />
  </owl:SubClassOf>
  <owl:SubClassOf>
    <owl:ObjectUnionOf>
        <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/MobilePhone\" />
        <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/FixedPhone\" />
    </owl:ObjectUnionOf>
    <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/Phone\" />
  </owl:SubClassOf>
  <owl:SubClassOf>
    <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/Phone\" />
    <owl:ObjectUnionOf>
        <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/MobilePhone\" />
        <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/FixedPhone\" />
    </owl:ObjectUnionOf>
  </owl:SubClassOf>
</Tell>
<IsKBSatisfiable kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\" />
<IsClassSatisfiable kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/Phone\" />
</IsClassSatisfiable>
<IsClassSatisfiable kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/MobilePhone\" />
</IsClassSatisfiable>
<IsClassSatisfiable kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/FixedPhone\" />
</IsClassSatisfiable>
<GetSubClassHierarchy kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\" />
<GetDisjointClasses kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/Phone\" />
</GetDisjointClasses>
<GetDisjointClasses kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/MobilePhone\" />
</GetDisjointClasses>
<GetDisjointClasses kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/FixedPhone\" />
</GetDisjointClasses>
<GetEquivalentClasses kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/Phone\" />
</GetEquivalentClasses>
<GetEquivalentClasses kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/MobilePhone\" />
</GetEquivalentClasses>
<GetEquivalentClasses kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/FixedPhone\" />
</GetEquivalentClasses>
<GetPrefixes kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\"/>
</RequestMessage>";

        $strategy = new UMLcrowd();
        $builder = new OWLlinkBuilder();
        $translator = new Translator($strategy, $builder);

        $actual = $translator->to_owllink($json);

        $expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);
        $this->assertEqualXMLStructure($expected, $actual, true);
    }


    public function test_JSON_to_OWLlink_with_users_owllink(){
        //TODO: Complete JSON!
        $json = '{"metadata":[],"graphops":[],"header":[],"prefix":[],"ontologyIRI":[{"prefix":"","iri":"http://crowd.fi.uncoma.edu.ar/kb1/"}],"classes": [{"attrs":[], "methods":[], "name": "HiWorld"}], "links" : [], "owllink": ""}';
        //TODO: Complete XML!
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
    <Set kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\" key=\"abbreviatesIRIs\"><Literal>false</Literal></Set>
<Tell kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:SubClassOf>
    <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/HiWorld\" />
    <owl:Class IRI=\"http://www.w3.org/2002/07/owl#Thing\" />
  </owl:SubClassOf>
</Tell>
<IsKBSatisfiable kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\" />
<IsClassSatisfiable kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/HiWorld\" />
</IsClassSatisfiable>
<GetSubClassHierarchy kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\" />
<GetDisjointClasses kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/HiWorld\" />
</GetDisjointClasses>
<GetEquivalentClasses kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
  <owl:Class IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/HiWorld\" />
</GetEquivalentClasses>
<GetPrefixes kb=\"http://crowd.fi.uncoma.edu.ar/kb1/\"/>
</RequestMessage>";

        $strategy = new UMLcrowd();
        $builder = new OWLlinkBuilder();
        $translator = new Translator($strategy, $builder);

        $actual = $translator->to_owllink($json);

        $expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);
        $this->assertEqualXMLStructure($expected, $actual, true);
    }

    public function test_merge_importedJSON_with_OWLlink(){
        //TODO: Complete JSON!
        $json = '{"metadata":[],"graphops":[],"header":[],"prefix":[{"prefix":"","iri":"http:\/\/crowd.fi.uncoma.edu.ar\/kb1\/"}],
        "ontologyIRI":[{"prefix":"","iri":"http:\/\/crowd.fi.uncoma.edu.ar\/kb1\/"}],
        "classes":[{"name":"http:\/\/crowd.fi.uncoma.edu.ar\/kb1\/Class2","attrs":[],"methods":[]},
                   {"name":"http:\/\/crowd.fi.uncoma.edu.ar\/kb1\/Class3","attrs":[],"methods":[]}],
        "links":[{"name":"http:\/\/crowd.fi.uncoma.edu.ar\/kb1\/R",
          "classes":["http:\/\/crowd.fi.uncoma.edu.ar\/kb1\/Class3","http:\/\/www.w3.org\/2002\/07\/owl#Class2"],
          "multiplicity":[null,null],"roles":["",""],"type":"association"}]}';

        $json_str = json_encode(json_decode($json));

        $expected = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#"
  xmlns:owl="http://www.w3.org/2002/07/owl#"
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:schemaLocation="http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd"
  xml:base="http://crowd.fi.uncoma.edu.ar/kb1/">
  <CreateKB kb="http://crowd.fi.uncoma.edu.ar/kb1/">
    <Prefix name="" fullIRI="http://crowd.fi.uncoma.edu.ar/kb1/"/>
    <Prefix name="rdf" fullIRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
    <Prefix name="rdfs" fullIRI="http://www.w3.org/2000/01/rdf-schema#"/>
    <Prefix name="xsd" fullIRI="http://www.w3.org/2001/XMLSchema#"/>
    <Prefix name="owl" fullIRI="http://www.w3.org/2002/07/owl#"/>
  </CreateKB>
  <Set kb="http://crowd.fi.uncoma.edu.ar/kb1/" key="abbreviatesIRIs">
    <Literal>false</Literal>
  </Set>
  <Tell kb="http://crowd.fi.uncoma.edu.ar/kb1/">
    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing"/>
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class3"/>
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing"/>
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:ObjectSomeValuesFrom>
        <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/R"/>
        <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing"/>
      </owl:ObjectSomeValuesFrom>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class3"/>
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:ObjectSomeValuesFrom>
        <owl:ObjectInverseOf>
          <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/R"/>
        </owl:ObjectInverseOf>
        <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing"/>
      </owl:ObjectSomeValuesFrom>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
    </owl:SubClassOf>
    <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class3_http://crowd.fi.uncoma.edu.ar/kb1/R_min"/>
      <owl:ObjectIntersectionOf>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class3"/>
        <owl:ObjectMinCardinality cardinality="1">
          <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/R"/>
        </owl:ObjectMinCardinality>
      </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class3_http://crowd.fi.uncoma.edu.ar/kb1/R_max"/>
      <owl:ObjectIntersectionOf>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class3"/>
        <owl:ObjectMaxCardinality cardinality="1">
          <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/R"/>
        </owl:ObjectMaxCardinality>
      </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2_http://crowd.fi.uncoma.edu.ar/kb1/R_min"/>
      <owl:ObjectIntersectionOf>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
        <owl:ObjectMinCardinality cardinality="1">
          <owl:ObjectInverseOf>
            <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/R"/>
          </owl:ObjectInverseOf>
        </owl:ObjectMinCardinality>
      </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2_http://crowd.fi.uncoma.edu.ar/kb1/R_max"/>
      <owl:ObjectIntersectionOf>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
        <owl:ObjectMaxCardinality cardinality="1">
          <owl:ObjectInverseOf>
            <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/R"/>
          </owl:ObjectInverseOf>
        </owl:ObjectMaxCardinality>
      </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
</Tell>
<IsKBSatisfiable kb="http://crowd.fi.uncoma.edu.ar/kb1/"/>
<IsClassSatisfiable kb="http://crowd.fi.uncoma.edu.ar/kb1/">
  <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
</IsClassSatisfiable>
<IsClassSatisfiable kb="http://crowd.fi.uncoma.edu.ar/kb1/">
  <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class3"/>
</IsClassSatisfiable>
<GetSubClassHierarchy kb="http://crowd.fi.uncoma.edu.ar/kb1/"/>
<GetDisjointClasses kb="http://crowd.fi.uncoma.edu.ar/kb1/">
  <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
</GetDisjointClasses>
<GetDisjointClasses kb="http://crowd.fi.uncoma.edu.ar/kb1/">
  <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class3"/>
</GetDisjointClasses>
<GetEquivalentClasses kb="http://crowd.fi.uncoma.edu.ar/kb1/">
  <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
</GetEquivalentClasses>
<GetEquivalentClasses kb="http://crowd.fi.uncoma.edu.ar/kb1/">
  <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class3"/>
</GetEquivalentClasses>
<GetPrefixes kb="http://crowd.fi.uncoma.edu.ar/kb1/"/>
</RequestMessage>
XML;

        $strategy = new UMLcrowd();
        $builder = new OWLlinkBuilder();
        $translator = new Translator($strategy, $builder);

        $actual = $translator->importedto_owllink($json,[],[],[]);

        $expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);
        $this->assertEqualXMLStructure($actual, $expected, true);
    }

/*
    public function test_importedJSON_to_OWLlink(){
        //TODO: Complete JSON!
        $json = '{"metadata":[],"graphops":[],"header":[],"prefix":[{"prefix":"","iri":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/"},{"prefix":"wd","iri":"http:\/\/www.wikidata.org\/entity\/"},{"prefix":"dbr","iri":"http:\/\/dbpedia.org\/resource\/"},{"prefix":"dwc","iri":"http:\/\/rs.tdwg.org\/dwc\/terms\/"},{"prefix":"owl","iri":"http:\/\/www.w3.org\/2002\/07\/owl#"},{"prefix":"rdf","iri":"http:\/\/www.w3.org\/1999\/02\/22-rdf-syntax-ns#"},{"prefix":"wdt","iri":"http:\/\/wikidata.org\/prop\/direct\/"},{"prefix":"xml","iri":"http:\/\/www.w3.org\/XML\/1998\/namespace"},{"prefix":"xsd","iri":"http:\/\/www.w3.org\/2001\/XMLSchema#"},{"prefix":"envo","iri":"http:\/\/purl.obolibrary.org\/obo\/"},{"prefix":"foaf","iri":"http:\/\/xmlns.com\/foaf\/0.1\/"},{"prefix":"rdfs","iri":"http:\/\/www.w3.org\/2000\/01\/rdf-schema#"},{"prefix":"time","iri":"http:\/\/www.w3.org\/2006\/time#"},{"prefix":"void","iri":"http:\/\/rdfs.org\/ns\/void#"},{"prefix":"dcterms","iri":"http:\/\/purl.org\/dc\/terms\/"},{"prefix":"geo-ont","iri":"http:\/\/www.geonames.org\/ontology#"},{"prefix":"geo-pos","iri":"http:\/\/www.w3.org\/2003\/01\/geo\/wgs84_pos#"},{"prefix":"bio-onto","iri":"http:\/\/www.cenpat-conicet.gob.ar\/ontology\/"}],"ontologyIRI":[{"prefix":"","iri":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/"}],"classes":[{"name":"http:\/\/purl.obolibrary.org\/obo\/ENVO_00000428","attrs":[],"methods":[]},{"name":"http:\/\/purl.obolibrary.org\/obo\/ENVO_00002297","attrs":[],"methods":[]},{"name":"http:\/\/purl.obolibrary.org\/obo\/ENVO_00010483","attrs":[],"methods":[]},{"name":"http:\/\/purl.org\/dc\/terms\/Location","attrs":[{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/maximumDepthInMeters","datatype":""},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimCoordinates","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/locationID","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/waterBody","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/decimalLatitude","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#decimal"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/decimalLongitude","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#decimal"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/locality","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/www.w3.org\/2003\/01\/geo\/wgs84_pos#long","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#decimal"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/stateProvince","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimLatitude","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimEventDate","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimLongitude","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/country","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/minimumDepthInMeters","datatype":""},{"name":"http:\/\/www.w3.org\/2003\/01\/geo\/wgs84_pos#lat","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#decimal"}],"methods":[]},{"name":"http:\/\/rdfs.org\/ns\/void#Dataset","attrs":[{"name":"http:\/\/purl.org\/dc\/terms\/source","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#anyURI"}],"methods":[]},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/Event","attrs":[],"methods":[]},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/Occurrence","attrs":[{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/sex","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/occurrenceRemarks","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/basisOfRecord","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/occurrenceID","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/catalogNumber","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/individualCount","datatype":""},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/collectionCode","datatype":""}],"methods":[]},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/Organism","attrs":[],"methods":[]},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/Taxon","attrs":[{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/genus","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/class","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/specificEpithet","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/family","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/phylum","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/scientificNameAuthorship","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/kingdom","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/scientificName","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/order","datatype":""}],"methods":[]},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/BioEvent","attrs":[{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/eventDate","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#dateTime"}],"methods":[]},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Environment","attrs":[],"methods":[]},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region","attrs":[],"methods":[]},{"name":"http:\/\/xmlns.com\/foaf\/0.1\/Agent","attrs":[],"methods":[]}],"links":[{"name":"g1","classes":["http:\/\/purl.obolibrary.org\/obo\/ENVO_00010483"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Environment","constraint":[]},{"name":"g1","classes":["http:\/\/purl.org\/dc\/terms\/Location"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region","constraint":[]},{"name":"g1","classes":["http:\/\/purl.obolibrary.org\/obo\/ENVO_00000428"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Environment","constraint":[]},{"name":"g1","classes":["http:\/\/purl.obolibrary.org\/obo\/ENVO_00002297"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Environment","constraint":[]},{"name":"g1","classes":["http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/BioEvent"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"http:\/\/rs.tdwg.org\/dwc\/terms\/Event","constraint":[]},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/associated","classes":["http:\/\/rs.tdwg.org\/dwc\/terms\/Occurrence","http:\/\/rs.tdwg.org\/dwc\/terms\/Organism"],"multiplicity":[null,null],"roles":["",""],"type":"association"},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/belongsTo","classes":["http:\/\/rs.tdwg.org\/dwc\/terms\/Organism","http:\/\/rs.tdwg.org\/dwc\/terms\/Taxon"],"multiplicity":[null,null],"roles":["",""],"type":"association"},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/caraterises","classes":["http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Environment","http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region"],"multiplicity":[null,null],"roles":["",""],"type":"association"},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/has_event","classes":["http:\/\/rs.tdwg.org\/dwc\/terms\/Occurrence","http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/BioEvent"],"multiplicity":[null,null],"roles":["",""],"type":"association"},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/has_location","classes":["http:\/\/rs.tdwg.org\/dwc\/terms\/Occurrence","http:\/\/purl.org\/dc\/terms\/Location"],"multiplicity":[null,null],"roles":["",""],"type":"association"},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/memberOf","classes":["http:\/\/rs.tdwg.org\/dwc\/terms\/Occurrence","http:\/\/rdfs.org\/ns\/void#Dataset"],"multiplicity":[null,null],"roles":["",""],"type":"association"},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/recorded_by","classes":["http:\/\/rs.tdwg.org\/dwc\/terms\/Occurrence","http:\/\/xmlns.com\/foaf\/0.1\/Agent"],"multiplicity":[null,null],"roles":["",""],"type":"association"}]}';

        $input =  json_encode(json_decode($json));
        $expected =<<<XML
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#"
  xmlns:owl="http://www.w3.org/2002/07/owl#"
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:schemaLocation="http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd"
  xml:base="http://www.cenpat-conicet.gob.ar/bioOnto/">
  <CreateKB kb="http://www.cenpat-conicet.gob.ar/bioOnto/">
    <Prefix name="" fullIRI="http://www.cenpat-conicet.gob.ar/bioOnto/"/>
    <Prefix name="wd" fullIRI="http://www.wikidata.org/entity/"/>
    <Prefix name="dbr" fullIRI="http://dbpedia.org/resource/"/>
    <Prefix name="dwc" fullIRI="http://rs.tdwg.org/dwc/terms/"/>
    <Prefix name="owl" fullIRI="http://www.w3.org/2002/07/owl#"/>
    <Prefix name="rdf" fullIRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
    <Prefix name="wdt" fullIRI="http://wikidata.org/prop/direct/"/>
    <Prefix name="xml" fullIRI="http://www.w3.org/XML/1998/namespace"/>
    <Prefix name="xsd" fullIRI="http://www.w3.org/2001/XMLSchema#"/>
    <Prefix name="envo" fullIRI="http://purl.obolibrary.org/obo/"/>
    <Prefix name="foaf" fullIRI="http://xmlns.com/foaf/0.1/"/>
    <Prefix name="rdfs" fullIRI="http://www.w3.org/2000/01/rdf-schema#"/>
    <Prefix name="time" fullIRI="http://www.w3.org/2006/time#"/>
    <Prefix name="void" fullIRI="http://rdfs.org/ns/void#"/>
    <Prefix name="dcterms" fullIRI="http://purl.org/dc/terms/"/>
    <Prefix name="geo-ont" fullIRI="http://www.geonames.org/ontology#"/>
    <Prefix name="geo-pos" fullIRI="http://www.w3.org/2003/01/geo/wgs84_pos#"/>
    <Prefix name="bio-onto" fullIRI="http://www.cenpat-conicet.gob.ar/ontology/"/>
    <Prefix name="" fullIRI="http://www.cenpat-conicet.gob.ar/bioOnto/"/>
    </CreateKB>
    <Set kb="http://www.cenpat-conicet.gob.ar/bioOnto/" key="abbreviatesIRIs">
      <Literal>false</Literal>
    </Set>
    <Tell kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:SubClassOf><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Event"/><owl:Class IRI="http://www.w3.org/2002/07/owl#Thing"/></owl:SubClassOf><owl:SubClassOf><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/Environment"/><owl:Class IRI="http://www.w3.org/2002/07/owl#Thing"/></owl:SubClassOf><owl:SubClassOf><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/Region"/><owl:Class IRI="http://www.w3.org/2002/07/owl#Thing"/></owl:SubClassOf><owl:SubClassOf><owl:Class IRI="http://purl.obolibrary.org/obo/ENVO_00010483"/><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/Environment"/></owl:SubClassOf><owl:SubClassOf><owl:Class IRI="http://purl.org/dc/terms/Location"/><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/Region"/></owl:SubClassOf><owl:SubClassOf><owl:Class IRI="http://purl.obolibrary.org/obo/ENVO_00000428"/><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/Environment"/></owl:SubClassOf><owl:SubClassOf><owl:Class IRI="http://purl.obolibrary.org/obo/ENVO_00002297"/><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/Environment"/></owl:SubClassOf><owl:SubClassOf><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/BioEvent"/><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Event"/></owl:SubClassOf><owl:SubClassOf><owl:ObjectSomeValuesFrom><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/associated"/><owl:Class IRI="http://www.w3.org/2002/07/owl#Thing"/></owl:ObjectSomeValuesFrom><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence"/></owl:SubClassOf><owl:SubClassOf><owl:ObjectSomeValuesFrom><owl:ObjectInverseOf><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/associated"/></owl:ObjectInverseOf><owl:Class IRI="http://www.w3.org/2002/07/owl#Thing"/></owl:ObjectSomeValuesFrom><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Organism"/></owl:SubClassOf><owl:EquivalentClasses><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence_http://www.cenpat-conicet.gob.ar/bioOnto/associated_min"/><owl:ObjectIntersectionOf><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence"/><owl:ObjectMinCardinality cardinality="1"><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/associated"/></owl:ObjectMinCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses><owl:EquivalentClasses><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence_http://www.cenpat-conicet.gob.ar/bioOnto/associated_max"/><owl:ObjectIntersectionOf><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence"/><owl:ObjectMaxCardinality cardinality="1"><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/associated"/></owl:ObjectMaxCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses><owl:EquivalentClasses><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Organism_http://www.cenpat-conicet.gob.ar/bioOnto/associated_min"/><owl:ObjectIntersectionOf><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Organism"/><owl:ObjectMinCardinality cardinality="1"><owl:ObjectInverseOf><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/associated"/></owl:ObjectInverseOf></owl:ObjectMinCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses><owl:EquivalentClasses><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Organism_http://www.cenpat-conicet.gob.ar/bioOnto/associated_max"/><owl:ObjectIntersectionOf><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Organism"/><owl:ObjectMaxCardinality cardinality="1"><owl:ObjectInverseOf><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/associated"/></owl:ObjectInverseOf></owl:ObjectMaxCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses><owl:SubClassOf><owl:ObjectSomeValuesFrom><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/belongsTo"/><owl:Class IRI="http://www.w3.org/2002/07/owl#Thing"/></owl:ObjectSomeValuesFrom><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Organism"/></owl:SubClassOf><owl:SubClassOf><owl:ObjectSomeValuesFrom><owl:ObjectInverseOf><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/belongsTo"/></owl:ObjectInverseOf><owl:Class IRI="http://www.w3.org/2002/07/owl#Thing"/></owl:ObjectSomeValuesFrom><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Taxon"/></owl:SubClassOf><owl:EquivalentClasses><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Organism_http://www.cenpat-conicet.gob.ar/bioOnto/belongsTo_min"/><owl:ObjectIntersectionOf><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Organism"/><owl:ObjectMinCardinality cardinality="1"><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/belongsTo"/></owl:ObjectMinCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses><owl:EquivalentClasses><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Organism_http://www.cenpat-conicet.gob.ar/bioOnto/belongsTo_max"/><owl:ObjectIntersectionOf><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Organism"/><owl:ObjectMaxCardinality cardinality="1"><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/belongsTo"/></owl:ObjectMaxCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses><owl:EquivalentClasses><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Taxon_http://www.cenpat-conicet.gob.ar/bioOnto/belongsTo_min"/><owl:ObjectIntersectionOf><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Taxon"/><owl:ObjectMinCardinality cardinality="1"><owl:ObjectInverseOf><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/belongsTo"/></owl:ObjectInverseOf></owl:ObjectMinCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses><owl:EquivalentClasses><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Taxon_http://www.cenpat-conicet.gob.ar/bioOnto/belongsTo_max"/><owl:ObjectIntersectionOf><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Taxon"/><owl:ObjectMaxCardinality cardinality="1"><owl:ObjectInverseOf><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/belongsTo"/></owl:ObjectInverseOf></owl:ObjectMaxCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses><owl:SubClassOf><owl:ObjectSomeValuesFrom><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/caraterises"/><owl:Class IRI="http://www.w3.org/2002/07/owl#Thing"/></owl:ObjectSomeValuesFrom><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/Environment"/></owl:SubClassOf><owl:SubClassOf><owl:ObjectSomeValuesFrom><owl:ObjectInverseOf><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/caraterises"/></owl:ObjectInverseOf><owl:Class IRI="http://www.w3.org/2002/07/owl#Thing"/></owl:ObjectSomeValuesFrom><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/Region"/></owl:SubClassOf><owl:EquivalentClasses><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/Environment_http://www.cenpat-conicet.gob.ar/bioOnto/caraterises_min"/><owl:ObjectIntersectionOf><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/Environment"/><owl:ObjectMinCardinality cardinality="1"><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/caraterises"/></owl:ObjectMinCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses><owl:EquivalentClasses><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/Environment_http://www.cenpat-conicet.gob.ar/bioOnto/caraterises_max"/><owl:ObjectIntersectionOf><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/Environment"/><owl:ObjectMaxCardinality cardinality="1"><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/caraterises"/></owl:ObjectMaxCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses><owl:EquivalentClasses><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/Region_http://www.cenpat-conicet.gob.ar/bioOnto/caraterises_min"/><owl:ObjectIntersectionOf><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/Region"/><owl:ObjectMinCardinality cardinality="1"><owl:ObjectInverseOf><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/caraterises"/></owl:ObjectInverseOf></owl:ObjectMinCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses><owl:EquivalentClasses><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/Region_http://www.cenpat-conicet.gob.ar/bioOnto/caraterises_max"/><owl:ObjectIntersectionOf><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/Region"/><owl:ObjectMaxCardinality cardinality="1"><owl:ObjectInverseOf><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/caraterises"/></owl:ObjectInverseOf></owl:ObjectMaxCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses><owl:SubClassOf><owl:ObjectSomeValuesFrom><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/has_event"/><owl:Class IRI="http://www.w3.org/2002/07/owl#Thing"/></owl:ObjectSomeValuesFrom><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence"/></owl:SubClassOf><owl:SubClassOf><owl:ObjectSomeValuesFrom><owl:ObjectInverseOf><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/has_event"/></owl:ObjectInverseOf><owl:Class IRI="http://www.w3.org/2002/07/owl#Thing"/></owl:ObjectSomeValuesFrom><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/BioEvent"/></owl:SubClassOf><owl:EquivalentClasses><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence_http://www.cenpat-conicet.gob.ar/bioOnto/has_event_min"/><owl:ObjectIntersectionOf><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence"/><owl:ObjectMinCardinality cardinality="1"><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/has_event"/></owl:ObjectMinCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses><owl:EquivalentClasses><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence_http://www.cenpat-conicet.gob.ar/bioOnto/has_event_max"/><owl:ObjectIntersectionOf><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence"/><owl:ObjectMaxCardinality cardinality="1"><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/has_event"/></owl:ObjectMaxCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses><owl:EquivalentClasses><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/BioEvent_http://www.cenpat-conicet.gob.ar/bioOnto/has_event_min"/><owl:ObjectIntersectionOf><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/BioEvent"/><owl:ObjectMinCardinality cardinality="1"><owl:ObjectInverseOf><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/has_event"/></owl:ObjectInverseOf></owl:ObjectMinCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses><owl:EquivalentClasses><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/BioEvent_http://www.cenpat-conicet.gob.ar/bioOnto/has_event_max"/><owl:ObjectIntersectionOf><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/BioEvent"/><owl:ObjectMaxCardinality cardinality="1"><owl:ObjectInverseOf><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/has_event"/></owl:ObjectInverseOf></owl:ObjectMaxCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses><owl:SubClassOf><owl:ObjectSomeValuesFrom><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/has_location"/><owl:Class IRI="http://www.w3.org/2002/07/owl#Thing"/></owl:ObjectSomeValuesFrom><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence"/></owl:SubClassOf><owl:SubClassOf><owl:ObjectSomeValuesFrom><owl:ObjectInverseOf><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/has_location"/></owl:ObjectInverseOf><owl:Class IRI="http://www.w3.org/2002/07/owl#Thing"/></owl:ObjectSomeValuesFrom><owl:Class IRI="http://purl.org/dc/terms/Location"/></owl:SubClassOf><owl:EquivalentClasses><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence_http://www.cenpat-conicet.gob.ar/bioOnto/has_location_min"/><owl:ObjectIntersectionOf><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence"/><owl:ObjectMinCardinality cardinality="1"><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/has_location"/></owl:ObjectMinCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses><owl:EquivalentClasses><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence_http://www.cenpat-conicet.gob.ar/bioOnto/has_location_max"/><owl:ObjectIntersectionOf><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence"/><owl:ObjectMaxCardinality cardinality="1"><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/has_location"/></owl:ObjectMaxCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses><owl:EquivalentClasses><owl:Class IRI="http://purl.org/dc/terms/Location_http://www.cenpat-conicet.gob.ar/bioOnto/has_location_min"/><owl:ObjectIntersectionOf><owl:Class IRI="http://purl.org/dc/terms/Location"/><owl:ObjectMinCardinality cardinality="1"><owl:ObjectInverseOf><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/has_location"/></owl:ObjectInverseOf></owl:ObjectMinCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses><owl:EquivalentClasses><owl:Class IRI="http://purl.org/dc/terms/Location_http://www.cenpat-conicet.gob.ar/bioOnto/has_location_max"/><owl:ObjectIntersectionOf><owl:Class IRI="http://purl.org/dc/terms/Location"/><owl:ObjectMaxCardinality cardinality="1"><owl:ObjectInverseOf><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/has_location"/></owl:ObjectInverseOf></owl:ObjectMaxCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses><owl:SubClassOf><owl:ObjectSomeValuesFrom><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/memberOf"/><owl:Class IRI="http://www.w3.org/2002/07/owl#Thing"/></owl:ObjectSomeValuesFrom><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence"/></owl:SubClassOf><owl:SubClassOf><owl:ObjectSomeValuesFrom><owl:ObjectInverseOf><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/memberOf"/></owl:ObjectInverseOf><owl:Class IRI="http://www.w3.org/2002/07/owl#Thing"/></owl:ObjectSomeValuesFrom><owl:Class IRI="http://rdfs.org/ns/void#Dataset"/></owl:SubClassOf><owl:EquivalentClasses><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence_http://www.cenpat-conicet.gob.ar/bioOnto/memberOf_min"/><owl:ObjectIntersectionOf><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence"/><owl:ObjectMinCardinality cardinality="1"><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/memberOf"/></owl:ObjectMinCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses><owl:EquivalentClasses><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence_http://www.cenpat-conicet.gob.ar/bioOnto/memberOf_max"/><owl:ObjectIntersectionOf><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence"/><owl:ObjectMaxCardinality cardinality="1"><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/memberOf"/></owl:ObjectMaxCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses><owl:EquivalentClasses><owl:Class IRI="http://rdfs.org/ns/void#Dataset_http://www.cenpat-conicet.gob.ar/bioOnto/memberOf_min"/><owl:ObjectIntersectionOf><owl:Class IRI="http://rdfs.org/ns/void#Dataset"/><owl:ObjectMinCardinality cardinality="1"><owl:ObjectInverseOf><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/memberOf"/></owl:ObjectInverseOf></owl:ObjectMinCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses><owl:EquivalentClasses><owl:Class IRI="http://rdfs.org/ns/void#Dataset_http://www.cenpat-conicet.gob.ar/bioOnto/memberOf_max"/><owl:ObjectIntersectionOf><owl:Class IRI="http://rdfs.org/ns/void#Dataset"/><owl:ObjectMaxCardinality cardinality="1"><owl:ObjectInverseOf><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/memberOf"/></owl:ObjectInverseOf></owl:ObjectMaxCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses><owl:SubClassOf><owl:ObjectSomeValuesFrom><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/recorded_by"/><owl:Class IRI="http://www.w3.org/2002/07/owl#Thing"/></owl:ObjectSomeValuesFrom><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence"/></owl:SubClassOf><owl:SubClassOf><owl:ObjectSomeValuesFrom><owl:ObjectInverseOf><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/recorded_by"/></owl:ObjectInverseOf><owl:Class IRI="http://www.w3.org/2002/07/owl#Thing"/></owl:ObjectSomeValuesFrom><owl:Class IRI="http://xmlns.com/foaf/0.1/Agent"/></owl:SubClassOf><owl:EquivalentClasses><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence_http://www.cenpat-conicet.gob.ar/bioOnto/recorded_by_min"/><owl:ObjectIntersectionOf><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence"/><owl:ObjectMinCardinality cardinality="1"><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/recorded_by"/></owl:ObjectMinCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses><owl:EquivalentClasses><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence_http://www.cenpat-conicet.gob.ar/bioOnto/recorded_by_max"/><owl:ObjectIntersectionOf><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence"/><owl:ObjectMaxCardinality cardinality="1"><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/recorded_by"/></owl:ObjectMaxCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses><owl:EquivalentClasses><owl:Class IRI="http://xmlns.com/foaf/0.1/Agent_http://www.cenpat-conicet.gob.ar/bioOnto/recorded_by_min"/><owl:ObjectIntersectionOf><owl:Class IRI="http://xmlns.com/foaf/0.1/Agent"/><owl:ObjectMinCardinality cardinality="1"><owl:ObjectInverseOf><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/recorded_by"/></owl:ObjectInverseOf></owl:ObjectMinCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses><owl:EquivalentClasses><owl:Class IRI="http://xmlns.com/foaf/0.1/Agent_http://www.cenpat-conicet.gob.ar/bioOnto/recorded_by_max"/><owl:ObjectIntersectionOf><owl:Class IRI="http://xmlns.com/foaf/0.1/Agent"/><owl:ObjectMaxCardinality cardinality="1"><owl:ObjectInverseOf><owl:ObjectProperty IRI="http://www.cenpat-conicet.gob.ar/bioOnto/recorded_by"/></owl:ObjectInverseOf></owl:ObjectMaxCardinality></owl:ObjectIntersectionOf></owl:EquivalentClasses></Tell><IsKBSatisfiable kb="http://www.cenpat-conicet.gob.ar/bioOnto/"/><IsClassSatisfiable kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://purl.obolibrary.org/obo/ENVO_00000428"/></IsClassSatisfiable><IsClassSatisfiable kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://purl.obolibrary.org/obo/ENVO_00002297"/></IsClassSatisfiable><IsClassSatisfiable kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://purl.obolibrary.org/obo/ENVO_00010483"/></IsClassSatisfiable><IsClassSatisfiable kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://purl.org/dc/terms/Location"/></IsClassSatisfiable><IsClassSatisfiable kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://rdfs.org/ns/void#Dataset"/></IsClassSatisfiable><IsClassSatisfiable kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Event"/></IsClassSatisfiable><IsClassSatisfiable kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence"/></IsClassSatisfiable><IsClassSatisfiable kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Organism"/></IsClassSatisfiable><IsClassSatisfiable kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Taxon"/></IsClassSatisfiable><IsClassSatisfiable kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/BioEvent"/></IsClassSatisfiable><IsClassSatisfiable kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/Environment"/></IsClassSatisfiable><IsClassSatisfiable kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/Region"/></IsClassSatisfiable><IsClassSatisfiable kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://xmlns.com/foaf/0.1/Agent"/></IsClassSatisfiable><GetSubClassHierarchy kb="http://www.cenpat-conicet.gob.ar/bioOnto/"/><GetDisjointClasses kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://purl.obolibrary.org/obo/ENVO_00000428"/></GetDisjointClasses><GetDisjointClasses kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://purl.obolibrary.org/obo/ENVO_00002297"/></GetDisjointClasses><GetDisjointClasses kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://purl.obolibrary.org/obo/ENVO_00010483"/></GetDisjointClasses><GetDisjointClasses kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://purl.org/dc/terms/Location"/></GetDisjointClasses><GetDisjointClasses kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://rdfs.org/ns/void#Dataset"/></GetDisjointClasses><GetDisjointClasses kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Event"/></GetDisjointClasses><GetDisjointClasses kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence"/></GetDisjointClasses><GetDisjointClasses kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Organism"/></GetDisjointClasses><GetDisjointClasses kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Taxon"/></GetDisjointClasses><GetDisjointClasses kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/BioEvent"/></GetDisjointClasses><GetDisjointClasses kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/Environment"/></GetDisjointClasses><GetDisjointClasses kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/Region"/></GetDisjointClasses><GetDisjointClasses kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://xmlns.com/foaf/0.1/Agent"/></GetDisjointClasses><GetEquivalentClasses kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://purl.obolibrary.org/obo/ENVO_00000428"/></GetEquivalentClasses><GetEquivalentClasses kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://purl.obolibrary.org/obo/ENVO_00002297"/></GetEquivalentClasses><GetEquivalentClasses kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://purl.obolibrary.org/obo/ENVO_00010483"/></GetEquivalentClasses><GetEquivalentClasses kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://purl.org/dc/terms/Location"/></GetEquivalentClasses><GetEquivalentClasses kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://rdfs.org/ns/void#Dataset"/></GetEquivalentClasses><GetEquivalentClasses kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Event"/></GetEquivalentClasses><GetEquivalentClasses kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Occurrence"/></GetEquivalentClasses><GetEquivalentClasses kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Organism"/></GetEquivalentClasses><GetEquivalentClasses kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://rs.tdwg.org/dwc/terms/Taxon"/></GetEquivalentClasses><GetEquivalentClasses kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/BioEvent"/></GetEquivalentClasses><GetEquivalentClasses kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/Environment"/></GetEquivalentClasses><GetEquivalentClasses kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://www.cenpat-conicet.gob.ar/bioOnto/Region"/></GetEquivalentClasses><GetEquivalentClasses kb="http://www.cenpat-conicet.gob.ar/bioOnto/"><owl:Class IRI="http://xmlns.com/foaf/0.1/Agent"/></GetEquivalentClasses><GetPrefixes kb="http://www.cenpat-conicet.gob.ar/bioOnto/"/></RequestMessage>
XML;


$ontologyIRI = ["prefix" => "", "iri" => "http://www.cenpat-conicet.gob.ar/bioOnto/"];
$prefix = [
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

        $strategy = new UMLcrowd();
        $builder = new OWLlinkBuilder();
        $translator = new Translator($strategy, $builder);

        $actual = $translator->importedto_owllink($input, $ontologyIRI,$prefix,[]);

        $expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);
        $this->assertEqualXMLStructure($expected, $actual, true);
    }
    */

}

?>
