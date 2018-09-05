<?php
/**
 * The purpose of this class is to simply allow for a fairly
 * simplistic an lightweight query building tool that allows
 * one to develop a reasonably simplistic query. Please take
 * note that this class is not made to replace the likes of
 * PDO, it's simply a way in which you can write your query
 * using an object and methods attatched to this object.
 *
 * @author    Joseph Evans <joeevs196@gmail.com>
 * @version   1.0.0
 * @since     05/09/2018
 * @license   MIT
 * @copyright (c) Joseph Evans 2018
 */
class QueryBuilder {
  private $__select;
  private $__from;
  private $__where;
  private $__joins;
  private $__joinType;

  /**
   * This is just a general constructor, nothing
   * special here.
   *
   * @return QueryBuilder
   */
  public function __construct () {
    $this->__select = "";
    $this->__from = "";
    $this->__where = "";
    $this->__table = "";
    $this->__joinType = "";
    $this->__joins = array();
  }

  /**
   * The purpose of this method is to allow a developer to
   * state the select clause within their query.
   *
   * @return QueryBuilder
   */
  public function select ($select) {
    if (strpos(strtoupper($select), 'SELECT') === false) {
      $select = "SELECT " . $select;
    }

    $this->__select = $select;
    return $this;
  }

  /**
   * The purpose of this method is to allow a developer to
   * state the from caluse within their query.
   *
   * @return QueryBuilder
   */
  public function from ($from) {
    if (strpos(strtoupper($from), 'FROM') === false) {
      $from = " FROM " . $from;
    }

    $this->__from = $from;
    return $this;
  }

  /**
   * The purpose of this method is to allow a developer to
   * state the where clause within their query.
   *
   * @return QueryBuilder
   */
  public function where ($where) {
    if (strpos(strtoupper($where), 'WHERE') === false) {
      $where = " WHERE " . $where;
    }

    $this->__where = $where;
    return $this;
  }

  /**
   * The purpose of this method is to essentially allow a
   * developer to define a plain join within the query.
   *
   * @return QueryBuilder
   */
  public function join ($table) {
    if (isset($table) && $table != "") {

      if (strpos(strtoupper($table), 'JOIN') === false) {
        $table = " JOIN " . $table;
      }

      $this->__joinType = $table;
    }
    return $this;
  }

  /**
   * The purpose of this method is to essentially allow a
   * developer to define an inner join within the query.
   *
   * @return QueryBuilder
   */
  public function innerJoin ($table) {
    if (isset($table) && $table != "") {

      if (strpos(strtoupper($table), 'INNER JOIN') === false) {
        $table = " INNER JOIN " . $table;
      }

      $this->__joinType = $table;
    }
    return $this;
  }

  /**
   * The purpose of this method is to essentially allow a
   * developer to define a right join within the query.
   *
   * @return QueryBuilder
   */
  public function rightJoin ($table) {
    if (isset($table) && $table != "") {

      if (strpos(strtoupper($table), 'RIGHT JOIN') === false) {
        $table = " RIGHT JOIN " . $table;
      }

      $this->__joinType = $table;
    }
    return $this;
  }

  /**
   * The purpose of this method is to essentially allow a
   * developer to define a left join within the query.
   *
   * @return QueryBuilder
   */
  public function leftJoin ($table) {
    if (isset($table) && $table != "") {

      if (strpos(strtoupper($table), 'LEFT JOIN') === false) {
        $table = " LEFT JOIN " . $table;
      }

      $this->__joinType = " LEFT JOIN " . $table;
    }
    return $this;
  }

  /**
   * The purpose of this method is to essentially allow
   * a developer to define what they'd like to set their
   * selected join to actually join upon.
   *
   * @return QueryBuilder
   */
  public function on ($valueLeft, $valueRight) {
    if (isset($this->__joinType) && $this->__joinType != "") {

      if (strpos(strtoupper($this->__joinType), 'ON') === false) {
        $this->__joinType .= " ON ";
      }

      $this->__joinType .= $valueLeft . " = ". $valueRight;
      array_push($this->__joins, $this->__joinType);
      $this->__joinType = ""; // reset
    }
    return $this;
  }


  /**
   * The purpose of this method is to essentially return
   * the query string.
   *
   * @return string
   */
  public function build () {
    $str = $this->__select;
    $str .= $this->__from;

    foreach ($this->__joins as $join) {
      $str .= $join;
    }

    $str .= $this->__where;

    return $str;
  }
}
?>
