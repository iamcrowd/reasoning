<?php
/*

   Copyright 2018 GILIA

   Author: GILIA

   decodertest.php

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

  /*
  public function testToJsonClassUML(){

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
              <Class IRI="http://crowd.fi.uncoma.edu.ar#Class5"/>
            </Declaration>
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

      $this->assertJsonStringEqualsJsonString($actual, $expected, true);
  }


    public function testToJsonSubClassUML(){

        $json_iri =<<<EOT
        {"namespaces":{"ontologyIRI":[{"prefix":"crowd","value":"http://crowd.fi.uncoma.edu.ar/"}],
                       "defaultIRIs":[{"prefix":"rdf","value":"http://www.w3.org/1999/02/22-rdf-syntax-ns#"},
                                      {"prefix":"rdfs","value":"http://www.w3.org/2000/01/rdf-schema#"},
                                      {"prefix":"xsd","value":"http://www.w3.org/2001/XMLSchema#"},
                                      {"prefix":"owl","value":"http://www.w3.org/2002/07/owl#"}],
                        "IRIs":[]},
                        "classes":[{"name":"http://crowd.fi.uncoma.edu.ar#Class1","attrs":[],"methods":[]},
                                   {"name":"http://crowd.fi.uncoma.edu.ar#Class3","attrs":[],"methods":[]}],
                        "links":[{"name":"http://crowd.fi.uncoma.edu.ar#s1",
                                  "parent":"http://crowd.fi.uncoma.edu.ar#Class1",
                                  "classes":["http://crowd.fi.uncoma.edu.ar#Class3"],
                                  "multiplicity":null,
                                  "roles":null,
                                  "type":"generalization",
                                  "constraint":[]}]}
EOT;

        $expected = json_encode(json_decode($json_iri));

        $owl2 = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
  <Ontology xmlns="http://www.w3.org/2002/07/owl#"
            xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
            xmlns:xml="http://www.w3.org/XML/1998/namespace"
            xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
            xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
            xml:base="http://crowd.fi.uncoma.edu.ar#"
            ontologyIRI="http://crowd.fi.uncoma.edu.ar/">
            <Prefix name="rdf" IRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
            <Prefix name="rdfs" IRI="http://www.w3.org/2000/01/rdf-schema#"/>
            <Prefix name="xsd" IRI="http://www.w3.org/2001/XMLSchema#"/>
            <Prefix name="owl" IRI="http://www.w3.org/2002/07/owl#"/>
            <Prefix name="crowd" IRI="http://crowd.fi.uncoma.edu.ar#"/>
            <Declaration>
              <Class IRI="http://crowd.fi.uncoma.edu.ar#Class1"/>
            </Declaration>
            <Declaration>
              <Class IRI="http://crowd.fi.uncoma.edu.ar#Class3"/>
            </Declaration>
            <SubClassOf>
              <Class IRI="http://crowd.fi.uncoma.edu.ar#Class3"/>
              <Class IRI="http://crowd.fi.uncoma.edu.ar#Class1"/>
            </SubClassOf>
  </Ontology>
XML;

      $strategy = new UMLcrowd();
      $jsonbuilder = new UMLJSONBuilder();
      $decoder = new Decoder($strategy, $jsonbuilder);

      $ontologyIRI = ["prefix" => "crowd", "value" => "http://crowd.fi.uncoma.edu.ar/"];
      $prefix = [["prefix" => "rdf", "value" => "http://www.w3.org/1999/02/22-rdf-syntax-ns#"],
                 ["prefix" => "rdfs","value" => "http://www.w3.org/2000/01/rdf-schema#"],
                 ["prefix" => "xsd","value" => "http://www.w3.org/2001/XMLSchema#"],
                 ["prefix" => "owl","value" => "http://www.w3.org/2002/07/owl#"]];

      $actual = $decoder->to_json($owl2, $ontologyIRI, $prefix);

      $this->assertJsonStringEqualsJsonString($actual, $expected, true);
    }


    public function testToJson0NAssocUML(){

        $json ='{"classes":[{"name":"Class1","attrs":[],"methods":[]},{"name":"Class2","attrs":[],"methods":[]},{"name":"Class3","attrs":[],"methods":[]}],
"links":[{"name":"g1","classes":["Class3"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"Class1","constraint":[]},
{"name":"R","classes":["Class3","Class2"],"multiplicity":[null,null],"roles":["",""],"type":"association"}]}';

        $json_iri =<<<EOT
{"metadata": [], "graphops": [], "header": [],
  "prefix": [{"prefix":"","iri":"http://crowd.fi.uncoma.edu.ar/kb1/"}],
  "ontologyIRI": [{"prefix":"", "iri":"http://crowd.fi.uncoma.edu.ar/kb1/"}],
  "classes":[{"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class1","attrs":[],"methods":[]},
          {"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class2","attrs":[],"methods":[]},
          {"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class3","attrs":[],"methods":[]}],
  "links":[{"name":"http://crowd.fi.uncoma.edu.ar/kb1/g1","classes":["http://crowd.fi.uncoma.edu.ar/kb1/Class3"],
            "multiplicity":null,"roles":[null,null],
            "type":"generalization",
            "parent":"http://crowd.fi.uncoma.edu.ar/kb1/Class1","constraint":[]},
            {"name":"http://crowd.fi.uncoma.edu.ar/kb1/R","classes":["http://crowd.fi.uncoma.edu.ar/kb1/Class3","http://crowd.fi.uncoma.edu.ar/kb1/Class2"],
              "multiplicity":[null,null],"roles":["",""],
              "type":"association"}]}
EOT;

        $expected = json_encode(json_decode($json_iri));

        $owl2 = <<<XML
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
        <ObjectSomeValuesFrom>
          <ObjectInverseOf>
            <ObjectProperty IRI="R"/>
          </ObjectInverseOf>
          <Class abbreviatedIRI="owl:Thing"/>
        </ObjectSomeValuesFrom>
        <Class IRI="Class2"/>
      </SubClassOf>
      <EquivalentClasses>
        <Class IRI="Class3_R_min"/>
          <ObjectIntersectionOf>
            <Class IRI="Class3"/>
            <ObjectMinCardinality cardinality="1">
              <ObjectProperty IRI="R"/>
            </ObjectMinCardinality>
          </ObjectIntersectionOf>
      </EquivalentClasses>
      <EquivalentClasses>
        <Class IRI="Class3_R_max"/>
        <ObjectIntersectionOf>
          <Class IRI="Class3"/>
          <ObjectMaxCardinality cardinality="1">
            <ObjectProperty IRI="R"/>
          </ObjectMaxCardinality>
        </ObjectIntersectionOf>
      </EquivalentClasses>
      <EquivalentClasses>
        <Class IRI="Class2_R_min"/>
        <ObjectIntersectionOf>
          <Class IRI="Class2"/>
          <ObjectMinCardinality cardinality="1">
            <ObjectInverseOf>
              <ObjectProperty IRI="R"/>
            </ObjectInverseOf>
          </ObjectMinCardinality>
        </ObjectIntersectionOf>
      </EquivalentClasses>
      <EquivalentClasses>
        <Class IRI="Class2_R_max"/>
        <ObjectIntersectionOf>
          <Class IRI="Class2"/>
          <ObjectMaxCardinality cardinality="1">
            <ObjectInverseOf>
              <ObjectProperty IRI="R"/>
            </ObjectInverseOf>
          </ObjectMaxCardinality>
        </ObjectIntersectionOf>
      </EquivalentClasses>
   </Ontology>
XML;

      $strategy = new UMLcrowd();
      $jsonbuilder = new UMLJSONBuilder();
      $decoder = new Decoder($strategy, $jsonbuilder);

      $ontologyIRI = ["prefix" => "", "iri" => "http://crowd.fi.uncoma.edu.ar/kb1/"];
      $prefix = [["prefix" => "", "iri" => "http://crowd.fi.uncoma.edu.ar/kb1/"]];

      $actual = $decoder->to_json($owl2, $ontologyIRI, $prefix);

      $this->assertJsonStringEqualsJsonString($actual, $expected, true);
    }


    public function testToJson1NAssocUML(){

        $json_iri =<<<EOT
{"metadata": [], "graphops": [], "header": [],
          "prefix": [{"prefix":"","iri":"http://crowd.fi.uncoma.edu.ar/kb1/"}],
          "ontologyIRI": [{"prefix":"", "iri":"http://crowd.fi.uncoma.edu.ar/kb1/"}],
"classes":[{"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class1","attrs":[],"methods":[]},
        {"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class2","attrs":[],"methods":[]},
        {"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class3","attrs":[],"methods":[]}],
"links":[
          {"name":"http://crowd.fi.uncoma.edu.ar/kb1/g1","classes":["http://crowd.fi.uncoma.edu.ar/kb1/Class3"],"multiplicity":null,"roles":[null,null],
            "type":"generalization","parent":"http://crowd.fi.uncoma.edu.ar/kb1/Class1","constraint":[]},
          {"name":"http://crowd.fi.uncoma.edu.ar/kb1/R","classes":["http://crowd.fi.uncoma.edu.ar/kb1/Class3","http://crowd.fi.uncoma.edu.ar/kb1/Class2"],
            "multiplicity":[null,"1..*"],"roles":["",""],"type":"association"}]}
EOT;

        $expected = json_encode(json_decode($json_iri));

        $owl2 = <<<XML
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
        <ObjectSomeValuesFrom>
          <ObjectInverseOf>
            <ObjectProperty IRI="R"/>
          </ObjectInverseOf>
          <Class abbreviatedIRI="owl:Thing"/>
        </ObjectSomeValuesFrom>
        <Class IRI="Class2"/>
      </SubClassOf>
      <EquivalentClasses>
        <Class IRI="Class3_R_min"/>
          <ObjectIntersectionOf>
            <Class IRI="Class3"/>
            <ObjectMinCardinality cardinality="1">
              <ObjectProperty IRI="R"/>
            </ObjectMinCardinality>
          </ObjectIntersectionOf>
      </EquivalentClasses>
      <EquivalentClasses>
        <Class IRI="Class3_R_max"/>
        <ObjectIntersectionOf>
          <Class IRI="Class3"/>
          <ObjectMaxCardinality cardinality="1">
            <ObjectProperty IRI="R"/>
          </ObjectMaxCardinality>
        </ObjectIntersectionOf>
      </EquivalentClasses>
      <EquivalentClasses>
        <Class IRI="Class2_R_min"/>
        <ObjectIntersectionOf>
          <Class IRI="Class2"/>
          <ObjectMinCardinality cardinality="1">
            <ObjectInverseOf>
              <ObjectProperty IRI="R"/>
            </ObjectInverseOf>
          </ObjectMinCardinality>
        </ObjectIntersectionOf>
      </EquivalentClasses>
      <EquivalentClasses>
        <Class IRI="Class2_R_max"/>
        <ObjectIntersectionOf>
          <Class IRI="Class2"/>
          <ObjectMaxCardinality cardinality="1">
            <ObjectInverseOf>
              <ObjectProperty IRI="R"/>
            </ObjectInverseOf>
          </ObjectMaxCardinality>
        </ObjectIntersectionOf>
      </EquivalentClasses>
   </Ontology>
XML;

      $strategy = new UMLcrowd();
      $jsonbuilder = new UMLJSONBuilder();
      $decoder = new Decoder($strategy, $jsonbuilder);

      $ontologyIRI = ["prefix" => "", "iri" => "http://crowd.fi.uncoma.edu.ar/kb1/"];
      $prefix = [["prefix" => "", "iri" => "http://crowd.fi.uncoma.edu.ar/kb1/"]];

      $actual = $decoder->to_json($owl2, $ontologyIRI, $prefix);

      $this->assertJsonStringEqualsJsonString($actual, $expected, true);
    }


    public function testToJson01AssocUML(){

        $json_iri =<<<EOT
{"metadata": [], "graphops": [], "header": [],
                  "prefix": [{"prefix":"","iri":"http://crowd.fi.uncoma.edu.ar/kb1/"}],
                  "ontologyIRI": [{"prefix":"", "iri":"http://crowd.fi.uncoma.edu.ar/kb1/"}],
"classes":[{"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class1","attrs":[],"methods":[]},
        {"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class2","attrs":[],"methods":[]},{"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class3","attrs":[],"methods":[]}],
"links":[{"name":"http://crowd.fi.uncoma.edu.ar/kb1/g1","classes":["http://crowd.fi.uncoma.edu.ar/kb1/Class3"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"http://crowd.fi.uncoma.edu.ar/kb1/Class1","constraint":[]},
{"name":"http://crowd.fi.uncoma.edu.ar/kb1/R","classes":["http://crowd.fi.uncoma.edu.ar/kb1/Class3","http://crowd.fi.uncoma.edu.ar/kb1/Class2"],"multiplicity":[null,"0..1"],"roles":["",""],"type":"association"}]}
EOT;

        $expected = json_encode(json_decode($json_iri));

        $owl2 = <<<XML
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
        <ObjectMaxCardinality cardinality="1">
          <ObjectProperty IRI="R"/>
        </ObjectMaxCardinality>
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
      <EquivalentClasses>
        <Class IRI="Class3_R_min"/>
          <ObjectIntersectionOf>
            <Class IRI="Class3"/>
            <ObjectMinCardinality cardinality="1">
              <ObjectProperty IRI="R"/>
            </ObjectMinCardinality>
          </ObjectIntersectionOf>
      </EquivalentClasses>
      <EquivalentClasses>
        <Class IRI="Class3_R_max"/>
        <ObjectIntersectionOf>
          <Class IRI="Class3"/>
          <ObjectMaxCardinality cardinality="1">
            <ObjectProperty IRI="R"/>
          </ObjectMaxCardinality>
        </ObjectIntersectionOf>
      </EquivalentClasses>
      <EquivalentClasses>
        <Class IRI="Class2_R_min"/>
        <ObjectIntersectionOf>
          <Class IRI="Class2"/>
          <ObjectMinCardinality cardinality="1">
            <ObjectInverseOf>
              <ObjectProperty IRI="R"/>
            </ObjectInverseOf>
          </ObjectMinCardinality>
        </ObjectIntersectionOf>
      </EquivalentClasses>
      <EquivalentClasses>
        <Class IRI="Class2_R_max"/>
        <ObjectIntersectionOf>
          <Class IRI="Class2"/>
          <ObjectMaxCardinality cardinality="1">
            <ObjectInverseOf>
              <ObjectProperty IRI="R"/>
            </ObjectInverseOf>
          </ObjectMaxCardinality>
        </ObjectIntersectionOf>
      </EquivalentClasses>
   </Ontology>
XML;

      $strategy = new UMLcrowd();
      $jsonbuilder = new UMLJSONBuilder();
      $decoder = new Decoder($strategy, $jsonbuilder);

      $ontologyIRI = ["prefix" => "", "iri" => "http://crowd.fi.uncoma.edu.ar/kb1/"];
      $prefix = [["prefix" => "", "iri" => "http://crowd.fi.uncoma.edu.ar/kb1/"]];

      $actual = $decoder->to_json($owl2, $ontologyIRI, $prefix);

      $this->assertJsonStringEqualsJsonString($actual, $expected, true);
    }


    public function testToJson11AssocUML(){

        $json_iri =<<<EOT
{"metadata": [], "graphops": [], "header": [],
                          "prefix": [{"prefix":"","iri":"http://crowd.fi.uncoma.edu.ar/kb1/"}],
                          "ontologyIRI": [{"prefix":"", "iri":"http://crowd.fi.uncoma.edu.ar/kb1/"}],
"classes":[{"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class1","attrs":[],"methods":[]},
        {"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class2","attrs":[],"methods":[]},{"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class3","attrs":[],"methods":[]}],
"links":[{"name":"http://crowd.fi.uncoma.edu.ar/kb1/g1","classes":["http://crowd.fi.uncoma.edu.ar/kb1/Class3"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"http://crowd.fi.uncoma.edu.ar/kb1/Class1","constraint":[]},
{"name":"http://crowd.fi.uncoma.edu.ar/kb1/R","classes":["http://crowd.fi.uncoma.edu.ar/kb1/Class3","http://crowd.fi.uncoma.edu.ar/kb1/Class2"],"multiplicity":[null,"1..1"],"roles":["",""],"type":"association"}]}
EOT;

        $expected = json_encode(json_decode($json_iri));

        $owl2 = <<<XML
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
        <ObjectMaxCardinality cardinality="1">
          <ObjectProperty IRI="R"/>
        </ObjectMaxCardinality>
      </SubClassOf>
      <SubClassOf>
        <Class IRI="Class3"/>
        <ObjectMinCardinality cardinality="1">
          <ObjectProperty IRI="R"/>
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
      <EquivalentClasses>
        <Class IRI="Class3_R_min"/>
          <ObjectIntersectionOf>
            <Class IRI="Class3"/>
            <ObjectMinCardinality cardinality="1">
              <ObjectProperty IRI="R"/>
            </ObjectMinCardinality>
          </ObjectIntersectionOf>
      </EquivalentClasses>
      <EquivalentClasses>
        <Class IRI="Class3_R_max"/>
        <ObjectIntersectionOf>
          <Class IRI="Class3"/>
          <ObjectMaxCardinality cardinality="1">
            <ObjectProperty IRI="R"/>
          </ObjectMaxCardinality>
        </ObjectIntersectionOf>
      </EquivalentClasses>
      <EquivalentClasses>
        <Class IRI="Class2_R_min"/>
        <ObjectIntersectionOf>
          <Class IRI="Class2"/>
          <ObjectMinCardinality cardinality="1">
            <ObjectInverseOf>
              <ObjectProperty IRI="R"/>
            </ObjectInverseOf>
          </ObjectMinCardinality>
        </ObjectIntersectionOf>
      </EquivalentClasses>
      <EquivalentClasses>
        <Class IRI="Class2_R_max"/>
        <ObjectIntersectionOf>
          <Class IRI="Class2"/>
          <ObjectMaxCardinality cardinality="1">
            <ObjectInverseOf>
              <ObjectProperty IRI="R"/>
            </ObjectInverseOf>
          </ObjectMaxCardinality>
        </ObjectIntersectionOf>
      </EquivalentClasses>
   </Ontology>
XML;

      $strategy = new UMLcrowd();
      $jsonbuilder = new UMLJSONBuilder();
      $decoder = new Decoder($strategy, $jsonbuilder);

      $ontologyIRI = ["prefix" => "", "iri" => "http://crowd.fi.uncoma.edu.ar/kb1/"];
      $prefix = [["prefix" => "", "iri" => "http://crowd.fi.uncoma.edu.ar/kb1/"]];

      $actual = $decoder->to_json($owl2, $ontologyIRI, $prefix);

      $this->assertJsonStringEqualsJsonString($actual, $expected, true);
    }


    public function testToJson11AssocUMLComplete(){

        $json_iri =<<<EOT
{"metadata": [], "graphops": [], "header": [],
        "prefix": [{"prefix":"","iri":"http://crowd.fi.uncoma.edu.ar/kb1/"}],
        "ontologyIRI": [{"prefix":"", "iri":"http://crowd.fi.uncoma.edu.ar/kb1/"}],
"classes":[{"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class1","attrs":[],"methods":[]},{"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class2","attrs":[],"methods":[]},
        {"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class3","attrs":[],"methods":[]}],
"links":[{"name":"http://crowd.fi.uncoma.edu.ar/kb1/g1","classes":["http://crowd.fi.uncoma.edu.ar/kb1/Class3"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"http://crowd.fi.uncoma.edu.ar/kb1/Class1","constraint":[]},
{"name":"http://crowd.fi.uncoma.edu.ar/kb1/R","classes":["http://crowd.fi.uncoma.edu.ar/kb1/Class3","http://crowd.fi.uncoma.edu.ar/kb1/Class2"],"multiplicity":["1..1","1..1"],"roles":["",""],"type":"association"}]}
EOT;

        $expected = json_encode(json_decode($json_iri));

        $owl2 = <<<XML
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
        <ObjectMaxCardinality cardinality="1">
          <ObjectProperty IRI="R"/>
        </ObjectMaxCardinality>
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
        <Class IRI="Class2"/>
        <ObjectMaxCardinality cardinality="1">
          <ObjectInverseOf>
            <ObjectProperty IRI="R"/>
          </ObjectInverseOf>
        </ObjectMaxCardinality>
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
      <EquivalentClasses>
        <Class IRI="Class3_R_min"/>
          <ObjectIntersectionOf>
            <Class IRI="Class3"/>
            <ObjectMinCardinality cardinality="1">
              <ObjectProperty IRI="R"/>
            </ObjectMinCardinality>
          </ObjectIntersectionOf>
      </EquivalentClasses>
      <EquivalentClasses>
        <Class IRI="Class3_R_max"/>
        <ObjectIntersectionOf>
          <Class IRI="Class3"/>
          <ObjectMaxCardinality cardinality="1">
            <ObjectProperty IRI="R"/>
          </ObjectMaxCardinality>
        </ObjectIntersectionOf>
      </EquivalentClasses>
      <EquivalentClasses>
        <Class IRI="Class2_R_min"/>
        <ObjectIntersectionOf>
          <Class IRI="Class2"/>
          <ObjectMinCardinality cardinality="1">
            <ObjectInverseOf>
              <ObjectProperty IRI="R"/>
            </ObjectInverseOf>
          </ObjectMinCardinality>
        </ObjectIntersectionOf>
      </EquivalentClasses>
      <EquivalentClasses>
        <Class IRI="Class2_R_max"/>
        <ObjectIntersectionOf>
          <Class IRI="Class2"/>
          <ObjectMaxCardinality cardinality="1">
            <ObjectInverseOf>
              <ObjectProperty IRI="R"/>
            </ObjectInverseOf>
          </ObjectMaxCardinality>
        </ObjectIntersectionOf>
      </EquivalentClasses>
   </Ontology>
XML;

      $strategy = new UMLcrowd();
      $jsonbuilder = new UMLJSONBuilder();
      $decoder = new Decoder($strategy, $jsonbuilder);

      $ontologyIRI = ["prefix" => "", "iri" => "http://crowd.fi.uncoma.edu.ar/kb1/"];
      $prefix = [["prefix" => "", "iri" => "http://crowd.fi.uncoma.edu.ar/kb1/"]];

      $actual = $decoder->to_json($owl2, $ontologyIRI, $prefix);

      $this->assertJsonStringEqualsJsonString($actual, $expected, true);
    }



    public function testToJsonAssocsUMLSameDomain(){

        $json_iri =<<<EOT
{"metadata": [], "graphops": [], "header": [],
                "prefix": [{"prefix":"","iri":"http://crowd.fi.uncoma.edu.ar/kb1/"}],
                "ontologyIRI": [{"prefix":"", "iri":"http://crowd.fi.uncoma.edu.ar/kb1/"}],
"classes":[{"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class1","attrs":[],"methods":[]},
        {"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class2","attrs":[],"methods":[]},
        {"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class3","attrs":[],"methods":[]}],
        "links":[{"name":"http://crowd.fi.uncoma.edu.ar/kb1/R1","classes":["http://crowd.fi.uncoma.edu.ar/kb1/Class1","http://crowd.fi.uncoma.edu.ar/kb1/Class2"],"multiplicity":[null,null],"roles":["",""],"type":"association"},
        {"name":"http://crowd.fi.uncoma.edu.ar/kb1/R2","classes":["http://crowd.fi.uncoma.edu.ar/kb1/Class1","http://crowd.fi.uncoma.edu.ar/kb1/Class3"],"multiplicity":[null,null],"roles":["",""],"type":"association"}]}
EOT;

        $expected = json_encode(json_decode($json_iri));

        $owl2 = <<<XML
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
            <Class abbreviatedIRI="owl:Thing"/>
          </SubClassOf>
          <SubClassOf>
            <ObjectSomeValuesFrom>
              <ObjectProperty IRI="R1"/>
              <Class abbreviatedIRI="owl:Thing"/>
            </ObjectSomeValuesFrom>
            <Class IRI="Class1"/>
          </SubClassOf>
          <SubClassOf>
            <ObjectSomeValuesFrom>
              <ObjectInverseOf>
                <ObjectProperty IRI="R1"/>
              </ObjectInverseOf>
              <Class abbreviatedIRI="owl:Thing"/>
            </ObjectSomeValuesFrom>
            <Class IRI="Class2"/>
          </SubClassOf>
          <EquivalentClasses>
            <Class IRI="Class1_R1_min"/>
            <ObjectIntersectionOf>
              <Class IRI="Class1"/>
              <ObjectMinCardinality cardinality="1">
                <ObjectProperty IRI="R1"/>
              </ObjectMinCardinality>
            </ObjectIntersectionOf>
          </EquivalentClasses>
          <EquivalentClasses>
            <Class IRI="Class1_R1_max"/>
            <ObjectIntersectionOf>
              <Class IRI="Class1"/>
              <ObjectMaxCardinality cardinality="1">
                <ObjectProperty IRI="R1"/>
              </ObjectMaxCardinality>
            </ObjectIntersectionOf>
          </EquivalentClasses>
          <EquivalentClasses>
            <Class IRI="Class2_R1_min"/>
            <ObjectIntersectionOf>
              <Class IRI="Class2"/>
              <ObjectMinCardinality cardinality="1">
                <ObjectInverseOf>
                  <ObjectProperty IRI="R1"/>
                </ObjectInverseOf>
              </ObjectMinCardinality>
            </ObjectIntersectionOf>
          </EquivalentClasses>
          <EquivalentClasses>
            <Class IRI="Class2_R1_max"/>
            <ObjectIntersectionOf>
              <Class IRI="Class2"/>
              <ObjectMaxCardinality cardinality="1">
                <ObjectInverseOf>
                  <ObjectProperty IRI="R1"/>
                </ObjectInverseOf>
              </ObjectMaxCardinality>
            </ObjectIntersectionOf>
          </EquivalentClasses>
          <SubClassOf>
            <ObjectSomeValuesFrom>
              <ObjectProperty IRI="R2"/>
              <Class abbreviatedIRI="owl:Thing"/>
            </ObjectSomeValuesFrom>
            <Class IRI="Class1"/>
          </SubClassOf>
          <SubClassOf>
            <ObjectSomeValuesFrom>
              <ObjectInverseOf>
                <ObjectProperty IRI="R2"/>
              </ObjectInverseOf>
              <Class abbreviatedIRI="owl:Thing"/>
            </ObjectSomeValuesFrom>
            <Class IRI="Class3"/>
          </SubClassOf>
          <EquivalentClasses>
            <Class IRI="Class1_R2_min"/>
            <ObjectIntersectionOf>
              <Class IRI="Class1"/>
              <ObjectMinCardinality cardinality="1">
                <ObjectProperty IRI="R2"/>
              </ObjectMinCardinality>
            </ObjectIntersectionOf>
          </EquivalentClasses>
          <EquivalentClasses>
            <Class IRI="Class1_R2_max"/>
            <ObjectIntersectionOf>
              <Class IRI="Class1"/>
              <ObjectMaxCardinality cardinality="1">
                <ObjectProperty IRI="R2"/>
              </ObjectMaxCardinality>
            </ObjectIntersectionOf>
          </EquivalentClasses>
          <EquivalentClasses>
            <Class IRI="Class3_R2_min"/>
            <ObjectIntersectionOf>
              <Class IRI="Class3"/>
              <ObjectMinCardinality cardinality="1">
                <ObjectInverseOf>
                  <ObjectProperty IRI="R2"/>
                </ObjectInverseOf>
              </ObjectMinCardinality>
            </ObjectIntersectionOf>
          </EquivalentClasses>
          <EquivalentClasses>
            <Class IRI="Class3_R2_max"/>
            <ObjectIntersectionOf>
              <Class IRI="Class3"/>
              <ObjectMaxCardinality cardinality="1">
                <ObjectInverseOf>
                  <ObjectProperty IRI="R2"/>
                </ObjectInverseOf>
              </ObjectMaxCardinality>
            </ObjectIntersectionOf>
          </EquivalentClasses>
        </Ontology>
XML;

      $strategy = new UMLcrowd();
      $jsonbuilder = new UMLJSONBuilder();
      $decoder = new Decoder($strategy, $jsonbuilder);

      $ontologyIRI = ["prefix" => "", "iri" => "http://crowd.fi.uncoma.edu.ar/kb1/"];
      $prefix = [["prefix" => "", "iri" => "http://crowd.fi.uncoma.edu.ar/kb1/"]];

      $actual = $decoder->to_json($owl2, $ontologyIRI, $prefix);

      $this->assertJsonStringEqualsJsonString($actual, $expected, true);
    }


    public function testToJsonAttributeUML(){

        $json =<<<EOT
{"metadata": [], "graphops": [], "header": [],
                        "prefix": [{"prefix":"","iri":"http://crowd.fi.uncoma.edu.ar/kb1/"}],
                        "ontologyIRI": [{"prefix":"", "iri":"http://crowd.fi.uncoma.edu.ar/kb1/"}],
"classes":[{"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class5","attrs":[
          {"name":"http://crowd.fi.uncoma.edu.ar/kb1/dni","datatype":"xsd:string"}],"methods":[]}],"links":[]}
EOT;

        $expected = json_encode(json_decode($json));

        $owl2 = <<<XML
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
            <Class IRI="Class5"/>
            <Class abbreviatedIRI="owl:Thing"/>
          </SubClassOf>
          <DataPropertyDomain>
              <DataProperty IRI="dni"/>
              <Class IRI="Class5"/>
          </DataPropertyDomain>
          <DataPropertyRange>
              <DataProperty IRI="dni"/>
              <Datatype IRI="xsd:string"/>
          </DataPropertyRange>
  </Ontology>
XML;

      $strategy = new UMLcrowd();
      $jsonbuilder = new UMLJSONBuilder();
      $decoder = new Decoder($strategy, $jsonbuilder);

      $ontologyIRI = ["prefix" => "", "iri" => "http://crowd.fi.uncoma.edu.ar/kb1/"];
      $prefix = [["prefix" => "", "iri" => "http://crowd.fi.uncoma.edu.ar/kb1/"]];

      $actual = $decoder->to_json($owl2, $ontologyIRI, $prefix);

      $this->assertJsonStringEqualsJsonString($actual, $expected, true);

    }


    public function testToJsonAttributeUMLplus(){

        $json =<<<EOT
{"metadata": [], "graphops": [], "header": [],
 "prefix": [{"prefix":"","iri":"http://crowd.fi.uncoma.edu.ar/kb1/"}],
 "ontologyIRI": [{"prefix":"", "iri":"http://crowd.fi.uncoma.edu.ar/kb1/"}],
"classes":[{"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class5","attrs":[
          {"name":"http://crowd.fi.uncoma.edu.ar/kb1/dni","datatype":"xsd:string"}],"methods":[]},
          {"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class6","attrs":[{"name":"http://crowd.fi.uncoma.edu.ar/kb1/age","datatype":"xsd:int"},
          {"name":"http://crowd.fi.uncoma.edu.ar/kb1/cuil","datatype":"xsd:string"}],"methods":[]}],"links":[]}
EOT;

        $expected = json_encode(json_decode($json));

        $owl2 = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
  <Ontology xmlns="http://www.w3.org/2002/07/owl#"
          xml:base="http://crowd.fi.uncoma.edu.ar/kb1/"
          xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
          xmlns:xml="http://www.w3.org/XML/1998/namespace"
          xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
          xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
          ontologyIRI="http://localhost/kb/">
          <Prefix name="" IRI="http://crowd.fi.uncoma.edu.ar/kb1/"/>
          <SubClassOf>
            <Class IRI="Class5"/>
            <Class abbreviatedIRI="owl:Thing"/>
          </SubClassOf>
          <SubClassOf>
            <Class IRI="Class6"/>
            <Class abbreviatedIRI="owl:Thing"/>
          </SubClassOf>
          <DataPropertyDomain>
              <DataProperty IRI="dni"/>
              <Class IRI="Class5"/>
          </DataPropertyDomain>
          <DataPropertyRange>
              <DataProperty IRI="dni"/>
              <Datatype IRI="xsd:string"/>
          </DataPropertyRange>
          <DataPropertyDomain>
              <DataProperty IRI="age"/>
              <Class IRI="Class6"/>
          </DataPropertyDomain>
          <DataPropertyRange>
              <DataProperty IRI="age"/>
              <Datatype IRI="xsd:int"/>
          </DataPropertyRange>
          <DataPropertyDomain>
              <DataProperty IRI="cuil"/>
              <Class IRI="Class6"/>
          </DataPropertyDomain>
          <DataPropertyRange>
              <DataProperty IRI="cuil"/>
              <Datatype IRI="xsd:string"/>
          </DataPropertyRange>
  </Ontology>
XML;

      $strategy = new UMLcrowd();
      $jsonbuilder = new UMLJSONBuilder();
      $decoder = new Decoder($strategy, $jsonbuilder);

      $ontologyIRI = ["prefix" => "", "iri" => "http://crowd.fi.uncoma.edu.ar/kb1/"];
      $prefix = [["prefix" => "", "iri" => "http://crowd.fi.uncoma.edu.ar/kb1/"]];

      $actual = $decoder->to_json($owl2, $ontologyIRI, $prefix);

      $this->assertJsonStringEqualsJsonString($actual, $expected, true);

    }
*/
    public function testToJsonFullIRIsUMLplus(){

        $json =<<<EOT
        {"namespaces":{"ontologyIRI":[{"prefix":"crowd","value":"http://crowd.fi.uncoma.edu.ar#"}],
        "defaultIRIs":[{"prefix":"rdf","value":"http://www.w3.org/1999/02/22-rdf-syntax-ns#"},
        {"prefix":"rdfs","value":"http://www.w3.org/2000/01/rdf-schema#"},
        {"prefix":"xsd","value":"http://www.w3.org/2001/XMLSchema#"},
        {"prefix":"owl","value":"http://www.w3.org/2002/07/owl#"}],
        "IRIs":[]},
        "classes":[{"name":"http://crowd.fi.uncoma.edu.ar#Class4","attrs":[],"methods":[]},
                   {"name":"http://crowd.fi.uncoma.edu.ar#Class5","attrs":[],"methods":[]}],
        "links":[{"name":"http://crowd.fi.uncoma.edu.ar#r1",
          "classes":["http://crowd.fi.uncoma.edu.ar#Class5","http://crowd.fi.uncoma.edu.ar#Class4"],
          "multiplicity":["0..*","0..*"],
          "roles":["http://crowd.fi.uncoma.edu.ar#class5","http://crowd.fi.uncoma.edu.ar#class4"],
          "type":"association"}]}
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
    <Class IRI="http://crowd.fi.uncoma.edu.ar#Class4"/>
  </Declaration>
  <Declaration>
    <Class IRI="http://crowd.fi.uncoma.edu.ar#Class5"/>
  </Declaration>
  <Declaration>
    <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#r1"/>
  </Declaration>
  <SubClassOf>
    <ObjectSomeValuesFrom>
      <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#r1"/>
      <Class abbreviatedIRI="owl:Thing"/>
    </ObjectSomeValuesFrom>
      <Class IRI="http://crowd.fi.uncoma.edu.ar#Class4"/>
  </SubClassOf>
  <SubClassOf>
    <ObjectSomeValuesFrom>
      <ObjectInverseOf>
        <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#r1"/>
      </ObjectInverseOf>
      <Class abbreviatedIRI="owl:Thing"/>
    </ObjectSomeValuesFrom>
    <Class IRI="http://crowd.fi.uncoma.edu.ar#Class5"/>
  </SubClassOf>
</Ontology>
XML;
//
      $strategy = new UMLcrowd();
      $jsonbuilder = new UMLJSONBuilder();

      $decoder = new Decoder($strategy, $jsonbuilder);

      $ontologyIRI = ["prefix" => "crowd", "value" => "http://crowd.fi.uncoma.edu.ar/"];
      $prefix = [["prefix" => "rdf", "value" => "http://www.w3.org/1999/02/22-rdf-syntax-ns#"],
                 ["prefix" => "rdfs","value" => "http://www.w3.org/2000/01/rdf-schema#"],
                 ["prefix" => "xsd","value" => "http://www.w3.org/2001/XMLSchema#"],
                 ["prefix" => "owl","value" => "http://www.w3.org/2002/07/owl#"]];

      $actual = $decoder->to_json($owl2, $ontologyIRI, $prefix);

      var_dump($actual);

//      $this->assertJsonStringEqualsJsonString($actual, $expected, true);

    }

/*
    public function testToJsonBioOnto(){

        $json =<<<EOT
{"metadata":[],"graphops":[],"header":[],"prefix":[{"prefix":"","iri":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/"},{"prefix":"wd","iri":"http:\/\/www.wikidata.org\/entity\/"},{"prefix":"dbr","iri":"http:\/\/dbpedia.org\/resource\/"},{"prefix":"dwc","iri":"http:\/\/rs.tdwg.org\/dwc\/terms\/"},{"prefix":"owl","iri":"http:\/\/www.w3.org\/2002\/07\/owl#"},{"prefix":"rdf","iri":"http:\/\/www.w3.org\/1999\/02\/22-rdf-syntax-ns#"},{"prefix":"wdt","iri":"http:\/\/wikidata.org\/prop\/direct\/"},{"prefix":"xml","iri":"http:\/\/www.w3.org\/XML\/1998\/namespace"},{"prefix":"xsd","iri":"http:\/\/www.w3.org\/2001\/XMLSchema#"},{"prefix":"envo","iri":"http:\/\/purl.obolibrary.org\/obo\/"},{"prefix":"foaf","iri":"http:\/\/xmlns.com\/foaf\/0.1\/"},{"prefix":"rdfs","iri":"http:\/\/www.w3.org\/2000\/01\/rdf-schema#"},{"prefix":"time","iri":"http:\/\/www.w3.org\/2006\/time#"},{"prefix":"void","iri":"http:\/\/rdfs.org\/ns\/void#"},{"prefix":"dcterms","iri":"http:\/\/purl.org\/dc\/terms\/"},{"prefix":"geo-ont","iri":"http:\/\/www.geonames.org\/ontology#"},{"prefix":"geo-pos","iri":"http:\/\/www.w3.org\/2003\/01\/geo\/wgs84_pos#"},{"prefix":"bio-onto","iri":"http:\/\/www.cenpat-conicet.gob.ar\/ontology\/"}],"ontologyIRI":[{"prefix":"","iri":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/"}],"classes":[{"name":"http:\/\/purl.obolibrary.org\/obo\/ENVO_00000428","attrs":[],"methods":[]},{"name":"http:\/\/purl.obolibrary.org\/obo\/ENVO_00002297","attrs":[],"methods":[]},{"name":"http:\/\/purl.obolibrary.org\/obo\/ENVO_00010483","attrs":[],"methods":[]},{"name":"http:\/\/purl.org\/dc\/terms\/Location","attrs":[{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/maximumDepthInMeters","datatype":""},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimCoordinates","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/locationID","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/waterBody","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/decimalLatitude","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#decimal"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/decimalLongitude","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#decimal"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/locality","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/www.w3.org\/2003\/01\/geo\/wgs84_pos#long","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#decimal"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/stateProvince","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimLatitude","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimEventDate","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/verbatimLongitude","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/country","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/minimumDepthInMeters","datatype":""},{"name":"http:\/\/www.w3.org\/2003\/01\/geo\/wgs84_pos#lat","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#decimal"}],"methods":[]},{"name":"http:\/\/rdfs.org\/ns\/void#Dataset","attrs":[{"name":"http:\/\/purl.org\/dc\/terms\/source","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#anyURI"}],"methods":[]},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/Event","attrs":[],"methods":[]},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/Occurrence","attrs":[{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/sex","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/occurrenceRemarks","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/basisOfRecord","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/occurrenceID","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/catalogNumber","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/individualCount","datatype":""},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/collectionCode","datatype":""}],"methods":[]},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/Organism","attrs":[],"methods":[]},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/Taxon","attrs":[{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/genus","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/class","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/specificEpithet","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/family","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/phylum","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/scientificNameAuthorship","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/kingdom","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/scientificName","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#string"},{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/order","datatype":""}],"methods":[]},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/BioEvent","attrs":[{"name":"http:\/\/rs.tdwg.org\/dwc\/terms\/eventDate","datatype":"http:\/\/www.w3.org\/2001\/XMLSchema#dateTime"}],"methods":[]},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Environment","attrs":[],"methods":[]},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region","attrs":[],"methods":[]},{"name":"http:\/\/xmlns.com\/foaf\/0.1\/Agent","attrs":[],"methods":[]}],"links":[{"name":"g1","classes":["http:\/\/purl.obolibrary.org\/obo\/ENVO_00010483"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Environment","constraint":[]},{"name":"g1","classes":["http:\/\/purl.org\/dc\/terms\/Location"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region","constraint":[]},{"name":"g1","classes":["http:\/\/purl.obolibrary.org\/obo\/ENVO_00000428"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Environment","constraint":[]},{"name":"g1","classes":["http:\/\/purl.obolibrary.org\/obo\/ENVO_00002297"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Environment","constraint":[]},{"name":"g1","classes":["http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/BioEvent"],"multiplicity":null,"roles":[null,null],"type":"generalization","parent":"http:\/\/rs.tdwg.org\/dwc\/terms\/Event","constraint":[]},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/associated","classes":["http:\/\/rs.tdwg.org\/dwc\/terms\/Occurrence","http:\/\/rs.tdwg.org\/dwc\/terms\/Organism"],"multiplicity":[null,null],"roles":["",""],"type":"association"},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/belongsTo","classes":["http:\/\/rs.tdwg.org\/dwc\/terms\/Organism","http:\/\/rs.tdwg.org\/dwc\/terms\/Taxon"],"multiplicity":[null,null],"roles":["",""],"type":"association"},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/caraterises","classes":["http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Environment","http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/Region"],"multiplicity":[null,null],"roles":["",""],"type":"association"},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/has_event","classes":["http:\/\/rs.tdwg.org\/dwc\/terms\/Occurrence","http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/BioEvent"],"multiplicity":[null,null],"roles":["",""],"type":"association"},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/has_location","classes":["http:\/\/rs.tdwg.org\/dwc\/terms\/Occurrence","http:\/\/purl.org\/dc\/terms\/Location"],"multiplicity":[null,null],"roles":["",""],"type":"association"},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/memberOf","classes":["http:\/\/rs.tdwg.org\/dwc\/terms\/Occurrence","http:\/\/rdfs.org\/ns\/void#Dataset"],"multiplicity":[null,null],"roles":["",""],"type":"association"},{"name":"http:\/\/www.cenpat-conicet.gob.ar\/bioOnto\/recorded_by","classes":["http:\/\/rs.tdwg.org\/dwc\/terms\/Occurrence","http:\/\/xmlns.com\/foaf\/0.1\/Agent"],"multiplicity":[null,null],"roles":["",""],"type":"association"}]}
EOT;

        $expected = json_encode(json_decode($json));

        $owl2 = <<<XML
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

      $strategy = new UMLcrowd();
      $jsonbuilder = new UMLJSONBuilder();
      $decoder = new Decoder($strategy, $jsonbuilder);

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

      $actual = $decoder->to_json($owl2, $ontologyIRI, $prefix);
      $this->assertJsonStringEqualsJsonString($actual, $expected, true);

    }



    */
}

?>
