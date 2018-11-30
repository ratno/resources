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
                return str_repeat("\t",$n);
            }
        }
    }
}

if(!function_exists("uniform_tab")) {
    function uniform_tab($arrayOrString, $uniform_tab_number)
    {
        if(is_array($arrayOrString)) {
            $array = $arrayOrString;
        } else {
            $array = explode("\n",$arrayOrString);
        }

        $out = [];
        $idx = 0;
        if(is_array($array)) {
            foreach($array as $line) {
                if($uniform_tab_number < 0) {
                    $out[] = preg_replace("/".str_repeat('\s',abs($uniform_tab_number)*4)."/","",$line,1);
                } else {
                    $out[] = tab($uniform_tab_number,$idx++) . $line;
                }
            }
        }

        return implode("\n",$out);
    }
}