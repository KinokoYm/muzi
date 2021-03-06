<?php
/**
 * 网站设置管理
 */

// 载入脚本
// ========================================

  require_once dirname(__FILE__).'/../../functions.php';

// 访问控制
// ========================================

// 获取登录用户信息
  muzi_get_current_user();

// 处理表单请求
// ========================================

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  if (!empty($_POST['site_logo'])) {
    muzi_execute(sprintf('update `options` set `value` = \'%s\' where `key` = \'site_logo\'', $_POST['site_logo']));
  }
  if (!empty($_POST['site_name'])) {
    muzi_execute(sprintf('update `options` set `value` = \'%s\' where `key` = \'site_name\'', $_POST['site_name']));
  }
  if (!empty($_POST['site_description'])) {
    muzi_execute(sprintf('update `options` set `value` = \'%s\' where `key` = \'site_description\'', $_POST['site_description']));
  }
  if (!empty($_POST['site_keywords'])) {
    muzi_execute(sprintf('update `options` set `value` = \'%s\' where `key` = \'site_keywords\'', $_POST['site_keywords']));
  }

  muzi_execute(sprintf('update `options` set `value` = \'%s\' where `key` = \'comment_status\'', !empty($_POST['comment_status'])));

  muzi_execute(sprintf('update `options` set `value` = \'%s\' where `key` = \'comment_reviewed\'', !empty($_POST['comment_reviewed'])));
}

// 查询数据
// ========================================

$data = muzi_connection('select * from options');
$options = array();

foreach ($data as $item) {
  $options[$item['key']] = $item['value'];
}

  //获取页面内容
    $option = muzi_connection('select * from options');
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
  <meta charset="utf-8">
  <title><?php echo $option[2]['value'] ?></title>
  <link rel="stylesheet" href="/static/assets/vendors/bootstrap/css/bootstrap.css">
  <link rel="stylesheet" href="/static/assets/vendors/font-awesome/css/font-awesome.css">
  <link rel="stylesheet" href="/static/assets/vendors/nprogress/nprogress.css">
  <link rel="stylesheet" href="/static/assets/css/admin.css">
  <link rel="icon" href="<?php echo $option[1]['value'] ?>" type="image/x-icon" />
  <script src="/static/assets/vendors/nprogress/nprogress.js"></script>
</head>
<body>
  <script>NProgress.start()</script>

  <div class="main">
    
    <?php include '../inc/navbar.php' ?>

    <div class="container-fluid">
      <div class="page-title">
        <h1>网站设置</h1>
      </div>
      <form class="form-horizontal" action="/admin/setting/settings.php" method="post">
        <div class="form-group">
          <label for="site_logo" class="col-sm-2 control-label">网站图标</label>
          <div class="col-sm-6">
            <input id="site_logo" name="site_logo" type="hidden">
            <label class="form-image">
              <input id="upload" type="file">
              <img src="<?php echo $options['site_logo']; ?>">
              <i class="mask fa fa-upload"></i>
            </label>
          </div>
        </div>
        <div class="form-group">
          <label for="site_name" class="col-sm-2 control-label">站点名称</label>
          <div class="col-sm-6">
            <input id="site_name" name="site_name" class="form-control" type="type" value="<?php echo $options['site_name']; ?>" placeholder="站点名称">
          </div>
        </div>
        <div class="form-group">
          <label for="site_description" class="col-sm-2 control-label">站点描述</label>
          <div class="col-sm-6">
            <textarea id="site_description" name="site_description" class="form-control" placeholder="站点描述" cols="30" rows="6"><?php echo $options['site_description']; ?></textarea>
          </div>
        </div>
        <div class="form-group">
          <label for="site_keywords" class="col-sm-2 control-label">站点关键词</label>
          <div class="col-sm-6">
            <input id="site_keywords" name="site_keywords" class="form-control" type="type" value="<?php echo $options['site_keywords']; ?>" placeholder="站点关键词">
          </div>
        </div>
        <div class="form-group">
          <label class="col-sm-2 control-label">评论</label>
          <div class="col-sm-6">
            <div class="checkbox">
              <label><input id="comment_status" name="comment_status" type="checkbox"<?php echo $options['comment_status'] ? ' checked' : ''; ?>>开启评论功能</label>
            </div>
            <div class="checkbox">
              <label><input id="comment_reviewed" name="comment_reviewed" type="checkbox"<?php echo $options['comment_reviewed'] ? ' checked' : ''; ?>>评论必须经人工批准</label>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="col-sm-offset-2 col-sm-6">
            <button type="submit" class="btn btn-primary">保存设置</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <?php $current_page = 'settings'; ?>
  <?php include '../inc/sidebar.php'; ?>

  <script src="/static/assets/vendors/jquery/jquery.js"></script>
  <script src="/static/assets/vendors/bootstrap/js/bootstrap.js"></script>
  <script>
    $(function () {
      // 异步上传文件
      $('#upload').on('change', function () {
        // 选择文件后异步上传文件
        var formData = new FormData()
        formData.append('file', $(this).prop('files')[0])

        // 上传图片
        $.ajax({
          url: '/admin/avatar/upload.php',
          cache: false,
          contentType: false,
          processData: false,
          data: formData,
          type: 'post',
          success: function (res) {
            if (res.success) {
              $('#site_logo').val(res.data)
              $('#upload').siblings('img').attr('src', res.data).fadeIn()
            } else {
              alert('上传文件失败')
            }
          }
        })
      })
    })
  </script>
  <script>NProgress.done()</script>
</body>
</html>
