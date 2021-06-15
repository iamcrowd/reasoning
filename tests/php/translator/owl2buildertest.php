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
load("owlbuilder.php", "wicom/translator/builders/");

use Wicom\Translator\Builders\OWLBuilder;

class OWL2BuilderTest extends PHPUnit\Framework\TestCase
{


  public function testOWLBuilderCustomURI(){
    $expected = '<?xml version="1.0" encoding="UTF-8"?>
          <Ontology
            xmlns="http://www.w3.org/2002/07/owl#"
            xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
            xmlns:xml="http://www.w3.org/XML/1998/namespace"
            xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
            xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
            xml:base="http://crowd.fi.uncoma.edu.ar/kb1/"
            ontologyIRI="http://crowd.fi.uncoma.edu.ar/kb1/">
            <Prefix name="rdf" IRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
            <Prefix name="rdfs" IRI="http://www.w3.org/2000/01/rdf-schema#"/>
            <Prefix name="xsd" IRI="http://www.w3.org/2001/XMLSchema#"/>
            <Prefix name="owl" IRI="http://www.w3.org/2002/07/owl#"/>
            <Declaration>
              <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1"/>
            </Declaration>
            <Declaration>
              <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
            </Declaration>
            <SubClassOf>
              <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class2"/>
              <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1/Class1"/>
            </SubClassOf>
          </Ontology>';


      $builder = new OWLBuilder();
      $builder->insert_header_owl2("http://crowd.fi.uncoma.edu.ar/kb1/");
      $builder->insert_class_declaration("http://crowd.fi.uncoma.edu.ar/kb1/Class1");
      $builder->insert_class_declaration("http://crowd.fi.uncoma.edu.ar/kb1/Class2");
      $builder->translate_DL([
          ["subclass" => [
              ["class" => "http://crowd.fi.uncoma.edu.ar/kb1/Class2"],
              ["class" => "http://crowd.fi.uncoma.edu.ar/kb1/Class1"],
          ]]]);
      $builder->insert_footer();
      $actual = $builder->get_product();
      $actual = $actual->to_string();

      //var_dump($actual);

      $expected = process_xmlspaces($expected);
      $actual = process_xmlspaces($actual);
      $this->assertEqualXMLStructure($expected, $actual, true);
  }

  public function testOWLBuilderDefaultURI(){
    $expected = '<?xml version="1.0" encoding="UTF-8"?>
          <Ontology
            xmlns="http://www.w3.org/2002/07/owl#"
            xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
            xmlns:xml="http://www.w3.org/XML/1998/namespace"
            xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
            xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
            xml:base="http://crowd.fi.uncoma.edu.ar/kb1#"
            ontologyIRI="http://crowd.fi.uncoma.edu.ar/kb1#">
            <Prefix name="rdf" IRI="http://www.w3.org/1999/02/22-rdf-syntax-ns#"/>
            <Prefix name="rdfs" IRI="http://www.w3.org/2000/01/rdf-schema#"/>
            <Prefix name="xsd" IRI="http://www.w3.org/2001/XMLSchema#"/>
            <Prefix name="owl" IRI="http://www.w3.org/2002/07/owl#"/>
            <Declaration>
              <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1#Class1"/>
            </Declaration>
            <Declaration>
              <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1#Class2"/>
            </Declaration>
            <SubClassOf>
              <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1#Class2"/>
              <Class IRI="http://crowd.fi.uncoma.edu.ar/kb1#Class1"/>
            </SubClassOf>
          </Ontology>';


      $builder = new OWLBuilder();
      $builder->insert_header_owl2();
      $builder->insert_class_declaration("Class1");
      $builder->insert_class_declaration("Class2");
      $builder->translate_DL([
          ["subclass" => [
              ["class" => "Class2"],
              ["class" => "Class1"],
          ]]]);
      $builder->insert_footer();
      $actual = $builder->get_product();
      $actual = $actual->to_string();

      var_dump($actual);

      $expected = process_xmlspaces($expected);
      $actual = process_xmlspaces($actual);
      $this->assertEqualXMLStructure($expected, $actual, true);
  }


}

?>
