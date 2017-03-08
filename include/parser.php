<?php

/**
 * Created by PhpStorm.
 * User: Dominik Skála
 * Date: 16.02.2017
 * Time: 17:38
 */
class Parser
{
    /**
     * Function checks for validity of regex, if regex is not valid, the function will return error, otherwise it will continue normally
     */
    private function regexChecker(Common $common, $string)
    {
        $regex = '/'.$string.'/';
        if ((@preg_match($regex, "Test")) === false) {
            $common->exception(3, "formatFile", true);
        }
    }


    /**
     *
     *
     */
    protected function parseRegex(Common $common, $string)
    {
        $parsing = true;
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
                } else if ((preg_match("/(%w)/", $string) == 1)) {
                    $string = preg_replace("/(%w)/", "\w", $string);
                    $parsing = true;
                } else if ((preg_match("/(%W)/", $string) == 1)) {
                    $string = preg_replace("/(%W)/", "\w|\d", $string);
                    $parsing = true;
                } else if ((preg_match("/(%t)/", $string) == 1)) {
                    $string = preg_replace("/(%t)/", "\t", $string);
                    $parsing = true;
                } else if ((preg_match("/(%n)/", $string) == 1)) {
                    $string = preg_replace("/(%n)/", "\n", $string);
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
            } else if ((preg_match("/((!)([^\s]+))/", $string) == 1) && (preg_match("/((\\!)([^\s]+))/", $string) != 1)) {
                $string = preg_replace("/((!)([^\s]+))/", "[^$3]", $string);
                $parsing = true;
            /*} else if ((preg_match("/([\x00\x01\x02\x03\x04\x05\x06\x07\x08\x09\x0A\x0B\x0C\x0D\x0E\x0F\x10\x11\x12\x13\x14\x15\x16\x17\x18\x19\x1A\x1B\x1C\x1D\x1E\x1F])/", $string) == 1)) {
                echo "samfin\n";
                $common->exception(4, "", true);*/
            }

        }

        $this->regexChecker($common, $string);

        return $string;


    }

    /**
     *
     *
     */
    private function parseTag(Common $common, $tag, $preString, $postString)
    {
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
     *
     */
    public function parseFormatFile(Common $common, $formatFile)
    {
        global $files;

        if ($formatFile == "none") {
            formatFile:
            //we will return input file, when the format file is not included
            echo "parsing not done, we will not format the file and return the original file\n";
        } else {
            // now the real fun begins..
            $formats = array();
            $file = $common->getFile($formatFile, "ff");
            if ($file == "stdin") {
                goto formatFile;
            }

            $i = 0;
            foreach ($file as $line) {
                if ((preg_match("/([\w\d\"\.\|\!\%\*\+\(\)]+[\t]+[\w\d,: \t]+)$/", $line)) == 1) {
                    $formats[$i] = preg_split("/([,\s]+)/", $line);
                    if ($i < (count($file) - 1) || $formats[$i][count($formats[$i]) - 1] == "") {
                        array_pop($formats[$i]);
                    }
                    $i++;
                } else {
                    $common->exception(4, "formatFile", true);
                }
            }


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

            return $formatting;
        }
    }
}