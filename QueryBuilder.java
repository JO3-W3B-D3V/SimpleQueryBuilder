
/**
 * @author  JoesephEvans <p><br/><b>Contact: </b> joeevs196@gmail.com</p>
 * @version 1.0.0
 * @class   The purpose of this class is to implement a 
 *          feature much like jOOQ, only a much more 
 *          minimal solution. This implementation is more 
 *          than likely vastly simplistic in comparison to 
 *          jOOQ.
 * 
 * @todo    Consider implementing a feature where one is able 
 *          to include many subqueries, with clause(es), etc. 
 * @todo    A simple 'switch' feature to toggle whether the 
 *          developer would like to sort in ascending order 
 *          by default or descending order by default. 
 * 
 * @note    The purpose of this code is not to replace tools 
 *          such as HQL, but rather to work along side, specifically 
 *          when using a native query. 
 */

import java.util.List;
import java.util.ArrayList;

public class QueryBuilder {
    private String select;
    private String from;
    private String where;
    private String join;
    private String orderBy;
    private String groupBy;
    private String build;
    private final List<String> joins = new ArrayList<>();
    private final static String NEW_LINE = "\n"; // short for newline 
    
    
    
    /*
     * These properties are not YET being used 
     * however the plan is to implement their use(s) at a 
     * later date. 
     */
    @SuppressWarnings("unused")
    private String with;
    private final List<String> withClauses = new ArrayList<>();
    private String subQuery;
    private String limit;
    private String offset;
    
    
    
    /**
     * This is just a very basic constructor, it serves nothing 
     * more than allowing other objects/classes the ability to 
     * create an object from this class. 
     */
    public QueryBuilder () {
        // TODO ... 
        this.build = "";
    }
    
    
    
    /**
     * @ignore 
     * @param string {String}
     * @return Boolean 
     * 
     * The purpose of this method is to simply see if 
     * a provided string is suitable to use. Don't worry, 
     * this code uses a technique called 'logical short-
     * circuiting', as you can see here, I'm checking that 
     * if the string is null first. As we check if an argument 
     * is null FIRST, this prevents a null pointer exception 
     * from occuring when we run the 'isEmpty' method on the 
     * provided string. 
     */
    private Boolean isValidString (String string) {
        return string != null && !string.isEmpty();
    }
    
    
    
    /**
     * @ignore
     * @param reference {String}
     * @param operation {String}
     * @return String
     * 
     * The purpose behind this method is to simply allow 
     * a developer the ability to combine two strings 
     * together, provided both strings are okay to use.
     */
    private String process (String reference, String operation) {
        if (isValidString(reference) && isValidString(operation)) {
            if (!reference.toUpperCase().contains(operation.toUpperCase())) {
                reference = " " + operation.toUpperCase() + " " + reference;
            }
        }
        
        return reference;
    }
    
    
    
    /**
     * @param  select {String}
     * @return QueryBuilder
     * 
     * The purpose behind this method is to allow a developer 
     * the ability to execute the 'select' statement.
     */
    public QueryBuilder select (String select) {
        this.select = process(select, "SELECT");
        return this;
    }
    
    
    
    /**
     * @param  from {String}
     * @return QueryBuilder
     * 
     * The purpose of this method is to allow a developer to include
     * a from clause within the current query.
     */
    public QueryBuilder from (String from) {
        this.from = process(from, "FROM");
        return this;
    }    
    
    
    
    /**
     * @param  where {String}
     * @return QueryBuilder 
     * 
     * The purpose of this method is to allow a developer to state 
     * the with clause.
     */
    public QueryBuilder where (String where) {
        this.where = process(where, "WHERE");
        return this;
    }
    
    
    
    /**
     * @param  join {String}
     * @return QueryBuilder
     * 
     * The purpose of this method is to allow a developer to do 
     * a simple join. 
     */
    public QueryBuilder join (String join) {
        this.join = process(join, "JOIN");
        return this;
    }
    
    
    
    /**
     * @param  join {String}
     * @return QueryBuilder 
     * 
     * The purpose of this method is to allow a developer to 
     * do a simple inner join. 
     */
    public QueryBuilder innerJoin (String join) {
        this.join = process(join, "INNER JOIN");
        return this;
    }
    
    
    
    /**
     * @param  join {String}
     * @return QueryBuilder
     * 
     * The purpose of this method is to allow a developer to 
     * perform a simple right join.
     */
    public QueryBuilder rightJoin (String join) {
        this.join = process(join, "RIGHT JOIN");
        return this;
    }
    
    
    
    /**
     * @param  join {String}
     * @return QueryBuilder
     * 
     * The purpose of this method is to allow a developer to 
     * perform a simple left join. 
     */
    public QueryBuilder leftJoin (String join) {
        this.join = process(join, "LEFT JOIN");
        return this;
    }
    
    
    
    /**
     * @param  on {String}
     * @return QueryBuilder
     * 
     * The purpose of this simple method is to allow a developer 
     * to complete the join so to speak. 
     */
    public QueryBuilder on (String on) {
        if (isValidString(on)) {
            
            if (!on.replaceAll(" ", "").toUpperCase().contains("ON")) {
                on = " ON " + on;
            }
            
            join += on;
            joins.add(join);
            join = "";
        }
        
        return this;
    }
    
    
    
    /**
     * @param  column1 {String}
     * @param  column2 {String}
     * @return QueryBuilder 
     * 
     * The purpose of this method is to mimic the one above, 
     * but allowing the developer to provide two strings instead 
     * of one long one. 
     */
    public QueryBuilder on (String column1, String column2) {
        if (!isValidString(column1) || !isValidString(column2)) {
            return this;
        }
        
        column1 = column1.replaceAll("=", "");
        column2 = column2.replaceAll("=", "");
        
        String on = " ON " + column1 + " = " + column2;
        join += on;
        joins.add(join);
        join = "";
    
        return this;
    }
    
    
    /**
     * @param  col {String}
     * @return QueryBuilder 
     * 
     * @todo   Allow for the ability to toggle
     *         the default sorting order.
     * 
     * The purpose of this method is to allow 
     * a developer to just order by a specific 
     * column, disregarding the actual order.
     * At the moment it currently will order by 
     * ascending order, however there's the idea 
     * to possibly allow a developer to somehow 
     * toggle this value somewhere.
     */
    public QueryBuilder orderBy (String col) {
        orderBy = process(col, "ORDER BY");
        orderBy += " ASC ";
        return this;
    }
    
    
    
    /**
     * @param  col   {String}
     * @param  order {String}
     * @return QueryBuilder 
     * 
     * The purpose of this method is to simply allow the 
     * developer to state how they'd like to order the query.
     */
    public QueryBuilder orderBy (String col, String order) {
        this.orderBy = process(col, "ORDER BY");
        
        if (isValidString(order)) {
            order = order.toUpperCase().replaceAll(" ", "");
            
            if ((!order.equals("ASC")) && (!order.equals("DESC"))) {
                order = "ASC";
            }
        } else {
            order = "ASC";
        }
        
        this.orderBy += order;
        
        return this;
    }
    
    
    
    /**
     * @param String {group}
     */
    public QueryBuilder groupBy (String group) {
        this.groupBy = process(group, "GROUP BY");
        return this;
    }
    
    
    
    /**
     * @return String 
     * 
     * The purpose of this method is to simply allow a developer 
     * to get the query string value from the current object.
     */
    public String build () {
        build = "";
        build += select + NEW_LINE;
        build += from + NEW_LINE;
        
        joins.forEach(j -> { build += j + NEW_LINE; });
        build += where + NEW_LINE;
        build += orderBy + groupBy;
        
        return build;
    }
}
