<?php

declare(strict_types=1);

/*
 * This file is part of the Kimai time-tracking app.
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Crm\Domain\Doctrine\Extensions;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\AST\Node;
use Doctrine\ORM\Query\Parser;
use Doctrine\ORM\Query\SqlWalker;
use Doctrine\ORM\Query\TokenType;

/**
 * @package App\Crm\Domain\Doctrine\Extensions
 * @author  Rami Aouinti <rami.aouinti@tkdeutschland.de>
 */
final class Year extends FunctionNode
{
    private Node|string|null $value;

    public function getSql(SqlWalker $sqlWalker): string
    {
        return 'YEAR(' . $sqlWalker->walkArithmeticPrimary($this->value) . ')';
    }

    public function parse(Parser $parser): void
    {
        $parser->match(TokenType::T_IDENTIFIER);
        $parser->match(TokenType::T_OPEN_PARENTHESIS);
        $this->value = $parser->ArithmeticPrimary();
        $parser->match(TokenType::T_CLOSE_PARENTHESIS);
    }
}
