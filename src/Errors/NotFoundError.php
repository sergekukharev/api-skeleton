<?php

namespace SergeiKukhariev\ApiSkeleton\Errors;

use Fig\Http\Message\StatusCodeInterface;

class NotFoundError extends ClientError
{
    public function __construct(string $title = '', string $detail = '')
    {
        parent::__construct(StatusCodeInterface::STATUS_NOT_FOUND, $title, $detail);
    }
}
