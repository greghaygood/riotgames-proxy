# Avvo Proxy

This is an example proxy script for working with the
[Riot Games API](https://developer.riotgames.com/) from a browser. _It is meant
for instructional purposes only, and should not be used in a production environment without additional security measures._


## Usage

To call an API, pass your various parameters:

```$javascript
fetch('./riotgames.php?_ep=/lol/summoner/v3/summoners/by-name/RiotSchmick?api_key=<YOUR_API_KEY>')
.then(response => response.json())
.then(data => processTheData(data))
```

Any other parameters will be sent to the requested endpoint, so this should allow full access to the API. It is
currently only meant for making GET requests; any POST/PUT/DELETE requests will fail in unexpected (but spectacular?!)
ways.

Cheers!
