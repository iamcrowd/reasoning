<?php
/*

   Copyright 2017 Giménez, Christian

   Author: Giménez, Christian - Braun, Germán

   owldocument.php

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
class OWLDocument extends Document{
    protected $content = null;

    protected $owllink_text = "";

    protected $actual_kb = null;

    protected $current_prefixes = [];

    const default_ontologyIRI = [
        [
            'prefix' => 'crowd',
            'value' => "http://crowd.fi.uncoma.edu.ar/kb1#"
        ]
    ];
    
    protected $default_prefixes = [
	["prefix" => "rdf",
	 "value" => "http://www.w3.org/1999/02/22-rdf-syntax-ns#"],
	["prefix" => "rdfs",
	 "value" => "http://www.w3.org/2000/01/rdf-schema#"],
	["prefix" => "xsd",
	 "value" => "http://www.w3.org/2001/XMLSchema#"],
	["prefix" => "owl",
	 "value" => "http://www.w3.org/2002/07/owl#"]
    ];

    protected $default_header = [
	["attr" => "xmlns", "value" => "http://www.w3.org/2002/07/owl#"],
	["attr" => "xmlns:rdf", "value" => "http://www.w3.org/1999/02/22-rdf-syntax-ns#"],
	["attr" => "xmlns:xml", "value" => "http://www.w3.org/XML/1998/namespace"],
	["attr" => "xmlns:xsd", "value" => "http://www.w3.org/2001/XMLSchema#"],
	["attr" => "xmlns:rdfs", "value" => "http://www.w3.org/2000/01/rdf-schema#"],
    ];

    function __construct(){
        $this->content = new XMLWriter();
        $this->content->openMemory();
    }

    /**
       @name Starting and Ending the document
     */
    ///@{

    public function insert_startdocument(){
        $this->content->startDocument("1.0", "UTF-8");
    }

    public function set_actual_kb($kb_uri){
        $this->actual_kb = $kb_uri;
    }

    /**
       Insert the Ontology tag.

       This is the first tag on an OWL 2 document. It usually has got prefixes 
       and the ontology IRI.
       
       @param $ontologyIRI {Array} A list with the default URI. A value should 
         be `[['prefix' => 'crowd', 'value' => 'http://crowd.fi.uncoma.edu.ar/']]`
       @param $headerIRIs {array} (Optional) An array with elements like
       `['prefix' => "NAME", 'value' => "IRI"]`. If an empty array is passed
       default_header prefixes are used.
     */
    public function insert_ontology($ontologyIRI = null, $headerIRIs = []){
        $this->content->startElement("Ontology");

	if (empty($headerIRIs)){
	    $headerIRIs = $this->default_header;
	}

	if ($ontologyIRI == null){
	    $ontologyIRI = OWLDocument::default_ontologyIRI;
	}
        
        $ontologyIRI = $ontologyIRI[0]['value'];
	
	foreach ($headerIRIs as $header){
	    $this->content->writeAttribute($header["attr"], $header["value"]);
        }
	
	$this->content->writeAttribute("xml:base", $ontologyIRI);
        $this->content->writeAttribute("ontologyIRI", $ontologyIRI);

        $this->actual_kb = $ontologyIRI;
    }

    /**
       Start the document with default elements.

       Default elements are some initial tags. The `end_document()` must be 
       called after this method.

       @param $ontologyIRI {string} An IRI. It is the IRI which represent the 
       ontology.
       @param headerIRIs {array} An array of elements like 
       `['prefix' => "PREFIX", 'value' => "IRI"]`.
     */
    public function start_document($ontologyIRI = null, $headerIRIs = []){
        $this->insert_startdocument();
        $this->insert_ontology($ontologyIRI, $headerIRIs);
    }

    /**
       End the document.
       
       End some important tags. If the `start_document()` method has been 
       called, this one must be called too.
     */
    public function end_document(){
        $this->content->endElement();
    }

    ///@}
    // Starting and ending the document

    /**
       Change ontology prefixes.

       @param $prefixes {array} An array of prefixes. Its elements must be a 
       hash of `[ "prefix" => "NAME", "value"="AN IRI"]`.
     */
    public function set_ontology_prefixes($prefixes){
	// Insert default prefixes for OWL 2 into an array of prefixes
	foreach ($this->default_prefixes as $pref){
            if (!in_array($pref, $prefixes)){
		array_push($prefixes, $pref);
            }
	}

	$this->insert_prefix($prefixes);
	$this->current_prefixes = $prefixes;
    }

    /**
       Insert several Prefix tags.
     */
    public function insert_prefix($prefixes){
	foreach ($prefixes as $prefix){
            $this->content->startElement("Prefix");
            $this->content->writeAttribute("name", $prefix["prefix"]);
            $this->content->writeAttribute("IRI", $prefix["value"]);
            $this->content->endElement();
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

    public function insert_class_declaration($class){
        $this->content->startElement("Declaration");
        $this->insert_class($class);
        $this->content->endElement();
    }

    public function insert_objectproperty_declaration($objprop){
        $this->content->startElement("Declaration");
        $this->insert_objectproperty($objprop);
        $this->content->endElement();
    }

    public function insert_dataproperty_declaration($dprop){
        $this->content->startElement("Declaration");
        $this->insert_dataproperty($dprop);
        $this->content->endElement();
    }

    public function insert_subclassof($child_class, $father_class, $child_abbrev=false, $father_abbrev=false){
        $this->content->startElement("SubClassOf");
        $this->insert_class($child_class, $child_abbrev);
        $this->insert_class($father_class, $father_abbrev);
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

    public function insert_subobjectpropertyof($child_objprop, $father_objprop, $child_abbrev = false, $father_abbrev = false){
        $this->content->startElement("SubObjectPropertyOf");
        $this->insert_objectproperty($child_objprop, $child_abbrev);
        $this->insert_objectproperty($father_objprop, $father_abbrev);
        $this->content->endElement();
    }


    /**
       Add a class DL element.

       Abbreviated IRI's are recognized automatically by name_has_namespace() function.

       @note crowd does not expand class names when ontology is written in OWL 2.
       if name is abbreviated, crowd checks that such prefix had been declarated and expands the name with the respective IRI.
       if name is already expanded, crowd uses this full name.

       @param name String the name or IRI of the new concept.
       @param is_abbreviated Boolean (Optional) force that the given IRI is or is not an abreviated like <tt>owl:class</tt>.
     */
    public function insert_class($name, $is_abbreviated = null){

	if ($is_abbreviated == null){;
            $has_namespace = $this->name_has_namespace($name);

            if ($has_namespace){
		$is_abbreviated = $this->check_prefixes($name);
            }
            $is_fullexpanded = $this->name_full_expanded($name);
	}

	$this->content->startElement("Class");

	if ($is_abbreviated != null){
            $this->content->writeAttribute("abbreviatedIRI", $name);
	} elseif ($is_fullexpanded){
            $this->content->writeAttribute("IRI", $name);
        }
        else{
            $this->content->writeAttribute("IRI", $name);
        }

	$this->content->endElement();
    }

    public function insert_objectproperty($name, $is_abbreviated=null){
	if ($is_abbreviated == null){
            $has_namespace = $this->name_has_namespace($name);

            if ($has_namespace){
		$is_abbreviated = $this->check_prefixes($name);
            }
            $is_fullexpanded = $this->name_full_expanded($name);
	}

	$this->content->startElement("ObjectProperty");

	if ($is_abbreviated != null){
            $this->content->writeAttribute("abbreviatedIRI", $name);
	}elseif ($is_fullexpanded){
            $this->content->writeAttribute("IRI", $name);
        }
        else{
            $this->content->writeAttribute("IRI", $name);
        }

	$this->content->endElement();
    }

    public function insert_dataproperty($name, $is_abbreviated=null){
	if ($is_abbreviated == null){
            $has_namespace = $this->name_has_namespace($name);

            if ($has_namespace){
		$is_abbreviated = $this->check_prefixes($name);
            }
            $is_fullexpanded = $this->name_full_expanded($name);
	}

	$this->content->startElement("DataProperty");

	if ($is_abbreviated != null){
            $this->content->writeAttribute("abbreviatedIRI", $name);
	}elseif ($is_fullexpanded){
            $this->content->writeAttribute("IRI", $name);
        }
        else{
            $this->content->writeAttribute("IRI", $name);
        }

	$this->content->endElement();
    }


    public function insert_datatype($name, $is_abbreviated=null){
	if ($is_abbreviated == null){
            $has_namespace = $this->name_has_namespace($name);

            if ($has_namespace){
		$is_abbreviated = $this->check_prefixes($name);
            }
            $is_fullexpanded = $this->name_full_expanded($name);
	}

	$this->content->startElement("Datatype");

	if ($is_abbreviated != null){
            $this->content->writeAttribute("abbreviatedIRI", $name);
	}elseif ($is_fullexpanded){
            $this->content->writeAttribute("IRI", $name);
        }
        else{
            $this->content->writeAttribute("IRI", $name);
        }

	$this->content->endElement();
    }

    public function begin_inverseof(){
        $this->content->startElement("ObjectInverseOf");
    }
    public function end_inverseof(){
        $this->content->EndElement();
    }
    public function begin_subclassof(){
        $this->content->startElement("SubClassOf");
    }
    public function end_subclassof(){
        $this->content->EndElement();
    }
    public function begin_intersectionof(){
        $this->content->startElement("ObjectIntersectionOf");
    }
    public function end_intersectionof(){
        $this->content->EndElement();
    }

    public function begin_unionof(){
        $this->content->startElement("ObjectUnionOf");
    }
    public function end_unionof(){
        $this->content->EndElement();
    }

    public function begin_complementof(){
        $this->content->startElement("ObjectComplementOf");
    }
    public function end_complementof(){
        $this->content->EndElement();
    }

    public function begin_somevaluesfrom(){
        $this->content->startElement("ObjectSomeValuesFrom");
    }
    public function end_somevaluesfrom(){
        $this->content->EndElement();
    }

    public function begin_allvaluesfrom(){
        $this->content->startElement("ObjectAllValuesFrom");
    }
    public function end_allvaluesfrom(){
        $this->content->EndElement();
    }

    public function begin_mincardinality($cardinality){
        $this->content->startElement("ObjectMinCardinality");
        $this->content->writeAttribute("cardinality", $cardinality);
    }
    public function end_mincardinality(){
        $this->content->EndElement();
    }
    public function begin_maxcardinality($cardinality){
        $this->content->startElement("ObjectMaxCardinality");
        $this->content->writeAttribute("cardinality", $cardinality);
    }
    public function end_maxcardinality(){
        $this->content->EndElement();
    }

    public function begin_objectpropertydomain(){
	$this->content->startElement("ObjectPropertyDomain");
    }

    public function end_objectpropertydomain(){
        $this->content->EndElement();
    }

    public function begin_objectpropertyrange(){
	$this->content->startElement("ObjectPropertyRange");
    }

    public function end_objectpropertyrange(){
        $this->content->EndElement();
    }

    // DataProperties

    # Min and Max Cardinalities for DataProperties
    #
    public function begin_mincardinality_dataproperty($cardinality){
	$this->content->startElement("DataMinCardinality");
	$this->content->writeAttribute("cardinality", $cardinality);
    }
    public function end_mincardinality_dataproperty(){
	$this->content->EndElement();
    }

    public function begin_maxcardinality_dataproperty($cardinality){
	$this->content->startElement("DataMaxCardinality");
	$this->content->writeAttribute("cardinality", $cardinality);
    }
    public function end_maxcardinality_dataproperty(){
	$this->content->EndElement();
    }

    public function begin_somevaluesfrom_dataproperty(){
	$this->content->startElement("DataSomeValuesFrom");
    }
    public function end_somevaluesfrom_dataproperty(){
	$this->content->EndElement();
    }

    public function begin_allvaluesfrom_dataproperty(){
	$this->content->startElement("DataAllValuesFrom");
    }
    public function end_allvaluesfrom_dataproperty(){
	$this->content->EndElement();
    }

    public function begin_datapropertydomain(){
	$this->content->startElement("DataPropertyDomain");
    }

    public function end_datapropertydomain(){
	$this->content->EndElement();
    }

    public function begin_datapropertyrange(){
	$this->content->startElement("DataPropertyRange");
    }

    public function end_datapropertyrange(){
	$this->content->EndElement();
    }

    public function begin_equivalentclasses(){
	$this->content->startElement("EquivalentClasses");
    }

    public function end_equivalentclasses(){
        $this->content->EndElement();
    }

    public function begin_disjointclasses(){
	$this->content->startElement("DisjointClasses");
    }

    public function end_disjointclasses(){
        $this->content->EndElement();
    }

    /**
       Insert an ASK query denominated IsEntailedDirect for all the classes in the array.

       @param $array An array of Strings with classnames.
     */
    public function insert_equivalent_class_query($array){
        $this->content->startelement("EquivalentClasses");
        foreach ($array as $classname){
            $this->content->startElement("Class");
            $this->content->writeAttribute("IRI", $classname);
            $this->content->endElement();
        }
        $this->content->endElement();
    }

    public function to_string(){
        $str = $this->content->outputMemory();
        return $str;
    }

    public function insert_owl2($text){
        $this->content->writeRaw($text);

    }
}

?>
