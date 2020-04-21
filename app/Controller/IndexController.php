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

use Hyperf\Di\Annotation\Inject;
use Hyperf\Utils\Codec\Json;
use League\Flysystem\Filesystem;

class IndexController extends Controller
{
    /**
     * @Inject
     * @var Filesystem
     */
    protected $filesystem;

    public function index()
    {
        try {
            $res = $this->filesystem->put('test.json', Json::encode([
                'id' => uniqid(),
            ]));
            var_dump($res);
        } catch (\Throwable $exception) {
            var_dump((string) $exception);
        }

        return $this->response->success();
    }
}
