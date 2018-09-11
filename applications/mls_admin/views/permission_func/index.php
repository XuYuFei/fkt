<?php require APPPATH . 'views/header.php'; ?>
<div id="wrapper">
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header">权限菜单功能列表</h1>
            </div>
            <!-- /.col-lg-12 -->
        </div>
        <!-- /.row -->
        <div class="row">
            <div class="col-lg-12">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div role="grid" class="dataTables_wrapper form-inline" id="dataTables-example_wrapper">
                            <form name="search_form" method="post" action="">
                            <div class="row">
                                <div class="col-sm-6" style="width:100%">
                                    <div class="dataTables_length" id="dataTables-example_length">
                                        <label>模块
                                            <select id="module_id" name="module_id" aria-controls="dataTables-example" class="form-control input-sm" onchange="get_menu_list();">
                                                <option value="0">请选择</option>
                                                <?php foreach($module_list as $key=>$val){?>
                                                <option value="<?php echo $val['id'];?>" <?php if($val['id']==$module_id){echo "selected='selected'";} ?>><?php echo $val['name'];?></option>
                                                <?php }?>
                                            </select>
                                        </label>
                                        <label>菜单
                                            <select id="menu_id" name="menu_id" aria-controls="dataTables-example" class="form-control input-sm">
                                                <option value="0">请选择</option>
                                                <?php foreach($menu_list as $key=>$val){?>
                                                <option value="<?php echo $val['id'];?>" <?php if($val['id']==$menu_id){echo "selected='selected'";} ?>><?php echo $val['name'];?></option>
                                                <?php }?>
                                            </select>
                                        </label>
                                        <label>
                                            <div class="dataTables_length" id="dataTables-example_length">
                                                <input type="hidden" name="pg" value="1">
                                                <input class="btn btn-primary" type="submit" value="查询">
                                                <a class="btn btn-primary" href='<?php echo MLS_ADMIN_URL; ?>/permission_func/add/<?php echo $module_id; ?>/<?php echo $menu_id; ?>'>添加</a>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>

                    <!-- /.panel-heading -->

                        <table class="table table-striped table-bordered table-hover" id="dataTables-example">
                            <thead>
                                <tr>
                                    <th>序号</th>
                                    <th>权限模块</th>
                                    <th>菜单</th>
                                    <th>菜单名称</th>
                                    <th>范围</th>
                                    <th>默认权限</th>
                                    <th>是否菜单功能</th>
                                    <th>类名</th>
                                    <th>方法名</th>
                                    <th>功能</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if (isset($permission_func) && !empty($permission_func)) {
                                    foreach ($permission_func as $key => $value) {
                                        ?>
                                        <tr class="gradeA">
                                            <td><?php echo $value['id']; ?></td>
                                            <td><?php echo $value['module_name']; ?></td>
                                            <td><?php echo $value['menu_name']; ?></td>
                                            <td><?php echo $value['name']; ?></td>
                                            <td><?php echo $value['area_name']; ?></td>
                                            <td><?php if($value['init_auth']){echo "是";}else{echo "<span style='color:red'>否</span>";} ?></td>
                                            <td><?php if($value['is_menu']){echo "<span style='color:red'>是</span>";}else{echo "否";} ?></td>
                                            <td><?php echo $value['class']; ?></td>
                                            <td><?php echo $value['method']; ?></td>
                                            <td>
                                                <a href="<?php echo MLS_ADMIN_URL; ?>/permission_func/modify/<?php echo $value['id']; ?>" >修改</a>
                                                <a href="<?php echo MLS_ADMIN_URL; ?>/permission_func/del/<?php echo $value['id']; ?>" onclick="return checkdel()">删除</a>
                                            </td>
                                        </tr>
                                    <?php }
                                } ?>
                            </tbody>
                        </table>

                        <div class="row">
                            <div class="col-sm-6">
                                <div class="dataTables_paginate paging_simple_numbers" id="dataTables-example_paginate">

                                    <ul class="pagination" style="margin:-8px 0;padding-left:20px">
<?php echo page_uri($page, $pages, MLS_ADMIN_URL . '/permission_func/index'); ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.panel-body -->

        </div>
        <!-- /.panel -->
    </div>
    <!-- /.col-lg-12 -->
</div>
<!-- /.row -->



</div>
<!-- /#page-wrapper -->

</div>
<script>
    function checkdel() {
        if (confirm("确实要删除吗？"))
        {
            return true;
        }
        else
        {
            return false;
        }
    }

    //改变菜单
function get_menu_list(){
    var module_id = $("#module_id").val();
    if(module_id){
        $.ajax({
            url: "<?=MLS_ADMIN_URL?>/permission_func/get_menu_list/",
            type: "GET",
            dataType: "json",
            data: {
                module_id:module_id
            },
            success: function(data) {
                var html ="<option value='0'>请选择</option>";
                if(data['result'] == 'ok')
                {
                    var list = data['list'];
                    for(var i in list){
                        html += "<option value='"+list[i]['id']+"'>"+list[i]['name']+"</option>";
                    }
                }
                $("#menu_id").html(html);
            }
        });
    }
}
</script>
<?php require APPPATH . 'views/footer.php'; ?>

