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
     *
     */
    private function regexChecker($string)
    {
    }


    /**
     *
     *
     */
    protected function parseRegex(Common $common, $string)
    {
        $array = str_split($string, 1);
        foreach ($array as $regex) {
            echo $regex."\n";
        }
        echo "\n\n\n";
        return $string;
    }

    /**
     *
     *
     */
    private function parseTag(Common $common, $tag, $preString, $postString)
    {
        if ($tag == "bold") {
            $preString = $preString."<b>";
            $postString = $postString."</b>";
        } else if ($tag == "italic") {
            $preString = $preString."<i>";
            $postString = $postString."</i>";
        } else if ($tag == "underline") {
            $preString = $preString . "<u>";
            $postString = $postString . "</u>";
        } else if ($tag == "teletype") {
                $preString = $preString."<tt>";
                $postString = $postString."</tt>";
        } else if (((preg_match("/(color:[a-fA-F0-9]{6})$/", $tag)) == 1) || ((preg_match("/(color:[a-fA-F0-9]{3})$/", $tag)) == 1)) {
            $helpString = substr($tag,6);
            $preString = $preString."<font color=#".$helpString.">";
            $postString = $postString."</font>";
        } else if (((preg_match("/(size:[1-7]{1})$/", $tag)) == 1)) {
            $helpString = substr($tag, 5);
            $preString = $preString."<font size=".$helpString.">";
            $postString = $postString."</font>";
        } else {
            $common->exception(3, "formatFile", true);
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
            //we will return input file, when the format file is not included
            echo "parsing not done, we will not format the file and return the original file\n";
        } else {
            // now the real fun begins..
            $formats = array();
            $file = $common->getFile($formatFile);

            $i = 0;
            foreach ($file as $line) {
                if ((preg_match("/([\w\d\.\|\!\%\*\+\(\)]+[\t]+[\w\d,: \t]+)$/", $line)) == 1) {
                    $formats[$i] = preg_split("/([,\s\t]+)/", $line);
                    array_pop($formats[$i]);
                    $i++;
                } else {
                    $common->exception(3, "formatFile", true);
                }
            }

            $htmlPrefix = "";
            $htmlSuffix = "";
            $j = 0;
            foreach ($formats as $format) {
                $formatting[$j][0] = $this->parseRegex($common, $format[0]);
                $formatting[$j][1] = "";
                $formatting[$j][2] = "";
                for ($i = 1; $i < count($format); $i++) {
                    $formatting[$j][1] = $this->parseTag($common, $format[$i], $formatting[$j][1], $formatting[$j][2])[0];
                    $formatting[$j][2] = $this->parseTag($common, $format[$i], $formatting[$j][1], $formatting[$j][2])[1];
                }
                $j++;
            }
            /*for ($i = 0; $i < )
                $size = count($format);
                for ($j = 1; $j < $size; $j++) {
                    $html[$i][$j] = $this->parseTag($common, $formats[$i][$j].'', $html[$i][$j], $html[$i][$j]);
                }
                $i++;
            }*/

            //var_dump($formats);

            //regexChecker($string);

        }

    }
}