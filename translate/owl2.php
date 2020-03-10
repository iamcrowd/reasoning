<?php
/*

   Copyright 2016 GILIA

   Author: GILIA

   owl2xml.php

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
   Translate to OWL2 / XML.

   to be published

   Try this command:

   @code
   curl -d 'json={\"classes\": [{\"attrs\":[], \"methods\":[], \"name\": \"Hi World\"}]}' http://host.com/api/translate/crowd.php";
   @endcode

   @return An XML web page.
 */

require_once '../common/import_functions.php';

load('translator.php', '../wicom/translator/');
load('owldocument.php', '../wicom/translator/documents/');
load('owllinkdocument.php', '../wicom/translator/documents/');
// load('crowd_uml.php','../wicom/translator/strategies/');
load('owlbuilder.php', '../wicom/translator/builders/');
load('htmlbuilder.php', '../wicom/translator/builders/');
load('owllinkbuilder.php', '../wicom/translator/builders/');
load('berardistrat.php', '../wicom/translator/strategies/');


use Wicom\Translator\Translator;
use Wicom\Translator\Documents\OWLDocument;
// use Wicom\Translator\Strategies\UMLcrowd;
use Wicom\Translator\Strategies\Berardi;
use Wicom\Translator\Builders\OWLBuilder;
use Wicom\Translator\Builders\OWLlinkBuilder;
use Wicom\Translator\Builders\HTMLBuilder;

$format = 'owlxml';
if (array_key_exists('format',$_REQUEST)){
    $format = $_REQUEST['format'];
}

$strategy = 'crowd';
if (array_key_exists('strategy',$_REQUEST)){
    $strategy = $_REQUEST['strategy'];
}

if ( ! array_key_exists('json', $_POST)){
    echo "
There's no \"json\" parameter :-(
Use, for example:

    curl -d 'json={\"classes\": [{\"attrs\":[], \"methods\":[], \"name\": \"Hi World\"}]}' http://host.com/translator/crowd.php";
}else{
    $builder = null;


    switch ($strategy){
        case "crowd" :
            /*
            $strat = new UMLcrowd();
            break;
            */
        case "berardi" :
            $strat = new Berardi();
            break;
        default:
            die("Invalid Strategy!");
    }

    switch ($format){
        case "owlxml" :
            $trans = new Translator($strat, new OWLBuilder());
            $res = $trans->to_owl2($_POST['json']);
            break;
        case "html" :
            $builder = new HTMLBuilder();
            break;
        case "owllink" :
            $trans = new Translator($strat, new OWLlinkBuilder());
            $res = $trans->to_owllink($_POST['json']);
            break;
            /*    case "rdfxml" :
               $builder = new OWLlinkBuilder();
               break;
               case "rdfturtle" :
               $builder = new OWLlinkBuilder();
               break;
               case "rdfmanchester" :
               $builder = new OWLlinkBuilder();
               break; */
        default:
            die("Invalid Format!");
    }

    print_r($res);
}

?>
