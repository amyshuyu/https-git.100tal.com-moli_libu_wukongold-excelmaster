<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/***
 * 短期班model
 * Class Course_model
 */
class Course_model extends Base_Model {

	public $page_size = 10;
    public function __construct()
    {
		$this -> table_name = 'course';
		parent::__construct();
    }

    public function get_datainfo($where,$page_no,$orderby)
    {
        $page_no = max($page_no,1);
        return $this ->  listinfo($where,'*',$orderby , $page_no, $this  -> page_size,'',$this  -> page_size,page_list_url('adminpanel/course/index',true));
    }

    function default_info(){
        return array(
            'id'=>0,
            'EAS编码'=>'',
            '800短期班类型'=>'',
            'EAS学科'=>''
        );
    }

    /**
     * 根据800的短期班类型名称获取励步的短期班名称
     * @param $course
     * @return mixed
     */
    public function get_one_course($course){
        $query = $this -> db -> select('EAS编码,EAS学科') -> where('800短期班类型',$course) -> get('t_sys_course') ;
        return $query -> row_array();
    }

}