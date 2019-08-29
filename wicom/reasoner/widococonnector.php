<?php
/*

   Copyright 2018 GILIA

   Author: GILIA

   widococonnector.php

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

namespace Wicom\Reasoner;

load("connector.php");
load("config.php", "../../config/");

use Wicom\Reasoner\Connector;
use DOMDocument;
use SimpleXMLElement;

class WidocoConnector extends Connector{


    //TODO: Change PROGRAM_CMD and FILES_PATH into configuration variables.

    /**
       Widoco command executes with all its parameters.
       Widoco generates the documentation for an OWL 2 ontology.
     */
//    const PROGRAM_CMD = "Racer";
//    const PROGRAM_PARAMS = "-- -silent -owllink ";

    const PROGRAM_CMD = "widoco.jar";
    const PROGRAM_PARAMS_ONTO = " -ontFile ";
    const PROGRAM_PARAMS_FOLDER = " -outFolder ";
    const PROGRAM_PARAMS_REWRITE = " -rewriteAll ";
    const PROGRAM_PARAMS_METADATA = " -confFile ";

    /**
       Execute Widoco with the given $document as input.
       Widoco needs to be invoked from a writable folder in order to create temporal path
       so that crowd should move to the current folder before invoking Widoco
       "cd $temporal_path"
     */
    function run($input_string){
        $temporal_path = $GLOBALS['config']['temporal_path'];
        $widoco_path = $GLOBALS['config']['widoco_path'];
        $public_html = $GLOBALS['config']['public_html'];

        $uuid = uniqid();

        $temporal_path = realpath($temporal_path) . "/";
        $file_path = $temporal_path . $uuid . "crowd-doc.owl";
        $widoco_path .= WidocoConnector::PROGRAM_CMD;
        $outFolder = $public_html . $uuid . "crowdOntoDoc";
        $confFile = $temporal_path . "crowdWidoco.properties";
        $commandline = "java -jar " . $widoco_path . WidocoConnector::PROGRAM_PARAMS_ONTO . $file_path . WidocoConnector::PROGRAM_PARAMS_FOLDER . $outFolder . WidocoConnector::PROGRAM_PARAMS_REWRITE . WidocoConnector::PROGRAM_PARAMS_METADATA . $confFile;

        $owl2_file = fopen($file_path, "w");

        if (! $owl2_file) {
            throw new \Exception("Temporal file couldn't be opened for writing...
Does there exist '$file_path' file?");
        }

        // Generate OWL file to be sent to Widoco
        $owl_input = new SimpleXMLElement($input_string);
        $owl = $owl_input->asXML();

        fwrite($owl2_file, $owl);
        fclose($owl2_file);

        $final_commandline = "cd ".$temporal_path.";".$commandline;
        exec($final_commandline);

        $linktodoc = "/" . $uuid . "crowdOntoDoc";
        
        array_push($this->col_answers, [$linktodoc]);

}


    /**
       Check for program and input file existance and proper permissions.

       @return true always
       @exception Exception with proper message if any problem is founded.
    */
    function check_files($temporal_path, $widoco_path, $file_path){
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

        if (!file_exists($widoco_path)){
            throw new \Exception("The Konclude program has not been founded...
You told me that '$widoco_path' is the Konclude program, is this right? check your 'web-src/config/config.php' configuration file.");
        }

        return true;
    }
}
?>
