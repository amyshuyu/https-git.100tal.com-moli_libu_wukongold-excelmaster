<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Subject extends Admin_Controller {
	
	function __construct()
	{
		parent::__construct();
        $this -> load -> model('Subject_model');
	}

	function index($page_no = 1){

        $orderby = $keyword = '';
        $where_arr = array();
        if ( isset($_GET['dosubmit']) ) {
            $keyword = isset($_GET['keyword']) ? safe_replace(trim($_GET['keyword'])) : '';
            if( $keyword !='' ) $where_arr[] = "concat(字段,科目,科目代码,学科,编码2,名称2) like '%{$keyword}%'";
        }
        $where = implode(' and ',$where_arr);

        $data_list = $this -> Subject_model -> get_datainfo($where,$page_no,$orderby);

        $this->view('index',array('data_list'=>$data_list,'pages'=>$this->Subject_model->pages,'keyword'=>$keyword,'require_js'=>true));
	}


    function edit($id=0)
    {
        $id = intval($id);

        $data_info =$this->Subject_model->get_one(array('id'=>$id));

        //如果是AJAX请求
        if($this->input->is_ajax_request())
        {
            if(!$data_info)exit(json_encode(array('status'=>false,'tips'=>'信息不存在')));

            $ziduan = isset($_POST["ziduan"])?trim(safe_replace($_POST["ziduan"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $kemu = isset($_POST["kemu"])?trim(safe_replace($_POST["kemu"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $kemudaima = isset($_POST["kemudaima"])?trim(safe_replace($_POST["kemudaima"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            if(!is_numeric($kemudaima))
            {
                exit(json_encode(array('status'=>false,'tips'=>'必须为数字')));
            }
            $chengbenzhongxin = isset($_POST["chengbenzhongxin"])?trim(safe_replace($_POST["chengbenzhongxin"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $xueke = isset($_POST["xueke"])?trim(safe_replace($_POST["xueke"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $kehu = isset($_POST["kehu"])?trim(safe_replace($_POST["kehu"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $zhiyuan = isset($_POST["zhiyuan"])?trim(safe_replace($_POST["zhiyuan"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $jiedaifang = isset($_POST["jiedaifang"])?trim(safe_replace($_POST["jiedaifang"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $fenluhao = isset($_POST["fenluhao"])?trim(safe_replace($_POST["fenluhao"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $hesuanxiangmmu1 = isset($_POST["hesuanxiangmmu1"])?trim(safe_replace($_POST["hesuanxiangmmu1"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $bianma1 = isset($_POST["bianma1"])?trim(safe_replace($_POST["bianma1"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $mingcheng1 = isset($_POST["mingcheng1"])?trim(safe_replace($_POST["mingcheng1"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $hesuanxiangmu2 = isset($_POST["hesuanxiangmu2"])?trim(safe_replace($_POST["hesuanxiangmu2"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $bianma2 = isset($_POST["bianma2"])?trim(safe_replace($_POST["bianma2"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $mingcheng2 = isset($_POST["mingcheng2"])?trim(safe_replace($_POST["mingcheng2"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $xuekebianma = isset($_POST["xuekebianma"])?trim(safe_replace($_POST["xuekebianma"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $xuekemingcheng = isset($_POST["xuekemingcheng"])?trim(safe_replace($_POST["xuekemingcheng"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));


            $status = $this->Subject_model->update(
                array(
                    '字段'=>$ziduan,
                    '科目'=>$kemu,
                    '科目代码'=>$kemudaima,
                    '成本中心'=>$chengbenzhongxin,
                    '学科'=>$xueke,
                    '客户'=>$kehu,
                    '职员'=>$zhiyuan,
                    '借贷方'=>$jiedaifang,
                    '分录号'=>$fenluhao,
                    '核算项目1'=>$hesuanxiangmmu1,
                    '编码1'=>$bianma1,
                    '名称1'=>$mingcheng1,
                    '核算项目2'=>$hesuanxiangmu2,
                    '编码2'=>$bianma2,
                    '名称2'=>$mingcheng2,
                    '学科编码'=>$xuekebianma,
                    '学科名称'=>$xuekemingcheng,
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
    function  abc(){

    }

    function add()
    {
        //如果是AJAX请求
        if($this->input->is_ajax_request())
        {
            //接收POST参数
            $ziduan = isset($_POST["ziduan"])?trim(safe_replace($_POST["ziduan"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $kemu = isset($_POST["kemu"])?trim(safe_replace($_POST["kemu"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $kemudaima = isset($_POST["kemudaima"])?trim(safe_replace($_POST["kemudaima"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            if(!is_numeric($kemudaima))
            {
                exit(json_encode(array('status'=>false,'tips'=>'必须为数字')));
            }
            $chengbenzhongxin = isset($_POST["chengbenzhongxin"])?trim(safe_replace($_POST["chengbenzhongxin"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $xueke = isset($_POST["xueke"])?trim(safe_replace($_POST["xueke"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $kehu = isset($_POST["kehu"])?trim(safe_replace($_POST["kehu"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $zhiyuan = isset($_POST["zhiyuan"])?trim(safe_replace($_POST["zhiyuan"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $jiedaifang = isset($_POST["jiedaifang"])?trim(safe_replace($_POST["jiedaifang"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $fenluhao = isset($_POST["fenluhao"])?trim(safe_replace($_POST["fenluhao"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $hesuanxiangmmu1 = isset($_POST["hesuanxiangmmu1"])?trim(safe_replace($_POST["hesuanxiangmmu1"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $bianma1 = isset($_POST["bianma1"])?trim(safe_replace($_POST["bianma1"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $mingcheng1 = isset($_POST["mingcheng1"])?trim(safe_replace($_POST["mingcheng1"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $hesuanxiangmu2 = isset($_POST["hesuanxiangmu2"])?trim(safe_replace($_POST["hesuanxiangmu2"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $bianma2 = isset($_POST["bianma2"])?trim(safe_replace($_POST["bianma2"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $mingcheng2 = isset($_POST["mingcheng2"])?trim(safe_replace($_POST["mingcheng2"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $xuekebianma = isset($_POST["xuekebianma"])?trim(safe_replace($_POST["xuekebianma"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $xuekemingcheng = isset($_POST["xuekemingcheng"])?trim(safe_replace($_POST["xuekemingcheng"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));

            $new_id = $this->Subject_model->insert(
                array(
                    '字段'=>$ziduan,
                    '科目'=>$kemu,
                    '科目代码'=>$kemudaima,
                    '成本中心'=>$chengbenzhongxin,
                    '学科'=>$xueke,
                    '客户'=>$kehu,
                    '职员'=>$zhiyuan,
                    '借贷方'=>$jiedaifang,
                    '分录号'=>$fenluhao,
                    '核算项目1'=>$hesuanxiangmmu1,
                    '编码1'=>$bianma1,
                    '名称1'=>$mingcheng1,
                    '核算项目2'=>$hesuanxiangmu2,
                    '编码2'=>$bianma2,
                    '名称2'=>$mingcheng2,
                    '学科编码'=>$xuekebianma,
                    '学科名称'=>$xuekemingcheng,
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
            $this->view('edit',array('is_edit'=>false,'require_js'=>true,'data_info'=>$this->Subject_model->default_info()));
        }
    }


    function delete()
    {
        if(isset($_POST))
        {
            $pidarr = isset($_POST['pid']) ? $_POST['pid'] : $this->showmessage('无效参数', HTTP_REFERER);
            $where = $this->Subject_model->to_sqls($pidarr, '', 'id');
            $status = $this->Subject_model->delete($where);
            if($status)
            {
                $this->showmessage('操作成功',base_url('adminpanel/subject/index'));
            }else
            {
                $this->showmessage('操作失败');
            }
        }
    }


}
