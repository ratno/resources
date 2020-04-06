<?php

if(!function_exists("col")) {
    /*
     * membuat instance dari semar/column
     * @param string $real_column default true, karena akan disimpan sebagai kolom, jika false maka hanya pseudo column saja
     */
    function col($real_column=true)
    {
        return new \Ratno\Resources\Column($real_column);
    }
}

if(!function_exists("tab")) {
    function tab($n=1,$idx=null)
    {
        if(is_null($idx)) {
            return "";
        } else {
            if($idx == 0) {
                return "";
            } else {
                if($n > 0) {
                    return str_repeat(" ",($n*4));
                } else {
                    return "";
                }
            }
        }
    }
}

if(!function_exists("uniform_tab")) {
    function uniform_tab($arrayOrString, $uniform_tab_number,$removeFirstEmptyLine = false, $idx_init = 0, $debug = false)
    {
        if(is_array($arrayOrString)) {
            $array = $arrayOrString;
        } else {
            $array = explode("\n",$arrayOrString);
        }

        if(count($array)>1 && $removeFirstEmptyLine) {
            // remove first empty line
            if(trim($array[0]) == "") {
                $array[1] = $array[0] . trim($array[1]);
                unset($array[0]);
            }
        }

        $out = [];
        $idx = $idx_init;
        if(is_array($array)) {
            foreach($array as $line) {
                if($debug) echo $line;
                if($uniform_tab_number < 0) {
                    $pattern = "/".str_repeat('\s',abs($uniform_tab_number)*4)."/";
                    $out[] = preg_replace($pattern,"",$line,1);
                } else {
                    $out[] = tab($uniform_tab_number,$idx++) . $line;
                }
            }
        }

        if(count($out)) {
            $last_index = count($out)-1;
            if(trim($out[$last_index]) == ""){
                unset($out[$last_index]);
            }
        }

        return implode("\n",$out);
    }
}

if (!function_exists('prepare_file_column_save')) {
    function prepare_file_column_save($value)
    {
        $out = [];
        if(count($value)) {
            foreach($value as $item) {
                $item['url'] = substr($item['url'],stripos($item['url'],"api/upload"));
                $out[] = $item;
            }
        }
        return json_encode($out);
    }
}

if (!function_exists('prepare_file_column_read')) {
    function prepare_file_column_read($value)
    {
        $array = json_decode($value,true);
        $out = [];
        if($array) {
            foreach ($array as $item) {
                // check kalau urlnya
                if(preg_match("/(api\/upload)/",$item['url'])) {
                    $item['url'] = request()->getSchemeAndHttpHost() . "/" . $item['url'];
                }

                $out[] = $item;
            }
        }
        return $out;
    }
}