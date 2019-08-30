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
load("umlgraphicalrules.php","wicom/translator/strategies/goms/");


use Wicom\Translator\Strategies\GOMS\UMLGraphicalRules;
use SimpleXMLElement;

class GraphicalRulesTest extends PHPUnit\Framework\TestCase{

    public function testUMLClassRule(){
        $input = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
        <Ontology xmlns="http://www.w3.org/2002/07/owl#"
          xml:base=""
          xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
          xmlns:xml="http://www.w3.org/XML/1998/namespace"
          xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
          xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
          ontologyIRI="http://localhost/kb1">
          <SubClassOf>
            <Class IRI="#A"/>
            <Class abbreviatedIRI="owl:Thing"/>
          </SubClassOf>
          <SubClassOf>
            <Class IRI="#B"/>
            <Class abbreviatedIRI="owl:Thing"/>
          </SubClassOf>
          <SubClassOf>
            <Class IRI="#C"/>
            <Class IRI="#B"/>
          </SubClassOf>
        </Ontology>
XML;
       $expected = '[{"classA":"#A","classB":"owl:Thing"},{"classA":"#B","classB":"owl:Thing"}]';

       $d = new UMLGraphicalRules($input);
       $actual = $d->search_classes();

       $this->assertEquals($expected, $actual, true);

    }

    public function testUMLSubRule(){
        $input = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
        <Ontology xmlns="http://www.w3.org/2002/07/owl#"
          xml:base=""
          xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
          xmlns:xml="http://www.w3.org/XML/1998/namespace"
          xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
          xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
          ontologyIRI="http://localhost/kb1">
          <SubClassOf>
            <Class IRI="#A"/>
            <Class abbreviatedIRI="owl:Thing"/>
          </SubClassOf>
          <SubClassOf>
            <Class IRI="#B"/>
            <Class abbreviatedIRI="owl:Thing"/>
          </SubClassOf>
          <SubClassOf>
            <Class IRI="#C"/>
            <Class IRI="#B"/>
          </SubClassOf>
          <SubClassOf>
            <Class IRI="#D"/>
            <Class IRI="#A"/>
          </SubClassOf>
        </Ontology>
XML;

       $expected = '[{"classA":"#C","classB":"#B"},{"classA":"#D","classB":"#A"}]';

       $d = new UMLGraphicalRules($input);
       $actual = $d->search_sub();

       $this->assertEquals($expected, $actual, true);

    }

    public function testUMLSubPartialRule(){
        $input = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
        <Ontology xmlns="http://www.w3.org/2002/07/owl#"
          xml:base=""
          xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
          xmlns:xml="http://www.w3.org/XML/1998/namespace"
          xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
          xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
          ontologyIRI="http://localhost/kb1">
          <SubClassOf>
            <Class IRI="#A"/>
            <Class abbreviatedIRI="owl:Thing"/>
          </SubClassOf>
          <SubClassOf>
            <Class IRI="#B"/>
            <Class IRI="#A"/>
          </SubClassOf>
          <SubClassOf>
            <Class IRI="#C"/>
            <Class IRI="#A"/>
          </SubClassOf>
          <SubClassOf>
            <ObjectUnionOf>
              <Class IRI="#B"/>
              <Class IRI="#C"/>
            </ObjectUnionOf>
            <Class IRI="#A"/>
          </SubClassOf>
        </Ontology>
XML;

       $expected = '[{"union":["#B","#C"],"class":["#A"]}]';

       $d = new UMLGraphicalRules($input);
       $parent = "#A";
       $actual = $d->search_subpartial($parent);

       $this->assertEquals($expected, $actual, true);

    }

    public function testUMLSubTotalRule(){
        $input = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
        <Ontology xmlns="http://www.w3.org/2002/07/owl#"
          xml:base=""
          xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
          xmlns:xml="http://www.w3.org/XML/1998/namespace"
          xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
          xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
          ontologyIRI="http://localhost/kb1">
          <SubClassOf>
            <Class IRI="#A"/>
            <Class abbreviatedIRI="owl:Thing"/>
          </SubClassOf>
          <SubClassOf>
            <Class IRI="#B"/>
            <Class IRI="#A"/>
          </SubClassOf>
          <SubClassOf>
            <Class IRI="#C"/>
            <Class IRI="#A"/>
          </SubClassOf>
          <SubClassOf>
            <ObjectUnionOf>
              <Class IRI="#B"/>
              <Class IRI="#C"/>
            </ObjectUnionOf>
            <Class IRI="#A"/>
          </SubClassOf>
          <SubClassOf>
            <Class IRI="#A"/>
            <ObjectUnionOf>
              <Class IRI="#B"/>
              <Class IRI="#C"/>
            </ObjectUnionOf>
          </SubClassOf>
        </Ontology>
XML;

        $expected = '[{"total":["#B","#C"],"class":["#A"]},{"total":["#B","#C"],"class":["#A"]}]';

        $d = new UMLGraphicalRules($input);
        $parent = "#A";
        $actual = $d->search_subtotal($parent);

        $this->assertEquals($expected, $actual, true);
    }

    public function testUMLDisjRule(){
        $input = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
        <Ontology xmlns="http://www.w3.org/2002/07/owl#"
          xml:base=""
          xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
          xmlns:xml="http://www.w3.org/XML/1998/namespace"
          xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
          xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
          ontologyIRI="http://localhost/kb1">
          <SubClassOf>
            <Class IRI="#A"/>
            <Class abbreviatedIRI="owl:Thing"/>
          </SubClassOf>
          <SubClassOf>
            <Class IRI="#B"/>
            <Class IRI="#A"/>
          </SubClassOf>
          <SubClassOf>
            <Class IRI="#C"/>
            <Class IRI="#A"/>
          </SubClassOf>
          <SubClassOf>
            <ObjectUnionOf>
              <Class IRI="#B"/>
              <Class IRI="#C"/>
            </ObjectUnionOf>
            <Class IRI="#A"/>
          </SubClassOf>
          <DisjointClasses>
            <Class IRI="#B"/>
            <Class IRI="#C"/>
          </DisjointClasses>
          <DisjointClasses>
            <Class IRI="#C"/>
            <Class IRI="#A"/>
          </DisjointClasses>
        </Ontology>
XML;

        $expected1 = '[{"disjoint":["#B","#C"]},{"disjoint":["#C","#A"]}]';

        $expected2 = '[{"disjoint":["#B","#C"]}]';

        $d1 = new UMLGraphicalRules($input);
        $actual1 = $d1->search_disjointness();

        $classA = "#B";
        $classB = "#C";

        $d2 = new UMLGraphicalRules($input);
        $actual2 = $d2->search_disjointness($classA,$classB);

        $this->assertEquals($expected1, $actual1, true);
        $this->assertEquals($expected2, $actual2, true);

    }

    public function testUMLEqRule(){
        $input = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
        <Ontology xmlns="http://www.w3.org/2002/07/owl#"
          xml:base=""
          xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
          xmlns:xml="http://www.w3.org/XML/1998/namespace"
          xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
          xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
          ontologyIRI="http://localhost/kb1">
          <SubClassOf>
            <Class IRI="#A"/>
            <Class abbreviatedIRI="owl:Thing"/>
          </SubClassOf>
          <SubClassOf>
            <Class IRI="#B"/>
            <Class IRI="#A"/>
          </SubClassOf>
          <SubClassOf>
            <Class IRI="#C"/>
            <Class IRI="#A"/>
          </SubClassOf>
          <SubClassOf>
            <ObjectUnionOf>
              <Class IRI="#B"/>
              <Class IRI="#C"/>
            </ObjectUnionOf>
            <Class IRI="#A"/>
          </SubClassOf>
          <EquivalentClasses>
            <Class IRI="#B"/>
            <Class IRI="#C"/>
          </EquivalentClasses>
          <EquivalentClasses>
            <Class IRI="#C"/>
            <Class IRI="#A"/>
          </EquivalentClasses>
        </Ontology>
XML;

        $expected1 = '[{"equivalent":["#B","#C"]},{"equivalent":["#C","#A"]}]';

        $expected2 = '[{"equivalent":["#B","#C"]}]';

        $d1 = new UMLGraphicalRules($input);
        $actual1 = $d1->search_equivalence();

        $classA = "#B";
        $classB = "#C";

        $d2 = new UMLGraphicalRules($input);
        $actual2 = $d2->search_equivalence($classA,$classB);

        $this->assertEquals($expected1, $actual1, true);
        $this->assertEquals($expected2, $actual2, true);


    }


    public function testUMLXpathAxis(){
        $input = <<<XML
<?xml version="1.0" encoding="UTF-8"?>
        <Ontology xmlns="http://www.w3.org/2002/07/owl#"
          xml:base=""
          xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
          xmlns:xml="http://www.w3.org/XML/1998/namespace"
          xmlns:xsd="http://www.w3.org/2001/XMLSchema#"
          xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
          ontologyIRI="http://localhost/kb1">
          <SubClassOf>
            <Class IRI="#A"/>
            <Class abbreviatedIRI="owl:Thing"/>
          </SubClassOf>
          <SubClassOf>
            <Class IRI="#B"/>
            <Class IRI="#A"/>
          </SubClassOf>
          <SubClassOf>
            <Class IRI="#C"/>
            <Class IRI="#A"/>
          </SubClassOf>
          <SubClassOf>
            <ObjectUnionOf>
              <Class IRI="#B"/>
              <Class IRI="#C"/>
            </ObjectUnionOf>
            <Class IRI="#A"/>
          </SubClassOf>
          <SubClassOf>
            <Class IRI="#A"/>
            <ObjectUnionOf>
              <Class IRI="#B"/>
              <Class IRI="#C"/>
            </ObjectUnionOf>
          </SubClassOf>
          <EquivalentClasses>
            <Class IRI="#B"/>
            <Class IRI="#C"/>
          </EquivalentClasses>
          <EquivalentClasses>
            <Class IRI="#C"/>
            <Class IRI="#A"/>
          </EquivalentClasses>
        </Ontology>
XML;

        $expected1 = '[{"equivalent":["#B","#C"]},{"equivalent":["#C","#A"]}]';

        $expected2 = '[{"equivalent":["#B","#C"]}]';


        $xml = new SimpleXMLElement($input);

/*        $obj = "//*[local-name()='Ontology']/*[local-name()='DisjointClasses']/
                  *[local-name()='Class'][1][@IRI]/../
                  *[local-name()='Class'][2][@IRI]/.."; */

        $obj2 = "//*[local-name()='Ontology']/child::node()/*[local-name()='ObjectUnionOf']/..";

        $res = $xml->xpath($obj2);
        var_dump($res);

//        var_dump($res[0]->asXML());
//        var_dump($res[1]->asXML());

//       $this->assertEquals($expected, $actual, true);

    }


}

?>
