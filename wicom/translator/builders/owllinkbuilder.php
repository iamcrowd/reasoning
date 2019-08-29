<?php
/*

   Copyright 2016 Giménez, Christian

   Author: Giménez, Christian - Braun, Germán

   OWLlinkBuilder.php

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
load("owllinkdocument.php", "../documents/");

use Wicom\Translator\Documents\OWLlinkDocument;

class OWLlinkBuilder extends DocumentBuilder{


    protected $actual_kb = null;

    function __construct(){
        $this->product = new OWLlinkDocument;
        $this->min_max = [];
    }



    /**
    @param $createkb A Boolean flag for setting CreateKB tags
    @param $starttell A Boolean flag for starting Tell elements
    @param $ontologyIRI An Array containing the IRI for the ontology: ["prefix" => "", "value" => ""]
    @param $uris An Array containing the Request IRIs: [["prefix" => "", "value" => ""], ... , ["prefix" => "", "value" => ""]]
    */

    public function insert_header($createkb=true, $starttell=true, $ontologyIRI = [], $uris = []){
        $this->product->start_document($ontologyIRI, []);

        if (empty($ontologyIRI)){
          $this->actual_kb = "http://crowd.fi.uncoma.edu.ar/kb1/";
        } else {
          $this->actual_kb = $ontologyIRI["value"];
        }

        if ($createkb){
            $this->product->insert_create_kb($ontologyIRI, $uris);
        }

        $this->product->set_abbreviatedIRIs(false);

        if ($starttell){
            $this->product->start_tell();
        }
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

    public function insert_class_declaration($name){
      $this->product->insert_class_declaration($name);
    }

    public function insert_class($name, $col_attrs = []){
        $this->product->insert_class($name);
    }

    public function insert_dataproperty_declaration($name){
      $this->product->insert_dataproperty_declaration($name);
    }

    public function insert_dataproperty($name, $datatype){
        $this->product->insert_dataproperty($name, $datatype);
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

    public function insert_subobjectpropertyof($child, $father){
      $this->product->insert_subobjectpropertyof($child, $father);
    }

    public function insert_subdatapropertyof($child, $father){
      $this->product->insert_subdataectpropertyof($child, $father);
    }

    protected function ensure_end_tell(){
        $this->product->end_tell();
    }

    public function set_tell_toEdit($bool){
       $this->product->set_in_tell($bool);
    }

    public function get_KB(){
      return $this->product->get_firstElementKB();
    }

    /**
       @name Queries
    */
    ///@{

    /**
       Insert "is diagram/KB satisfiable" query.
     */
    public function insert_satisfiable(){
        $this->ensure_end_tell();
        $this->product->insert_satisfiable();
    }

    public function insert_satisfiable_class($classname){
        $this->ensure_end_tell();
        $this->product->insert_satisfiable_class($classname);
    }

    public function insert_satisfiable_objectProperty($opname){
        $this->ensure_end_tell();
        $this->product->insert_satisfiable_objectProperty($opname);
    }

    public function insert_satisfiable_dataProperty($dpname){
        $this->ensure_end_tell();
        $this->product->insert_satisfiable_dataProperty($dpname);
    }

    public function insert_getPrefixes(){
      $this->ensure_end_tell();
      $this->product->insert_getPrefixes();
    }

    /**
       Insert an entail OWLlink query for checking entailed classes.

       @param $array An array of class names.
     */
    public function insert_isEntailed_query($array){
        $this->ensure_end_tell();
        $this->product->insert_isEntailed_query($array);
    }


    /**
       Insert an entail OWLlink query for checking entailedDirect subclasses.

       @param $array An array of class names.
     */
    public function insert_isEntailedDirectSubClasses_query($array){
        $this->ensure_end_tell();
        $this->product->insert_isEntailedDirectSubClasses_query($array);
    }

    /**
       Insert an entail OWLlink query for checking entailedDirect disjointclasses.

       @param $array An array of class names.
     */
    public function insert_isEntailedDirectDisjointClasses_query($array){
        $this->ensure_end_tell();
        $this->product->insert_isEntailedDirectDisjointClasses_query($array);
    }

    /**
       Insert a query denominated GetAllObjectProperties for the current kb.

     */
    public function insert_get_all_object_properties_query(){
        $this->ensure_end_tell();
        $this->product->insert_get_all_object_properties_query();
    }


    /**
       Insert a query denominated GetAllClasses for the current kb.

     */
    public function insert_get_all_classes_query(){
        $this->ensure_end_tell();
        $this->product->insert_get_all_classes_query();
    }

    /**
       Insert a query denominated GetSubClasses for all the classes in the array.

       @param $array An array of Strings with classnames.
     */
    public function insert_get_subClasses_query($array){
        $this->ensure_end_tell();
        $this->product->insert_get_subClasses_query($array);
    }

    /**
       Insert a query denominated GetSuperClasses for all the classes in the array.

       @param $array An array of Strings with classnames.
     */
    public function insert_get_superClasses_query($array){
        $this->ensure_end_tell();
        $this->product->insert_get_superClasses_query($array);
    }

    /**
       Insert a query denominated GetEquivalentClasses for all the classes in the array.

       @param $array An array of Strings with classnames.
     */
    public function insert_get_equivalentClasses_query($array){
        $this->ensure_end_tell();
        $this->product->insert_get_equivalentClasses_query($array);
    }

    /**
       Insert a query denominated GetDisjointClasses for all the classes in the array.

       @param $array An array of Strings with classnames.
     */
    public function insert_get_disjointClasses_query($array){
        $this->ensure_end_tell();
        $this->product->insert_get_disjointClasses_query($array);
    }

    /**
       Insert a query denominated GetSubClassHierarchy for the current kb.

     */
    public function insert_getSubClassHierarchy_query(){
        $this->ensure_end_tell();
        $this->product->insert_getSubClassHierarchy_query();
    }

    /**
       Insert a query denominated GetSubClassHierarchy for the current kb.

     */
    public function insert_getSubObjectPropertyHierarchy_query(){
      $this->ensure_end_tell();
      $this->product->insert_getSubObjectPropertyHierarchy_query();
    }


    ///@}
    // Queries

    public function insert_owllink($text){
//        $this->ensure_end_tell();
        $this->product->insert_owllink($text);
    }


    public function insert_footer(){
        $this->product->end_tell();
        $this->product->end_document();
    }

    /**
       Retrieve the classes with its min and max if its exists.

       @return A hash like ["classname" => ["min_class_name", "max_class_name"]]
       @todo Move this into the Strategy.
     */
    public function get_classes_with_min_max(){
        return $this->min_max;
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




    /**
       @name DL list translation
    */
    ///@{
    ///@}
    // DL List Translation
}
?>
