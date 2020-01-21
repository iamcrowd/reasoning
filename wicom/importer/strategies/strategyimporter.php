<?php
/*

   Copyright 2020 GILIA

   Author: GILIA

   strategyimporter.php

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

namespace Wicom\Importer;

load("metajsonbuilder.php", "../../../wicom/translator/builders/");

use Wicom\Translator\Builders\MetaJSONBuilder;

/**
   @see Translator class for description about the JSON format.
*/
abstract class StrategyImporter{

    function __construct(){}

    /**
       Translate the given JSON String into the OWLlink XML string.

       @param json The JSON string
       @param build The Builder instance.
       @return An XML String.

       @see Translator class for description about the JSON format.
     */
    abstract function import_classes();

    abstract function import_object_properties();

    abstract function import_data_properties();

    abstract function import_subsumptions();


}
