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
    http://marquisdegeek.com/api/tube/?sortby=longitude&sortorder=desc
```
 Or north-most station in zone 1
```
    http://marquisdegeek.com/api/tube/?sortby=latitude&sortorder=desc&zone=1
```
 Or the closest to the meridan, sorted by distance
```
    http://marquisdegeek.com/api/tube/?long=0&lat=51.50&distance=1000&sortby=distance
```
 Or the closest to the meridian, ignoring the latitude
```
    http://marquisdegeek.com/api/tube/?long=0&distance=1000&sortby=distance
```

### Country name to ISO2 API

I have published a version at http://marquisdegeek.com/api/country/

The available parameters are:

```
    name           : Country name
    closest        : If provided, return names that match within a Levenshtein distance of N. If not provided, it must match exactly
```

Example Usage:

 Find the ISO2 code for Germany:
```
    http://marquisdegeek.com/api/country/?name=Germany
```

 Find the ISO2 code for Germny (or its mispelling):
```
    http://marquisdegeek.com/api/country/?name=Germny&closest
```

Note: This was created for TADHack 2016 and is, therefore, a hack!


