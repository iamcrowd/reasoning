<?php
/*

   Copyright 2020

   Author: GILIA

   metajsondocumenttest.php

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

//use function \load;
load("metajsondocument.php","wicom/translator/documents/");


use Wicom\Translator\Documents\MetaJSONDocument;

/**
   @testdox UMLJSonDocument tests
*/
class MetaJSONDocumentTest extends PHPUnit\Framework\TestCase{

    /**
       @testdox Convert an empty ontology into JSON.
     */
    public function testKFConstructor(){
      $expected = <<<'EOT'
        {
          "Entity type": {
          "Object type": [],
          "Data type": [],
          "Value property": [
              {
                "name": "",
                "domain": [],
                "value type": ""
              }
            ]
          }
        }
EOT;

        $d = new MetaJSONDocument();
        $actual = $d->to_json();
        $this->assertJsonStringEqualsJsonString(trim($expected), $actual, true);
    }

    /**
       @testdox Test inserting Object types into a KF metamodel JSON
     */
    public function testOTintoKFJSON(){
      $expected = <<<'EOT'
        {
          "Entity type": {
            "Object type": ["http://crowd.fi.uncoma.edu.ar/kb1#Dog"],
            "Data type": [],
            "Value property": [
              {
                "name": "",
                "domain": [],
                "value type": ""
              }
            ]
          }
        }
EOT;

      $d = new MetaJSONDocument();
      $d->insert_object_type("http://crowd.fi.uncoma.edu.ar/kb1#Dog");
      $actual = $d->to_json();

      $this->assertJsonStringEqualsJsonString(trim($expected), $actual, true);
    }

}

?>
