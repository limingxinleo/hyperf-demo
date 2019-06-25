<?php


namespace App\Aspect;

use App\Annotation\Formatter;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;

/**
 * @Aspect()
 */
class FormatterAspect extends AbstractAspect
{
    public $annotations = [
        Formatter::class,
    ];

    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        $result = $proceedingJoinPoint->process();
        if (! is_array($result)) {
            return $result;
        }
        return $this->formatter($result);
    }

    private function formatter(array $data)
    {
        foreach ($data as $k => $v){
            if ($v === []){
                $data[$k] = (object)$v;
            } elseif (is_array($v)) {
                $data[$k] = $this->formatter($v);
            }
        }
        return $data;
    }
}