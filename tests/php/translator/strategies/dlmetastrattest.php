<?php
/*

   Copyright 2019 gilia

   Author: gilia

   dlmetastrattest.php

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

load("autoload.php", "vendor/");

load("crowd_dlmeta.php", "wicom/translator/strategies/strategydlmeta/");
load("owllinkbuilder.php", "wicom/translator/builders/");
load("owlbuilder.php", "wicom/translator/builders/");
load('metamodeltranslator.php', 'wicom/translator/');

use Wicom\Translator\Strategies\Strategydlmeta\DLMeta;
use Wicom\Translator\Builders\OWLlinkBuilder;
use Wicom\Translator\Builders\OWLBuilder;
use Wicom\Translator\MetamodelTranslator;

use Opis\JsonSchema\Validator;
use Opis\JsonSchema\Schema;

/**
   # Warning!
   Don't use assertEqualXMLStructure()! It won't check for attributes values!

   It will only check for the amount of attributes.
 */
class DLMetaTest extends PHPUnit\Framework\TestCase{

    protected function validate_against_scheme($json){
      $data = json_decode($json);
      $scheme_json = file_get_contents('/var/www/html/reasoning/wicom/translator/strategies/strategydlmeta/kfmetaScheme.json');
      $scheme = Schema::fromJsonString($scheme_json);

      $validator = new Validator();
      $result = $validator->schemaValidation($data, $scheme);

      if ($result->isValid()) {
        return true;
      } else {
        $error = $result->getFirstError();
        echo '$data is invalid', PHP_EOL;
        echo "Error: ", $error->keyword(), PHP_EOL;
        echo json_encode($error->keywordArgs(), JSON_PRETTY_PRINT), PHP_EOL;
        return false;
      }
    }

    /**
       @testdox Translate a simple model with some KF OBJECT TYPES into OWLlink with SAT queries
     */
    public function testObjTypeIntoOWLlinkWithSat(){
        $json = file_get_contents("translator/strategies/data/testObjTypeIntoOWLlink.json");
        $expected = file_get_contents("translator/strategies/data/testObjTypeIntoOWLlink.owllink");

        if ($this->validate_against_scheme($json)){
          $strategy = new DLMeta();
          $builder = new OWLlinkBuilder();

          $builder->insert_header();
          $strategy->translate($json, $builder);
          $strategy->translate_queries($strategy, $builder);
          $builder->insert_footer();

          $actual = $builder->get_product();
          $actual = $actual->to_string();

          $this->assertXmlStringEqualsXmlString($expected, $actual, true);
        } else {
          $this->assertTrue(false, "JSON KF does not match against KF Scheme");
        }
    }

    /**
       @testdox Translate a simple model with some KF SUBSUMPTIONS into OWLlink with SAT queries
     */
    public function testSubsumptionNoConstIntoOWLlinkWithSat(){
        $json = file_get_contents("translator/strategies/data/testSubIntoOWLlink.json");
        $expected = file_get_contents("translator/strategies/data/testSubIntoOWLlink.owllink");

        if ($this->validate_against_scheme($json)){
          $strategy = new DLMeta();
          $builder = new OWLlinkBuilder();

          $builder->insert_header();
          $strategy->translate($json, $builder);
          $strategy->translate_queries($strategy, $builder);
          $builder->insert_footer();

          $actual = $builder->get_product();
          $actual = $actual->to_string();

          $this->assertXmlStringEqualsXmlString($expected, $actual, true);
        } else {
          $this->assertTrue(false, "JSON KF does not match against KF Scheme");
        }
    }

    /**
       @testdox Translate a simple model with some KF SUBSUMPTIONS and CONSTRAINTS into OWLlink with SAT queries
     */
    public function testSubsumptionWithConstIntoOWLlinkWithSat(){
        $json = file_get_contents("translator/strategies/data/testSubWithConstraintsIntoOWLlink.json");
        $expected = file_get_contents("translator/strategies/data/testSubWithConstraintsIntoOWLlink.owllink");

        if ($this->validate_against_scheme($json)){
          $strategy = new DLMeta();
          $builder = new OWLlinkBuilder();

          $builder->insert_header();
          $strategy->translate($json, $builder);
          $strategy->translate_queries($strategy, $builder);
          $builder->insert_footer();

          $actual = $builder->get_product();
          $actual = $actual->to_string();

          $this->assertXmlStringEqualsXmlString($expected, $actual, true);
        } else {
          $this->assertTrue(false, "JSON KF does not match against KF Scheme");
        }
    }

    /**
       @testdox Translate a simple model with some KF RELATIONSHIPS and 0..N CARDINALITIES
       into OWLlink with SAT queries
     */
    public function testRel0NCardIntoOWLlinkWithSat(){
        $json = file_get_contents("translator/strategies/data/testRelNoCardIntoOWLlink.json");
        $expected = file_get_contents("translator/strategies/data/testRelNoCardIntoOWLlink.owllink");

        if ($this->validate_against_scheme($json)){
          $strategy = new DLMeta();
          $builder = new OWLlinkBuilder();

          $builder->insert_header();
          $strategy->translate($json, $builder);
          $strategy->translate_queries($strategy, $builder);
          $builder->insert_footer();

          $actual = $builder->get_product();
          $actual = $actual->to_string();

          $this->assertXmlStringEqualsXmlString($expected, $actual, true);
        } else {
          $this->assertTrue(false, "JSON KF does not match against KF Scheme");
        }
    }

    /**
       @testdox Translate a simple model with some KF RELATIONSHIPS and > 1 CARDINALITIES
       into OWLlink with SAT queries.
     */
    public function testRelMoreThan1CardIntoOWLlinkWithSat(){
        $json = file_get_contents("translator/strategies/data/testRelNoExtendedCardIntoOWLlink.json");
        $expected = file_get_contents("translator/strategies/data/testRelNoExtendedCardIntoOWLlink.owllink");

        if ($this->validate_against_scheme($json)){
          $strategy = new DLMeta();
          $builder = new OWLlinkBuilder();

          $builder->insert_header();
          $strategy->translate($json, $builder);
          $strategy->translate_queries($strategy, $builder);
          $builder->insert_footer();

          $actual = $builder->get_product();
          $actual = $actual->to_string();

          $this->assertXmlStringEqualsXmlString($expected, $actual, true);
        } else {
          $this->assertTrue(false, "JSON KF does not match against KF Scheme");
        }
    }

    /**
       @testdox Translate a simple model with some KF ROLE SUBSUMPTIONS
       into OWLlink with SAT queries.
     */
    public function testSubRolesIntoOWLlinkWithSat(){
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );

        $json = file_get_contents("translator/strategies/data/testSubRoleIntoOWLlink.json");
        $expected = file_get_contents("translator/strategies/data/testSubRoleIntoOWLlink.owllink");

        if ($this->validate_against_scheme($json)){
          $strategy = new DLMeta();
          $builder = new OWLlinkBuilder();

          $builder->insert_header();
          $strategy->translate($json, $builder);
          $strategy->translate_queries($strategy, $builder);
          $builder->insert_footer();

          $actual = $builder->get_product();
          $actual = $actual->to_string();

          $this->assertXmlStringEqualsXmlString($expected, $actual, true);
        }
        else {
          $this->assertTrue(false, "JSON KF does not match against KF Scheme");
        }
    }

    /**
       @testdox Translate a simple model with some KF RELATIONSHIP SUBSUMPTIONS
       into OWLlink with SAT queries.
     */
    public function testSubRelationshipsIntoOWLlinkWithSat(){
      $json = file_get_contents("translator/strategies/data/testSubRelationshipsIntoOWLlink.json");
      $expected = file_get_contents("translator/strategies/data/testSubRelationshipsIntoOWLlink.owllink");

      if ($this->validate_against_scheme($json)){
        $strategy = new DLMeta();
        $builder = new OWLlinkBuilder();

        $builder->insert_header();
        $strategy->translate($json, $builder);
        $strategy->translate_queries($strategy, $builder);
        $builder->insert_footer();

        $actual = $builder->get_product();
        $actual = $actual->to_string();

        $this->assertXmlStringEqualsXmlString($expected, $actual, true);
      }
      else {
        $this->assertTrue(false, "JSON KF does not match against KF Scheme");
      }
    }

    /**
       @testdox Translate a simple model without KF RELATIONSHIP SUBSUMPTIONS where they should be disjoint each other.
     */
    public function testGeneralAxiomsSignaturesRel(){
      $this->markTestIncomplete(
          'This test has not been implemented yet.'
      );
      
      $json = file_get_contents("translator/strategies/data/testGeneralAxioms.json");
      $expected = file_get_contents("translator/strategies/data/testGeneralAxioms.owllink");

      if ($this->validate_against_scheme($json)){
        $strategy = new DLMeta();
        $builder = new OWLlinkBuilder();

        $builder->insert_header();
        $strategy->translate($json, $builder);
        $strategy->translate_queries($strategy, $builder);
        $builder->insert_footer();

        $actual = $builder->get_product();
        $actual = $actual->to_string();

        $this->assertXmlStringEqualsXmlString($expected, $actual, true);
      }
      else {
        $this->assertTrue(false, "JSON KF does not match against KF Scheme");
      }
    }

    /**
       @testdox Translate a simple model with some KF Attribute Property
       into OWLlink
       @See http://crowd.fi.uncoma.edu.ar/KFDoc/
     */
    public function testAttributePropertyIntoOWLlink(){
        $json = file_get_contents("translator/strategies/data/testAttributePropertyIntoOWLlink.json");
        $expected = file_get_contents("translator/strategies/data/testAttributePropertyIntoOWLlink.owllink");

        if ($this->validate_against_scheme($json)){
          $strategy = new DLMeta();
          $builder = new OWLlinkBuilder();

          $builder->insert_header();
          $strategy->translate($json, $builder);
          $strategy->translate_queries($strategy, $builder);
          $builder->insert_footer();

          $actual = $builder->get_product();
          $actual = $actual->to_string();

          $this->assertXmlStringEqualsXmlString($expected, $actual, true);
        }
        else {
          $this->assertTrue(false, "JSON KF does not match against KF Scheme");
        }
    }

    /**
       @testdox Translate a simple model with some KF Attributes into OWLlink but considering Transformation Rules, which use MappedTo primitive.
       @See http://crowd.fi.uncoma.edu.ar/KFDoc/
     */
    public function testAttributeMappedToIntoOWLlink(){
        $json = file_get_contents("translator/strategies/data/testAttributeMappedToIntoOWLlink.json");
        $expected = file_get_contents("translator/strategies/data/testAttributeMappedToIntoOWLlink.owllink");

        if ($this->validate_against_scheme($json)){
          $strategy = new DLMeta();
          $builder = new OWLlinkBuilder();

          $builder->insert_header();
          $strategy->translate($json, $builder);
          $strategy->translate_queries($strategy, $builder);
          $builder->insert_footer();

          $actual = $builder->get_product();
          $actual = $actual->to_string();

          $this->assertXmlStringEqualsXmlString($expected, $actual, true);
        }
        else {
          $this->assertTrue(false, "JSON KF does not match against KF Scheme");
        }
    }

    /**
       @testdox Translate a more complex model. Adding all queries
       @See http://crowd.fi.uncoma.edu.ar/KFDoc/
     */
    public function testKFtoOWLlinkAllQueries(){
        $json = file_get_contents("translator/strategies/data/testKFtoOWLlinkAllQueries.json");
        $expected = file_get_contents("translator/strategies/data/testKFtoOWLlinkAllQueries.owllink");

        if ($this->validate_against_scheme($json)){
          $strategy = new DLMeta();
          $builder = new OWLlinkBuilder();

          $builder->insert_header();
          $strategy->translate($json, $builder);
          $strategy->translate_queries($strategy, $builder);
          $builder->insert_footer();

          $actual = $builder->get_product();
          $actual = $actual->to_string();

          $this->assertXmlStringEqualsXmlString($expected, $actual, true);
        }
        else {
          $this->assertTrue(false, "JSON KF does not match against KF Scheme");
        }
    }

    /**
       @testdox Translate a more complex model to OWL 2. Adding all queries
       @See http://crowd.fi.uncoma.edu.ar/KFDoc/
     */
    public function testKFtoOWL2AllQueries(){
        $json = file_get_contents("translator/strategies/data/testKFtoOWLlinkAllQueries.json");
        $expected = file_get_contents("translator/strategies/data/testKFtoOWLAllQueries.owl");

        if ($this->validate_against_scheme($json)){
          $strategy = new DLMeta();
          $builder = new OWLBuilder();

          $builder = new OWLBuilder();
          $trans = new MetamodelTranslator($strategy, $builder);
          $actual = $trans->to_owl2($json);

          //var_dump($actual);
          $this->assertXmlStringEqualsXmlString($expected, $actual, true);
        }
        else {
          $this->assertTrue(false, "JSON KF does not match against KF Scheme");
        }
    }

}
