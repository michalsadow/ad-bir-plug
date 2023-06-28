<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataBirPlug\Api;

use Przeslijmi\AgileDataBirPlug\Api\Company;
use Przeslijmi\AgileDataBirPlug\Exceptions\NipDonoexException;
use Przeslijmi\AgileDataBirPlug\Exceptions\GusApiLoginFopException;
use Przeslijmi\AgileDataBirPlug\NipHandler;

/**
 * Resource for API call for list of silos.
 */
class CompanyRefreshApi extends Company
{

    /**
     * Answers for GET list of siloses on Api.
     *
     * @return void
     */
    public function get(): void
    {

        // Lvd.
        $loc = $_ENV['LOCALE'];
        $nip = $this->route->getParam('nip');

        // Try to refresh and handle errors.
        try {

            $handler = new NipHandler($nip);
            $handler->refresh();
            $handler->save();

        } catch (NipDonoexException $sexc) {

            // Add alert.
            $this->response['data']['alerts'][] = [
                'text' => $loc->get('Przeslijmi.AgileDataBirPlug.nipNotFound'),
                'type' => 'danger',
            ];
            $this->sendJson($this->response);
            return;

        } catch (GusApiLoginFopException $sexc) {

            // Lvd.
            $key = $sexc->getInfos()['usedBirGusApiKey'];

            // Add alert.
            $this->response['data']['alerts'][] = [
                'text' => $loc->get('Przeslijmi.AgileDataBirPlug.loginFailed', [ $key ]),
                'type' => 'danger',
            ];
            $this->sendJson($this->response);
            return;
        }//end try

        // Add alert.
        $this->response['data']['alerts'][] = [
            'text' => $loc->get('Przeslijmi.AgileDataBirPlug.companyRefreshSucceeded'),
            'type' => 'success',
        ];

        $this->sendJson($this->response);
    }
}
