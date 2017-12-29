import requests
import hmac
import hashlib
import datetime as dt
import simplejson as json

access_key = '80473469' # example
secret_key = 'GhDzk8Lc00xUzUjHFJqDqLztMNq5KMgU' # example

# Generate the X-Timestamp
t = dt.datetime.utcnow().replace(microsecond=0)
timestamp = t.isoformat()
timestamp = '2015-10-29T14:33:46'
headers = {
    'accept': "application/json",
    'x-timestamp': timestamp
    }

# Generate the MD5 hash of the body
body = ''
body_md5 = hashlib.md5(body).hexdigest() if body != '' else ''

# Creating URI info
query_params = ['limit=1','page=2'] # Since this is a simple request, we won't set any query params
query_params.sort()
url_scheme = 'https'
net_location = 'api.flowroute.com'
method = 'GET'
path = '/v1/routes/'
ordered_query_params = u'&'.join(query_params)
canonical_uri = '{0}://{1}{2}\n{3}'.format(url_scheme, net_location, path, ordered_query_params)

# Create the message string
tokens = (timestamp, method, body_md5, canonical_uri)
message_string = u'\n'.join(tokens).encode('utf-8')

# Generate the signature
signature = hmac.new(secret_key, message_string, digestmod=hashlib.sha1).hexdigest()

# Make the request
request_url = '{0}://{1}{2}?{3}'.format(url_scheme, net_location, path, ordered_query_params)  # append ordered query params here
#request = requests.get(request_url, auth=(access_key, signature), headers=headers)
#result = json.loads(request.text)

print "timestamp: " + str(timestamp)
print "tokens: " + str(tokens)
print "canonical_uri: " + str(canonical_uri)
print "request_uri: " + str(request_url)
print "message_string: " + str(message_string)
print "access_key: " + str(access_key)
print "signature: " + str(signature)
print "headers: " + str(headers)
print ""
#print str(result)