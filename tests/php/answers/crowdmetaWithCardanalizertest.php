<?php
/*

   Copyright 2016 GILIA

   Author: Giménez, Christian. Braun, Germán

   berardianalizertest.php

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
load("crowdmetaanalizer.php", "wicom/translator/strategies/qapackages/answeranalizers/");
load("crowd_dlmeta.php", "wicom/translator/strategies/strategydlmeta/");
load("owllinkbuilder.php", "wicom/translator/builders/");

use Wicom\Translator\Strategies\QAPackages\AnswerAnalizers\CrowdMetaAnalizer;
use Wicom\Translator\Strategies\Strategydlmeta\DLMeta;
use Wicom\Translator\Builders\OWLlinkBuilder;

class CrowdMetaAnalizerTest extends PHPUnit\Framework\TestCase{

  /**
     @testdox Parse owllink answers for KF Binary Relationship 0..N Cardinalities
   */
/*  public function testAnswerOWLlinkOutputKFBinaryRelationship0NCardinalities(){

    $input = file_get_contents("answers/data/testRelNoCardIntoOWLlink.owllink");
    $output = file_get_contents("answers/data/testRelNoCardIntoOWLlinkOut.owllink");
    $expected = file_get_contents("answers/data/testRelNoCardIntoOWLlinkOut.json");

    $oa = new CrowdMetaAnalizer();
    $oa->generate_answer($input, $output);
    $oa->analize();
    $answer = $oa->get_answer();

    $answer->set_reasoner_input("");
    $answer->set_reasoner_output("");
    $actual = $answer->to_json();

    $this->assertJsonStringEqualsJsonString($expected, $actual, true);
  }*/

  /**
     @testdox Parse owllink answers for KF Binary Relationship with Cardinalities. This test should out the very same JSON than 0..N
   */
  public function testAnswerOWLlinkOutputKFBinaryRelationshipCardinalities(){

    $json = file_get_contents("answers/data/testRelNoExtendedCardIntoOWLlink.json");
    $input_owl = file_get_contents("answers/data/testRelExtendedCardIntoOWLlink.owllink");
    $output = file_get_contents("answers/data/testRelExtendedCardIntoOWLlinkOut.owllink");
//    $expected = file_get_contents("answers/data/testRelExtendedCardIntoOWLlinkOut.json");

    $strategy = new DLMeta();
    $strategy->set_check_cardinalities(true);
    $builder = new OWLlinkBuilder();

    $builder->insert_header();
    $strategy->translate($json, $builder);
    $strategy->translate_queries($strategy, $builder);
    $builder->insert_footer();

    $actual_owl = $builder->get_product();
    $actual_owl = $actual_owl->to_string();

    $this->assertXmlStringEqualsXmlString($input_owl, $actual_owl, true);

    $oa = new CrowdMetaAnalizer();
    $oa->generate_answer($input_owl, $output);
    $oa->set_c_strategy($strategy);
    $oa->analize();
    $answer = $oa->get_answer();

    $actual = $answer->to_json();

  //  $this->assertJsonStringEqualsJsonString($expected, $actual, true);
  }

}
