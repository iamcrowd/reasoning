<?php
/*

   Copyright 2018

   Author: GILIA

   rest_owl2importertest.php

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

// use function \load;
load("wicom.php", "common/");
load("owl2Importer.php", "common/");

load("owllinkbuilder.php", "wicom/translator/builders/");
load("owlbuilder.php", "wicom/translator/builders/");

use Wicom\Translator\Builders\OWLlinkBuilder;
use Wicom\Translator\Builders\OWLBuilder;

use Wicom\Wicom;
use Wicom\OWL2Importer;

class RestAPIOWL2ImporterTest extends PHPUnit\Framework\TestCase
{

  public function testRestImportClasses(){

      $importer = new OWL2Importer();
      $actual = $importer->rest_owl2importer_classes("pizza");

      var_dump($actual);

  }

}
