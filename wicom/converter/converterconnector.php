<?php
/*

   Copyright 2021 GILIA

   Author: GILIA

   converterconnector.php

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

namespace Wicom\Converter;

load("connector.php", "../reasoner/");
load("config.php", "../../config/");

use Wicom\Reasoner\Connector;

class ConverterConnector extends Connector{

    /**
       The ont-converter command to execute with all its parameters.
       A simple command-line utility to convert any RDF graph to OWL2-DL ontology
       @see https://github.com/sszuev/ont-converter
     */
    const JAVA = "java -jar ";
    const PROGRAM_CMD = "ont-converter.jar ";
    const INPUT_PARAMS = " -i ";
    const OUTPUT_PARAMS = " -o ";
    const INPUT_SYNTAX_PARAMS = " -if ";
    const OUTPUT_SYNTAX_PARAMS = " -of ";
    const OWL_XML = "owl/xml";
    const RDF_XML = "rdf/xml";
    const TURTLE = "turtle";
    const JSON_LD = "jsonld";
    const NTRIPLES = "ntriples";
    const MANCHESTER = "manchestersyntax";
    const FUNCTIONAL = "functionalsyntax";

    function run($input_string){}

    /**
       Execute ont-converter with the given $document as input.
     */
    function run_converter($input_string, $syntax_in, $syntax_out){
        $temporal_path = $GLOBALS['config']['temporal_path'];
        $converter_path = $GLOBALS['config']['converter_path'];

        $uuid = uniqid();

        $tmp_realpath = realpath($temporal_path);

      	if (($tmp_realpath == FALSE) or (!is_writable($tmp_realpath)) ) {
      	    throw new \Exception(
      		      "Temporal path does not exists or is not  writeable. ".
      		      "Check if this path exists and is writeable: '$temporal_path'."
      	    );
      	}

      	$tmp_realpath .= '/';

      	$file_name_in = $uuid . "input-file.owl";
        $file_path_in = $tmp_realpath . $file_name_in;

        $file_name_out = $uuid . "output-file.owl";
        $file_path_out = $tmp_realpath . $file_name_out;

        $converter_path = ConverterConnector::JAVA . $converter_path . ConverterConnector::PROGRAM_CMD;

      //	if (! is_executable($converter_path)){
      //	    throw new \Exception(
      //		"The program is not executable. " .
      //		"Please, use chmod +x to make it executable.");
      //	}

        $commandline = $converter_path . " " . ConverterConnector::INPUT_PARAMS . $file_path_in .
        ConverterConnector::INPUT_SYNTAX_PARAMS . $syntax_in . ConverterConnector::OUTPUT_PARAMS . $file_path_out .
        ConverterConnector::OUTPUT_SYNTAX_PARAMS . $syntax_out;

        $owl_file_in = fopen($file_path_in, "w");

        if (! $owl_file_in){
                throw new \Exception(
      		           "Temporal file couldn't be opened for " .
      		           "writing... \n Is the path '$file_path_in' correct?");
        }

        fwrite($owl_file_in, $input_string);
        fclose($owl_file_in);

        exec($commandline, $answer);

        $owl_file_out = fopen($file_path_out, "r");

        if (! $owl_file_out) {
            throw new \Exception(
              "Temporal file couldn't be opened for " .
              " writing... Does there exist '$file_path_out' file?");
        }

        $answer = fread($owl_file_out, filesize($file_path_out));

        fclose($owl_file_out);

        unlink($file_path_in);
        unlink($file_path_out);

        array_push($this->col_answers, $answer);
    }

    /**
       Check for program and input file existance and proper permissions.

       @return true always
       @exception Exception with proper message if any problem is founded.
    */
    function check_files($temporal_path, $converter_path, $file_path){
        if (! is_dir($temporal_path)){
            throw new \Exception("Temporal path desn't exists!
Are you sure about this path?
temporal_path = \"$temporal_path\"");
        }

        if (!file_exists($file_path)){
            throw new \Exception("Temporal file doesn't exists, please create one at '$file_path'.");
        }

        if (!is_readable($file_path)){
            throw new \Exception("Temporal file cannot be readed.
Please set the write and read permissions for '$file_path'");
        }

        if (file_exists($file_path) and !is_writable($file_path)){
            throw new \Exception("Temporal file is not writable, please change the permissions.
Check the permissions on '${file_path}'.");
        }

        if (!file_exists($racer_path)){
            throw new \Exception("The Racer program has not been founded...
You told me that '$racer_path' is the Racer program, is this right? check your 'web-src/config/config.php' configuration file.");
        }

        if (!is_executable($racer_path)){
            throw new \Exception("The Racer program is not executable...
Is the path '$racer_path' right? Is the permissions setted properly?");
        }

        return true;
    }
}

?>
