<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 个人中心-积分商城
 * @package     mls
 * @subpackage      Controllers
 * @category        Controllers
 * @author      sun
 */
class Gift_exchange extends MY_Controller
{
  /**
   * 当前页码
   *
   * @access private
   * @var string
   */
  private $_current_page = 1;

  /**
   * 每页条目数
   *
   * @access private
   * @var int
   */
  private $_limit = 20;

  /**
   * 偏移
   *
   * @access private
   * @var int
   */
  private $_offset = 0;

  /**
   * 条目总数
   *
   * @access private
   * @var int
   */
  private $_total_count = 0;

  /**
   * 解析函数
   * @access public
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
    $this->load->model('gift_manage_model');
    $this->load->model('gift_exchange_record_base_model');
    $this->load->model('broker_info_model');
    $this->load->model('operate_log_model');
  }

  //积分商城--商品展示
  public function index()
  {
    $data = array();
    $broker_id = $this->user_arr['broker_id'];

    $data['credit_total'] = $this->broker_info_model->get_credit_by_broker_id($broker_id);
    $where = 'status = 1 and type = 1 and down_time >' . time();

    //get参数  分页
    $get_param = $this->input->get(NULL, TRUE);
    $page = isset($get_param['page']) ? intval($get_param['page']) : 1;
    $pagesize = isset($get_param['pagesize']) ? intval($get_param['pagesize']) : 0;
    $this->_init_pagination($page, $pagesize);

    $this->_total_count = $this->gift_manage_model->count_by($where);
    //计算总页数
    $pages = $this->_total_count > 0 ? ceil($this->_total_count / $this->_limit) : 0;

    //任务信息
    $gift_data = $this->gift_manage_model->get_all_by($where, $this->_offset, $this->_limit, 'id', 'desc');
    foreach ($gift_data as $key => $value) {
      $gift_data[$key]['product_picture'] = str_replace('thumb/', '', $value['product_picture']);
    }
    $data['gift_data'] = $gift_data;
    $this->result("1", "获取商品兑换记录列表成功", $data);
    return;

  }

  //积分商城--我的兑换
  public function record($id = '')
  {
    $data = array();
    $isajax = $this->input->get('isajax', TRUE);

    $broker_id = $this->user_arr['broker_id'];
    $data['credit_total'] = $this->broker_info_model->get_credit_by_broker_id($broker_id);

    //get参数  分页
    $get_param = $this->input->get(NULL, TRUE);
    $page = isset($get_param['page']) ? intval($get_param['page']) : 1;
    $pagesize = isset($get_param['pagesize']) ? intval($get_param['pagesize']) : 0;
    $this->_init_pagination($page, $pagesize);

    $where = 'gift_exchange_record.broker_id = ' . $broker_id;

    $this->_total_count = $this->gift_exchange_record_base_model->count_by($where);
    //计算总页数
    $pages = $this->_total_count > 0 ? ceil($this->_total_count / $this->_limit) : 0;

    //任务信息
    $gift_data = $this->gift_exchange_record_base_model->get_all_by($where, $this->_offset, $this->_limit, 'id', 'desc');
    if (is_full_array($gift_data)) {
      foreach ($gift_data as $k => $v) {
        $gift = $this->gift_manage_model->get_by_id($v['gift_id']);
        $gift_data[$k]['product_name'] = $gift['product_name'];
        $gift_data[$k]['product_serial_num'] = $gift['product_serial_num'];
        $gift_data[$k]['product_picture'] = $gift['product_picture'];
      }
    }
    $data['gift_data'] = $gift_data;
    $this->result("1", "我的兑换展示成功", $data);
    return;
  }

  //商品详情
  public function detail()
  {
    $data = array();
    $broker_id = $this->user_arr['broker_id'];
    $id = $this->input->get('id');
    $data['credit_total'] = $this->broker_info_model->get_credit_by_broker_id($broker_id);
    $list = $this->gift_manage_model->get_by_id($id);
    $list['my_credit'] = $data['credit_total'];
    $data['list'] = $list;
    if ($data['list']['status'] == 1 && ($data['list']['down_time'] > time())) {
      $this->result("1", "商品详情展示成功", $data);
      return;
    } else {
      $this->result("0", "商品已下架", $data);
      return;
    }

  }

  //兑换商品
  public function exchange()
  {
    $param = $this->input->post(NULL, TRUE);
    if (!is_full_array($param)) {
      $param = $this->input->get(NULL, TRUE);
    }
    $deviceid = $param['deviceid'];

    $broker_id = $this->user_arr['broker_id'];

    $credit_total = $this->broker_info_model->get_credit_by_broker_id($broker_id);

    $gift_id = $this->input->get('gift_id');
    $score = $this->input->get('score');

    $gift_data = $this->gift_manage_model->get_by_id($gift_id);
    $over_exchange_num = $gift_data['over_exchange_num'];
    $stock_num = $gift_data['stock'];

    if ($stock_num <= 0) {
      $this->result("0", "库存不足，商品兑换失败");
      return;
    } else {
      //西安站的45号商品，每个人只能兑换一次
      if ('21' == $this->user_arr['city_id']) {
        //线上 西安站的45号商品 '21'==$this->user_arr['city_id'] && '45'==$gift_id
        //测试机 南京站的47号商品 '3'==$this->user_arr['city_id'] && '47'==$gift_id
        if ('45' == $gift_id) {
          $where_cond = array(
            'broker_id' => $broker_id,
            'gift_id' => intval($gift_id)
          );
          $gift_data = $this->gift_exchange_record_base_model->get_one_new_by($where_cond);
          if (is_full_array($gift_data)) {
            $this->result("0", "该商品只能兑换一次");
            return;
          } else {
            //产生兑换记录
            $result = $this->gift_manage_model->exchange($broker_id, $gift_id);
            if ($result['status'] == 1) {
              //添加积分兑换操作日志
              $add_log_param = array();
              $add_log_param['company_id'] = $this->user_arr['company_id'];
              $add_log_param['agency_id'] = $this->user_arr['agency_id'];
              $add_log_param['broker_id'] = $this->user_arr['broker_id'];
              $add_log_param['broker_name'] = $this->user_arr['truename'];
              $add_log_param['type'] = 41;
              $add_log_param['text'] = '兑换 ' . $gift_data['product_name'];
              if ($param['api_key'] == 'android') {
                $add_log_param['from_system'] = 2;
              } else {
                $add_log_param['from_system'] = 3;
              }
              $add_log_param['device_id'] = $deviceid;
              $add_log_param['from_ip'] = get_ip();
              $add_log_param['mac_ip'] = '127.0.0.1';
              $add_log_param['from_host_name'] = '127.0.0.1';
              $add_log_param['hardware_num'] = '测试硬件序列号';
              $add_log_param['time'] = time();
              $this->operate_log_model->add_operate_log($add_log_param);
              $update_gift = array('over_exchange_num' => $over_exchange_num + 1, 'stock' => $stock_num - 1);
              $this->gift_manage_model->update_by_id($gift_id, $update_gift);

              //获得积分兑换时间
              $id = intval($result['id']);
              $creatitime = 0;
              if ($id > 0) {
                $gift_data = $this->gift_exchange_record_base_model->get_one_new_by(array('id' => $id));
                if (is_full_array($gift_data)) {
                  $creatitime = $gift_data[0]['create_time'];
                }
              }
              $create_date = date('Y年m月d日', $creatitime);

              //系统消息
              $company_id = intval($this->user_arr['company_id']);
              $this->load->model('agency_model');
              $this_company_data = $this->agency_model->get_by_id($company_id);
              $company_name = '';
              if (is_full_array($this_company_data)) {
                $company_name = $this_company_data['name'];
              }
              $broker_name = $this->user_arr['truename'];
              $agency_name = $this->user_arr['agency_name'];
              $phone = $this->user_arr['phone'];
              $msg_type = '8-48';
              $url_a = '/message/bulletin/';
              $params['agency_name'] = $agency_name;
              $params['company_name'] = $company_name;
              $params['phone'] = $phone;
              $params['create_date'] = $create_date;

              $this->load->model('message_base_model');
              //添加系统消息
              $msg_id = $this->message_base_model->add_message($msg_type, $broker_id, $broker_name, $url_a, $params);
              //发送推送消息
              $send_arr = array(
                'broker_name' => $broker_name,
                'agency_name' => $agency_name,
                'company_name' => $company_name,
                'phone' => $phone,
                'create_date' => $create_date
              );
              $this->load->model('push_func_model');
              $this->push_func_model->send(1, 13, 1, 0, $broker_id, array('msg_id' => $msg_id), $send_arr);

              $this->result("1", "商品兑换成功");
              return;
            } else {
              $this->result("0", "商品兑换失败");
              return;
            }
          }
        } else {
          $result = $this->gift_manage_model->exchange($broker_id, $gift_id);
          if ($result['status'] == 1) {
            //添加积分兑换操作日志
            $add_log_param = array();
            $add_log_param['company_id'] = $this->user_arr['company_id'];
            $add_log_param['agency_id'] = $this->user_arr['agency_id'];
            $add_log_param['broker_id'] = $this->user_arr['broker_id'];
            $add_log_param['broker_name'] = $this->user_arr['truename'];
            $add_log_param['type'] = 41;
            $add_log_param['text'] = '兑换 ' . $gift_data['product_name'];
            if ($param['api_key'] == 'android') {
              $add_log_param['from_system'] = 2;
            } else {
              $add_log_param['from_system'] = 3;
            }
            $add_log_param['device_id'] = $deviceid;
            $add_log_param['from_ip'] = get_ip();
            $add_log_param['mac_ip'] = '127.0.0.1';
            $add_log_param['from_host_name'] = '127.0.0.1';
            $add_log_param['hardware_num'] = '测试硬件序列号';
            $add_log_param['time'] = time();
            $this->operate_log_model->add_operate_log($add_log_param);
            $update_gift = array('over_exchange_num' => $over_exchange_num + 1, 'stock' => $stock_num - 1);
            $this->gift_manage_model->update_by_id($gift_id, $update_gift);
            $this->result("1", "商品兑换成功");
            return;
          } else {
            $this->result("0", "商品兑换失败");
            return;
          }
        }
      } else {
        $result = $this->gift_manage_model->exchange($broker_id, $gift_id);
        if ($result['status'] == 1) {
          //添加积分兑换操作日志
          $add_log_param = array();
          $add_log_param['company_id'] = $this->user_arr['company_id'];
          $add_log_param['agency_id'] = $this->user_arr['agency_id'];
          $add_log_param['broker_id'] = $this->user_arr['broker_id'];
          $add_log_param['broker_name'] = $this->user_arr['truename'];
          $add_log_param['type'] = 41;
          $add_log_param['text'] = '兑换 ' . $gift_data['product_name'];
          if ($param['api_key'] == 'android') {
            $add_log_param['from_system'] = 2;
          } else {
            $add_log_param['from_system'] = 3;
          }
          $add_log_param['device_id'] = $deviceid;
          $add_log_param['from_ip'] = get_ip();
          $add_log_param['mac_ip'] = '127.0.0.1';
          $add_log_param['from_host_name'] = '127.0.0.1';
          $add_log_param['hardware_num'] = '测试硬件序列号';
          $add_log_param['time'] = time();
          $this->operate_log_model->add_operate_log($add_log_param);
          $update_gift = array('over_exchange_num' => $over_exchange_num + 1, 'stock' => $stock_num - 1);
          $this->gift_manage_model->update_by_id($gift_id, $update_gift);
          $this->result("1", "商品兑换成功");
          return;
        } else {
          $this->result("0", "商品兑换失败");
          return;
        }
      }
    }
  }

  //积分规则
  public function protocol()
  {
    $protocol_html = file_get_contents(dirname(__FILE__) . '/../views/gift_exchange/exchange_rule.php');
    $protocol_html_arr = array('protocol' => $protocol_html);
    $this->result(1, '查询成功', $protocol_html_arr);
  }

  //积分商城--抽奖
  public function raffle()
  {
    $data = array();
    /*$scode = $this->input->get('scode', true);
        if(!$scode){
            $scode = $this->input->post('scode', true);
        }*/
    $param = $this->input->post(NULL, TRUE);
    if (!is_full_array($param)) {
      $param = $this->input->get(NULL, TRUE);
    }
    $data['param'] = $param;
    $broker_id = $this->user_arr['broker_id'];
    $data['credit_total'] = $this->broker_info_model->get_credit_by_broker_id($broker_id);
    $data['credit_num'] = intval($data['credit_total'] / 500);
    //print_r($data['credit_total']);

    $pg = $this->input->post('page');
    $where = 'gift_exchange_record.type = 2';
    //页面标题
    $data['page_title'] = '积分商城-商品抽奖';
    //奖品列表
    $reward = $this->gift_manage_model->get_reward_type();
    $data['reward'] = $reward;
    $page = isset($pg) && $pg ? intval($pg) : 1; // 获取当前页数
    $this->_init_pagination($page);
    $this->_total_count = $this->gift_exchange_record_base_model->count_by($where);
    //计算总页数
    $pages = $this->_total_count > 0 ? ceil($this->_total_count / $this->_limit) : 0;

    $params = array(
      'total_rows' => $this->_total_count, //总行数
      'method' => 'post', //URL提交方式 get/html/post
      'now_page' => $pg,//当前页数
      'list_rows' => $this->_limit,//每页显示个数
    );
    //加载分页类
    $this->load->library('page_list', $params);
    //调用分页函数（不同的样式不同的函数参数）
    $data['page_list'] = $this->page_list->show('jump');
    //中奖信息
    $gift_raffle_data = $this->gift_exchange_record_base_model->get_all_by($where, $this->_offset, $this->_limit);

    if (is_full_array($gift_raffle_data)) {
      foreach ($gift_raffle_data as $k => $v) {
        $gift = $this->gift_manage_model->get_by_id($v['gift_id']);
        $gift_raffle_data[$k]['phone'] = substr_replace($v['phone'], '****', 3, 4);
        $gift_raffle_data[$k]['product_serial_num'] = $gift['product_serial_num'];
        $gift_raffle_data[$k]['product_picture'] = $gift['product_picture'];
        $gift_raffle_data[$k]['product_name'] = $gift['product_name'];
      }
    }
    //print_r($gift_raffle_data);
    $data['gift_raffle_data'] = $gift_raffle_data;

    //需要加载的css
    $data['css'] = load_css('mls/css/v1.0/base.css,mls/css/v1.0/app_cj.css');
    //需要加载的JS
    $data['js'] = load_js('mls/js/v1.0/jquery-1.8.3.min.js,mls/js/v1.0/raffle.js');
    //底部JS
    /*$data['footer_js'] = load_js('mls/js/v1.0/openWin.js,mls/js/v1.0/house.js,'
                            .'mls/js/v1.0/backspace.js,mls/js/v1.0/personal_center.js');*/
    $this->load->view('gift_exchange/raffle', $data);

  }

  //积分商城--抽奖
  public function raffle_rule()
  {
    $data = array();
    $this->load->view('gift_exchange/raffle_rule', $data);
  }

  //抽奖
  public function lottery()
  {
    //防打开地址式
    if (!strstr($_SERVER['HTTP_REFERER'], MLS_MOBILE_URL)) {
      die('为了建设祖国更美好的未来，请不要模拟参数！');
    }
    //判断时间内
    if (!$this->gift_manage_model->is_active_intime_lottery()) {
      $lottery_reward = array('result' => 1, 'award_id' => '', 'award_name' => '', 'award_writer' => '');
      $lottery_reward['award_writer'] = '<span class="app_cj_con_zj_inf_lose">抽奖系统暂未开启，尽请期待！<br /></span>';
      echo json_encode($lottery_reward);
      die;
    }
    $lottery_reward = array('result' => 1, 'award_id' => '', 'award_name' => '', 'award_writer' => '');
    $broker_id = $this->user_arr['broker_id'];
    //查看抽奖积分
    $credit_total = $this->broker_info_model->get_credit_by_broker_id($broker_id);
    $credit_num = intval($credit_total / 500);
    if ($credit_num > 0) {
      $insert_win_data = array();
      $reward_type = '';
      $reward = $this->gift_manage_model->get_reward_type();
      $format_reward = change_to_key_array($reward, 'id');

      $rid = 0;
      foreach ($reward as $key => $val) {
        $arr[$val['id']] = intval($val['rate'] * 1000000);
      }
      //开始摇奖
      $rid = $this->get_rand($arr);
      //以防数据错乱
      $rid_a = array(3, 8); //指定数组
      $rid_b = array_rand($rid_a, 1); //取得数组$a中三个随机的键值。
      if ($rid <= 0 || $rid > 10) {
        $rid = $rid_a[$rid_b];//如果没有抽到奖，默认为谢谢参与
      }
      if ($format_reward[$rid]['gift_id']) {
        $gift_data = $this->gift_manage_model->get_by_id($format_reward[$rid]['gift_id']);
        //判断是否有库存
        if ($gift_data['stock'] < 1) {
          $rid = $rid_a[$rid_b];//如果如果库存不足，直接默认到谢谢参与
        } else {
          //判断当月此商品抽中限额是否已达到
          $y = date("Y");
          $m = date("m");
          $m = sprintf("%02d", intval($m));
          $y = str_pad(intval($y), 4, "0", STR_PAD_RIGHT);
          $m > 12 || $m < 1 ? $m = 1 : $m = $m;
          $firstday = strtotime($y . $m . "01000000");
          $firstdaystr = date("Y-m-01", $firstday);
          $lastday = strtotime(date('Y-m-d 23:59:59', strtotime("$firstdaystr +1 month -1 day")));
          $where = 'type = 2 and gift_id = ' . $format_reward[$rid]['gift_id'] . ' and create_time > ' . $firstday . ' and create_time < ' . $lastday;
          $gift_count = $this->gift_exchange_record_base_model->count_by($where);
          if ($gift_count >= $gift_data['raffle_num'] && $gift_data['raffle_num']) {//达到上限，默认转到谢谢参与，并扣除积分
            $rid = $rid_a[$rid_b];//如果没有抽到奖，默认为谢谢参与
          }
        }
      } else {
        $rid = $rid_a[$rid_b];//如果没有抽到奖，默认为谢谢参与
      }
      //产生抽奖记录
      if ($rid != 3 && $rid != 8) {
        $result = $this->gift_manage_model->raffle($broker_id, $format_reward[$rid]['gift_id']);
        $gift_data = $this->gift_manage_model->get_by_id($format_reward[$rid]['gift_id']);
        $over_exchange_num = $gift_data['over_exchange_num'];
        $stock_num = $gift_data['stock'];
        $update_gift = array('over_exchange_num' => $over_exchange_num + 1, 'stock' => $stock_num - 1);
        $this->gift_manage_model->update_by_id($format_reward[$rid]['gift_id'], $update_gift);
      } else {//未中奖只扣积分
        $result = $this->gift_manage_model->raffle($broker_id, $format_reward[$rid]['gift_id'], $format_reward[$rid]['score']);
        $gift_data = $this->gift_manage_model->get_by_id($format_reward[$rid]['gift_id']);
        $over_exchange_num = $gift_data['over_exchange_num'];
        $stock_num = $gift_data['stock'];
        $update_gift = array('over_exchange_num' => $over_exchange_num + 1);
        $this->gift_manage_model->update_by_id($format_reward[$rid]['gift_id'], $update_gift);
      }
      if ($result['status'] == 1) {
        //客户端返回奖口类型
        $lottery_reward['award_id'] = $rid;
        $lottery_reward['award_writer'] = '<div class="app_cj_con_zj_inf_sucess"><b>恭喜! 您已抽中奖品</b><span><strong>' . $format_reward[$rid]['name'] . '</strong></span><p>请等待客服人员与您联系</p></div>';
        $lottery_reward['rand_num'] = $rid;
      } else {
        if ($result['code']) {
          $lottery_reward['award_writer'] = $result['msg'];
        } else {
          $lottery_reward['award_writer'] = '抽奖失败，请重试！';
        }
      }
    } else {
      //根本没有抽奖的机会
      //$lottery_reward['award_name'] = '温馨提示';
      $lottery_reward['award_writer'] = '<span class="app_cj_con_zj_inf_lose">您当前的积分不足！<br/>攒够积分下次再来抽奖吧。</span>';
    }
    $lottery_reward['credit_total'] = $this->broker_info_model->get_credit_by_broker_id($broker_id);
    $lottery_reward['credit_num'] = intval($lottery_reward['credit_total'] / 500);

    echo json_encode($lottery_reward);
  }

  //中奖概率计算
  function get_rand($proArr)
  {
    $result = '';
    //概率数组的总概率精度
    $proSum = array_sum($proArr);
    //概率数组循环
    foreach ($proArr as $key => $proCur) {
      $randNum = mt_rand(1, $proSum);
      if ($randNum <= $proCur) {
        $result = $key;
        break;
      } else {
        $proSum -= $proCur;
      }
    }
    unset ($proArr);

    return $result;
  }

  /**
   * 初始化分页参数
   *
   * @access public
   * @param  int $current_page
   * @param  int $page_size
   * @return void
   */
  private function _init_pagination($current_page = 1, $page_size = 0)
  {
    /** 当前页 */
    $this->_current_page = ($current_page && is_numeric($current_page)) ?
      intval($current_page) : 1;

    /** 每页多少项 */
    $this->_limit = ($page_size && is_numeric($page_size)) ?
      intval($page_size) : $this->_limit;

    /** 偏移量 */
    $this->_offset = ($this->_current_page - 1) * $this->_limit;

    if ($this->_offset < 0) {
      redirect(base_url());
    }
  }

}

/* End of file my_growing_credit.php */
/* Location: ./application/mls/controllers/my_growing_credit.php */
