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

namespace App\Controller;

use App\Service\InjectTest\Demo;
use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Snowflake\IdGeneratorInterface;

/**
 * @AutoController(prefix="snow")
 */
class SnowController extends Controller
{
    public function index()
    {
        $generator = di()->get(IdGeneratorInterface::class);

        $data = [];
        for ($i = 0; $i < 1000; ++$i) {
            $data[] = $generator->generate();
        }

        return $this->response->success(count(array_unique($data)));
    }

    public function inject()
    {
        $demo = di()->get(Demo::class);

        return $this->response->success([$demo->a->name, $demo->b->name]);
    }
}
