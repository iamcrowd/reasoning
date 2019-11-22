<?php
/*

   Copyright 2017 GILIA

   Author: Giménez, Christian and Braun, Germán

   owlbuilder.php

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

namespace Wicom\Translator\Builders;

use function \load;
load("documentbuilder.php");
load("owldocument.php", "../documents/");

use Wicom\Translator\Documents\OWLDocument;

class OWLBuilder extends DocumentBuilder{

    protected $actual_kb = null;

    function __construct($ontologyIRI = null, $iris = []){
        $this->product = new OWLDocument;
	// $this->insert_header_owl2($ontologyIRI, $iris);
        $this->min_max = [];
    }

    /**
       @param $ontologyIRI {string} A string containing the IRI for the ontology
       @param $uris {array} An Array containing the OWL 2 header IRIs:
       [["prefix" => "", "value" => ""], ... , ["prefix" => "", "value" => ""]]
     */
    public function insert_header_owl2($ontologyIRI = null, $uris = []){
        if (($ontologyIRI == null) or ($ontologyIRI == '')){
            $this->actual_kb = OWLDocument::default_ontologyIRI;
            $ontologyIRI = $this->actual_kb;
        } else {
            $this->actual_kb = $ontologyIRI;
        }
	
        $this->product->start_document($ontologyIRI, []);
        $this->product->set_ontology_prefixes($uris);
    }

    public function insert_class_declaration($name){
	$this->product->insert_class_declaration($name);
    }

    public function insert_class($name, $col_attrs = []){
        $this->product->insert_class($name);
    }

    public function insert_dataproperty_declaration($name){
	$this->product->insert_dataproperty_declaration($name);
    }

    public function insert_dataproperty($name){
        $this->product->insert_dataproperty($name);
    }

    public function insert_objectproperty_declaration($name){
	$this->product->insert_objectproperty_declaration($name);
    }

    public function insert_objectproperty($name){
        $this->product->insert_objectproperty($name);
    }

    public function insert_subclassof($child, $father){
        $this->product->insert_subclassof($child, $father);
    }

    public function insert_footer(){
        $this->product->end_document();
    }


    /**
       @todo Move this into the Strategy.
     */
    public function _normalise_strategy($strategyClass){
	return str_replace("#", "/", $strategyClass);
    }
    /**
       @todo Move this into the Strategy.
     */
    public function insert_class_min($classname, $rolename, $i){
	$class_n = $this->_normalise_strategy($classname);
	$role_n = $this->_normalise_strategy($rolename);
	$minname = $class_n.'_'.$role_n.'_min'.'_'.$i;

	if (key_exists($classname, $this->min_max)){
            $this->min_max[$classname][0] = $minname;
	}else{
            $this->min_max[$classname] = [$minname, null];
	}
	$this->product->insert_class($minname);
    }
    /**
       @todo Move this into the Strategy.
     */
    public function insert_class_max($classname, $rolename, $i){
	$class_n = $this->_normalise_strategy($classname);
	$role_n = $this->_normalise_strategy($rolename);
	$maxname = $class_n.'_'.$role_n.'_max'.'_'.$i;

	if (key_exists($classname, $this->min_max)){
            $this->min_max[$classname][1] = $maxname;
	}else{
            $this->min_max[$classname] = [null, $maxname];
	}

	$this->product->insert_class($maxname);
    }


    /**
       Reimplementation because we have to finish the product
       before getting it.
     */
    public function get_product($finish=false){
        if ($finish){
            $this->product->end_document();
        }
        return $this->product;
    }

    public function insert_owl2($text){
        $this->product->insert_owl2($text);
    }

    /**
       @name DL list translation
     */
    ///@{
    ///@}
    // DL List Translation
}
?>
