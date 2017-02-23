<?php
/**
 * Created by PhpStorm.
 * User: Dominik
 * Date: 19.02.2017
 * Time: 23:35
 */

	class Editor {

		/**
		 * Function checks for --br tag, if it is passed, the function will insert --br tag at end of each line
		 * @param Common $common
		 * @param $outputFile
		 * @return 0 if everything went well, other return values according to the documentation when something happened
		 */
		public function insertNewLine(Common $common, $outputFile) {

			global $flags;
			
			if ($flags["br"] == true) {
				$file = $common->openFile($outputFile, "w+");
				echo "test\n";
				while (!feof($file)) {
					$c = fgetc($file);
					if ($c == '\n') {

					}
				}
			}
		}

	}