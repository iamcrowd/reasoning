<?php
/*

   Copyright 2020 GILIA

   Author: GILIA

   apiconnectortest.php

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

use Wicom\Importer\ConnectorAPI;

class ConnectorAPITest extends PHPUnit\Framework\TestCase
{
    /**
      @testdox test API status
    */
    public function testHeader(){

      $conn = new ConnectorAPI();
      $conn->validateHeader("ontology");
      $actual = $conn->get_header()[0];

    }

    /**
      @testdox test connector to API
    */
    public function testConnector(){

        $conn = new ConnectorAPI();
        $conn->getOntologies();
        $actual = $conn->get_col_answers()[0];
        print_r($actual);

        /*$expected = process_xmlspaces($expected);
        $actual = process_xmlspaces($actual);
        $this->assertEqualXMLStructure($expected, $actual, true);*/
    }
}
