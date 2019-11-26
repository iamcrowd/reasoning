<?php
/*

   Copyright 2016 Giménez, Christian

   Author: Giménez, Christian

   documentbuilder.php

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
load("documents.php");

use Wicom\Translator\Documents\Document;

/**
   I set the common behaviour for every DocumentBuilder subclass.

   @abstract
 */
abstract class DocumentBuilder extends Documents{
    protected $product = null;

    /**
       Shorthand for a DL complete declaration.

       In DL this would create the "Class is subset of Top" instead only the 
       class name.

       @param $name The class name to insert.
    */
    public function insert_class_declaration($name){
        $this->insert_class($name);
    }
    public function insert_dataproperty_declaration($name){
        $this->insert_dataproperty($name);
    }
    
    abstract public function insert_class($name, $col_attrs = []);
    abstract public function insert_dataproperty($name, $datatype);
    
    /**
       Depending on the subclass, add an OWLlink text directly.
     */
    public function insert_owllink($text){}

    abstract public function insert_footer();

    /*   abstract public function insert_satisfiable();
       abstract public function insert_satisfiable_class($classname); */

    public function insert_getSubClassHierarchy_query(){}
    public function insert_get_disjointClasses_query($classname){}
    public function insert_get_equivalentClasses_query($classname){}
    public function insert_getPrefixes(){}
    public function insert_isEntailedDirectSubObjPropertyOf_query($lst_xsclassnames){}
    public function insert_isEntailedDirectDisjointClasses_query($lst_classnames){}
    public function insert_isEntailedDirectSubClasses_query($lst_classanames){}
    public function insert_isEntailed_query($lst_classnames){}
    public function insert_getSubObjectPropertyHierarchy_query(){}
    public function insert_satisfiable_objectProperty($objprop_name){}
    public function insert_satisfiable_dataProperty($dataprop_name){}
    public function insert_satisfiable_class($classname){}


    /**
       @name DL List Translation

       A DL List is a list of Description Logic operands and parameters in
       preorder form. There are some declaration supported, more can be added
       as much as needed in the DL_element() function.

       The first element of the list declares the operation or the type of the
       element is representing, a class or a role.

       ```php
       ['class' => $classname]
       ['role' => $rolename]
       ['subclass' => [$class_or_expression1, $class_or_expression2, $others]]
       ['exists' => [$role, $class]]
       ['inverse' => $class_or_expression]
       ['mincard' => [$number, $role]]
       ['maxcard' => [$number, $role]]
       ['intersection' => [$class_or_expression1,$class_or_expression2, $others]]
       ```
     */
    ///@{
    public function translate_DL($DL_list){

        //		print_r($DL_list);
        foreach ($DL_list as $elt){
            $this->DL_element($elt);
        }
    }

    /**
       I translate a single element of the DL List.

       # Overriding
       Use `$this->product` for referencing the product being built. Also, you
       can call the function translate_DL() for translating a subexpression.

       @param $elt  A List element.
     */
    protected function DL_element($elt){
        if (! \is_array($elt)){
            // Is not an array! something wrong has been passed!
            throw new \Exception("DL_element receives only hashed arrays, check your Descriptive Logic
            array if is correctly formatted. You passed a " . gettype($elt) . " on: " . print_r($elt, true) );
        }

        $key = array_keys($elt)[0];

        switch ($key){
	    case "top" :
		$this->product->insert_class($elt["top"]);
		break;
            case "class" :
                $this->product->insert_class($elt["class"]);
                break;
            case "class_min" :
                $this->insert_class_min($elt["class_min"][0], $elt["class_min"][1], $elt["class_min"][2]);
                break;
            case "class_max" :
                $this->insert_class_max($elt["class_max"][0], $elt["class_max"][1], $elt["class_max"][2]);
                break;
            case "subclass" :
                $this->product->begin_subclassof();
                $this->translate_DL($elt["subclass"]);
                $this->product->end_subclassof();
                break;

                // ObjectProperties
            case "role" :
                $this->product->insert_objectproperty($elt["role"]);
                break;
            case "intersection" :
                $this->product->begin_intersectionof();
                $this->translate_DL($elt["intersection"]);
                $this->product->end_intersectionof();
                break;
            case "union" :
                $this->product->begin_unionof();
                $this->translate_DL($elt["union"]);
                $this->product->end_unionof();
                break;
            case "complement" :
                $this->product->begin_complementof();
                $this->DL_element($elt["complement"]);
                $this->product->end_complementof();
                break;
            case "inverse" :
                $this->product->begin_inverseof();
                $this->DL_element($elt["inverse"]);
                $this->product->end_inverseof();
                break;
            case "exists" :
                $this->product->begin_somevaluesfrom();
                $this->DL_element($elt["exists"][0]);
                if (key_exists(1, $elt["exists"])){
                    $this->DL_element($elt["exists"][1]);
                }
                $this->product->end_somevaluesfrom();
                break;
            case "forall" :
                $this->product->begin_allvaluesfrom();
                $this->DL_element($elt["forall"][0]);
                $this->DL_element($elt["forall"][1]);
                $this->product->end_allvaluesfrom();
                break;
            case "mincard" :
                $this->product->begin_mincardinality($elt["mincard"][0]);
                $this->DL_element($elt["mincard"][1]);
                if (key_exists(2, $elt["mincard"])){
                    $this->DL_element($elt["mincard"][2]);
                }
                $this->product->end_mincardinality();
                break;
            case "maxcard" :
                $this->product->begin_maxcardinality($elt["maxcard"][0]);
                $this->DL_element($elt["maxcard"][1]);
                if (key_exists(2, $elt["maxcard"])){
                    $this->DL_element($elt["maxcard"][2]);
                }
                $this->product->end_maxcardinality();
                break;
	    case "domain" :
		$this->product->begin_objectpropertydomain();
		$this->DL_element($elt["domain"][0]);
		$this->DL_element($elt["domain"][1]);
		$this->product->end_objectpropertydomain();
		break;
	    case "range" :
		$this->product->begin_objectpropertyrange();
		$this->DL_element($elt["range"][0]);
		$this->DL_element($elt["range"][1]);
		$this->product->end_objectpropertyrange();
		break;
	    case "equivalentclasses" :
		$this->product->begin_equivalentclasses();
		$this->translate_DL($elt["equivalentclasses"]);
		$this->product->end_equivalentclasses();
		break;
            case "disjointclasses" :
   		$this->product->begin_disjointclasses();
   		$this->translate_DL($elt["disjointclasses"]);
   		$this->product->end_disjointclasses();
   		break;

                // DataProperties
            case "datatype" :
                $this->product->insert_datatype($elt["datatype"]);
                break;
            case "data_role" :
                $this->product->insert_dataproperty($elt["data_role"]);
                break;
            case "data_intersection" :
                $this->product->begin_intersectionof_dataproperty();
                $this->translate_DL($elt["data_intersection"]);
                $this->product->end_intersectionof_dataproperty();
                break;
            case "data_union" :
                $this->product->begin_unionof_dataproperty();
                $this->translate_DL($elt["data_union"]);
                $this->product->end_unionof_dataproperty();
                break;
            case "data_complement" :
                $this->product->begin_complementof_dataproperty();
                $this->DL_element($elt["data_complement"]);
                $this->product->end_complementof_dataproperty();
                break;
            case "data_exists" :
                $this->product->begin_somevaluesfrom_dataproperty();
                $this->DL_element($elt["data_exists"][0]);
                if (key_exists(1, $elt["data_exists"])){
                    $this->DL_element($elt["data_exists"][1]);
                }
                $this->product->end_somevaluesfrom_dataproperty();
                break;
            case "data_forall" :
                $this->product->begin_allvaluesfrom_dataproperty();
                $this->DL_element($elt["data_forall"][0]);
                $this->DL_element($elt["data_forall"][1]);
                $this->product->end_allvaluesfrom_dataproperty();
                break;
            case "data_mincard" :
                $this->product->begin_mincardinality_dataproperty($elt["data_mincard"][0]);
                $this->DL_element($elt["data_mincard"][1]);
                if (key_exists(2, $elt["data_mincard"])){
                    $this->DL_element($elt["data_mincard"][2]);
                }
                $this->product->end_mincardinality_dataproperty();
                break;
            case "data_maxcard" :
                $this->product->begin_maxcardinality_dataproperty($elt["data_maxcard"][0]);
                $this->DL_element($elt["data_maxcard"][1]);
                if (key_exists(2, $elt["data_maxcard"])){
                    $this->DL_element($elt["data_maxcard"][2]);
                }
                $this->product->end_maxcardinality_dataproperty();
                break;
	    case "data_domain" :
		$this->product->begin_datapropertydomain();
		$this->DL_element($elt["data_domain"][0]);
		$this->DL_element($elt["data_domain"][1]);
		$this->product->end_datapropertydomain();
		break;
            case "data_domain_exists" :
   		$this->DL_element($elt["data_domain_exists"][0]);
   		break;
	    case "data_range" :
		$this->product->begin_datapropertyrange();
		$this->DL_element($elt["data_range"][0]);
		$this->DL_element($elt["data_range"][1]);
		$this->product->end_datapropertyrange();
		break;
            case "data_range_exists" :
		$this->translate_DL($elt["data_range_exists"][0]);
		break;
            case "data_range_inverse" :
 		$this->DL_element($elt["data_range_inverse"][0]);
 		break;
            default:
                throw new \Exception("I don't know $key DL operand");
        }
    }

    ///@}
    // DL List Translation
}
?>
