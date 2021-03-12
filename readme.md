# Project: Wallet
## Description: Endpoints and Request for APi-Wallet

Api Wallet es una api que se encarga de gestionar un monedero virtual, con el objetivo de manejar un saldo interno capaz de aumentar o disminur conforme el usuario lo decida.

Se acepta por diferentes medios de pagos 
# 📁 Collection: Wallet 


## End-point: Store Wallet
### Description: A wallet is a site where our users will store funds and make some operations like deposits, withdrwals or transfer that to another user.


Method: POST
>```
>{{wallets}}/wallets
>```
### Body (**raw**)

```json
{
    "user_id":10,
    "account_number":"skander1705@gmail.com"
}
```

### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvMTI3LjAuMC4xOjgwMDRcL3VzXC9sb2dpbiIsImlhdCI6MTYxMzc2OTkzNSwiZXhwIjoxNjU0NTY5OTM1LCJuYmYiOjE2MTM3Njk5MzUsImp0aSI6IkNOaElKSmlZR3lNVlR0elEiLCJzdWIiOiJza2FuZGVyMTcwNUBnbWFpbC5jb20iLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3IiwidXNlcm5hbWUiOiJza2FuZGVyMTcwNUBnbWFpbC5jb20iLCJhY2NvdW50Ijo5LCJyb2xlcyI6WyJVc3VhcmlvIl19.ISF4QFBJEa4IC34071DC62XZWRoqtrfGzHoUtfE35SE|string|



⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃


## End-point: List Clients Wallets
### Description: 
Method: GET
>```
>{{wallets}}/wallets
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvMTI3LjAuMC4xOjgwMDRcL3VzXC9sb2dpbiIsImlhdCI6MTYxNDExNTYxOSwiZXhwIjoxNjU0OTE1NjE5LCJuYmYiOjE2MTQxMTU2MTksImp0aSI6ImRlNDYwZEo5VlFzck1ZQ0QiLCJzdWIiOiJza2FuZGVyMTcwNUBnbWFpbC5jb20iLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3IiwidXNlcm5hbWUiOiJza2FuZGVyMTcwNUBnbWFpbC5jb20iLCJhY2NvdW50Ijo5LCJyb2xlcyI6WyJVc3VhcmlvIl19.DNi8xfD27lEAhDMqYeTp2Kk-P-ltZHjVkjK21vjeGeY|string|



⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃


## End-point: Show a Wallet
### Description: 
Method: GET
>```
>{{wallets}}/wallets/1
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvMTI3LjAuMC4xOjgwMDRcL3VzXC9sb2dpbiIsImlhdCI6MTYxMzc2OTkzNSwiZXhwIjoxNjU0NTY5OTM1LCJuYmYiOjE2MTM3Njk5MzUsImp0aSI6IkNOaElKSmlZR3lNVlR0elEiLCJzdWIiOiJza2FuZGVyMTcwNUBnbWFpbC5jb20iLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3IiwidXNlcm5hbWUiOiJza2FuZGVyMTcwNUBnbWFpbC5jb20iLCJhY2NvdW50Ijo5LCJyb2xlcyI6WyJVc3VhcmlvIl19.ISF4QFBJEa4IC34071DC62XZWRoqtrfGzHoUtfE35SE|string|



⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃


## End-point: Find Users Wallet
### Description: The third parameter is de user_id field
Method: GET
>```
>{{wallets}}/user/wallets/331
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvMTI3LjAuMC4xOjgwMDRcL3VzXC9sb2dpbiIsImlhdCI6MTYxMzc2OTkzNSwiZXhwIjoxNjU0NTY5OTM1LCJuYmYiOjE2MTM3Njk5MzUsImp0aSI6IkNOaElKSmlZR3lNVlR0elEiLCJzdWIiOiJza2FuZGVyMTcwNUBnbWFpbC5jb20iLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3IiwidXNlcm5hbWUiOiJza2FuZGVyMTcwNUBnbWFpbC5jb20iLCJhY2NvdW50Ijo5LCJyb2xlcyI6WyJVc3VhcmlvIl19.ISF4QFBJEa4IC34071DC62XZWRoqtrfGzHoUtfE35SE|string|



⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

# 📁 Collection: Deposit 


## End-point: Lists of deposits
### Description: Only the users with rol "admin","sysadmin" or "superadmin" can list this information


Method: GET
>```
>{{wallets}}/deposits
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvMTI3LjAuMC4xOjgwMDRcL3VzXC9sb2dpbiIsImlhdCI6MTYxMzc2OTkzNSwiZXhwIjoxNjU0NTY5OTM1LCJuYmYiOjE2MTM3Njk5MzUsImp0aSI6IkNOaElKSmlZR3lNVlR0elEiLCJzdWIiOiJza2FuZGVyMTcwNUBnbWFpbC5jb20iLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3IiwidXNlcm5hbWUiOiJza2FuZGVyMTcwNUBnbWFpbC5jb20iLCJhY2NvdW50Ijo5LCJyb2xlcyI6WyJVc3VhcmlvIl19.ISF4QFBJEa4IC34071DC62XZWRoqtrfGzHoUtfE35SE|string|



⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃


## End-point: Save Deposit
### Description: If the rol is "admin", "sysadmin" or "superadmin" you can send the user_id field to create a deposit to another user.

also there you can send null to ommit this field because this id is taken for the token with the wallet_id field
Method: POST
>```
>{{wallets}}/deposits
>```
### Body (**raw**)

```json
{
    "amount" :25.0001,
    "user_id":null,
    "detail": {
        "method_payment_id": 2,
        "method_payment"   : "Efectivo",
        "message"          : "opcional, se usa para las trasferencias entre usuarios",
        "method_detail"    :{}
    }
}
```

### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvMTI3LjAuMC4xOjgwMDRcL3VzXC9sb2dpbiIsImlhdCI6MTYxMzc2OTkzNSwiZXhwIjoxNjU0NTY5OTM1LCJuYmYiOjE2MTM3Njk5MzUsImp0aSI6IkNOaElKSmlZR3lNVlR0elEiLCJzdWIiOiJza2FuZGVyMTcwNUBnbWFpbC5jb20iLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3IiwidXNlcm5hbWUiOiJza2FuZGVyMTcwNUBnbWFpbC5jb20iLCJhY2NvdW50Ijo5LCJyb2xlcyI6WyJVc3VhcmlvIl19.ISF4QFBJEa4IC34071DC62XZWRoqtrfGzHoUtfE35SE|string|



⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃


## End-point: List of deposits for user
### Description: The user_id parameter is taken by the current logger user, if you are "admin","sysadmin" or "superadmin" you cant send the user_id as the url paramert


Method: GET
>```
>{{wallets}}/user/deposits/
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvMTI3LjAuMC4xOjgwMDRcL3VzXC9sb2dpbiIsImlhdCI6MTYxMzc2OTkzNSwiZXhwIjoxNjU0NTY5OTM1LCJuYmYiOjE2MTM3Njk5MzUsImp0aSI6IkNOaElKSmlZR3lNVlR0elEiLCJzdWIiOiJza2FuZGVyMTcwNUBnbWFpbC5jb20iLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3IiwidXNlcm5hbWUiOiJza2FuZGVyMTcwNUBnbWFpbC5jb20iLCJhY2NvdW50Ijo5LCJyb2xlcyI6WyJVc3VhcmlvIl19.ISF4QFBJEa4IC34071DC62XZWRoqtrfGzHoUtfE35SE|string|



⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

# 📁 Collection: Withdrawal 


## End-point: List of withdrawals for user
### Description: 
Method: GET
>```
>{{wallets}}/user/withdrawals/
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvMTI3LjAuMC4xOjgwMDRcL3VzXC9sb2dpbiIsImlhdCI6MTYxMzc2OTkzNSwiZXhwIjoxNjU0NTY5OTM1LCJuYmYiOjE2MTM3Njk5MzUsImp0aSI6IkNOaElKSmlZR3lNVlR0elEiLCJzdWIiOiJza2FuZGVyMTcwNUBnbWFpbC5jb20iLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3IiwidXNlcm5hbWUiOiJza2FuZGVyMTcwNUBnbWFpbC5jb20iLCJhY2NvdW50Ijo5LCJyb2xlcyI6WyJVc3VhcmlvIl19.ISF4QFBJEa4IC34071DC62XZWRoqtrfGzHoUtfE35SE|string|



⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃


## End-point: Save withdrawal
### Description: 
Method: POST
>```
>{{wallets}}/withdrawals
>```
### Body (**raw**)

```json
{
    "amount" :20,
    "user_id":null,
    "detail": {
        "method_payment_id": 1,
        "method_payment"   : "Efectivo",
        "message"          : "opcional, se usa para las trasferencias"
    }
}
```

### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvMTI3LjAuMC4xOjgwMDRcL3VzXC9sb2dpbiIsImlhdCI6MTYxMzc2OTkzNSwiZXhwIjoxNjU0NTY5OTM1LCJuYmYiOjE2MTM3Njk5MzUsImp0aSI6IkNOaElKSmlZR3lNVlR0elEiLCJzdWIiOiJza2FuZGVyMTcwNUBnbWFpbC5jb20iLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3IiwidXNlcm5hbWUiOiJza2FuZGVyMTcwNUBnbWFpbC5jb20iLCJhY2NvdW50Ijo5LCJyb2xlcyI6WyJVc3VhcmlvIl19.ISF4QFBJEa4IC34071DC62XZWRoqtrfGzHoUtfE35SE|string|



⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃


## End-point: Lists of withdrawals
### Description: 
Method: GET
>```
>{{wallets}}/withdrawals
>```
### 🔑 Authentication bearer

|Param|value|Type|
|---|---|---|
|token|eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwczpcL1wvMTI3LjAuMC4xOjgwMDRcL3VzXC9sb2dpbiIsImlhdCI6MTYxMzc2OTkzNSwiZXhwIjoxNjU0NTY5OTM1LCJuYmYiOjE2MTM3Njk5MzUsImp0aSI6IkNOaElKSmlZR3lNVlR0elEiLCJzdWIiOiJza2FuZGVyMTcwNUBnbWFpbC5jb20iLCJwcnYiOiIyM2JkNWM4OTQ5ZjYwMGFkYjM5ZTcwMWM0MDA4NzJkYjdhNTk3NmY3IiwidXNlcm5hbWUiOiJza2FuZGVyMTcwNUBnbWFpbC5jb20iLCJhY2NvdW50Ijo5LCJyb2xlcyI6WyJVc3VhcmlvIl19.ISF4QFBJEa4IC34071DC62XZWRoqtrfGzHoUtfE35SE|string|



⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃ ⁃

_________________________________________________
Author: [bautistaj](https://github.com/bautistaj)

Package: [postman-to-markdown](https://github.com/bautistaj)
