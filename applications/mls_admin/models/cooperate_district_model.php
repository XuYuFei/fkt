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
 * report_model CLASS
 *
 * 公盘公客举报
 *
 * @package         MLS
 * @subpackage      Models
 * @category        Models
 * @author          sun
 */
load_m("Cooperate_district_base_model");

class Cooperate_district_model extends Cooperate_district_base_model
{

    /**
     * 类初始化
     */
    public function __construct()
    {
        parent::__construct();
    }
}

/* End of file report_model.php */
/* Location: ./application/mls/models/report_model.php */
