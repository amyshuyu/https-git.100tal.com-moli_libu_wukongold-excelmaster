<?php defined('BASEPATH') or exit('No direct script access allowed.'); ?>
<form class="form-horizontal" role="form" id="validateform" name="validateform" action="<?php echo current_url()?>" >
<div class='panel panel-default'>
	<div class='panel-heading'>
		<i class='icon-edit icon-large'></i>
		<?php echo $is_edit?"修改":"新增"?>学科信息
		<div class='panel-tools'>

			<div class='btn-group'>
				<?php aci_ui_a($folder_name,'course','index','',' class="btn  btn-sm "','<span class="glyphicon glyphicon-arrow-left"></span> 返回')?>
			</div>
		</div>
	</div>
	<div class='panel-body'>
		<fieldset>
				<legend>学科信息</legend>

					<div class="form-group">
						<label class="col-sm-2 control-label">EAS编码</label>
						<div class="col-sm-4">
							<input type="text" name="bianma"  id="bianma"  class="form-control validate[required]"  placeholder="保留为空，信息不修改" size="45" value="<?php echo $data_info['EAS编码']?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">800短期班类型</label>
						<div class="col-sm-4">
						  <input name="xiaoqu" type="text" class="form-control validate[required]" id="xiaoqu" placeholder="保留为空，信息不修改" value="<?php echo $data_info['800短期班类型']?>" size="45" />
						</div>
					</div>
					<div class="form-group">
						<label  class="col-sm-2 control-label">EAS学科</label>
						<div class="col-sm-4">
						  <input name="eas" type="text" class="form-control validate[required]" id="eas" placeholder="保留为空，信息不修改" value="<?php echo $data_info['EAS学科']?>" size="45" />
						</div>
					</div>
					
			</fieldset>



		<div class='form-actions'>
			<?php aci_ui_button($folder_name,'course','edit',' type="submit" id="dosubmit" class="btn btn-primary " ','保存')?>
		</div>
     </div>

</form>
<script language="javascript" type="text/javascript">

	var id = <?php echo $data_info['id']?>;
	var edit= <?php echo $is_edit?"true":"false"?>;
	var folder_name = "<?php echo $folder_name?>";


	require(['<?php echo SITE_URL?>scripts/common.js'], function (common) {
		require(['<?php echo SITE_URL?>scripts/<?php echo $folder_name?>/<?php echo $controller_name?>/edit.js']);
	});
</script>