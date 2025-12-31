<?php

namespace Leenset\Sms\Tests;

use Leenset\Sms\Rayansms\RayanSms;
use Orchestra\Testbench\TestCase;
use Mockery;
use Illuminate\Support\Facades\Http;

class RayanSmsTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCanSetConfig()
    {
        $config = Mockery::mock('Illuminate\Contracts\Config\Repository');
        $config->shouldReceive('get')
            ->with('sms.rayansms.api-key')
            ->andReturn('test-api-key');
        $config->shouldReceive('get')
            ->with('sms.rayansms.api-url', 'https://rayansms.com/api/')
            ->andReturn('https://rayansms.com/api/');

        $rayanSms = new RayanSms();
        $rayanSms->setConfig($config);

        $this->assertNotNull($rayanSms->config);
    }

    public function testSendReturnsTrueOnSuccess()
    {
        Http::fake([
            'rayansms.com/api/send' => Http::response([
                'status' => 'success',
                'message' => 'Sent'
            ], 200)
        ]);

        $config = Mockery::mock('Illuminate\Contracts\Config\Repository');
        $config->shouldReceive('get')
            ->with('sms.rayansms.api-key')
            ->andReturn('test-key');
        $config->shouldReceive('get')
            ->with('sms.rayansms.api-url', 'https://rayansms.com/api/')
            ->andReturn('https://rayansms.com/api/');

        $rayanSms = new RayanSms();
        $rayanSms->setConfig($config);

        $result = $rayanSms->send('Test message', '09123456789');
        $this->assertTrue($result);
    }

    public function testOTPReturnsTrueOnSuccess()
    {
        Http::fake([
            'rayansms.com/api/otp' => Http::response([
                'status' => 'success',
                'message' => 'Sent'
            ], 200)
        ]);

        $config = Mockery::mock('Illuminate\Contracts\Config\Repository');
        $config->shouldReceive('get')
            ->with('sms.rayansms.api-key')
            ->andReturn('test-key');
        $config->shouldReceive('get')
            ->with('sms.rayansms.api-url', 'https://rayansms.com/api/')
            ->andReturn('https://rayansms.com/api/');

        $rayanSms = new RayanSms();
        $rayanSms->setConfig($config);

        $result = $rayanSms->OTP('123456', '09123456789');
        $this->assertTrue($result);
    }
}

