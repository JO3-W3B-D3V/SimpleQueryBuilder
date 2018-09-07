# SimpleQueryBuilder
The purpose behind this project is to implement a _nice enough_ object which has a set of methods, which will allow you to write a traditional SQL query. This project is a simple approach to allowing people to migrate away from having to do things like: 

```php
$queryString = 'SELECT X FROM TEST INNDER JOIN OTHER_TEST';
$queryString .= 'ON TEXT.ID = OTHER_TEST.ID';
$queryString .= 'LEFT JOIN OTHER_TABLE ON OTHER_TABLE.CID = TEST.CID';
$queryString .= 'ORDER BY X GROUP BY T';
```

Now while you _may_ think there's nothing wrong with the PHP example above, personally I think it for one looks disgusting and is harder to manage than a neater OOP based approach. This project by no means includes anything amazing, there's no security features, no performance increases, etc, this project is just here to simply make it easier on the eyes when you build your queries. You may not even like the way in which I've designed the code, you may actually prefer the text approach, which is fine, it's just a _personal_ preference. 

## Honest Moment
I would actually suggest that **for the time being**, if you want to write some complex query that includes a series of nested joins, and just all kinds of crazy and magical stuff happens, in that case I **do** suggest you stick to the string based approach. Alternatively you could use anothre implementation, there are many, and there are many in many different languages, a quick example being jOOQ _(Java)_. 

## Demo(s)
### PHP
This is a simple demo of what you could do in PHP. 
```php
$qb = new QueryBuilder();

$q = $qb->select("x")->
  from("test")->
  where("test.x = :username")->
  innerJoin("otherTest")->
  on("test.x", "otherTest.x")->
  leftJoin("nextTbl")->
  on("nextTbl.x", "test.x")->
  build();

echo($q);
```

### Node 
This is another simple demo, just this time using JavaScript. The only major difference as you can see is the syntax. 

#### Notes About The JS Implementation 
I believe that you should be aware, when using the Node one I've deliberately forced the user fo this code to use the methods in a specific order, I find this implementation much more appealing, _personally_. 

This implementation is **not** designed to work with NoSQL such as MongoDB or anything along those lines, the purpose of this project is to simply implement a nicer approach to writing your SQL queries. 

```javascript
let qb = new QueryBuilder();
let query = qb.select("x")
  .from("test")
  .where("test.age > 10")
  .innerJoin("otherTest")
  .on("test.id = otherTest.id")
  .orderBy("name", "asc")
  .groupBy("x")
  .build();
console.log(query);
```
