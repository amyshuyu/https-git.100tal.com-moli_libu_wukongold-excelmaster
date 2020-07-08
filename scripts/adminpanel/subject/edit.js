define(function (require) {
	var $ = require('jquery');
	var aci = require('aci');
	require('bootstrap');
	require('jquery-ui-dialog-extend');
	require('bootstrapValidator');
	require('message');


	var validator_config = {
		message: '输入框不能为空',
		feedbackIcons: {
			valid: 'glyphicon glyphicon-ok',
			invalid: 'glyphicon glyphicon-remove',
			validating: 'glyphicon glyphicon-refresh'
		},
		fields: {
				ziduan: {
					validators: {
						notEmpty: {
							message: '请输入凭证字段信息'
						}
					}
				},
				kemu: {
					validators: {
						notEmpty: {
							message: '请输入凭证科目信息'
						}
					}
				},
				kemudaima: {
					validators: {
						notEmpty: {
							message: '请输入凭证科目代码'
						},
						regexp: {
							regexp: /^[0-9\.]+$/,
							message: '科目代码全为数字'
						}
					}
				},
				jiedaifang:{
					validators: {
						notEmpty: {
							message: '请输入借贷方信息'
						}
					}
				},
                fenluhao:{
					validators: {
						notEmpty: {
							message: '请输入分录号信息'
						},
						regexp: {
							regexp: /^[0-9\.]+$/,
							message: '科目代码全为数字'
						}
					}
				},
			}
	};

	if(edit){
		var validator_config = {
			message: '输入框不能为空',
			feedbackIcons: {
				valid: 'glyphicon glyphicon-ok',
				invalid: 'glyphicon glyphicon-remove',
				validating: 'glyphicon glyphicon-refresh'
			},
			fields: {
				ziduan: {
					validators: {
						notEmpty: {
							message: '请输入凭证字段信息'
						}
					}
				},
				kemu: {
					validators: {
						notEmpty: {
							message: '请输入凭证科目信息'
						}
					}
				},
				kemudaima: {
					validators: {
						notEmpty: {
							message: '请输入凭证科目代码'
						},
						regexp: {
							regexp: /^[0-9\.]+$/,
							message: '科目代码全为数字'
						}
					}
				},
				jiedaifang:{
					validators: {
						notEmpty: {
							message: '请输入借贷方信息'
						}
					}
				},
                fenluhao:{
					validators: {
						notEmpty: {
							message: '请输入分录号信息'
						},
						regexp: {
							regexp: /^[0-9\.]+$/,
							message: '科目代码全为数字'
						}
					}
				},
			}
		};
	}
	$('#validateform').bootstrapValidator(validator_config).on('success.form.bv', function(e) {
		e.preventDefault();

		$("#dosubmit").attr("disabled","disabled");


		$.scojs_message('请稍候...', $.scojs_message.TYPE_WAIT);
		$.ajax({
			type: "POST",
			url: edit?SITE_URL+folder_name+"/subject/edit/"+id:SITE_URL+folder_name+"/subject/add/",
			data:  $("#validateform").serialize(),
			success:function(response){
				var dataObj=jQuery.parseJSON(response);
				if(dataObj.status)
				{
					$.scojs_message('操作成功,3秒后将返回列表页...', $.scojs_message.TYPE_OK);
					aci.GoUrl(SITE_URL+folder_name+'/subject/index/',1);
				}else
				{
					$.scojs_message(dataObj.tips, $.scojs_message.TYPE_ERROR);
					$("#dosubmit").removeAttr("disabled");
				}
			},
			error: function (request, status, error) {
				$.scojs_message(request.responseText, $.scojs_message.TYPE_ERROR);
				$("#dosubmit").removeAttr("disabled");
			}
		});

	}).on('error.form.bv',function(e){ $.scojs_message('带*号不能为空', $.scojs_message.TYPE_ERROR);$("#dosubmit").removeAttr("disabled");});

});