<?php
/*

   Copyright 2016 Giménez, Christian

   Author: Giménez, Christian. Braun, Germán

   ansanalizer.php

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

/**
   Namespace for Answers analizers.

   This analizers process the reasoner output and generate a JSON as
   follows:

 */
namespace Wicom\Translator\Strategies\QAPackages\AnswerAnalizers;

require_once __DIR__ . '/answer.php';
require_once __DIR__ . '/../../../builders/owllinkbuilder.php';
require_once __DIR__ . '/../../../builders/owlbuilder.php';

use Wicom\Translator\Builders\OWLlinkBuilder;
use Wicom\Translator\Builders\OWLBuilder;
use \XMLReader;
use \SimpleXMLElement;
use \SimpleXMLIterator;


/**
   Analize the reasoner answer.

   You need to create an instance and provide the reasoner query and its response using generate_answer(). Then you can ask for analize() all.

   @code{php}
   $ans = new AnsAnalizer();
   $ans->generate_answer($reasoner_query, $reasoner_answer);
   $ans->analize()
   @endcode

 */
abstract class AnsAnalizer{    //AggregateIterator

    /**
       An instance of Wicom\Answers\Answer.

       The answer created with analize().
     */
    protected $answer = null;
    protected $owllink_responses = null;
    protected $owllink_queries = null;

    /**
       Please, call generate_answer() for start to process the reasoner query and its response.
     */
    function __construct(){
    }

    /**
       Generate an empty Answer instance available before analizing.

       <Client> for Iterator pattern

       @param query The input query String given to the reasoner.
       @param answer The output given by the reasoner.
       @param $owl2 The input OWL 2 ontology
    */
    function generate_answer($query, $owl_answer, $owl2=""){
        $this->answer = new Answer(new OWLBuilder());  // creating summary of reasoning
        $this->owllink_responses = new SimpleXMLIterator($owl_answer);   // iterating on owlllink responses
        $this->owllink_queries = new SimpleXMLIterator($query);  // iterating on input ontology and queries
        $this->answer->set_reasoner_input($query);
        $this->answer->set_reasoner_output($owl_answer);
        $this->answer->set_original_owl2($owl2);
    }

    /**
       Do the last task and return the Answer instance.

       Ensure to call generate_answer() and analize() before.
     */
    function get_answer(){
        return $this->answer;
    }

    function get_owl2_from_answer(){
      return $this->answer->get_new_owl2();
    }

    /**
       Return the new ontology after reasoning.

       Ensure to call generate_answer() and analize() before.
     */
/*    function get_newonto(){
        return $this->owllink_newonto->get_product();
    }*/


    /**
       Analize and create the answer.

       **Implements in the subclass**

       Set the $answer attribute.
     */
    abstract function analize();


    abstract function get_responses();
}
