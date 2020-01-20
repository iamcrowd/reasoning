<?php
/*

   Copyright 2020 GILIA

   Author: GILIA

   interfaceapitest.php

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

use Wicom\Importer\ConnectorAPI;
use Wicom\Importer\UnderstandAPI;

class UnderstandAPITest extends PHPUnit\Framework\TestCase
{

    /**
      @testdox Asserting that restfulAPI returns "HTTP/1.1 200 OK"
    */
    public function testHeaderOKInterface(){

      $inter = new UnderstandAPI();
      $codigo = $inter->status("ontology");
      $this->assertTrue($codigo);

    }

    /**
      @testdox Asserting that restfulAPI returns "HTTP/1.1 404 Not Found"
    */
    public function testHeader404Interface(){

      $inter = new UnderstandAPI();
      $codigo = $inter->status("ontologyyyyy");
      $this->assertNotTrue($codigo);

    }

    /**
      @testdox Test List of ontologies
    */
    public function testListOfOntolInterface(){

        $inter = new UnderstandAPI();
        $actual = $inter->listOntologies();
    }

    /**
      @testdox Test Ontology by ID
    */
    public function testOntolByIdInterface(){

        $inter = new UnderstandAPI();
        $inter->getOntologyById("12");
        $name = $inter->getOntologyName();
        $uri = $inter->getOntologyURI();
        $this->assertEquals("camera", $name, true);
        $this->assertEquals("http://protege.stanford.edu/ontologies/camera.owl", $uri, true);
    }

    /**
      @testdox Test Class by ID
    */
    public function testClassByIdInterface(){

        $inter = new UnderstandAPI();
        $inter->getClassById("731");
        $name = $inter->getClassName();
        $uri = $inter->getClassURI();
        $prefix = $inter->getClassPrefix();
        $this->assertEquals(".Viewer", $name, true);
        $this->assertEquals("http://www.xfront.com/owl/ontologies/camera/#Viewer", $uri, true);
        $this->assertEquals("camera", $prefix, true);

    }

}
