<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataBirPlug;

use Przeslijmi\AgileData\Operations\OperationsParent as MyParent;
use Przeslijmi\AgileDataBirPlug\IndexHandler as BirIndex;
use Przeslijmi\AgileDataBirPlug\NipHandler;
use Przeslijmi\AgileDataBirPlug\ReportFormatter;
use stdClass;

/**
 * Operation that reads data from Bir TERC JSON file.
 */
abstract class BirOperationsParent extends MyParent
{

    /**
     * Current NIP index entry - the one the app is working on currently.
     *
     * @var stdClass
     */
    protected $currentIndexEntry;

    /**
     * Used below when returning final records.
     *
     * @var integer
     */
    private $idCounter = 0;

    /**
     * Wheter to translate props names with locale - or leave unchanged.
     *
     * @var boolean
     */
    protected $translatePropsToLocale = true;

    /**
     * Converts any text sent by the user into proper NIPS - searching for NIPs.
     *
     * @param string $nips Text that will be scanned.
     *
     * @return array
     */
    protected function getOnlyProperNips(string $nips): array
    {

        // Lvd.
        $nips = preg_replace('/([^0-9-])/', ',', $nips);
        $nips = explode(',', str_replace('-', '', $nips));

        // Ignore empty and invalid.
        foreach ($nips as $nipId => $nip) {
            if (empty($nip) === true || strlen($nip) !== 10) {
                unset($nips[$nipId]);
                continue;
            }
        }

        return array_values($nips);
    }

    /**
     * Scan BIR index to decide which NIPs has to be returned in final report and which are not to be returned.
     *
     * @param array $nipLimitArray Optional, alternative way to send NIP limit string.
     *
     * @return array
     */
    protected function scanBirIndex(array $nipLimitArray = []): array
    {

        // Lvd.
        $today         = date('Y-m-d');
        $step          = $this->getStep();
        $nipLimit      = array_merge($nipLimitArray, $this->getOnlyProperNips(( $this->getStep()->nipLimit ?? '' )));
        $nipLimitCount = count($nipLimit);

        // Get index.
        $bir   = new BirIndex();
        $index = (array) clone($bir->getIndex());

        // Scan index.
        foreach ($index as $nip => $entry) {

            // Service nip limit.
            if ($nipLimitCount > 0 && in_array($nip, $nipLimit) === false) {
                unset($index[$nip]);
                continue;
            }

            // Ignore non-refreshed.
            if (empty($entry->wasEverRefreshed) === true) {
                unset($index[$nip]);
                continue;
            }

            // Lvd.
            $isInRegistry        = false;
            $hasProperActiveness = false;
            $expActiveness       = ( $step->active ?? 'a' );

            // Check if is in registry.
            if (count($step->registry) === 4) {
                $isInRegistry = true;
            } else {
                $isInRegistry = ( count(array_intersect($step->registry, $entry->regs)) > 0 );
            }

            // Check activeness.
            if ($expActiveness === 'a') {
                $hasProperActiveness = true;
            } elseif ($expActiveness === 'y' && ( $entry->activityEnd === null || $entry->activityEnd > $today )) {
                $hasProperActiveness = true;
            } elseif ($expActiveness === 'n' && $entry->activityEnd !== null && $entry->activityEnd <= $today) {
                $hasProperActiveness = true;
            }

            // Eliminate those that are not expected.
            if ($isInRegistry === false || $hasProperActiveness === false) {
                unset($index[$nip]);
            }
        }//end foreach

        return $index;
    }

    /**
     * Get one or more records for given NIP.
     *
     * @param string $nip Company NIP (10 digits).
     *
     * @return array
     */
    protected function getRecordsForNip(string $nip): array
    {

        // Lvd.
        $records    = [];
        $oneRow     = $this->getStep()->oneRow;
        $allReports = [ 'krs', 'ceidg', 'rural', 'other' ];
        $delReports = array_diff($allReports, $this->getStep()->registry);

        // Get NIP whole data.
        $nipHandler = new NipHandler($nip);

        // What to work on?
        if ($this->getStep()->oneRow === 'state') {
            $nipData = $nipHandler->getHistoryStatesOrdered();
        } else {
            $nipData = $nipHandler->getHistoryStatesOrdered(1);
        }

        // Start work.
        foreach ($nipData as $stateTime => $state) {

            // Get formatted state.
            $stateFrmt = ( new ReportFormatter() )->ignoreReports($delReports)->formatState($state);

            // Decide.
            if ($this->getStep()->oneRow === 'registry' || $this->getStep()->oneRow === 'state') {

                // Get every registry.
                foreach ($allReports as $report) {
                    if (isset($stateFrmt[$report]) === true) {
                        $records[] = $this->getRecordForNipStateReg($nip, $stateTime, $report, $stateFrmt[$report]);
                    }
                }

            } else {

                // Get only one registry - but which?
                // Possibilities IF THERE IS A CONFLICT are:
                // nipCeidg - ceidg if exists - any other otherwise
                // nipRural - rural if exists - any other otherwise
                // nipOther - other if exists - any other otherwise
                // There is no possibility of conflict between KRS and CEIDG.
                if (isset($stateFrmt['krs']) === true) {
                    $records[] = $this->getRecordForNipStateReg($nip, $stateTime, 'krs', $stateFrmt['krs']);
                } elseif ($oneRow === 'nipCeidg' && isset($stateFrmt['ceidg']) === true) {
                    $records[] = $this->getRecordForNipStateReg($nip, $stateTime, 'ceidg', $stateFrmt['ceidg']);
                } elseif ($oneRow === 'nipRural' && isset($stateFrmt['rural']) === true) {
                    $records[] = $this->getRecordForNipStateReg($nip, $stateTime, 'rural', $stateFrmt['rural']);
                } elseif ($oneRow === 'nipOther' && isset($stateFrmt['other']) === true) {
                    $records[] = $this->getRecordForNipStateReg($nip, $stateTime, 'other', $stateFrmt['other']);
                } else {

                    // Define any report.
                    $anyReport = array_rand(array_flip(array_intersect(
                        $allReports,
                        array_keys($stateFrmt)
                    )));

                    // Get any report.
                    $records[] = $this->getRecordForNipStateReg($nip, $stateTime, $anyReport, $stateFrmt[$anyReport]);
                }
            }//end if
        }//end foreach

        // Free memory.
        $nipData    = null;
        $nipHandler = null;

        return $records;
    }

    /**
     * Get one final record for given NIP, historical state and registry.
     *
     * @param string $nip        Company NIP (10 digits).
     * @param string $stateTime  State time in YYYY-MM-DD HH:MM:SS format.
     * @param string $reportName Name of the report (krs, ceidg, rural, other).
     * @param array  $report     Contents of the report.
     *
     * @return stdClass
     *
     * @phpcs:disable Generic.Metrics.CyclomaticComplexity
     */
    protected function getRecordForNipStateReg(
        string $nip,
        string $stateTime,
        string $reportName,
        array $report
    ): stdClass {

        // Create properties container.
        $props = new stdClass();
        $entry = $this->currentIndexEntry;
        $loc   = $_ENV['LOCALE'];

        // Find list of fields to be downloaded.
        if (isset($this->getStep()->details) === true) {
            $fields = $this->getStep()->details;
        } elseif (isset($this->getStep()->mapColumns) === true) {
            foreach ($this->getStep()->mapColumns as $map) {
                $fields[] = $map->sourceField;
            }
            $fields[] = 'nip';
        } else {
            $fields = [];
        }

        // Define propeties.
        foreach ($fields as $field) {

            // Find prop proper name.
            if ($this->translatePropsToLocale === true) {
                $propName = $loc->get('Przeslijmi.AgileDataBirPlug.details.' . $field . '.name');
            } else {
                $propName = $field;
            }

            if (substr($field, 0, 2) === 'r.') {

                $path = explode('.', substr($field, 2));

                if (count($path) === 1) {
                    $props->{$propName} = (string) $report[$path[0]];
                } elseif (count($path) === 2) {
                    $props->{$propName} = (string) $report[$path[0]][$path[1]];
                } elseif (count($path) === 3) {
                    $props->{$propName} = (string) $report[$path[0]][$path[1]][$path[2]];
                }

            } elseif ($field === 'reg') {
                $props->{$propName} = (string) $loc->get('Przeslijmi.AgileDataBirPlug.regs.' . $reportName);

            } elseif ($field === 'date') {
                $props->{$propName} = (string) substr($stateTime, 0, 10);

            } elseif ($field === 'nip') {
                $props->{$propName} = (string) $nip;

            } elseif ($field === 'activityEnd') {
                $props->{$propName} = (string) $entry->{'activityEnd'};

            } elseif ($field === 'numberOfChanges') {
                $props->{$propName} = (string) $entry->{'noOfChanges'};

            } elseif ($field === 'lastRefreshDate') {
                $props->{$propName} = (string) $entry->{'lastRefreshDate'};

            } elseif ($field === 'refreshDeadline') {
                $props->{$propName} = (string) $entry->{'refreshDeadline'};

            }//end if
        }//end foreach

        // Return object.
        return (object) [
            'info' => (object) [
                'rowId' => ( ++$this->idCounter ),
            ],
            'properties' => $props,
        ];
    }
}
