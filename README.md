MultipartCache
==============

This a simple class that extends `PHP` memcache to support saving data larger than `1MB`. 


##### Example 1 ;
```PHP
$largeSet = range(0, 100000);
$key = "largeSet";

$cache = new Mcache\Main();
$cache->addserver("127.0.0.1"); // add memecache server
$cache->addserver("54.234.98.140:49226"); //Free server from www.memcachedasaservice.com

$cache->setLimit(1024); 		// Set smaller limit to for testing
$cache->set($key, $largeSet);	// Go get the data

```
If you just want to see details of how it was saved use `Mcache\Main::getDetails()`

```PHP
print_r($cache->getDetails($key));
```

###### Sample Output 

	{
	    "type": "array",
	    "sha1": "9959bd9fa94479fcda3f82825a269970b4517cb1",
	    "md5": "e25c88f7a6e171ece40495ce00239f9f",
	    "length": 588898,
	    "slice": 576,
	    "time": "Sat, 01 Jun 2013 21:55:21 +0200"
	}
	

- Total data length `588898`
- System split this data into `576` slices
- You can also see the time the data was saved

***Note that `Mcache\Main::getDetails()` would only have information of any data grater than the limit set. This was implement for performance reasons***
	
	
To get the stored information just use normal `memcache` methods since `Mcache\Main` extends `memcache`

```PHP
$dataFromCache = $cache->get($key);
```

##### Example 2

```PHP
$key = "largeImage";
$cache = new Mcache\Main();
$cache->addserver("127.0.0.1");  // Local memecache server
$cache->addserver("X.X.X.X"); //Free server from www.memcachedasaservice.com

$cache->set($key, file_get_contents("large_image.jpg"));

header("Content-Type: image/jpeg");
echo $cache->get($key);
```


#### Licence [MIT](http://opensource.org/licenses/MIT)

	Copyright (c) 2013 Oleku Konko
	
	Permission is hereby granted, free of charge, to any person obtaining a copy
	of this software and associated documentation files (the "Software"), to deal
	in the Software without restriction, including without limitation the rights
	to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
	copies of the Software, and to permit persons to whom the Software is
	furnished to do so, subject to the following conditions:
	
	The above copyright notice and this permission notice shall be included in
	all copies or substantial portions of the Software.
	
	THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
	IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
	FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
	AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
	LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
	OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
	THE SOFTWARE.
	 


 
 