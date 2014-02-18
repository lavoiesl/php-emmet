<?php

namespace Lavoiesl\Emmet\Parser\Exception;

use Lavoiesl\Emmet\Parser\Token\ParserToken;

class TokenUsageException extends Exception
{
    public function __construct(ParserToken $token, $message)
    {
        $tokens = $token->getTokens();
        $line = $tokens[0]->line === 0 ? '' : "line " . $tokens[0]->line . ', ';

        parent::__construct(sprintf("Usage error at %soffset %d: %s", $line, $tokens[0]->offset, $message));
    }
}
