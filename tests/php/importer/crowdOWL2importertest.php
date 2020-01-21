<?php
/*

   Copyright 2020 GILIA

   Author: GILIA

   crowdOWL2importertest.php

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
load("config.php", "config/");
load("connectorAPI.php", "wicom/importer/interface/");
load("understandAPI.php", "wicom/importer/interface/");
load("crowdOWL2Importer.php", "wicom/importer/strategies/crowd/");

use Wicom\Importer\ConnectorAPI;
use Wicom\Importer\UnderstandAPI;
use Wicom\Importer\OWL2Importer;

class UnderstandAPITest extends PHPUnit\Framework\TestCase
{

    /**
      @testdox Getting an KF instance from OWL 2 only object types
    */
    public function testObjectTypesFromOWL2(){

      $importer = new OWL2Importer("12");
      $importer->import_classes();
      $kf = $importer->getKFInstance();
      var_dump($kf);

    }

}
