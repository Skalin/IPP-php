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
	 * @param Common $common
	 * @param $outputFile
	 * @return 0 if no new line was inserted due to some limitations of arguments, otherwise returns the whole file
	 */
	public function insertNewLine($file) {

		global $flags;

		if ($flags["br"]) {
			return $br = implode("<br />\n", $br = explode("\n", $file));
		}

		return $file;
	}

	/*
	 * Function
	 *
	 * @param $file
	 * @param $formatting
	 */
	public function editInputFile($file, $formatting) {

		echo "================KONEC VSTUPU===============\n";
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

		if (isset($leftTags) && isset($rightTags)) {
			ksort($leftTags);

			$prevIndex = 0;
			$output = "";
			foreach($leftTags as $index => $tags) {
				$cTags = $tags;
				array_shift($cTags);
				$tag = implode($cTags);
				$lTags[$index] = array($tags[0],$tag);
				$offset = $index - $prevIndex;
				//echo "Index - prevIndex:". $prevIndex . " - ". $index ."\n";
				$tmp = substr($file, 0, $offset);
				//echo $tmp."\n";
				$file = substr($file, strlen($tmp));
				$output = $output.$tmp.$tag;
				$prevIndex = $index;
			}
			$output = $output.$file;

			krsort($rightTags);

			//print_r($rightTags);
			foreach($rightTags as $key => $tags) {
				$regex = $tags[0];
				array_shift($tags);
				$tagS = $tags;
				$tag = implode($tagS);
				//echo "TAG:".$tag."\n";
				$rightTags[$key] = array($regex, $tag);
			}
			//print_r($rightTags);

			if (isset($lTags)) {
				$copyLTags = $lTags;

				foreach ($rightTags as $index => $tags) {
					$shift = 0;
					$i = 0;
					while ($i <= (count($copyLTags) - 1)) {
						$shift += strlen(current($copyLTags)[1]);
						//echo $shift."\n";
						next($copyLTags);
						$i++;
					}
					//print_r($tags);
					$right[$index + $shift] = $tags;
					array_pop($copyLTags);
				}

				ksort($right);
				$prevI = 0;
				foreach ($right as $i => $key) {
					//print_r($i);
					//print_r($key);
					$offset = $i - $prevI + strlen($right[$i][0]);
					//echo "Delka slova: ".strlen($right[$i][0])."\n";
					$tmp = substr($output, 0, $offset);
					//cho "TMP: ".$tmp."\n";
					$output = substr($output, strlen($tmp));
					$out = $out . $tmp . $key[1];
					$prevI = $i + strlen($right[$i][0]);
				}
				$out = $out . $output;
			}
		}
		$output = $out;
		return $output;
	}

	/*
	 * Function
	 */
	public function readFromStdinToInput() {

		$input = "";
		while (($char = fgetc(STDIN)) !== false) {
			$input = $input . $char;
		}

		return $input;

	}

	/*
	 * Function
	 *
	 *
	 */
	public function readFromFileToInput($file) {
		$fp = fopen($file,"r");
		$input = "";
		while (($line = fgets($fp)) !== false) {
			$input = $input.$line;
		}

		return $input;
	}

	/*
	 * Function
	 *
	 * @param $common
	 * @param $file
	 */
	public function checkEncoding(Common $common, $file) {

		if (($enc = mb_detect_encoding($file)) != "UTF-8") {
			$common->exception(4, "utf", true);
		}

	}

	/*
	 * Function
	 *
	 * @param $file
	 * @param $oFile
	 */
	public function writeToFile($file, $oFile) {

		$filePointer = fopen($oFile, "w+");
		fwrite($filePointer, '');
		fwrite($filePointer, $file);
		fclose($filePointer);
	}

	/*
	 * Function
	 *
	 * @param $file
	 */
	public function writeToStdout($file) {

		fwrite(STDOUT, $file);
	}
}
