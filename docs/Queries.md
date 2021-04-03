
# Queries

| Query | Requires input | Returns |
| ------  | ----- | ----- |
| me |  | User |


## me Query

Can be used to get all user data for a specific accessToken.
(in this case only the accessToken is used instead of the credentials, like in the `authenticate` case.)

Can be used to get all user data for a specific accessToken.
(in this case only the accessToken is used instead of the credentials, like in the `authenticate` case.)

**request preparation:**

To make this query (and all other room/message queries work) you need to pass the `accessToken` from the mutations above as a Authorization header.

So, the headers should have at least the following content.
```json
{
    "Authorization": "Bearer ${REPLACE_ACCESS_TOKEN_FROM_ABOVE}"
}
```

_header example (accessToken from authenticate filled in):_
```json
{
    "Authorization": "Bearer 2|6okeHVOnUeHR392ArASz957LV2dBFCG5QHtFpn16"
}
```


**Query:**
```graphql
query getUser {
  me{
    id
    name
    email
  }
}
```

**Variables:**

no variables needed


**Result:**
```json
{
  "data": {
    "id": "1",
    "name": "Frodo12",
    "email": "demo@demo.at"
  }
}
```
