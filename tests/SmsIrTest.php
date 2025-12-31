<?php

namespace Leenset\Sms\Tests;

use Leenset\Sms\Smsir\SmsIr;
use PHPUnit\Framework\TestCase;
use Mockery;
use Illuminate\Support\Facades\Http;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

class SmsIrTest extends OrchestraTestCase
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
            ->with('sms.smsir.api-key')
            ->andReturn('test-api-key');
        $config->shouldReceive('get')
            ->with('sms.smsir.secret-key')
            ->andReturn('test-secret-key');
        $config->shouldReceive('get')
            ->with('sms.smsir.api-url')
            ->andReturn('https://ws.sms.ir/');

        $smsIr = new SmsIr();
        $smsIr->setConfig($config);

        $this->assertEquals('test-api-key', $smsIr->APIKey);
        $this->assertEquals('test-secret-key', $smsIr->SecretKey);
        $this->assertEquals('https://ws.sms.ir/', $smsIr->APIURL);
    }

    public function testGetTokenReturnsFalseOnFailure()
    {
        Http::fake([
            'ws.sms.ir/api/Token' => Http::response([
                'IsSuccessful' => false,
                'Message' => 'Error'
            ], 200)
        ]);

        $config = Mockery::mock('Illuminate\Contracts\Config\Repository');
        $config->shouldReceive('get')
            ->with('sms.smsir.api-key')
            ->andReturn('test-key');
        $config->shouldReceive('get')
            ->with('sms.smsir.secret-key')
            ->andReturn('test-secret');
        $config->shouldReceive('get')
            ->with('sms.smsir.api-url')
            ->andReturn('https://ws.sms.ir/');

        $smsIr = new SmsIr();
        $smsIr->setConfig($config);

        $reflection = new \ReflectionClass($smsIr);
        $method = $reflection->getMethod('getToken');
        $method->setAccessible(true);
        $result = $method->invoke($smsIr);

        $this->assertFalse($result);
    }

    public function testSendReturnsFalseWhenTokenFails()
    {
        Http::fake([
            'ws.sms.ir/api/Token' => Http::response([
                'IsSuccessful' => false
            ], 200)
        ]);

        $config = Mockery::mock('Illuminate\Contracts\Config\Repository');
        $config->shouldReceive('get')
            ->with('sms.smsir.api-key')
            ->andReturn('test-key');
        $config->shouldReceive('get')
            ->with('sms.smsir.secret-key')
            ->andReturn('test-secret');
        $config->shouldReceive('get')
            ->with('sms.smsir.api-url')
            ->andReturn('https://ws.sms.ir/');
        $config->shouldReceive('get')
            ->with('sms.smsir.line-number')
            ->andReturn('10001001');

        $smsIr = new SmsIr();
        $smsIr->setConfig($config);

        $result = $smsIr->send('Test message', '09123456789');
        $this->assertFalse($result);
    }
}

