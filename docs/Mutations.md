
## Mutations

| Mutation | Requires input | Returns |
| ------  | ----- | ----- |
| register | RegisterUserInput | ResponseUser |
| authenticate | AuthenticateInput | ResponseUser |


## register Mutation

Can be used to register/create new users.

**Query:**
```graphql
mutation($input: RegisterUserInput!){
  register(input: $input){
    accessToken
    user {
      id
    	name
      email
    }
  }
}
```

**Variables:**
```json
{ 
  "input": {
      "email": "demo@demo.at",
      "password": "123456789",
      "password_confirmation": "123456789",
      "name": "John Doe"
  }
}
```

**Result:**
```json
{
  "data": {
    "authenticate": {
      "accessToken": "2|6okeHVOnUeHR392ArASz957LV2dBFCG5QHtFpn16",
      "user": {
        "id": "1",
        "name": "John Doe",
        "email": "demo@demo.at"
      }
    }
  }
}
```

## authenticate Mutation

Can be used to authenticate users.

**Query:**
```graphql
mutation($input: AuthenticateInput!){
  authenticate(input: $input){
    accessToken
    user {
      id
      name
      email
    }
  }
}
```

**Variables:**
```json
{ 
  "input": {
      "email": "demo@demo.at",
      "password": "123456789",
  }
}
```


**Result:**
```json
{
  "data": {
    "authenticate": {
      "accessToken": "2|6okeHVOnUeHR392ArASz957LV2dBFCG5QHtFpn16",
      "user": {
        "id": "1",
        "name": "John Doe",
        "email": "demo@demo.at"
      }
    }
  }
}
```
