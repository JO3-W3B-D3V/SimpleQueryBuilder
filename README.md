# SimpleQueryBuilder
The purpose of this project is to simply allow for a very minimal PHP query builder. 

## Demo
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
