<?php

namespace Emberfuse\Tests\Support;

use Emberfuse\Tests\TestCase;
use Emberfuse\Support\Repository;

class RepositoryTest extends TestCase
{
    /**
     * @var \Emberfuse\Support\Repository
     */
    protected $repository;

    /**
     * @var array
     */
    protected $config;

    protected function setUp(): void
    {
        $this->repository = new Repository($this->config = [
            'foo' => 'bar',
            'bar' => 'baz',
            'baz' => 'bat',
            'null' => null,
            'associate' => [
                'x' => 'xxx',
                'y' => 'yyy',
            ],
            'array' => [
                'aaa',
                'zzz',
            ],
            'x' => [
                'z' => 'zoo',
            ],
        ]);

        parent::setUp();
    }

    public function testConstruct()
    {
        $this->assertInstanceOf(Repository::class, $this->repository);
    }

    public function testHasIsTrue()
    {
        $this->assertTrue($this->repository->has('foo'));
    }

    public function testHasIsFalse()
    {
        $this->assertFalse($this->repository->has('not-exist'));
    }

    public function testGet()
    {
        $this->assertSame('bar', $this->repository->get('foo'));
    }

    public function testGetWithDefault()
    {
        $this->assertSame('default', $this->repository->get('not-exist', 'default'));
    }

    public function testSet()
    {
        $this->repository->set('key', 'value');
        $this->assertSame('value', $this->repository->get('key'));
    }

    public function testPrepend()
    {
        $this->repository->prepend('array', 'xxx');
        $this->assertSame('xxx', $this->repository->get('array')['0']);
    }

    public function testPush()
    {
        $this->repository->push('array', 'xxx');
        $this->assertSame('xxx', $this->repository->get('array')['2']);
    }

    public function testAll()
    {
        $this->assertSame($this->config, $this->repository->all());
    }

    public function testOffsetExists()
    {
        $this->assertTrue(isset($this->repository['foo']));
        $this->assertFalse(isset($this->repository['not-exist']));
    }

    public function testOffsetGet()
    {
        $this->assertNull($this->repository['not-exist']);
        $this->assertSame('bar', $this->repository['foo']);
        $this->assertSame([
            'x' => 'xxx',
            'y' => 'yyy',
        ], $this->repository['associate']);
    }

    public function testOffsetSet()
    {
        $this->assertNull($this->repository['key']);

        $this->repository['key'] = 'value';

        $this->assertSame('value', $this->repository['key']);
    }

    public function testOffsetUnset()
    {
        $this->assertArrayHasKey('associate', $this->repository->all());
        $this->assertSame($this->config['associate'], $this->repository->get('associate'));

        unset($this->repository['associate']);

        $this->assertArrayHasKey('associate', $this->repository->all());
        $this->assertNull($this->repository->get('associate'));
    }
}