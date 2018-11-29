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
    function uniform_tab($array, $uniform_tab_number)
    {
        $out = [];
        $idx = 0;
        if(is_array($array)) {
            foreach($array as $line) {
                $out[] = tab($uniform_tab_number,$idx++) . $line;
            }
        }

        return implode("\n",$out);
    }
}