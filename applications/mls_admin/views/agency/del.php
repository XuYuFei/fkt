<?php require APPPATH.'views/header.php'; ?>
    <div id="wrapper">
        <div id="page-wrapper">
            <div class="row">
                <div class="col-lg-12">
                    <h1 class="page-header">删除门店</h1>
                </div>
            </div>
            <?php if(1===$deleteResult){ ?>
            	<div><h1><b>删除成功</b></h1></div>
                <div><a href="/agency/index">点此返回</a></div>
            <?php }else if ($deleteResult === 2){?>
            	<div><h1><b>门店名下有经纪人，请先删除经纪人</b></h1></div>
                <div><a href="/agency/index">点此返回</a></div>
            <?php }else if ($deleteResult === 3){?>
            	<div><h1><b>系统门店无法删除</b></h1></div>
                <div><a href="/agency/index">点此返回</a></div>
            <?php } else {?>
                <div><h1><b>删除失败</b></h1></div>
                <div><a href="/agency/index">点此返回</a></div>
             <?php }?>
                </div>
            </div>
        </div>
    </div>
<?php require APPPATH.'views/footer.php'; ?>

