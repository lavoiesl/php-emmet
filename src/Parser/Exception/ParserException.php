<?php

namespace Lavoiesl\Emmet\Parser\Exception;

use Lavoiesl\Emmet\Parser\Token\LexerToken;

class ParserException extends Exception
{
    public function __construct(LexerToken $token)
    {
        parent::__construct(sprintf("Unexpected %s at offset %d", $token, $token->offset));
    }
}
