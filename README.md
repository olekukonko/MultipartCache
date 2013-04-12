MultipartCache
==============

This class would help split data larger than `1MB` in Memcache. 


##### Example 1 ;
```PHP
$largeSet = range(0, 100000);
$key = "largeSet";

$cache = new MultipartCache();
$cache->setLimit(1024);
$cache->set($key, $largeSet);
```
To get last Split Information

```PHP
print_r($cache->getLastSplit());
```

To get the data stored 

```PHP
$dataFromCache = $cache->get($key);
```


#### Note: This is still a concept and work in progress