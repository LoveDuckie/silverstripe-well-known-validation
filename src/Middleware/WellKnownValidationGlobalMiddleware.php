<?php

namespace LoveDuckie\SilverStripe\WellKnownValidation\Middleware;

use LoveDuckie\SilverStripe\WellKnownValidation\Models\DomainWellKnownValidationRule;
use SilverStripe\Control\Middleware\HTTPMiddleware;
use SilverStripe\Control\HTTPRequest;
use SilverStripe\Control\HTTPResponse;
use Exception;
use SilverStripe\SiteConfig\SiteConfig;

class WellKnownValidationGlobalMiddleware implements HTTPMiddleware
{
    private function tryValidationRequest(HTTPRequest $request, HTTPResponse &$response) {
        if (empty($request)) {
            throw new Exception("The request object is invalid or null");
        }
        
        return false;
    }

    public function process(HTTPRequest $request, callable $delegate)
    {
        if (empty($request)) {
            throw new Exception("The request object is invalid or null");
        }
        if (empty($delegate)) {
            throw new Exception("The delegate object is invalid or null");
        }
        $requestUrl = $request->getURL();
        if (str_starts_with($requestUrl, ".well-known")) {
            $siteConfig = SiteConfig::current_site_config();
            $currentDomainName = $siteConfig->getDomainName();
            $validationRules = DomainWellKnownValidationRule::get()->filter('DomainName',$currentDomainName);
            if (empty($validationRules)) {
                return null;
            }
            // $response = $delegate($request);
            $response = new HTTPResponse();
            $response->setBody('test');
            $response->setStatusCode(200);
            return $response;
        }

        return $delegate($request);
    }
}
