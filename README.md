MultipartCache
==============

This a simple class that extends `PHP` memcache to support saving data larger than `1MB`. 


##### Example 1 ;
```PHP
$largeSet = range(0, 100000);
$key = "largeSet";

$cache = new MultipartCache();
$cache->addserver("127.0.0.1"); // Local memecache server
$cache->addserver("54.234.98.140:49226"); //Free server from www.memcachedasaservice.com

$cache->setLimit(1024); 		// Reduce limit to for testing
$cache->set($key, $largeSet);
```
If you just want to see details of how it was save use `MultipartCache::getDetails`

```PHP
print_r($cache->getDetails($key));
```

###### Sample Output 

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

##### Example 2

```PHP
$key = "largeImage";
$cache = new MultipartCache();
$cache->addserver("127.0.0.1");  // Local memecache server
$cache->addserver("X.X.X.X"); //Free server from www.memcachedasaservice.com

$cache->set($key, file_get_contents("large_image.jpg"));

header("Content-Type: image/jpeg");
echo $cache->get($key);
```


#### Note: This is still a concept and work in progress