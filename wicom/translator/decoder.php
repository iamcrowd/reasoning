<?php
/*

   Copyright 2018 GILIA

   Author: GILIA

   decoder.php

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

namespace Wicom\Translator;

load('ontoextractor.php', './strategies/sparqldl/');
load('graphicalaxioms.php', './strategies/sparqldl/');

use function \load;
use function \json_decode;

use Wicom\Translator\Strategies\SPARQLDL\OntoExtractor;
use Wicom\Translator\Strategies\SPARQLDL\GraphicalAxioms;

/**
   Decode an Ontology OWL to be drawn in crowd using diagram UML/EER/ORM according to an encoding strategy.

   1. Give a Strategy for specifying the algorithm for decoding the OWL 2.
   2. Give a Builder for specifying the UML/EER/ORM json input format.

   # JSON Format

   We expect the following fields:

   - `classes` : An Array of classes information. Each class should have:
     - `attrs` An array of strings representing all attributes names
     - `methods` An array of strings representing all attributes names
     - `name` A string which represent the name of the class.
   - links : An array of links information. Each link should have:
     - `classes` : An array of strings with the name of the classes involved on the relationship.
     - `multiplicity` : An array of strings with the multiplicity on each class side.
     - `name` : A string with the name of the link.
     - `type` : A string with the type name of the link. Could be: "association", "generalization".

   ## Example
   @code{json}
   {"classes": [
     {"attrs":[], "methods":[], "name": "Person"},
     {"attrs":[], "methods":[], "name": "Cellphones"}],
    "links": [
     {"classes": ["Person", "Cellphones"],
      "multiplicity": ["1..1", "1..*"],
      "name": "hasCellphone",
      "type": "association"}
      ]
   }
   @endcode

 */
class Decoder{
    protected $strategy = null;
    protected $jsonbuilder = null;

    function __construct($strategy, $jsonbuilder){
        $this->strategy = $strategy;
        $this->jsonbuilder = $jsonbuilder;
    }

    /**
       @param $owl2 An OWL 2 Ontology.
       @param $ontologyIRI A JSON element containing a prefix and IRI for the ontologyIRI tag.
       @param $prefix A hashed JSON containing elements {"prefix": prefix, "iri": IRI} for the ontology prefixes.
       @return an UML Json.
     */
    function to_json($owl2, $ontologyIRI, $prefix){

        $extractor = new OntoExtractor();
        $extractor->extractor($owl2);

        $this->jsonbuilder->set_ontologyIRI($ontologyIRI);
        $this->jsonbuilder->set_prefixes($prefix);

        $this->strategy->decode_classes($extractor, $this->jsonbuilder);
        $this->strategy->decode_subsumptions($extractor, $this->jsonbuilder);
        $this->strategy->decode_relationships($extractor, $this->jsonbuilder);
        $this->strategy->decode_attributes($extractor, $this->jsonbuilder);
        $this->strategy->decode_equivalences($extractor, $this->jsonbuilder);
        $this->strategy->decode_disjointness($extractor, $this->jsonbuilder);
        $this->strategy->decode_rolehierarchy($extractor, $this->jsonbuilder);

        $uml = $this->jsonbuilder->get_product();

        return $uml->to_json();

    }


}
