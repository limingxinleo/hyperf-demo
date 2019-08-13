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

namespace App\Kernel\Model;

use Hyperf\Database\Commands\Ast\ModelUpdateVisitor as Visitor;
use PhpParser\Comment\Doc;
use PhpParser\Node;

class ModelUpdateVisitor extends Visitor
{
    public function leaveNode(Node $node)
    {
        switch ($node) {
            case $node instanceof Node\Stmt\PropertyProperty:
                if ($node->name == 'fillable') {
                    $node = $this->rewriteFillable($node);
                } elseif ($node->name == 'casts') {
                    $node = $this->rewriteCasts($node);
                } elseif ($node->name == 'connection') {
                    $node = $this->rewriteConnection($node);
                }

                return $node;
            case $node instanceof Node\Stmt\Class_:
                $doc = '/**' . PHP_EOL;
                foreach ($this->columns as $column) {
                    [$name, $type] = $this->getProperty($column);
                    $doc .= sprintf(' * @property %s $%s', $type, $name) . PHP_EOL;
                }
                $doc .= ' */';
                $node->setDocComment(new Doc($doc));
                return $node;
        }
    }

    protected function rewriteConnection(Node\Stmt\PropertyProperty $node): Node\Stmt\PropertyProperty
    {
        $node->default = new Node\Scalar\String_('default');
        return $node;
    }
}
