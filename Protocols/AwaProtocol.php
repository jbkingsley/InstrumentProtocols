<?php
/**
 * 爱华AWA688噪声仪协议
 * User: Deby
 * Date: 2016/11/3
 * Time: 下午4:56
 */

namespace InstrumentProtocols\Protocols;

use InstrumentProtocols\Functions;
use InstrumentProtocols\InstrumentInterface;
use Workerman\Connection\TcpConnection;

class AwaProtocol implements InstrumentInterface{

    const TYPE_LEN = 2;
    const DATA_LEN = 4;
    const VERIFY_LEN = 2;

    public function input($buffer)
    {
        // Receive length.
        $recv_len = strlen($buffer);

        // We need more data.
        if ($recv_len < 12) {
            return 0;
        }

        $pack = unpack("H6identify/H2type/H4length", $buffer);var_dump(unpack("H*", $buffer));
        $identify = Functions::hexToStr($pack['identify']);  echo '***************' . $identify . '************';
        if($identify!='AWA'){
            throw new \Exception(
                'error instrument type'
            );
        }
        $data_length = Functions::hexToDecAndLowFirst($pack['length']);
        $current_frame_length = $data_length * 2;
        return $current_frame_length;
    }

    public function decode($buffer)
    {
        $buf = unpack("H6identify/H2type/H4length/H14date/H*body", $buffer);
        $type = Functions::hexToStr($buf['type']);

        if($type=='A'){
            return array();
        }elseif($type=='B'){
            return $this->decodeBTypeData($buf['body']);
        }
    }

    private function decodeBTypeData($data){
        $ori_data = $this->getMeasurementData($data);
        $i=0;
        $rs['LZFp'] = $ori_data[$i]/100;
        $rs['LZSp'] = $ori_data[++$i]/100;
        $rs['LZIp'] = $ori_data[++$i]/100;
        $rs['LCFp'] = $ori_data[++$i]/100;
        $rs['LCSp'] = $ori_data[++$i]/100;
        $rs['LCIp'] = $ori_data[++$i]/100;
        $rs['LAFp'] = $ori_data[++$i]/100;
        $rs['LASp'] = $ori_data[++$i]/100;
        $rs['LAIp'] = $ori_data[++$i]/100;
        $rs['LA1s'] = $ori_data[++$i]/100;
        return $rs;
    }

    private function decodeATypeData($data){

    }

    private function getMeasurementData($data){
        $arr=array();
        for($i=0;$i<strlen($data)-3;$i+=4){
            $string = $data[$i].$data[$i+1].$data[$i+2].$data[$i+3];
            $arr[] = Functions::hexToDecAndLowFirst($string);
        }
        return $arr;
    }


}