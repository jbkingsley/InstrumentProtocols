<?php
/**
 * Created by PhpStorm.
 * User: Deby
 * Date: 2016/11/3
 * Time: 下午4:56
 */

namespace InstrumentProtocols;

Interface InstrumentInterface{

    public function input($buffer);

    public function decode($buffer);

}