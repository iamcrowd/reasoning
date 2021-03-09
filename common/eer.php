<?php
/*

   Copyright 2016 Giménez, Christian

   Author: Giménez, Christian

   wicom.php

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
require_once __DIR__ . '/../wicom/translator/strategies/crowd_uml.php';
require_once __DIR__ . '/../wicom/translator/builders/owllinkbuilder.php';
require_once __DIR__ . '/../wicom/reasoner/runner.php';
require_once __DIR__ . '/../wicom/reasoner/racerconnector.php';
require_once __DIR__ . '/../wicom/reasoner/koncludeconnector.php';

use Wicom\Translator\Translator;
use Wicom\Translator\Strategies\UMLcrowd;
use Wicom\Translator\Builders\OWLlinkBuilder;

use Wicom\Reasoner\Runner;
use Wicom\Reasoner\RacerConnector;
use Wicom\Reasoner\KoncludeConnector;

# use Wicom\Answers\OWLlinkAnalizer;

class EER_Wicom extends Wicom{

    function __construct(){
      parent::__construct();
    }

    /**
       Check the diagram represented in JSON format for
       satisfiability.

       @param $json_str A String with the diagram in JSON format.
       @param $reasoner A String with the reasoner name. We support two: Konclude and Racer.

       @return Wicom\Translator\Strategies\QAPackages\AnswerAnalizers\Answer an answer object.
     */
    function full_reasoning($json_str, $reasoner = 'Konclude'){
        // Translate using a strategy
        $strategy = new EERcrowd();
        $trans = new Translator($strategy, new OWLlinkBuilder());
        $owllink_str = $trans->to_owllink($json_str);

        // Execute the reasoner
        $reasonerconn = null;
        if ($reasoner == 'Racer'){
            // User select the Racer reasoner.
            $reasonerconn = new RacerConnector();
        }else{
            $reasonerconn = new KoncludeConnector();
        }

        $runner = new Runner($reasonerconn);
        $runner->run($owllink_str);
        $owllink_answer = $runner->get_last_answer();

        // Generate the answer.
        /*
          $owllink_analizer = new OWLlinkAnalizer($owllink_str, $owllink_answer);
          $owllink_analizer->analize();

          return $owllink_analizer->get_answer();
        */
        return $strategy->analize_answer($owllink_str, $owllink_answer);
    }
}

?>
