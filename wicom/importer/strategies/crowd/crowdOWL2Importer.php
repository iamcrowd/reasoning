<?php
/*

   Copyright 2020 GILIA

   Author: GILIA

   crowdOWLImporter.php

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

use function \load;

load('strategyimporter.php', '../../strategies/');
load('understandAPI.php', '../../../importer/interface/');
load("metajsonbuilder.php", "../../../../wicom/translator/builders/");

use Wicom\Translator\Builders\MetaJSONBuilder;
use Wicom\Importer\UnderstandAPI;

use SimpleXMLIterator;

/**
   This module implements the graphical-oriented OWL importer for crowd.
   OWL 2 is imported as a KF metamodel instance.

   @see UnderstandAPI class for description about the JSON format.
 */
class OWL2Importer extends StrategyImporter{

    protected $api;
    protected $anmeta;
    protected $onto_id;

    function __construct($id){
        //parent::__construct($id);
        $this->api = new UnderstandAPI();
        $this->anmetainstance = new MetaJSONBuilder();
        $this->onto_id = $id;
    }

    /**
      It returns the current instance for the imported ontology.
    */
    function getKFInstance(){
      return $this->anmetainstance->get_product()->to_json();
    }

    /**
      Get classes from OWL ontology and add them to an instance of KF with object types
    */
    function import_classes(){
      $this->api->getOntologyById($this->onto_id);
      $classasmeta = $this->api->getClasses();

      foreach ($classasmeta as $anclass) {
        $this->api->getClassById($this->api->getIDfromAPIElementID($anclass));
        $this->anmetainstance->insert_object_type($this->api->getClassURI());
      }
    }

    function import_object_properties(){}

    function import_data_properties(){}

    /**
      Get subsumptions from OWL ontology and add them to an instance of KF as relationships
    */
    function import_subsumptions(){
      $this->api->getOntologyById($this->onto_id);
      $subasmeta = $this->api->getSubClasses();

      foreach ($subasmeta as $ansub) {
        $this->api->getSubClassById($this->api->getIDfromAPIElementID($ansub));

        $parent_id = $this->api->getSubClassParent();
        $this->api->getClassById($this->api->getIDfromAPIElementID($parent_id));
        $parent = $this->api->getClassURI();

        $child_id = $this->api->getSubClassChild();
        $this->api->getClassById($this->api->getIDfromAPIElementID($child_id));
        $child = $this->api->getClassURI();
        
        $this->anmetainstance->insert_subsumption($parent, $child, $ansub);
      }
    }

}
