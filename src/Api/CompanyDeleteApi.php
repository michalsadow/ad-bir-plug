<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataBirPlug\Api;

use Przeslijmi\AgileDataBirPlug\Api\Company;
use Przeslijmi\AgileDataBirPlug\NipHandler;

/**
 * Resource for API call for list of silos.
 */
class CompanyDeleteApi extends Company
{

    /**
     * Answers for GET list of siloses on Api.
     *
     * @return void
     */
    public function delete(): void
    {

        // Lvd.
        $nip  = $this->route->getParam('nip');
        $body = ( $this->route->getBody() ?? new stdClass() );

        // Check acceptance.
        if ($body->doDelete !== 'yes') {

            // Add error.
            $this->addError(
                $_ENV['LOCALE']->get('Przeslijmi.AgileData.delete.noAgreement')
            );

            // Send reponse immediately.
            $this->sendJson($this->response);
            return;
        }

        // Delete nip.
        $handler = new NipHandler($nip);
        $handler->delete();

        $this->sendJson($this->response);
    }
}
