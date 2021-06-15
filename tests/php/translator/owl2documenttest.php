<?php
/*

   Copyright 2018

   Author: Giménez, Christian. Braun, Germán

   owllinkdocumenttest.php

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
load("owldocument.php","wicom/translator/documents/");


use Wicom\Translator\Documents\OWLDocument;

class OWL2DocumentTest extends PHPUnit\Framework\TestCase{

    /**
       @testdox Generate an OWL2 Subclass
     */
    public function testOWL2Subclass(){
	     $expected = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
        <Ontology
          xmlns=\"http://www.w3.org/2002/07/owl#\"
          xmlns:rdf=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"
          xmlns:xml=\"http://www.w3.org/XML/1998/namespace\"
          xmlns:xsd=\"http://www.w3.org/2001/XMLSchema#\"
          xmlns:rdfs=\"http://www.w3.org/2000/01/rdf-schema#\"
          xml:base=\"http://crowd.fi.uncoma.edu.ar/kb1/\"
          ontologyIRI=\"http://crowd.fi.uncoma.edu.ar/kb1/\">
          <Prefix name=\"\" IRI=\"http://crowd.fi.uncoma.edu.ar/kb1/\"/>
          <Prefix name=\"rdf\" IRI=\"http://www.w3.org/1999/02/22-rdf-syntax-ns#\"/>
          <Prefix name=\"rdfs\" IRI=\"http://www.w3.org/2000/01/rdf-schema#\"/>
          <Prefix name=\"xsd\" IRI=\"http://www.w3.org/2001/XMLSchema#\"/>
          <Prefix name=\"owl\" IRI=\"http://www.w3.org/2002/07/owl#\"/>
          <Declaration>
            <Class IRI=\"Class1\"/>
          </Declaration>
          <SubClassOf>
            <Class IRI=\"Class2\"/>
            <Class IRI=\"Class1\"/>
          </SubClassOf>
        </Ontology>";

	       $d = new OWLDocument();
	       $d->start_document([
            ['prefix' => 'crowd',
             'value' => "http://crowd.fi.uncoma.edu.ar/kb1/"]]);
	       $d->set_ontology_prefixes([
	          ["prefix" => "",
	           "value" => "http://crowd.fi.uncoma.edu.ar/kb1/"]
	          ]);

	       $d->insert_class_declaration("Class1");
	        //      $d->insert_class_declaration("Class2");
	       $d->insert_subclassof("Class2", "Class1");

	       $d->end_document();
	       $actual = $d->to_string();

	       $expected = process_xmlspaces($expected);
	       $actual = process_xmlspaces($actual);
	       $this->assertEqualXMLStructure($expected, $actual, true);
    }
}

?>
