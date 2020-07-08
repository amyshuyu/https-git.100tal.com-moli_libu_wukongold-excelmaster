<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/*
*/
class School_model extends Base_Model {

	public $page_size = 10;
    public function __construct()
    {
		$this -> table_name = 'school';
		parent::__construct();
    }

    public function get_datainfo($where,$page_no,$orderby)
    {
        $page_no = max($page_no,1);
        return $this ->  listinfo($where,'*',$orderby , $page_no, $this  -> page_size,'',$this  -> page_size,page_list_url('adminpanel/school/index',true));
    }

    function default_info(){
        return array(
            'id'=>0,
            '编码'=>'',
            '800校区'=>'',
            'EAS成本中心'=>'',
            '公司'=>'',
        );
    }

    public function get_one_gs($school){
        $query = $this -> db -> select('编码,EAS成本中心,公司') -> where('800校区',$school) -> get('t_sys_school') ;
        //echo $this -> db -> last_query();exit;
        return $query -> row_array();
    }

}