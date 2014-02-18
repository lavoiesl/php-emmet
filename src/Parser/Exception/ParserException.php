<?php

namespace Lavoiesl\Emmet\Parser\Exception;

use Lavoiesl\Emmet\Parser\Token\LexerToken;

class ParserException extends Exception
{
    public function __construct(LexerToken $token)
    {
        $line = $token->line === 0 ? '' : "line " . $token->line . ', ';

        parent::__construct(sprintf("Unexpected %s at %soffset %d", $token, $line, $token->offset));
    }
}
