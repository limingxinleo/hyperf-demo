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

use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\Utils\ApplicationContext;

/**
 * @AutoController(prefix="about")
 */
class AboutController
{
    /**
     * @var \Redis
     */
    protected $redis;

    public function __construct()
    {
        $this->redis = ApplicationContext::getContainer()->get(\Redis::class);
    }

    public function index()
    {
        $this->redis->set('about', uniqid());
        return $this->redis->get('about');
    }
}
