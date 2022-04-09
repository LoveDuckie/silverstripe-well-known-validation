<?php

namespace LoveDuckie\SilverStripe\WellKnownValidation\Controllers;

// use SilverStripe\Control\Middleware\RequestHandlerMiddlewareAdapter;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\Controller;
use SilverStripe\Control\Middleware\HTTPMiddleware;
use Exception;

class WellKnownValidationController extends Controller
{
    private static $allowed_actions = ['index'];

    // public function process(HTTPRequest $request, callable $delegate)
    // {
    //     if (empty($request)) {
    //         throw new Exception("The request object is invalid or null");
    //     }

        
    //     // return parent::handleRequest($request);
    // }
}