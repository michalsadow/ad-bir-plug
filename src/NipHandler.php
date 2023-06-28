<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataBirPlug;

use GusApi\Exception\InvalidUserKeyException;
use GusApi\Exception\NotFoundException;
use GusApi\GusApi;
use GusApi\ReportTypes;
use Przeslijmi\AgileData\Tools\JsonSettings as Json;
use Przeslijmi\AgileDataBirPlug\Exceptions\GusApiLoginFopException;
use Przeslijmi\AgileDataBirPlug\Exceptions\NipDonoexException;
use Przeslijmi\AgileDataBirPlug\IndexHandler;
use stdClass;

/**
 * Opens, reads, saves and deletes NIP files, updates info from BIR Api and calls to update index.
 */
class NipHandler
{

    /**
     * NIP for this instance.
     *
     * @var string
     */
    private $nip;

    /**
     * Current data of NIP file.
     *
     * @var array
     */
    private $data;

    /**
     * If this NIP exists in database or not.
     *
     * @var boolean
     */
    private $exists;

    /**
     * Additional information about registers.
     *
     * @var array
     */
    private $regs = [
        'BIR11OsPrawna' => [
            'type' => 'krs',
            'wasntStarted' => 'praw_NiePodjetoDzialalnosci',
        ],
        'BIR11OsFizycznaDzialalnoscCeidg' => [
            'type' => 'ceidg',
            'wasntStarted' => 'fizC_NiePodjetoDzialalnosci',
        ],
        'BIR11OsFizycznaDzialalnoscRolnicza' => [
            'type' => 'rural',
            'wasntStarted' => 'fizC_NiePodjetoDzialalnosci',
        ],
        'BIR11OsFizycznaDzialalnoscPozostala' => [
            'type' => 'other',
            'wasntStarted' => 'fizC_NiePodjetoDzialalnosci',
        ],
    ];

    /**
     * Which reports for which entry type `F` (person) or `P` (company).
     *
     * @var array
     */
    private $reports = [
        'F' => [
            ReportTypes::REPORT_PERSON,
            ReportTypes::REPORT_ORGANIZATION_ACTIVITY,
        ],
        'P' => [
            ReportTypes::REPORT_ORGANIZATION,
            ReportTypes::REPORT_ORGANIZATION_ACTIVITY,
        ],
    ];

    /**
     * Constructor.
     *
     * @param string $nipOrFile NIP for this instance or file name with NIP information (eg. `n.5213007709.json`).
     */
    public function __construct(string $nipOrFile)
    {

        // Is this a file name constructor or a NIP number constructor?
        if (strlen($nipOrFile) === 10) {
            $nip = $nipOrFile;
        } else {
            $nip = substr($nipOrFile, 2, 10);
        }

        // Save.
        $this->nip = $nip;

        // Reads if exists - or create empty one.
        if (file_exists($this->getUri()) === true) {

            // Return real.
            $this->data = json_decode(file_get_contents($this->getUri()));

            // Mark this exist.
            $this->exists = true;
        } else {

            // Return empty freshly created object.
            $this->data = (object) [
                'refreshOn' => null,
                'refreshDeadline' => null,
                'nip' => $this->nip,
                'current' => new stdClass(),
                'historyChecksums' => new stdClass(),
                'history' => new stdClass(),
            ];

            // Mark this does not exist.
            $this->exists = false;
        }//end if
    }

    /**
     * Deletes this nip from app.
     *
     * @return void
     */
    public function delete(): void
    {

        // Delete file.
        if (file_exists($this->getUri()) === true) {
            unlink($this->getUri());
        }

        // Call index.
        $index = new IndexHandler();
        $index->update($this->nip, null);
    }

    /**
     * Return if this NIP exists in database or not.
     *
     * @return boolean
     */
    public function isExisting(): bool
    {

        return $this->exists;
    }

    /**
     * Defines refreshing pace for this NIP.
     *
     * @param integer $refreshOn Pace in days (only 7, 14, 30, 60, 90, 180 possible, and -1 for **never**).
     *
     * @return void
     */
    public function setRefreshOn(int $refreshOn): void
    {

        // Correct.
        if (in_array($refreshOn, [ -1, 7, 14, 30, 60, 90, 180 ]) === false) {
            $refreshOn = -1;
        }

        // Save.
        $this->data->refreshOn       = $refreshOn;
        $this->data->refreshDeadline = $this->calcRefreshDeadline();

        // Update index.
        $this->updateIndex();
    }

    /**
     * Returns refreshing pace for this NIP (null when unknown).
     *
     * @return null|integer
     */
    public function getRefreshOn(): ?int
    {

        return ( $this->data->refreshOn ?? null );
    }

    /**
     * Calculates when this nip should be refreshed and return string date in YYYY-MM-DD format (null means never).
     *
     * @return null|string
     */
    private function calcRefreshDeadline(): ?string
    {

        // Get last refreshment unix time.
        if (count((array) $this->data->historyChecksums) === 0) {
            $lastRefreshUtime = 0;
        } else {
            $lastRefreshUtime = (int) max(array_keys((array) $this->data->historyChecksums));
        }

        // Check.
        if (is_int($this->data->refreshOn) === true && empty($lastRefreshUtime) === true) {

            // It was never refreshed - do it today.
            $refreshDeadline = date('Y-m-d');

        } elseif (is_int($this->data->refreshOn) === true) {

            // It was refreshed - calc next date.
            $refreshDeadline = date(
                'Y-m-d',
                mktime(0, 0, 0, (int) date('m'), ( (int) date('d') + $this->data->refreshOn ), (int) date('Y'))
            );

        } else {

            // It is not be refreshed.
            $refreshDeadline = null;
        }

        return $refreshDeadline;
    }

    /**
     * Calls GUS Api to refresh information on NIP.
     *
     * @throws GusApiLoginFopException When api key is wrong or login failed.
     * @throws NipDonoexException When given nip does not exists.
     *
     * @return void
     */
    public function refresh(): void
    {

        // Start client.
        if ($_ENV['PRZESLIJMI_ADBIRPLUG_BIR_KEY'] === 'abcde12345abcde12345') {
            $gus = new GusApi($_ENV['PRZESLIJMI_ADBIRPLUG_BIR_KEY'], 'dev');
        } else {
            $gus = new GusApi($_ENV['PRZESLIJMI_ADBIRPLUG_BIR_KEY']);
        }

        // Try to login.
        try {
            $gus->login();
        } catch (InvalidUserKeyException $exc) {
            throw new GusApiLoginFopException([ $_ENV['PRZESLIJMI_ADBIRPLUG_BIR_KEY'] ]);
        }

        // Try to get NIP.
        try {
            $entity = $gus->getByNip($this->nip)[0];
        } catch (NotFoundException $exc) {
            throw new NipDonoexException([ $this->nip ]);
        }

        // Calc type of NIP - `F` (person) or `P` (company).
        $type = mb_strtoupper($entity->getType());

        // Create main data.
        $data           = [];
        $data['report'] = [
            'regon' => $entity->getRegon(),
            'nip' => $entity->getNip(),
            'nipStatus' => $entity->getNipStatus(),
            'regon14' => $entity->getRegon14(),
            'name' => $entity->getName(),
            'province' => $entity->getProvince(),
            'district' => $entity->getDistrict(),
            'community' => $entity->getCommunity(),
            'city' => $entity->getCity(),
            'propertyNumber' => $entity->getPropertyNumber(),
            'apartmentNumber' => $entity->getApartmentNumber(),
            'zipCode' => $entity->getZipCode(),
            'street' => $entity->getStreet(),
            'type' => $entity->getType(),
            'silo' => $entity->getSilo(),
            'activityEndDate' => $entity->getActivityEndDate(),
            'postCity' => $entity->getPostCity(),
        ];

        // Call for other reports as defined.
        foreach (( $this->reports[$type] ?? [] ) as $reportType) {
            $data[$reportType] = $gus->getFullReport($entity, $reportType);
        }
        if ((int) ( $data['BIR11OsFizycznaDaneOgolne']['0']['fiz_dzialalnoscCeidg'] ?? '0' ) === 1) {
            $data['BIR11OsFizycznaDzialalnoscCeidg'] = $gus->getFullReport(
                $entity,
                'BIR11OsFizycznaDzialalnoscCeidg'
            );
        }
        if ((int) ( $data['BIR11OsFizycznaDaneOgolne']['0']['fiz_dzialalnoscRolnicza'] ?? '0' ) === 1) {
            $data['BIR11OsFizycznaDzialalnoscRolnicza'] = $gus->getFullReport(
                $entity,
                'BIR11OsFizycznaDzialalnoscRolnicza'
            );
        }
        if ((int) ( $data['BIR11OsFizycznaDaneOgolne']['0']['fiz_dzialalnoscPozostala'] ?? '0' ) === 1) {
            $data['BIR11OsFizycznaDzialalnoscPozostala'] = $gus->getFullReport(
                $entity,
                'BIR11OsFizycznaDzialalnoscPozostala'
            );
        }

        // Save data in index and in files.
        $this->update($data);
    }

    /**
     * Save given state to index and nip own file.
     *
     * @param array $currentState Complete information from GUS API to be saved.
     *
     * @return void
     */
    public function update(array $currentState): void
    {

        // Convert to object deep and core.
        foreach ($currentState as $id => $report) {
            $currentState[$id] = (object) $report;
        }
        $currentState = (object) $currentState;

        // Add current.
        $this->data->current = $currentState;

        // Get latest checksum.
        if (empty(array_keys((array) $this->data->historyChecksums)) === true) {
            $latestCheksum = null;
        } else {
            $maxTime       = max(array_keys((array) $this->data->historyChecksums));
            $latestCheksum = ( $this->data->historyChecksums->{$maxTime} ?? null );
        }
        $currentCheksum = crc32(serialize($currentState));

        // Add history checksum.
        $this->data->historyChecksums->{time()} = $currentCheksum;

        // Add history state - if anything changed.
        if ($latestCheksum !== $currentCheksum) {
            $this->data->history->{$currentCheksum} = $currentState;
        }

        // Calc new refresh deadline.
        $this->data->refreshDeadline = $this->calcRefreshDeadline();

        // Update index.
        $indexEntry = $this->updateIndex();

        // Save index inside file.
        $this->data->current->indexEntry                    = $indexEntry;
        $this->data->history->{$currentCheksum}->indexEntry = $indexEntry;
    }

    /**
     * Update information in index, return this NIP index entry.
     *
     * @return stdClass
     */
    private function updateIndex(): stdClass
    {

        // Lvd.
        $wasntStarted = [];

        // Prepare index entry.
        $entry = new stdClass();

        // Define most important.
        $entry->regon        = ( $this->data->current->report->regon ?? null );
        $entry->name         = ( $this->data->current->report->name ?? null );
        $entry->regs         = [];
        $entry->noOfChanges  = ( count((array) $this->data->history) - 1 );
        $entry->wasntStarted = false;

        // Calc is acive.
        if (empty(( $this->data->current->report->activityEndDate ?? null )) === true) {
            $entry->activityEnd = null;
        } else {
            $entry->activityEnd = $this->data->current->report->activityEndDate;
        }

        // Calc is was ever refreshed.
        if (empty(( $this->data->current->report->nip ?? null )) === true) {
            $entry->wasEverRefreshed = false;
            $entry->noOfChanges      = null;
        } else {
            $entry->wasEverRefreshed = true;
        }

        // Define registries.
        foreach ($this->regs as $regName => $regDef) {
            if (isset($this->data->current->{$regName}) === true) {
                $entry->regs[]  = $regDef['type'];
                $wasntStarted[] = ( $this->data->current->{$regName}->{$regDef['wasntStarted']} ?? null );
            }
        }

        // Check wasnt started.
        foreach ($wasntStarted as $id => $wasnt) {
            if ($wasnt === 'false') {
                $wasntStarted[$id] = null;
            }
        }
        if (count($wasntStarted) === 0 || empty(max($wasntStarted)) === false) {
            $entry->wasntStarted = true;
        }

        // Define last refresh date.
        if (count((array) $this->data->historyChecksums) === 0) {
            $lastRefreshUtime = 0;
        } else {
            $lastRefreshUtime = (int) max(array_keys((array) $this->data->historyChecksums));
        }
        if (empty($lastRefreshUtime) === false) {
            $entry->lastRefreshDate = date('Y-m-d', $lastRefreshUtime);
        } else {
            $entry->lastRefreshDate = null;
        }

        // Define the rest.
        $entry->refreshOn       = $this->data->refreshOn;
        $entry->refreshDeadline = $this->data->refreshDeadline;

        // Call index.
        $index = new IndexHandler();
        $index->update($this->nip, $entry);

        return $entry;
    }

    /**
     * Get current data for this NIP.
     *
     * @return stdClass
     */
    public function getData(): stdClass
    {

        return $this->data;
    }

    /**
     * Return list of history states.
     *
     * @param integer $howMany Optional, `-1`. How many states (from latest/youngest) have to be returned (-1 for all).
     *
     * @return array
     */
    public function getHistoryStatesOrdered(int $howMany = -1): array
    {

        // Lvd.
        $result = [];
        $states = (array) $this->data->history;
        $times  = array_flip((array) $this->data->historyChecksums);

        // Define every state date.
        foreach ($states as $crc32 => $state) {

            // Get date of this state.
            $date = date('Y-m-d H:i:s', $times[$crc32]);

            // Add this state.
            $result[$date] = $state;
        }

        // Sort (newest at the beginning).
        krsort($result);

        if ($howMany < 1) {
            return $result;
        } else {
            return array_slice($result, 0, $howMany);
        }
    }

    /**
     * Saves file.
     *
     * @return void
     */
    public function save(): void
    {

        // Save file.
        file_put_contents($this->getUri(), json_encode($this->data, Json::stdWrite()));
    }

    /**
     * Delivers file uri.
     *
     * @return string
     */
    private function getUri(): string
    {

        // Get from env.
        $dir = $_ENV['PRZESLIJMI_ADBIRPLUG_COMPANIES_DIR_URI'];

        // Create last dir if not exists.
        if (file_exists($dir) === false) {
            mkdir($dir);
        }

        return $dir . 'n.' . $this->nip . '.json';
    }
}
