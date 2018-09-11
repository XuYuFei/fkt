<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/**
 * zsb
 *
 * 实收实付类库
 *
 * @package         mls
 * @author          EllisLab Dev Team
 * @copyright       Copyright (c) 2006 - 2014
 * @link            http://nj.sell.house.com
 * @since           Version 1.0
 * @filesource
 */

// ------------------------------------------------------------------------

/**
 * 实收实付流程类
 *
 *
 * @package         zsb
 * @subpackage      Models
 * @category        Models
 * @author          No.one
 */
class Contract_flow_model extends MY_Model
{
  private $_flow_tbl = 'contract_flow';

  private $_search_fields = array();

  /**
   * Constructor
   */
  public function __construct()
  {
    parent::__construct();
  }

  /**
   * 获交易表名称
   *
   * @access  public
   * @param  void
   * @return  string 出售、求组信息表名称
   */
  public function get_tbl()
  {
    return $this->_flow_tbl;
  }


  /**
   * 获取设置合同实收实付信息表需要查询的字段数组
   *
   * @access  public
   * @param  void
   * @return  array  房源需求信息表需要查询的字段数组
   */
  public function get_search_fields()
  {
    return $this->_search_fields;
  }

  /**
   * 获取符合条件的房源需求信息列表
   *
   * @access  protected
   * @param  string $cond_where 查询条件
   * @param  int $offset 偏移数,默认值为0
   * @param  int $limit 每次取的条数，默认值为10
   * @param  string $order_key 排序字段，默认值
   * @param  string $order_by 升序、降序，默认降序排序
   * @return  array   出售出租信息列表
   */
  public function get_list_by_cond($cond_where = '', $offset = 0, $limit = 10,
                                   $order_key = 'id', $order_by = 'ASC'
  )
  {
    //房源需求信息表
    $tbl_demand = $this->get_tbl();

    //需要查询的房源需求信息字段
    $select_fields = $this->get_search_fields();

    if (isset($select_fields) && !empty($select_fields)) {
      //查询字段
      $select_fields_str = implode(',', $select_fields);
      $this->dbback_city->select($select_fields_str);
    }

    //查询条件
    if ($cond_where != '') {
      $this->dbback_city->where($cond_where);
    }

    //排序条件
    $this->dbback_city->order_by($order_key, $order_by);

    //查询
    if (empty($limit) && empty($offset)) {
      $arr_data = $this->dbback_city->get($tbl_demand)->result_array();
    } else {
      $arr_data = $this->dbback_city->get($tbl_demand, $limit, $offset)->result_array();
    }

//        echo $this->dbback_city->last_query();
    return $arr_data;
  }

  /**
   * 添加实收实付
   * @param array $paramlist 实收实付字段
   * @return insert_id or 0
   */
  function add_flow($paramlist = array())
  {
    if (!empty($paramlist) && is_array($paramlist)) {
      $this->db_city->insert($this->_flow_tbl, $paramlist);//插入数据
      if (($this->db_city->affected_rows()) >= 1) {
        $result = $this->db_city->insert_id();//如果插入成功，则返回插入的id
      } else {
        $result = 0;    //如果插入失败,返回0
      }
    } else {
      $result = 0;
    }

    return $result;
  }

  /**
   * 获取要实收实付的信息
   * @date
   * @author
   */
  function get_info($where = array(), $where_in = array(), $like = array(), $offset = 0, $limit = 10, $database = 'db_city')
  {
    $result = $this->get_data(array('form_name' => 'contract_flow', 'where' => $where, 'where_in' => $where_in, 'like' => $like, 'offset' => $offset, 'limit' => $limit), $database);
    return $result;
  }

  /**
   * 设置事件状态位
   *
   * @access  public
   * @param  array $data 添加数据,string $database 数据库
   * @return  int
   */
  public function flow_update($id, $paramlist = array())
  {
    $result = $this->modify_data(array('id' => $id), $paramlist, 'db_city', 'contract_flow');
    return $result;
  }


  //总计数据
  public function get_total($where)
  {
    //查询字段
    $this->db_city->select("SUM(collect_money) AS collect_money_total,SUM(pay_money) AS pay_money_total");

    if ($where) {
      //查询条件
      $this->db_city->where($where);
    }

    $this->db_city->from($this->_flow_tbl);
    //返回结果
    return $this->db_city->get()->row_array();
  }


  /**
   * 获取符合条件的信息条数
   *
   * @access  protected
   * @param  string $cond_where 查询条件
   * @return  int   符合条件的信息条数
   */
  public function get_count_by_cond($cond_where = '')
  {
    $count_num = 0;


    //查询条件
    if ($cond_where != '') {
      $this->dbback_city->where($cond_where);
      $count_num = $this->dbback_city->count_all_results('contract_flow');
    }

    return intval($count_num);
  }

}

?>
