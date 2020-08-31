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

use Wicom\Translator\Strategies\QAPackages\AnswerAnalizers\CrowdMetaAnalizer;

class CrowdMetaAnalizerTest extends PHPUnit\Framework\TestCase{

  /**
     @testdox Parse owllink answers for KF OBJECT TYPES
   */
  public function testAnswerOWLlinkOutputKFOT(){

    $input = file_get_contents("answers/data/testObjTypeIntoOWLlink.owllink");
    $output = file_get_contents("answers/data/testObjTypeIntoOWLlinkOut.owllink");
    $expected = file_get_contents("answers/data/testObjTypeIntoOWLlinkOut.json");

    $oa = new CrowdMetaAnalizer();
    $oa->generate_answer($input, $output);
    $oa->analize();
    $answer = $oa->get_answer();

    $answer->set_reasoner_input("");
    $answer->set_reasoner_output("");
    $actual = $answer->to_json();

    $this->assertJsonStringEqualsJsonString($expected, $actual, true);
  }

  /**
     @testdox Parse owllink answers for KF Subsumptions
   */
  public function testAnswerOWLlinkOutputKFSubsumption(){

    $input = file_get_contents("answers/data/testSubIntoOWLlink.owllink");
    $output = file_get_contents("answers/data/testSubIntoOWLlinkOut.owllink");
    $expected = file_get_contents("answers/data/testSubIntoOWLlinkOut.json");

    $oa = new CrowdMetaAnalizer();
    $oa->generate_answer($input, $output);
    $oa->analize();
    $answer = $oa->get_answer();

    $answer->set_reasoner_input("");
    $answer->set_reasoner_output("");
    $actual = $answer->to_json();

    $this->assertJsonStringEqualsJsonString($expected, $actual, true);
  }

  /**
     @testdox Parse owllink answers for KF Subsumptions with Disjoint and Completeness Constraints
   */
  public function testAnswerOWLlinkOutputKFSubsumptionWithConstraints(){

    $input = file_get_contents("answers/data/testSubWithConstraintsIntoOWLlink.owllink");
    $output = file_get_contents("answers/data/testSubWithConstraintsIntoOWLlinkOut.owllink");
    $expected = file_get_contents("answers/data/testSubWithConstraintsIntoOWLlinkOut.json");

    $oa = new CrowdMetaAnalizer();
    $oa->generate_answer($input, $output);
    $oa->analize();
    $answer = $oa->get_answer();

    $answer->set_reasoner_input("");
    $answer->set_reasoner_output("");
    $actual = $answer->to_json();

    $this->assertJsonStringEqualsJsonString($expected, $actual, true);
  }

  /**
     @testdox Parse owllink answers for KF Binary Relationship 0..N Cardinalities
   */
  public function testAnswerOWLlinkOutputKFBinaryRelationship0NCardinalities(){

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
  }

  /**
     @testdox Parse owllink answers for KF Binary Relationship with Cardinalities. This test should out the very same JSON than 0..N
   */
  public function testAnswerOWLlinkOutputKFBinaryRelationshipCardinalities(){

    $input = file_get_contents("answers/data/testRelNoExtendedCardIntoOWLlink.owllink");
    $output = file_get_contents("answers/data/testRelNoExtendedCardIntoOWLlinkOut.owllink");
    $expected = file_get_contents("answers/data/testRelNoExtendedCardIntoOWLlinkOut.json");

    $oa = new CrowdMetaAnalizer();
    $oa->generate_answer($input, $output);
    $oa->analize();
    $answer = $oa->get_answer();

    $answer->set_reasoner_input("");
    $answer->set_reasoner_output("");
    $actual = $answer->to_json();

    $this->assertJsonStringEqualsJsonString($expected, $actual, true);
  }

  /**
     @testdox Parse owllink answers for KF Attributive Properties
   */
  public function testAnswerOWLlinkOutputKFAttributiveProperties(){

    $input = file_get_contents("answers/data/testAttributePropertyIntoOWLlink.owllink");
    $output = file_get_contents("answers/data/testAttributePropertyIntoOWLlinkOut.owllink");
    $expected = file_get_contents("answers/data/testAttributePropertyIntoOWLlinkOut.json");

    $oa = new CrowdMetaAnalizer();
    $oa->generate_answer($input, $output);
    $oa->analize();
    $answer = $oa->get_answer();

    $answer->set_reasoner_input("");
    $answer->set_reasoner_output("");
    $actual = $answer->to_json();

    $this->assertJsonStringEqualsJsonString($expected, $actual, true);
  }

  /**
     @testdox Parse owllink answers for KF MappedTo
   */
  public function testAnswerOWLlinkOutputKFMappedTo(){

    $input = file_get_contents("answers/data/testAttributeMappedToIntoOWLlink.owllink");
    $output = file_get_contents("answers/data/testAttributeMappedToIntoOWLlinkOut.owllink");
    $expected = file_get_contents("answers/data/testAttributeMappedToIntoOWLlinkOut.json");

    $oa = new CrowdMetaAnalizer();
    $oa->generate_answer($input, $output);
    $oa->analize();
    $answer = $oa->get_answer();

    $answer->set_reasoner_input("");
    $answer->set_reasoner_output("");
    $actual = $answer->to_json();

    $this->assertJsonStringEqualsJsonString($expected, $actual, true);
  }

  /**
     @testdox Parse owllink answers for KF model UNSAT primitives
   */
  public function testAnswerOWLlinkOutputKFAllQueriesUSATElements(){

    $input = file_get_contents("answers/data/testKFtoOWLlinkAllQueries.owllink");
    $output = file_get_contents("answers/data/testKFtoOWLlinkAllQueriesOut.owllink");
    $expected = file_get_contents("answers/data/testKFtoOWLlinkAllQueriesOut.json");

    $oa = new CrowdMetaAnalizer();
    $oa->generate_answer($input, $output);
    $oa->analize();
    $answer = $oa->get_answer();

    $answer->set_reasoner_input("");
    $answer->set_reasoner_output("");
    $actual = $answer->to_json();

    //var_dump($actual);

    $this->assertJsonStringEqualsJsonString($expected, $actual, true);
  }
}
