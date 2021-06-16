<?php
/*

   Copyright 2019 GILIA

   Author: gab

   metamodel2owl.php

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
   Translate to OWLlink using Berardi strategy.

   The Berardi strategy implements the method explained on "Reasoning on UML
   Class Diagrams" by Daniela Berardi, Diego Calvanesse and Giuseppe De
   Giacomo.

   Try this command:

   @code
   curl -d 'json={\"classes\": [{\"attrs\":[], \"methods\":[], \"name\": \"Hi World\"}]}' http://host.com/api/translate/berardi.php";
   @endcode

   @return An XML web page.
 */

require_once '../common/import_functions.php';

load('translator.php', '../wicom/translator/');
load('metamodeltranslator.php', '../wicom/translator/');

load('owllinkdocument.php', '../wicom/translator/documents/');
load('owldocument.php', '../wicom/translator/documents/');

load("crowd_dl_alcin_meta.php", "../wicom/translator/strategies/strategydlmeta/crowd10/");
load("crowd_dl_alcqi_meta_exists.php", "../wicom/translator/strategies/strategydlmeta/crowd20/");

load('owllinkbuilder.php', '../wicom/translator/builders/');
load('owlbuilder.php', '../wicom/translator/builders/');
load('htmlbuilder.php', '../wicom/translator/builders/');

use Wicom\Translator\Translator;
use Wicom\Translator\MetamodelTranslator;

use Wicom\Translator\Strategies\Strategydlmeta\crowd10\DLALCINMeta;
use Wicom\Translator\Strategies\Strategydlmeta\crowd20\DLALCQIMetaExists;

use Wicom\Translator\Builders\OWLlinkBuilder;
use Wicom\Translator\Builders\OWLBuilder;
use Wicom\Translator\Builders\HTMLBuilder;


$syntax = 'rdfxml';
if (array_key_exists('syntax',$_REQUEST)){
    $syntax = $_REQUEST['syntax'];
}

$format = 'owl2-alcqi';
if (array_key_exists('format',$_REQUEST)){
    $format = $_REQUEST['format'];
}

if ( ! array_key_exists('json', $_POST)){
    echo "
    \"json\" parameter must be given";
}else{
    $builder = null;
    $res = "";

    switch ($format){
    case "owl2-alcin":
        $builder = new OWLBuilder();
        $builder->set_syntax($syntax);
        $trans = new MetamodelTranslator(new DLALCINMeta(), $builder);
        $res = $trans->to_owl2($_POST['json']);
        break;
    case "owl2-alcqi":
        $builder = new OWLBuilder();
        $builder->set_syntax($syntax);
        $trans = new MetamodelTranslator(new DLALCQIMetaExists(), $builder);
        $res = $trans->to_owl2($_POST['json']);
        break;
    case "owllink-alcin":
        $builder = new OWLlinkBuilder();
        $trans = new MetamodelTranslator(new DLALCINMeta(), $builder);
        $res = $trans->to_owllink($_POST['json']);
        break;
    case "owllink-alcqi":
        $builder = new OWLlinkBuilder();
        $trans = new MetamodelTranslator(new DLALCQIMetaExists(), $builder);
        $res = $trans->to_owllink($_POST['json']);
        break;
    case "html" :
        $builder = new HTMLBuilder();
        $trans = new MetamodelTranslator(new DLMeta(), $builder);
        $res = $trans->to_owl2($_POST['json']);
        break;
    default:
        die("Format not recognized");
    }

    print_r($res);
}

?>
