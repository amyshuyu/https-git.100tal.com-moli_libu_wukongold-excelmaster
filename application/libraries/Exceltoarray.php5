<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/6
 * Time: 17:40
 */

class ExceltoArray {

    public function __construct(){

        include BASEPATH.'/libraries/PHPExcel.php';

    }

    public function read($filename,$encode='utf-8',$extension){


		if( $extension =='xlsx' )
            {
			  $objReader = PHPExcel_IOFactory::createReader('Excel2007');
            }
        else
            {
			  $objReader = PHPExcel_IOFactory::createReader('Excel5');
            }

        //$objReader = PHPExcel_IOFactory::createReader('Excel5');

        $objReader->setReadDataOnly(true);

        $objPHPExcel = $objReader->load($filename);

        $objWorksheet = $objPHPExcel->getActiveSheet();

        $highestRow = $objWorksheet->getHighestRow();

        $highestColumn = $objWorksheet->getHighestColumn();

        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

        $excelData = array();

        for ($row = 1; $row <= $highestRow; $row++) {
           for ($col = 0; $col < $highestColumnIndex; $col++) {
               if($col == 3){
                   $excelData[$row][] = $this->excelTime($objWorksheet->getCellByColumnAndRow($col, $row)->getValue());
               }else{
                   $excelData[$row][] = $objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
               }

            }
        }
        return $excelData;

    }



    function excelTime($date, $time = false) {

        if(function_exists('GregorianToJD')){

            if (is_numeric( $date )) {

                $jd = GregorianToJD( 1, 1, 1970 );

                $gregorian = JDToGregorian( $jd + intval ( $date ) - 25569 );

                $date = explode( '/', $gregorian );

                $date_str = str_pad( $date [2], 4, '0', STR_PAD_LEFT )

                    ."-". str_pad( $date [0], 2, '0', STR_PAD_LEFT )

                    ."-". str_pad( $date [1], 2, '0', STR_PAD_LEFT )

                    . ($time ? " 00:00:00" : '');

                return $date_str;

            }

        }else{

            $date=$date>25568?$date+1:25569;

            /*There was a bug if Converting date before 1-1-1970 (tstamp 0)*/

            $ofs=(70 * 365 + 17+2) * 86400;

            $date = date("Y-m-d",($date * 86400) - $ofs).($time ? " 00:00:00" : '');

        }

        return $date;

    }

}