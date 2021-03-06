<?php
/**
 * Project: SYN 06 PHP
 * User: Dominik Skála (xskala11)
 *
 * Date: 16.02.2017
 */
class Common
{
	/*
	 * Class Common
	 * This class is containing basic functions that are used mainly in main script file and or in parser, or editor classes
	 */

	/**
	 * The function checks for validity of all arguments, if the check fails, the function will exit script with code 1
	 *
	 * @param array $argv An array of arguments passed to script
	 */
	public function checkArguments($argv) {

		global $flags, $files;

		$argCount = count($argv);

		if ($argCount < 2) {
			$this->exception(1, "amount", true);
		} else if ($argCount > 5) {
			$this->exception(1, "amount", true);
		} else {
			if ((strcmp($argv[1], "--help") == 0) && ($argCount < 3)) {
				$this->printHelp();
				exit(0);
			} else if (strcmp($argv[1], "--help") != 0) {
				for ($i = 1; $i < $argCount; $i++) {
					if (preg_match("/--input=.*/", $argv[$i]) == 1) {
						if ($flags["if"] == true) {
							$this->exception(1, "combination", true);
						} else {
							// ulozit promennou
							$files["inputFile"] = substr($argv[$i], 8);
							$flags["if"] = true;
						}
					} else if (preg_match("/--output=.*/", $argv[$i]) == 1) {
						if ($flags["of"] == true) {
							$this->exception(1, "combination", true);
						} else {
							// ulozit promennou
							$files["outputFile"] = substr($argv[$i], 9);
							$flags["of"] = true;
						}
					} else if (preg_match("/--format=.*/", $argv[$i]) == 1) {
						if ($flags["ff"] == true) {
							$this->exception(1, "combination", true);
						} else {
							// ulozit promennou
							$files["formatFile"] = substr($argv[$i], 9);
							$flags["ff"] = true;
						}
					} else if (preg_match("/--br/", $argv[$i]) == 1) {
						if ($flags["br"] == true) {
							$this->exception(1, "combination", true);
						} else {
							// ulozit promennou
							$flags["br"] = true;
						}
					} else {
						$this->exception(1, "combination", true);
					}
				}
			} else {
				$this->exception(1, "combination", true);
			}
			$this->checkAllFiles($files);
		}
	}

	/*
	 * Function checks for availability of all files, if input file is not valid, we will throw exception with exit(2)
	 * if the output file already exists, we will unlink it (delete it) just to be sure..
	 *
	 * @param array $files Array of files that is being validated
	 */
	public function checkAllFiles($files) {

		if ($files["inputFile"] != "stdin") {
			if (!file_exists($files["inputFile"])) {
				$this->exception(2, "file", true);
			}
		}

		if ($files["outputFile"] != "stdout") {

			if (file_exists($files["outputFile"])) {
				unlink($files["outputFile"]);
			}
		}
	}

	/**
	 * Function for throwing exceptions and stopping the script
	 *
	 * @param int $errorCode selector of type of error
	 * @param string $errorText Depending on this value function selects which type i will echo
	 * @param bool $echo value selects whether to echo error or not.
	 */
	public function exception($errorCode, $errorText, $echo) {

		global $fileName;

		if ($echo == true) {

			fwrite(STDERR, "ERROR: ");
			if ($errorText == "combination") {
				fwrite(STDERR,"Wrong combination of parameters! ");
			} else if ($errorText == "amount") {
				fwrite(STDERR,"Wrong amount of arguments passed! ");
			} else if ($errorText == "file") {
				fwrite(STDERR,"Couldn't open or missing file! ");
			} else if ($errorText == "formatFile") {
				fwrite(STDERR,"Wrong format of the formatting file! ");
			} else if ($errorText == "utf") {
				fwrite(STDERR,"Wrong format of the input file! ");
			}
			fwrite(STDERR,"Use: " . $fileName . " --help\n");
		}
		exit($errorCode);
	}


	/**
	 * Function opens the format file and loads it into array of lines
	 *
	 * @param string $file Name of the formatting file
	 * @param string $type Type of the formatting file
	 */
	public function getFormatFile($file, $type) {

		// this function was in the past used also for input and output files, later on it was reworked and used only for format files, could be revisioned and shortened..
		if (!file_exists($file)) {
			if ($type != "ff") {
				$this->exception(2, "file", true);
			} else {
				$file = "stdin"; // just a "help variable" that will be probably never used until somebody will create stdin file (probably won't happen
				return $file;
			}
		} else {
			$ff = file($file);
			if ($ff === false) {
				$this->exception(2, "file", true);
			}
			return $ff;
		}
		return false;
	}

	/**
	 * Function for fileClosing and checking for correct file closing
	 *
	 * @param resource $file Name of the file to be closed
	 */
	public function closeFile($file) {

		if ((fclose($file)) == false) {
			$this->exception(2, "file", false);
		}
	}

	/**
	 * Function that should be called only with --help arguments, prints help
	 *
	 */
	public function printHelp() {

		echo "\nDeveloper: Dominik Skala (xskala11)\n";
		echo "Task name: SYN\n";
		echo "Subject: IPP (2016/2017)\n\n\n";
		echo "How to use certain arguments:\n";
		echo "--help\t\tCan be used only when no other arguments are passed.
	Prints this help message\n";
		echo "--br\t\tCan be used with any other argument except for --help.
	At the end of the script, it will insert a \"<br />\" after every \\n.\n";
		echo "--format=FILE\tCan be used with any other argument except for --help.
	Specifies the formatting of input file. If no file is included,
	returns unchanged input file.\n";
		echo "--input=FILE\tCan be used with any other argument except for --help.
	Specifies the input file, which will be selected to modify.
	If no input file is selected, script will accept any text 
	from stdin.\n";
		echo "--output=FILE\tCan be used with any other argument except for --help.
	Specifies the output file, which will select, where to 
	store modified input.
	If no output file is selected, script will return everything 
	to stdout.\n";
		echo "FILE\t\tFILE is a route (either absolute or relative) to the file\n";
	}
}