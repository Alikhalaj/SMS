<?php

namespace Leenset\Sms\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app)
    {
        return [
            \Leenset\Sms\SmsServiceProvider8::class,
        ];
    }

    protected function getPackageAliases($app)
    {
        return [
            'Sms' => \Leenset\Sms\Sms::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('sms.default', 'smsir');
        $app['config']->set('sms.smsir.api-key', 'test-key');
        $app['config']->set('sms.smsir.secret-key', 'test-secret');
        $app['config']->set('sms.smsir.api-url', 'https://ws.sms.ir/');
    }
}

