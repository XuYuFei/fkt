<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * MLS房源管理系统
 *
 * 基于Codeigniter的经纪人房源管理系统
 *
 * MLS房源管理系统是服务于房产经纪人的后台房源管理系统
 *
 *
 * @package         applications
 * @author          xz
 * @copyright       Copyright (c) 2006 - 2012.
 * @version         4.0
 */

// ------------------------------------------------------------------------

/**
 * 逻辑控制器类
 * （Codeigniter所有的控制器都必须继承CI_Controller类，但CI_Controller类位于system目录下，
 *  不方便修改，所以创建MY_Controller，用来继承CI_Controller）
 *
 * 所有的控制器都继承MY_Controller， MY_Controller加载一些公用帮助函数、公用类库等
 *
 * @package         admincp
 * @subpackage      core
 * @category        MY_Controller
 * @author          xz
 */
class MY_Controller extends CI_Controller
{
  protected $user_arr = array();     //已登录用户信息
  protected $user_purview = array(); //用户权限
  protected $user_menu = ''; //用户菜单
  protected $user_func_menu = ''; //用户二级菜单
  protected $user_func_purview = array(); //用户页面功能权限
  protected $company_basic_arr = array(); //当前经济人所在公司基本设置信息

  /**
   * Constructor
   * @param string $type 区分不同模块来加载不同的共用函数、类库（备用扩展）
   */
  public function __construct($type = NULL)
  {
    parent::__construct();
    //判断是否在线，非在线状态将自动跳转登录
    $this->_check_online();

    //加载memcached
    $this->load->library('My_memcached', '', 'mc');

    //为user_arr属性复制
    $this->_init_user_info();

    if (is_full_array($this->user_arr)) {

      if (!$this->isAjax()) {
        //菜单生成
        $this->load->model('purview_tab_model');
        //初始化用户的菜单
        $this->_init_tab_purview();
        //初始化用户的二级菜单
        $this->_init_secondtab_purview();
      }

      //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
      //权限功能api
     // $this->load->model('api_signatory_purview_model');

      //初始化当前经纪人所在公司的基本设置信息
      $this->_init_company_info();
      //@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@@
    }
  }

  /**
   * Init user info, this function is just let us get user's info easier
   */
  private function _init_user_info()
  {
    if ($this->config->item('login_city')) {
      $signatory = $this->signatory_model->get_user_session();
      //把用户信息赋给user_arr，方便使用
      $this->load->model('api_signatory_model');
      $signatory_info = $this->api_signatory_model->get_baseinfo_by_signatory_id($signatory['signatory_id']);
      $signatory_info['city_id'] = $signatory['city_id'];
      $signatory_info['city_spell'] = $signatory['city_spell'];
      $signatory_info['cityname'] = $signatory['cityname'];
      $signatory_info['deviceid'] = $signatory['deviceid'];  //单点登录用
      $signatory_info['osid'] = $signatory['osid'];      //单点登录用
      $this->config->set_item('signatory_info', $signatory_info);
      $this->user_arr = $signatory_info;

      //把组合信息写入SESSION
      $this->signatory_model->set_user_session($signatory_info);
    }
  }

  //一级菜单生成
  private function _init_tab_purview()
  {
    //定位
    $router = where_am_i();
    $class = $router['class'];
    $method = $router['method'];
    $this->user_menu = $this->purview_tab_model->get_tab($class, $method);
  }

  /**
   * 二级菜单生成
   * @param string $class 类名
   * @param string $method 方法名
   */
  private function _init_secondtab_purview()
  {
    //定位
    $router = where_am_i();
    $class = $router['class'];
    $method = $router['method'];

    //如果一级菜单没找到通过二级菜单反推一级菜单
    if ('' == $this->user_menu) {
      $this->user_menu = $this->purview_tab_model->reset_tabs($class, $method);
    }

    $this->user_func_menu = $this->purview_tab_model->get_secondtab($class, $method);
    //用户的树型菜单
    $this->user_tree_menu = $this->purview_tab_model->get_tree_menu($class, $method);
  }

  /**
   * 初始化当前经纪人所在门店的基本设置
   */
  private function _init_company_info()
  {
    $this_signatory_data = $this->user_arr;
    $department_id = intval($this_signatory_data['department_id']);
      $company_id = intval($this_signatory_data['company_id']);
    if (is_int($department_id) && !empty($department_id)) {
      $this->load->model('department_basic_setting_model');
      $company_basic_data = $this->department_basic_setting_model->get_data_by_department_id($department_id);
      if (is_array($company_basic_data) && !empty($company_basic_data)) {
        $this->company_basic_arr = $company_basic_data[0];
      } else {
        $company_basic_default_data = $this->department_basic_setting_model->get_default_data();
        if (is_array($company_basic_default_data) && !empty($company_basic_default_data)) {
          $this->company_basic_arr = $company_basic_default_data[0];
        }
      }
    } elseif (is_int($company_id) && !empty($company_id)) {
        $this->load->model('department_basic_setting_model');
        $company_basic_data = $this->department_basic_setting_model->get_data_by_company_id($company_id);
        if (is_array($company_basic_data) && !empty($company_basic_data)) {
            $this->company_basic_arr = $company_basic_data[0];
        } else {
            $company_basic_default_data = $this->department_basic_setting_model->get_default_data();
            if (is_array($company_basic_default_data) && !empty($company_basic_default_data)) {
                $this->company_basic_arr = $company_basic_default_data[0];
            }
        }
    }

  }

  //无权限访问功能
  public function redirect_purview_none()
  {
    if ($this->isAjax()) {
      if (strpos($_SERVER['HTTP_ACCEPT'], 'json') !== false) {
        echo json_encode(array('errorCode' => '403'));
      } else {
        echo 'errorCode403';
      }
    } else {
      $this->jump(MLS_SIGN_URL . '/purview/none/');
    }
  }

  //无范围权限访问功能
  public function redirect_purview_none_iframe($iframe_id = '')
  {
    if ($this->isAjax()) {
      if (strpos($_SERVER['HTTP_ACCEPT'], 'json') !== false) {
        echo json_encode(array('errorCode' => '403'));
      } else {
        echo 'errorCode403';
      }
    } else {
      $this->jump(MLS_SIGN_URL . '/purview/none_iframe/' . $iframe_id);
    }
  }

    //无范围权限访问功能
    public function purview_none()
    {
        echo "<script>
                   $(function() {
                        window.permission_none();
                    });
            </script>";
    }
  /**
   * 判断功能是否有权限
   * @param string $class 类名
   * @param string $method 方法名
   * @return ['area' : 1(1本人，2门店，3公司), 'auth' => 1（1有权限 0 无权限）]
   */
  public function get_func_purview($class, $method)
  {
    if ($class == 'welcome') {
      return;
    } //默认窗口不管
    //return $this->api_signatory_purview_model->get_func_purview($class, $method);
  }

  /**
   * 批量判断功能是否有权限
   * @param array $arr_func 功能数组 array(array('class' => 'sell', 'method' => 'add')))
   * @return [['area' : 1(1本人，2门店，3公司), 'auth' => 1（1有权限 0 无权限）, 'class' => 'sell', 'method' : 'list'],
   * ['area' : 1(1本人，2门店，3公司), 'auth' => 1（1有权限 0 无权限）, 'class' => 'sell', 'method' : 'add']]
   */
  public function get_batch_func_purview($arr_func)
  {
   // return $this->api_signatory_purview_model->get_batch_func_purview($arr_func);
  }

  /**
   * 比较查出的权限是否和经纪人所寄于的权限是否一致
   * @param array $area_auth 功能默认权限
   * @param array $compare_param 对比的权限
   */
  public function compare_has_purview($area_auth, $compare_param)
  {
    //return $this->api_signatory_purview_model->compare_has_purview($area_auth, $compare_param);
  }

  /**
   * Check online function
   * If you want to make someone pass this check all the time,
   * you just need to add it in the config array.
   */
  private function _check_online()
  {
    //定位
    $router = where_am_i();
    $class = $router['class'];
    $method = $router['method'];
    //加载配置
    $no_login_check_arr = $this->config->item('no_login_check');
    //加载signatory模型类
    $this->load->model('signatory_model');
    //判断是否在线
    $isonline = isset($no_login_check_arr[$class])
    && is_array($no_login_check_arr[$class])
    && !empty($no_login_check_arr[$class])
    && in_array($method, $no_login_check_arr[$class])
      ? TRUE : $this->signatory_model->check_online();

    //不在线跳出登录
    if (!$isonline) {
      //跳转到登录页面
      $this->redirectLogin();
      exit;
    }
  }

  /**
   * 页面跳转中间页
   * @param string $url 跳转URL地址
   * @param string $msg 页面方案
   * @param int $time 间隔时间点
   */
  public function jump($url, $msg = '', $time = 2000)
  {
    $time = intval($time);
    $goto_url = '' != $url ? $url : MLS_SIGN_URL;

    if ('' != $msg && $time > 0) {
      $data_arr['url'] = $goto_url;
      $data_arr['msg'] = $msg;
      $data_arr['time'] = $time;
      //调用跳转页面
      $this->load->view('jump', $data_arr);
    } else {
      redirect($goto_url);
    }
  }

  /**
   * 引入框体体
   * @param string $page 页面
   * @param array $data_view 参数
   */
  public function frame($page, $data_view)
  {
    $this->load->view($page, $data_view);
  }


  /**
   * 引入模板
   * @param string $page 页面
   * @param array $data_view 参数
   */
  public function view($page, &$data_view)
  {
    //头部模板
    $this->load->view('header', $data_view);

    //页面模板
    $this->load->view($page, $data_view);

    //页面尾部
    $this->load->view('footer', $data_view);
  }


  /**
   * 页面跳转
   */
  protected function redirectLogin()
  {
    if ($this->isAjax()) {
      if (strpos($_SERVER['HTTP_ACCEPT'], 'json') !== false) {
        echo json_encode(array('errorCode' => '401'));
      } else {
        echo 'errorCode401';
      }
    } else {
      $this->jump(MLS_SIGN_URL . '/login/');
    }
  }

  /**
   * 判断是否是ajax请求
   * @return boolean
   */
  public function isAjax()
  {
    return (isset($_SERVER['HTTP_X_REQUESTED_WITH'])
      && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest');
  }
}
/* End of file MY_Controller.php */
/* Location: ./applications/core/MY_Controller.php */
