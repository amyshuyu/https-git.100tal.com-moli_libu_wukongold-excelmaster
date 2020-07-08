<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class School extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();
        $this -> load -> model('School_model');
	}

	function index($page_no = 1){

        $orderby = $keyword = '';
        $where_arr = array();
        if ( isset($_GET['dosubmit']) ) {
            $keyword = isset($_GET['keyword']) ? safe_replace(trim($_GET['keyword'])) : '';
            if( $keyword !='' ) $where_arr[] = "concat(编码,800校区,EAS成本中心,公司) like '%{$keyword}%'";
        }
        $where = implode(' and ',$where_arr);

        $data_list = $this -> School_model -> get_datainfo($where,$page_no,$orderby);

        $this->view('index',array('data_list'=>$data_list,'pages'=>$this->School_model->pages,'keyword'=>$keyword,'require_js'=>true));
	}

    function edit($id=0)
    {
        $id = intval($id);

        $data_info =$this->School_model->get_one(array('id'=>$id));

        //如果是AJAX请求
        if($this->input->is_ajax_request())
        {
            if(!$data_info)exit(json_encode(array('status'=>false,'tips'=>'信息不存在')));

            $bianma = isset($_POST["bianma"])?trim(safe_replace($_POST["bianma"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $xiaoqu = isset($_POST["xiaoqu"])?trim(safe_replace($_POST["xiaoqu"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $eas = isset($_POST["eas"])?trim(safe_replace($_POST["eas"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $gongsi = isset($_POST["gongsi"])?trim(safe_replace($_POST["gongsi"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));



            $status = $this->School_model->update(
                array(
                    '编码'=>$bianma,
                    '800校区'=>$xiaoqu,
                    'EAS成本中心'=>$eas,
                    '公司'=>$gongsi,
                ),array('id'=>$id));
            if($status)
            {
                exit(json_encode(array('status'=>true,'tips'=>'修改成功')));
            }else
            {
                exit(json_encode(array('status'=>false,'tips'=>'修改失败')));
            }
        }else
        {
            if(!$data_info)$this->showmessage('信息不存在');
            $this->view('edit',array('is_edit'=>true,'data_info'=>$data_info,'require_js'=>true));
        }
    }


    function add()
    {
        //如果是AJAX请求
        if($this->input->is_ajax_request())
        {
            //接收POST参数
            $bianma = isset($_POST["bianma"])?trim(safe_replace($_POST["bianma"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $xiaoqu = isset($_POST["xiaoqu"])?trim(safe_replace($_POST["xiaoqu"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $eas = isset($_POST["eas"])?trim(safe_replace($_POST["eas"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $gongsi = isset($_POST["gongsi"])?trim(safe_replace($_POST["gongsi"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));

            $new_id = $this->School_model->insert(
                array(
                    '编码'=>$bianma,
                    '800校区'=>$xiaoqu,
                    'EAS成本中心'=>$eas,
                    '公司'=>$gongsi,
                ));
            if($new_id)
            {
                exit(json_encode(array('status'=>true,'tips'=>'新增成功','new_id'=>$new_id)));
            }else
            {
                exit(json_encode(array('status'=>false,'tips'=>'新增失败','new_id'=>0)));
            }
        }else
        {
            $this->view('edit',array('is_edit'=>false,'require_js'=>true,'data_info'=>$this->School_model->default_info()));
        }
    }


    function delete()
    {
        if(isset($_POST))
        {
            $pidarr = isset($_POST['pid']) ? $_POST['pid'] : $this->showmessage('无效参数', HTTP_REFERER);
            $where = $this->School_model->to_sqls($pidarr, '', 'id');
            $status = $this->School_model->delete($where);
            if($status)
            {
                $this->showmessage('操作成功', base_url('adminpanel/school/index'));
            }else
            {
                $this->showmessage('操作失败');
            }
        }
    }
}