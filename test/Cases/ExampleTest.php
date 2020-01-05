<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

namespace HyperfTest\Cases;

use HyperfTest\HttpTestCase;

/**
 * @internal
 * @coversNothing
 */
class ExampleTest extends HttpTestCase
{
    public function testExample()
    {
        $this->assertTrue(true);

        $res = $this->get('/');

        $this->assertSame(0, $res['code']);
        $this->assertSame('Hello Hyperf.', $res['data']['message']);
        $this->assertSame('GET', $res['data']['method']);
        $this->assertSame('Hyperf', $res['data']['user']);

        $res = $this->get('/', ['user' => 'limx']);

        $this->assertSame(0, $res['code']);
        $this->assertSame('limx', $res['data']['user']);

        $res = $this->post('/', [
            'user' => 'limx',
        ]);
        $this->assertSame('Hello Hyperf.', $res['data']['message']);
        $this->assertSame('POST', $res['data']['method']);
        $this->assertSame('limx', $res['data']['user']);
    }

    public function testSuperGlobals()
    {
        for ($i = 0; $i < 1; $i++) {
            $res = $this->json('/sg/index?id=' . $id = uniqid(), [
                'name' => $name = 'Hyperf' . uniqid(),
            ], [
                'X-Token' => $token = uniqid(),
            ]);

            $this->assertSame(0, $res['code']);
            $this->assertSame($id, $res['data']['get']['id']);
            $this->assertSame($id, $res['data']['request']['id']);
            $this->assertSame($name, $res['data']['post']['name']);
            $this->assertSame($name, $res['data']['request']['name']);
            $this->assertSame($token, $res['data']['server']['HTTP_X_TOKEN'][0]);
        }
    }
}
