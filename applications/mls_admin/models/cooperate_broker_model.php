<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * MLS
 *
 * MLS系统类库
 *
 * @package         MLS
 * @author          EllisLab Dev Team
 * @copyright       Copyright (c) 2006 - 2014
 * @link            http://mls.house.com
 * @since           Version 1.0
 */

// ------------------------------------------------------------------------

/**
 * Broker_model CLASS
 *
 * 经纪人业务逻辑类 提供用户在线、注册、登录、修改密码、登出、Session相关
 *
 * @package         MLS
 * @subpackage      Models
 * @category        Models
 * @author          sun
 */
load_m("Cooperate_broker_base_model");

class Cooperate_broker_model extends Cooperate_broker_base_model
{

  /**
   * 用户注册，找回密码验证码有效时长为60s
   * @var int
   */
  public $validcode_expiretime = 60;

  /**
   * 类初始化
   */
  public function __construct()
  {
    parent::__construct();
  }

}

/* End of file Broker_model.php */
/* Location: ./app/models/Broker_model.php */
