# Changelog

All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [2.0.0] - 2026-09-01

### Changed
- **Breaking:** namespace renamed from `Leenset\Sms` to `Alikh\Sms` (package name on Packagist stays `alikhalaj/sms`) across every class, the Laravel service provider registration, and the `Sms` facade alias.

## [1.1.0] - 2025-12-31

### Added
- تست‌های واحد کامل برای تمام کلاس‌ها
- فایل README.md با مستندات کامل
- پشتیبانی کامل از RayanSms
- متد getToken برای SmsIr
- متد VerifyLookup برای Kavenegar با پشتیبانی از چندین token

### Fixed
- رفع باگ مسیر config در SmsServiceProvider8 (مسیر publish)
- رفع مشکل SmsResolver در انتخاب درگاه
- رفع مشکل headers در SmsIr
- رفع مشکل execute در Kavenegar
- حذف کدهای unreachable

### Changed
- بهبود مدیریت خطا در SmsResolver
- بهبود ساختار کد و خوانایی
- تکمیل composer.json با dependencies مناسب

## [1.0.3] - Previous Release

### Changed
- تغییرات در SMS verification

## [1.0.2] - Previous Release

### Changed
- تغییر template

## [1.0.1] - Previous Release

### Changed
- تغییرات اولیه

## [1.0.0] - Initial Release

### Added
- پشتیبانی از SMS.ir
- پشتیبانی از Kavenegar
- پشتیبانی اولیه از RayanSms

