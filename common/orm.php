<?php
/*

   Copyright 2018

   Author: GILIA

   uml.php

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

namespace Wicom;


require_once __DIR__ . '/../wicom/translator/translator.php';
require_once __DIR__ . '/../wicom/translator/strategies/crowd_orm_strat.php';
require_once __DIR__ . '/../wicom/translator/builders/owllinkbuilder.php';
require_once __DIR__ . '/../wicom/translator/builders/owlbuilder.php';
require_once __DIR__ . '/../wicom/translator/builders/umljsonbuilder.php';
require_once __DIR__ . '/../wicom/reasoner/runner.php';
require_once __DIR__ . '/../wicom/reasoner/racerconnector.php';
require_once __DIR__ . '/../wicom/reasoner/koncludeconnector.php';
require_once __DIR__ .
    '/../wicom/translator/strategies/qapackages/answeranalizers/ansanalizer.php';

use Wicom\Translator\Translator;
//use Wicom\Translator\Strategies\Berardi;
use Wicom\Translator\Strategies\CrowdORM;
use Wicom\Translator\Builders\OWLlinkBuilder;
use Wicom\Translator\Builders\OWLBuilder;
use Wicom\Translator\Builders\UMLJSONBuilder;

use Wicom\Reasoner\Runner;
use Wicom\Reasoner\RacerConnector;
use Wicom\Reasoner\KoncludeConnector;

use Wicom\Translator\Strategies\QAPackages\AnswerAnalizers\AnsAnalizer;
use Wicom\Translator\Strategies\QAPackages\QueriesGenerators\QueriesGenerator;

class ORM_Wicom extends Wicom{

    function __construct(){
	parent::__construct();
    }

    /**
       Check the diagram represented in JSON format for satisfiability.

       @param $json_str A String with the diagram in JSON format.
       @param $strategy A String representing an specific Description Logic encoding
       @param $reasoner A String with the reasoner name. We support two: Konclude and Racer.

       @return Wicom\Translator\Strategies\QAPackages\AnswerAnalizers\Answer an answer object.
     */
    function is_satisfiable($json_str, $strategy = 'CrowdOrm', $reasoner = 'Racer'){

        $encoding = null;
        switch($strategy){
            case "CrowdOrm" :
				        $encoding = new CrowdORM();
                break;
            default: die("Invalid Encoding");
        }

        $trans = new Translator($encoding, new OWLlinkBuilder());
        $owllink_str = $trans->to_owllink($json_str);


        $reasonerconn = null;
        switch($reasoner){
            case "Konclude" :
		          $reasonerconn = new KoncludeConnector();
		          break;
            case "Racer" :
		          $reasonerconn = new RacerConnector();
		          break;
            default: die("Reasoner Not Found!");
        }
        $runner = new Runner($reasonerconn);

		$runner->run($owllink_str);

		$reasoner_answer = $runner->get_last_answer();

        $owl2_str = '';

        $encoding->analize_answer($owllink_str, $reasoner_answer, $owl2_str);
		    $answer = $encoding->get_answer();

        return $answer;

	}
}

?>
