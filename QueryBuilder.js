/**
 * @author    Joseph Evans <joeevs196@gmail.com>
 * @version   1.0.0
 * @since     07/090/2018
 * @license   MIT 2018
 * @copyright Joseph Evans 2018 (c)
 * @desc      The purpose of this file is simple, it's to allow
 *            a node application to interact with SQL in such
 *            a way where you can write queries in a very neat
 *            and tidy way. Please be aware that this code does 
 *            not include any form of security layer's, however 
 *            I believe that it could easily be modified to do 
 *            so, etc. Using this tool alone, your SQL query 
 *            WOULD be open to SQL injection. WARNING!!! 
 */



/**
 * @global
 * @function QueryBuilder
 * @desc     The purpose of this function being a function is
 *           down to the fact that with JavaScript class(es),
 *           you can't (at this moment in time) have private
 *           members/properties, which kinda sucks. So the
 *           old OOP approach with JavaScript is actually
 *           necessary here. This may work client side to,
 *           ALTHOUGH it's not designed or intended to work
 *           client side. I wouldn't personally advise
 *           ANYONE to use such a tool client side, that's
 *           just asking for a world of pain.
 *
 * @note     Back to the ES5 approach to implementing this
 *           class, I personally find it rather odd how ES6
 *           does not currently have native support for private
 *           members/properties. You can use things like
 *           symbol(s), however those can still be accessed
 *           outside the scope of the object/class.
 */
function QueryBuilder () {



  /**
   * @ignore
   * @desc   These properties here are all private and cannot
   *         be accessed outside the scope of this class, at
   *         least not without some form of getter/setter method(s).
   */
  let select;
  let from;
  let where;
  let join;
  let orderby;
  let groupby;
  let joinType = ' INNER ';
  let joins = [];

  let _from;
  let _where;
  let _join;
  let _on;
  let _orderby;
  let _groupby;
  let _build;



  /**
   * @ignore
   * @private
   * @param   {String} string
   * @desc    The purpose of this method is to ensure
   *          that a given string.
   */
  const validString = (string) => {
    return string != null && typeof string == "string" && string.length > 0;
  };



  /**
   * @ignore
   * @private
   * @param   {String} string
   * @param   {String} operation
   * @return  {String}
   * @desc    The purpose of this method is to, 'kinda',
   *          allow us to easily clean some string in a
   *          specific manner.
   */
  const process = (string, operation) => {
    if (validString(string) && validString(operation)) {
      if (string.toUpperCase().indexOf(operation.toUpperCase()) < 0) {
        string = " " + operation + " " + string;
      }
    }

    return string;
  };



  /**
   * @private
   * @param   {String} f
   * @return  {Object}
   * @desc    The purpose of this method is to allow the
   *          developer to describe the from clause.
   */
  _from = (f) => {
    if (validString(f)) {
      from = process(f, ' FROM ');
    }

    console.log(from);
    return {
      where:_where,
      build: _build
    };
  };



  /**
   * @private
   * @param   {String} w
   * @return  {Object}
   * @desc    The purpose of this method is to allow a
   *          developer to write the where clause within
   *          this query.
   */
  _where = (w) => {
    if (validString(w)) {
      where = process(w, ' WHERE ');
    }


    return {
      join: (j) => {
        joinType = ' ';
        return _join(j);
      },
      leftJoin: (j) => {
        joinType = ' LEFT ';
        return _join(j);
      },
      rightJoin: (j) => {
        joinType = ' RIGHT ';
        return _join(j);
      },
      innerJoin: (j) => {
        joinType = ' INNER ';
        return _join(j);
      },
      customJoin: (t, j) => {
        if (validString(t)) {
          joinType = ' ' + t.toUpperCase() + ' ';
        } else {
          joinType = ' INNER '; // assume
        }
        return _join(j);
      },
      orderBy: _orderby,
      groupby: _groupby,
      build: _build
    };
  };



  /**
   * @private
   * @param   {String} j
   * @return  {Object}
   * @desc    The purpose of this method is to simply allow
   *          the developer to describe a join of some sort.
   */
  _join = (j) => {
    if (validString(j)) {
      if (joinType.toUpperCase().indexOf('JOIN') < 0) {
        joinType += ' JOIN ';
      }

      join = process(j, joinType);
    }

    return { on: _on};
  };



  /**
   * @private
   * @param  {String} o
   * @return {Object}
   * @desc   The purpose of this method is to simply allow the
   *         developer to state what they'd like to make a recent
   *         join on.
   */
  _on = (o) => {
    if (validString(o)) {
      join += process(o, ' ON ');
      joins.push(join);
      join = "";
    }

    return {
      join: () => {
        joinType = ' ';
        return _join(arguments);
      },
      leftJoin: () => {
        joinType = ' LEFT ';
        return _join(arguments);
      },
      rightJoin: () => {
        joinType = ' RIGHT ';
        return _join(arguments);
      },
      innerJoin: () => {
        joinType = ' INNER ';
        return _join(arguments);
      },
      customJoin: (t) => {
        if (validString(t)) {
          joinType = ' ' + t.toUpperCase() + ' ';
        }
        return _join(arguments);
      },
      orderBy: _orderby,
      groupby: _groupby,
      build: _build
    };
  };



  /**
   * @private
   * @param   {String} o
   * @param   {String} col
   * @return  {Object}
   * @desc    The purpose of this method is to simply
   *          allow the developer to state how they'd
   *          like to order their query results.
   */
  _orderby = (o, col) => {
    if (validString(o)) {
      orderby = process(o, ' ORDER BY ');

      if (validString(col) &&
        col.toUpperCase().replace(/\ /g, '') != 'ASC' &&
        col.toUpperCase().replace(/\ /g, '') != 'DESC') {
        col = ' ASC ';
      } else {
        col = ' ' + col.toUpperCase() + ' ';
      }

      orderby += col;
    }

    let obj = {};
    obj.build = _build;

    if (groupby == "" || groupby == null) {
      obj.groupBy = _groupby;
    }

    return obj;
  };



  /**
   * @private
   * @param   {String} g
   * @return  {Object}
   * @desc    The purpose of this method is to simply
   *          allow a developer to execute an SQL group by
   *          clause.
   */
  _groupby = (g) => {
    if (validString(g)) {
      groupby = process(g, ' GROUP BY ');
    }

    let obj = {};
    obj.build = _build;

    if (orderby == "" || orderby == null) {
      obj.orderBy = _orderby;
    }

    return obj;
  };



  /**
   * @private
   * @return  {String}
   * @desc    The purpose of this method is to essentially
   *          get the SQL statement that this object has been
   *          in the process of building. 
   */
  _build = () => {
    let string = '';
    let n = '\n';

    string += select + n + from + n + where + n;
    joins.forEach((x) => { string += x + n; });
    string += orderby + n + groupby + n;
    return string;
  };



  // force developer to start with a select statement
  const _public = {


    /**
     * @public
     * @function
     * @param    {String} s
     * @return   {Object}
     * @desc     The purpose of this method is to
     *           allow a developer to write a select
     *           statement. If the developer enters a
     *           valid string then they can continue to
     *           the 'from' method.
     */
    select: (s) => {
      if (!validString(s)) {
        return _public;
      } else {
        select = process(s, ' SELECT ');
        return {from : _from};
      }
    }
  };
  
  return _public;
}
