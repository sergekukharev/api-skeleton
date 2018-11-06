<?php

namespace SergeiKukhariev\ApiSkeleton\Errors;

interface ApiError
{
    public function getDetail(): string;
    public function getTitle(): string;
    public function getStatusCode(): int;
}