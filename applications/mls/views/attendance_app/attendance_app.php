<script>
    window.parent.addNavClass(10);
</script>
<script src="<?php echo MLS_SOURCE_URL;?>/min/?f=common/third/My97DatePicker/WdatePicker.js"></script>
<div class="tab_box" id="js_tab_box">
    <?php if(isset($user_menu) && $user_menu != ''){ echo $user_menu;}?>
</div>

<form name="search_form" id="search_form" method="post" action="<?php echo MLS_URL;?>/attendance_app/index">
<input type="hidden" name="is_submit" value="1">
<div class="search_box clearfix" id="js_search_box">
    <div class="fg_box">
		<p class="fg fg_tex">年份：</p>
        <div class="fg">
			<select class="select" name='year' id='year' onchange='check_date(1);'>
				<!-- <option value="0">不限</option> -->
					<?php
						for ( $i = 2016; $i <= date('Y',time()) ; $i++  ) {
					?>
						<option value="<?php echo $i;?>" <?=($post_param['year']==$i || $year == $i)?'selected':''?>><?php echo $i;?></option>
					<?php } ?>
			</select>
		</div>
        <p class="fg fg_tex">月份：</p>
        <div class="fg">
			<select class="select" name='month' id='month' onchange='check_date(0);'>
				<!-- <option value="0">不限</option> -->
					<?php
						for ( $i = 1; $i <= 12; $i++  ) {
					?>
						<option value="<?php echo $i;?>" <?=($post_param['month']==$i || $month == $i)?'selected':''?>><?php echo $i;?></option>
					<?php } ?>
			</select>
		</div>
		<p class="fg fg_tex">日份：</p>
        <div class="fg">
			<select class="select" name='day' id='day' onchange='check_date(1);'>
				<!-- <option value="0">不限</option> -->
					<?php
						for ( $i = 1; $i <= date('t',time()); $i++  ) {
					?>
						<option value="<?php echo $i;?>" <?=($post_param['day']==$i || $day == $i)?'selected':''?>><?php echo $i;?></option>
					<?php } ?>
			</select>&nbsp;&nbsp;<span style="font-weight:bold;color:red;" id="year_month"></span>
		</div>		
		<p class="fg fg_tex">门店：</p>
        <div class="fg">
			<select class="select" name="agency_id" id="agency_id" onchange="chang('check_work')">
				<!--<option value="0">不限</option>-->
					<?php if($agency_list){
						foreach($agency_list as $key=>$val){
						?>
						<option <?php if($val['agency_id'] == $post_param['agency_id'] || ($val['agency_id']==$agency_id && $post_param['broker_id'] == ''))
                                echo "selected"; ?> value="<?php echo $val['agency_id'];?>"><?php echo $val['agency_name'];?></option>
					<?php }}?>
			</select>
		</div>
		<p class="fg fg_tex">人员：</p>
        <div class="fg">
			<select class="select" name="broker_id" id="broker_id" onchange='check_date(2);'>
				<option value="0">不限</option>
					<?php if($broker_list){ ?>
						<?php foreach($broker_list as $key=>$val){ ?>
						<option  <?php if($val['broker_id'] == $post_param['broker_id'] ||($val['broker_id']==$broker_id && $post_param['broker_id'] == ''))
                                echo "selected"; ?> value='<?php echo $val['broker_id']?>'><?php echo $val['truename']?></option>
					<?php }}?>
			</select>&nbsp;&nbsp;<span style="font-weight:bold;color:red;" id="agency_broker"></span>
		</div>
    </div>
    <div class="fg_box">
        <div class="fg"> <a href="javascript:void(0);" class="btn"><span class="btn_inner" onclick="form_submit();return false;">搜索</span></a> </div>
        <div class="fg"> <a href="javascript:void(0);" onclick="$('#search_form').attr('action', '/attendance_app/exportAttendance/');form_submit();$('#search_form').attr('action', '');return false;" class="btn"><span class="btn_inner">导出(日)</span></a> </div>
        <div class="fg"> <a href="javascript:void(0);" onclick="$('#search_form').attr('action', '/attendance_app/exportAllAttendance/');form_submit();$('#search_form').attr('action', '');return false;" class="btn"><span class="btn_inner">导出(月)</span></a> </div>
        <div class="fg"> <a href="/attendance_app/attendance_app/" class="reset">重置</a> </div>
    </div>
</div>
<div class="table_all">
    <div class="title shop_title" id="js_title">
        <table class="table">
            <tr>
                <td class="c5"><div class="info">序号</div></td>
                <td class="c10"><div class="info">姓名</div></td>
              	<td class="c10"><div class="info">所在部门</div></td>
              	<td class="c10"><div class="info">上午打卡</div></td>
              	<td class="c10"><div class="info">定位位置</div></td>
              	<td class="c15"><div class="info">备注</div></td>
              	<td class="c10"><div class="info">下午打卡</div></td>
              	<td class="c10"><div class="info">定位位置</div></td>
              	<td class="c15"><div class="info">备注</div></td>
            </tr>
        </table>
    </div>
    <div class="inner shop_inner" id="js_inner">
        <table class="table">
		<?php
		if(is_full_array($broker_info)){
			foreach($broker_info as $key=>$vo){?>
			<tr onclick = "$('#js_details_pop .iframePop').attr('src','/attendance_app/details/<?php echo $vo['broker_id'];?>/<?=$year?>/<?=$month?>');openWin('js_details_pop');">
                <td class="c5"><div class="info"><?=($key+1)?></div></td>
                <td class="c10"><div class="info"><?=$vo['broker_name']?></div></td>
              	<td class="c10"><div class="info"><?=$vo['agency_name']?></div></td>
              	<td class="c10"><div class="info"><?=$vo['attendance_am']?></div></td>
              	<td class="c10"><div class="info"><?=$vo['position_am']?></div></td>
              	<td class="c15" title="<?=$vo['remarks_am']?>"><div class="info"><?php if (strlen($vo['remarks_am'])>15) {
              		echo mb_substr($vo['remarks_am'],0,15,'utf-8').' ......';
              	}else{
              		echo $vo['remarks_am'];
              	}?></div></td>
              	<td class="c10"><div class="info"><?=$vo['attendance_pm']?></div></td>
              	<td class="c10"><div class="info"><?=$vo['position_pm']?></div></td>
              	<td class="c15" title="<?=$vo['remarks_pm']?>"><div class="info"><?php if (strlen($vo['remarks_pm'])>15) {
              		echo mb_substr($vo['remarks_pm'],0,15,'utf-8').' ......';
              	}else{
              		echo $vo['remarks_pm'];
              	}?></div></td>
            </tr>
		<?php }}else{?>
			<tr><td><span class="no-data-tip">抱歉，没有找到符合条件的信息</span></td></tr>
		<?php } ?>
        </table>
    </div>
</div>
<div id="js_fun_btn" class="fun_btn clearfix">
	<!--<form action="" name="search_form" method="post" id="subform">-->
	<div class="get_page">
		<?php if(isset($page_list) && $page_list != ''){ echo $page_list;}?>
	</div>
    <!--</form>-->
</div>
</form>

<!--考勤详情弹窗-->
<div id="js_details_pop" class="iframePopBox" style="width:600px; height:440px;display:none">
    <a class="JS_Close close_pop iconfont" href="javascript:void(0)" date-iframe="1">&#xe60c;</a>
    <iframe frameborder="0" scrolling="auto" width="600" height="440" class='iframePop' src=""></iframe>
</div>

<script>

//关闭考勤详情弹窗
function close_detail(){
	$('#js_details_pop').css('display','none');
	$("#GTipsCovermainloading").remove();
	//$("#GTipsCoverjs_details_pop").css('display','none');
	$("#GTipsCoverjs_details_pop").remove();
}

//公司员工js触发类
function chang(type){
	var agency_id=$("select[name='agency_id']").val();
	$.ajax({
		url: "<?php echo MLS_URL;?>/"+type+"/broker_list/",
		type: "GET",
		dataType: "json",
		data:{agency_id: agency_id},
		success:function(data_list){
			var str_html='<option value="0">不限</option>';
			if(agency_id>0){
				for(var i=0;i<data_list.length;i++){
					str_html +='<option value='+data_list[i].broker_id+'>'+data_list[i].truename+'</option>';
				}
			}
			$("#broker_id").empty().html(str_html);
		}
	});

}
/*
*	aim:	年月等 onblur 事件的校验
*	author: angel_in_us
*	date:	2015.03.04
*/
function check_date(type){
	$("input[name='page']").val('1');//初始化page

	var year      =    $("#year option:selected").val();	//年
	var month     =    $("#month option:selected").val();	//月
	var day       =    $("#day option:selected").val();	//日
	var agency_id =    $("#agency_id option:selected").val();	//门店
	var broker_id =    $("#broker_id option:selected").val();	//人员

	//alert(year);return false;
	if(!year && !month && !day){
		$("#year_month").html("");
		$("input[name='is_submit']").val('1');
	}else if(year!=0 && month!=0 && day!=0){
		$("#year_month").html("");
		$("input[name='is_submit']").val('1');
	}else{
		$("#year_month").html("请选择年月日！");
		$("input[name='is_submit']").val('0');
	}
	if (type == 0) {
		var date = new Date(year,month,0);
		var t = date.getDate();
		var str_html = '';
		for (var i = 1; i <= t; i++) {
			if ( <?php echo $post_param['day']?$post_param['day']:0;?> == i || <?php echo $day?$day:0;?> == i ) {
				str_html +='<option value='+i+' selected>'+i+'</option>';
			}else{
				str_html +='<option value='+i+'>'+i+'</option>'; 
			}
		}
		$("#day").empty().html(str_html);		
	}
	// if(type == 1){

	// }
}

//通过参数判断是否可以被提交
function form_submit(){
	var is_submit = $("input[name='is_submit']").val();
	if(is_submit ==1){
		$('#search_form').submit();
	}
}

</script>
<img src="<?php echo MLS_SOURCE_URL;?>/mls/images/v1.0/009.gif" id="mainloading" ><!--遮罩 loading-->


