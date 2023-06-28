<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataBirPlug\Operations;

use Przeslijmi\AgileData\Operations\OperationsInterface as MyInterface;
use Przeslijmi\AgileDataBirPlug\BirOperationsParent as MyParent;
use Przeslijmi\AgileDataBirPlug\Dictionaries;
use stdClass;

/**
 * Operation that reads data from BIR service.
 */
class ReadFromBir extends MyParent implements MyInterface
{

    /**
     * Operation key.
     *
     * @var string
     */
    protected static $opKey = 'eoybxf2M';

    /**
     * Only those fields are accepted for this operation.
     *
     * @var array
     */
    public static $operationFields = [
        'active',
        'registry',
        'details',
        'oneRow',
        'nipLimit',
    ];

    /**
     * Get info (mainly name and category of this operation).
     *
     * @return stdClass
     */
    public static function getInfo(): stdClass
    {

        // Lvd.
        $locSta = 'Przeslijmi.AgileDataBirPlug.Operations.ReadFromBir.';

        // Lvd.
        $result             = new stdClass();
        $result->name       = $_ENV['LOCALE']->get($locSta . 'title');
        $result->vendor     = 'Przeslijmi\AgileDataBirPlug';
        $result->class      = self::class;
        $result->depr       = false;
        $result->category   = 100;
        $result->sourceName = $_ENV['LOCALE']->get($locSta . 'sourceName');

        return $result;
    }

    /**
     * Deliver fields to edit settings of this operation.
     *
     * @param string        $taskId Id of task in which edited step is present.
     * @param stdClass|null $step   Opt. Only when editing step (when creating it is null).
     *
     * @return array
     *
     * @phpcs:disable Generic.CodeAnalysis.UnusedFunctionParameter
     */
    public static function getStepFormFields(string $taskId, ?stdClass $step = null): array
    {

        // Lvd.
        $fields = [];
        $loc    = $_ENV['LOCALE'];
        $locSta = 'Przeslijmi.AgileDataBirPlug.Operations.ReadFromBir.fields.';
        $locSco = 'Przeslijmi.AgileDataBirPlug.scope.';

        // Create fields.
        $fields[] = [
            'type' => 'select',
            'id' => 'active',
            'value' => ( $step->active ?? 'a' ),
            'name' => $loc->get($locSta . 'active.name'),
            'desc' => $loc->get($locSta . 'active.desc'),
            'options' => [
                'y' => $loc->get($locSta . 'active.options.y'),
                'n' => $loc->get($locSta . 'active.options.n'),
                'a' => $loc->get($locSta . 'active.options.a'),
            ],
            'group' => $loc->get('Przeslijmi.AgileData.tabs.operation'),
        ];
        $fields[] = [
            'type' => 'select',
            'id' => 'registry',
            'multiple' => 4,
            'noDefaultOptions' => true,
            'value' => ( $step->registry ?? [ 'krs', 'ceidg', 'rural', 'other' ] ),
            'name' => $loc->get($locSta . 'registry.name'),
            'desc' => $loc->get($locSta . 'registry.desc'),
            'options' => [
                'krs' => $loc->get('Przeslijmi.AgileDataBirPlug.regs.krs'),
                'ceidg' => $loc->get('Przeslijmi.AgileDataBirPlug.regs.ceidg'),
                'rural' => $loc->get('Przeslijmi.AgileDataBirPlug.regs.rural'),
                'other' => $loc->get('Przeslijmi.AgileDataBirPlug.regs.other'),
            ],
            'group' => $loc->get('Przeslijmi.AgileData.tabs.operation'),
        ];
        $fields[] = [
            'type' => 'select',
            'id' => 'details',
            'multiple' => 12,
            'noDefaultOptions' => true,
            'value' => ( $step->details ?? null ),
            'name' => $loc->get($locSta . 'details.name'),
            'desc' => $loc->get($locSta . 'details.desc'),
            'options' => Dictionaries::getDetails(),
            'group' => $loc->get('Przeslijmi.AgileData.tabs.operation'),
        ];
        $fields[] = [
            'type' => 'select',
            'id' => 'oneRow',
            'value' => ( $step->oneRow ?? 'nipCeidg' ),
            'name' => $loc->get($locSta . 'oneRow.name'),
            'desc' => $loc->get($locSta . 'oneRow.desc'),
            'options' => [
                'registry' => $loc->get($locSta . 'oneRow.options.registry'),
                'nipCeidg' => $loc->get($locSta . 'oneRow.options.nipCeidg'),
                'nipRural' => $loc->get($locSta . 'oneRow.options.nipRural'),
                'nipOther' => $loc->get($locSta . 'oneRow.options.nipOther'),
                'state' => $loc->get($locSta . 'oneRow.options.state'),
            ],
            'group' => $loc->get('Przeslijmi.AgileData.tabs.operation'),
        ];
        $fields[] = [
            'type' => 'textarea',
            'id' => 'nipLimit',
            'value' => ( $step->nipLimit ?? '' ),
            'name' => $loc->get($locSta . 'nipLimit.name'),
            'desc' => $loc->get($locSta . 'nipLimit.desc'),
            'group' => $loc->get('Przeslijmi.AgileData.tabs.operation'),
        ];

        return $fields;
    }

    /**
     * Validates plug definition.
     *
     * @return void
     */
    public function validate(): void
    {

        // Test nodes.
        $this->testNodes($this->getStepPathInPlug(), $this->getStep(), [
            'active' => [ '!stringEnum', [ 'y', 'n', 'a' ] ],
            'registry' => [ '!arrayEnum', [ 'krs', 'ceidg', 'rural', 'other' ] ],
            'details' => '!array',
            'oneRow' => [ '!stringEnum', [ 'registry', 'nipCeidg', 'nipRural', 'nipOther', 'state' ] ],
        ]);
    }

    /**
     * Reads data from BIR plug.
     *
     * @return void
     */
    public function perform(): void
    {

        // Lvd.
        $data = [];

        // Get index of NIP's to return.
        foreach ($this->scanBirIndex() as $nip => $entry) {

            // Define that this is a NIP on which we currently are working.
            $this->currentIndexEntry = $entry;

            // Get all records for this NIP.
            $record = $this->getRecordsForNip((string) $nip);

            // Add all those records int final data.
            $data = array_merge($data, $record);
        }

        // Empty current index entry.
        $this->currentIndexEntry = null;

        // Calc data types.
        $dataTypes = Dictionaries::getDataTypes(true, $this->getStep()->details);

        // Define final data.
        $this->getCallingTask()->replaceRecords($data, $dataTypes);
    }

    /**
     * Delivers simple list of props that is available after this operation finishes work.
     *
     * @param array $inputProps Properties available in previous operation.
     *
     * @return array[]
     */
    public function getPropsAvailableAfter(array $inputProps): array
    {

        // Clean all previous props.
        $this->setProps($inputProps);
        $this->deleteProps($inputProps);

        // Lvd.
        $this->addParamAsProp($this->getTask());

        foreach (Dictionaries::getDataTypes(true, $this->getStep()->details) as $field => $dataType) {
            $this->addProp($field, $dataType);
        }

        return $this->availableProps;
    }
}
