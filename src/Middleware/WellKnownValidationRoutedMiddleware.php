<?php

namespace LoveDuckie\SilverStripe\WellKnownValidation\Middleware;

use SilverStripe\Control\Middleware\RequestHandlerMiddlewareAdapter;
use SilverStripe\Control\HTTPRequest;
class WellKnownValidationRoutedMiddleware extends RequestHandlerMiddlewareAdapter
{
    public function handleRequest(HTTPRequest $request)
    {
        return parent::handleRequest($request);
    }
}