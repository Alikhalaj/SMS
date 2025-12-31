# پکیج SMS برای Laravel

پکیج کامل برای ارسال پیامک با پشتیبانی از چندین درگاه (SMS.ir, Kavenegar, RayanSms)

## نصب

```bash
composer require alikhalaj/sms
```

## پیکربندی

پس از نصب، فایل کانفیگ را publish کنید:

```bash
php artisan vendor:publish --provider="Leenset\Sms\SmsServiceProvider8" --tag="config"
```

سپس در فایل `.env` خود تنظیمات زیر را اضافه کنید:

```env
SMS_CONNECTION=smsir

# تنظیمات SMS.ir
SMS_API_KEY=your-api-key
SMS_API_SECRET_KEY=your-secret-key
SMS_API_URL=https://ws.sms.ir/
SMS_LINE_NUMBER=10001001
SMS_TEMPLATE_ID=424974

# تنظیمات Kavenegar
KAVENEGAR_API_KEY=your-api-key
KAVENEGAR_API_URL=https://api.kavenegar.com/v1/
KAVENEGAR_NUMBER=10001001
KAVENEGAR_VERIFICATION_TEMPLATE=template-name

# تنظیمات RayanSms
RAYANSMS_API_KEY=your-api-key
RAYANSMS_API_URL=https://rayansms.com/api/
```

## استفاده

### استفاده از Facade

```php
use Leenset\Sms\Sms;

// ارسال پیامک ساده
Sms::send('متن پیامک', '09123456789');

// ارسال کد OTP
Sms::OTP('123456', '09123456789');

// ارسال کد تأیید (برای SMS.ir)
Sms::verificationCode('123456', '09123456789');
```

### استفاده مستقیم از Resolver

```php
use Leenset\Sms\SmsResolver;

$sms = new SmsResolver();
$sms->make('kavenegar')->send('متن پیامک', '09123456789');
```

### تغییر درگاه

```php
// استفاده از درگاه خاص
Sms::make('kavenegar')->send('متن پیامک', '09123456789');
Sms::make('rayansms')->OTP('123456', '09123456789');
```

## درگاه‌های پشتیبانی شده

### SMS.ir
- `send($message, $mobileNumber)` - ارسال پیامک ساده
- `OTP($code, $mobileNumber, $templateId)` - ارسال کد OTP
- `verificationCode($code, $mobileNumber)` - ارسال کد تأیید

### Kavenegar
- `send($message, $mobileNumber)` - ارسال پیامک ساده
- `OTP($code, $mobileNumber, $template)` - ارسال کد OTP
- `VerifyLookup($mobileNumber, $template, ...$tokens)` - ارسال با چندین توکن

### RayanSms
- `send($message, $mobileNumber)` - ارسال پیامک ساده
- `OTP($code, $mobileNumber, $template)` - ارسال کد OTP
- `verificationCode($code, $mobileNumber)` - ارسال کد تأیید

## تست

برای اجرای تست‌ها:

```bash
composer test
```

یا:

```bash
./vendor/bin/phpunit
```

## نیازمندی‌ها

- PHP >= 7.4
- Laravel >= 8.0

## مجوز

MIT License

## نویسنده

alikhalaj - akh30002@gmail.com

