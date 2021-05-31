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

load("crowd_dlmeta_enrico_exists.php", "wicom/translator/strategies/strategydlmeta/crowd20/");
load("owllinkbuilder.php", "wicom/translator/builders/");
load("owlbuilder.php", "wicom/translator/builders/");
load('metamodeltranslator.php', 'wicom/translator/');

use Wicom\Translator\Strategies\Strategydlmeta\crowd20\DLMetaEnricoExists;
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
class DLMetaEnricoExistsTest extends PHPUnit\Framework\TestCase{

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
       @testdox Translate a simple model with some KF RELATIONSHIPS and 0..N CARDINALITIES
       into OWLlink with SAT queries
     */
    public function testRel0NCardIntoOWLlinkWithSat(){
        $json = file_get_contents("translator/strategies/data/testRelNoCardIntoOWLlink.json");
        $expected = file_get_contents("translator/strategies/data/crowd20/testRelNoCardIntoOWLlink.owllink");

        if ($this->validate_against_scheme($json)){
          $strategy = new DLMetaEnricoExists();
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
        $expected = file_get_contents("translator/strategies/data/crowd20/testRelNoExtendedCardIntoOWLlink.owllink");

        if ($this->validate_against_scheme($json)){
          $strategy = new DLMetaEnricoExists();
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
       @testdox Translate a simple model with some KF Attribute Property
       into OWLlink
       @See http://crowd.fi.uncoma.edu.ar/KFDoc/
     */
    public function testAttributePropertyIntoOWLlink(){
        $json = file_get_contents("translator/strategies/data/testAttributePropertyIntoOWLlink.json");
        $expected = file_get_contents("translator/strategies/data/crowd20/testAttributePropertyIntoOWLlink.owllink");

        if ($this->validate_against_scheme($json)){
          $strategy = new DLMetaEnricoExists();
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
        $expected = file_get_contents("translator/strategies/data/crowd20/testAttributeMappedToIntoOWLlink.owllink");

        if ($this->validate_against_scheme($json)){
          $strategy = new DLMetaEnricoExists();
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
        $expected = file_get_contents("translator/strategies/data/crowd20/testKFtoOWLlinkAllQueries.owllink");

        if ($this->validate_against_scheme($json)){
          $strategy = new DLMetaEnricoExists();
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
        $expected = file_get_contents("translator/strategies/data/crowd20/testKFtoOWLAllQueries.owl");

        if ($this->validate_against_scheme($json)){
          $strategy = new DLMetaEnricoExists();
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
