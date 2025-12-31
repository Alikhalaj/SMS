<?php

namespace Leenset\Sms\Tests;

use Leenset\Sms\Kavenegar\Kavenegar;
use Orchestra\Testbench\TestCase;
use Mockery;
use Illuminate\Support\Facades\Http;

class KavenegarTest extends TestCase
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
            ->with('sms.kavenegar.api-key')
            ->andReturn('test-api-key');
        $config->shouldReceive('get')
            ->with('sms.kavenegar.api-url')
            ->andReturn('https://api.kavenegar.com/v1/');
        $config->shouldReceive('get')
            ->with('sms.kavenegar.number')
            ->andReturn('10001001');

        $kavenegar = new Kavenegar();
        $kavenegar->setConfig($config);

        $this->assertEquals('test-api-key', $kavenegar->APIKey);
        $this->assertEquals('https://api.kavenegar.com/v1/', $kavenegar->APIURL);
        $this->assertEquals('10001001', $kavenegar->NUMBER);
    }

    public function testSendReturnsTrueOnSuccess()
    {
        Http::fake([
            'api.kavenegar.com/v1/*' => Http::response([
                'return' => [
                    'status' => 200,
                    'message' => 'Success'
                ]
            ], 200)
        ]);

        $config = Mockery::mock('Illuminate\Contracts\Config\Repository');
        $config->shouldReceive('get')
            ->with('sms.kavenegar.api-key')
            ->andReturn('test-key');
        $config->shouldReceive('get')
            ->with('sms.kavenegar.api-url')
            ->andReturn('https://api.kavenegar.com/v1/');
        $config->shouldReceive('get')
            ->with('sms.kavenegar.number')
            ->andReturn('10001001');

        $kavenegar = new Kavenegar();
        $kavenegar->setConfig($config);

        $result = $kavenegar->send('Test message', '09123456789');
        $this->assertTrue($result);
    }

    public function testOTPReturnsTrueOnSuccess()
    {
        Http::fake([
            'api.kavenegar.com/v1/*' => Http::response([
                'return' => [
                    'status' => 200,
                    'message' => 'Success'
                ]
            ], 200)
        ]);

        $config = Mockery::mock('Illuminate\Contracts\Config\Repository');
        $config->shouldReceive('get')
            ->with('sms.kavenegar.api-key')
            ->andReturn('test-key');
        $config->shouldReceive('get')
            ->with('sms.kavenegar.api-url')
            ->andReturn('https://api.kavenegar.com/v1/');

        $kavenegar = new Kavenegar();
        $kavenegar->setConfig($config);

        $result = $kavenegar->OTP('123456', '09123456789', 'template-name');
        $this->assertTrue($result);
    }
}

