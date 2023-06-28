<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataBirPlug\Api;

use Przeslijmi\AgileDataBirPlug\Api\HistoryState;
use Przeslijmi\AgileDataBirPlug\NipHandler;
use Przeslijmi\AgileDataBirPlug\ReportFormatter;

/**
 * Resource for API call for list of silos.
 */
class HistoryStatesReadApi extends HistoryState
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

        // Find data.
        $nip          = $this->route->getParam('nip');
        $company      = new NipHandler($nip);
        $data         = $company->getData();
        $currUtime    = time();
        $nextChecksum = null;

        $utimes = array_keys((array) $data->historyChecksums);
        rsort($utimes);
        $utimes = array_values($utimes);

        // Define list contents.
        foreach ($utimes as $order => $utime) {

            // Find nex checksum.
            if (isset($utimes[( $order + 1 )]) === true) {
                $nextChecksum = ( $data->historyChecksums->{$utimes[( $order + 1 )]} ?? null );
            } else {
                $nextChecksum = null;
            }

            // Create one task.
            $checksum     = $data->historyChecksums->{$utime};
            $details      = $data->history->{$checksum};
            $historyState = ( new ReportFormatter() )->formatIndex((object) $details->indexEntry);

            // Define one historyState.
            $historyState['id']   = $utime;
            $historyState['name'] = $details->report->name;
            $historyState['date'] = date('Y-m-d H:i:s', (int) $utime);
            $historyState['age']  = (int) round(( $currUtime - $utime ), 0);

            // Add change type.
            if ($nextChecksum === null) {
                $historyState['type'] = 'firstVersion';
            } elseif ($nextChecksum === $checksum) {
                $historyState['type'] = 'noChanges';
            } else {
                $historyState['type'] = 'update';
            }
            $historyState['typeLoc'] = $loc->get('Przeslijmi.AgileDataBirPlug.historyState.' . $historyState['type']);

            // Save it.
            $this->rows[] = $historyState;
        }//end foreach

        // Pack and send.
        $this->composeList();

        $this->sendJson($this->response);
    }
}
