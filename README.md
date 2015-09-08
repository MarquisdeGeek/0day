# 0day
Create read-only APIs without almost 0 code, and 0 time!

## Writing your own APIs

The easiest way to start is by reviewing the tube.php code.


## Calling an API

I use the sgx.lib.rest API, since I wrote it! This allows code such as:

```
var api = new sgx.lib.rest("http://marquisdegeek.com/api/tube/");
var params = {sortby:'distance', distance:1000, longitude:0, limit:1};

api.getRequest(params).then(function(e) {
	$('#answer').html(JSON.stringify(e[0]));
});
```


## Examples

### London Tube API

I have published a version at http://marquisdegeek.com/api/tube/

The available parameters are:

```
    name           : Filter by name
    zone           : Filter by zone
    long/longitude : Filter by longitude, within a given distance
    lat/latitude   : Filter by latitude, within a given distance
    distance       : The distance to used when filtering by long/lat, in meters

    sortby         : Sorting parameter. Can be name, zone, longitude, latitude or distance (only if filtering by long/lat)
    sortorder      : If provided, must be 'asc' or 'desc'.
```

Example Usage:

 Find the most easterly stations:
```
    http://localhost/self/code/html5/0api/index.php?sortby=longitude&sortorder=desc
```
 Or north-most station in zone 1
```
    http://localhost/self/code/html5/0api/index.php?sortby=latitude&sortorder=desc&zone=1
```
 Or the closest to the meridan, sorted by distance
```
    http://localhost/self/code/html5/0api/index.php?long=0&lat=51.50&distance=1000&sortby=distance
```
 Or the closest to the meridian, ignoring the latitude
```
    http://localhost/self/code/html5/0api/index.php?long=0&distance=1000&sortby=distance
```

