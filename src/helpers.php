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