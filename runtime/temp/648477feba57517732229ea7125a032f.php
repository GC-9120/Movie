<?php if (!defined('THINK_PATH')) exit(); /*a:3:{s:59:"/www/wwwroot/maccms/application/admin/view/wxapi/index.html";i:1603762191;s:59:"/www/wwwroot/maccms/application/admin/view/public/head.html";i:1603093946;s:59:"/www/wwwroot/maccms/application/admin/view/public/foot.html";i:1603093946;}*/ ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <title><?php echo $title; ?> - 请勿泄露后台地址 - Copyright by 苹果CMS内容管理系统</title>
    <link rel="stylesheet" href="/static/layui/css/layui.css?v=1024">
    <link rel="stylesheet" href="/static/css/admin_style.css?v=1024">
    <script type="text/javascript" src="/static/js/jquery.js?v=1024"></script>
    <script type="text/javascript" src="/static/layui/layui.js?v=1024"></script>
    <script>
        var ROOT_PATH="",ADMIN_PATH="<?php echo $_SERVER['SCRIPT_NAME']; ?>", MAC_VERSION='v10';
    </script>
</head>
<body>

<div class="page-container p10">
    <div class="my-toolbar-box">

        <div class="layui-btn-group">
            <a data-href="<?php echo url('info'); ?>" data-full="1" class="layui-btn layui-btn-primary j-iframe"><i class="layui-icon">&#xe654;</i>添加微信</a>
        </div>

    </div>

    <form class="layui-form " method="post" id="pageListForm">
        <table class="layui-table" lay-size="sm">
            <thead>
            <tr>
                <th width="25"><input type="checkbox" lay-skin="primary" lay-filter="allChoose"></th>
                <th width="80">平台</th>
                <th width="80">标识</th>
                <th width="150">名称</th>
                <th width="80">所属</th>
                <th width="100">操作</th>
            </tr>
            </thead>
            <?php if(is_array($list) || $list instanceof \think\Collection || $list instanceof \think\Paginator): $i = 0; $__LIST__ = $list;if( count($__LIST__)==0 ) : echo "" ;else: foreach($__LIST__ as $key=>$vo): $mod = ($i % 2 );++$i;?>
            <tr>
                <td><input type="checkbox" name="ids[]" value="<?php echo $vo['name']; ?>" class="layui-checkbox checkbox-ids" lay-skin="primary"></td>
                 <td><?php echo $vo['app']; ?></td>
                <td><?php echo $vo['name']; ?></td>
                <td><?php echo $vo['des']; ?></td>
                <td><?php echo $vo['admin']; ?></td>
                <td>
                    <!--<a class="layui-badge-rim j-iframe" data-href="<?php echo url('info'); ?>?id=<?php echo rawurlencode($vo['name']); ?>" href="javascript:;" title="编辑">编辑</a>-->
                    <a class="layui-badge-rim j-iframe" data-full="1" data-href="<?php echo url($vo['info']); ?>?id=<?php echo rawurlencode($vo['name']); ?>" href="javascript:;" title="编辑">编辑</a>
                    <a class="layui-badge-rim j-tr-del" data-href="<?php echo url('del'); ?>?ids=<?php echo rawurlencode($vo['name']); ?>" href="javascript:;" title="删除">删除</a>
                </td>
            </tr>
            <?php endforeach; endif; else: echo "" ;endif; ?>
            </tbody>
        </table>

    </form>
</div>
<script type="text/javascript" src="/static/js/admin_common.js"></script>

<script type="text/javascript">

</script>
</body>
</html>