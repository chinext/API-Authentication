# API-Authentication
API based Authentication system ( Login, Registration, Update User &amp; validateToken Flows) using vanilla PHP


Installation
------------

1) Clone to folder
2) Create Database
3) Run install/database.sql to create a users table
4) edit config.php 



Example
-------
```php



  -------------------------------------------------------------------------------------------
  /register
  
  ##JSON Body
   {
      "firstname" : "John",
      "lastname" : "Doe",
      "email" : "johndoe@yahoo.com",
      "password" : "secret"
   }
  
  ## output 
    {
        "message": "User was created."
    }




  -------------------------------------------------------------------------------------------
   /login
    ##JSON Body
    {
      "email" : "johndoe@yahoo.com",
      "password" : "secret"
    }

    ## output 
    {
    "message": "Login successful",
    "jwt": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9teXBhdHJpY2lhLmNvIiwiYXVkIjoiaHR0cDpcL1wvbXlwYXRyaWNpYS5vcmciLCJpYXQiOjEzNTY5OTk1MjQsIm5iZiI6MTM1NzAwMDAwMCwiZGF0YSI6eyJpZCI6IjQiLCJmaXJzdG5hbWUiOiJDaGluZWR1IiwibGFzdG5hbWUiOiJFamliZW5kdSIsImVtYWlsIjoiY2hpbmV4dHdvcmxkQHlhaG9vLmNvbSJ9fQ.CXSaHBHa893fFyLZ0KPpGr3qurCPzmjBMHVGb1yfH1g"
     }




  -------------------------------------------------------------------------------------------
   /validateToken
   ##JSON Body
    {
      "jwt": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9teXBhdHJpY2lhLmNvIiwiYXVkIjoiaHR0cDpcL1wvbXlwYXRyaWNpYS5vcmciLCJpYXQiOjEzNTY5OTk1MjQsIm5iZiI6MTM1NzAwMDAwMCwiZGF0YSI6eyJpZCI6IjQiLCJmaXJzdG5hbWUiOiJDaGluZWR1IiwibGFzdG5hbWUiOiJFamliZW5kdSIsImVtYWlsIjoiY2hpbmV4dHdvcmxkQHlhaG9vLmNvbSJ9fQ.CXSaHBHa893fFyLZ0KPpGr3qurCPzmjBMHVGb1yfH1g"
    }
    ##  output
    {
      "message": "Access granted.",
      "data": {
          "id": "1",
          "firstname": "John",
          "lastname": "Doe",
          "email": "johndoe@yahoo.com"
      }
    }



  
  -------------------------------------------------------------------------------------------
   /updateUser 
   ##JSON Body
   {
        "firstname" : "John",
        "lastname" : "Doe",
        "email" : "johndoe@yahoo.com",
        "password" : "secret",
        "jwt": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9leGFtcGxlLm9yZyIsImF1ZCI6Imh0dHA6XC9cL2V4YW1wbGUuY29tIiwiaWF0IjoxMzU2OTk5NTI0LCJuYmYiOjEzNTcwMDAwMDAsImRhdGEiOnsiaWQiOiI5IiwiZmlyc3RuYW1lIjoiVmluY2UiLCJsYXN0bmFtZSI6IkRhbGlzYXkiLCJlbWFpbCI6Im1pa2VAY29kZW9mYW5pbmphLmNvbSJ9fQ.3Sv65TVYACkNPo4HMr4NvreyZY16wxG-nSorLi_jykI"
    }
     ##  output
    {
        "message": "User was updated.",
        "jwt": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJpc3MiOiJodHRwOlwvXC9teXBhdHJpY2lhLmNvIiwiYXVkIjoiaHR0cDpcL1wvbXlwYXRyaWNpYS5vcmciLCJpYXQiOjEzNTY5OTk1MjQsIm5iZiI6MTM1NzAwMDAwMCwiZGF0YSI6eyJpZCI6IjEiLCJmaXJzdG5hbWUiOiJDaGluZWR1IiwibGFzdG5hbWUiOiJFamliZW5kdSIsImVtYWlsIjoiY2hpbmV4dHdvcmxkQHlhaG9vLmNvbS5jb20ifX0.ImYYXMXxILEdCDUwPFImKdl0eYjDQp-BUaiD-5s0o7g"
    }

    ## output error
    {
      "message": "Access denied.",
      "error": "Signature verification failed"
    }
    {
      "message": "Access denied.",
      "error": "Unexpected control character found"
    }





```
