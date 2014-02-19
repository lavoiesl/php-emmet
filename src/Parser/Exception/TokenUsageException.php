<?php

namespace Lavoiesl\Emmet\Parser\Exception;

use Lavoiesl\Emmet\Parser\Token\ParserToken;

class TokenUsageException extends Exception
{
    public function __construct(ParserToken $token, $message)
    {
        parent::__construct(sprintf("Usage error at offset %d: %s", $token->offset, $message));
    }
}
