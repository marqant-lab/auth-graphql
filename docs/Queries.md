
## Queries

| Query | Requires input | Returns |
| ------  | ----- | ----- |
| me |  | User |


### me Query

Can be used to get all user data for a specific accessToken.
(in this case only the accessToken is used instead of the credentials, like in the `authenticate` case.)

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
