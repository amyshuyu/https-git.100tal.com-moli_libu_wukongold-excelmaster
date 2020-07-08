<?php defined('BASEPATH') or exit('No direct script access allowed.'); ?>
<?php echo template('public','header_view',array('date'=>$date))?>
 <div class="site-wrapper">

      <div class="site-wrapper-inner">

        <div class="cover-container">

          <div class="masthead clearfix">
            <div class="inner">
              <h3 class="masthead-brand">励步教育——财务凭证系统</h3>
              <nav>
                <ul class="nav masthead-nav">
                  <li class="active"><a href="<?php echo site_url()?>">首页</a></li>
                </ul>
              </nav>
            </div>
          </div>

          <div class="inner cover">
            <h1 class="cover-heading">欢迎使用{title}</h1>
            <?php  if (version_compare(PHP_VERSION, '5.3.0') <= 0) :?>
            <p class="lead">很抱歉 ACI 要求最低PHP版本不能小于PHP 5.3</p>
            <?php else:?>
              <?php if(!isRewriteMod()&&function_exists('apache_get_version')): ?>
                <p class="lead">很抱歉您当前环境未开始 mod_rewrite </p>
              <?php elseif(site_url()!=curPageURL() && false ):?>
                <p class="lead"> 安装说中的第一条不正确 ， 请修改 application/config/config.php 中的 $config['base_url'] = '<?php echo curPageURL()?>'; </p>
              <?php elseif($cache_chomd<755):?>
                <p class="lead"> application/cache 文件夹 权限不够，要求权限>=755以上 </p>
              <?php else:?>
                <p class="lead">

                  <a href="<?php echo site_url('adminpanel')?>" class="btn btn-lg btn-default">进入后台管理</a>
                </p>
              <?php endif;?>
            <?php endif;?>

          </div>

          <div class="mastfoot">
            <div class="inner">
              <p>励步教育</p>
            </div>
          </div>

        </div>

      </div>

    </div>
<?php echo template('public','footer_view')?>
