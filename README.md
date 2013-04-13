MultipartCache
==============

This a simple class that extends `PHP` memcache to support saving data larger than `1MB`. 


##### Example 1 ;
```PHP
$largeSet = range(0, 100000);
$key = "largeSet";

$cache = new MultipartCache();
$cache->addserver("127.0.0.1"); // connect to memecache server
$cache->setLimit(1024); 		// Reduce limit to for testing
$cache->set($key, $largeSet);
```
If you just want to see details of how it was save use `MultipartCache::getDetails`

```PHP
print_r($cache->getDetails($key));
```

Sample Output 

	MultipartSplit Object
	(
	    [type:MultipartSplit:private] => array
	    [start:MultipartSplit:private] => largeSet-0
	    [hash:MultipartSplit:private] => 7ad86b0942000e4824b09d8626ca9a660e34c596
	    [limit:MultipartSplit:private] => 10
	    [slices:MultipartSplit:private] => 0
	    [length:MultipartSplit:private] => 295
	    [binary:MultipartSplit:private] => 
	)
	
	
	
To get the stored information just use normal `memcache` methods since `MultipartCache` extends `memcache`

```PHP
$dataFromCache = $cache->get($key);
```


#### Note: This is still a concept and work in progress