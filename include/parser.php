<?php

/**
 * Project: SYN 06 PHP
 * User: Dominik Skála (xskala11)
 *
 * Date: 16.02.2017
 */
class Parser
{
	/*
	 * Class Parser
	 * This class contains several functions that are only used for parsing the file and checking regexes..
	 */

	/**
	 * Function checks for validity of regex, if regex is not valid, the function will return error, otherwise it will continue normally
	 * If the regex is invalid, exception is called, otherwise it does nothing
	 *
	 * @param Common $common We are using this class to call for exceptions
	 * @param $string
	 */
	private function regexChecker(Common $common, $string) {

		$regex = '/'.$string.'/';
		echo $regex."\n";
		if ((@preg_match($regex, "Test")) === false) {
			$common->exception(3, "formatFile", true);
		}

	}

	/**
	 * Function replaces all formatting with valid PCRE formatting, after everything that should be replaced is replaced, the function checks for validity of REGEX
	 *
	 * @param Common $common We are using this class to call for exceptions
	 * @param $string String that will be parsed/replaced/validated
	 */
	protected function parseRegex(Common $common, $string) {

		$parsing = true;
		$slashes = true;
		while ($parsing == true) {
			$parsing = false;
			if ((preg_match("/(!!)/", $string) == 1)) {
				$string = preg_replace("/(!!)/", "", $string);
				$parsing = true;
			} else if ((preg_match("/(\*\*)/", $string) == 1)) {
				$string = preg_replace("/(\*\*)/", "*", $string);
				$parsing = true;
			} else if ((preg_match("/((^\.\.)|([^%]\.\.))/", $string) == 1)) {
				$common->exception(4, "", true);
			}else if ((preg_match("/([^\S]\.)/", $string) == 1)) {
				$common->exception(4, "", true);
			} else if ((preg_match("/((^\|\|)|([^%]\|\|))/", $string) == 1)) {
				$common->exception(4, "", true);
			} else if ((preg_match("/(\+\*|\*\+)/", $string) == 1)) {
				$string = preg_replace("/(\+\*|\*\+)/", "*", $string);
				$parsing = true;
			} else if ((preg_match("/\+\+/", $string) == 1)) {
				$string = preg_replace("/\+\+/", "+", $string);
				$parsing = true;
			} else if ((preg_match("/(%.)/", $string) == 1)) {
				if ((preg_match("/(%s)/", $string) == 1)) {
					$string = preg_replace("/(%s)/", "\s", $string);
					$parsing = true;
				} else if ((preg_match("/(%a)/", $string) == 1)) {
					$string = preg_replace("/(%a)/", ".", $string);
					$parsing = true;
				} else if ((preg_match("/(%d)/", $string) == 1)) {
					$string = preg_replace("/(%d)/", "\d", $string);
					$parsing = true;
				}  else if ((preg_match("/(%l)/", $string) == 1)) {
					$string = preg_replace("/(%l)/", "[a-z]", $string);
					$parsing = true;
				}  else if ((preg_match("/(%L)/", $string) == 1)) {
					$string = preg_replace("/(%L)/", "[A-Z]", $string);
					$parsing = true;
				} else if ((preg_match("/(%w)/", $string) == 1)) {
					$string = preg_replace("/(%w)/", "[a-zA-Z]", $string);
					$parsing = true;
				} else if ((preg_match("/(%W)/", $string) == 1)) {
					$string = preg_replace("/(%W)/", "[a-zA-Z\d]", $string);
					$parsing = true;
				} else if ((preg_match("/(%t)/", $string) == 1)) {
					$string = preg_replace("/(%t)/", "\\t", $string);
					$parsing = true;
				} else if ((preg_match("/(%n)/", $string) == 1)) {
					$string = preg_replace("/(%n)/", "\\n", $string);
					$parsing = true;
				} else if ((preg_match("/(%\|)/", $string) == 1)) {
					$string = preg_replace("/(%\|)/", "\|", $string);
					$parsing = true;
				} else if ((preg_match("/(%\.)/", $string) == 1)) {
					$string = preg_replace("/(%\.)/", "\.", $string);
					$parsing = true;
				} else if ((preg_match("/(%!)/", $string) == 1)) {
					$string = preg_replace("/(%!)/", "\!", $string);
					$parsing = true;
				} else if ((preg_match("/(%\*)/", $string) == 1)) {
					$string = preg_replace("/(%\*)/", "\*", $string);
					$parsing = true;
				} else if ((preg_match("/(%\+)/", $string) == 1)) {
					$string = preg_replace("/(%\+)/", "\+", $string);
					$parsing = true;
				} else if ((preg_match("/(%\()/", $string) == 1)) {
					$string = preg_replace("/(%\()/", "\(", $string);
					$parsing = true;
				} else if ((preg_match("/(%\))/", $string) == 1)) {
					$string = preg_replace("/(%\))/", "\)", $string);
					$parsing = true;
				} else if ((preg_match("/(%%)/", $string) == 1)) {
					$string = preg_replace("/(%%)/", "\%", $string);
					$parsing = true;
				} else {
					$common->exception(4, "", true);
				}
			} else if ((preg_match("/\//", $string, $matches, PREG_OFFSET_CAPTURE) == 1) && $slashes) {
				$string = preg_replace("/\//", "\/", $string);
				$parsing = true;
				$slashes = false;
			}/* else if ((preg_match("/\!../", $string, $matches, PREG_OFFSET_CAPTURE) == 1)) {
				$string = preg_replace("/\!../", "[^\\" . substr($matches[0][0], 1, 2) . "]", $string);
				$parsing = true;
			}*/ else if ((preg_match("/\!./", $string, $matches, PREG_OFFSET_CAPTURE) == 1)) {
				$string = preg_replace("/\!./", "[^\\" . substr($matches[0][0], 1, 1) . "]", $string);
				$parsing = true;
			} else if ((preg_match("/\(.*\)/", $string, $matches, PREG_OFFSET_CAPTURE) == 1)) {
				$string = preg_replace("/\(.*\)/", substr(substr($matches[0][0],1), 0, strlen(substr($matches[0][0],1))-1), $string);
				$parsing = true;

			//} else if ((preg_match("/([\x00\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0A\x0B\x0C\x0D\x0E\x0F\x10\x11\x12\x13\x14\x15\x16\x17\x18\x19\x1A\x1B\x1C\x1D\x1E\x1F])/", $string) == 1)) {
				//$common->exception(4, "", true);
			}

		}


		$this->regexChecker($common, $string);

		return $string;
	}

	/**
	 * Function is concatenating html tags to $preString and $postString which are strings of html tags
	 *
	 * @param Common $common We are using this class to call for exceptions
	 * @param $tag String we will concatenate correct html tag depending of this value
	 * @param $preString String containing all html tags that should be before certain regex
	 * @param $postString String containing all html tags that should be after certain regex
	 */
	private function parseTag(Common $common, $tag, $preString, $postString) {
		if ((preg_match("/^(bold)$/", $tag)) == 1) {
			$preString = $preString."<b>";
			$postString = "</b>".$postString;
		} else if ((preg_match("/^(italic)$/", $tag)) == 1) {
			$preString = $preString."<i>";
			$postString = "</i>".$postString;
		} else if ((preg_match("/^(underline)$/", $tag)) == 1) {
			$preString = $preString."<u>";
			$postString = "</u>".$postString;
		} else if ((preg_match("/^(teletype)$/", $tag)) == 1) {
			/** @noinspection HtmlDeprecatedTag */
			$preString = $preString."<tt>";
			$postString = "</tt>".$postString;
		} else if (((preg_match("/^(color:[a-fA-F0-9]{6})$/", $tag)) == 1) || ((preg_match("/^(color:[a-fA-F0-9]{3})$/", $tag)) == 1)) {
			$helpString = substr($tag,6);
			$preString = $preString."<font color=#".$helpString.">";
			$postString = "</font>".$postString;
		} else if (((preg_match("/^(size:[1-7]{1})$/", $tag)) == 1)) {
			$helpString = substr($tag, 5);
			$preString = $preString."<font size=".$helpString.">";
			$postString = "</font>".$postString;
		} else {
			$common->exception(4, "formatFile", true);
		}

		// remember that prefix for regex is on the 1 position, suffix is on the 2 position
		return $result = array($preString,$postString);
	}

	/**
	 * Function looks for $formatFile, if the format file is missing, formatting is skipped otherwise validity of formatting file is checked and then it is correctly parsed
	 *
	 * @param Common $common We are using this class to call exceptions or other functions outside of function and outside of this file
	 * @param file::$formatFile File containing all formatting
	 * @return array 2D array of formatting
	 */
	public function parseFormatFile(Common $common, $formatFile) {

		if ($formatFile == "none") {
			//we will return input file, when the format file is not included
			$formatting = array();
			return $formatting;
		} else {
			// now the real fun begins..
			$formats = array();
			$file = $common->getFormatFile($formatFile, "ff");
			if ($file == "stdin") {
				return array();
			}

			// this foreach should do the correct splitting of format file
			$i = 0;
			foreach ($file as $line) {
				if ((preg_match("/([\S\s\"]+[\t]+[\w\d,: \t]+)$/", $line)) == 1) {
					$formats[$i] = preg_split("/([,\s]+)/", $line);
					if ($i < (count($file) - 1) || $formats[$i][count($formats[$i]) - 1] == "") {
						array_pop($formats[$i]);
					}
					$i++;
				} else {
					$common->exception(4, "formatFile", true);
				}
			}


			// array of formats will be filled with correct regular expressions and tags
			$j = 0;
			foreach ($formats as $format) {
				$formatting[$j][0] = $this->parseRegex($common, $format[0]);
				$formatting[$j][1] = "";
				$formatting[$j][2] = "";
				for ($i = 1; $i < count($format); $i++) {
					list($formatting[$j][1], $formatting[$j][2]) = $this->parseTag($common, $format[$i], $formatting[$j][1], $formatting[$j][2]);
				}
				$j++;
			}

			if (isset($formatting)) {
				return $formatting;
			} else {
				return array();
			}
		}
	}
}
