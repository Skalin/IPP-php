<?php
/**
 * Created by PhpStorm.
 * User: Dominik
 * Date: 19.02.2017
 * Time: 23:35
 */

	class Editor {

		/**
		 * Function checks for --br tag, if it is passed, the function will insert </br> tag at end of each line
		 * @param Common $common
		 * @param $outputFile
		 * @return 0 if no new line was inserted due to some limitations of arguments, otherwise returns the whole file
		 */
		public function insertNewLine(Common $common, $file) {

			global $flags;
			
			if ($flags["br"]) {
                return $br = implode( "<br />\n", $br = explode("\n", $file));
            }

            return 0;
        }

        public function editInputFile(Common $common, $file, $formatting) {
            foreach ($formatting as $form) {
                $write = true;
                while ($write == true) {
                    $write = false;
                    /*if ((preg_match("/([^>]" . $form[0] . "\s?)/", $file)) == 1) {
                        $file = preg_replace("/(\s?[^>]" . $form[0]."\s?)/", $form[1] . "$0", $file);
                        var_dump($file);
                        $write = true;
                        echo "prepisuji\n";
                    }*/
                }
            }
            return $file;
        }

        public function writeToFile(Common $common, $file, $oFile) {
		    $filePointer = fopen($oFile, "w");
		    fwrite($filePointer, $file);
		    fclose($filePointer);
            /*if (($num = fwrite($filePointer, $file)) === false) {
                $common->exception(3,"output",true);
            }*/
        }

        public function writeToStdout(Common $common, $file) {
		    if (($num = fwrite(STDOUT, $file)) === false) {
		        $common->exception(3,"output",true);
            }
        }
	}