<?php
/**
API entry point: Translate using Berardi et al. strategy.

Copyright 2016 Giménez, Christian

Author: Giménez, Christian

berardi.php

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

PHP version >= 7.2

@category API
@package  Crowd
@author   Gimenez Christian <christian.gimenez@fi.uncoma.edu.ar>
@author   Germán Braun <german.braun@fi.uncoma.edu.ar>
@author   GILIA <nomail@fi.uncoma.edu.ar>
@license  GPLv3 https://www.gnu.org/licenses/gpl-3.0.html
@link     http://crowd.fi.uncoma.edu.ar
 */


/**
Translate to OWLlink using Berardi strategy.

The Berardi strategy implements the method explained on "Reasoning on UML
Class Diagrams" by Daniela Berardi, Diego Calvanesse and Giuseppe De
Giacomo.

Try this command:

@code
curl -d 'json={\"classes\": [{\"attrs\":[], \"methods\":[],
\"name\": \"Hi World\"}]}' http://host.com/api/translate/berardi.php";
@endcode

@return An XML web page.
 */

require_once __DIR__ . '/../common/import_functions.php';
require_once __DIR__ . '/../wicom/translator/translator.php';
require_once __DIR__ . '/../wicom/translator/documents/owllinkdocument.php';
require_once __DIR__ . '/../wicom/translator/builders/owlbuilder.php';
require_once __DIR__ . '/../wicom/translator/strategies/berardistrat.php';
require_once __DIR__ . '/../wicom/translator/builders/owllinkbuilder.php';
require_once __DIR__ . '/../wicom/translator/builders/htmlbuilder.php';

use Wicom\Translator\Translator;
use Wicom\Translator\Strategies\Berardi;
use Wicom\Translator\Builders\OWLlinkBuilder;
use Wicom\Translator\Builders\HTMLBuilder;
use Wicom\Translator\Builders\OWLBuilder;


$format = 'owllink';
if (array_key_exists('format', $_REQUEST)) {
    $format = $_REQUEST['format'];
}

if (! array_key_exists('json', $_POST)) {
    echo "
There's no \"json\" parameter :-(
Use, for example:

    curl -d 'json={\"classes\": [{\"attrs\":[], \"methods\":[],
       \"name\": \"Hi World\"}]}' http://host.com/translator/berardi.php";
    return;
}

$builder = null;

switch ($format) {
case "owl":
case "owlxml":
case "owl2":
    $builder = new OWLBuilder();
    break;
case "owllink":
    $builder = new OWLlinkBuilder();
    break;
case "html" :
    $builder = new HTMLBuilder();
    break;
default:
    die("Format not recognized");
}

$trans = new Translator(new Berardi(), $builder);
$res = $trans->to_owllink($_POST['json']);
print_r($res);
