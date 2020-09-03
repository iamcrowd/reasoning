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
load("crowd_checkmeta.php", "wicom/translator/strategies/strategydlmeta/");
load("owllinkbuilder.php", "wicom/translator/builders/");
load("metajsonbuilder.php", "wicom/translator/builders/");
load("crowdmetaanalizer.php", "wicom/translator/strategies/qapackages/answeranalizers/");

use Wicom\Translator\Strategies\Strategydlmeta\DLMeta;
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
class DLMetaCheckTest extends PHPUnit\Framework\TestCase{

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
       @testdox test for beauty_responses with disjoint and equivalence axioms inferred
       @See http://crowd.fi.uncoma.edu.ar/KFDoc/
     */
    public function testKFtoOWLlinkAllQueries(){
        $json = file_get_contents("translator/strategies/data_inf/testKFtoOWLlinkAllQueries.json");
        $input = file_get_contents("translator/strategies/data_inf/testKFtoOWLlinkAllQueries.owllink");
        $output = file_get_contents("translator/strategies/data_inf/testKFtoOWLlinkAllQueriesOut.owllink");
        $inferred_expected = file_get_contents("translator/strategies/data_inf/testKFtoOWLlinkAllQueriesBeautyOutInferred.json");
        $beauty_out = file_get_contents("translator/strategies/data_inf/testKFtoOWLlinkAllQueriesBeautyOut.json");

        if ($this->validate_against_scheme($json)){
          $strategy = new DLMeta();
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

    //var_dump($oa->get_subclass("http://www.w3.org/2002/07/owl#Thing"));
    /*var_dump($oa->get_disjoint_class("http://www.w3.org/2002/07/owl#Nothing"));
    var_dump($oa->get_disjoint_class("http://www.w3.org/2002/07/owl#Thing"));
    var_dump($oa->get_disjoint_class("http://crowd.fi.uncoma.edu.ar/kb1#D"));
    var_dump($oa->get_equivalent_class("http://crowd.fi.uncoma.edu.ar/kb1#D"));*/

    //$metabuilder = new MetaJSONBuilder($json);
    //$name = $metabuilder->insert_subsumption("http://crowd.fi.uncoma.edu.ar/kb1#F", "http://crowd.fi.uncoma.edu.ar/kb1#E");

    //if($metabuilder->subsumption_in_instance("http://crowd.fi.uncoma.edu.ar/kb1#F", "http://crowd.fi.uncoma.edu.ar/kb1#E")){
    //  echo("existing");
    //} else {
    //  echo("unexisting");
    //}
    //var_dump($metabuilder->get_product()->to_json());

}
