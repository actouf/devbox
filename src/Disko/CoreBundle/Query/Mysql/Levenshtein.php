<?php

namespace Disko\CoreBundle\Query\Mysql;

use Doctrine\ORM\Query\AST\Functions\FunctionNode;
use Doctrine\ORM\Query\Lexer;

class Levenshtein extends FunctionNode
{
    public $firstStringExpression = null;
    public $secondStringExpression = null;

    public function getSql(\Doctrine\ORM\Query\SqlWalker $sqlWalker)
    {
        return 'LEVENSHTEIN(' .
            $this->firstStringExpression->dispatch($sqlWalker) . ', ' .
            $this->secondStringExpression->dispatch($sqlWalker) .
            ')';
    }

    public function parse(\Doctrine\ORM\Query\Parser $parser)
    {
        // levenhstein(str1, str2)
        $parser->match(Lexer::T_IDENTIFIER);
        $parser->match(Lexer::T_OPEN_PARENTHESIS);
        $this->firstStringExpression = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_COMMA);
        $this->secondStringExpression = $parser->ArithmeticPrimary();
        $parser->match(Lexer::T_CLOSE_PARENTHESIS);
    }
}
