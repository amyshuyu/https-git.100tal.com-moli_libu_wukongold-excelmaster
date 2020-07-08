<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/6/6
 * Time: 17:40
 */

class Excel {

    public function __construct(){

        //include BASEPATH.'libraries/PHPExcel.php';

    }

    public function w_excel($letter,$tableheader,$data,$table_name){

        $excel = new PHPExcel();
        $excel -> setActiveSheetIndex(0);
        $excel -> getActiveSheet()-> setTitle('凭证');


//Excel表格式,这里简略写了8列

        //$letter = array('A','B','C','D','E','F','F','G');

//表头数组

        //$tableheader = array('学号','姓名','性别','年龄','班级');

//填充表头信息
        $excel->getActiveSheet()->getStyle('B')->getNumberFormat()
            ->setFormatCode('yyyy/m/d;@');
		$excel->getActiveSheet()->getStyle('C')->getNumberFormat()
            ->setFormatCode('yyyy/m/d;@');
        $excel->getActiveSheet()->getStyle('I')->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $excel->getActiveSheet()->getStyle('AI')->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $excel->getActiveSheet()->getStyle('AL')->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $excel->getActiveSheet()->getStyle('AO')->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $excel->getActiveSheet()->getStyle('AR')->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $excel->getActiveSheet()->getStyle('AU')->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $excel->getActiveSheet()->getStyle('AX')->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $excel->getActiveSheet()->getStyle('BA')->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $excel->getActiveSheet()->getStyle('BD')->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
		$excel->getActiveSheet()->getStyle('BN')->getNumberFormat()
            ->setFormatCode('yyyy/m/d;@');

        for($i = 0;$i < count($tableheader);$i++) {

            $excel->getActiveSheet()->getColumnDimension($letter[$i])->setWidth(15);
            $excel->getActiveSheet()->getStyle($letter[$i])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle($letter[$i])->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->getStyle("$letter[$i]1")->getFont()->setSize(14);
            $excel->getActiveSheet()->getStyle("$letter[$i]1")->getFont()->setBold(true);
            $excel->getActiveSheet()->setCellValue("$letter[$i]1","$tableheader[$i]");

        }




//填充表格信息

        for ($i = 2;$i <= count($data) + 1;$i++) {
           // $excel->getActiveSheet()->getRowDimension($i)->setRowHeight(20);

            $j = 0;

            foreach ($data[$i - 2] as $key=>$value) {

                $excel->getActiveSheet()->setCellValue("$letter[$j]$i",$value);
				if($j == 1 || $j==2 || $j==65){
					$excel->getActiveSheet()->setCellValue("$letter[$j]$i",PHPExcel_Shared_Date::PHPToExcel($value));
				}

                $j++;

            }
            //$excel->getActiveSheet()->getStyle('A1:'."$letter[$j]$i")->getBorders()->getAllBorders()->setBorderStyle(\PHPExcel_Style_Border::BORDER_THIN);

        }

        $excel -> createSheet();
		$excel -> setActiveSheetIndex(1);
        $excel -> getActiveSheet()->setTitle('现金流量');

        $letter2 = array('A','B','C','D','E','F','G','H','I',"J",'K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO');
        $tableheader2 = array('公司','记账日期','会计期间','凭证类型','凭证号','币种','分录号','对方分录号','主表信息','附表信息','原币','本位币','报告币','主表金额系数','附表金额系数','性质','核算项目1','编码1','名称1','核算项目2','编码2','名称2','核算项目3','编码3','名称3','核算项目4','编码4','名称4','核算项目5','编码5','名称5','核算项目6','编码6','名称6','核算项目7','编码7','名称7','核算项目8','编码8','名称8');

        $excel->getActiveSheet()->getStyle('R')->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $excel->getActiveSheet()->getStyle('U')->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $excel->getActiveSheet()->getStyle('X')->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $excel->getActiveSheet()->getStyle('AA')->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $excel->getActiveSheet()->getStyle('AD')->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $excel->getActiveSheet()->getStyle('AG')->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $excel->getActiveSheet()->getStyle('AJ')->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);
        $excel->getActiveSheet()->getStyle('AM')->getNumberFormat()
            ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_TEXT);

        for($i = 0;$i < count($tableheader2);$i++) {
            $excel->getActiveSheet()->getColumnDimension($letter2[$i]);
            $excel->getActiveSheet()->getStyle($letter2[$i])->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
            $excel->getActiveSheet()->getStyle($letter2[$i])->getAlignment()->setVertical(PHPExcel_Style_Alignment::VERTICAL_CENTER);
            $excel->getActiveSheet()->setCellValue("$letter2[$i]1","$tableheader2[$i]");

        }
        $excel -> setActiveSheetIndex(0);

        $write = new PHPExcel_Writer_Excel5($excel);

        header("Pragma: public");

        header("Expires: 0");

        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");

        header("Content-Type:application/force-download");

        header("Content-Type:application/vnd.ms-execl");

        header("Content-Type:application/octet-stream");

        header("Content-Type:application/download");;

        header('Content-Disposition:attachment;filename="'.$table_name.'.xls"');

        header("Content-Transfer-Encoding:binary");

        $write->save('php://output');

		

    }

}