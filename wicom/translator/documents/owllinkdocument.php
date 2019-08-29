<?php
/*

   Copyright 2016 Giménez, Christian

   Author: Giménez, Christian

   owllinkdocument.php

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

namespace Wicom\Translator\Documents;

use function \load;
load('document.php');

use function \preg_match;
use \XMLWriter;
use \XMLReader;

/**

   # Example

   @code{.php}
   $d = new OWLlinkDocument();
   $d->insert_startdocument();
   $d->insert_request();

   // ...

   $d->end_document();

   $d->to_string();
   @endcode

 */
class OWLlinkDocument extends Document{
    protected $content = null;

    protected $firstKBElement = "Tell";

    /**
       The current KB URI as \i. of String.


       This can be changed by creating a new KB
       (see insert_create_kb()) or by using the setter.

     */
    protected $actual_kb = null;

    protected $current_prefixes = [];

    /**
       I'm inserting Tell's queries?
    */
    protected $in_tell = false;
    protected $in_queries = false;


    protected $default_header = [
      ["attr" => "xmlns", "value" => "http://www.owllink.org/owllink#"],
      ["attr" => "xmlns:owl", "value" => "http://www.w3.org/2002/07/owl#"],
      ["attr" => "xmlns:xsi", "value" => "http://www.w3.org/2001/XMLSchema-instance"],
      ["attr" => "xsi:schemaLocation", "value" => "http://www.owllink.org/owllink# http://www.owllink.org/owllink-20091116.xsd"]
    ];

    protected $default_prefixes = [
      ["prefix" => "rdf", "value" => "http://www.w3.org/1999/02/22-rdf-syntax-ns#"],
      ["prefix" => "rdfs", "value" => "http://www.w3.org/2000/01/rdf-schema#"],
      ["prefix" => "xsd", "value" => "http://www.w3.org/2001/XMLSchema#"],
      ["prefix" => "owl", "value" => "http://www.w3.org/2002/07/owl#"]
    ];

    protected $owllink_text = "";

    public function set_actual_kb($kb_uri){
        $this->actual_kb = $kb_uri;
    }

    public function set_abbreviatedIRIs($boolean){
      $this->content->startElement("Set");
      $this->content->writeAttribute("kb", $this->actual_kb);
      $this->content->writeAttribute("key", "abbreviatesIRIs");
      if ($boolean){
        $this->content->writeElement("Literal", "true");
      } else{
        $this->content->writeElement("Literal", "false");
      }
      $this->content->endElement();
    }

    function __construct(){
        $this->content = new XMLWriter();
        $this->content->openMemory();

        $this->in_tell = false;
        $this->in_queries = false;
    }

    /**
       @name Starting and Ending the document
    */
    ///@{

    public function insert_startdocument(){
        $this->content->startDocument("1.0", "UTF-8");
    }

    public function insert_request($ontologyIRI, $reqiris = []){
      $this->content->startElement("RequestMessage");

      if ((empty($reqiris)) && (empty($ontologyIRI))){
          foreach ($this->default_header as $header){
            $this->content->writeAttribute($header["attr"], $header["value"]);
          }
          $this->content->writeAttribute("xml:base","http://crowd.fi.uncoma.edu.ar/kb1/");
      } elseif ((empty($reqiris)) && (!empty($ontologyIRI))){
            foreach ($this->default_header as $header){
              $this->content->writeAttribute($header["attr"], $header["value"]);
            }
            $this->content->writeAttribute("xml:base",$ontologyIRI["value"]);
        } elseif ((!empty($reqiris)) && (empty($ontologyIRI))){
            foreach ($reqiris as $iri){
              $this->content->writeAttribute($iri["prefix"], $iri["value"]);
            }
            $this->content->writeAttribute("xml:base","http://crowd.fi.uncoma.edu.ar/kb1/");
        } elseif ((!empty($reqiris)) && (!empty($ontologyIRI))){
            foreach ($reqiris as $iri){
              $this->content->writeAttribute($iri["prefix"], $iri["value"]);
            }
        }
    }

    /**
       Abbreviation of:

       @code{.php}
       $d->insert_startdocument();
       $d->insert_request();
       @endcode
     */
    public function start_document($ontologyIRI = [], $req = []){
      $this->insert_startdocument();
      $this->insert_request($ontologyIRI, $req);
    }

    public function end_document(){
        if ($this->in_tell) {
            $this->end_tell();
        }
        $this->content->endElement();
    }

    ///@}
    // Starting and ending the document

    public function insert_prefix($prefixes){
      foreach ($prefixes as $prefix){
        $this->content->startElement("Prefix");
        $this->content->writeAttribute("name", $prefix["prefix"]);
        $this->content->writeAttribute("fullIRI", $prefix["value"]);
        $this->content->endElement();
      }
    }

    /**
       @name KB Management Messages

       These messages is used for insert OWLlink's primitives for
       manage Knowldege Bases.
    */
    //@{

    /**
       Insert a "CreateKB" OWLlink primitive. After that, set the
       actual_kb to the given URI.

       @param $ontologyIRI An IRI for the current ontology. The name or the URI of the KB.
       @param $prefixes An Array of namespaces and IRIs for the ontology.
     */
    public function insert_create_kb($ontologyIRI, $prefixes = []){
        $this->content->startElement("CreateKB");

        if (!empty($ontologyIRI)){
          $this->content->writeAttribute("kb",$ontologyIRI["value"]);
          $this->actual_kb = $ontologyIRI["value"];
        } else {
          $this->content->writeAttribute("kb","http://crowd.fi.uncoma.edu.ar/kb1/");
          $this->actual_kb = "http://crowd.fi.uncoma.edu.ar/kb1/";
          $ontologyIRI = ["prefix" => "crowd", "value" => $this->actual_kb];
        }

        array_push($prefixes, $ontologyIRI);

        // Insert default prefixes for OWLlink into an array of prefixes
        foreach ($this->default_prefixes as $pref){
          if (!in_array($pref, $prefixes)){
            array_push($prefixes, $pref);
          }
        }

        $this->insert_prefix($prefixes);

        $this->content->endElement();
        $this->current_prefixes = $prefixes;
    }

    /**
       Insert a "ReleaseKB" OWLlink primitive.

       If the URI corresponds to the actual_kb one, set it to null.

       @param uri String (Optional). The name or the URI of the
       database to release. If not given, use the actual_kb one.
     */
    public function insert_release_kb($uri=null){
        if ($uri == null){
            $uri = $this->actual_kb;
        }

        $this->content->startElement("ReleaseKB");
        $this->content->writeAttribute("kb", $uri["value"]);
        $this->content->endElement();

        // $uri can be given by parameter or setted by this methods
        // when $uri is null (or not given).
        // Whenever be the case, it should be checked if it is the
        // same as actual_kb.
        if ($uri == $this->actual_kb) {
            $this->actual_kb = null;
        }

    }

    ///@}
    // KB Management.

    /**
       @name Tell

       Messages for adding Tell queries into the OWLlink document.
    */
    ///@{

    public function get_in_tell(){
        return $this->in_tell;
    }

    public function set_in_tell($bool = false){
       return $this->in_tell = $bool;
    }

    public function get_firstElementKB(){
      return $this->firstKBElement;
    }

    /**
       Open e Tell query.

       The KB is setted according to actual_kb.

       Example:

       @code{.php}
       $owllink_document->start_tell();
       $owllink_document->insert_concept("Person");
       $owllink_document->insert_concept("OtherPerson");
       $owllink_document->end_tell();
       @endcode
     */
    public function start_tell(){
        $this->content->startElement("Tell");
        $this->content->writeAttribute("kb", $this->actual_kb);
        $this->in_tell = true;
    }

    public function end_tell(){
        if ($this->in_tell){
            $this->content->endElement();
            $this->in_tell = false;
            $this->in_queries = true;
        }
    }


    /**
       Insert a DL subclass-of operator.

       Abbreviated IRIs are recognized automatically.

       @param child_class A String with the child's name class.
       @param father_class Same as $child_class parameter but for
       the $father_class.
       @param child_abbrev If true, force the abbreviated IRI for the
       child class; if false, force the (not abbreviated) IRI; if
       null check it automatically.
       @param father_abbrev same as $child_abbrev but for the
       $father_class.
     */
    public function insert_subclassof($child_class, $father_class, $child_abbrev=false, $father_abbrev=false){
        if (! $this->in_tell){
            return false;
        }
        $this->content->startElement("owl:SubClassOf");
        $this->insert_class($child_class, $child_abbrev);
        $this->insert_class($father_class, $father_abbrev);
        $this->content->endElement();
    }

    /**
       Insert a DL subobjectproperty-of operator.

       Abbreviated IRIs are recognized automatically.

       @param child_class A String with the child's name class.
       @param father_class Same as $child_class parameter but for
       the $father_class.
       @param child_abbrev If true, force the abbreviated IRI for the
       child class; if false, force the (not abbreviated) IRI; if
       null check it automatically.
       @param father_abbrev same as $child_abbrev but for the
       $father_class.
     */
    public function insert_subobjectpropertyof($child_op, $father_op){
        if (! $this->in_tell){
            return false;
        }
        $this->content->startElement("owl:SubObjectPropertyOf");
        $this->insert_objectproperty($child_op);
        $this->insert_objectproperty($father_op);
        $this->content->endElement();
    }

    /**
       Insert a DL subdataproperty-of operator.

       Abbreviated IRIs are recognized automatically.

       @param child_class A String with the child's name class.
       @param father_class Same as $child_class parameter but for
       the $father_class.
       @param child_abbrev If true, force the abbreviated IRI for the
       child class; if false, force the (not abbreviated) IRI; if
       null check it automatically.
       @param father_abbrev same as $child_abbrev but for the
       $father_class.
     */
    public function insert_subdatapropertyof($child_dp, $father_dp){
        if (! $this->in_tell){
            return false;
        }
        $this->content->startElement("owl:SubDataPropertyOf");
        $this->insert_dataproperty($child_dp);
        $this->insert_dataproperty($father_dp);
        $this->content->endElement();
    }



    protected function prefix_exists($prefix){
      foreach ($this->current_prefixes as $p){
          if (strcmp($p["prefix"], $prefix) == 0){
            $iri = $p["value"];
            return $iri;
          }
      }
      $iri = null;
      return $iri;
    }


    protected function check_prefixes($name){
        $dot_pos = stripos($name, ':');

        if ($dot_pos !== false){
          $prefix = mb_substr($name, 0, $dot_pos);
          $iri = $this->prefix_exists($prefix);

          if ($iri != null){
            return $iri;
          }
          else {
            return null;
          }
        }
    }

    /**
       Check if this IRI has a namespace, (i.e.: is an
       abbreviated IRI).

       Like in "owl:Thing" which its namespace is "owl" here.

       @param name a String with the IRI.
       @return True if the name has an XML Namespace. False otherwise.
    */
    protected function name_has_namespace($name){
        $ns_regexp = '/([a-zA-Z0-9])+\:([a-zA-Z0-9])+/';        // Namespace Regexp.

        if (preg_match($ns_regexp, $name) > 0){
            return true;
        }else{
            return false;
        }
    }

    /**
       Check if class names are full-expanded (i.e.: "http://crowd.fi.uncoma.edu.ar/Class").

       @param name a String with the IRI.
       @return True if the name is full expanded. False otherwise.
    */
    protected function name_full_expanded($name){
        $ns_regexp = '/^(http:\/\/([a-zA-Z0-9\/\.])+)/';        // Namespace Regexp.

        if (preg_match($ns_regexp, $name) > 0){
            return true;
        }else{
            return false;
        }
    }

    /**
    This function returns a short name for a OWL 2 entity removing prefix expansions
    */

    protected function remove_prefixExpansion($fullname){
      $hash_pos = stripos($fullname, '#');  //looking for hash to remove prefix

      if ($hash_pos !== false){
        $short_name = mb_substr($fullname, $hash_pos + 1);
      } else {
        $slash_pos = strrpos($fullname, '/'); //looking for the latest slash to remove prefix

        if ($slash_pos !== false){
          $short_name = mb_substr($fullname, $slash_pos + 1);
        }
        else{
          $dot_pos = strrpos($fullname, ':'); //looking for the latest : to remove prefix

          if ($dot_pos !== false){
            $short_name = mb_substr($fullname, $dot_pos + 1);
          }elseif ((!strcmp($fullname, "") == 0)) {
          $short_name = $fullname; // if value does not have an expanded prefix
          }
        }
      }
      return $short_name;
    }

    /**
       Add a class DL element.

       Abbreviated IRI's are recognized automatically by name_has_namespace() function.
      var_dump($prefixes);
       @note crowd expands every name when ontology is written in OWLlink.
       if name does not contain prefixes nor IRIs, crowd appends its default or given IRI base.
       if name is abbreviated, crowd checks that such prefix had been declarated and expands the name with the respective IRI.
       if name is already expanded, crowd uses this full name.

       @param name String the name or IRI of the new concept.
       @param is_abbreviated Boolean (Optional) force that the given IRI is or is not an abreviated like <tt>owl:class</tt>.
     */
    public function insert_class($name, $is_abbreviated=null){
        if ((! $this->in_tell) && (! $this->in_queries)){
            return false;
        }

        if ($is_abbreviated == null){
            $has_namespace = $this->name_has_namespace($name);

            if ($has_namespace){
              $is_abbreviated = $this->check_prefixes($name);
            }
            $is_fullexpanded = $this->name_full_expanded($name);
        }

        $this->content->startElement("owl:Class");

        if ($is_abbreviated != null){
            $short_name = $this->remove_prefixExpansion($name);
            $this->content->writeAttribute("IRI", $is_abbreviated.$short_name);
        } elseif ($is_fullexpanded){
              $this->content->writeAttribute("IRI", $name);
          }
          else{
            $this->content->writeAttribute("IRI", $this->actual_kb.$name);
          }

        $this->content->endElement();
    }

    public function insert_class_declaration($class){
        $this->insert_subclassof($class, "owl:Thing");
    }

    public function insert_objectproperty_declaration($objprop){
        $this->insert_subobjectpropertyof($objprop, "owl:topObjectProperty");
    }

    public function insert_dataproperty_declaration($dprop){
        $this->insert_subdatapropertyof($dprop, "owl:topDataProperty");
    }

    public function insert_dataproperty($name, $is_abbreviated=null){
      if ((! $this->in_tell) && (! $this->in_queries)){
          // We're not in tell mode!!!
          return false;
      }

      if ($is_abbreviated == null){
          $has_namespace = $this->name_has_namespace($name);

          if ($has_namespace){
            $is_abbreviated = $this->check_prefixes($name);
          }
          $is_fullexpanded = $this->name_full_expanded($name);
      }

      $this->content->startElement("owl:DataProperty");

      if ($is_abbreviated != null){
          $short_name = $this->remove_prefixExpansion($name);
          $this->content->writeAttribute("IRI", $is_abbreviated.$short_name);
      }elseif ($is_fullexpanded){
//          $short_name = $this->remove_prefixExpansion($name);
          $this->content->writeAttribute("IRI", $name);
        }
        else{
          $this->content->writeAttribute("IRI", $this->actual_kb.$name);
        }

      $this->content->endElement();
    }

    public function insert_objectproperty($name, $is_abbreviated=null){
      if ((! $this->in_tell) && (! $this->in_queries)){
          // We're not in tell mode!!!
          return false;
      }

      if ($is_abbreviated == null){
          $has_namespace = $this->name_has_namespace($name);

          if ($has_namespace){
            $is_abbreviated = $this->check_prefixes($name);
          }
          $is_fullexpanded = $this->name_full_expanded($name);
      }

      $this->content->startElement("owl:ObjectProperty");

      if ($is_abbreviated != null){
          $short_name = $this->remove_prefixExpansion($name);
          $this->content->writeAttribute("IRI", $is_abbreviated.$short_name);
      }elseif ($is_fullexpanded){
//          $short_name = $this->remove_prefixExpansion($name);
          $this->content->writeAttribute("IRI", $name);
        }
        else{
          $this->content->writeAttribute("IRI", $this->actual_kb.$name);
        }

      $this->content->endElement();
    }

    public function begin_inverseof(){
        $this->content->startElement("owl:ObjectInverseOf");
    }

    public function end_inverseof(){
        $this->content->EndElement();
    }

    public function begin_subclassof(){
        $this->content->startElement("owl:SubClassOf");
    }

    public function end_subclassof(){
        $this->content->EndElement();
    }

    public function begin_intersectionof(){
        $this->content->startElement("owl:ObjectIntersectionOf");
    }

    public function end_intersectionof(){
        $this->content->EndElement();
    }

    public function begin_unionof(){
        $this->content->startElement("owl:ObjectUnionOf");
    }
    public function end_unionof(){
        $this->content->EndElement();
    }

    public function begin_complementof(){
        $this->content->startElement("owl:ObjectComplementOf");
    }
    public function end_complementof(){
        $this->content->EndElement();
    }

    public function begin_somevaluesfrom(){
        $this->content->startElement("owl:ObjectSomeValuesFrom");
    }
    public function end_somevaluesfrom(){
        $this->content->EndElement();
    }

    public function begin_allvaluesfrom(){
        $this->content->startElement("owl:ObjectAllValuesFrom");
    }
    public function end_allvaluesfrom(){
        $this->content->EndElement();
    }

    public function begin_mincardinality($cardinality){
        $this->content->startElement("owl:ObjectMinCardinality");
        $this->content->writeAttribute("cardinality", $cardinality);
    }
    public function end_mincardinality(){
        $this->content->EndElement();
    }
    public function begin_maxcardinality($cardinality){
        $this->content->startElement("owl:ObjectMaxCardinality");
        $this->content->writeAttribute("cardinality", $cardinality);
    }
    public function end_maxcardinality(){
        $this->content->EndElement();
    }

	  public function begin_objectpropertydomain(){
		    $this->content->startElement("owl:ObjectPropertyDomain");
	  }

   	public function end_objectpropertydomain(){
        $this->content->EndElement();
	  }

	  public function begin_objectpropertyrange(){
		    $this->content->startElement("owl:ObjectPropertyRange");
	  }

   	public function end_objectpropertyrange(){
        $this->content->EndElement();
	  }

	  public function begin_equivalentclasses(){
		    $this->content->startElement("owl:EquivalentClasses");
	  }

   	public function end_equivalentclasses(){
        $this->content->EndElement();
	  }

    public function begin_disjointclasses(){
      $this->content->startElement("owl:DisjointClasses");
    }

     public function end_disjointclasses(){
        $this->content->EndElement();
     }
    ///@}
    // Tell group.

    // DataProperties

    # Min and Max Cardinalities for DataProperties
    #
    public function begin_mincardinality_dataproperty($cardinality){
        $this->content->startElement("owl:DataMinCardinality");
        $this->content->writeAttribute("cardinality", $cardinality);
    }
    public function end_mincardinality_dataproperty(){
        $this->content->EndElement();
    }

    public function begin_maxcardinality_dataproperty($cardinality){
        $this->content->startElement("owl:DataMaxCardinality");
        $this->content->writeAttribute("cardinality", $cardinality);
    }
    public function end_maxcardinality_dataproperty(){
        $this->content->EndElement();
    }

    public function begin_somevaluesfrom_dataproperty(){
        $this->content->startElement("owl:DataSomeValuesFrom");
    }
    public function end_somevaluesfrom_dataproperty(){
        $this->content->EndElement();
    }

    public function begin_allvaluesfrom_dataproperty(){
        $this->content->startElement("owl:DataAllValuesFrom");
    }
    public function end_allvaluesfrom_dataproperty(){
        $this->content->EndElement();
    }

    public function begin_datapropertydomain(){
		    $this->content->startElement("owl:DataPropertyDomain");
	  }

   	public function end_datapropertydomain(){
        $this->content->EndElement();
	  }

	  public function begin_datapropertyrange(){
		    $this->content->startElement("owl:DataPropertyRange");
	  }

   	public function end_datapropertyrange(){
        $this->content->EndElement();
	  }

    public function begin_intersectionof_dataproperty(){
        $this->content->startElement("owl:DataIntersectionOf");
    }

    public function end_intersectionof_dataproperty(){
        $this->content->EndElement();
    }

    public function begin_unionof_dataproperty(){
        $this->content->startElement("owl:DataUnionOf");
    }
    public function end_unionof_dataproperty(){
        $this->content->EndElement();
    }

    public function begin_complementof_dataproperty(){
        $this->content->startElement("owl:DataComplementOf");
    }
    public function end_complementof_dataproperty(){
        $this->content->EndElement();
    }

    public function insert_datatype($name, $is_abbreviated=null){
      if ((! $this->in_tell) && (! $this->in_queries)){
          // We're not in tell mode!!!
          return false;
      }

      if ($is_abbreviated == null){
          $has_namespace = $this->name_has_namespace($name);

          if ($has_namespace){
            $is_abbreviated = $this->check_prefixes($name);
          }
          $is_fullexpanded = $this->name_full_expanded($name);
      }

      $this->content->startElement("owl:Datatype");

      if ($is_abbreviated != null){
          $short_name = $this->remove_prefixExpansion($name);
          $this->content->writeAttribute("IRI", $is_abbreviated.$short_name);
      }elseif ($is_fullexpanded){
//          $short_name = $this->remove_prefixExpansion($name);
          $this->content->writeAttribute("IRI", $name);
        }
        else{
          $this->content->writeAttribute("IRI", $this->actual_kb.$name);
        }

      $this->content->endElement();
    }

    /**
       @name Ask

       Messages for the Ask section.
     */
    ///@{

    public function insert_satisfiable(){
        $this->content->startElement("IsKBSatisfiable");
        $this->content->writeAttribute("kb", $this->actual_kb);
        $this->content->endElement();
    }

    public function insert_getPrefixes(){
        $this->content->startElement("GetPrefixes");
        $this->content->writeAttribute("kb", $this->actual_kb);
        $this->content->endElement();
    }

    public function insert_satisfiable_class($classname){
        $this->content->startElement("IsClassSatisfiable");
        $this->content->writeAttribute("kb", $this->actual_kb);

        $this->insert_class($classname);

        $this->content->endElement();
    }

    /**
       Insert a query denominated IsObjectPropertySatisfiable for all the object properties in the array.

       OWLlink query:

       <IsObjectPropertySatisfiable kb="">
   	     <owl:ObjectProperty IRI="r2"/>
       </IsObjectPropertySatisfiable>

       @param $array An array of Strings with objectspropnames.
       @note not supported by konclude reasoner
     */

    public function insert_satisfiable_objectProperty($opname){
        $this->content->startElement("IsObjectPropertySatisfiable");
        $this->content->writeAttribute("kb", $this->actual_kb);

        $this->insert_objectproperty($opname);

        $this->content->endElement();
    }

    /**
       Insert a query denominated IsDataPropertySatisfiable for all the data properties in the array.

       OWLlink query:

       <IsDataPropertySatisfiable kb="">
   	     <owl:DataProperty IRI="r2"/>
       </IsDataPropertySatisfiable>

       @param $array An array of Strings with data property names.
       @note not supported by konclude reasoner
     */

    public function insert_satisfiable_dataProperty($dpname){
        $this->content->startElement("IsDataPropertySatisfiable");
        $this->content->writeAttribute("kb", $this->actual_kb);

        $this->insert_dataproperty($dpname);

        $this->content->endElement();
    }

    /**
       Insert an ASK query denominated IsEntailed for all the classes in the array.

       OWLlink query:

       <IsEntailed kb="http://localhost/kb1">
         <owl:EquivalentClasses>
          <owl:Class IRI="PhoneCall"/>
          <owl:Class IRI="MobileCall"/>
        </owl:EquivalentClasses>
       </IsEntailed>

       @param $array An array of Strings with classnames.
     */
    public function insert_isEntailed_query($array){
        $this->content->startElement("IsEntailed");
        $this->content->writeAttribute("kb", $this->actual_kb);

        $this->content->startelement("owl:EquivalentClasses");
        foreach ($array as $classname){
            $this->content->startElement("owl:Class");
            $this->content->writeAttribute("IRI", $classname);
            $this->content->endElement();

        }
        $this->content->endElement();

        $this->content->endElement();
    }


    /**
       Insert an ASK query denominated IsEntailedDirect for all the classes in the array.

       OWLlink query:

       <IsEntailedDirect kb="http://localhost/kb1">
         <owl:SubClassOf>
          <owl:Class IRI="PhoneCall"/>
          <owl:Class IRI="MobileCall"/>
        </owl:SubClassOf>
       </IsEntailedDirect>

       @param $array An array of Strings with classnames.
     */
    public function insert_isEntailedDirectSubClasses_query($array){
        $this->content->startElement("IsEntailedDirect");
        $this->content->writeAttribute("kb", $this->actual_kb);

        $this->content->startelement("owl:SubClassOf");
        foreach ($array as $classname){
            $this->content->startElement("owl:Class");
            $this->content->writeAttribute("IRI", $classname);
            $this->content->endElement();
        }
        $this->content->endElement();

        $this->content->endElement();
    }


    /**
       Insert an ASK query denominated IsEntailedDirect for all the classes in the array.

       OWLlink query:

       <IsEntailedDirect kb="http://localhost/kb1">
         <owl:DisjointClasses>
          <owl:Class IRI="PhoneCall"/>
          <owl:Class IRI="MobileCall"/>
        </owl:DisjointClasses>
       </IsEntailedDirect>

       @param $array An array of Strings with classnames.
     */
    public function insert_isEntailedDirectDisjointClasses_query($array){
        $this->content->startElement("IsEntailedDirect");
        $this->content->writeAttribute("kb", $this->actual_kb);

        $this->content->startelement("owl:DisjointClasses");
        foreach ($array as $classname){
            $this->content->startElement("owl:Class");
            $this->content->writeAttribute("IRI", $classname);
            $this->content->endElement();
        }
        $this->content->endElement();

        $this->content->endElement();
    }


    /**
       Insert a query denominated GetAllObjectProperties for the current kb.

      OWLlink query: <GetAllObjectProperties kb="http://localhost/kb1"/>

     */
    public function insert_get_all_object_properties_query(){
        $this->content->startElement("GetAllObjectProperties");
        $this->content->writeAttribute("kb", $this->actual_kb);

        $this->content->endElement();
    }

    /**
       Insert a query denominated GetAllClasses for the current kb.

      OWLlink query: <GetAllClasses kb="http://localhost/kb1"/>

     */
    public function insert_get_all_classes_query(){
        $this->content->startElement("GetAllClasses");
        $this->content->writeAttribute("kb", $this->actual_kb);

        $this->content->endElement();
    }


    /**
       Insert a query denominated GetSubClasses for all the classes in the array.

       OWLlink query:

       <GetSubClasses kb="http://localhost/kb1">
          <owl:Class IRI="PhoneCall"/>
       </GetSubClasses>

       @param $classname an Object Type.
     */
    public function insert_get_subClasses_query($classname){
        $this->content->startElement("GetSubClasses");
        $this->content->writeAttribute("kb", $this->actual_kb);

        $this->content->startElement("owl:Class");
        $this->content->writeAttribute("IRI", $classname);
        $this->content->endElement();

        $this->content->endElement();
    }


    /**
       Insert a query denominated GetSuperClasses for all the classes in the array.

       OWLlink query:

       <GetSuperClasses kb="http://localhost/kb1">
          <owl:Class IRI="PhoneCall"/>
       </GetSuperClasses>

       @param $classname an Object Type.
     */
    public function insert_get_superClasses_query($classname){
        $this->content->startElement("GetSuperClasses");
        $this->content->writeAttribute("kb", $this->actual_kb);

        $this->content->startElement("owl:Class");
        $this->content->writeAttribute("IRI", $classname);
        $this->content->endElement();

        $this->content->endElement();
    }

    /**
       Insert a query denominated GetEquivalentClasses for all the classes in the array.

       OWLlink query:

       <GetEquivalentClasses kb="http://localhost/kb1">
     	  <owl:Class IRI="Person"/>
       </GetEquivalentClasses>

       @param $array An array of Strings with classnames.
     */
    public function insert_get_equivalentClasses_query($classname){
        $this->content->startElement("GetEquivalentClasses");
        $this->content->writeAttribute("kb", $this->actual_kb);

        $this->insert_class($classname);

        $this->content->endElement();
    }


    /**
       Insert a query denominated GetSubClassHierarchy for the current kb.

      OWLlink query: <GetSubClassHierarchy kb="http://localhost/kb1"/>

     */
    public function insert_getSubClassHierarchy_query(){
        $this->content->startElement("GetSubClassHierarchy");
        $this->content->writeAttribute("kb", $this->actual_kb);

        $this->content->endElement();
    }

    /**
       Insert a query denominated GetObjectPropertiesHierarchy for the current kb.

      OWLlink query: <GetSubObjectPropertyHierarchy kb="http://localhost/kb1"/>

     */
    public function insert_getSubObjectPropertyHierarchy_query(){
        $this->content->startElement("GetSubObjectPropertyHierarchy");
        $this->content->writeAttribute("kb", $this->actual_kb);

        $this->content->endElement();
    }


    /**
       Insert a query denominated GetDisjointClasses for all the classes in the array.

       OWLlink query:

       <GetDisjointClasses kb="">
           <owl:Class IRI="Person"/>
       </GetDisjointClasses>

       @param $classname an Object Type.
       @note not supported by konclude reasoner
     */
    public function insert_get_disjointClasses_query($classname){
        $this->content->startElement("GetDisjointClasses");
        $this->content->writeAttribute("kb", $this->actual_kb);

        $this->insert_class($classname);

        $this->content->endElement();
    }


    public function insert_owllink($text){
        $this->content->writeRaw($text);

    }

    public function to_string(){
        $str = $this->content->outputMemory();
        return $str;
    }


}

?>
