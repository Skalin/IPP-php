<?php
/**
 * Created by PhpStorm.
 * User: Dominik    
 * Date: 16.02.2017
 * Time: 17:38
*/

class Parser {

	

	/*
	 *
	 *
	 */
	public function arrayFiller($regex, $format) {
		if ($format == "bold") {

		}
	}

	/**
	 *
	 */
	private function regexChecker($string) {
	}


	/**
	 *
	 *
	 */
	protected function parseRegex() {
		echo "picus";
	}

	/**
	 *
	 *
	 */
	private function parseTag() {

	}

	/**
	 *
	 */
	public function parseFormatFile(Common $common, $formatFile) {
		global $files;

		if ($formatFile == "none" && !(file_exists($files["formatFile"]))) {
			//we will return input file, when the format file is not included
			echo "parsing not done, we will not format the file and return the original file\n";
		} else {
			// now the real fun begins..
			$regexF = false;
			$tagF = false;
			// tags show us which part of parsing we are doing (checking for tag, regex..)
			$regex = "";
			$tag = "";
			$file = file($formatFile);

			foreach ($file as $line) {
				$formats = preg_split("/( ,|\s|\t)+/", $line);
				array_pop($formats);
				foreach ($formats as $format) {
					echo $format."\n";

				}
			}

			//regexChecker($string);

		}

	}
}