<?php
/**
 * Created by PhpStorm.
 * User: Dominik
 * Date: 16.02.2017
 * Time: 18:15
 */
	class Common
	{
		/**
		 * The function checks for validity of all arguments, if the check fails,
		 * the function will exit script with code 1
		 * @param array $argv An array of arguments passed to script
		 */
		public function checkArguments($argv) {

		    global $argCount, $flags, $files;

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

		public function checkAllFiles($files) {
			if ($files["inputFile"] != "stdin") {
				if (!file_exists($files["inputFile"])) {
					$this->exception(2, "file", true);
				}
			}

			if ($files["outputFile"] != "stdout") {
				if (!file_exists($files["outputFile"])) {
					$this->exception(2, "file", true);
				}
			}

			if ($files["formatFile"] != "none") {
				if (!file_exists($files["formatFile"])) {
					$this->exception(2, "file", true);
				}
			}
		}

		/**
		 * Function for calling exceptions and stopping the script
		 * @param $errorCode Int selecting type of error
		 * @param $errorText String Depending on this value function selects which type i will echo
		 * @param $echo Bool value selects whether to echo error or not.
		 */
		public function exception($errorCode, $errorText, $echo) {
		    global $fileName, $files, $flags;

		    if ($echo == true) {

				echo "ERROR: ";
				if ($errorText == "combination") {
					echo "Wrong combination of parameters!";
				} else if ($errorText == "amount") {
					echo "Wrong amount of arguments passed!";
				} else if ($errorText == "file") {
					echo "Couldn't open or missing file!";
				} else if ($errorText == "formatFile") {
				    echo "Wrong format of the formatting file!";
                }
				echo " Use: " . $fileName . " --help\n";
			}
			//var_dump($flags);
            //var_dump($files);
			exit($errorCode);
		}


		/**
		 *
		 */
		public function getFile($file)
		{
			if (!file_exists($file)) {
				$this->exception(2, "file", true);
			} else {
				$ff = file($file);
				if ($ff == false) {
					$this->exception(2, "file", true);
				}
				return $ff;
			}
			return false;
		}

		/**
		 *
		 */
		public function closeFile($file)
		{
			if ((fclose($file)) == false) {
				$this->exception(2, "file", false);
			}
		}

		/**
		 *
		 */
		public function printHelp()
		{
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