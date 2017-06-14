<?php
/**
 * Created by PhpStorm.
 * User: Deby
 * Date: 2016/11/5
 * Time: 上午11:07
 */

namespace InstrumentProtocols;

class Functions {

    public static  function hexToStr($hex){
        $string="";
        for($i=0;$i<strlen($hex)-1;$i+=2){
            $string.=chr(hexdec($hex[$i].$hex[$i+1]));
        }
        return  $string;
    }

    public static  function hexToDecAndLowFirst($hex){
        $string="";
        for($i=0;$i<strlen($hex)-1;$i+=2){
            $string = hexdec($hex[$i].$hex[$i+1]) . $string;
        }
        return (int) $string;
    }

}