<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataBirPlug;

use stdClass;
use Przeslijmi\AgileData\Tools\JsonSettings as Json;

/**
 * Keeps index up to date.
 *
 * Index keeps:
 *   - nip
 *   - regon
 *   - name
 *   - is active?
 *   - no of changes
 *   - was ever refreshed?
 *   - last refresh date
 *   - refresh on
 *   - refresh deadline
 */
class IndexHandler
{

    /**
     * Updates/deletes information in index for given NIP.
     *
     * @param string   $nip     Ten-digits NIP number.
     * @param stdClass $details Details to be updated.
     *
     * @return void
     */
    public function update(string $nip, ?stdClass $details): void
    {

        // Get index and add current nip.
        $index = $this->getIndex();

        // Add/change or delete.
        if ($details === null && isset($index->{$nip}) === true) {
            unset($index->{$nip});
        } else {
            $index->{$nip} = $details;
        }

        // Save index.
        $this->save($index);
    }

    /**
     * Delivers whole index.
     *
     * @return stdClass
     */
    public function getIndex(): stdClass
    {

        // Lvd.
        $uri = $this->getUri();

        // Deliver real index if it exists.
        if (file_exists($uri) === true) {
            return json_decode(file_get_contents($this->getUri()));
        }

        // Deliver empty otherwise.
        return new stdClass();
    }

    /**
     * Saves file.
     *
     * @param stdClass $index Index contents to be saved.
     *
     * @return void
     */
    private function save(stdClass $index): void
    {

        // Save file.
        file_put_contents($this->getUri(), json_encode($index, Json::stdWrite()));
    }

    /**
     * Delivers index file uri (see config `PRZESLIJMI_ADBIRPLUG_INDEX_URI`).
     *
     * @return string
     */
    private function getUri(): string
    {

        return $_ENV['PRZESLIJMI_ADBIRPLUG_INDEX_URI'];
    }
}
