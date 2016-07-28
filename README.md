# Sinch-Verification-PHP

### Send Verification Code
```php
$sinch = new sinch();
$countryCode = "+91";
$phoneNumber = "9999999999";
$result = $sinch->sendCode($countryCode . $phoneNumber);
```
#### Output

```php
array (size=2)
  'id' => string '50411387' (length=8)
  'sms' => 
    array (size=2)
      'template' => string 'Your verification code is {{CODE}}.' (length=35)
      'interceptionTimeout' => int 120
```


### Validate Verification Code
```php
$sinch = new sinch();
$countryCode = "+91";
$phoneNumber = "9999999999";
$receivedCode = "4444";
$result = $sinch->verifyMobile($countryCode . $phoneNumber, $receivedCode);
```
#### Output

```php
array (size=3)
  'id' => string '50411387' (length=8)
  'method' => string 'sms' (length=3)
  'status' => string 'SUCCESSFUL' (length=10)
```