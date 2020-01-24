<?php
/*

   Copyright 2020

   Author: GILIA

   metajsonbuildertest.php

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
load("metajsonbuilder.php","wicom/translator/builders/");

use Wicom\Translator\Documents\MetaJSONDocument;
use Wicom\Translator\Builders\MetaJSONBuilder;

/**
   @testdox UMLJSonBuilder tests
*/
class MetaJSONBuilderTest extends PHPUnit\Framework\TestCase{

    /**
       @testdox Return an empty KF JSON validated OK against KF JSON Schema.

       @see KF JSON Schema 'wicom/translator/strategies/strategydlmeta/kfmetaScheme.json'
     */
    public function testKFSchemaOK(){
      $expected = <<<'EOT'
      {
        "Entity type":
          {
            "Object type": [],
            "Data type" : [],
      			"Value property": []
      		},
       "Role": [],
       "Relationship":
         {
           "Subsumption": [],
           "Relationship" : [],
           "Attributive Property": []
         },
       "Constraints" : {
         "Disjointness constraints" :
          {
            "Disjoint object type": [],
            "Disjoint role": []
          },
          "Completeness constraints" : [],
          "Cardinality constraints":
          {
            "Object type cardinality": [],
            "Attibutive property cardinality": []
          }
        }
      }
EOT;

        $d = new MetaJSONBuilder();
        $actual = $d->get_product()->to_json();
        $this->assertJsonStringEqualsJsonString(trim($expected), $actual, true);
    }

}

?>
