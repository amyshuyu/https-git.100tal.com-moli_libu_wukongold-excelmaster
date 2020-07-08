<?php
/**
 * 学科（短期班）管理
 * User: fl
 * Date: 2018/1/6
 * Time: 下午4:24
 */
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Course extends Admin_Controller {

    function __construct()
    {
        parent::__construct();
        $this -> load -> model('Course_model');
    }

    function index($page_no = 1)
    {
        $orderby = $keyword = '';
        $where_arr = array();
        if (isset($_GET['dosubmit'])) {
            $keyword = isset($_GET['keyword']) ? safe_replace(trim($_GET['keyword'])) : '';
            if ($keyword != '') $where_arr[] = "concat(EAS编码,800短期班类型,EAS学科) like '%{$keyword}%'";
        }
        $where = implode(' and ', $where_arr);

        $data_list = $this -> Course_model -> get_datainfo($where,$page_no,$orderby);

        $this->view('index',array('data_list'=>$data_list,'pages'=>$this->Course_model->pages,'keyword'=>$keyword,'require_js'=>true));


    }

    function edit($id=0)
    {
        $id = intval($id);

        $data_info =$this->Course_model->get_one(array('id'=>$id));

        //如果是AJAX请求
        if($this->input->is_ajax_request())
        {
            if(!$data_info)exit(json_encode(array('status'=>false,'tips'=>'信息不存在')));

            $bianma = isset($_POST["bianma"])?trim(safe_replace($_POST["bianma"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $xiaoqu = isset($_POST["xiaoqu"])?trim(safe_replace($_POST["xiaoqu"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));
            $eas = isset($_POST["eas"])?trim(safe_replace($_POST["eas"])):exit(json_encode(array('status'=>false,'tips'=>'不能为空')));



            $status = $this->Course_model->update(
                array(
                    'EAS编码'=>$bianma,
                    '800短期班类型'=>$xiaoqu,
                    'EAS学科'=>$eas
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

            $new_id = $this->Course_model->insert(
                array(
                    'EAS编码'=>$bianma,
                    '800短期班类型'=>$xiaoqu,
                    'EAS学科'=>$eas
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
            $this->view('edit',array('is_edit'=>false,'require_js'=>true,'data_info'=>$this->Course_model->default_info()));
        }
    }


    function delete()
    {
        if(isset($_POST))
        {
            $pidarr = isset($_POST['pid']) ? $_POST['pid'] : $this->showmessage('无效参数', HTTP_REFERER);
            $where = $this->Course_model->to_sqls($pidarr, '', 'id');
            $status = $this->Course_model->delete($where);
            if($status)
            {
                $this->showmessage('操作成功', base_url('adminpanel/course/index'));
            }else
            {
                $this->showmessage('操作失败');
            }
        }
    }
}