<div class="contract-wrap clearfix">
<div class="tab-left"><?=$user_tree_menu?></div>
<div class="forms_scroll h90">
    <div class="contract_top_main">
        <div class="i_box">
        	<div class="clearfix">
		    <h4 class="h4">合同信息</h4>
		    <a class="btn_l" href="javascript:void(0)" id="return_last" onclick="location.href='/contract/contract_list/<?php echo $contract['type']; ?>';return false;">&lt;&lt;返回</a>
            <?php if($contract['is_check'] != 2){?><a class="btn_l" href="javascript:void(0);" <?php if($auth['edit']['auth']){?>onclick="location.href='/contract/modify_contract/1/<?=$contract['id'];?>';return false;"<?php }else{?>onclick="permission_none();"<?php }?>>编辑</a><?php }?>
		</div>
		<div class="t_item clearfix contract_mess">
		    <p class="item w235"><span class="tex">合同编号：</span><a href="javascript:void(0)"><?=$contract['number'];?></a></p>
		    <p class="item w260"><span class="tex">房源编号：</span><a href="javascript:void(0)" onclick="$('#js_pop_box_g .iframePop').attr('src','/sell/details_house/<?=substr($contract['house_id'],2);?>/4');openWin('js_pop_box_g');"><?=$contract['house_id'];?></a></p>
		    <p class="item w267"><span class="tex">楼盘名称：</span><?=$contract['block_name'];?></p>
		    <p class="item wauto"><span class="tex">房源面积：</span><b style="font-weight:bold;"><?=strip_end_0($contract['buildarea']);?></b>m²</p>
		</div>
		<div class="t_item clearfix contract_mess">
		    <p class="item w500"><span class="tex">房源地址：</span><a href="javascript:void(0)"><?=$contract['house_addr'];?></a></p>
		    <p class="item w267"><span class="tex">物业类型：</span><?=$base_config['sell_type'][$contract['sell_type']];?></p>
		    <p class="item float:left;"><span class="tex">签约日期：</span><?=date('Y-m-d',$contract['signing_time']);?></p>
		</div>
		<div class="t_item clearfix contract_mess">
		    <p class="item w235"><span class="tex">交易方式：</span><?=$contract['type']==1?'出售':'出租';?></p>
		    <p class="item w260"><span class="tex">成交金额：</span><strong><?=strip_end_0($contract['price']);?>元</strong></p>
		    <p class="item w267"><span class="tex">合作房源：</span><?=$contract['is_cooperate']==1?'是':'否';?></p>
            <?php if ($contract['is_cooperate']==1) { ?>
		    <p class="item wauto"><span class="tex">合作编号：</span><?=$contract['order_sn'];?></p>
            <?php } ?>
		</div>
        </div>

        <!--卖方信息-->
        <dl class="sale_message">
            <dd>
               <img src="<?php echo MLS_SOURCE_URL;?>/mls/images/v1.0/saler_03.png" />
                <p>卖方信息</p>
            </dd>
            <dt>
                <div>
                    <p><span class="input_add_F" style="width:80px;text-align:right;">业主姓名：</span><b><?=$contract['owner']?></b></p>
                    <p><span class="input_add_F" style="width:80px;text-align:right;">联系方式：</span><b><?=$contract['owner_tel']?></b></p>
                    <p><span class="input_add_F" style="width:80px;text-align:right;">身份证号：</span><b><?=$contract['owner_idcard']?></b></p>
                </div>
                <div>
                    <p><span class="input_add_F" style="width:80px;text-align:right;">签约门店：</span><b><?=$contract['agency_name_a']?></b></p>
                    <p><span class="input_add_F" style="width:80px;text-align:right;">签约人：</span><b><?=$contract['broker_name_a']?></b></p>
                    <p><span class="input_add_F" style="width:80px;text-align:right;">联系方式：</span><b><?=$contract['broker_tel_a']?></b></p>
                </div>
            </dt>
        </dl>
        <!--买方信息-->
        <dl class="sale_message" style="border-bottom:1px solid #ededed;">
            <dd>
               <img src="<?php echo MLS_SOURCE_URL;?>/mls/images/v1.0/buyer_06.png" />
                <p>买方信息</p>
            </dd>
            <dt>
                <div>
                    <p><span class="input_add_F" style="width:80px;text-align:right;">客源编号：</span><b><?=$contract['customer_id']?></b></p>
                    <p><span class="input_add_F" style="width:80px;text-align:right;">业主姓名：</span><b><?=$contract['customer']?></b></p>
                    <p><span class="input_add_F" style="width:80px;text-align:right;">联系方式：</span><b><?=$contract['customer_tel']?></b></p>
                    <p><span class="input_add_F" style="width:80px;text-align:right;">身份证号：</span><b><?=$contract['customer_idcard']?></b></p>
                </div>
                <div>
                    <p><span class="input_add_F" style="width:80px;text-align:right;">签约门店：</span><b><?=$contract['agency_name_b']?></b></p>
                    <p><span class="input_add_F" style="width:80px;text-align:right;">签约人：</span><b><?=$contract['broker_name_b']?></b></p>
                    <p><span class="input_add_F" style="width:80px;text-align:right;" >联系方式：</span><b><?=$contract['broker_tel_b']?></b></p>
                </div>

            </dt>
        </dl>
        <div style="clear:both;"></div>
        <!--佣金结算-->
        <div class="sale_message_commission" style="margin-top:0;margin-bottom:0;">

            <h4 class="h4 padding_size" style="float:none;display:block;padding:12px 26px 0 26px;">佣金结算</h4>

            <div class="sale_message_commission_detial" >
                <p><b>付款方式：</b><strong><?=$config['buy_type_s'][$contract['buy_type_s']];?></strong></p>
                <p><b>客户首付：</b><strong><?=strip_end_0($contract['shoufu']);?>元</strong></p>
                <p><b>客户贷款金额：</b><strong><?=strip_end_0($contract['loan']);?>元</strong></p>
                <p><b>税收支付：</b><strong><?=$config['tax_pay_type'][$contract['tax_pay_type']];?></strong></p>

                <p><b>营业税：</b><strong><?=$contract['business_tax']=='0'?'无':'是'?></strong></p>
                <p><b>个税：</b><strong><?=$contract['tax']=='0'?'无':'是'?></strong></p>
                <p><b>业主税费合计：</b><strong><?=strip_end_0($contract['owner_tax_total']);?>元</strong></p>
                <p><b>客户税费合计：</b><strong><?=strip_end_0($contract['customer_tax_total']);?>元</strong></p>

                <p><b>业主应付佣金：</b><strong><?=strip_end_0($contract['owner_commission']);?>元</strong></p>
                <p><b>客户应付佣金：</b><strong><?=strip_end_0($contract['customer_commission']);?>元</strong></p>
                <p><b>其他收入：</b><strong><?=strip_end_0($contract['other_income']);?>元</strong></p>
                <p><b>佣金总计收入：</b><strong><?=strip_end_0($contract['commission_total']);?>元</strong></p>
                <dl>
                      <dd>合同备注：</dd>
                      <dt><?=$contract['remarks'];?></dt>
                </dl>
                <?php if(in_array($contract['is_check'],array(2,3))){?>
                <dl>
                      <dd>审核意见：</dd>
                      <dt class="colr_rest"><b>【<?=$contract['check_agency'];?>-<?=$contract['check_broker'];?>】</b><?=$contract['check_remark'];?></dt>
                </dl>
                <?php }?>
            </div>
        </div>
        <div style="clear:both;"></div>
        <!--合同细节-->
        <div id="js_search_box" class="shop_tab_title  scr_clear top_Marign" style="float:left;display:inline;width:99%;padding-right:1%;background:#FFF;padding-top:10px;margin:0;">
            <!--            <a href="javascript:void(0);" class="contract_filing link link_on" id="warrant_step" data="/contract/contract_warrant_manage/-->
            <? //=$contract['id'];?><!--">权证流程<span class="iconfont hide"></span></a>-->
            <a href="javascript:void(0);" class="link contract_filing link_on" id="contract_divide"
               data="/contract/contract_divide_manage/<?= $contract['id']; ?>">业绩分成<span class="iconfont hide"></span></a>
            <a href="javascript:void(0);" class="link contract_filing" id="should_flow" data="/contract/contract_should_manage/<?=$contract['id'];?>">应收应付<span class="iconfont hide"></span></a>
            <a href="javascript:void(0);" class="link contract_filing" id="actual_flow" data="/contract/contract_actual_manage/<?=$contract['id'];?>">实收实付<span class="iconfont hide"></span></a>
            <a href="javascript:void(0);" class="link contract_filing" id="contract_follow" data="/contract/contract_follow_manage/<?=$contract['id'];?>">跟进明细<span class="iconfont hide"></span></a>
            <?php if ($contract['is_commission'] == 0) { ?><a href="javascript:void(0)" class="btn-lv fr"
                                                              id="contract_divide1"
                                                              style="" <?php if ($auth['divide_add']['auth']){ ?>onclick="$('#divide').attr('src','/contract/contract_divide_modify/<?= $contract['id']; ?>');openWin('js_divide_pop');"
                                                              <?php }else{ ?>onclick="permission_none();"<?php } ?>>
                    <span style="margin-right:16px;">+ 添加业绩分成</span></a><?php } ?>
            <?php if($should_num >0){?><a href="javascript:void(0)" class="btn-lv fr" id="actual_flow1" style="display:none" <?php if($auth['actual_add']['auth']){?>onclick="$('#actual').attr('src','/contract/contract_actual_modify/<?=$contract['id'];?>');openWin('js_actual_pop');"<?php }else{?>onclick="permission_none();"<?php }?>><span style="margin-right:16px;">+ 添加实收实付</span></a><?php }?>
            <a href="javascript:void(0)" class="btn-lv fr" id="should_flow1" style="display:none" <?php if($auth['should_add']['auth']){?>onclick="$('#should').attr('src','/contract/contract_should_modify/<?=$contract['id'];?>');openWin('js_should_pop');"<?php }else{?>onclick="permission_none();"<?php }?>><span style="margin-right:16px;">+ 添加应收应付</span></a>


            <input type="hidden" id="stage_id">
            <input type="hidden" id="flow_id">
            <input type="hidden" id="divide_id">
            <input type="hidden" id="contract_id" value='<?=$contract['id'];?>'>
            <input type="hidden" id="percent_total" value="<?=$divide_total['percent_total'];?>">
        </div>
    <?php if($auth['actual_add']['auth']==1){?>
    <script>
        function show_actual_add(){
            var html='<a href="javascript:void(0)" class="btn-lv fr" id="actual_flow1" style="display:none" onclick="open_actual_add(<?=$contract['id']?>)"><span style="margin-right:16px;">+ 添加实收实付</span></a>';
            $("#js_search_box").append(html);
        }
    </script>
    <?php }else{?>
    <script>
        function show_actual_add(){
            var html='<a href="javascript:void(0)" class="btn-lv fr" id="actual_flow1" style="display:none" onclick="permission_none();"><span style="margin-right:16px;">+ 添加实收实付</span></a>';
            $("#js_search_box").append(html);
        }
    </script>
    <?php }?>
        <!--嵌入模块弹框-->
        <div id="js_mukuai_box" class="iframePopBox" style="width:100%;border:none; box-shadow:none;display:block;padding:0;padding-top:10px;background:#FFF;margin-top:0;">
            <!--            <iframe frameborder="0" scrolling="no" width="100%" height="100%" src="/contract/contract_warrant_manage/-->
            <? //=$contract['id'];?><!--" id="iframepage" name="iframepage"></iframe>-->
            <iframe frameborder="0" scrolling="no" width="100%" height="100%"
                    src="/contract/contract_divide_manage/<?= $contract['id']; ?>" id="iframepage"
                    name="iframepage"></iframe>
        </div>
    </div>
    </div>
</div>
<!--操作成功弹窗-->
<div class="pop_box_g pop_see_inform pop_no_q_up" style="display:none;" id="js_pop_success">
    <div class="hd">
        <div class="title">提示</div>
        <div class="close_pop"><a href="javascript:void(0);" title="关闭" class="JS_Close iconfont"></a></div>
    </div>
     <div class="mod">
    	<div class="inform_inner">
	    <div class="up_inner">
                <table class="del_table_pop">
                    <tr>
                        <td width="25%" align="right" style="padding-right:10px;">
			    <img alt="" src="<?php echo MLS_SOURCE_URL;?>/mls/images/v1.0/dakacg.gif"></td>
                        <td>
			    <p class="left" style="font-size:14px;color:#666;" id="js_prompt1"></p>
                        </td>
                    </tr>
                </table>
                <button class="btn-lv1 JS_Close" type="button">确定</button>
            </div>
         </div>
    </div>
</div>

<!--操作失败弹窗-->
<div class="pop_box_g pop_see_inform pop_no_q_up" style="display:none;" id="js_pop_false">
    <div class="hd">
        <div class="title">提示</div>
        <div class="close_pop"><a href="javascript:void(0);" title="关闭" class="JS_Close iconfont"></a></div>
    </div>
     <div class="mod">
    	<div class="inform_inner">
	    <div class="up_inner">
                <table class="del_table_pop">
                    <tr>
                        <td width="25%" align="right" style="padding-right:10px;">
                            <img alt="" src="<?php echo MLS_SOURCE_URL;?>/mls/images/v1.0/s_ico.png"></td>
                        <td>
                            <p class="left" style="font-size:14px;color:#666;" id="js_prompt2"></p>
                        </td>
                    </tr>
                </table>
                <button class="btn-lv1 JS_Close" type="button">确定</button>
            </div>
         </div>
    </div>
</div>
<!--删除-->
<div id="js_del_warrant" class="pop_box_g pop_see_inform pop_no_q_up" >
    <div class="hd">
	<div class="title">提示</div>
	<div class="close_pop"><a href="javascript:void(0);" title="关闭" class="JS_Close iconfont"></a></div>
    </div>
    <div class="mod">
	<div class="inform_inner">
	    <div class="up_inner">
		<p class="text"><img alt="" src="<?php echo MLS_SOURCE_URL;?>/mls/images/v1.0/s_ico.png">&nbsp;&nbsp;步骤删除之后不可恢复。<br/>是否确认删除？</p>
		<button type="button" class="btn-lv1 JS_Close" onclick="delete_warrant();" style="margin-right:10px;">确定</button>
		<button type="button" class="btn-hui1 JS_Close">取消</button>
	    </div>
	</div>
    </div>
</div>
<!--删除-->
<div id="js_del_pop" class="pop_box_g pop_see_inform pop_no_q_up" >
    <div class="hd">
	<div class="title">提示</div>
	<div class="close_pop"><a href="javascript:void(0);" title="关闭" class="JS_Close iconfont"></a></div>
    </div>
    <div class="mod">
	<div class="inform_inner">
	    <div class="up_inner">
		<p class="text"><img alt="" src="<?php echo MLS_SOURCE_URL;?>/mls/images/v1.0/s_ico.png">&nbsp;&nbsp;您确定要删除此实收实付吗？<br/>确认删除后不可恢复。</p>
		<button type="button" class="btn-lv1 JS_Close" onclick="delete_actual_this();" style="margin-right:10px;">确定</button>
		<button type="button" class="btn-hui1 JS_Close">取消</button>
	    </div>
	</div>
    </div>
</div>

<!--删除-->
<div id="js_del_pop1" class="pop_box_g pop_see_inform pop_no_q_up" >
    <div class="hd">
	<div class="title">提示</div>
	<div class="close_pop"><a href="javascript:void(0);" title="关闭" class="JS_Close iconfont"></a></div>
    </div>
    <div class="mod">
	<div class="inform_inner">
	    <div class="up_inner">
		<p class="text"><img alt="" src="<?php echo MLS_SOURCE_URL;?>/mls/images/v1.0/s_ico.png">&nbsp;&nbsp;您确定要删除此应收应付吗？<br/>确认删除后不可恢复。</p>
		<button type="button" class="btn-lv1 JS_Close" onclick="delete_should_this();" style="margin-right:10px;">确定</button>
		<button type="button" class="btn-hui1 JS_Close">取消</button>
	    </div>
	</div>
    </div>
</div>

<!--删除-->
<div id="js_del_divide" class="pop_box_g pop_see_inform pop_no_q_up" >
    <div class="hd">
	<div class="title">提示</div>
	<div class="close_pop"><a href="javascript:void(0);" title="关闭" class="JS_Close iconfont"></a></div>
    </div>
    <div class="mod">
	<div class="inform_inner">
	    <div class="up_inner">
		<p class="text"><img alt="" src="<?php echo MLS_SOURCE_URL;?>/mls/images/v1.0/s_ico.png">&nbsp;&nbsp;您确定要删除此业绩分成吗？<br/>确认删除后不可恢复。</p>
		<button type="button" class="btn-lv1 JS_Close" onclick="delete_divide();" style="margin-right:10px;">确定</button>
		<button type="button" class="btn-hui1  JS_Close">取消</button>
	    </div>
	</div>
    </div>
</div>

<!--结佣-->
<div id="js_commission_pop" class="pop_box_g pop_see_inform pop_no_q_up" >
    <div class="hd">
	<div class="title">提示</div>
	<div class="close_pop"><a href="javascript:void(0);" title="关闭" class="JS_Close iconfont"></a></div>
    </div>
    <div class="mod">
	<div class="inform_inner">
	    <div class="up_inner">
		<p class="text"><img alt="" src="<?php echo MLS_SOURCE_URL;?>/mls/images/v1.0/s_ico.png">&nbsp;&nbsp;分成结佣后，将不可对分成信息修改。<br/>是否确认操作？</p>
		<button type="button" class="btn-lv1 btn-left JS_Close" onclick="complete_commission();" style="margin-right:10px;">确定</button>
		<button type="button" class="btn-hui1  JS_Close">取消</button>
	    </div>
	</div>
    </div>
</div>

<!--确认收付-->
<div id="js_sure_flow_pop" class="pop_box_g pop_see_inform pop_no_q_up" >
    <div class="hd">
	<div class="title">提示</div>
	<div class="close_pop"><a href="javascript:void(0);" title="关闭" class="JS_Close iconfont"></a></div>
    </div>
    <div class="mod">
	<div class="inform_inner">
	    <div class="up_inner">
		<p class="text"><img alt="" src="<?php echo MLS_SOURCE_URL;?>/mls/images/v1.0/s_ico.png">&nbsp;&nbsp;是否确认当前收付已收付？</p>
		<button type="button" class="btn-lv1 JS_Close" onclick="sure_flow();" style="margin-right:10px;">确定</button>
		<button type="button" class="btn-hui1  JS_Close">取消</button>
	    </div>
	</div>
    </div>
</div>

<!--权证办结-->
<div id="js_all_complete_pop" class="pop_box_g pop_see_inform pop_no_q_up" >
    <div class="hd">
	<div class="title">提示</div>
	<div class="close_pop"><a href="javascript:void(0);" title="关闭" class="JS_Close iconfont"></a></div>
    </div>
    <div class="mod">
	<div class="inform_inner">
	    <div class="up_inner">
		<p class="text"><img alt="" src="<?php echo MLS_SOURCE_URL;?>/mls/images/v1.0/s_ico.png">&nbsp;&nbsp;权证流程办结后，该合同将默认为已结盘，合同将不可再修改，是否确认操作？</p>
		<button type="button" class="btn-lv1 JS_Close" onclick="complete_all_temp();" style="margin-right:10px;">确定</button>
		<button type="button" class="btn-hui1 JS_Close">取消</button>
	    </div>
	</div>
    </div>
</div>
<!--权证步骤确认完成-->
<div class="delate_btn1 wH335" style="display: none" id="js_complete_pop">
    <dl class="title_top">
        <dd>权证流程确认完成</dd>
        <dt class="JS_Close">X</dt>
    </dl>
    <div class="qz_set_input">
          <p><strong style="font-weight:normal;float:left;display:inline;">合同编号：<?=$contract['number'];?></strong><b style="font-weight:normal;float:right;display:inline;">步骤：<label id="confirm_step"></label></b></p>
          <p>流程阶段：<label id="confirm_stage"></label></p>
          <p>备注：<label id="confirm_remark"></label></p>
          <p><input type="checkbox" name="is_confirm" value="1">是否已经完成<label class="errorBox" id="confirm_error"></label></p>
          <p>
            完成人：
            <select name="confirm_agency_id" <?=$level==6?'disabled':''?>>
                <?php foreach($agencys as $key=>$val){?>
                <option value="<?=$val['id'];?>" <?=$val['id']==$agency_id?'selected':''?>><?=$val['name'];?></option>
                <?php }?>
            </select>
            <select name="confirm_broker_id">
                <?php foreach($brokers as $key=>$val){?>
                <option value="<?=$val['broker_id'];?>" <?=$val['broker_id']==$broker_id?'selected':''?>><?=$val['truename'];?></option>
                <?php }?>
            </select>
          </p>
          <script>
                $("select[name='confirm_agency_id']").change(function(){
                    var agency_id = $("select[name='confirm_agency_id']").val();
                    if(agency_id){
                        $.ajax({
                            url:"/contract_earnest_money/broker_list",
                            type:"GET",
                            dataType:"json",
                            data:{
                               agency_id:agency_id
                            },
                            success:function(data){
                                var html = "<option>请选择人员</option>";
                                if(data['result'] == 1){
                                    for(var i in data['list']){
                                    html+="<option value='"+data['list'][i]['broker_id']+"'>"+data['list'][i]['truename']+"</option>";
                                    }
                                }
                                $("select[name='confirm_broker_id']").html(html);
                            }
                        })
                    }else{
                    $("select[name='confirm_broker_id']").html("<option value=''>请选择</option>");
                    }
                })
		    </script>
          <p>完成时期：<input type="text" class="aad_pop_select_W100 time_bg" name='confirm_time' onfocus="WdatePicker({lang:'zh-cn',dateFmt:'yyyy-MM-dd'})"></p>
    </div>
    <dl class="qz_prcess_btn">
        <dd><label onclick="confirm_complete();">确认</label></dd>
        <dt class="JS_Close">取消</dt>
    </dl>
</div>
<!--新建模版弹窗-->
<div class="delate_btn1 qz_moudle_H500" style="display: none;width:300px;margin-left:-150px;height:200px;margin-top:-100px" id="js_template_pop">
    <dl class="title_top">
        <dd>新建权证流程模板</dd>
        <dt class="JS_Close">X</dt>
    </dl>
    <div class="qz_moudle_con2">
        <p>模版名称：<input type="text" class="qz_moudle_text" name="template_name" maxlength="8"></p>
        <div class="qz_moudle_con1">
          <a href="javascript:void(0)" onclick="save_template(1);" class="JS_Close">下一步</a>
          <a href="javascript:void(0)" class="JS_Close" style="margin:0 0 0 10px;">取　消</a>
        </div>
    </div>
</div>

<!--添加权证步骤弹框-->
<div id="js_addtemp_pop" class="iframePopBox" style="width: 612px;height:438px;border:none;">
    <a class="JS_Close close_pop iconfont" href="javascript:void(0)" date-iframe="1">&#xe60c;</a>
    <iframe frameborder="0" scrolling="no" width="612px" height="438px" class='iframePop' src="" id="addtemp"></iframe>
</div>

<!--添加应收应付弹框-->
<div id="js_should_pop" class="iframePopBox" style="width: 582px;height:313px;border:none;">
    <a class="JS_Close close_pop iconfont" href="javascript:void(0)" date-iframe="1">&#xe60c;</a>
    <iframe frameborder="0" scrolling="no" width="582px" height="313px" class='iframePop' src="" id="should"></iframe>
</div>

<!--添加实收实付弹框-->
<div id="js_actual_pop" class="iframePopBox" style="width: 582px;height:413px;border:none;">
    <a class="JS_Close close_pop iconfont" href="javascript:void(0)" date-iframe="1">&#xe60c;</a>
    <iframe frameborder="0" scrolling="no" width="582px" height="413px" class='iframePop' src="" id="actual"></iframe>
</div>

<!--添加业绩分成弹框-->
<div id="js_divide_pop" class="iframePopBox" style="width: 502px;height:422px;border:none;">
    <a class="JS_Close close_pop iconfont" href="javascript:void(0)" date-iframe="1">&#xe60c;</a>
    <iframe frameborder="0" scrolling="no" width="502px" height="420px" class='iframePop' src="" id="divide"></iframe>
</div>

<!--权证步骤详情弹框-->
<div id="js_warrant_pop" class="iframePopBox" style="width: 400px;height:250px;border:none;">
    <a class="JS_Close close_pop iconfont" href="javascript:void(0)" date-iframe="1">&#xe60c;</a>
    <iframe frameborder="0" scrolling="no" width="400px" height="300px" class='iframePop' src="" id="warrant"></iframe>
</div>

<!--权证步骤详情弹框-->
<div id="js_warrant_pop1" class="iframePopBox" style="width: 400px;height:250px;border:none;">
    <a class="JS_Close close_pop iconfont" href="javascript:void(0)" date-iframe="1">&#xe60c;</a>
    <iframe frameborder="0" scrolling="no" width="400px" height="300px" class='iframePop' src="" id="warrant1"></iframe>
</div>

<!--选择模板弹框-->
<div id="js_temp_box" class="iframePopBox" style="width: 842px;height:463px;border:none;">
    <a class="JS_Close close_pop iconfont" href="javascript:void(0)" date-iframe="1">&#xe60c;</a>
    <iframe frameborder="0" scrolling="no" width="842" height="500" class='iframePop' src="" id="choose_template"></iframe>
</div>

<!--新建模版弹框-->
<div id="js_edit_template_pop" class="iframePopBox" style="width: 842px;height:504px;border:none;">
    <a class="JS_Close close_pop iconfont" href="javascript:void(0)" date-iframe="1">&#xe60c;</a>
    <iframe frameborder="0" scrolling="no" width="842" height="502" class='iframePop' src=""></iframe>
</div>

<!--房源详情弹框-->
<div id="js_pop_box_g" class="iframePopBox" style=" width:816px; height:540px; ">
    <a class="JS_Close close_pop iconfont" href="javascript:void(0)" date-iframe="1">&#xe60c;</a>
    <iframe frameborder="0" scrolling="no" width="816" height="540" class='iframePop' src=""></iframe>
</div>
<img src="<?php echo MLS_SOURCE_URL;?>/mls/images/v1.0/009.gif" id="mainloading" ><!--遮罩 loading-->
<script src="<?php echo MLS_SOURCE_URL;?>/min/?b=mls/js/v1.0&f=openWin.js,house.js,backspace.js"></script>

<script>
$(function () {
    function re_width(){
      var h1 = $(window).height();
      var w1 = $(window).width() - 180;
      $(".tab-left, .forms_scroll").height(h1-65);
      $(".forms_scroll").width(w1).show();
    };
    re_width();
    $(window).resize(function(e) {
	re_width();
	$(".sale_message dt").css("width",($(".sale_message").width()-100-36)+"px");
	$(".qz_precess_add_modle p").css("padding-left",($(".qz_precess_add_modle").width()-450)/2+"px");
    });

	$(".qz_precess_add_modle p").css("padding-left",($(".qz_precess_add_modle").width()-450)/2+"px");
	$(".sale_message dt").css("width",($(".sale_message").width()-100-36)+"px");
     //items   table   隔行换色
    //$("tbody tr:odd").css("background","#f7f7f7");
    //$("tbody tr:even").css("background","#fcfcfc");
    $("#should_flow").find("tr").css("background","none");
    $("#add_actual").find("tr").css("background","none");
    $(".add_pop_messages").find("tr").css("background","none");

    $("input[name='is_confirm']").live('click',function(){
        $("#confirm_error").text('');
    })

    //var history_num = 0;
    $("#js_search_box").find('.contract_filing').live('click',function(){
        $("#js_search_box").find('a').removeClass('link_on');
        $(this).addClass('link_on');
        var id = $(this).attr('id');
        var data = $(this).attr('data');
        $("#js_search_box .btn-lv").hide();
        $("#"+id+'1').show();
        $("#iframepage").attr("src",data);
        //history_num = history_num-1;alert(history_num);
        //$("#return_last").live('click',function(){history.go(history_num);});
    })
});


    //iframe自适应高度
    function iFrameHeight() {

        var ifm = document.getElementById("iframepage");

            var subWeb = document.frames ? document.frames["iframepage"].document : ifm.contentDocument;
            if (ifm != null && subWeb != null) {
                ifm.height = subWeb.body.scrollHeight;

            }




    }

    //打开收付删除弹窗
    function open_actual_add(id){
        $("#actual").attr('src','/contract/contract_actual_modify/'+id);
        openWin('js_actual_pop');
    }

    //打开收付删除弹窗
    function open_should_delete(id){
        $('#flow_id').val(id);
        openWin('js_del_pop1');
    }
    function open_divide_delete(id){
        $('#divide_id').val(id);
        openWin('js_del_divide');
    }

    function open_template_edit(id){
        $("#js_edit_template_pop").find(".iframePop").attr('src','/contract/warrant_template_add/'+id);
        openWin('js_edit_template_pop');
    }

    //删除此条收付记录
    function delete_actual_this(){
        $.ajax({
            url:"/contract/flow_del",
            type:"GET",
            dataType:"json",
            data:{
                id:$('#flow_id').val(),
                c_id:$("#contract_id").val(),
                flow_type:'actual'
            },
            success:function(data){
                if(data['result'] == 'ok'){
                    iframepage.window.location=iframepage.window.location;
                    $('#js_prompt1').text(data['msg']);
                    openWin('js_pop_success');
                }else{
                    $('#js_prompt2').text(data['msg']);
                    openWin('js_pop_false');
                }
            }
        })
    }

    //删除此条收付记录
    function delete_should_this(){
        $.ajax({
            url:"/contract/flow_del",
            type:"GET",
            dataType:"json",
            data:{
                id:$('#flow_id').val(),
                c_id:$("#contract_id").val(),
                flow_type:'should'
            },
            success:function(data){
                if(data['result'] == 'ok'){
                    iframepage.window.location=iframepage.window.location;
                    if (data['num'] == 0)
                    {
                        $("#actual_flow1").remove();
                    }
                    $('#js_prompt1').text(data['msg']);
                    openWin('js_pop_success');
                }else{
                    $('#js_prompt2').text(data['msg']);
                    openWin('js_pop_false');
                }
            }
        })
    }

    //删除此条收付记录
    function delete_divide(){
        $.ajax({
            url:"/contract/divide_del",
            type:"POST",
            dataType:"json",
            data:{
                id:$('#divide_id').val(),
                c_id:$("#contract_id").val()
            },
            success:function(data){
                if(data['result'] == 'ok'){
                    iframepage.window.location=iframepage.window.location;
                    $('#js_prompt1').text(data['msg']);
                    openWin('js_pop_success');
                }else{
                    $('#js_prompt2').text(data['msg']);
                    openWin('js_pop_false');
                }
            }
        });
    }

    function delete_warrant(){
        $.ajax({
            url:"/contract/delete_temp_step",
            type:"POST",
            dataType:"json",
            data:{
                stage_id:$('#stage_id').val(),
                c_id:$("#contract_id").val()
            },
            success:function(data){
                if(data['result'] == 'ok'){
                    $('#js_prompt1').text(data['msg']);
                    iframepage.window.location=iframepage.window.location;
                    openWin('js_pop_success');
                }else{
                    $('#js_prompt2').text(data['msg']);
                    openWin('js_pop_false');
                }
            }
        });
    }

    function complete_commission(){
        var total = iframepage.window.document.getElementById("percent_total").value;
        if(parseInt(total) == 100){
            $.ajax({
                url:"/contract/confirm_all_commission",
                type:"POST",
                dataType:"json",
                data:{
                    c_id:$("#contract_id").val()
                },
                success:function(data){
                    if(data['result'] == 'ok'){
                        iframepage.window.location=iframepage.window.location;
                        $('#js_prompt1').text(data['msg']);
                        openWin('js_pop_success');
                    }else{
                        $('#js_prompt2').text(data['msg']);
                        openWin('js_pop_false');
                    }
                }
            })
        }else{
            $('#js_prompt2').text('您还有剩余的业绩未分配！');
            openWin('js_pop_false');
        }
    }

    function complete_all_temp(){
        $.ajax({
            url:"/contract/confirm_all_complete",
            type:"POST",
            dataType:"json",
            data:{
                contract_id:$("#contract_id").val()
            },
            success:function(data){
                if(data['result'] == 'ok'){
                    iframepage.window.location=iframepage.window.location;
                    $('#js_prompt1').text(data['msg']);
                    openWin('js_pop_success');
                }else{
                    $('#js_prompt2').text(data['msg']);
                    openWin('js_pop_false');
                }
            }
        })
    }

    function confirm_warrant_detail(id){
        $("#stage_id").val(id);
        $.ajax({
			type: 'post',
			url: '/contract/sure_temp_judge',
			data: {
				stage_id:id,
                contract_id:$("#contract_id").val()
			},
			dataType: 'json',
			success: function(data){
                if(data['result'] == 'ok'){
                    $("input[name='is_confirm']").attr('checked',false);
                    $("input[name='confirm_time']").val('');
                    $("select[name='confirm_agency_id']").attr('selected',false);
                    $("select[name='confirm_broker_id']").attr('selected',false);
                    $.ajax({
                        type: 'post',
                        url: '/contract/warrant_detail',
                        data: {
                            id:id
                        },
                        dataType: 'json',
                        success: function(data){
                            if(data['result'] == 1){
                                $("#confirm_step").text(data['warrant_list']['step_name']);
                                $("#confirm_stage").text(data['warrant_list']['stage_name']);
                                if(data['warrant_list']['remark']){
                                    $("#confirm_remark").text(data['warrant_list']['remark']);
                                }
                                openWin('js_complete_pop');
                            }
                        }
                    });
                }else{
                    $('#js_prompt2').text(data['msg']);
                    openWin('js_pop_false');
                }
			}
		});
    }

    function confirm_complete(){
        var is_confirm = $("input[name='is_confirm']:checked").val();
        if(is_confirm){
            $.ajax({
                url:"/contract/confirm_complete",
                type:"POST",
                dataType:"json",
                data:{
                    contract_id:$("#contract_id").val(),
                    stage_id:$("#stage_id").val(),
                    agency_id:$("select[name='confirm_agency_id']").val(),
                    broker_id:$("select[name='confirm_broker_id']").val(),
                    confirm_time:$("input[name='confirm_time']").val()
                },
                success:function(data){
                    if(data['result'] == 'ok'){
                        $("#js_complete_pop").hide();
                        $("#GTipsCoverjs_complete_pop").remove();
                        iframepage.window.location=iframepage.window.location;
                        $('#js_prompt1').text(data['msg']);
                        openWin('js_pop_success');
                    }else{
                        $('#js_prompt2').text(data['msg']);
                        openWin('js_pop_false');
                    }
                }
            });
        }else{
            $("#confirm_error").text('请勾选完成');
        }
    }

    function save_template(key){
        $.ajax({
            url:"/contract/save_template",
            type:"POST",
            dataType:"json",
            data:{
                contract_id:$("#contract_id").val(),
                template_name:$("input[name='template_name']").val()
            },
            success:function(data){
                var c_id = $("#contract_id").val();
                if(data['result'] == 'ok'){
                    $("#js_template_pop").hide();
                    $("#GTipsCoverjs_template_pop").remove();
                    $("#js_edit_template_pop .iframePop").attr('src','/contract/warrant_template_add/'+data['data']+"/"+key+"/"+c_id);
                    openWin('js_edit_template_pop');
                }else{
                    $('#js_prompt2').text(data['msg']);
                    openWin('js_pop_false');
                }
            }
        })
    }

    function sure_flow(){
        $.ajax({
            url:"/contract/flow_sure",
            type:"POST",
            dataType:"json",
            data:{
                id:$("#flow_id").val(),
                c_id:$("#contract_id").val()
            },
            success:function(data){
                if(data['result'] == 'ok'){
                    iframepage.window.location=iframepage.window.location;
                    $('#js_prompt1').text(data['msg']);
                    openWin('js_pop_success');
                }else{
                    $('#js_prompt2').text(data['msg']);
                    openWin('js_pop_false');
                }
            }
        })
    }

    function add_template_pop(){
        $("input[name='template_name']").val('');
        $.ajax({
            url:"/contract/add_template_judge",
            type:"POST",
            dataType:"json",
            success:function(data){
                if(data['result'] == 'ok'){
                    openWin('js_template_pop');
                }else{
                    $('#js_prompt2').text(data['msg']);
                    openWin('js_pop_false');
                }
            }
        })
    }

</script>
