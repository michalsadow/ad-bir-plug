<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataBirPlug\Api;

use Przeslijmi\AgileDataBirPlug\Api\Company;
use Przeslijmi\AgileDataBirPlug\IndexHandler;
use Przeslijmi\AgileDataBirPlug\ReportFormatter;

/**
 * Resource for API call for list of silos.
 */
class CompaniesReadApi extends Company
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

        // Find index.
        $index = new IndexHandler();

        // Define list contents.
        foreach ($index->getIndex() as $nip => $details) {

            // Define one company.
            $company = array_merge(
                [
                    'id' => $nip,
                    'nip' => $nip,
                ],
                ( new ReportFormatter() )->formatIndex($details),
            );

            // Save it.
            $this->rows[] = $company;
        }

        // Pack and send.
        $this->composeList();

        $this->sendJson($this->response);
    }
}
