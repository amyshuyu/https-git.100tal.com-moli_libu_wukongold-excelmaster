<?php defined('BASEPATH') or exit('No direct script access allowed.'); ?>
<form class="form-horizontal" role="form" id="validateform" name="validateform" action="<?php echo current_url()?>" >
<div class='panel panel-default'>
	<div class='panel-heading'>
		<i class='icon-edit icon-large'></i>
		<?php echo $is_edit?"修改":"新增"?>凭证校区信息
		<div class='panel-tools'>

			<div class='btn-group'>
				<?php aci_ui_a($folder_name,'school','index','',' class="btn  btn-sm "','<span class="glyphicon glyphicon-arrow-left"></span> 返回')?>
			</div>
		</div>
	</div>
	<div class='panel-body'>
		<fieldset>
				<legend>校区信息</legend>

					<div class="form-group">
						<label class="col-sm-2 control-label">编码</label>
						<div class="col-sm-4">
							<input type="text" name="bianma"  id="bianma"  class="form-control validate[required]"  placeholder="保留为空，信息不修改" size="45" value="<?php echo $data_info['编码']?>">
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">800校区</label>
						<div class="col-sm-4">
						  <input name="xiaoqu" type="text" class="form-control validate[required]" id="xiaoqu" placeholder="保留为空，信息不修改" value="<?php echo $data_info['800校区']?>" size="45" />
						</div>
					</div>
					<div class="form-group">
						<label  class="col-sm-2 control-label">EAS成本中心</label>
						<div class="col-sm-4">
						  <input name="eas" type="text" class="form-control validate[required]" id="eas" placeholder="保留为空，信息不修改" value="<?php echo $data_info['EAS成本中心']?>" size="45" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">公司</label>
						<div class="col-sm-4">
						  <input name="gongsi" type="text" class="form-control validate[required]" value="<?php echo $data_info['公司']?>" id="gongsi" placeholder="保留为空，信息不修改" size="45" />
						</div>
					</div>
					
			</fieldset>



		<div class='form-actions'>
			<?php aci_ui_button($folder_name,'school','edit',' type="submit" id="dosubmit" class="btn btn-primary " ','保存')?>
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