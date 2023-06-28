<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataBirPlug\Api;

use Przeslijmi\AgileDataBirPlug\Api\Company;
use Przeslijmi\AgileDataBirPlug\NipHandler;

/**
 * Resource for API call for list of silos.
 */
class CompanyCreateApi extends Company
{

    /**
     * Answers for GET list of siloses on Api.
     *
     * @return void
     */
    public function post(): void
    {

        // Lvd.
        $loc  = $_ENV['LOCALE'];
        $body = ( $this->route->getBody() ?? new stdClass() );

        // Save every properly recognized NIP.
        foreach ($this->readAndValidateNips($body->nips) as $nip) {
            $handler = new NipHandler($nip);
            $handler->setRefreshOn((int) $body->refresh);
            $handler->save();
        }

        $this->sendJson($this->response);
    }
}
