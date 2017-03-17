<?php

/**
 * Project: SYN 06 PHP
 * User: Dominik Skála (xskala11)
 *
 * Date: 19.02.2017
 */
class Editor
{

	/**
	 * Function checks for --br tag, if it is passed, the function will insert </br> tag at end of each line
	 *
	 * @param string $file file that was converted into string
	 * @return string $file Formatted file that is modified if br tag is passed, otherwise returns unchanged file, returned as string
	 */
	public function insertNewLine($flags, $file) {

		if ($flags["br"]) {
			return $br = implode("<br />\n", $br = explode("\n", $file));
		}

		return $file;
	}

	/*
	 * Function loads the input file in format of string and array of formats in $formatting, after that, all of the matches for all regular expressions are found and saved into array, after that, concatenation of HTML tag from left side and right side is done
	 *
	 * @param string $file File previously converted into string
	 * @param array $formatting Array of all regular expressions and html tags
	 * @return string $output If formatting was done, functions returns modified $file string, otherwise the $file is returned directly from passed argument
	 */
	public function editInputFile($file, $formatting) {

		foreach ($formatting as $formKey => $form) {
			$matches = array();
			if ((preg_match_all("/" . $form[0] . "/", $file, $matches[$formKey], PREG_OFFSET_CAPTURE)) >= 0) {

				if (count($matches[$formKey][0]) > 0) {
					foreach ($matches[$formKey] as $occur) {
						$i = 0;
						while (count($occur) > $i) {
							$item = $occur[$i][0];
							$offset = $occur[$i][1];
							$key = $offset;
							if (!isset($leftTags[$key])) {
								$leftTags[$key] = array($item,$form[1]);
							} else {
								array_push($leftTags[$key],$form[1]);
							}

							if (!isset($rightTags[$key])) {
								$rightTags[$key] = array($item,$form[2]);
							} else {
								array_push($rightTags[$key],$form[2]);
							}
							$i++;
						}
					}
				}
			}
		}

		$out = "";

		// if everything was created properly
		if (isset($leftTags) && isset($rightTags)) {
			// we will sort left array by key indexes
			ksort($leftTags);

			// and we will concatenate left pair of HTML tags to the input
			$prevIndex = 0;
			$output = "";
			foreach($leftTags as $index => $tags) {
				$cTags = $tags;
				array_shift($cTags);
				$tag = implode($cTags);
				$lTags[$index] = array($tags[0],$tag);
				$offset = $index - $prevIndex;
				$tmp = substr($file, 0, $offset);
				$file = substr($file, strlen($tmp));
				$output = $output.$tmp.$tag;
				$prevIndex = $index;
			}
			$output = $output.$file;

			// we will sort right side from the biggest key to the smalles
			krsort($rightTags);

			// and we will implode all rightside html tags
			foreach($rightTags as $key => $tags) {
				$regex = $tags[0];
				array_shift($tags);
				$tagS = $tags;
				$tag = implode($tagS);
				$rightTags[$key] = array($regex, $tag);
			}

			// again we will check for correct creation of array
			if (isset($lTags)) {
				$copyLTags = $lTags;

				// we will shift all keys by the size of left html tags that were included before certain regular expressions
				foreach ($rightTags as $index => $tags) {
					$shift = 0;
					$i = 0;
					while ($i <= (count($copyLTags) - 1)) {
						$shift += strlen(current($copyLTags)[1]);
						next($copyLTags);
						$i++;
					}
					$right[$index + $shift] = $tags;
					array_pop($copyLTags);
				}

				// we will resort it again and concatenate right side of html pairs
				ksort($right);
				$prevI = 0;
				foreach ($right as $i => $key) {
					$offset = $i - $prevI + strlen($right[$i][0]);
					$tmp = substr($output, 0, $offset);
					$output = substr($output, strlen($tmp));
					$out = $out . $tmp . $key[1];
					$prevI = $i + strlen($right[$i][0]);
				}
				$out = $out . $output;
			}
		}
		$output = $out;

		// and it's done!
		return $output;
	}

	/*
	 * Function is reading the text from STDIN when argument --input= is not passed to script
	 *
	 * @return string $input Text from STDIN is converted into string and returned, if no chars were read, function returns blank string
	 */
	public function readFromStdinToInput() {

		$input = "";
		while (($char = fgetc(STDIN)) !== false) {
			$input = $input . $char;
		}

		return $input;

	}

	/*
	 * Function reads text from $file, only called when --input= argument is passed.
	 *
	 * @param string $file string containing all lines of file is returned, otherwise blank string is returned
	 */
	public function readFromFileToInput(Common $common, $file) {
		$input = "";
		if (($fp = fopen($file,"r")) !== false) {
			while (($line = fgets($fp)) !== false) {
				$input = $input.$line;
			}
		} else {
			$common->exception(2, "file", true);
		}

		return $input;
	}

	/*
	 * Function checks for encoding of the file, it is not currently used in this version
	 *
	 * @param Class $common Class that is called for exceptions
	 * @param file $file Filename that is passed to the script
	 */
	public function checkEncoding(Common $common, $file) {

		if (($enc = mb_detect_encoding($file)) != "UTF-8") {
			$common->exception(4, "utf", true);
		}

	}

	/*
	 * Function writes the $file string to the output file specified in $oFile, only called when output file is specified
	 *
	 * @param string $file Modified file converted into string
	 * @param file $oFile Name of the output file
	 */
	public function writeToFile(Common $common, $file, $oFile) {

		if (($filePointer = @fopen($oFile, "w+")) !== false) {
			fwrite($filePointer, '');
			fwrite($filePointer, $file);
			fclose($filePointer);
		} else {
			$common->exception(3,"file", true);
		}
	}

	/*
	 * Function writes the modified formatted file converted into string to the stdout, only called when no output file is specified
	 *
	 * @param string $file formatted file converted into string
	 */
	public function writeToStdout($file) {

		fwrite(STDOUT, $file);
	}
}
