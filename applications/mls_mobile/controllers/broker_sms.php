<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * 经纪sms操作 Class
 *
 * @package     mls
 * @subpackage      Controllers
 * @category        Controllers
 * @author      sun
 */
class Broker_sms extends MY_Controller
{

  /**
   * 解析函数
   * @access public
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
    $this->load->model('broker_sms_model');
  }

  //经纪人获取短信验证码
  public function index()
  {
    //获取手机号码和获取验证码类型
    $phone = $this->input->get('phone', TRUE);
    $type = intval($this->input->get('type', TRUE));
    $jid = ltrim($this->input->get('jid', TRUE));
    if ($jid == '')//默认验证码
    {
      $jid = '1';
    }
    if ($type == 1) {
      $type = 'register';
    } else if ($type == 2) {
      $type = 'findpw';
    }
    //返回结果数组
    $result = array('result' => 1, 'msg' => '');
    if ($phone == '' || $type == '') //验证手机号码是否为空
    {
      $result['result'] = 2;
      $result['msg'] = '手机号码不能为空';
    } else if ($type == 'register' || $type == 'findpw') //注册和找回密码
    {
      $this->broker_sms_model->type = $type;
      //经纪人类
      $this->load->model('broker_model');
      //号码是否已经被注册过
      $is_exist_phone = $this->broker_model->is_exist_by_phone($phone);
      //注册帐号时判断号码是否已经注册过
      if ($type == 'register' && $is_exist_phone) {
        $result['result'] = 3;
        $result['msg'] = '此号码已经被注册过';
      }
      //只有注册用户才可找回密码
      if ($type == 'findpw' && !$is_exist_phone) {
        $result['result'] = 5;
        $result['msg'] = '注册用户才可找回密码';
      }
    }
    if ($result['result'] == 1) //前面的都成功
    {
      $is_repeate = $this->broker_sms_model->is_expire_by_phone($phone);
      if ($is_repeate && false) //重复获取
      {
        $result['result'] = 4;
        $result['msg'] = '请不要在一分钟之内重复获取验证码';
      } else {
        $city_spell = 'nj'; //默认南京
        //获取城市
        if ($type == 'register') {
          $city_id = ltrim($this->input->get('city_id', TRUE));
        } else if ($type == 'findpw') {
          //根据手机号码获取
          $broker = $this->broker_model->get_by_phone($phone);
          if (is_full_array($broker)) {
            $city_id = $broker['city_id'];
          }
        }
        if ($city_id) {
          //城市类
          $this->load->model('city_model');
          $city = $this->city_model->get_by_id($city_id);
          $city_spell = $city['spell'];
        }
        //验证码
        $validcode = $this->broker_sms_model->rand_num();
        //插入相应的数据 3 注册
        $insert_id = $this->broker_sms_model->add($phone, $validcode);

        if ($insert_id) //成功后发送相应短信
        {
          //引入SMS类库，并发送短信
          $this->load->library('Sms_codi', array('city' => $city_spell, 'jid' => $jid, 'template' => $type), 'sms');
          $return = $this->sms->send($phone, array('validcode' => $validcode));
          $result['status'] = $return['success'] ? 1 : 0;
          $result['msg'] = $return['success'] ? '短信发送成功' : $return['errorMessage'];
        } else {
          $result['status'] = 0;
          $result['msg'] = '短信获取失败，请重新获取';
        }
      }
    }
    echo json_encode($result);
  }
}
/* End of file broker_sms.php */
/* Location: ./application/mls/controllers/broker_sms.php */
