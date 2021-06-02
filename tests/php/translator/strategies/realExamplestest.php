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
load("crowd_dlmeta_enrico_exists.php", "wicom/translator/strategies/strategydlmeta/crowd20/");

load("crowd_checkmeta.php", "wicom/translator/strategies/strategydlmeta/");
load("owllinkbuilder.php", "wicom/translator/builders/");
load("metajsonbuilder.php", "wicom/translator/builders/");
load("crowdmetaanalizer.php", "wicom/translator/strategies/qapackages/answeranalizers/");

use Wicom\Translator\Strategies\Strategydlmeta\DLMeta;
use Wicom\Translator\Strategies\Strategydlmeta\crowd20\DLMetaEnricoExists;

use Wicom\Translator\Strategies\Strategydlmeta\DLCheckMeta;
use Wicom\Translator\Builders\OWLlinkBuilder;
use Wicom\Translator\Builders\MetaJSONBuilder;
use Wicom\Translator\Strategies\QAPackages\AnswerAnalizers\CrowdMetaAnalizer;

use Opis\JsonSchema\Validator;
use Opis\JsonSchema\Schema;

/**
   # Warning!
   Don't use assertEqualXMLStructure()! It won't check for attributes values!

   It will only check for the amount of attributes.
 */
class RealExamplesTest extends PHPUnit\Framework\TestCase{

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
       @testdox Reported by Maria Keet. Motivational Scenario for ER 21
       @See http://crowd.fi.uncoma.edu.ar/KFDoc/
     */
     public function testMariaKeetER21(){
        $json = file_get_contents("translator/strategies/testMariaKeetER21/testMariaKeet21.json");
        $input = file_get_contents("translator/strategies/testMariaKeetER21/testMariaKeet21OWLlink.owllink");
        $output = file_get_contents("translator/strategies/testMariaKeetER21/testMariaKeet21Out.owllink");
        $inferred_expected = file_get_contents("translator/strategies/testMariaKeetER21/testMariaKeet21BeautyOutInferred.json");
        $beauty_out = file_get_contents("translator/strategies/testMariaKeetER21/testMariaKeet21BeautyOut.json");

        if ($this->validate_against_scheme($json)){
          $strategy = new DLMetaEnricoExists();
          $strategy->set_check_cardinalities(true);
          $builder = new OWLlinkBuilder();

          $builder->insert_header();
          $strategy->translate($json, $builder);
          $strategy->translate_queries($strategy, $builder);
          $builder->insert_footer();

          $actual = $builder->get_product();
          $actual = $actual->to_string();

          $this->assertXmlStringEqualsXmlString($input, $actual, true);

          $oa = $strategy->get_qa_pack()->get_ans_analizer();
          $oa->generate_answer($actual, $output);
          $oa->set_c_strategy($strategy);
          $oa->analize();
          $answer = $oa->get_answer();

          $answer->set_reasoner_input("");
          $answer->set_reasoner_output("");
          $actual_o = $answer->to_json();

          $beauty_out_json = $oa->get_beatified_responses();

          $this->assertJsonStringEqualsJsonString($beauty_out, $beauty_out_json, true);

          $inferred = new DLCheckMeta($json, $strategy, $answer);

          $this->assertJsonStringEqualsJsonString($inferred_expected, $inferred->built_output(), true);

        }
        else {
          $this->assertTrue(false, "JSON KF does not match against KF Scheme");
        }
    }

}
