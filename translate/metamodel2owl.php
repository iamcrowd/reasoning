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

load('crowd_dlmeta.php','../wicom/translator/strategies/strategydlmeta/');

load('owllinkbuilder.php', '../wicom/translator/builders/');
load('owlbuilder.php', '../wicom/translator/builders/');
load('htmlbuilder.php', '../wicom/translator/builders/');

use Wicom\Translator\Translator;
use Wicom\Translator\MetamodelTranslator;
use Wicom\Translator\Strategies\Strategydlmeta\DLMeta;
use Wicom\Translator\Builders\OWLlinkBuilder;
use Wicom\Translator\Builders\OWLBuilder;
use Wicom\Translator\Builders\HTMLBuilder;

$format = 'owllink';
if (array_key_exists('format',$_REQUEST)){
    $format = $_REQUEST['format'];
}

if ( ! array_key_exists('json', $_POST)){
    echo "
There's no \"json\" parameter :-(
Use, for example:

    curl -d 'json={\"classes\": [{\"attrs\":[], \"methods\":[], \"name\": \"Hi World\"}]}' http://host.com/translator/berardi.php";
}else{
    $builder = null;
    $res = "";

    switch ($format){
    case "owl2":
        $builder = new OWLBuilder();
        $trans = new MetamodelTranslator(new DLMeta(), $builder);
        $res = $trans->to_owl2($_POST['json']);
        break;
    case "owllink":
        $builder = new OWLlinkBuilder();
        $trans = new MetamodelTranslator(new DLMeta(), $builder);
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
