<?php
/*

   Copyright 2019

   Author: gilia

   metamodel_translator.php

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

use function \load;

use function \json_decode;


class MetamodelTranslator{
    protected $strategy = null;
    protected $builder = null;

    /**
       @todo The translator should add queries?
     */
    protected $with_queries = true;

    function __construct($strategy, $builder){
        $this->strategy = $strategy;
        $this->builder = $builder;
        $this->with_queries = true;
    }

    function set_with_queries($bool){
        $this->with_queries = $bool;
    }

    function get_with_queries(){
        return $this->with_queries;
    }


    function get_ontologyURI_fromNS($json_ns){
      if (array_key_exists("namespaces", $json_ns)) {

        if (array_key_exists("ontologyIRI", $json_ns["namespaces"])) {
          return $json_ns["namespaces"]["ontologyIRI"];
        }
        else return null;
      }
      else return null;
    }

    function get_other_URIs_fromNS($json_ns){
      if (array_key_exists("namespaces", $json_ns)) {

        if ((array_key_exists("defaultIRIs", $json_ns["namespaces"])) &&
           (array_key_exists("IRIs", $json_ns["namespaces"]))) {

          $uris = [];
          $uris = array_merge($json_ns["namespaces"]["defaultIRIs"], $json_ns["namespaces"]["IRIs"]);
          return $uris;
        }
        else return [];
      }
      return [];
    }

    /**
       @param json A String.
       @return an XML OWLlink String.
     */
    function to_owllink($json){
        $json_obj = json_decode($json, true);
        $ontoURI = $this->get_ontologyURI_fromNS($json_obj);
        $uris = $this->get_other_URIs_fromNS($json_obj);
        $this->builder->insert_header(true, true, $ontoURI, $uris);
        $this->strategy->translate($json, $this->builder);

        if (array_key_exists("owllink", $json_obj)){
            $this->builder->insert_owllink($json_obj["owllink"]);
        }

        if ($this->with_queries){
            $this->strategy->translate_queries($this->strategy, $this->builder);
        }

        $this->builder->insert_footer();
        $document = $this->builder->get_product();
        return $document->to_string();
    }


    /**
       @param json A String.
       @return a XML OWL2 String.
     */
    function to_owl2($json){
        $json_obj = json_decode($json, true);
        $ontoURI = $this->get_ontologyURI_fromNS($json_obj);
        $uris = $this->get_other_URIs_fromNS($json_obj);
        $this->builder->insert_header_owl2($ontoURI, $uris);
        $this->strategy->translate($json, $this->builder);

        if (array_key_exists("owllink", $json_obj) &&
            count($json_obj["owllink"]) != 0){
            $owl_str = $this->insertedOWLlink2OWLNaive($json_obj["owllink"]);
            $this->builder->insert_owl2($owl_str);
        }

        $this->builder->insert_footer();
        $document = $this->builder->get_product();
        return $document->to_string();
    }


}
