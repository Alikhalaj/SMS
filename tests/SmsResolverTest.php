<?php

namespace Leenset\Sms\Tests;

use Leenset\Sms\SmsResolver;
use Leenset\Sms\Exceptions\PortNotFoundException;
use Orchestra\Testbench\TestCase;
use Mockery;

class SmsResolverTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCanCreateSmsResolverWithDefaultDriver()
    {
        $config = Mockery::mock('Illuminate\Contracts\Config\Repository');
        $config->shouldReceive('get')
            ->with('sms.default', 'smsir')
            ->andReturn('smsir');
        $config->shouldReceive('get')
            ->with('sms.smsir.api-key')
            ->andReturn('test-key');
        $config->shouldReceive('get')
            ->with('sms.smsir.secret-key')
            ->andReturn('test-secret');
        $config->shouldReceive('get')
            ->with('sms.smsir.api-url')
            ->andReturn('https://ws.sms.ir/');

        $resolver = new SmsResolver($config);
        $this->assertInstanceOf(SmsResolver::class, $resolver);
    }

    public function testCanMakeSpecificDriver()
    {
        $config = Mockery::mock('Illuminate\Contracts\Config\Repository');
        $config->shouldReceive('get')
            ->with('sms.default', 'smsir')
            ->andReturn('smsir');
        $config->shouldReceive('get')
            ->with('sms.kavenegar.api-key')
            ->andReturn('test-key');
        $config->shouldReceive('get')
            ->with('sms.kavenegar.api-url')
            ->andReturn('https://api.kavenegar.com/v1/');
        $config->shouldReceive('get')
            ->with('sms.kavenegar.number')
            ->andReturn('10001001');

        $resolver = new SmsResolver($config);
        $result = $resolver->make('kavenegar');
        $this->assertInstanceOf(SmsResolver::class, $result);
    }

    public function testThrowsExceptionForInvalidDriver()
    {
        $config = Mockery::mock('Illuminate\Contracts\Config\Repository');
        $config->shouldReceive('get')
            ->with('sms.default', 'smsir')
            ->andReturn('invalid-driver');

        $this->expectException(PortNotFoundException::class);
        $resolver = new SmsResolver($config);
    }

    public function testCanCallDriverMethods()
    {
        $config = Mockery::mock('Illuminate\Contracts\Config\Repository');
        $config->shouldReceive('get')
            ->with('sms.default', 'smsir')
            ->andReturn('smsir');
        $config->shouldReceive('get')
            ->with('sms.smsir.api-key')
            ->andReturn('test-key');
        $config->shouldReceive('get')
            ->with('sms.smsir.secret-key')
            ->andReturn('test-secret');
        $config->shouldReceive('get')
            ->with('sms.smsir.api-url')
            ->andReturn('https://ws.sms.ir/');

        $resolver = new SmsResolver($config);
        $this->assertNotNull($resolver->driver);
    }
}

