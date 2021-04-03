
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
      "name": "John2423",
      "firstName": "John",
      "lastName": "Doe"
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
