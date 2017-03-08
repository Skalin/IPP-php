<?php
 	# xskala11 - Dominik Skala
	# IPP 2016/2017
	# Project ID: 06 - SYN in PHP

	require_once('./include/common.php');
	require_once('./include/parser.php');
	require_once('./include/editor.php');

	$common = new Common;
	$parser = new Parser;
	$editor = new Editor;
	$fileName = $argv[0];
	$argCount = count($argv);
	$flags = array("if" => false, "of" => false, "ff" => false, "br" => false);
	$files = array("inputFile" => "stdin", "outputFile" => "stdout", "formatFile" => "none");
		// we will now check for arguments validity and then we'll check for integrity of all files

        $common->checkArguments($argv);
		// to speed up the process, we will call
        // shortcuts for array access
        $iFile = $files["inputFile"];
        $oFile = $files["outputFile"];
        $fFile = $files["formatFile"];

		$format = $parser->parseFormatFile($common, $fFile);

        $input = $common->getFile($iFile, "if");
        $input = implode("", $input);
        $output = $editor->editInputFile($common, $input, $format);
		// script will insert <br /> tag at end of each line, the function itself checks the tag
		$output = $editor->insertNewLine($common, $output);

		if ($files["outputFile"] == "stdout") {
            $editor->writeToStdout($common, $output);
        } else {
		    $editor->writeToFile($common, $output, $oFile);
        }



		//var_dump($files);
        echo "-------------------------------------------------";
		echo "\nEnd of the script\n";
		//echo $files["formatFile"]."\n";
		//var_dump($flags);

	return 0;

?>