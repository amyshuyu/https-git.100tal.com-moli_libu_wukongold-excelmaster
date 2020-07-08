<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Voucher extends Admin_Controller {

    //短期班对应的分录号
    const SHORT_COURSE_FENLU = 7;

    //短期班对应的科目编号
    const SHORT_COURSE_KEMU = '22030105';
	
	function __construct()
	{
		parent::__construct();
        $this -> load -> model('Subject_model');
        $this -> load -> model('School_model');
        $this -> load -> model('Course_model');
	}

	function index(){

        $this->view('index');
	}

    public function upload_file(){

        if (! empty ( $_FILES ['file_stu'] ['name'] ))
        {
            $tmp_file = $_FILES ['file_stu'] ['tmp_name'];
            $file_types = explode ( ".", $_FILES ['file_stu'] ['name'] );
            $file_type = $file_types [count ( $file_types ) - 1];

            /*判别是不是.xls文件，判别是不是excel文件*/
            if (strtolower ( $file_type ) != "xlsx" && strtolower ( $file_type ) != "xls")
            {
                $this->showmessage('不是Excel文件，重新上传',base_url('adminpanel/voucher/index'));
            }

            /*设置上传路径*/
            $savePath = $_SERVER['DOCUMENT_ROOT'].'/file/';

            /*以时间来命名上传的文件*/
            $str = date ( 'Ymdhis' );
            $file_name = $str . "." . $file_type;

            /*是否上传成功*/
            if (! copy ( $tmp_file, $savePath . $file_name ))
            {
                $this->showmessage('上传失败,请重试',base_url('adminpanel/voucher/index'));
            }
            $this -> load -> library('exceltoarray');
            $arr = $this -> exceltoarray -> read ($savePath . $file_name,$encode='utf-8',$file_type);
			unlink($savePath.$file_name);

            $arr_excl = array(
                //'编号',
                '收款单所有人所属的校区',
                '学员名字',
                '日期',
                '收款方式',
                '收款金额',
                '手续费',
                '入账金额',
                //'定金',
                '学费',
                //'注册费',
                '学杂费',
                //'Pen-reader',
                //'补买教材教具',
                //'短期班类型',
                //'短期班金额'
            );

            $result = array_diff($arr_excl,$arr[1]);

            if(!empty($result)){

                $this->showmessage('<span style="color: red">'.implode(' , ',$result).'</span></br>以上字段未找到或是名称错误！请检查后重新上传！',base_url('adminpanel/voucher/index'),36000);
            }else{
                $arr_data = array();
                for ( $i=2; $i<=count($arr); $i++ ){
                    $arr_data[]=array_combine($arr[1],$arr[$i]);
                }
                $this -> getVoucher($arr_data);
            }

        }else{
            $this->showmessage('请选择上传文件！',base_url('adminpanel/voucher/index'));
        }


    }

    /***
     * 进行800系统到金蝶系统的财务收款单凭证转换
     * @param $arr
     */
    public function getVoucher ($arr)
    {

        //获取凭证的表头
        $arr_data = $this->getVoucherExcelHeader();
        $num = 0;
        $i = 0;
        $p = 0;
        $arr_all = array();
		$all_zd = $this-> Subject_model ->get_all_zd();
		$arr_zd = array();
		//获取凭证学科（收费项目字段）的一维数组
		foreach($all_zd as $row) {
			$arr_zd[] = $row['字段'];
		}
		//追加短期班金额字段
        array_push($arr_zd,'短期班金额');
		//对excel内容按行进行循环转换
        foreach($arr as $key => $value){
            $arr_data['记账日期'] = strtotime($value['日期']);
            $arr_data['业务日期'] = strtotime($value['日期']);
            $arr_data['会计期间'] = date('n',strtotime($value['日期']));
            $arr_data['凭证类型'] = '记';
            $arr_data['币种'] = 'BB01';
            $arr_data['汇率'] = '1';
            $arr_data['数量'] = 0;
            $arr_data['单价'] = 0;
            $arr_data['制单人'] = '翁立宁';
            $arr_data['过账人'] = '';
            $arr_data['审核人'] = '';
            $arr_data['附件数量'] = '0';
            $arr_data['过账标记'] = 'FALSE';
            $arr_data['机制凭证模块'] = '';
            $arr_data['删除标记'] = 'FALSE';
            $arr_data['单位'] = '';
            $arr_data['参考信息'] = '';
            $arr_data['是否有现金流量'] = '';
            $arr_data['现金流量标记'] = 0;
            $arr_data['业务编号'] = '';
            $arr_data['结算方式'] = '';
            $arr_data['结算号'] = '';
            $arr_data['核算项目3'] = '';
            $arr_data['编码3'] = '';
            $arr_data['名称3'] = '';
            $arr_data['核算项目4'] = '';
            $arr_data['编码4'] = '';
            $arr_data['名称4'] = '';
            $arr_data['核算项目5'] = '';
            $arr_data['编码5'] = '';
            $arr_data['名称5'] = '';
            $arr_data['核算项目6'] = '';
            $arr_data['编码6'] = '';
            $arr_data['名称6'] = '';
            $arr_data['核算项目7'] = '';
            $arr_data['编码7'] = '';
            $arr_data['名称7'] = '';
            $arr_data['核算项目8'] = '';
            $arr_data['编码8'] = '';
            $arr_data['名称8'] = '';
            $arr_data['发票号'] = '';
            $arr_data['换票证号'] = '';
            $arr_data['客户'] = '';
            $arr_data['费用类别'] = '';
            $arr_data['收款人'] = '';
            $arr_data['物料'] = '';
            $arr_data['财务组织'] = '';
            $arr_data['供应商'] = '';
            $arr_data['辅助账业务日期'] = strtotime($value['日期']);
            $arr_data['到期日'] = '';
            //校区转换（800系统里面的校区到励步真实校区的转换）
            $row_s = $this -> School_model -> get_one_gs($value['收款单所有人所属的校区']);
            $arr_data['公司'] = $row_s['公司'];


            //从第2行开始 检测 本行和上一行 日期、校区、收款方式是否相同
            if($key > 0 && date('Y-m-d',strtotime($arr[$key - 1]['日期'])) == date('Y-m-d',strtotime($arr[$key]['日期'])) && $arr[$key - 1]['收款单所有人所属的校区'] == $arr[$key]['收款单所有人所属的校区'] && $arr[$key - 1]['收款方式'] == $arr[$key]['收款方式']){

                $arr_data['凭证号'] = $num;
                //$arr_data['凭证序号'] = $num;

                foreach($arr_zd as $key2 => $value2){

                    if($value[$value2] != 0 && $value2 == '入账金额'){

                        $row_z = $this -> Subject_model -> get_one_zd($value2);

                        $arr_data['借方金额'] = $arr_all[$arr_data['记账日期'].$value['收款单所有人所属的校区'].$value['收款方式']." 2203010806"]['原币金额'] + $value[$value2];
                        unset($arr_all[$arr_data['记账日期'].$value['收款单所有人所属的校区'].$value['收款方式']." 2203010806"]);
                        $arr_data['贷方金额'] = '';
                        $arr_data['方向'] = '1';
                        $arr_data['摘要'] = '收'.$value['收款单所有人所属的校区'].'校区学费'.date('Y-n-j',strtotime($value['日期']));
                        $arr_data['辅助账摘要'] = '收'.$value['收款单所有人所属的校区'].'校区学费'.date('Y-n-j',strtotime($value['日期']));
                        $arr_data['原币金额'] = $arr_data['借方金额']+$arr_data['贷方金额'];

                        $arr_data['核算项目1'] = $row_z['核算项目1'];

                        if($arr_data['核算项目1'] == '成本中心'){

                            $arr_data['编码1'] = $row_s['编码'];
                            $arr_data['名称1'] = $row_s['EAS成本中心'];

                        }elseif($arr_data['核算项目1'] == '客户'){

                            $arr_data['编码1'] = 'kh001';
                            $arr_data['名称1'] = '考试中心';

                        }elseif($arr_data['核算项目1'] == '职员'){

                            $arr_data['编码1'] = 'zy001';
                            $arr_data['名称1'] = '职员01';

                        }else{

                            $arr_data['编码1'] = '';
                            $arr_data['名称1'] = '';
                        }
                        $arr_data['核算项目2'] = $row_z['核算项目2'];
                        $arr_data['编码2'] = ' '.$row_z['编码2'];
                        $arr_data['名称2'] = $row_z['名称2'];
                        $arr_data['分录号'] = $row_z['分录号'];
                        $arr_data['科目'] = ' '.$row_z['科目代码'];


                    }elseif($value[$value2] != 0 && $value2 == '手续费'){

                        $row_z = $this -> Subject_model -> get_one_zd($value2);

                        $arr_data['借方金额'] = $arr_all[$arr_data['记账日期'].$value['收款单所有人所属的校区'].$value['收款方式']." 660245"]['原币金额'] + $value[$value2];
                        unset($arr_all[$arr_data['记账日期'].$value['收款单所有人所属的校区'].$value['收款方式']." 660245"]);
                        $arr_data['贷方金额'] = '';
                        $arr_data['方向'] = '1';
                        $arr_data['摘要'] = '收'.$value['收款单所有人所属的校区'].'校区学费'.date('Y-n-j',strtotime($value['日期']));
                        $arr_data['辅助账摘要'] = '收'.$value['收款单所有人所属的校区'].'校区学费'.date('Y-n-j',strtotime($value['日期']));
                        $arr_data['原币金额'] = $arr_data['借方金额']+$arr_data['贷方金额'];

                        $arr_data['核算项目1'] = $row_z['核算项目1'];
                        if($arr_data['核算项目1'] == '成本中心'){

                            $arr_data['编码1'] = $row_s['编码'];
                            $arr_data['名称1'] = $row_s['EAS成本中心'];

                        }elseif($arr_data['核算项目1'] == '客户'){

                            $arr_data['编码1'] = 'kh001';
                            $arr_data['名称1'] = '考试中心';

                        }elseif($arr_data['核算项目1'] == '职员'){

                            $arr_data['编码1'] = 'zy001';
                            $arr_data['名称1'] = '职员01';

                        }else{

                            $arr_data['编码1'] = '';
                            $arr_data['名称1'] = '';
                        }
                        $arr_data['核算项目2'] = $row_z['核算项目2'];
                        $arr_data['编码2'] = ' '.$row_z['编码2'];
                        $arr_data['名称2'] = $row_z['名称2'];
                        $arr_data['分录号'] = $row_z['分录号'];
                        $arr_data['科目'] = ' '.$row_z['科目代码'];


                    }elseif($value[$value2] != 0){


                        $row_z = $this -> Subject_model -> get_one_zd($value2);
                        if($row_z['借贷方'] == '借'){
                            $arr_data['借方金额'] = $value[$value2];
                            $arr_data['贷方金额'] = '';
                            $arr_data['方向'] = '1';
                            $arr_data['摘要'] = '收'.$value['收款单所有人所属的校区'].'校区学费'.date('Y-n-j',strtotime($value['日期']));
                            $arr_data['辅助账摘要'] = '收'.$value['收款单所有人所属的校区'].'校区学费'.date('Y-n-j',strtotime($value['日期']));
                        }else{
                            $arr_data['借方金额'] = '';
                            $arr_data['贷方金额'] = $value[$value2];
                            $arr_data['方向'] = '0';
                            $arr_data['摘要'] = '收'.$value['收款单所有人所属的校区'].'校区学费'.date('Y-n-j',strtotime($value['日期']));
                            $arr_data['辅助账摘要'] = '收'.$value['收款单所有人所属的校区'].'校区'.$value['学员名字'].'学费'.date('Y-n-j',strtotime($value['日期']));
                        }
                        $arr_data['原币金额'] = $arr_data['借方金额']+$arr_data['贷方金额'];
                        //短期班 核算项目1  直接取 成本中心
                        if($value2 == '短期班金额'){
                            $arr_data['核算项目1'] = '成本中心';
                        }else{
                            $arr_data['核算项目1'] = $row_z['核算项目1'];
                        }
                        if($arr_data['核算项目1'] == '成本中心'){
                            $arr_data['编码1'] = $row_s['编码'];
                            $arr_data['名称1'] = $row_s['EAS成本中心'];

                        }elseif($arr_data['核算项目1'] == '客户'){

                            $arr_data['编码1'] = 'kh001';
                            $arr_data['名称1'] = '考试中心';

                        }elseif($arr_data['核算项目1'] == '职员'){

                            $arr_data['编码1'] = 'zy001';
                            $arr_data['名称1'] = '职员01';

                        }else{

                            $arr_data['编码1'] = '';
                            $arr_data['名称1'] = '';
                        }

                        $arr_data['分录号'] = $row_z['分录号'];
                        $arr_data['科目'] = ' '.$row_z['科目代码'];

                        if($value2 == '短期班金额'){
                            //从新增加的短期班对应关系表里获取编码和新名称
                            $courseInfo = $this->Course_model->get_one_course($value['短期班类型']);
                            $arr_data['核算项目2'] = '学科';
                            $arr_data['编码2'] = ' '.$courseInfo['EAS编码'];
                            $arr_data['名称2'] = $courseInfo['EAS学科'];
                            $arr_data['分录号'] = self::SHORT_COURSE_FENLU;
                            $arr_data['科目'] = ' '.self::SHORT_COURSE_KEMU;

                        }else{

                            $arr_data['核算项目2'] = $row_z['核算项目2'];
                            $arr_data['编码2'] = ' '.$row_z['编码2'];
                            $arr_data['名称2'] = $row_z['名称2'];

                        }

                    }else{
                        continue;
                    }
                    if($value2 == '入账金额' || $value2 == '手续费'){
                        $arr_all[$arr_data['记账日期'].$value['收款单所有人所属的校区'].$value['收款方式'].$arr_data['科目']] = $arr_data;
                    }else{
                        $arr_all[$arr_data['记账日期'].$value['收款单所有人所属的校区'].$value['收款方式'].$arr_data['科目'].$i++] = $arr_data;
                    }


                }


            }else{

                if($key > 0 && date('Y-m-d',strtotime($arr[$key - 1]['日期'])) != date('Y-m-d',strtotime($arr[$key]['日期']))){
                    $num = 0;
                }
                $num = $num + 1;
                $arr_data['凭证号'] = $num;
                //$arr_data['凭证序号'] = $num;
                foreach($arr_zd as $key2 => $value2){

                    if($value[$value2] != 0){

                        $row_z = $this -> Subject_model -> get_one_zd($value2);


                        if($row_z['借贷方'] == '借'){
                            $arr_data['借方金额'] = $value[$value2];
                            $arr_data['贷方金额'] = '';
                            $arr_data['方向'] = '1';
                            $arr_data['摘要'] = '收'.$value['收款单所有人所属的校区'].'校区学费'.date('Y-n-j',strtotime($value['日期']));
                            $arr_data['辅助账摘要'] = '收'.$value['收款单所有人所属的校区'].'校区学费'.date('Y-n-j',strtotime($value['日期']));
                        }else{
                            $arr_data['借方金额'] = '';
                            $arr_data['贷方金额'] = $value[$value2];
                            $arr_data['方向'] = '0';
                            $arr_data['摘要'] = '收'.$value['收款单所有人所属的校区'].'校区学费'.date('Y-n-j',strtotime($value['日期']));
                            $arr_data['辅助账摘要'] = '收'.$value['收款单所有人所属的校区'].'校区'.$value['学员名字'].'学费'.date('Y-n-j',strtotime($value['日期']));
                        }
                        $arr_data['原币金额'] = $arr_data['借方金额']+$arr_data['贷方金额'];
                        //短期班 核算项目1  直接取 成本中心
                        if($value2 == '短期班金额'){
                            $arr_data['核算项目1'] = '成本中心';
                        }else{
                            $arr_data['核算项目1'] = $row_z['核算项目1'];
                        }
                        if($arr_data['核算项目1'] == '成本中心'){

                            $arr_data['编码1'] = $row_s['编码'];
                            $arr_data['名称1'] = $row_s['EAS成本中心'];

                        }elseif($arr_data['核算项目1'] == '客户'){

                            $arr_data['编码1'] = 'kh001';
                            $arr_data['名称1'] = '考试中心';

                        }elseif($arr_data['核算项目1'] == '职员'){

                            $arr_data['编码1'] = 'zy001';
                            $arr_data['名称1'] = '职员01';

                        }else{

                            $arr_data['编码1'] = '';
                            $arr_data['名称1'] = '';
                        }

                        $arr_data['分录号'] = $row_z['分录号'];
                        $arr_data['科目'] = ' '.$row_z['科目代码'];

                        if($value2 == '短期班金额'){
                            //从新增加的短期班对应关系表里获取编码和新名称
                            $courseInfo = $this->Course_model->get_one_course($value['短期班类型']);
                            $arr_data['核算项目2'] = '学科';
                            $arr_data['编码2'] = ' '.$courseInfo['EAS编码'];
                            $arr_data['名称2'] = $courseInfo['EAS学科'];
                            $arr_data['分录号'] = self::SHORT_COURSE_FENLU;
                            $arr_data['科目'] = ' '.self::SHORT_COURSE_KEMU;
                        }else{
                            $arr_data['核算项目2'] = $row_z['核算项目2'];
                            $arr_data['编码2'] = ' '.$row_z['编码2'];
                            $arr_data['名称2'] = $row_z['名称2'];
                        }

                    }else{
                        continue;
                    }

                    if($value2 == '入账金额' || $value2 == '手续费'){
                        $arr_all[$arr_data['记账日期'].$value['收款单所有人所属的校区'].$value['收款方式'].$arr_data['科目']] = $arr_data;
                    }else{
                        $arr_all[$arr_data['记账日期'].$value['收款单所有人所属的校区'].$value['收款方式'].$arr_data['科目'].$i++] = $arr_data;
                    }

                }

            }

        }


        foreach ($arr_all as $key => $value){

            $value['凭证序号'] = ++$p;
            $all_data[] = $value;
        }

        $this -> excle_p($all_data);

    }


    public function excle_p($arr_data){


            $table_name = '凭证';
            $this -> load -> library('excel');
            $letter = array('A','B','C','D','E','F','G','H','I',"J",'K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP');
            $tableheader = array('公司','记账日期','业务日期','会计期间','凭证类型','凭证号','分录号','摘要','科目','币种','汇率','方向','原币金额','数量','单价','借方金额','贷方金额','制单人','过账人','审核人','附件数量','过账标记','机制凭证模块','删除标记','凭证序号','单位','参考信息','是否有现金流量','现金流量标记','业务编号','结算方式','结算号','辅助账摘要','核算项目1','编码1','名称1','核算项目2','编码2','名称2','核算项目3','编码3','名称3','核算项目4','编码4','名称4','核算项目5','编码5','名称5','核算项目6','编码6','名称6','核算项目7','编码7','名称7','核算项目8','编码8','名称8','发票号','换票证号','客户','费用类别','收款人','物料','财务组织','供应商','辅助账业务日期','到期日');
            $this -> excel -> w_excel($letter,$tableheader,$arr_data,$table_name);

    }

    /***
     * 获取凭证的表头
     * @return array
     */
    private function getVoucherExcelHeader(){

        $arr = array(
            '公司' => '',
            '记账日期' => '',
            '业务日期' => '',
            '会计期间' => '',
            '凭证类型' => '',
            '凭证号' => '',
            '分录号' => '',
            '摘要' => '',
            '科目' => '',
            '币种' => '',
            '汇率' => '',
            '方向' => '',
            '原币金额' => '',
            '数量' => '',
            '单价' => '',
            '借方金额' => '',
            '贷方金额' => '',
            '制单人' => '',
            '过账人' => '',
            '审核人' => '',
            '附件数量' => '',
            '过账标记' => '',
            '机制凭证模块' => '',
            '删除标记' => '',
            '凭证序号' => '',
            '单位' => '',
            '参考信息' => '',
            '是否有现金流量' => '',
            '现金流量标记' => '',
            '业务编号' => '',
            '结算方式' => '',
            '结算号' => '',
            '辅助账摘要' => '',
            '核算项目1' => '',
            '编码1' => '',
            '名称1' => '',
            '核算项目2' => '',
            '编码2' => '',
            '名称2' => '',
            '核算项目3' => '',
            '编码3' => '',
            '名称3' => '',
            '核算项目4' => '',
            '编码4' => '',
            '名称4' => '',
            '核算项目5' => '',
            '编码5' => '',
            '名称5' => '',
            '核算项目6' => '',
            '编码6' => '',
            '名称6' => '',
            '核算项目7' => '',
            '编码7' => '',
            '名称7' => '',
            '核算项目8' => '',
            '编码8' => '',
            '名称8' => '',
            '发票号' => '',
            '换票证号' => '',
            '客户' => '',
            '费用类别' => '',
            '收款人' => '',
            '物料' => '',
            '财务组织' => '',
            '供应商' => '',
            '辅助账业务日期' => '',
            '到期日' => '');

        return $arr;

    }


}
