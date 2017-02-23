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

		// we will now check for arguments validity]
		$common->checkArguments($argv);

		// shortcuts for array access
		$iFile = $files["inputFile"];
		$oFile = $files["outputFile"];
		$fFile = $files["formatFile"];
		// to speed up the process, we will call

		$parser->parseFormatFile($common, $fFile);




		// script will insert <br /> tag at end of each line, the function itself checks the tag
		$editor->insertNewLine($common, $oFile);

		//var_dump($files);
		echo "\nEnd of the script\n";
		//echo $files["formatFile"]."\n";
		//var_dump($flags);

	return 0;

?>