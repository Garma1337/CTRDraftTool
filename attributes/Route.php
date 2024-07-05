<?php

declare(strict_types = 1);

namespace DraftTool\Attributes;

use Attribute;
use DraftTool\Services\Request;

/**
 * Route attribute
 * @author Garma
 */
#[Attribute]
class Route
{
    /**
     * @param string $value
     */
    public function __construct(
        public string $value = '',
        public array $acceptedMethods = [Request::METHOD_GET, Request::METHOD_POST]
    ) {}
}
