<?php require APPPATH . 'views/header.php'; ?>
<link href="<?= MLS_SOURCE_URL ?>/min/?f=mls/third/iconfont/iconfont.css" rel="stylesheet" type="text/css">
<div id="wrapper">
    <div id="page-wrapper">
        <div class="row">
            <div class="col-lg-12">
                <h1 class="page-header"><?php echo $title; ?></h1>
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
                                            <label>
                                                <input type="text" name="company_name" id="company_name" value="<?php echo isset($param['company_name']) ? $param['company_name'] : ''; ?>" placeholder="请输入公司名" class="form-control"/>
                                                <input type="text" name="agency_name" id="agency_name" value="<?php echo isset($param['agency_name']) ? $param['agency_name'] : ''; ?>" placeholder="请输入门店名" class="form-control"/>
                                            </label>
                                            <label>
                                                <div class="dataTables_length" id="dataTables-example_length">
                                                    <input type="hidden" name="pg" value="1">
                                                    <input class="btn btn-primary" type="submit" value="查询">
                                                    <a class="btn btn-primary"
                                                       href='<?php echo MLS_ADMIN_URL; ?>/call/index/add/'>添加</a>
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
                                <th>公司名称</th>
                                <th>分店名称</th>
                                <th>电话</th>
                                <th>开通号码总数</th>
                                <th>申请号码总数</th>
                                <th>总月租费用</th>
                                <th>添加时间</th>
                                <th>账户余额</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php
                            if (isset($agency_list) && !empty($agency_list)) {
                                foreach ($agency_list as $key => $value) {
                                    ?>
                                    <tr class="gradeA">
                                        <td><?php echo $value['id']; ?></td>
                                        <td><?php echo $value['company_name']; ?></td>
                                        <td><?php echo $value['agency_name']; ?></td>
                                        <td><?php echo $value['telno']; ?></td>
                                        <td><?php echo $value['phone_num']; ?></td>
                                        <td><?php echo $value['all_phone_num']; ?></td>
                                        <td><?php echo $value['monthly_fee']; ?></td>
                                        <td><?php echo date('Y-m-d H:i:s', $value['create_time']); ?></td>
                                        <td><?php echo $value['balance']; ?></td>
                                        <td>
                                            <a href="<?php echo MLS_ADMIN_URL; ?>/call/apply/index?id=<?php echo $value['id']; ?>">使用号码个数管理</a>&nbsp;&nbsp;
                                            <a href="<?php echo MLS_ADMIN_URL; ?>/call/index/balance?id=<?php echo $value['id']; ?>">资金明细</a>
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
                                        <?php echo page_uri($page, $pages); ?>
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
        if (confirm("删除加入区域公盘的门店，将同时删除门店发送到公盘的房源，确实要删除吗？")) {
            return true;
        }
        else {
            return false;
        }
    }
</script>
<?php require APPPATH . 'views/footer.php'; ?>

