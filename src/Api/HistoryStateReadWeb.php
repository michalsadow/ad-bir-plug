<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataBirPlug\Api;

use Przeslijmi\AgileDataBirPlug\Api\HistoryState;
use Przeslijmi\AgileDataBirPlug\NipHandler;
use Przeslijmi\AgileDataBirPlug\ReportFormatter;

/**
 * Resource showing page with list of fieldses.
 */
class HistoryStateReadWeb extends HistoryState
{

    /**
     * Answers for GET list of fieldses on Api.
     *
     * @return void
     */
    public function get(): void
    {

        // Preparations.
        $this->prepareSwts('historyState', ( dirname(dirname(dirname(__FILE__))) . '/tpl/' ));

        // Lvd.
        $loc = $_ENV['LOCALE'];

        // Find data.
        $nip      = $this->route->getParam('nip');
        $company  = new NipHandler($nip);
        $checksum = $company->getData()->historyChecksums->{$this->route->getParam('stateId')};
        $state    = $company->getData()->history->{$checksum};

        // Add main information.
        $this->swts->assign('nip', $nip);
        $this->swts->assign('company', $company);
        $this->swts->assign('activities', ( new ReportFormatter() )->formatState($state));

        // Print parsed contents.
        echo $this->swts->parse();
    }
}
