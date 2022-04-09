<?php

namespace LoveDuckie\SilverStripe\WellKnownValidation\Middleware;

// use SilverStripe\Control\Middleware\RequestHandlerMiddlewareAdapter;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\Middleware\HTTPMiddleware;
use Exception;

class WellKnownValidationGlobalMiddleware extends HTTPMiddleware
{
    public function process(HTTPRequest $request, callable $delegate)
    {
        if (empty($request)) {
            throw new Exception("The request object is invalid or null");
        }

        
        // return parent::handleRequest($request);
    }
}