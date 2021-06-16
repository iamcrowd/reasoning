<?php
/*

   Copyright 2017 GILIA

   Author: GILIA

   reasonertest.php

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
load("converterconnector.php", "wicom/converter/");

use Wicom\Converter\ConverterConnector;

class ConverterConnectorTest extends PHPUnit\Framework\TestCase
{

  /**
  @testdox Converting from OWL/XML to RDF/XML
  */
    public function testConverter(){
        $input = '<?xml version="1.0" encoding="UTF-8"?>
        <Ontology xmlns="http://www.w3.org/2002/07/owl#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:xml="http://www.w3.org/XML/1998/namespace" xmlns:xsd="http://www.w3.org/2001/XMLSchema#" xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#" xml:base="http://crowd.fi.uncoma.edu.ar/kb1#" ontologyIRI="http://crowd.fi.uncoma.edu.ar/kb1#">
        <Prefix name="rdf" IRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
        <Prefix name="rdfs" IRI="http://www.w3.org/2000/01/rdf-schema#"/>
        <Prefix name="xsd" IRI="http://www.w3.org/2001/XMLSchema#"/>
        <Prefix name="owl" IRI="http://www.w3.org/2002/07/owl#"/>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#of"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#approved-by"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#with"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#with"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#as1"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
        </Declaration>
        <Declaration>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
        </Declaration>
        <Declaration>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#country"/>
        </Declaration>
        <Declaration>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#Person"/>
        </Declaration>
        <Declaration>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#date"/>
        </Declaration>
        <Declaration>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#batch"/>
        </Declaration>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
        </SubClassOf>
        <DisjointClasses>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
        </DisjointClasses>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
          <ObjectUnionOf>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
          </ObjectUnionOf>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#as1"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#of"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#as1"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#approved-by"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#with"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <ObjectMinCardinality cardinality="1">
            <ObjectInverseOf>
              <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
            </ObjectInverseOf>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
          </ObjectMinCardinality>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <ObjectMaxCardinality cardinality="1">
            <ObjectInverseOf>
              <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
            </ObjectInverseOf>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
          </ObjectMaxCardinality>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#with"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine"/>
          <ObjectMaxCardinality cardinality="2">
            <ObjectInverseOf>
              <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#with"/>
            </ObjectInverseOf>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
          </ObjectMaxCardinality>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <ObjectMaxCardinality cardinality="1">
            <ObjectInverseOf>
              <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
            </ObjectInverseOf>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
          </ObjectMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
          </DataMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
          </DataMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#country"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#country"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#country"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
          </DataMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#Person"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#Person"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#Person"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
          </DataMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#date"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#date"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#date"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#date"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#date"/>
          </DataMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#batch"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#batch"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#batch"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
          </DataMaxCardinality>
        </SubClassOf>
        </Ontology>';

        $expected = '<rdf:RDF
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:owl="http://www.w3.org/2002/07/owl#"
    xmlns="http://crowd.fi.uncoma.edu.ar/kb1#"
    xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
    xmlns:xsd="http://www.w3.org/2001/XMLSchema#">
  <owl:Ontology rdf:about="http://crowd.fi.uncoma.edu.ar/kb1#"/>
  <owl:Class rdf:about="http://crowd.fi.uncoma.edu.ar#As2">
    <rdfs:subClassOf>
      <owl:Restriction>
        <owl:someValuesFrom>
          <owl:Class rdf:about="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
        </owl:someValuesFrom>
        <owl:onProperty>
          <owl:ObjectProperty rdf:about="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
        </owl:onProperty>
      </owl:Restriction>
    </rdfs:subClassOf>
    <rdfs:subClassOf>
      <owl:Restriction>
        <owl:someValuesFrom>
          <owl:Class rdf:about="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
        </owl:someValuesFrom>
        <owl:onProperty>
          <owl:ObjectProperty rdf:about="http://crowd.fi.uncoma.edu.ar#with"/>
        </owl:onProperty>
      </owl:Restriction>
    </rdfs:subClassOf>
  </owl:Class>
  <owl:Class rdf:about="http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine">
    <rdfs:subClassOf>
      <owl:Restriction>
        <owl:onClass>
          <owl:Class rdf:about="http://crowd.fi.uncoma.edu.ar#As3"/>
        </owl:onClass>
        <owl:maxQualifiedCardinality rdf:datatype="http://www.w3.org/2001/XMLSchema#nonNegativeInteger"
        >2</owl:maxQualifiedCardinality>
        <owl:onProperty rdf:parseType="Resource">
          <owl:inverseOf rdf:resource="http://crowd.fi.uncoma.edu.ar#with"/>
        </owl:onProperty>
      </owl:Restriction>
    </rdfs:subClassOf>
    <rdfs:subClassOf>
      <owl:Class rdf:about="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
    </rdfs:subClassOf>
  </owl:Class>
  <owl:Class rdf:about="http://crowd.fi.uncoma.edu.ar#Regulatory-Body">
    <rdfs:subClassOf>
      <owl:Restriction>
        <owl:onDataRange rdf:resource="http://www.w3.org/2001/XMLSchema#string"/>
        <owl:maxQualifiedCardinality rdf:datatype="http://www.w3.org/2001/XMLSchema#nonNegativeInteger"
        >1</owl:maxQualifiedCardinality>
        <owl:onProperty>
          <owl:DatatypeProperty rdf:about="http://crowd.fi.uncoma.edu.ar#country"/>
        </owl:onProperty>
      </owl:Restriction>
    </rdfs:subClassOf>
    <rdfs:subClassOf>
      <owl:Restriction>
        <owl:onDataRange rdf:resource="http://www.w3.org/2001/XMLSchema#string"/>
        <owl:maxQualifiedCardinality rdf:datatype="http://www.w3.org/2001/XMLSchema#nonNegativeInteger"
        >1</owl:maxQualifiedCardinality>
        <owl:onProperty>
          <owl:DatatypeProperty rdf:about="http://crowd.fi.uncoma.edu.ar#name"/>
        </owl:onProperty>
      </owl:Restriction>
    </rdfs:subClassOf>
  </owl:Class>
  <owl:Class rdf:about="http://crowd.fi.uncoma.edu.ar#As3">
    <rdfs:subClassOf>
      <owl:Restriction>
        <owl:someValuesFrom>
          <owl:Class rdf:about="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
        </owl:someValuesFrom>
        <owl:onProperty rdf:resource="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
      </owl:Restriction>
    </rdfs:subClassOf>
    <rdfs:subClassOf>
      <owl:Restriction>
        <owl:someValuesFrom rdf:resource="http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine"/>
        <owl:onProperty rdf:resource="http://crowd.fi.uncoma.edu.ar#with"/>
      </owl:Restriction>
    </rdfs:subClassOf>
    <rdfs:subClassOf rdf:resource="http://crowd.fi.uncoma.edu.ar#As2"/>
  </owl:Class>
  <owl:Class rdf:about="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine">
    <rdfs:subClassOf>
      <owl:Restriction>
        <owl:onDataRange rdf:resource="http://www.w3.org/2001/XMLSchema#string"/>
        <owl:maxQualifiedCardinality rdf:datatype="http://www.w3.org/2001/XMLSchema#nonNegativeInteger"
        >1</owl:maxQualifiedCardinality>
        <owl:onProperty>
          <owl:DatatypeProperty rdf:about="http://crowd.fi.uncoma.edu.ar#name"/>
        </owl:onProperty>
      </owl:Restriction>
    </rdfs:subClassOf>
    <rdfs:subClassOf>
      <owl:Class>
        <owl:unionOf rdf:parseType="Collection">
          <owl:Class rdf:about="http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug"/>
          <owl:Class rdf:about="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
        </owl:unionOf>
      </owl:Class>
    </rdfs:subClassOf>
  </owl:Class>
  <owl:Class rdf:about="http://crowd.fi.uncoma.edu.ar#Inoculation">
    <rdfs:subClassOf>
      <owl:Restriction>
        <owl:onDataRange rdf:resource="http://www.w3.org/2001/XMLSchema#string"/>
        <owl:maxQualifiedCardinality rdf:datatype="http://www.w3.org/2001/XMLSchema#nonNegativeInteger"
        >1</owl:maxQualifiedCardinality>
        <owl:onProperty>
          <owl:DatatypeProperty rdf:about="http://crowd.fi.uncoma.edu.ar#batch"/>
        </owl:onProperty>
      </owl:Restriction>
    </rdfs:subClassOf>
    <rdfs:subClassOf>
      <owl:Restriction>
        <owl:onDataRange rdf:resource="http://www.w3.org/2001/XMLSchema#date"/>
        <owl:maxQualifiedCardinality rdf:datatype="http://www.w3.org/2001/XMLSchema#nonNegativeInteger"
        >1</owl:maxQualifiedCardinality>
        <owl:onProperty>
          <owl:DatatypeProperty rdf:about="http://crowd.fi.uncoma.edu.ar#date"/>
        </owl:onProperty>
      </owl:Restriction>
    </rdfs:subClassOf>
    <rdfs:subClassOf>
      <owl:Restriction>
        <owl:onDataRange rdf:resource="http://www.w3.org/2001/XMLSchema#string"/>
        <owl:maxQualifiedCardinality rdf:datatype="http://www.w3.org/2001/XMLSchema#nonNegativeInteger"
        >1</owl:maxQualifiedCardinality>
        <owl:onProperty>
          <owl:DatatypeProperty rdf:about="http://crowd.fi.uncoma.edu.ar#Person"/>
        </owl:onProperty>
      </owl:Restriction>
    </rdfs:subClassOf>
    <rdfs:subClassOf>
      <owl:Restriction>
        <owl:onClass rdf:resource="http://crowd.fi.uncoma.edu.ar#As3"/>
        <owl:maxQualifiedCardinality rdf:datatype="http://www.w3.org/2001/XMLSchema#nonNegativeInteger"
        >1</owl:maxQualifiedCardinality>
        <owl:onProperty>
          <rdf:Description rdf:nodeID="A0">
            <owl:inverseOf rdf:resource="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
          </rdf:Description>
        </owl:onProperty>
      </owl:Restriction>
    </rdfs:subClassOf>
    <rdfs:subClassOf>
      <owl:Restriction>
        <owl:onClass rdf:resource="http://crowd.fi.uncoma.edu.ar#As2"/>
        <owl:maxQualifiedCardinality rdf:datatype="http://www.w3.org/2001/XMLSchema#nonNegativeInteger"
        >1</owl:maxQualifiedCardinality>
        <owl:onProperty rdf:nodeID="A0"/>
      </owl:Restriction>
    </rdfs:subClassOf>
    <rdfs:subClassOf>
      <owl:Restriction>
        <owl:onClass rdf:resource="http://crowd.fi.uncoma.edu.ar#As2"/>
        <owl:minQualifiedCardinality rdf:datatype="http://www.w3.org/2001/XMLSchema#nonNegativeInteger"
        >1</owl:minQualifiedCardinality>
        <owl:onProperty rdf:nodeID="A0"/>
      </owl:Restriction>
    </rdfs:subClassOf>
  </owl:Class>
  <owl:Class rdf:about="http://crowd.fi.uncoma.edu.ar#as1">
    <rdfs:subClassOf>
      <owl:Restriction>
        <owl:someValuesFrom rdf:resource="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
        <owl:onProperty>
          <owl:ObjectProperty rdf:about="http://crowd.fi.uncoma.edu.ar#approved-by"/>
        </owl:onProperty>
      </owl:Restriction>
    </rdfs:subClassOf>
    <rdfs:subClassOf>
      <owl:Restriction>
        <owl:someValuesFrom rdf:resource="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
        <owl:onProperty>
          <owl:ObjectProperty rdf:about="http://crowd.fi.uncoma.edu.ar#of"/>
        </owl:onProperty>
      </owl:Restriction>
    </rdfs:subClassOf>
  </owl:Class>
  <owl:Class rdf:about="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine">
    <rdfs:subClassOf rdf:resource="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
  </owl:Class>
  <owl:Class rdf:about="http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug">
    <owl:disjointWith rdf:resource="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
    <rdfs:subClassOf rdf:resource="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
  </owl:Class>
  <owl:DatatypeProperty rdf:about="http://crowd.fi.uncoma.edu.ar#country">
    <rdfs:range rdf:resource="http://www.w3.org/2001/XMLSchema#string"/>
    <rdfs:domain rdf:resource="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
  </owl:DatatypeProperty>
  <owl:DatatypeProperty rdf:about="http://crowd.fi.uncoma.edu.ar#date">
    <rdfs:range rdf:resource="http://www.w3.org/2001/XMLSchema#date"/>
    <rdfs:domain rdf:resource="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
  </owl:DatatypeProperty>
  <owl:DatatypeProperty rdf:about="http://crowd.fi.uncoma.edu.ar#name">
    <rdfs:domain rdf:resource="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
    <rdfs:range rdf:resource="http://www.w3.org/2001/XMLSchema#string"/>
    <rdfs:domain rdf:resource="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
  </owl:DatatypeProperty>
  <owl:DatatypeProperty rdf:about="http://crowd.fi.uncoma.edu.ar#Person">
    <rdfs:range rdf:resource="http://www.w3.org/2001/XMLSchema#string"/>
    <rdfs:domain rdf:resource="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
  </owl:DatatypeProperty>
  <owl:DatatypeProperty rdf:about="http://crowd.fi.uncoma.edu.ar#batch">
    <rdfs:range rdf:resource="http://www.w3.org/2001/XMLSchema#string"/>
    <rdfs:domain rdf:resource="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
  </owl:DatatypeProperty>
</rdf:RDF>';


        $converter = new ConverterConnector();

        $converter->run_converter($input,"owl/xml","rdf/xml");
        $actual = $converter->get_col_answers()[0];

        $expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);

        $this->assertEqualXMLStructure($expected, $actual, true);
    }

    /**
    @testdox Converting from OWL/XML to Turtle
    */
    public function testConverterTurtle(){
        $input = '<?xml version="1.0" encoding="UTF-8"?>
        <Ontology xmlns="http://www.w3.org/2002/07/owl#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:xml="http://www.w3.org/XML/1998/namespace" xmlns:xsd="http://www.w3.org/2001/XMLSchema#" xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#" xml:base="http://crowd.fi.uncoma.edu.ar/kb1#" ontologyIRI="http://crowd.fi.uncoma.edu.ar/kb1#">
        <Prefix name="rdf" IRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
        <Prefix name="rdfs" IRI="http://www.w3.org/2000/01/rdf-schema#"/>
        <Prefix name="xsd" IRI="http://www.w3.org/2001/XMLSchema#"/>
        <Prefix name="owl" IRI="http://www.w3.org/2002/07/owl#"/>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#of"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#approved-by"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#with"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#with"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#as1"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
        </Declaration>
        <Declaration>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
        </Declaration>
        <Declaration>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#country"/>
        </Declaration>
        <Declaration>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#Person"/>
        </Declaration>
        <Declaration>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#date"/>
        </Declaration>
        <Declaration>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#batch"/>
        </Declaration>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
        </SubClassOf>
        <DisjointClasses>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
        </DisjointClasses>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
          <ObjectUnionOf>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
          </ObjectUnionOf>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#as1"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#of"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#as1"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#approved-by"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#with"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <ObjectMinCardinality cardinality="1">
            <ObjectInverseOf>
              <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
            </ObjectInverseOf>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
          </ObjectMinCardinality>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <ObjectMaxCardinality cardinality="1">
            <ObjectInverseOf>
              <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
            </ObjectInverseOf>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
          </ObjectMaxCardinality>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#with"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine"/>
          <ObjectMaxCardinality cardinality="2">
            <ObjectInverseOf>
              <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#with"/>
            </ObjectInverseOf>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
          </ObjectMaxCardinality>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <ObjectMaxCardinality cardinality="1">
            <ObjectInverseOf>
              <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
            </ObjectInverseOf>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
          </ObjectMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
          </DataMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
          </DataMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#country"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#country"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#country"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
          </DataMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#Person"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#Person"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#Person"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
          </DataMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#date"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#date"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#date"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#date"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#date"/>
          </DataMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#batch"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#batch"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#batch"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
          </DataMaxCardinality>
        </SubClassOf>
        </Ontology>';

        $expected = '@prefix :      <http://crowd.fi.uncoma.edu.ar/kb1#> .
@prefix owl:   <http://www.w3.org/2002/07/owl#> .
@prefix rdf:   <http://www.w3.org/1999/02/22-rdf-syntax-ns#> .
@prefix xml:   <http://www.w3.org/XML/1998/namespace> .
@prefix xsd:   <http://www.w3.org/2001/XMLSchema#> .
@prefix rdfs:  <http://www.w3.org/2000/01/rdf-schema#> .

<http://crowd.fi.uncoma.edu.ar#as1>
        a                owl:Class ;
        rdfs:subClassOf  [ a                   owl:Restriction ;
                           owl:onProperty      <http://crowd.fi.uncoma.edu.ar#approved-by> ;
                           owl:someValuesFrom  <http://crowd.fi.uncoma.edu.ar#Regulatory-Body>
                         ] ;
        rdfs:subClassOf  [ a                   owl:Restriction ;
                           owl:onProperty      <http://crowd.fi.uncoma.edu.ar#of> ;
                           owl:someValuesFrom  <http://crowd.fi.uncoma.edu.ar#COVID-19Medicine>
                         ] .

<http://crowd.fi.uncoma.edu.ar#batch>
        a            owl:DatatypeProperty ;
        rdfs:domain  <http://crowd.fi.uncoma.edu.ar#Inoculation> ;
        rdfs:range   xsd:string .

<http://crowd.fi.uncoma.edu.ar#Inoculation>
        a                owl:Class ;
        rdfs:subClassOf  [ a                            owl:Restriction ;
                           owl:maxQualifiedCardinality  "1"^^xsd:nonNegativeInteger ;
                           owl:onDataRange              xsd:string ;
                           owl:onProperty               <http://crowd.fi.uncoma.edu.ar#batch>
                         ] ;
        rdfs:subClassOf  [ a                            owl:Restriction ;
                           owl:maxQualifiedCardinality  "1"^^xsd:nonNegativeInteger ;
                           owl:onDataRange              xsd:date ;
                           owl:onProperty               <http://crowd.fi.uncoma.edu.ar#date>
                         ] ;
        rdfs:subClassOf  [ a                            owl:Restriction ;
                           owl:maxQualifiedCardinality  "1"^^xsd:nonNegativeInteger ;
                           owl:onDataRange              xsd:string ;
                           owl:onProperty               <http://crowd.fi.uncoma.edu.ar#Person>
                         ] ;
        rdfs:subClassOf  [ a                            owl:Restriction ;
                           owl:maxQualifiedCardinality  "1"^^xsd:nonNegativeInteger ;
                           owl:onClass                  <http://crowd.fi.uncoma.edu.ar#As3> ;
                           owl:onProperty               _:b0
                         ] ;
        rdfs:subClassOf  [ a                            owl:Restriction ;
                           owl:maxQualifiedCardinality  "1"^^xsd:nonNegativeInteger ;
                           owl:onClass                  <http://crowd.fi.uncoma.edu.ar#As2> ;
                           owl:onProperty               _:b0
                         ] ;
        rdfs:subClassOf  [ a                            owl:Restriction ;
                           owl:minQualifiedCardinality  "1"^^xsd:nonNegativeInteger ;
                           owl:onClass                  <http://crowd.fi.uncoma.edu.ar#As2> ;
                           owl:onProperty               _:b0
                         ] .

<http://crowd.fi.uncoma.edu.ar#of>
        a       owl:ObjectProperty .

<http://crowd.fi.uncoma.edu.ar#date>
        a            owl:DatatypeProperty ;
        rdfs:domain  <http://crowd.fi.uncoma.edu.ar#Inoculation> ;
        rdfs:range   xsd:date .

<http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug>
        a                 owl:Class ;
        rdfs:subClassOf   <http://crowd.fi.uncoma.edu.ar#COVID-19Medicine> ;
        owl:disjointWith  <http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine> .

<http://crowd.fi.uncoma.edu.ar#role-b-2>
        a       owl:ObjectProperty .

_:b0    owl:inverseOf  <http://crowd.fi.uncoma.edu.ar#role-b-2> .

<http://crowd.fi.uncoma.edu.ar#COVID-19Medicine>
        a                owl:Class ;
        rdfs:subClassOf  [ a                            owl:Restriction ;
                           owl:maxQualifiedCardinality  "1"^^xsd:nonNegativeInteger ;
                           owl:onDataRange              xsd:string ;
                           owl:onProperty               <http://crowd.fi.uncoma.edu.ar#name>
                         ] ;
        rdfs:subClassOf  [ a            owl:Class ;
                           owl:unionOf  ( <http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug> <http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine> )
                         ] .

<http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine>
        a                owl:Class ;
        rdfs:subClassOf  <http://crowd.fi.uncoma.edu.ar#COVID-19Medicine> .

<http://crowd.fi.uncoma.edu.ar#name>
        a            owl:DatatypeProperty ;
        rdfs:domain  <http://crowd.fi.uncoma.edu.ar#Regulatory-Body> , <http://crowd.fi.uncoma.edu.ar#COVID-19Medicine> ;
        rdfs:range   xsd:string .

<http://crowd.fi.uncoma.edu.ar#with>
        a       owl:ObjectProperty .

:       a       owl:Ontology .

<http://crowd.fi.uncoma.edu.ar#country>
        a            owl:DatatypeProperty ;
        rdfs:domain  <http://crowd.fi.uncoma.edu.ar#Regulatory-Body> ;
        rdfs:range   xsd:string .

<http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine>
        a                owl:Class ;
        rdfs:subClassOf  <http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine> ;
        rdfs:subClassOf  [ a                            owl:Restriction ;
                           owl:maxQualifiedCardinality  "2"^^xsd:nonNegativeInteger ;
                           owl:onClass                  <http://crowd.fi.uncoma.edu.ar#As3> ;
                           owl:onProperty               [ owl:inverseOf
                                             <http://crowd.fi.uncoma.edu.ar#with> ]
                         ] .

<http://crowd.fi.uncoma.edu.ar#As3>
        a                owl:Class ;
        rdfs:subClassOf  <http://crowd.fi.uncoma.edu.ar#As2> ;
        rdfs:subClassOf  [ a                   owl:Restriction ;
                           owl:onProperty      <http://crowd.fi.uncoma.edu.ar#role-b-2> ;
                           owl:someValuesFrom  <http://crowd.fi.uncoma.edu.ar#Inoculation>
                         ] ;
        rdfs:subClassOf  [ a                   owl:Restriction ;
                           owl:onProperty      <http://crowd.fi.uncoma.edu.ar#with> ;
                           owl:someValuesFrom  <http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine>
                         ] .

<http://crowd.fi.uncoma.edu.ar#Regulatory-Body>
        a                owl:Class ;
        rdfs:subClassOf  [ a                            owl:Restriction ;
                           owl:maxQualifiedCardinality  "1"^^xsd:nonNegativeInteger ;
                           owl:onDataRange              xsd:string ;
                           owl:onProperty               <http://crowd.fi.uncoma.edu.ar#country>
                         ] ;
        rdfs:subClassOf  [ a                            owl:Restriction ;
                           owl:maxQualifiedCardinality  "1"^^xsd:nonNegativeInteger ;
                           owl:onDataRange              xsd:string ;
                           owl:onProperty               <http://crowd.fi.uncoma.edu.ar#name>
                         ] .

<http://crowd.fi.uncoma.edu.ar#Person>
        a            owl:DatatypeProperty ;
        rdfs:domain  <http://crowd.fi.uncoma.edu.ar#Inoculation> ;
        rdfs:range   xsd:string .

<http://crowd.fi.uncoma.edu.ar#approved-by>
        a       owl:ObjectProperty .

<http://crowd.fi.uncoma.edu.ar#As2>
        a                owl:Class ;
        rdfs:subClassOf  [ a                   owl:Restriction ;
                           owl:onProperty      <http://crowd.fi.uncoma.edu.ar#role-b-2> ;
                           owl:someValuesFrom  <http://crowd.fi.uncoma.edu.ar#Inoculation>
                         ] ;
        rdfs:subClassOf  [ a                   owl:Restriction ;
                           owl:onProperty      <http://crowd.fi.uncoma.edu.ar#with> ;
                           owl:someValuesFrom  <http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine>
                         ] .';

        $converter = new ConverterConnector();

        $converter->run_converter($input,"owl/xml","turtle");
        $actual = $converter->get_col_answers()[0];

        //$this->assertSame($expected, $actual);
    }

    /**
    @testdox Converting from OWL/XML to NTRIPLES
    */
    public function testConverterNTriples(){
        $input = '<?xml version="1.0" encoding="UTF-8"?>
        <Ontology xmlns="http://www.w3.org/2002/07/owl#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:xml="http://www.w3.org/XML/1998/namespace" xmlns:xsd="http://www.w3.org/2001/XMLSchema#" xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#" xml:base="http://crowd.fi.uncoma.edu.ar/kb1#" ontologyIRI="http://crowd.fi.uncoma.edu.ar/kb1#">
        <Prefix name="rdf" IRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
        <Prefix name="rdfs" IRI="http://www.w3.org/2000/01/rdf-schema#"/>
        <Prefix name="xsd" IRI="http://www.w3.org/2001/XMLSchema#"/>
        <Prefix name="owl" IRI="http://www.w3.org/2002/07/owl#"/>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#of"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#approved-by"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#with"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#with"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#as1"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
        </Declaration>
        <Declaration>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
        </Declaration>
        <Declaration>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#country"/>
        </Declaration>
        <Declaration>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#Person"/>
        </Declaration>
        <Declaration>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#date"/>
        </Declaration>
        <Declaration>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#batch"/>
        </Declaration>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
        </SubClassOf>
        <DisjointClasses>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
        </DisjointClasses>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
          <ObjectUnionOf>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
          </ObjectUnionOf>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#as1"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#of"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#as1"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#approved-by"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#with"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <ObjectMinCardinality cardinality="1">
            <ObjectInverseOf>
              <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
            </ObjectInverseOf>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
          </ObjectMinCardinality>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <ObjectMaxCardinality cardinality="1">
            <ObjectInverseOf>
              <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
            </ObjectInverseOf>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
          </ObjectMaxCardinality>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#with"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine"/>
          <ObjectMaxCardinality cardinality="2">
            <ObjectInverseOf>
              <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#with"/>
            </ObjectInverseOf>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
          </ObjectMaxCardinality>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <ObjectMaxCardinality cardinality="1">
            <ObjectInverseOf>
              <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
            </ObjectInverseOf>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
          </ObjectMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
          </DataMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
          </DataMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#country"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#country"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#country"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
          </DataMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#Person"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#Person"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#Person"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
          </DataMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#date"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#date"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#date"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#date"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#date"/>
          </DataMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#batch"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#batch"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#batch"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
          </DataMaxCardinality>
        </SubClassOf>
        </Ontology>';

        $expected = '_:B107c6040X2D551fX2D4a09X2D95a0X2Da457ed36d19c <http://www.w3.org/2002/07/owl#someValuesFrom> <http://crowd.fi.uncoma.edu.ar#Regulatory-Body> .
_:B107c6040X2D551fX2D4a09X2D95a0X2Da457ed36d19c <http://www.w3.org/2002/07/owl#onProperty> <http://crowd.fi.uncoma.edu.ar#approved-by> .
_:B107c6040X2D551fX2D4a09X2D95a0X2Da457ed36d19c <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#Restriction> .
<http://crowd.fi.uncoma.edu.ar#as1> <http://www.w3.org/2000/01/rdf-schema#subClassOf> _:B107c6040X2D551fX2D4a09X2D95a0X2Da457ed36d19c .
<http://crowd.fi.uncoma.edu.ar#as1> <http://www.w3.org/2000/01/rdf-schema#subClassOf> _:B8a382ae8X2D5368X2D4d95X2D8928X2D3cf87f51a35a .
<http://crowd.fi.uncoma.edu.ar#as1> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#Class> .
_:Bf1ac6645X2Df6cfX2D4063X2Da6e4X2D5ef2c57e3636 <http://www.w3.org/2002/07/owl#onDataRange> <http://www.w3.org/2001/XMLSchema#string> .
_:Bf1ac6645X2Df6cfX2D4063X2Da6e4X2D5ef2c57e3636 <http://www.w3.org/2002/07/owl#maxQualifiedCardinality> "1"^^<http://www.w3.org/2001/XMLSchema#nonNegativeInteger> .
_:Bf1ac6645X2Df6cfX2D4063X2Da6e4X2D5ef2c57e3636 <http://www.w3.org/2002/07/owl#onProperty> <http://crowd.fi.uncoma.edu.ar#Person> .
_:Bf1ac6645X2Df6cfX2D4063X2Da6e4X2D5ef2c57e3636 <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#Restriction> .
<http://crowd.fi.uncoma.edu.ar#batch> <http://www.w3.org/2000/01/rdf-schema#range> <http://www.w3.org/2001/XMLSchema#string> .
<http://crowd.fi.uncoma.edu.ar#batch> <http://www.w3.org/2000/01/rdf-schema#domain> <http://crowd.fi.uncoma.edu.ar#Inoculation> .
<http://crowd.fi.uncoma.edu.ar#batch> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#DatatypeProperty> .
_:B19eabc50X2Da6acX2D4683X2Da57cX2Dac713bdc1072 <http://www.w3.org/2002/07/owl#someValuesFrom> <http://crowd.fi.uncoma.edu.ar#Inoculation> .
_:B19eabc50X2Da6acX2D4683X2Da57cX2Dac713bdc1072 <http://www.w3.org/2002/07/owl#onProperty> <http://crowd.fi.uncoma.edu.ar#role-b-2> .
_:B19eabc50X2Da6acX2D4683X2Da57cX2Dac713bdc1072 <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#Restriction> .
_:Ba90ac6a1X2D0219X2D4ec2X2Db63eX2Dab9f933371a0 <http://www.w3.org/2002/07/owl#someValuesFrom> <http://crowd.fi.uncoma.edu.ar#Inoculation> .
_:Ba90ac6a1X2D0219X2D4ec2X2Db63eX2Dab9f933371a0 <http://www.w3.org/2002/07/owl#onProperty> <http://crowd.fi.uncoma.edu.ar#role-b-2> .
_:Ba90ac6a1X2D0219X2D4ec2X2Db63eX2Dab9f933371a0 <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#Restriction> .
_:B54cb7531X2D8426X2D476cX2DbbbbX2D71eef478c2f7 <http://www.w3.org/1999/02/22-rdf-syntax-ns#rest> <http://www.w3.org/1999/02/22-rdf-syntax-ns#nil> .
_:B54cb7531X2D8426X2D476cX2DbbbbX2D71eef478c2f7 <http://www.w3.org/1999/02/22-rdf-syntax-ns#first> <http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine> .
_:B492c6c4fX2D0788X2D40fcX2D8716X2D782e4aad71ae <http://www.w3.org/2002/07/owl#inverseOf> <http://crowd.fi.uncoma.edu.ar#role-b-2> .
<http://crowd.fi.uncoma.edu.ar#Inoculation> <http://www.w3.org/2000/01/rdf-schema#subClassOf> _:B93e99ae6X2Dddd3X2D4ba0X2Db64aX2Dbb88ef623937 .
<http://crowd.fi.uncoma.edu.ar#Inoculation> <http://www.w3.org/2000/01/rdf-schema#subClassOf> _:B3ea6768aX2D3f83X2D4210X2D9f14X2D5074ce2d858f .
<http://crowd.fi.uncoma.edu.ar#Inoculation> <http://www.w3.org/2000/01/rdf-schema#subClassOf> _:Bf1ac6645X2Df6cfX2D4063X2Da6e4X2D5ef2c57e3636 .
<http://crowd.fi.uncoma.edu.ar#Inoculation> <http://www.w3.org/2000/01/rdf-schema#subClassOf> _:B3013dea9X2Dde8eX2D446fX2D9202X2D7b07e9d0ed7f .
<http://crowd.fi.uncoma.edu.ar#Inoculation> <http://www.w3.org/2000/01/rdf-schema#subClassOf> _:Bd78f775aX2Dfa54X2D47a1X2Db009X2D896035c400a2 .
<http://crowd.fi.uncoma.edu.ar#Inoculation> <http://www.w3.org/2000/01/rdf-schema#subClassOf> _:B7d1dbed1X2Dcd30X2D4466X2Da590X2D8dc582760b5b .
<http://crowd.fi.uncoma.edu.ar#Inoculation> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#Class> .
_:B8fddf79cX2De92dX2D47beX2Dbb9bX2D3a8debff9dd0 <http://www.w3.org/1999/02/22-rdf-syntax-ns#rest> _:B54cb7531X2D8426X2D476cX2DbbbbX2D71eef478c2f7 .
_:B8fddf79cX2De92dX2D47beX2Dbb9bX2D3a8debff9dd0 <http://www.w3.org/1999/02/22-rdf-syntax-ns#first> <http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug> .
<http://crowd.fi.uncoma.edu.ar#of> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#ObjectProperty> .
<http://crowd.fi.uncoma.edu.ar#date> <http://www.w3.org/2000/01/rdf-schema#range> <http://www.w3.org/2001/XMLSchema#date> .
<http://crowd.fi.uncoma.edu.ar#date> <http://www.w3.org/2000/01/rdf-schema#domain> <http://crowd.fi.uncoma.edu.ar#Inoculation> .
<http://crowd.fi.uncoma.edu.ar#date> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#DatatypeProperty> .
_:Bd78f775aX2Dfa54X2D47a1X2Db009X2D896035c400a2 <http://www.w3.org/2002/07/owl#onClass> <http://crowd.fi.uncoma.edu.ar#As2> .
_:Bd78f775aX2Dfa54X2D47a1X2Db009X2D896035c400a2 <http://www.w3.org/2002/07/owl#maxQualifiedCardinality> "1"^^<http://www.w3.org/2001/XMLSchema#nonNegativeInteger> .
_:Bd78f775aX2Dfa54X2D47a1X2Db009X2D896035c400a2 <http://www.w3.org/2002/07/owl#onProperty> _:B492c6c4fX2D0788X2D40fcX2D8716X2D782e4aad71ae .
_:Bd78f775aX2Dfa54X2D47a1X2Db009X2D896035c400a2 <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#Restriction> .
<http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug> <http://www.w3.org/2002/07/owl#disjointWith> <http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine> .
<http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug> <http://www.w3.org/2000/01/rdf-schema#subClassOf> <http://crowd.fi.uncoma.edu.ar#COVID-19Medicine> .
<http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#Class> .
_:Bbeb17661X2D4dc0X2D459dX2D9d3dX2D1b00709b2899 <http://www.w3.org/2002/07/owl#unionOf> _:B8fddf79cX2De92dX2D47beX2Dbb9bX2D3a8debff9dd0 .
_:Bbeb17661X2D4dc0X2D459dX2D9d3dX2D1b00709b2899 <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#Class> .
_:B6f655570X2D51f4X2D45d7X2D98beX2D2f0ee243e9d7 <http://www.w3.org/2002/07/owl#onDataRange> <http://www.w3.org/2001/XMLSchema#string> .
_:B6f655570X2D51f4X2D45d7X2D98beX2D2f0ee243e9d7 <http://www.w3.org/2002/07/owl#maxQualifiedCardinality> "1"^^<http://www.w3.org/2001/XMLSchema#nonNegativeInteger> .
_:B6f655570X2D51f4X2D45d7X2D98beX2D2f0ee243e9d7 <http://www.w3.org/2002/07/owl#onProperty> <http://crowd.fi.uncoma.edu.ar#country> .
_:B6f655570X2D51f4X2D45d7X2D98beX2D2f0ee243e9d7 <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#Restriction> .
<http://crowd.fi.uncoma.edu.ar#role-b-2> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#ObjectProperty> .
_:B14e22494X2Dd843X2D41d8X2D9724X2Dd54de3f75876 <http://www.w3.org/2002/07/owl#onClass> <http://crowd.fi.uncoma.edu.ar#As3> .
_:B14e22494X2Dd843X2D41d8X2D9724X2Dd54de3f75876 <http://www.w3.org/2002/07/owl#maxQualifiedCardinality> "2"^^<http://www.w3.org/2001/XMLSchema#nonNegativeInteger> .
_:B14e22494X2Dd843X2D41d8X2D9724X2Dd54de3f75876 <http://www.w3.org/2002/07/owl#onProperty> _:Bcf121732X2Df36eX2D4c4dX2Da8cfX2Dd01ab6cba8ea .
_:B14e22494X2Dd843X2D41d8X2D9724X2Dd54de3f75876 <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#Restriction> .
_:B93e99ae6X2Dddd3X2D4ba0X2Db64aX2Dbb88ef623937 <http://www.w3.org/2002/07/owl#onDataRange> <http://www.w3.org/2001/XMLSchema#string> .
_:B93e99ae6X2Dddd3X2D4ba0X2Db64aX2Dbb88ef623937 <http://www.w3.org/2002/07/owl#maxQualifiedCardinality> "1"^^<http://www.w3.org/2001/XMLSchema#nonNegativeInteger> .
_:B93e99ae6X2Dddd3X2D4ba0X2Db64aX2Dbb88ef623937 <http://www.w3.org/2002/07/owl#onProperty> <http://crowd.fi.uncoma.edu.ar#batch> .
_:B93e99ae6X2Dddd3X2D4ba0X2Db64aX2Dbb88ef623937 <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#Restriction> .
<http://crowd.fi.uncoma.edu.ar#COVID-19Medicine> <http://www.w3.org/2000/01/rdf-schema#subClassOf> _:B5d92a2abX2Dcb15X2D460dX2D8a71X2D2792f89e7fef .
<http://crowd.fi.uncoma.edu.ar#COVID-19Medicine> <http://www.w3.org/2000/01/rdf-schema#subClassOf> _:Bbeb17661X2D4dc0X2D459dX2D9d3dX2D1b00709b2899 .
<http://crowd.fi.uncoma.edu.ar#COVID-19Medicine> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#Class> .
_:B7d1dbed1X2Dcd30X2D4466X2Da590X2D8dc582760b5b <http://www.w3.org/2002/07/owl#onClass> <http://crowd.fi.uncoma.edu.ar#As2> .
_:B7d1dbed1X2Dcd30X2D4466X2Da590X2D8dc582760b5b <http://www.w3.org/2002/07/owl#minQualifiedCardinality> "1"^^<http://www.w3.org/2001/XMLSchema#nonNegativeInteger> .
_:B7d1dbed1X2Dcd30X2D4466X2Da590X2D8dc582760b5b <http://www.w3.org/2002/07/owl#onProperty> _:B492c6c4fX2D0788X2D40fcX2D8716X2D782e4aad71ae .
_:B7d1dbed1X2Dcd30X2D4466X2Da590X2D8dc582760b5b <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#Restriction> .
<http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine> <http://www.w3.org/2000/01/rdf-schema#subClassOf> <http://crowd.fi.uncoma.edu.ar#COVID-19Medicine> .
<http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#Class> .
<http://crowd.fi.uncoma.edu.ar#name> <http://www.w3.org/2000/01/rdf-schema#domain> <http://crowd.fi.uncoma.edu.ar#Regulatory-Body> .
<http://crowd.fi.uncoma.edu.ar#name> <http://www.w3.org/2000/01/rdf-schema#range> <http://www.w3.org/2001/XMLSchema#string> .
<http://crowd.fi.uncoma.edu.ar#name> <http://www.w3.org/2000/01/rdf-schema#domain> <http://crowd.fi.uncoma.edu.ar#COVID-19Medicine> .
<http://crowd.fi.uncoma.edu.ar#name> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#DatatypeProperty> .
<http://crowd.fi.uncoma.edu.ar#with> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#ObjectProperty> .
<http://crowd.fi.uncoma.edu.ar/kb1#> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#Ontology> .
_:B8a382ae8X2D5368X2D4d95X2D8928X2D3cf87f51a35a <http://www.w3.org/2002/07/owl#someValuesFrom> <http://crowd.fi.uncoma.edu.ar#COVID-19Medicine> .
_:B8a382ae8X2D5368X2D4d95X2D8928X2D3cf87f51a35a <http://www.w3.org/2002/07/owl#onProperty> <http://crowd.fi.uncoma.edu.ar#of> .
_:B8a382ae8X2D5368X2D4d95X2D8928X2D3cf87f51a35a <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#Restriction> .
<http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine> <http://www.w3.org/2000/01/rdf-schema#subClassOf> _:B14e22494X2Dd843X2D41d8X2D9724X2Dd54de3f75876 .
<http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine> <http://www.w3.org/2000/01/rdf-schema#subClassOf> <http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine> .
<http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#Class> .
<http://crowd.fi.uncoma.edu.ar#country> <http://www.w3.org/2000/01/rdf-schema#range> <http://www.w3.org/2001/XMLSchema#string> .
<http://crowd.fi.uncoma.edu.ar#country> <http://www.w3.org/2000/01/rdf-schema#domain> <http://crowd.fi.uncoma.edu.ar#Regulatory-Body> .
<http://crowd.fi.uncoma.edu.ar#country> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#DatatypeProperty> .
<http://crowd.fi.uncoma.edu.ar#As3> <http://www.w3.org/2000/01/rdf-schema#subClassOf> _:B19eabc50X2Da6acX2D4683X2Da57cX2Dac713bdc1072 .
<http://crowd.fi.uncoma.edu.ar#As3> <http://www.w3.org/2000/01/rdf-schema#subClassOf> _:Bd1f5ec73X2Df66aX2D47b9X2Db81fX2D23928855e0a7 .
<http://crowd.fi.uncoma.edu.ar#As3> <http://www.w3.org/2000/01/rdf-schema#subClassOf> <http://crowd.fi.uncoma.edu.ar#As2> .
<http://crowd.fi.uncoma.edu.ar#As3> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#Class> .
<http://crowd.fi.uncoma.edu.ar#Regulatory-Body> <http://www.w3.org/2000/01/rdf-schema#subClassOf> _:B6f655570X2D51f4X2D45d7X2D98beX2D2f0ee243e9d7 .
<http://crowd.fi.uncoma.edu.ar#Regulatory-Body> <http://www.w3.org/2000/01/rdf-schema#subClassOf> _:Bbf9e3c03X2D162dX2D45b6X2Da3ffX2Dc34618a3ee6e .
<http://crowd.fi.uncoma.edu.ar#Regulatory-Body> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#Class> .
<http://crowd.fi.uncoma.edu.ar#Person> <http://www.w3.org/2000/01/rdf-schema#range> <http://www.w3.org/2001/XMLSchema#string> .
<http://crowd.fi.uncoma.edu.ar#Person> <http://www.w3.org/2000/01/rdf-schema#domain> <http://crowd.fi.uncoma.edu.ar#Inoculation> .
<http://crowd.fi.uncoma.edu.ar#Person> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#DatatypeProperty> .
_:B3ea6768aX2D3f83X2D4210X2D9f14X2D5074ce2d858f <http://www.w3.org/2002/07/owl#onDataRange> <http://www.w3.org/2001/XMLSchema#date> .
_:B3ea6768aX2D3f83X2D4210X2D9f14X2D5074ce2d858f <http://www.w3.org/2002/07/owl#maxQualifiedCardinality> "1"^^<http://www.w3.org/2001/XMLSchema#nonNegativeInteger> .
_:B3ea6768aX2D3f83X2D4210X2D9f14X2D5074ce2d858f <http://www.w3.org/2002/07/owl#onProperty> <http://crowd.fi.uncoma.edu.ar#date> .
_:B3ea6768aX2D3f83X2D4210X2D9f14X2D5074ce2d858f <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#Restriction> .
_:Bcf121732X2Df36eX2D4c4dX2Da8cfX2Dd01ab6cba8ea <http://www.w3.org/2002/07/owl#inverseOf> <http://crowd.fi.uncoma.edu.ar#with> .
_:B6cf9d453X2Db42dX2D4e9cX2D8d24X2Dfb87d8e6fe07 <http://www.w3.org/2002/07/owl#someValuesFrom> <http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine> .
_:B6cf9d453X2Db42dX2D4e9cX2D8d24X2Dfb87d8e6fe07 <http://www.w3.org/2002/07/owl#onProperty> <http://crowd.fi.uncoma.edu.ar#with> .
_:B6cf9d453X2Db42dX2D4e9cX2D8d24X2Dfb87d8e6fe07 <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#Restriction> .
_:Bbf9e3c03X2D162dX2D45b6X2Da3ffX2Dc34618a3ee6e <http://www.w3.org/2002/07/owl#onDataRange> <http://www.w3.org/2001/XMLSchema#string> .
_:Bbf9e3c03X2D162dX2D45b6X2Da3ffX2Dc34618a3ee6e <http://www.w3.org/2002/07/owl#maxQualifiedCardinality> "1"^^<http://www.w3.org/2001/XMLSchema#nonNegativeInteger> .
_:Bbf9e3c03X2D162dX2D45b6X2Da3ffX2Dc34618a3ee6e <http://www.w3.org/2002/07/owl#onProperty> <http://crowd.fi.uncoma.edu.ar#name> .
_:Bbf9e3c03X2D162dX2D45b6X2Da3ffX2Dc34618a3ee6e <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#Restriction> .
_:Bd1f5ec73X2Df66aX2D47b9X2Db81fX2D23928855e0a7 <http://www.w3.org/2002/07/owl#someValuesFrom> <http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine> .
_:Bd1f5ec73X2Df66aX2D47b9X2Db81fX2D23928855e0a7 <http://www.w3.org/2002/07/owl#onProperty> <http://crowd.fi.uncoma.edu.ar#with> .
_:Bd1f5ec73X2Df66aX2D47b9X2Db81fX2D23928855e0a7 <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#Restriction> .
_:B3013dea9X2Dde8eX2D446fX2D9202X2D7b07e9d0ed7f <http://www.w3.org/2002/07/owl#onClass> <http://crowd.fi.uncoma.edu.ar#As3> .
_:B3013dea9X2Dde8eX2D446fX2D9202X2D7b07e9d0ed7f <http://www.w3.org/2002/07/owl#maxQualifiedCardinality> "1"^^<http://www.w3.org/2001/XMLSchema#nonNegativeInteger> .
_:B3013dea9X2Dde8eX2D446fX2D9202X2D7b07e9d0ed7f <http://www.w3.org/2002/07/owl#onProperty> _:B492c6c4fX2D0788X2D40fcX2D8716X2D782e4aad71ae .
_:B3013dea9X2Dde8eX2D446fX2D9202X2D7b07e9d0ed7f <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#Restriction> .
_:B5d92a2abX2Dcb15X2D460dX2D8a71X2D2792f89e7fef <http://www.w3.org/2002/07/owl#onDataRange> <http://www.w3.org/2001/XMLSchema#string> .
_:B5d92a2abX2Dcb15X2D460dX2D8a71X2D2792f89e7fef <http://www.w3.org/2002/07/owl#maxQualifiedCardinality> "1"^^<http://www.w3.org/2001/XMLSchema#nonNegativeInteger> .
_:B5d92a2abX2Dcb15X2D460dX2D8a71X2D2792f89e7fef <http://www.w3.org/2002/07/owl#onProperty> <http://crowd.fi.uncoma.edu.ar#name> .
_:B5d92a2abX2Dcb15X2D460dX2D8a71X2D2792f89e7fef <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#Restriction> .
<http://crowd.fi.uncoma.edu.ar#approved-by> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#ObjectProperty> .
<http://crowd.fi.uncoma.edu.ar#As2> <http://www.w3.org/2000/01/rdf-schema#subClassOf> _:Ba90ac6a1X2D0219X2D4ec2X2Db63eX2Dab9f933371a0 .
<http://crowd.fi.uncoma.edu.ar#As2> <http://www.w3.org/2000/01/rdf-schema#subClassOf> _:B6cf9d453X2Db42dX2D4e9cX2D8d24X2Dfb87d8e6fe07 .
<http://crowd.fi.uncoma.edu.ar#As2> <http://www.w3.org/1999/02/22-rdf-syntax-ns#type> <http://www.w3.org/2002/07/owl#Class> .';

        $converter = new ConverterConnector();

        $converter->run_converter($input,"owl/xml","ntriples");
        $actual = $converter->get_col_answers()[0];

        //$this->assertSame($expected, $actual, true);
    }

    /**
    @testdox Converting from OWL/XML to MANCHESTER
    */
    public function testConverterManchester(){
        $input = '<?xml version="1.0" encoding="UTF-8"?>
        <Ontology xmlns="http://www.w3.org/2002/07/owl#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:xml="http://www.w3.org/XML/1998/namespace" xmlns:xsd="http://www.w3.org/2001/XMLSchema#" xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#" xml:base="http://crowd.fi.uncoma.edu.ar/kb1#" ontologyIRI="http://crowd.fi.uncoma.edu.ar/kb1#">
        <Prefix name="rdf" IRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
        <Prefix name="rdfs" IRI="http://www.w3.org/2000/01/rdf-schema#"/>
        <Prefix name="xsd" IRI="http://www.w3.org/2001/XMLSchema#"/>
        <Prefix name="owl" IRI="http://www.w3.org/2002/07/owl#"/>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#of"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#approved-by"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#with"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#with"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#as1"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
        </Declaration>
        <Declaration>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
        </Declaration>
        <Declaration>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#country"/>
        </Declaration>
        <Declaration>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#Person"/>
        </Declaration>
        <Declaration>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#date"/>
        </Declaration>
        <Declaration>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#batch"/>
        </Declaration>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
        </SubClassOf>
        <DisjointClasses>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
        </DisjointClasses>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
          <ObjectUnionOf>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
          </ObjectUnionOf>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#as1"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#of"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#as1"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#approved-by"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#with"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <ObjectMinCardinality cardinality="1">
            <ObjectInverseOf>
              <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
            </ObjectInverseOf>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
          </ObjectMinCardinality>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <ObjectMaxCardinality cardinality="1">
            <ObjectInverseOf>
              <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
            </ObjectInverseOf>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
          </ObjectMaxCardinality>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#with"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine"/>
          <ObjectMaxCardinality cardinality="2">
            <ObjectInverseOf>
              <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#with"/>
            </ObjectInverseOf>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
          </ObjectMaxCardinality>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <ObjectMaxCardinality cardinality="1">
            <ObjectInverseOf>
              <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
            </ObjectInverseOf>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
          </ObjectMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
          </DataMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
          </DataMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#country"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#country"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#country"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
          </DataMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#Person"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#Person"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#Person"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
          </DataMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#date"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#date"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#date"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#date"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#date"/>
          </DataMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#batch"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#batch"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#batch"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
          </DataMaxCardinality>
        </SubClassOf>
        </Ontology>';

        $expected = 'Prefix: : <http://crowd.fi.uncoma.edu.ar/kb1#>
Prefix: owl: <http://www.w3.org/2002/07/owl#>
Prefix: rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
Prefix: rdfs: <http://www.w3.org/2000/01/rdf-schema#>
Prefix: xml: <http://www.w3.org/XML/1998/namespace>
Prefix: xsd: <http://www.w3.org/2001/XMLSchema#>



Ontology: <http://crowd.fi.uncoma.edu.ar/kb1#>


Datatype: xsd:date


Datatype: xsd:string


ObjectProperty: <http://crowd.fi.uncoma.edu.ar#approved-by>


ObjectProperty: <http://crowd.fi.uncoma.edu.ar#of>


ObjectProperty: <http://crowd.fi.uncoma.edu.ar#role-b-2>


ObjectProperty: <http://crowd.fi.uncoma.edu.ar#with>


DataProperty: <http://crowd.fi.uncoma.edu.ar#Person>

    Domain:
        <http://crowd.fi.uncoma.edu.ar#Inoculation>

    Range:
        xsd:string


DataProperty: <http://crowd.fi.uncoma.edu.ar#batch>

    Domain:
        <http://crowd.fi.uncoma.edu.ar#Inoculation>

    Range:
        xsd:string


DataProperty: <http://crowd.fi.uncoma.edu.ar#country>

    Domain:
        <http://crowd.fi.uncoma.edu.ar#Regulatory-Body>

    Range:
        xsd:string


DataProperty: <http://crowd.fi.uncoma.edu.ar#date>

    Domain:
        <http://crowd.fi.uncoma.edu.ar#Inoculation>

    Range:
        xsd:date


DataProperty: <http://crowd.fi.uncoma.edu.ar#name>

    Domain:
        <http://crowd.fi.uncoma.edu.ar#COVID-19Medicine>,
        <http://crowd.fi.uncoma.edu.ar#Regulatory-Body>

    Range:
        xsd:string


Class: <http://crowd.fi.uncoma.edu.ar#As2>

    SubClassOf:
        <http://crowd.fi.uncoma.edu.ar#role-b-2> some <http://crowd.fi.uncoma.edu.ar#Inoculation>,
        <http://crowd.fi.uncoma.edu.ar#with> some <http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine>


Class: <http://crowd.fi.uncoma.edu.ar#As3>

    SubClassOf:
        <http://crowd.fi.uncoma.edu.ar#As2>,
        <http://crowd.fi.uncoma.edu.ar#role-b-2> some <http://crowd.fi.uncoma.edu.ar#Inoculation>,
        <http://crowd.fi.uncoma.edu.ar#with> some <http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine>


Class: <http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug>

    SubClassOf:
        <http://crowd.fi.uncoma.edu.ar#COVID-19Medicine>

    DisjointWith:
        <http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine>


Class: <http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine>

    SubClassOf:
        <http://crowd.fi.uncoma.edu.ar#COVID-19Medicine>

    DisjointWith:
        <http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug>


Class: <http://crowd.fi.uncoma.edu.ar#COVID-19Medicine>

    SubClassOf:
        <http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug> or <http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine>,
        <http://crowd.fi.uncoma.edu.ar#name> max 1 xsd:string


Class: <http://crowd.fi.uncoma.edu.ar#Inoculation>

    SubClassOf:
         inverse (<http://crowd.fi.uncoma.edu.ar#role-b-2>) min 1 <http://crowd.fi.uncoma.edu.ar#As2>,
         inverse (<http://crowd.fi.uncoma.edu.ar#role-b-2>) max 1 <http://crowd.fi.uncoma.edu.ar#As2>,
         inverse (<http://crowd.fi.uncoma.edu.ar#role-b-2>) max 1 <http://crowd.fi.uncoma.edu.ar#As3>,
        <http://crowd.fi.uncoma.edu.ar#Person> max 1 xsd:string,
        <http://crowd.fi.uncoma.edu.ar#batch> max 1 xsd:string,
        <http://crowd.fi.uncoma.edu.ar#date> max 1 xsd:date


Class: <http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine>

    SubClassOf:
        <http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine>,
         inverse (<http://crowd.fi.uncoma.edu.ar#with>) max 2 <http://crowd.fi.uncoma.edu.ar#As3>


Class: <http://crowd.fi.uncoma.edu.ar#Regulatory-Body>

    SubClassOf:
        <http://crowd.fi.uncoma.edu.ar#country> max 1 xsd:string,
        <http://crowd.fi.uncoma.edu.ar#name> max 1 xsd:string


Class: <http://crowd.fi.uncoma.edu.ar#as1>

    SubClassOf:
        <http://crowd.fi.uncoma.edu.ar#approved-by> some <http://crowd.fi.uncoma.edu.ar#Regulatory-Body>,
        <http://crowd.fi.uncoma.edu.ar#of> some <http://crowd.fi.uncoma.edu.ar#COVID-19Medicine>
';


        $converter = new ConverterConnector();

        $converter->run_converter($input,"owl/xml","manchestersyntax");
        $actual = $converter->get_col_answers()[0];

        //$this->assertSame($expected, $actual, true);
    }

    /**
    @testdox Converting from OWL/XML to FUNCTIONAL
    */
    public function testConverterFunctional(){
        $input = '<?xml version="1.0" encoding="UTF-8"?>
        <Ontology xmlns="http://www.w3.org/2002/07/owl#" xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#" xmlns:xml="http://www.w3.org/XML/1998/namespace" xmlns:xsd="http://www.w3.org/2001/XMLSchema#" xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#" xml:base="http://crowd.fi.uncoma.edu.ar/kb1#" ontologyIRI="http://crowd.fi.uncoma.edu.ar/kb1#">
        <Prefix name="rdf" IRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
        <Prefix name="rdfs" IRI="http://www.w3.org/2000/01/rdf-schema#"/>
        <Prefix name="xsd" IRI="http://www.w3.org/2001/XMLSchema#"/>
        <Prefix name="owl" IRI="http://www.w3.org/2002/07/owl#"/>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#of"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#approved-by"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#with"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#with"/>
        </Declaration>
        <Declaration>
          <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#as1"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
        </Declaration>
        <Declaration>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
        </Declaration>
        <Declaration>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
        </Declaration>
        <Declaration>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#country"/>
        </Declaration>
        <Declaration>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#Person"/>
        </Declaration>
        <Declaration>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#date"/>
        </Declaration>
        <Declaration>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#batch"/>
        </Declaration>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
        </SubClassOf>
        <DisjointClasses>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
        </DisjointClasses>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
          <ObjectUnionOf>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
          </ObjectUnionOf>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#as1"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#of"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#as1"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#approved-by"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#with"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <ObjectMinCardinality cardinality="1">
            <ObjectInverseOf>
              <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
            </ObjectInverseOf>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
          </ObjectMinCardinality>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <ObjectMaxCardinality cardinality="1">
            <ObjectInverseOf>
              <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
            </ObjectInverseOf>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#As2"/>
          </ObjectMaxCardinality>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#with"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine"/>
          <ObjectMaxCardinality cardinality="2">
            <ObjectInverseOf>
              <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#with"/>
            </ObjectInverseOf>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
          </ObjectMaxCardinality>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
          <ObjectSomeValuesFrom>
            <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          </ObjectSomeValuesFrom>
        </SubClassOf>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <ObjectMaxCardinality cardinality="1">
            <ObjectInverseOf>
              <ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar#role-b-2"/>
            </ObjectInverseOf>
            <Class IRI="http://crowd.fi.uncoma.edu.ar#As3"/>
          </ObjectMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#COVID-19Medicine"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
          </DataMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#name"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
          </DataMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#country"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#country"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Regulatory-Body"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#country"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
          </DataMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#Person"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#Person"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#Person"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
          </DataMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#date"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#date"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#date"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#date"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#date"/>
          </DataMaxCardinality>
        </SubClassOf>
        <DataPropertyDomain>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#batch"/>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
        </DataPropertyDomain>
        <DataPropertyRange>
          <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#batch"/>
          <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
        </DataPropertyRange>
        <SubClassOf>
          <Class IRI="http://crowd.fi.uncoma.edu.ar#Inoculation"/>
          <DataMaxCardinality cardinality="1">
            <DataProperty IRI="http://crowd.fi.uncoma.edu.ar#batch"/>
            <Datatype IRI="http://www.w3.org/2001/XMLSchema#string"/>
          </DataMaxCardinality>
        </SubClassOf>
        </Ontology>';

        $expected = 'Prefix(:=<http://crowd.fi.uncoma.edu.ar/kb1#>)
Prefix(owl:=<http://www.w3.org/2002/07/owl#>)
Prefix(rdf:=<http://www.w3.org/1999/02/22-rdf-syntax-ns#>)
Prefix(xml:=<http://www.w3.org/XML/1998/namespace>)
Prefix(xsd:=<http://www.w3.org/2001/XMLSchema#>)
Prefix(rdfs:=<http://www.w3.org/2000/01/rdf-schema#>)


Ontology(<http://crowd.fi.uncoma.edu.ar/kb1#>

Declaration(Class(<http://crowd.fi.uncoma.edu.ar#Inoculation>))
Declaration(Class(<http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine>))
Declaration(Class(<http://crowd.fi.uncoma.edu.ar#As2>))
Declaration(Class(<http://crowd.fi.uncoma.edu.ar#As3>))
Declaration(Class(<http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine>))
Declaration(Class(<http://crowd.fi.uncoma.edu.ar#as1>))
Declaration(Class(<http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug>))
Declaration(Class(<http://crowd.fi.uncoma.edu.ar#COVID-19Medicine>))
Declaration(Class(<http://crowd.fi.uncoma.edu.ar#Regulatory-Body>))
Declaration(ObjectProperty(<http://crowd.fi.uncoma.edu.ar#role-b-2>))
Declaration(ObjectProperty(<http://crowd.fi.uncoma.edu.ar#of>))
Declaration(ObjectProperty(<http://crowd.fi.uncoma.edu.ar#approved-by>))
Declaration(ObjectProperty(<http://crowd.fi.uncoma.edu.ar#with>))
Declaration(DataProperty(<http://crowd.fi.uncoma.edu.ar#batch>))
Declaration(DataProperty(<http://crowd.fi.uncoma.edu.ar#name>))
Declaration(DataProperty(<http://crowd.fi.uncoma.edu.ar#date>))
Declaration(DataProperty(<http://crowd.fi.uncoma.edu.ar#Person>))
Declaration(DataProperty(<http://crowd.fi.uncoma.edu.ar#country>))
Declaration(Datatype(xsd:date))

############################
#   Data Properties
############################

# Data Property: <http://crowd.fi.uncoma.edu.ar#Person> (<http://crowd.fi.uncoma.edu.ar#Person>)

DataPropertyDomain(<http://crowd.fi.uncoma.edu.ar#Person> <http://crowd.fi.uncoma.edu.ar#Inoculation>)
DataPropertyRange(<http://crowd.fi.uncoma.edu.ar#Person> xsd:string)

# Data Property: <http://crowd.fi.uncoma.edu.ar#batch> (<http://crowd.fi.uncoma.edu.ar#batch>)

DataPropertyDomain(<http://crowd.fi.uncoma.edu.ar#batch> <http://crowd.fi.uncoma.edu.ar#Inoculation>)
DataPropertyRange(<http://crowd.fi.uncoma.edu.ar#batch> xsd:string)

# Data Property: <http://crowd.fi.uncoma.edu.ar#country> (<http://crowd.fi.uncoma.edu.ar#country>)

DataPropertyDomain(<http://crowd.fi.uncoma.edu.ar#country> <http://crowd.fi.uncoma.edu.ar#Regulatory-Body>)
DataPropertyRange(<http://crowd.fi.uncoma.edu.ar#country> xsd:string)

# Data Property: <http://crowd.fi.uncoma.edu.ar#date> (<http://crowd.fi.uncoma.edu.ar#date>)

DataPropertyDomain(<http://crowd.fi.uncoma.edu.ar#date> <http://crowd.fi.uncoma.edu.ar#Inoculation>)
DataPropertyRange(<http://crowd.fi.uncoma.edu.ar#date> xsd:date)

# Data Property: <http://crowd.fi.uncoma.edu.ar#name> (<http://crowd.fi.uncoma.edu.ar#name>)

DataPropertyDomain(<http://crowd.fi.uncoma.edu.ar#name> <http://crowd.fi.uncoma.edu.ar#COVID-19Medicine>)
DataPropertyDomain(<http://crowd.fi.uncoma.edu.ar#name> <http://crowd.fi.uncoma.edu.ar#Regulatory-Body>)
DataPropertyRange(<http://crowd.fi.uncoma.edu.ar#name> xsd:string)



############################
#   Classes
############################

# Class: <http://crowd.fi.uncoma.edu.ar#As2> (<http://crowd.fi.uncoma.edu.ar#As2>)

SubClassOf(<http://crowd.fi.uncoma.edu.ar#As2> ObjectSomeValuesFrom(<http://crowd.fi.uncoma.edu.ar#role-b-2> <http://crowd.fi.uncoma.edu.ar#Inoculation>))
SubClassOf(<http://crowd.fi.uncoma.edu.ar#As2> ObjectSomeValuesFrom(<http://crowd.fi.uncoma.edu.ar#with> <http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine>))

# Class: <http://crowd.fi.uncoma.edu.ar#As3> (<http://crowd.fi.uncoma.edu.ar#As3>)

SubClassOf(<http://crowd.fi.uncoma.edu.ar#As3> <http://crowd.fi.uncoma.edu.ar#As2>)
SubClassOf(<http://crowd.fi.uncoma.edu.ar#As3> ObjectSomeValuesFrom(<http://crowd.fi.uncoma.edu.ar#role-b-2> <http://crowd.fi.uncoma.edu.ar#Inoculation>))
SubClassOf(<http://crowd.fi.uncoma.edu.ar#As3> ObjectSomeValuesFrom(<http://crowd.fi.uncoma.edu.ar#with> <http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine>))

# Class: <http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug> (<http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug>)

SubClassOf(<http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug> <http://crowd.fi.uncoma.edu.ar#COVID-19Medicine>)
DisjointClasses(<http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug> <http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine>)

# Class: <http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine> (<http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine>)

SubClassOf(<http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine> <http://crowd.fi.uncoma.edu.ar#COVID-19Medicine>)

# Class: <http://crowd.fi.uncoma.edu.ar#COVID-19Medicine> (<http://crowd.fi.uncoma.edu.ar#COVID-19Medicine>)

SubClassOf(<http://crowd.fi.uncoma.edu.ar#COVID-19Medicine> ObjectUnionOf(<http://crowd.fi.uncoma.edu.ar#COVID-19-treatment-drug> <http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine>))
SubClassOf(<http://crowd.fi.uncoma.edu.ar#COVID-19Medicine> DataMaxCardinality(1 <http://crowd.fi.uncoma.edu.ar#name> xsd:string))

# Class: <http://crowd.fi.uncoma.edu.ar#Inoculation> (<http://crowd.fi.uncoma.edu.ar#Inoculation>)

SubClassOf(<http://crowd.fi.uncoma.edu.ar#Inoculation> ObjectMinCardinality(1 ObjectInverseOf(<http://crowd.fi.uncoma.edu.ar#role-b-2>) <http://crowd.fi.uncoma.edu.ar#As2>))
SubClassOf(<http://crowd.fi.uncoma.edu.ar#Inoculation> ObjectMaxCardinality(1 ObjectInverseOf(<http://crowd.fi.uncoma.edu.ar#role-b-2>) <http://crowd.fi.uncoma.edu.ar#As2>))
SubClassOf(<http://crowd.fi.uncoma.edu.ar#Inoculation> ObjectMaxCardinality(1 ObjectInverseOf(<http://crowd.fi.uncoma.edu.ar#role-b-2>) <http://crowd.fi.uncoma.edu.ar#As3>))
SubClassOf(<http://crowd.fi.uncoma.edu.ar#Inoculation> DataMaxCardinality(1 <http://crowd.fi.uncoma.edu.ar#Person> xsd:string))
SubClassOf(<http://crowd.fi.uncoma.edu.ar#Inoculation> DataMaxCardinality(1 <http://crowd.fi.uncoma.edu.ar#batch> xsd:string))
SubClassOf(<http://crowd.fi.uncoma.edu.ar#Inoculation> DataMaxCardinality(1 <http://crowd.fi.uncoma.edu.ar#date> xsd:date))

# Class: <http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine> (<http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine>)

SubClassOf(<http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine> <http://crowd.fi.uncoma.edu.ar#COVID-19-vaccine>)
SubClassOf(<http://crowd.fi.uncoma.edu.ar#Pfizer-Vaccine> ObjectMaxCardinality(2 ObjectInverseOf(<http://crowd.fi.uncoma.edu.ar#with>) <http://crowd.fi.uncoma.edu.ar#As3>))

# Class: <http://crowd.fi.uncoma.edu.ar#Regulatory-Body> (<http://crowd.fi.uncoma.edu.ar#Regulatory-Body>)

SubClassOf(<http://crowd.fi.uncoma.edu.ar#Regulatory-Body> DataMaxCardinality(1 <http://crowd.fi.uncoma.edu.ar#country> xsd:string))
SubClassOf(<http://crowd.fi.uncoma.edu.ar#Regulatory-Body> DataMaxCardinality(1 <http://crowd.fi.uncoma.edu.ar#name> xsd:string))

# Class: <http://crowd.fi.uncoma.edu.ar#as1> (<http://crowd.fi.uncoma.edu.ar#as1>)

SubClassOf(<http://crowd.fi.uncoma.edu.ar#as1> ObjectSomeValuesFrom(<http://crowd.fi.uncoma.edu.ar#approved-by> <http://crowd.fi.uncoma.edu.ar#Regulatory-Body>))
SubClassOf(<http://crowd.fi.uncoma.edu.ar#as1> ObjectSomeValuesFrom(<http://crowd.fi.uncoma.edu.ar#of> <http://crowd.fi.uncoma.edu.ar#COVID-19Medicine>))


)';


        $converter = new ConverterConnector();

        $converter->run_converter($input,"owl/xml","functionalsyntax");
        $actual = $converter->get_col_answers()[0];

        $this->assertSame($expected, $actual, true);
    }
}
