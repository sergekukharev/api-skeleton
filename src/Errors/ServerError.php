<?php


namespace SergeiKukhariev\ApiSkeleton\Errors;


use Fig\Http\Message\StatusCodeInterface;

class ServerError extends \RuntimeException implements ApiError
{
    private $title;
    private $detail;

    public function __construct(
        int $statusCode = StatusCodeInterface::STATUS_INTERNAL_SERVER_ERROR,
        string $title = '',
        string $detail = ''
    ) {
        if(!$this->isClientStatusCode($statusCode)) {
            throw new \InvalidArgumentException('Wrong client status code provided, should be 4xx');
        }

        $this->code = $statusCode;
        $this->title = $title;
        $this->detail = $detail;
    }

    public function getDetail(): string
    {
        return $this->detail;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    private function isClientStatusCode(int $statusCode)
    {
        return $statusCode >= 500 && $statusCode < 600;
    }

    public function getStatusCode(): int
    {
        return (int)$this->code;
    }
}