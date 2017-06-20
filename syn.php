<?php

/**
 * Project: SYN 06 PHP
 * User: Dominik Skála (xskala11)
 *
 * Date: 19.02.2017
 */

	require_once('./include/common.php');
	require_once('./include/parser.php');
	require_once('./include/editor.php');

	$common = new Common;
	$parser = new Parser;
	$editor = new Editor;
	$fileName = $argv[0];
	$flags = array("if" => false, "of" => false, "ff" => false, "br" => false);
	$files = array("inputFile" => "stdin", "outputFile" => "stdout", "formatFile" => "none");

	// we will now check for arguments validity and then we'll check for integrity of all files
	$common->checkArguments($argv);

	// shortcuts for array access
	$iFile = $files["inputFile"];
	$oFile = $files["outputFile"];
	$fFile = $files["formatFile"];

	// let's parse it!
	$format = $parser->parseFormatFile($common, $fFile);

	// now we're deciding whether to read from stdin or from input file passed in arguments
	if (!$flags["if"] && $files["inputFile"] == "stdin") {
		$input = $editor->readFromStdinToInput();
	} else {
		$input = $editor->readFromFileToInput($common, $iFile);
	}


	// $input is being formatted into $output using formatting file
	if ($flags["ff"] != false && "formatFile" != "none") {
		$output = $editor->editInputFile($input, $format);
	} else {
		$output = $input;
	}
	// script will insert <br /> tag at end of each line, the function itself checks the tag
	$output = $editor->insertNewLine($flags, $output);


	// we will now decide whether to print to stdout or to file passed in arguments
	if ($files["outputFile"] == "stdout" && !$flags["of"]) {
		$editor->writeToStdout($output);
	} else {
		$editor->writeToFile($common, $output, $oFile);
	}

	return 0;

?>
