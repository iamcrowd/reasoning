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

load("wicom.php");
load("translator.php", "../wicom/translator/");
load("berardistrat.php", "../wicom/translator/strategies/");
load("owllinkbuilder.php", "../wicom/translator/builders/");
load("owlbuilder.php", "../wicom/translator/builders/");
load("umljsonbuilder.php", "../wicom/translator/builders/");

load("runner.php", "../wicom/reasoner/");
load("racerconnector.php", "../wicom/reasoner/");
load("koncludeconnector.php", "../wicom/reasoner/");

load("ansanalizer.php",
     "../wicom/translator/strategies/qapackages/answeranalizers/");

use Wicom\Translator\Translator;
use Wicom\Translator\Strategies\Berardi;
use Wicom\Translator\Builders\OWLlinkBuilder;
use Wicom\Translator\Builders\OWLBuilder;
use Wicom\Translator\Builders\UMLJSONBuilder;

use Wicom\Reasoner\Runner;
use Wicom\Reasoner\RacerConnector;
use Wicom\Reasoner\KoncludeConnector;

use Wicom\Translator\Strategies\QAPackages\AnswerAnalizers\AnsAnalizer;
use Wicom\Translator\Strategies\QAPackages\QueriesGenerators\QueriesGenerator;

class UML_Wicom extends Wicom{

    function __construct(){
	parent::__construct();
    }

    function is_satisfiable($json_str, $strategy = 'berardi', $reasoner = 'Racer'){
        return $this->full_reasoning($json_str, $strategy, $reasoner);
    }

    
    /**
       Check the diagram represented in JSON format for full reasoning.

       @param $json_str A String with the diagram in JSON format.
       @param $strategy A String representing an specific Description Logic encoding
       @param $reasoner A String with the reasoner name. We support two: Konclude and Racer.

       @return Wicom\Translator\Strategies\QAPackages\AnswerAnalizers\Answer an answer object.
     */
    function full_reasoning($json_str, $strategy = 'berardi', $reasoner = 'Racer'){

        $encoding = null;
        switch($strategy){
            case "berardi" :
		$encoding = new Berardi();
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

        $owl2_trans = new Translator($encoding, new OWLBuilder());
        // $encoding->change_min_maxTo_false();
        $owl2_str = $owl2_trans->to_owl2($json_str);

        $encoding->analize_answer($owllink_str, $reasoner_answer, $owl2_str);

	$answer = $encoding->get_answer();

	return $answer;

    }
}

?>
