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

      // print_r($actual);

      $expected = process_xmlspaces($expected);
      $actual = process_xmlspaces($actual);
      $this->assertEqualXMLStructure($expected, $actual, true);
  }


}

?>
