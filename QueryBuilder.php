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
 * @version   1.0.1
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
    private $__order;
    private $__orderCol;
    private $__group;

    /**
     * This is just a general constructor, nothing
     * special here.
     */
    public function __construct () {
        $this->__select = "";
        $this->__from = "";
        $this->__where = "";
        $this->__joinType = "";
        $this->__orderCol = "";
        $this->__order = "";
        $this->__group = "";
        $this->__joins = array();
    }

    /**
     * The purpose of this method is to allow a developer to
     * state the select clause within their query.
     *
     * @param  $select {string}
     * @return QueryBuilder
     */
    public function select (string $select) {
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
     * @param  $from {string}
     * @return QueryBuilder
     */
    public function from (string $from) {
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
     * @param  $where {string}
     * @return QueryBuilder
     */
    public function where (string $where) {
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
     * @param  $table {string}
     * @return QueryBuilder
     */
    public function join (string $table) {
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
     * @param  $table {string}
     * @return QueryBuilder
     */
    public function innerJoin (string $table) {
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
     * @param  $table {string}
     * @return QueryBuilder
     */
    public function rightJoin (string $table) {
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
     * @param  $table {string}
     * @return QueryBuilder
     */
    public function leftJoin (string $table) {
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
     * @param  $valueLeft   {string}
     * @param  $valueRight  {string}
     * @return QueryBuilder
     */
    public function on (string $valueLeft, string $valueRight) {
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
     * The purpose of this method is to simply allow
     * a developer to execute the group by clause
     * within their query
     *
     * @param  $group {string}
     * @return QueryBuilder
     */
    public function groupBy (string $group) {
        if (isset($group) && $group != "") {
            if (strpos(strtoupper($group), 'GROUP BY') === false) {
                $group = " GROUP BY " . $group;
            }

            $this->__group = $group;
        }

        return $this;
    }

    /**
     * The purpose of this method is to simply allow
     * a developer to state what they'd like
     * to order the data by.
     *
     * @param  $column {string}
     * @param  $order  {string}
     * @return QueryBuilder
     */
    public function orderBy (string $column, string $order)
    {
        if (isset($column) && isset($order) && $column != "" && $order != "") {
            if (strpos(strtoupper($column), 'ORDER BY') === false) {
                $column = " ORDER BY " . $column;
            }

            $this->__orderCol = $column . " ";
            $this->__order = $order;
        }
        return $this;
    }

    /**
     * The purpose of this method is to
     * simply allow a developer to see
     * the current join.
     *
     * @return string
     */
    public function getCurrentJoin () {
        return $this->__joinType;
    }

    /**
     * The purpose of this method is to
     * allow the developer to specify a
     * more complex sub query when using
     * a join.
     *
     * @param $join {string}
     * @return QueryBuilder
     */
    public function setCurrentJoin (string $join) {
        $validJoin = false;
        $completeJoin = false;

        try {
            $caps = strtoupper($join);
            $validJoin = (strpos($caps, 'JOIN') === true);
        } catch (Exception $e) {
            // don't worry about it
        }

        try {
            $caps = strtoupper(join);
            $completeJoin = (stropos($caps, 'ON') === true) && (stropos($caps, '=') === true);
        } catch (Exception $e) {
            // don't worry about it
        }

        if (isset($join) && $join != "" && $validJoin === true) {
            $this->__joinType = $join;
        }

        if ($completeJoin && $validJoin) {
            array_push($this->__joins, $this->__joinType);
            $this->__joinType = "";
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
        $str .= $this->__group;
        $str .= $this->__orderCol . $this->__order;
        return $str;
    }
}
?>
