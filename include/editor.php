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
		$output = $file;
		foreach ($formatting as $formKey => $form) {
			$matches = array();
			if ((preg_match_all("/" . $form[0] . "/", $file, $matches[$formKey], PREG_OFFSET_CAPTURE)) >= 0) {

				if (count($matches[$formKey][0]) > 0) {
					foreach ($matches[$formKey] as $occur) {
						$i = 0;
						$output = "";
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

		if (isset($leftTags) && isset($rightTags)) {
			ksort($leftTags);

			$prevIndex = 0;
			$output = "";
			foreach($leftTags as $index => $tags) {
				$cTags = $tags;
				array_shift($cTags);
				$tag = implode($cTags);
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

			$copyLeftTags = $leftTags;

			//print_r($rightTags);
			foreach($rightTags as $key => $tags) {
				array_shift($tags);
				$tag = implode($tags);
				$rightTags[$key] = $tag;
			}
			print_r($rightTags);

			foreach($rightTags as $index => $tags) {
				$shift = 0;
				$i = 0;
				//todo: dodelat iterovani shiftu pro pravy tagy, nasledne uz jen provest tisk a opraveni regexu
				while ($i <= (count($copyLeftTags)-1)) {
					print_r(current($copyLeftTags));
					array_shift(current($copyLeftTags));print_r(current($copyLeftTags));
					sleep(3);
					$i++;
					next($copyLeftTags);
				}
				array_pop($copyLeftTags);
			}

			foreach($rightTags as $index => $tags) {
				$shift = 0;
				$html = "";
				//print_r($tags);
				//print_r($index);
				$i = 0;/*
				while($i <= (count($copyLeftTags)-1)) {

					print_r(current($copyLeftTags));
					next($copyLeftTags);
					$i++;
				}
				reset($copyLeftTags);
				array_pop($copyLeftTags);*/
/*
				foreach($leftTags as $lIndex => $lTags) {

					$cTags = $lTags;
					array_shift($cTags);
					$html = $tag = strlen(implode($cTags));
					$shift += $tag;
				}

				$shift += $index;
				if (!isset($right[$shift])) {
					$right[$shift] = array($tags[0],$html);
				}*/
			}
		}

		//print_r($right);

		//print_r($rightTags);
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
