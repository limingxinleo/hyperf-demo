<?php

declare(strict_types=1);
/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://doc.hyperf.io
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf-cloud/hyperf/blob/master/LICENSE
 */

namespace App\Controller;

use Hyperf\HttpServer\Annotation\AutoController;
use Hyperf\View\Render;

/**
 * @AutoController
 */
class ViewController extends Controller
{
    public function index()
    {
        $render = di()->get(Render::class);

        $name = $this->request->input('name', 'limx');

        return $render->view('index.tpl', ['name' => $name]);
    }
}
