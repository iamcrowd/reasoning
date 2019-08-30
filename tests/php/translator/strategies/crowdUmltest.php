<?php
/*

   Copyright 2016 GILIA, Departamento de Teoría de la Computación, Universidad Nacional del Comahue

   Author: GILIA

   crowdUMLtest.php

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
load("crowd_uml.php", "wicom/translator/strategies/");
load("owllinkbuilder.php", "wicom/translator/builders/");
load("umljsonbuilder.php", "wicom/translator/builders/");
load("decoder.php", "wicom/translator/");


use Wicom\Translator\Strategies\UMLcrowd;
use Wicom\Translator\Builders\OWLlinkBuilder;
use Wicom\Translator\Builders\UMLJSONBuilder;
/**
   # Warning!
   Don't use assertEqualXMLStructure()! It won't check for attributes values!

   It will only check for the amount of attributes.

   In order to keep interoperability with Protégé, roles on left sides of owl expressions
   must have fillers (top or class). Look at next role domain expression:

   <owl:SubClassOf>
     <owl:ObjectSomeValuesFrom>
         <owl:ObjectProperty IRI="r1"/>
         <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
     </owl:ObjectSomeValuesFrom>
     <owl:Class IRI="PhoneCall"/>
   </owl:SubClassOf>

   However, right sides accept roles without fillers. Look at following max cardinality expression:

   <owl:SubClassOf>
          <owl:Class IRI="PhoneCall"/>
         <owl:ObjectMaxCardinality cardinality="1">
              <owl:ObjectProperty IRI="r1"/>
         </owl:ObjectMaxCardinality>
   </owl:SubClassOf>

 */


class UMLcrowdTest extends PHPUnit\Framework\TestCase
{

/*
  public function testAttributesTranslate(){
  $json = <<<EOT
{"namespaces":
  {"ontologyIRI":[{"prefix":"crowd","value":"http://crowd.fi.uncoma.edu.ar/kb1/"}],
   "defaultIRIs":[{"prefix":"rdf","value":"http://www.w3.org/1999/02/22-rdf-syntax-ns#"},
                  {"prefix":"rdfs","value":"http://www.w3.org/2000/01/rdf-schema#"},
                  {"prefix":"xsd","value":"http://www.w3.org/2001/XMLSchema#"},
                  {"prefix":"owl","value":"http://www.w3.org/2002/07/owl#"}],
   "IRIs":[]},
   "classes":[{"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class2",
               "attrs":[{"name":"http://crowd.fi.uncoma.edu.ar/kb1/attribute",
                         "datatype":"http://www.w3.org/2001/XMLSchema#integer"}],
               "methods":[],"position":{"x":247,"y":157}}],
   "links":[]
 }
EOT;

      $expected = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
        <RequestMessage
        xmlns="http://www.owllink.org/owllink#"
        xmlns:owl="http://www.w3.org/2002/07/owl#"
        xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
        xsi:schemaLocation="http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd"
        xml:base="http://crowd.fi.uncoma.edu.ar/kb1/">
        <CreateKB kb="http://crowd.fi.uncoma.edu.ar/kb1/">
          <Prefix name="crowd" fullIRI="http://crowd.fi.uncoma.edu.ar/kb1/"/>
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

            <owl:SubDataPropertyOf>
              <owl:DataProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/attribute"/>
              <owl:DataProperty IRI="http://www.w3.org/2002/07/owl#topDataProperty"/>
            </owl:SubDataPropertyOf>

            <owl:DataPropertyDomain>
                <owl:DataProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/attribute"/>
                <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
            </owl:DataPropertyDomain>

            <owl:DataPropertyRange>
                <owl:DataProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/attribute"/>
                <owl:Datatype IRI="http://www.w3.org/2001/XMLSchema#integer"/>
            </owl:DataPropertyRange>

            <owl:SubClassOf>
        	     <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2" />
        	     <owl:DataMaxCardinality cardinality="1">
        	       <owl:DataProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/attribute" />
               </owl:DataMaxCardinality>
            </owl:SubClassOf>

        </Tell>
        </RequestMessage>
EOT;

      $strategy = new UMLcrowd();
      $builder = new OWLlinkBuilder();

      $builder->insert_header();
      $strategy->translate($json, $builder);
      $builder->insert_footer();

      $actual = $builder->get_product();
      $actual = $actual->to_string();
      $this->assertXmlStringEqualsXmlString($expected, $actual,true);
}
*/
    public function testTranslateBinaryRolesWithoutClass0N(){
		$json = <<<EOT
{"namespaces":{"ontologyIRI":[{"prefix":"crowd","value":"http://crowd.fi.uncoma.edu.ar/kb1/"}],
              "defaultIRIs":[{"prefix":"rdf","value":"http://www.w3.org/1999/02/22-rdf-syntax-ns#"},
                             {"prefix":"rdfs","value":"http://www.w3.org/2000/01/rdf-schema#"},
                             {"prefix":"xsd","value":"http://www.w3.org/2001/XMLSchema#"},
                             {"prefix":"owl","value":"http://www.w3.org/2002/07/owl#"}],
              "IRIs":[]},
"classes": [{"name":"http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall", "attrs":[], "methods":[]},
			 {"name":"http://crowd.fi.uncoma.edu.ar/kb1/Phone", "attrs":[], "methods":[]}],
"links": [{"name": "http://crowd.fi.uncoma.edu.ar/kb1/r1",
  "classes":["http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall","http://crowd.fi.uncoma.edu.ar/kb1/Phone"],
  "multiplicity":["0..*","0..*"], "type":"association"}]
}
EOT;

        $expected = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#"
		xmlns:owl="http://www.w3.org/2002/07/owl#"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd"
    xml:base="http://crowd.fi.uncoma.edu.ar/kb1/">
    <CreateKB kb="http://crowd.fi.uncoma.edu.ar/kb1/">
    <Prefix name="crowd" fullIRI="http://crowd.fi.uncoma.edu.ar/kb1/" />
    <Prefix name="rdf" fullIRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
    <Prefix name="rdfs" fullIRI="http://www.w3.org/2000/01/rdf-schema#"/>
    <Prefix name="xsd" fullIRI="http://www.w3.org/2001/XMLSchema#"/>
    <Prefix name="owl" fullIRI="http://www.w3.org/2002/07/owl#"/>
    </CreateKB>
    <Set kb="http://crowd.fi.uncoma.edu.ar/kb1/" key="abbreviatesIRIs"><Literal>false</Literal></Set>
  <Tell kb="http://crowd.fi.uncoma.edu.ar/kb1/">
    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall" />
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone" />
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:SubClassOf>

    <owl:SubObjectPropertyOf>
      <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
      <owl:ObjectProperty IRI="http://www.w3.org/2002/07/owl#topObjectProperty"/>
    </owl:SubObjectPropertyOf>

    <owl:SubClassOf>
  		<owl:ObjectSomeValuesFrom>
          <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
          <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
      </owl:ObjectSomeValuesFrom>
  		<owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
    </owl:SubClassOf>

    <owl:SubClassOf>
  		<owl:ObjectSomeValuesFrom>
  			<owl:ObjectInverseOf>
              	<owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
  			</owl:ObjectInverseOf>
        <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
      </owl:ObjectSomeValuesFrom>
  		<owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
    </owl:SubClassOf>

    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
            <owl:ObjectMinCardinality cardinality="1">
                <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
            <owl:ObjectMinCardinality cardinality="2">
                <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
            <owl:ObjectMinCardinality cardinality="3">
                <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
            <owl:ObjectMinCardinality cardinality="4">
                <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
            <owl:ObjectMinCardinality cardinality="5">
                <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
            <owl:ObjectMinCardinality cardinality="6">
                <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
            <owl:ObjectMinCardinality cardinality="7">
                <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
            <owl:ObjectMinCardinality cardinality="8">
                <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
            <owl:ObjectMinCardinality cardinality="9">
                <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall_http://crowd.fi.uncoma.edu.ar/kb1/r1_max"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
            <owl:ObjectMaxCardinality cardinality="1">
                <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
            </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall_http://crowd.fi.uncoma.edu.ar/kb1/r1_max"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
            <owl:ObjectMaxCardinality cardinality="2">
                <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
            </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall_http://crowd.fi.uncoma.edu.ar/kb1/r1_max"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
            <owl:ObjectMaxCardinality cardinality="3">
                <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
            </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall_http://crowd.fi.uncoma.edu.ar/kb1/r1_max"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
            <owl:ObjectMaxCardinality cardinality="4">
                <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
            </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall_http://crowd.fi.uncoma.edu.ar/kb1/r1_max"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
            <owl:ObjectMaxCardinality cardinality="5">
                <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
            </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall_http://crowd.fi.uncoma.edu.ar/kb1/r1_max"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
            <owl:ObjectMaxCardinality cardinality="6">
                <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
            </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall_http://crowd.fi.uncoma.edu.ar/kb1/r1_max"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
            <owl:ObjectMaxCardinality cardinality="7">
                <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
            </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall_http://crowd.fi.uncoma.edu.ar/kb1/r1_max"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
            <owl:ObjectMaxCardinality cardinality="8">
                <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
            </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall_http://crowd.fi.uncoma.edu.ar/kb1/r1_max"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
            <owl:ObjectMaxCardinality cardinality="9">
                <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
            </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMinCardinality cardinality="1">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMinCardinality cardinality="2">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMinCardinality cardinality="3">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMinCardinality cardinality="4">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMinCardinality cardinality="5">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMinCardinality cardinality="6">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMinCardinality cardinality="7">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMinCardinality cardinality="8">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMinCardinality cardinality="9">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_max"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMaxCardinality cardinality="1">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_max"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMaxCardinality cardinality="2">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_max"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMaxCardinality cardinality="3">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_max"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMaxCardinality cardinality="4">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_max"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMaxCardinality cardinality="5">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_max"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMaxCardinality cardinality="6">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_max"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMaxCardinality cardinality="7">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_max"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMaxCardinality cardinality="8">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_max"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMaxCardinality cardinality="9">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
  </Tell>
</RequestMessage>
EOT;

        $strategy = new UMLcrowd();
        $builder = new OWLlinkBuilder();

        $builder->insert_header();
        $strategy->translate($json, $builder);
        $builder->insert_footer();

        $actual = $builder->get_product();
        $actual = $actual->to_string();
        $this->assertXmlStringEqualsXmlString($expected, $actual,true);
  }


  public function testTranslateBinaryRolesWithClassMN(){
  $json = <<<EOT
{"namespaces":
      {"ontologyIRI":
          [{"prefix":"crowd","value":"http://crowd.fi.uncoma.edu.ar/kb1/"}],
       "defaultIRIs":
          [{"prefix":"rdf","value":"http://www.w3.org/1999/02/22-rdf-syntax-ns#"},
           {"prefix":"rdfs","value":"http://www.w3.org/2000/01/rdf-schema#"},
           {"prefix":"xsd","value":"http://www.w3.org/2001/XMLSchema#"},
           {"prefix":"owl","value":"http://www.w3.org/2002/07/owl#"}],
        "IRIs":[]},
    "classes":
          [{"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class1","attrs":[],"methods":[],"position":{"x":154,"y":197}},
           {"name":"http://crowd.fi.uncoma.edu.ar/kb1/Class2","attrs":[],"methods":[],"position":{"x":851,"y":223}},
           {"name":"http://crowd.fi.uncoma.edu.ar/kb1/r1","attrs":[],"methods":[],"position":{"x":559,"y":417}}],
    "links":
          [{"name":"http://crowd.fi.uncoma.edu.ar/kb1/r1",
            "classes":["http://crowd.fi.uncoma.edu.ar/kb1/Class1","http://crowd.fi.uncoma.edu.ar/kb1/Class2"],
            "multiplicity":["3..5","1..2"],
            "roles":["http://crowd.fi.uncoma.edu.ar/kb1/class1", "http://crowd.fi.uncoma.edu.ar/kb1/class2"],
            "associated_class":{"name":"http://crowd.fi.uncoma.edu.ar/kb1/r1","attrs":[],"methods":[],"position":{"x":559,"y":417}},
            "type":"association with class",
            "position":{"x":502.5,"y":210}}]
}
EOT;

      $expected = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#"
  xmlns:owl="http://www.w3.org/2002/07/owl#"
  xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
  xsi:schemaLocation="http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd"
  xml:base="http://crowd.fi.uncoma.edu.ar/kb1/">
  <CreateKB kb="http://crowd.fi.uncoma.edu.ar/kb1/">
  <Prefix name="crowd" fullIRI="http://crowd.fi.uncoma.edu.ar/kb1/" />
  <Prefix name="rdf" fullIRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
  <Prefix name="rdfs" fullIRI="http://www.w3.org/2000/01/rdf-schema#"/>
  <Prefix name="xsd" fullIRI="http://www.w3.org/2001/XMLSchema#"/>
  <Prefix name="owl" fullIRI="http://www.w3.org/2002/07/owl#"/>
  </CreateKB>
  <Set kb="http://crowd.fi.uncoma.edu.ar/kb1/" key="abbreviatesIRIs"><Literal>false</Literal></Set>
<Tell kb="http://crowd.fi.uncoma.edu.ar/kb1/">
  <owl:SubClassOf>
    <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1" />
    <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
  </owl:SubClassOf>
  <owl:SubClassOf>
    <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2" />
    <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
  </owl:SubClassOf>
  <owl:SubClassOf>
    <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1" />
    <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
  </owl:SubClassOf>
  <owl:SubObjectPropertyOf>
    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class1"/>
    <owl:ObjectProperty IRI="http://www.w3.org/2002/07/owl#topObjectProperty"/>
  </owl:SubObjectPropertyOf>
  <owl:SubObjectPropertyOf>
    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class2"/>
    <owl:ObjectProperty IRI="http://www.w3.org/2002/07/owl#topObjectProperty"/>
  </owl:SubObjectPropertyOf>

  <owl:SubClassOf>
    <owl:ObjectSomeValuesFrom>
        <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class1"/>
        <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:ObjectSomeValuesFrom>
    <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
  </owl:SubClassOf>

  <owl:SubClassOf>
    <owl:ObjectSomeValuesFrom>
      <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class1"/>
      </owl:ObjectInverseOf>
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:ObjectSomeValuesFrom>
    <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1"/>
  </owl:SubClassOf>

  <owl:SubClassOf>
    <owl:ObjectSomeValuesFrom>
        <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class2"/>
        <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:ObjectSomeValuesFrom>
    <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
  </owl:SubClassOf>

  <owl:SubClassOf>
    <owl:ObjectSomeValuesFrom>
      <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class2"/>
      </owl:ObjectInverseOf>
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:ObjectSomeValuesFrom>
    <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
  </owl:SubClassOf>

  <owl:SubClassOf>
    <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>

    <owl:ObjectIntersectionOf>

      <owl:ObjectSomeValuesFrom>
        <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class1"/>
        <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
      </owl:ObjectSomeValuesFrom>

      <owl:ObjectMaxCardinality cardinality="1">
          <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class1"/>
      </owl:ObjectMaxCardinality>

      <owl:ObjectSomeValuesFrom>
        <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class2"/>
        <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
      </owl:ObjectSomeValuesFrom>

      <owl:ObjectMaxCardinality cardinality="1">
          <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class2"/>
      </owl:ObjectMaxCardinality>
      
    </owl:ObjectIntersectionOf>
  </owl:SubClassOf>

  <owl:SubClassOf>
         <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1"/>
         <owl:ObjectMinCardinality cardinality="1">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class1"/>
            </owl:ObjectInverseOf>
        </owl:ObjectMinCardinality>
  </owl:SubClassOf>


  <owl:SubClassOf>
         <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1"/>
         <owl:ObjectMaxCardinality cardinality="2">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class1"/>
            </owl:ObjectInverseOf>
        </owl:ObjectMaxCardinality>
  </owl:SubClassOf>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1_http://crowd.fi.uncoma.edu.ar/kb1/class1_min"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1"/>
          <owl:ObjectMinCardinality cardinality="1">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class1"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMinCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1_http://crowd.fi.uncoma.edu.ar/kb1/class1_min"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1"/>
          <owl:ObjectMinCardinality cardinality="2">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class1"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMinCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1_http://crowd.fi.uncoma.edu.ar/kb1/class1_min"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1"/>
          <owl:ObjectMinCardinality cardinality="3">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class1"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMinCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1_http://crowd.fi.uncoma.edu.ar/kb1/class1_min"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1"/>
          <owl:ObjectMinCardinality cardinality="4">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class1"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMinCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1_http://crowd.fi.uncoma.edu.ar/kb1/class1_min"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1"/>
          <owl:ObjectMinCardinality cardinality="5">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class1"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMinCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1_http://crowd.fi.uncoma.edu.ar/kb1/class1_min"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1"/>
          <owl:ObjectMinCardinality cardinality="6">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class1"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMinCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1_http://crowd.fi.uncoma.edu.ar/kb1/class1_min"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1"/>
          <owl:ObjectMinCardinality cardinality="7">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class1"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMinCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1_http://crowd.fi.uncoma.edu.ar/kb1/class1_min"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1"/>
          <owl:ObjectMinCardinality cardinality="8">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class1"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMinCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1_http://crowd.fi.uncoma.edu.ar/kb1/class1_min"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1"/>
          <owl:ObjectMinCardinality cardinality="9">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class1"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMinCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1_http://crowd.fi.uncoma.edu.ar/kb1/class1_max"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1"/>
          <owl:ObjectMaxCardinality cardinality="1">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class1"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMaxCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1_http://crowd.fi.uncoma.edu.ar/kb1/class1_max"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1"/>
          <owl:ObjectMaxCardinality cardinality="2">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class1"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMaxCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1_http://crowd.fi.uncoma.edu.ar/kb1/class1_max"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1"/>
          <owl:ObjectMaxCardinality cardinality="3">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class1"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMaxCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1_http://crowd.fi.uncoma.edu.ar/kb1/class1_max"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1"/>
          <owl:ObjectMaxCardinality cardinality="4">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class1"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMaxCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1_http://crowd.fi.uncoma.edu.ar/kb1/class1_max"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1"/>
          <owl:ObjectMaxCardinality cardinality="5">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class1"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMaxCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1_http://crowd.fi.uncoma.edu.ar/kb1/class1_max"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1"/>
          <owl:ObjectMaxCardinality cardinality="6">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class1"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMaxCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1_http://crowd.fi.uncoma.edu.ar/kb1/class1_max"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1"/>
          <owl:ObjectMaxCardinality cardinality="7">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class1"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMaxCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1_http://crowd.fi.uncoma.edu.ar/kb1/class1_max"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1"/>
          <owl:ObjectMaxCardinality cardinality="8">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class1"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMaxCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1_http://crowd.fi.uncoma.edu.ar/kb1/class1_max"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1"/>
          <owl:ObjectMaxCardinality cardinality="9">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class1"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMaxCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>


  <owl:SubClassOf>
         <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
         <owl:ObjectMinCardinality cardinality="3">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class2"/>
            </owl:ObjectInverseOf>
        </owl:ObjectMinCardinality>
  </owl:SubClassOf>


  <owl:SubClassOf>
         <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
         <owl:ObjectMaxCardinality cardinality="5">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class2"/>
            </owl:ObjectInverseOf>
        </owl:ObjectMaxCardinality>
  </owl:SubClassOf>



  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2_http://crowd.fi.uncoma.edu.ar/kb1/class2_min"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
          <owl:ObjectMinCardinality cardinality="1">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class2"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMinCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2_http://crowd.fi.uncoma.edu.ar/kb1/class2_min"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
          <owl:ObjectMinCardinality cardinality="2">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class2"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMinCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2_http://crowd.fi.uncoma.edu.ar/kb1/class2_min"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
          <owl:ObjectMinCardinality cardinality="3">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class2"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMinCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2_http://crowd.fi.uncoma.edu.ar/kb1/class2_min"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
          <owl:ObjectMinCardinality cardinality="4">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class2"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMinCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2_http://crowd.fi.uncoma.edu.ar/kb1/class2_min"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
          <owl:ObjectMinCardinality cardinality="5">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class2"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMinCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2_http://crowd.fi.uncoma.edu.ar/kb1/class2_min"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
          <owl:ObjectMinCardinality cardinality="6">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class2"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMinCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2_http://crowd.fi.uncoma.edu.ar/kb1/class2_min"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
          <owl:ObjectMinCardinality cardinality="7">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class2"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMinCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2_http://crowd.fi.uncoma.edu.ar/kb1/class2_min"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
          <owl:ObjectMinCardinality cardinality="8">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class2"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMinCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2_http://crowd.fi.uncoma.edu.ar/kb1/class2_min"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
          <owl:ObjectMinCardinality cardinality="9">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class2"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMinCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2_http://crowd.fi.uncoma.edu.ar/kb1/class2_max"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
          <owl:ObjectMaxCardinality cardinality="1">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class2"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMaxCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2_http://crowd.fi.uncoma.edu.ar/kb1/class2_max"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
          <owl:ObjectMaxCardinality cardinality="2">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class2"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMaxCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2_http://crowd.fi.uncoma.edu.ar/kb1/class2_max"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
          <owl:ObjectMaxCardinality cardinality="3">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class2"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMaxCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2_http://crowd.fi.uncoma.edu.ar/kb1/class2_max"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
          <owl:ObjectMaxCardinality cardinality="4">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class2"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMaxCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2_http://crowd.fi.uncoma.edu.ar/kb1/class2_max"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
          <owl:ObjectMaxCardinality cardinality="5">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class2"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMaxCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2_http://crowd.fi.uncoma.edu.ar/kb1/class2_max"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
          <owl:ObjectMaxCardinality cardinality="6">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class2"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMaxCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2_http://crowd.fi.uncoma.edu.ar/kb1/class2_max"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
          <owl:ObjectMaxCardinality cardinality="7">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class2"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMaxCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2_http://crowd.fi.uncoma.edu.ar/kb1/class2_max"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
          <owl:ObjectMaxCardinality cardinality="8">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class2"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMaxCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

  <owl:EquivalentClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2_http://crowd.fi.uncoma.edu.ar/kb1/class2_max"/>
      <owl:ObjectIntersectionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
          <owl:ObjectMaxCardinality cardinality="9">
            <owl:ObjectInverseOf>
              <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/class2"/>
            </owl:ObjectInverseOf>
          </owl:ObjectMaxCardinality>
      </owl:ObjectIntersectionOf>
  </owl:EquivalentClasses>

</Tell>
</RequestMessage>
EOT;

      $strategy = new UMLcrowd();
      $builder = new OWLlinkBuilder();

      $builder->insert_header();
      $strategy->translate($json, $builder);
      $builder->insert_footer();

      $actual = $builder->get_product();
      $actual = $actual->to_string();
      $this->assertXmlStringEqualsXmlString($expected, $actual,true);
}

/*
    public function testTranslateBinaryRolesWithoutClass01(){
		$json = <<< EOT
{
"classes": [{"name":"http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall", "attrs":[], "methods":[]},
			 {"name":"http://crowd.fi.uncoma.edu.ar/kb1/Phone", "attrs":[], "methods":[]}],
"links": [{"name": "http://crowd.fi.uncoma.edu.ar/kb1/r1",
"classes":["http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall","http://crowd.fi.uncoma.edu.ar/kb1/Phone"],
"multiplicity":["0..1","0..1"], "type":"association"}]
}
EOT;

        $expected = <<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#"
		xmlns:owl="http://www.w3.org/2002/07/owl#"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd"
    xml:base="http://crowd.fi.uncoma.edu.ar/kb1/">
    <CreateKB kb="http://crowd.fi.uncoma.edu.ar/kb1/">
    <Prefix name="crowd" fullIRI="http://crowd.fi.uncoma.edu.ar/kb1/" />
    <Prefix name="rdf" fullIRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
    <Prefix name="rdfs" fullIRI="http://www.w3.org/2000/01/rdf-schema#"/>
    <Prefix name="xsd" fullIRI="http://www.w3.org/2001/XMLSchema#"/>
    <Prefix name="owl" fullIRI="http://www.w3.org/2002/07/owl#"/>
    </CreateKB>
    <Set kb="http://crowd.fi.uncoma.edu.ar/kb1/" key="abbreviatesIRIs"><Literal>false</Literal></Set>
  <Tell kb="http://crowd.fi.uncoma.edu.ar/kb1/">
    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall" />
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone" />
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:SubClassOf>

    <owl:SubObjectPropertyOf>
      <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
      <owl:ObjectProperty IRI="http://www.w3.org/2002/07/owl#topObjectProperty"/>
    </owl:SubObjectPropertyOf>

    <owl:SubClassOf>
  		<owl:ObjectSomeValuesFrom>
          <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
          <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
      </owl:ObjectSomeValuesFrom>
  		<owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
    </owl:SubClassOf>

    <owl:SubClassOf>
  		<owl:ObjectSomeValuesFrom>
  			<owl:ObjectInverseOf>
              	<owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
  			</owl:ObjectInverseOf>
        <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
      </owl:ObjectSomeValuesFrom>
  		<owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
    </owl:SubClassOf>

    <owl:SubClassOf>
           <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
           <owl:ObjectMaxCardinality cardinality="1">
               <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
          </owl:ObjectMaxCardinality>
    </owl:SubClassOf>

    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
            <owl:ObjectMinCardinality cardinality="1">
                <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall_http://crowd.fi.uncoma.edu.ar/kb1/r1_max"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
            <owl:ObjectMaxCardinality cardinality="1">
                <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
            </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>

    <owl:SubClassOf>
           <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
           <owl:ObjectMaxCardinality cardinality="1">
             <owl:ObjectInverseOf>
                 <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
             </owl:ObjectInverseOf>
          </owl:ObjectMaxCardinality>
    </owl:SubClassOf>


    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMinCardinality cardinality="1">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_max"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMaxCardinality cardinality="1">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
  </Tell>
</RequestMessage>
EOT;

        $strategy = new UMLcrowd();
        $builder = new OWLlinkBuilder();

        $builder->insert_header();
        $strategy->translate($json, $builder);
        $builder->insert_footer();

        $actual = $builder->get_product();
        $actual = $actual->to_string();
        $this->assertXmlStringEqualsXmlString($expected, $actual,true);
    }


    ##
    # Test if translate works properly with binary roles many-to-many
    public function testTranslateBinaryRolesWithoutClass1N(){
		$json = <<< EOT
{
"classes": [{"name":"http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall", "attrs":[], "methods":[]},
			 {"name":"http://crowd.fi.uncoma.edu.ar/kb1/Phone", "attrs":[], "methods":[]}],
"links": [{"name": "http://crowd.fi.uncoma.edu.ar/kb1/r1",
"classes":["http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall","http://crowd.fi.uncoma.edu.ar/kb1/Phone"],
"multiplicity":["1..*","1..*"], "type":"association"}]
}
EOT;

        //TODO: Complete XML!
        $expected = <<< EOT
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#"
		xmlns:owl="http://www.w3.org/2002/07/owl#"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd"
    xml:base="http://crowd.fi.uncoma.edu.ar/kb1/">
    <CreateKB kb="http://crowd.fi.uncoma.edu.ar/kb1/">
    <Prefix name="crowd" fullIRI="http://crowd.fi.uncoma.edu.ar/kb1/" />
    <Prefix name="rdf" fullIRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
    <Prefix name="rdfs" fullIRI="http://www.w3.org/2000/01/rdf-schema#"/>
    <Prefix name="xsd" fullIRI="http://www.w3.org/2001/XMLSchema#"/>
    <Prefix name="owl" fullIRI="http://www.w3.org/2002/07/owl#"/>
    </CreateKB>
    <Set kb="http://crowd.fi.uncoma.edu.ar/kb1/" key="abbreviatesIRIs"><Literal>false</Literal></Set>
  <Tell kb="http://crowd.fi.uncoma.edu.ar/kb1/">
    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall" />
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone" />
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:SubClassOf>

    <owl:SubObjectPropertyOf>
      <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
      <owl:ObjectProperty IRI="http://www.w3.org/2002/07/owl#topObjectProperty"/>
    </owl:SubObjectPropertyOf>

    <owl:SubClassOf>
  		<owl:ObjectSomeValuesFrom>
          <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
          <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
      </owl:ObjectSomeValuesFrom>
  		<owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
    </owl:SubClassOf>

    <owl:SubClassOf>
  		<owl:ObjectSomeValuesFrom>
  			<owl:ObjectInverseOf>
              	<owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
  			</owl:ObjectInverseOf>
        <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
      </owl:ObjectSomeValuesFrom>
  		<owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
    </owl:SubClassOf>

    <owl:SubClassOf>
       	  <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
          <owl:ObjectMinCardinality cardinality="1">
               <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
          </owl:ObjectMinCardinality>
    </owl:SubClassOf>

    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
            <owl:ObjectMinCardinality cardinality="1">
                <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall_http://crowd.fi.uncoma.edu.ar/kb1/r1_max"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
            <owl:ObjectMaxCardinality cardinality="1">
                <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
            </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>

    <owl:SubClassOf>
       	  <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
          <owl:ObjectMinCardinality cardinality="1">
			       <owl:ObjectInverseOf>
               	<owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
             </owl:ObjectInverseOf>
          </owl:ObjectMinCardinality>
    </owl:SubClassOf>


    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMinCardinality cardinality="1">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_max"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMaxCardinality cardinality="1">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>


  </Tell>
</RequestMessage>
EOT;

        $strategy = new UMLcrowd();
        $builder = new OWLlinkBuilder();

        $builder->insert_header(); // Without this, loading the DOMDocument
        // will throw error for the owl namespace
        $strategy->translate($json, $builder);
        $builder->insert_footer();

        $actual = $builder->get_product();
        $actual = $actual->to_string();
        $this->assertXmlStringEqualsXmlString($expected, $actual,true);
    }


    ##
    # Test if translate works properly with binary roles many-to-many
    public function testTranslateBinaryRolesWithoutClass11(){
		$json = <<< EOT
{
"classes": [{"name":"http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall", "attrs":[], "methods":[]},
			 {"name":"http://crowd.fi.uncoma.edu.ar/kb1/Phone", "attrs":[], "methods":[]}],
"links": [{"name": "http://crowd.fi.uncoma.edu.ar/kb1/r1",
"classes":["http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall","http://crowd.fi.uncoma.edu.ar/kb1/Phone"],
"multiplicity":["1..1","1..1"], "type":"association"}]
}
EOT;

        $expected = <<< EOT
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#"
		xmlns:owl="http://www.w3.org/2002/07/owl#"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd"
    xml:base="http://crowd.fi.uncoma.edu.ar/kb1/">
    <CreateKB kb="http://crowd.fi.uncoma.edu.ar/kb1/">
    <Prefix name="crowd" fullIRI="http://crowd.fi.uncoma.edu.ar/kb1/" />
    <Prefix name="rdf" fullIRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
    <Prefix name="rdfs" fullIRI="http://www.w3.org/2000/01/rdf-schema#"/>
    <Prefix name="xsd" fullIRI="http://www.w3.org/2001/XMLSchema#"/>
    <Prefix name="owl" fullIRI="http://www.w3.org/2002/07/owl#"/>
    </CreateKB>
    <Set kb="http://crowd.fi.uncoma.edu.ar/kb1/" key="abbreviatesIRIs"><Literal>false</Literal></Set>
  <Tell kb="http://crowd.fi.uncoma.edu.ar/kb1/">
    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall" />
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone" />
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:SubClassOf>

    <owl:SubObjectPropertyOf>
      <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
      <owl:ObjectProperty IRI="http://www.w3.org/2002/07/owl#topObjectProperty"/>
    </owl:SubObjectPropertyOf>

    <owl:SubClassOf>
  		<owl:ObjectSomeValuesFrom>
          <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
          <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
      </owl:ObjectSomeValuesFrom>
  		<owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
    </owl:SubClassOf>

    <owl:SubClassOf>
  		<owl:ObjectSomeValuesFrom>
  			<owl:ObjectInverseOf>
              	<owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
  			</owl:ObjectInverseOf>
        <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
      </owl:ObjectSomeValuesFrom>
  		<owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
    </owl:SubClassOf>

    <owl:SubClassOf>
       	  <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
          <owl:ObjectMinCardinality cardinality="1">
               <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
           </owl:ObjectMinCardinality>
    </owl:SubClassOf>
    <owl:SubClassOf>
       	  <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
          <owl:ObjectMaxCardinality cardinality="1">
               <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
           </owl:ObjectMaxCardinality>
    </owl:SubClassOf>

    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
            <owl:ObjectMinCardinality cardinality="1">
                <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall_http://crowd.fi.uncoma.edu.ar/kb1/r1_max"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
            <owl:ObjectMaxCardinality cardinality="1">
                <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
            </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>

    <owl:SubClassOf>
       	  <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
          <owl:ObjectMinCardinality cardinality="1">
			        <owl:ObjectInverseOf>
               	<owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
             </owl:ObjectInverseOf>
           </owl:ObjectMinCardinality>
    </owl:SubClassOf>
    <owl:SubClassOf>
       	  <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
          <owl:ObjectMaxCardinality cardinality="1">
			       <owl:ObjectInverseOf>
               	<owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
             </owl:ObjectInverseOf>
          </owl:ObjectMaxCardinality>
    </owl:SubClassOf>


    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMinCardinality cardinality="1">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_max"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMaxCardinality cardinality="1">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>

  </Tell>
</RequestMessage>
EOT;

        $strategy = new UMLcrowd();
        $builder = new OWLlinkBuilder();

        $builder->insert_header(); // Without this, loading the DOMDocument
        // will throw error for the owl namespace
        $strategy->translate($json, $builder);
        $builder->insert_footer();

        $actual = $builder->get_product();
        $actual = $actual->to_string();
        $this->assertXmlStringEqualsXmlString($expected, $actual,true);
    }



    ##
    # Test if translate works properly with binary roles many-to-many > 1
    public function testTranslateBinaryRolesWithoutClassMN(){
		$json = <<< EOT
{
"classes": [{"name":"http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall", "attrs":[], "methods":[]},
			 {"name":"http://crowd.fi.uncoma.edu.ar/kb1/Phone", "attrs":[], "methods":[]}],
"links": [{"name": "http://crowd.fi.uncoma.edu.ar/kb1/r1",
"classes":["http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall","http://crowd.fi.uncoma.edu.ar/kb1/Phone"],
"multiplicity":["1..4","2..9"], "type":"association"}]
}
EOT;

        //TODO: Complete XML!
        $expected = <<< EOT
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#"
		xmlns:owl="http://www.w3.org/2002/07/owl#"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd"
    xml:base="http://crowd.fi.uncoma.edu.ar/kb1/">
    <CreateKB kb="http://crowd.fi.uncoma.edu.ar/kb1/">
    <Prefix name="crowd" fullIRI="http://crowd.fi.uncoma.edu.ar/kb1/" />
    <Prefix name="rdf" fullIRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
    <Prefix name="rdfs" fullIRI="http://www.w3.org/2000/01/rdf-schema#"/>
    <Prefix name="xsd" fullIRI="http://www.w3.org/2001/XMLSchema#"/>
    <Prefix name="owl" fullIRI="http://www.w3.org/2002/07/owl#"/>
    </CreateKB>
    <Set kb="http://crowd.fi.uncoma.edu.ar/kb1/" key="abbreviatesIRIs"><Literal>false</Literal></Set>
  <Tell kb="http://crowd.fi.uncoma.edu.ar/kb1/">
    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall" />
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone" />
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:SubClassOf>

    <owl:SubObjectPropertyOf>
      <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
      <owl:ObjectProperty IRI="http://www.w3.org/2002/07/owl#topObjectProperty"/>
    </owl:SubObjectPropertyOf>

    <owl:SubClassOf>
  		<owl:ObjectSomeValuesFrom>
          <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
          <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
      </owl:ObjectSomeValuesFrom>
  		<owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
    </owl:SubClassOf>

    <owl:SubClassOf>
  		<owl:ObjectSomeValuesFrom>
  			<owl:ObjectInverseOf>
              	<owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
  			</owl:ObjectInverseOf>
        <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
      </owl:ObjectSomeValuesFrom>
  		<owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
    </owl:SubClassOf>


    <owl:SubClassOf>
       	  <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
          <owl:ObjectMinCardinality cardinality="2">
               <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
          </owl:ObjectMinCardinality>
    </owl:SubClassOf>

    <owl:SubClassOf>
       	  <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
          <owl:ObjectMaxCardinality cardinality="9">
               <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
          </owl:ObjectMaxCardinality>
    </owl:SubClassOf>

    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
            <owl:ObjectMinCardinality cardinality="1">
                <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
            <owl:ObjectMinCardinality cardinality="2">
                <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>

    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall_http://crowd.fi.uncoma.edu.ar/kb1/r1_max"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
            <owl:ObjectMaxCardinality cardinality="9">
                <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
            </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>


    <owl:SubClassOf>
       	  <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
          <owl:ObjectMinCardinality cardinality="1">
			       <owl:ObjectInverseOf>
               	<owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
             </owl:ObjectInverseOf>
           </owl:ObjectMinCardinality>
    </owl:SubClassOf>
    <owl:SubClassOf>
       	  <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
          <owl:ObjectMaxCardinality cardinality="4">
			       <owl:ObjectInverseOf>
               	<owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
             </owl:ObjectInverseOf>
           </owl:ObjectMaxCardinality>
    </owl:SubClassOf>


    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMinCardinality cardinality="1">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMinCardinality cardinality="2">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_min"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMinCardinality cardinality="3">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMinCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>
    <owl:EquivalentClasses>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone_http://crowd.fi.uncoma.edu.ar/kb1/r1_max"/>
        <owl:ObjectIntersectionOf>
            <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Phone"/>
            <owl:ObjectMaxCardinality cardinality="4">
                <owl:ObjectInverseOf>
                    <owl:ObjectProperty IRI="http://crowd.fi.uncoma.edu.ar/kb1/r1"/>
                </owl:ObjectInverseOf>
            </owl:ObjectMaxCardinality>
        </owl:ObjectIntersectionOf>
    </owl:EquivalentClasses>

  </Tell>
</RequestMessage>
EOT;

        $strategy = new UMLcrowd();
        $builder = new OWLlinkBuilder();

        $builder->insert_header(); // Without this, loading the DOMDocument
        // will throw error for the owl namespace
        $strategy->translate($json, $builder);
        $builder->insert_footer();

        $actual = $builder->get_product();
        $actual = $actual->to_string();

        var_dump($actual);
        $this->assertXmlStringEqualsXmlString($expected, $actual,true);
    }



    # Test generalization is translated properly.
    public function testTranslateGeneralization(){
        //TODO: Complete JSON!
        $json = <<< EOT

{"classes": [
    {"attrs":[], "methods":[], "name": "http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"},
    {"attrs":[], "methods":[], "name": "http://crowd.fi.uncoma.edu.ar/kb1/MobileCall"}],
 "links": [
     {"classes": ["http://crowd.fi.uncoma.edu.ar/kb1/MobileCall"],
      "multiplicity": null,
      "name": "http://crowd.fi.uncoma.edu.ar/kb1/r1",
      "type": "generalization",
      "parent": "http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall",
      "constraint": []}
	]
}
EOT;
        $expected = <<< EOT
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#"
		xmlns:owl="http://www.w3.org/2002/07/owl#"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd"
    xml:base="http://crowd.fi.uncoma.edu.ar/kb1/">
    <CreateKB kb="http://crowd.fi.uncoma.edu.ar/kb1/">
    <Prefix name="crowd" fullIRI="http://crowd.fi.uncoma.edu.ar/kb1/" />
    <Prefix name="rdf" fullIRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
    <Prefix name="rdfs" fullIRI="http://www.w3.org/2000/01/rdf-schema#"/>
    <Prefix name="xsd" fullIRI="http://www.w3.org/2001/XMLSchema#"/>
    <Prefix name="owl" fullIRI="http://www.w3.org/2002/07/owl#"/>
    </CreateKB>
    <Set kb="http://crowd.fi.uncoma.edu.ar/kb1/" key="abbreviatesIRIs"><Literal>false</Literal></Set>
  <Tell kb="http://crowd.fi.uncoma.edu.ar/kb1/">

    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall" />
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/MobileCall" />
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:SubClassOf>

    <!-- Generalization -->

    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/MobileCall" />
	    <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall" />
    </owl:SubClassOf>

  </Tell>
</RequestMessage>
EOT;

        $strategy = new UMLcrowd();
        $builder = new OWLlinkBuilder();

        $builder->insert_header();
        $strategy->translate($json, $builder);
        $builder->insert_footer();

        $actual = $builder->get_product();
        $actual = $actual->to_string();

        $this->assertXmlStringEqualsXmlString($expected, $actual, TRUE);
    }


    # Test composed generalization (without constraints!).
    public function testTranslateComposedGen(){
        //TODO: Complete JSON!
        $json = <<<'EOT'
{"classes": [
    {"attrs":[], "methods":[], "name": "http://crowd.fi.uncoma.edu.ar/kb1/Person"},
    {"attrs":[], "methods":[], "name": "http://crowd.fi.uncoma.edu.ar/kb1/Employee"},
    {"attrs":[], "methods":[], "name": "http://crowd.fi.uncoma.edu.ar/kb1/Employer"},
    {"attrs":[], "methods":[], "name": "http://crowd.fi.uncoma.edu.ar/kb1/Director"}],
 "links": [
     {"classes": ["http://crowd.fi.uncoma.edu.ar/kb1/Employee", "http://crowd.fi.uncoma.edu.ar/kb1/Employer", "http://crowd.fi.uncoma.edu.ar/kb1/Director"],
      "multiplicity": null,
      "name": "http://crowd.fi.uncoma.edu.ar/kb1/r1",
      "type": "generalization",
      "parent": "http://crowd.fi.uncoma.edu.ar/kb1/Person",
      "constraint": []}
	]
}
EOT;
        $expected = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#"
		xmlns:owl="http://www.w3.org/2002/07/owl#"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd"
    xml:base="http://crowd.fi.uncoma.edu.ar/kb1/">
    <CreateKB kb="http://crowd.fi.uncoma.edu.ar/kb1/">
    <Prefix name="crowd" fullIRI="http://crowd.fi.uncoma.edu.ar/kb1/" />
    <Prefix name="rdf" fullIRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
    <Prefix name="rdfs" fullIRI="http://www.w3.org/2000/01/rdf-schema#"/>
    <Prefix name="xsd" fullIRI="http://www.w3.org/2001/XMLSchema#"/>
    <Prefix name="owl" fullIRI="http://www.w3.org/2002/07/owl#"/>
    </CreateKB>
    <Set kb="http://crowd.fi.uncoma.edu.ar/kb1/" key="abbreviatesIRIs"><Literal>false</Literal></Set>
  <Tell kb="http://crowd.fi.uncoma.edu.ar/kb1/">

    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Person" />
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:SubClassOf>

    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Employee" />
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:SubClassOf>

    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Employer" />
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:SubClassOf>

    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Director" />
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:SubClassOf>

    <!-- Generalization -->

    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Employee" />
	     <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Person" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Employer" />
	     <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Person" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Director" />
	     <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Person" />
    </owl:SubClassOf>

    <owl:SubClassOf>
      <owl:ObjectUnionOf>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Employee" />
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Employer" />
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Director" />
      </owl:ObjectUnionOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Person" />
    </owl:SubClassOf>
  </Tell>
  <!-- <ReleaseKB kb="http://localhost/kb1" /> -->
</RequestMessage>
EOT;

        $strategy = new UMLcrowd();
        $builder = new OWLlinkBuilder();

        $builder->insert_header();
        $strategy->translate($json, $builder);
        $builder->insert_footer();

        $actual = $builder->get_product();
        $actual = $actual->to_string();

        $this->assertXmlStringEqualsXmlString($expected, $actual, TRUE);
    }


    # Test generalization with disjoint constraint is translated properly.
    public function testTranslateGenDisjoint(){
        //TODO: Complete JSON!
        $json = <<<'EOT'
{"classes": [
    {"attrs":[], "methods":[], "name": "http://crowd.fi.uncoma.edu.ar/kb1/Person"},
    {"attrs":[], "methods":[], "name": "http://crowd.fi.uncoma.edu.ar/kb1/Employee"},
    {"attrs":[], "methods":[], "name": "http://crowd.fi.uncoma.edu.ar/kb1/Employer"},
    {"attrs":[], "methods":[], "name": "http://crowd.fi.uncoma.edu.ar/kb1/Director"}],
 "links": [
     {"classes": ["http://crowd.fi.uncoma.edu.ar/kb1/Employee", "http://crowd.fi.uncoma.edu.ar/kb1/Employer", "http://crowd.fi.uncoma.edu.ar/kb1/Director"],
      "multiplicity": null,
      "name": "http://crowd.fi.uncoma.edu.ar/kb1/r1",
      "type": "generalization",
      "parent": "http://crowd.fi.uncoma.edu.ar/kb1/Person",
      "constraint": ["disjoint"]}
	]
}
EOT;
        $expected = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#"
		xmlns:owl="http://www.w3.org/2002/07/owl#"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd"
    xml:base="http://crowd.fi.uncoma.edu.ar/kb1/">
    <CreateKB kb="http://crowd.fi.uncoma.edu.ar/kb1/">
    <Prefix name="crowd" fullIRI="http://crowd.fi.uncoma.edu.ar/kb1/" />
    <Prefix name="rdf" fullIRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
    <Prefix name="rdfs" fullIRI="http://www.w3.org/2000/01/rdf-schema#"/>
    <Prefix name="xsd" fullIRI="http://www.w3.org/2001/XMLSchema#"/>
    <Prefix name="owl" fullIRI="http://www.w3.org/2002/07/owl#"/>
    </CreateKB>
    <Set kb="http://crowd.fi.uncoma.edu.ar/kb1/" key="abbreviatesIRIs"><Literal>false</Literal></Set>
  <Tell kb="http://crowd.fi.uncoma.edu.ar/kb1/">

    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Person" />
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:SubClassOf>

    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Employee" />
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:SubClassOf>

    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Employer" />
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:SubClassOf>

    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Director" />
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:SubClassOf>

    <!-- Generalization -->

    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Employee" />
	     <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Person" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Employer" />
	     <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Person" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Director" />
	     <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Person" />
    </owl:SubClassOf>

    <owl:SubClassOf>
      <owl:ObjectUnionOf>
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Employee" />
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Employer" />
        <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Director" />
      </owl:ObjectUnionOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Person" />
    </owl:SubClassOf>

    <owl:DisjointClasses>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Employee" />
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Employer" />
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Director" />
    </owl:DisjointClasses>

  </Tell>
  <!-- <ReleaseKB kb="http://localhost/kb1" /> -->
</RequestMessage>
EOT;

        $strategy = new UMLcrowd();
        $builder = new OWLlinkBuilder();

        $builder->insert_header(); // Without this, loading the DOMDocument
        // will throw error for the owl namespace
        $strategy->translate($json, $builder);
        $builder->insert_footer();

        $actual = $builder->get_product();
        $actual = $actual->to_string();

        $this->assertXmlStringEqualsXmlString($expected, $actual, TRUE);
    }


    # Test generalization with covering constraint is translated properly.
    public function testTranslateGenCovering(){
        //TODO: Complete JSON!
        $json = <<<'EOT'
{"classes": [
    {"attrs":[], "methods":[], "name": "http://crowd.fi.uncoma.edu.ar/kb1/Person"},
    {"attrs":[], "methods":[], "name": "http://crowd.fi.uncoma.edu.ar/kb1/Employee"},
    {"attrs":[], "methods":[], "name": "http://crowd.fi.uncoma.edu.ar/kb1/Employer"},
    {"attrs":[], "methods":[], "name": "http://crowd.fi.uncoma.edu.ar/kb1/Director"}],
 "links": [
     {"classes": ["http://crowd.fi.uncoma.edu.ar/kb1/Employee", "http://crowd.fi.uncoma.edu.ar/kb1/Employer", "http://crowd.fi.uncoma.edu.ar/kb1/Director"],
      "multiplicity": null,
      "name": "http://crowd.fi.uncoma.edu.ar/kb1/r1",
      "type": "generalization",
      "parent": "http://crowd.fi.uncoma.edu.ar/kb1/Person",
      "constraint": ["covering"]}
	]
}
EOT;
        $expected = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#"
		xmlns:owl="http://www.w3.org/2002/07/owl#"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd"
    xml:base="http://crowd.fi.uncoma.edu.ar/kb1/">
    <CreateKB kb="http://crowd.fi.uncoma.edu.ar/kb1/">
    <Prefix name="crowd" fullIRI="http://crowd.fi.uncoma.edu.ar/kb1/" />
    <Prefix name="rdf" fullIRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
    <Prefix name="rdfs" fullIRI="http://www.w3.org/2000/01/rdf-schema#"/>
    <Prefix name="xsd" fullIRI="http://www.w3.org/2001/XMLSchema#"/>
    <Prefix name="owl" fullIRI="http://www.w3.org/2002/07/owl#"/>
    </CreateKB>
    <Set kb="http://crowd.fi.uncoma.edu.ar/kb1/" key="abbreviatesIRIs"><Literal>false</Literal></Set>
  <Tell kb="http://crowd.fi.uncoma.edu.ar/kb1/">

    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Person" />
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:SubClassOf>

    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Employee" />
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:SubClassOf>

    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Employer" />
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:SubClassOf>

    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Director" />
      <owl:Class IRI="http://www.w3.org/2002/07/owl#Thing" />
    </owl:SubClassOf>

    <!-- Generalization -->

    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Employee" />
	  <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Person" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Employer" />
	  <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Person" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Director" />
	  <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Person" />
    </owl:SubClassOf>

    <owl:SubClassOf>
      <owl:ObjectUnionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Employee" />
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Employer" />
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Director" />
      </owl:ObjectUnionOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Person" />
    </owl:SubClassOf>

    <owl:SubClassOf>
      <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Person" />
      <owl:ObjectUnionOf>
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Employee" />
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Employer" />
          <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Director" />
      </owl:ObjectUnionOf>
    </owl:SubClassOf>

  </Tell>
  <!-- <ReleaseKB kb="http://localhost/kb1" /> -->
</RequestMessage>
EOT;

        $strategy = new UMLcrowd();
        $builder = new OWLlinkBuilder();

        $builder->insert_header(); // Without this, loading the DOMDocument
        // will throw error for the owl namespace
        $strategy->translate($json, $builder);
        $builder->insert_footer();

        $actual = $builder->get_product();
        $actual = $actual->to_string();

        $this->assertXmlStringEqualsXmlString($expected, $actual, TRUE);
    }


    # Test for checking UMLcrowd::translate_queries method for full reasoning.

    public function test_translate_queries_full_reasoning(){
        $json = <<<EOT
{"classes": [
    {"attrs":[], "methods":[], "name": "http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"},
    {"attrs":[], "methods":[], "name": "http://crowd.fi.uncoma.edu.ar/kb1/MobileCall"}],
 "links": [
     {"classes": ["http://crowd.fi.uncoma.edu.ar/kb1/MobileCall"],
      "multiplicity": null,
      "name": "http://crowd.fi.uncoma.edu.ar/kb1/r1",
      "type": "generalization",
      "parent": "http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall",
      "constraint": []}
	]
}
EOT;
        $expected = <<< EOT
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#"
		xmlns:owl="http://www.w3.org/2002/07/owl#"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd"
    xml:base="http://crowd.fi.uncoma.edu.ar/kb1/">
    <CreateKB kb="http://crowd.fi.uncoma.edu.ar/kb1/">
    <Prefix name="crowd" fullIRI="http://crowd.fi.uncoma.edu.ar/kb1/" />
    <Prefix name="rdf" fullIRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
    <Prefix name="rdfs" fullIRI="http://www.w3.org/2000/01/rdf-schema#"/>
    <Prefix name="xsd" fullIRI="http://www.w3.org/2001/XMLSchema#"/>
    <Prefix name="owl" fullIRI="http://www.w3.org/2002/07/owl#"/>
    </CreateKB>
    <Set kb="http://crowd.fi.uncoma.edu.ar/kb1/" key="abbreviatesIRIs"><Literal>false</Literal></Set>
  <Tell kb="http://crowd.fi.uncoma.edu.ar/kb1/"/>
  <IsKBSatisfiable kb="http://crowd.fi.uncoma.edu.ar/kb1/"/>
  <IsClassSatisfiable kb="http://crowd.fi.uncoma.edu.ar/kb1/"><owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/></IsClassSatisfiable>
  <IsClassSatisfiable kb="http://crowd.fi.uncoma.edu.ar/kb1/"><owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/MobileCall"/></IsClassSatisfiable>
  <GetSubClassHierarchy kb="http://crowd.fi.uncoma.edu.ar/kb1/"/>
  <GetDisjointClasses kb="http://crowd.fi.uncoma.edu.ar/kb1/">
    <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
  </GetDisjointClasses>
  <GetDisjointClasses kb="http://crowd.fi.uncoma.edu.ar/kb1/">
    <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/MobileCall"/>
  </GetDisjointClasses>
  <GetEquivalentClasses kb="http://crowd.fi.uncoma.edu.ar/kb1/">
    <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/PhoneCall"/>
  </GetEquivalentClasses>
  <GetEquivalentClasses kb="http://crowd.fi.uncoma.edu.ar/kb1/">
    <owl:Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/MobileCall"/>
  </GetEquivalentClasses>
  <GetPrefixes kb="http://crowd.fi.uncoma.edu.ar/kb1/"/>
</RequestMessage>
EOT;

        $strategy = new UMLcrowd();
        $builder = new OWLlinkBuilder();

        $builder->insert_header(); // Without this, loading the DOMDocument
        // will throw error for the owl namespace
        $strategy->translate_queries($json, $builder);
        $builder->insert_footer();

        $actual = $builder->get_product();
        $actual = $actual->to_string();

        $this->assertXmlStringEqualsXmlString($expected, $actual, TRUE);
    }



    public function testBioOntoCrowd(){
      $json = <<<EOT
{"metadata":[],
"graphops":[],
"header":[],
"prefix":[{"prefix":"wd", "iri":"http://www.wikidata.org/entity/"},
{"prefix":"dbr", "iri":"http://dbpedia.org/resource/"},
{"prefix":"dwc", "iri":"http://rs.tdwg.org/dwc/terms/"},
{"prefix":"owl", "iri":"http://www.w3.org/2002/07/owl#"},
{"prefix":"rdf", "iri":"http://www.w3.org/1999/02/22-rdf-syntax-ns#"},
{"prefix":"wdt", "iri":"http://wikidata.org/prop/direct/"},
{"prefix":"xml", "iri":"http://www.w3.org/XML/1998/namespace"},
{"prefix":"xsd", "iri":"http://www.w3.org/2001/XMLSchema#"},
{"prefix":"envo", "iri":"http://purl.obolibrary.org/obo/"},
{"prefix":"foaf", "iri":"http://xmlns.com/foaf/0.1/"},
{"prefix":"rdfs", "iri":"http://www.w3.org/2000/01/rdf-schema#"},
{"prefix":"time", "iri":"http://www.w3.org/2006/time#"},
{"prefix":"void", "iri":"http://rdfs.org/ns/void#"},
{"prefix":"dcterms", "iri":"http://purl.org/dc/terms/"},
{"prefix":"geo-ont", "iri":"http://www.geonames.org/ontology#"},
{"prefix":"geo-pos", "iri":"http://www.w3.org/2003/01/geo/wgs84_pos#"},
{"prefix":"bio-onto", "iri":"http://www.cenpat-conicet.gob.ar/ontology/"}],
"ontologyIRI":[{"prefix":"", "iri":"http://www.cenpat-conicet.gob.ar/bioOnto/"}],
"classes": [],
"links": []
}
EOT;
        $expected =<<<EOT
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#"
    xmlns:owl="http://www.w3.org/2002/07/owl#"
    xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
    xmlns:xml="http://www.w3.org/XML/1998/namespace"
    xsi:schemaLocation="http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd"
    xml:base="http://www.cenpat-conicet.gob.ar/bioOnto/">
  <CreateKB kb="http://www.cenpat-conicet.gob.ar/bioOnto/">
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
  </CreateKB>
  <Set kb="http://www.cenpat-conicet.gob.ar/bioOnto/" key="abbreviatesIRIs"><Literal>false</Literal></Set>
</RequestMessage>
EOT;

    $strategy = new UMLcrowd();
    $builder = new OWLlinkBuilder();

    $json_obj = json_decode($json, true);

    $builder->insert_header(true,false, $json_obj["ontologyIRI"][0], $json_obj["header"], $json_obj["prefix"]);
    $strategy->translate($json, $builder);
    $builder->insert_footer();

    $actual = $builder->get_product();
    $actual = $actual->to_string();

    $this->assertXmlStringEqualsXmlString($expected, $actual,true);
    }

*/
}
