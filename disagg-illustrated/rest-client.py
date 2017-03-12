#!/usr/bin/python
import requests
import json

url = "http://203.0.113.3/api/v0/mycustomapi.php/testrequest"
header = {"content-type":"application/json"}

apiresponse = requests.get(url, headers=header, verify=False)

print (json.dumps(apiresponse.json(), indent=4, separators=(',',': ')))
