<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
*/
class Subject_model extends Base_Model {

	public $page_size = 10;
    public function __construct()
    {
		$this -> table_name = 'subject';
		parent::__construct();
    }

    public function get_datainfo($where,$page_no,$orderby)
    {
        $page_no = max($page_no,1);
        return $this ->  listinfo($where,'*',$orderby , $page_no, $this  -> page_size,'',$this  -> page_size,page_list_url('adminpanel/subject/index',true));
    }

    function default_info(){
        return array(
            'id'=>0,
            '字段'=>'',
            '科目'=>'',
            '科目代码'=>'',
            '成本中心'=>'',
            '学科'=>'',
            '客户'=>'',
            '职员'=>'',
            '借贷方'=>'',
            '分录号'=>'',
            '核算项目1'=>'',
            '编码1'=>'',
            '名称1'=>'',
            '核算项目2'=>'',
            '编码2'=>'',
            '名称2'=>'',
            '学科编码'=>'',
            '学科名称'=>'',
        );
    }

    public function  get_one_zd($ziduan){

        $query = $this -> db -> select('科目,科目代码,学科,客户,职员,借贷方,分录号,核算项目1,核算项目2,学科编码,学科名称,编码2,名称2') -> where('字段',$ziduan) -> get('t_sys_subject');
        return $query -> row_array();
    }


	public function  get_all_zd(){

        $query = $this -> db -> select('字段') -> get('t_sys_subject');
        return $query -> result_array();
    }

    

}