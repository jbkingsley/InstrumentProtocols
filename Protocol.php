<?php
/**
 * Created by PhpStorm.
 * User: Deby
 * Date: 2016/11/4
 * Time: 下午5:02
 */
namespace InstrumentProtocols;

class Protocol {

    private $instance;
    private $readCacheName;

    /**
     * instrument protocol
     *
     * @var InstrumentInterface
     */
    public $instrument;

    /**
     * set Instrument Protocol by Instrument's Name
     * @param string $protocolName
     * @return Protocol
     */
    public function setInstrument($protocolName){
        $this->instrument = Instrument::one()->$protocolName;
    }

    /**
     * set Instrument Protocol by Instrument's Name
     * @param $instance
     * @param $readCacheName
     * @return Protocol
     */
    public function setReadCache($instance, $readCacheName){
        $this->instance = $instance;
        $this->readCacheName = $readCacheName;
    }

    /**
     * read payload buffer
     * @param string $buffer;
     * @return array
     */
    public function baseRead(){
        $package_length = $this->instrument->input($this->instance[$this->readCacheName]);
        if($package_length===0){
                return NULL;
        }else{
            $one_request_package = substr($this->instance[$this->readCacheName], 0, $package_length);
            $this->consumeReadCache($package_length);
            return $this->instrument->decode($one_request_package);
        }
    }

    /**
     * Remove $length of data from payload buffer.

     * @param int $length
     * @return void
     */
    public function consumeReadCache($length)
    {
        $this->instance[$this->readCacheName] = substr($this->instance[$this->readCacheName], $length);
    }

}