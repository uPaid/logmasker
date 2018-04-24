<?php
namespace Upaid\Logmasker\Tests;

use Mockery;
use PHPUnit\Framework\TestCase;
use Illuminate\Contracts\Config\Repository;
use Upaid\Logmasker\Services\Logmasker;

class LogmaskerTest extends TestCase
{
    protected $maskerMock;
    protected $logmasker;
    protected $config;

    public function setUp() : void
    {
        parent::setUp();
        $this->config = Mockery::mock(Repository::class);
        $this->logmasker = new Logmasker($this->config);
    }

    /**
     * @test
     * @dataProvider dataForMaskAll
     */
    public function maskAll($data) : void
    {
        $this->config->shouldReceive('get')
            ->with('logmasker.mask_all.replacer')
            ->andReturn('*');

        $checkData = $this->logmasker->maskAll($data);
        $this->assertEquals($checkData, str_repeat('*', strlen($data)));
    }

    /**
     * @test
     * @dataProvider dataForMaskpartial
     */
    public function maskPartial($data) : void
    {
        $this->config->shouldReceive('get')
            ->with('logmasker.mask_partial.replacer')
            ->andReturn('******');
        $this->config->shouldReceive('get')
            ->with('logmasker.mask_partial.start')
            ->andReturn('6');
        $this->config->shouldReceive('get')
            ->with('logmasker.mask_partial.length')
            ->andReturn('6');
        $checkData = $this->logmasker->maskPartial($data);
        $this->assertEquals(substr_replace($data, '******', 6, 6), $checkData);
    }

    public function dataForMaskAll() : array
    {
        return [
            [111]
        ];
    }

    public function dataForMaskPartial() : array
    {
        return [
            [348368700665526]
        ];
    }
}
