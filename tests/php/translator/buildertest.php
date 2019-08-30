<?php
/*

   Copyright 2016 Giménez, Christian

   Author: Giménez, Christian

   buildertest.php

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
load("owllinkbuilder.php", "wicom/translator/builders/");
load("owlbuilder.php", "wicom/translator/builders/");

use Wicom\Translator\Builders\OWLlinkBuilder;
use Wicom\Translator\Builders\OWLBuilder;

class BuilderTest extends PHPUnit\Framework\TestCase
{

/*
  public function testTranslateOWLlinkClass(){
      $expected = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
  <RequestMessage
  xmlns="http://www.owllink.org/owllink#"
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
  </Tell>
  </RequestMessage>
EOT;


      $builder = new OWLlinkBuilder();

      $builder->insert_header();

      $builder->translate_DL([
          ["subclass" => [
              ["class" => "http://crowd.fi.uncoma.edu.ar/kb1/Class2"],
              ["class" => "http://www.w3.org/2002/07/owl#Thing"],
          ]],
      ]);

      $builder->insert_footer();
      $actual = $builder->get_product();
      $actual = $actual->to_string();

      $expected = process_xmlspaces($expected);
      $actual = process_xmlspaces($actual);
      $this->assertEqualXMLStructure($expected, $actual, true);
  }
*/

  public function testTranslateOWLlinkAttribute(){
      $expected = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
  <RequestMessage
  xmlns="http://www.owllink.org/owllink#"
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


      $builder = new OWLlinkBuilder();

      $builder->insert_header();

      $builder->translate_DL([
          ["subclass" => [
              ["class" => "http://crowd.fi.uncoma.edu.ar/kb1/Class2"],
              ["class" => "http://www.w3.org/2002/07/owl#Thing"],
          ]],
          ["data_domain" => [
              ["data_domain_exists" => [
                  ["data_role" => "http://crowd.fi.uncoma.edu.ar/kb1/attribute"],
                  ]
              ],
              ["class" => "http://crowd.fi.uncoma.edu.ar/kb1/Class2"]
          ]],
          ["data_range" => [
                ["data_range_exists" => [
                    ["data_range_inverse" =>
                        ["data_role" => "http://crowd.fi.uncoma.edu.ar/kb1/attribute"]
                    ]
                ]],
                ["datatype" => "http://www.w3.org/2001/XMLSchema#integer"]
               ]
          ],
          ["subclass" => [
              ["class" => "http://crowd.fi.uncoma.edu.ar/kb1/Class2"],
              ["data_maxcard" =>
               [1,
                ["data_role" => "http://crowd.fi.uncoma.edu.ar/kb1/attribute"]]]]],
      ]);

      $builder->insert_footer();
      $actual = $builder->get_product();
      $actual = $actual->to_string();

      print_r($actual);

      $expected = process_xmlspaces($expected);
      $actual = process_xmlspaces($actual);
      $this->assertEqualXMLStructure($expected, $actual, true);
  }


/*
    public function testTranslateOWLlink(){
        $expected = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
<RequestMessage xmlns="http://www.owllink.org/owllink#"
		xmlns:owl="http://www.w3.org/2002/07/owl#"
		xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
		xsi:schemaLocation="http://www.owllink.org/owllink#
				    http://www.owllink.org/owllink-20091116.xsd"
    xml:base="http://crowd.fi.uncoma.edu.ar/kb1/">
  <CreateKB kb="http://crowd.fi.uncoma.edu.ar/kb1/" />
  <Set kb="http://crowd.fi.uncoma.edu.ar/kb1/" key="abbreviatesIRIs"><Literal>false</Literal></Set>
  <Tell kb="http://crowd.fi.uncoma.edu.ar/kb1/">
    <!-- <owl:ClassAssertion>
      <owl:Class IRI="Person" />
      <owl:NamedIndividual IRI="Mary" />
      </owl:ClassAssertion>
      -->

    <owl:SubClassOf>
      <owl:Class IRI="Person" />
      <owl:Class abbreviatedIRI="owl:Thing" />
    </owl:SubClassOf>
    <owl:SubClassOf>
      <owl:Class IRI="Cellphone" />
      <owl:Class abbreviatedIRI="owl:Thing" />
    </owl:SubClassOf>
    <!-- One person can has lots of cellphones -->

    <owl:SubClassOf>
	<owl:ObjectSomeValuesFrom>
	    <owl:ObjectProperty IRI="hasCellphone" />
	    <owl:Class abbreviatedIRI="owl:Thing" />
	</owl:ObjectSomeValuesFrom>
	<owl:Class IRI="Person" />
    </owl:SubClassOf>

    <owl:SubClassOf>
	<owl:ObjectSomeValuesFrom>
	    <owl:ObjectInverseOf>
		<owl:ObjectProperty IRI="hasCellphone" />
	    </owl:ObjectInverseOf>
	    <owl:Class abbreviatedIRI="owl:Thing" />
	</owl:ObjectSomeValuesFrom>
	<owl:Class IRI="Cellphone" />
    </owl:SubClassOf>

    <owl:SubClassOf>
	<owl:Class IRI="Person" />
	<owl:ObjectMinCardinality cardinality="1">
	    <owl:ObjectProperty IRI="hasCellphone" />
	</owl:ObjectMinCardinality>
    </owl:SubClassOf>

    <owl:SubClassOf>
	<owl:Class IRI="Cellphone" />
	<owl:ObjectIntersectionOf>
	    <owl:ObjectMinCardinality cardinality="1">
		<owl:ObjectInverseOf>
		    <owl:ObjectProperty IRI="hasCellphone" />
		</owl:ObjectInverseOf>
	    </owl:ObjectMinCardinality>
	    <owl:ObjectMaxCardinality cardinality="1">
		<owl:ObjectInverseOf>
		    <owl:ObjectProperty IRI="hasCellphone" />
		</owl:ObjectInverseOf>
	    </owl:ObjectMaxCardinality>
	</owl:ObjectIntersectionOf>
    </owl:SubClassOf>

    <owl:SubClassOf>
      <owl:Class abbreviatedIRI="owl:Thing" />
      <owl:ObjectAllValuesFrom>
        <owl:ObjectInverseOf>
          <owl:ObjectProperty IRI="hasCellphone" />
        </owl:ObjectInverseOf>
        <owl:Class IRI="Cellphone" />
      </owl:ObjectAllValuesFrom>
    </owl:SubClassOf>

    <owl:ObjectUnionOf>
      <owl:Class abbreviatedIRI="owl:Thing" />
      <owl:Class IRI="hi world" />
      <owl:Class IRI="hi two" />
    </owl:ObjectUnionOf>

    <owl:ObjectComplementOf>
      <owl:Class abbreviatedIRI="owl:Thing" />
    </owl:ObjectComplementOf>

  </Tell>
  <!-- <ReleaseKB kb="http://crowd.fi.uncoma.edu.ar/kb1/" /> -->
</RequestMessage>
EOT;


        $builder = new OWLlinkBuilder();

        $builder->insert_header();
        var_dump($builder);
        $builder->translate_DL([
            ["subclass" => [
                ["class" => "Persona"],
                ["class" => "owl:Thing"],
            ]],
            ["subclass" => [
                ["class" => "Cellphone"],
                ["class" => "owl:Thing"]]],
            ["subclass" => [
                ["exists" => ["role" => "hasCellphone"]],
                ["class" => "Person"]]],
            ["subclass" => [
                ["exists" => ["inverse" =>
                              ["role" => "hasCellphone"]]],
                ["class" => "Cellphone"]]],
            ["subclass" => [
                ["class" => "Person"],
                ["mincard" =>
                 [1,
                  ["role" => "hasCellphone"]]]]],
            ["subclass" => [
                ["class" => "Cellphone"],
                ["intersection" => [
                    ["mincard" =>
                     [1,
                      ["inverse" => ["role" => "hasCellphone"]]]],
                    ["maxcard" =>
                     [1,
                      ["inverse" => ["role" => "hasCellphone"]]]]
                ]]]],
            ["subclass" => [
                ["class" => "owl:Thing"],
                ["forall" => [
                    ["inverse" =>
                     ["role" => "hasCellphone"]],
                    ["class" => "Cellphone"]]]]],
            ["union" => [
                ["class" => "owl:Thing"],
                ["class" => "hi world"],
                ["class" => "hi two"]]],
            ["complement" => ["class" => "owl:Thing"]]
        ]);

        $builder->insert_footer();
        $actual = $builder->get_product();
        $actual = $actual->to_string();

        var_dump($actual);

        $expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);
        $this->assertEqualXMLStructure($expected, $actual, true);
    }


    public function testTranslateOWL2(){
      $expected = <<<'EOT'
<?xml version="1.0" encoding="UTF-8"?>
    <Ontology
            xmlns="http://www.w3.org/2002/07/owl#"
            xml:base="http://crowd.fi.uncoma.edu.ar/kb1/"
            xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
            xmlns:xml="http://www.w3.org/XML/1998/namespace"
            xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
            xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
            ontologyIRI="http://crowd.fi.uncoma.edu.ar/kb1/">

    <SubClassOf>
	     <ObjectSomeValuesFrom>
	       <ObjectProperty IRI="hasCellphone" />
	       <Class abbreviatedIRI="owl:Thing" />
	     </ObjectSomeValuesFrom>
	     <Class IRI="Person" />
    </SubClassOf>

    <SubClassOf>
	     <ObjectSomeValuesFrom>
	        <ObjectInverseOf>
		        <ObjectProperty IRI="hasCellphone" />
	        </ObjectInverseOf>
	        <Class abbreviatedIRI="owl:Thing" />
	     </ObjectSomeValuesFrom>
	     <Class IRI="Cellphone" />
    </SubClassOf>

    <SubClassOf>
	     <Class IRI="Person" />
	     <ObjectMinCardinality cardinality="1">
	        <ObjectProperty IRI="hasCellphone" />
	     </ObjectMinCardinality>
    </SubClassOf>

    <SubClassOf>
	     <Class IRI="Cellphone" />
	     <ObjectIntersectionOf>
	        <ObjectMinCardinality cardinality="1">
		         <ObjectInverseOf>
		             <ObjectProperty IRI="hasCellphone" />
		         </ObjectInverseOf>
	        </ObjectMinCardinality>
	        <ObjectMaxCardinality cardinality="1">
		         <ObjectInverseOf>
		             <ObjectProperty IRI="hasCellphone" />
		         </ObjectInverseOf>
	        </ObjectMaxCardinality>
	      </ObjectIntersectionOf>
    </SubClassOf>

    <SubClassOf>
      <Class abbreviatedIRI="owl:Thing" />
      <ObjectAllValuesFrom>
        <ObjectInverseOf>
          <ObjectProperty IRI="hasCellphone" />
        </ObjectInverseOf>
        <Class IRI="Cellphone" />
      </ObjectAllValuesFrom>
    </SubClassOf>

    <ObjectUnionOf>
      <Class abbreviatedIRI="owl:Thing" />
      <Class IRI="hi world" />
      <Class IRI="hi two" />
    </ObjectUnionOf>

    <ObjectComplementOf>
      <Class abbreviatedIRI="owl:Thing" />
    </ObjectComplementOf>
</Ontology>
EOT;


        $builder = new OWLBuilder();

        $builder->insert_header_owl2("http://crowd.fi.uncoma.edu.ar/kb1/");
        $builder->translate_DL([
            ["subclass" => [
                ["exists" => ["role" => "hasCellphone"]],
                ["class" => "Person"]]],
            ["subclass" => [
                ["exists" => ["inverse" =>
                              ["role" => "hasCellphone"]]],
                ["class" => "Cellphone"]]],
            ["subclass" => [
                ["class" => "Person"],
                ["mincard" =>
                 [1,
                  ["role" => "hasCellphone"]]]]],
            ["subclass" => [
                ["class" => "Cellphone"],
                ["intersection" => [
                    ["mincard" =>
                     [1,
                      ["inverse" => ["role" => "hasCellphone"]]]],
                    ["maxcard" =>
                     [1,
                      ["inverse" => ["role" => "hasCellphone"]]]]
                ]]]],
            ["subclass" => [
                ["class" => "owl:Thing"],
                ["forall" => [
                    ["inverse" =>
                     ["role" => "hasCellphone"]],
                    ["class" => "Cellphone"]]]]],
            ["union" => [
                ["class" => "owl:Thing"],
                ["class" => "hi world"],
                ["class" => "hi two"]]],
            ["complement" => ["class" => "owl:Thing"]]
        ]);

        $builder->insert_footer();
        $actual = $builder->get_product();
        $actual = $actual->to_string();

        var_dump($actual);

        $expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);
        $this->assertEqualXMLStructure($expected, $actual, true);
    }
*/

}

?>
