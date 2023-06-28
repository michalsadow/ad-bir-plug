<?php

declare(strict_types=1);

namespace Przeslijmi\AgileDataBirPlug\Operations;

use Przeslijmi\AgileData\Operations\OperationsInterface as MyInterface;
use Przeslijmi\AgileDataBirPlug\BirOperationsParent as MyParent;
use Przeslijmi\AgileDataBirPlug\Dictionaries;
use stdClass;

/**
 * Operation that merge data with BIR info.
 */
class MergeWithBir extends MyParent implements MyInterface
{

    /**
     * Operation key.
     *
     * @var string
     */
    protected static $opKey = '4yKS0TKs';

    /**
     * Only those fields are accepted for this operation.
     *
     * @var array
     */
    public static $operationFields = [
        'nipSource',
        'mapColumns_sourceField_*',
        'mapColumns_destinationProp_*',
        'registry',
        'oneRow',
    ];

    /**
     * Get info (mainly name and category of this operation).
     *
     * @return stdClass
     */
    public static function getInfo(): stdClass
    {

        // Lvd.
        $locSta = 'Przeslijmi.AgileDataBirPlug.Operations.MergeWithBir.';

        // Lvd.
        $result           = new stdClass();
        $result->name     = $_ENV['LOCALE']->get($locSta . 'title');
        $result->vendor   = 'Przeslijmi\AgileDataBirPlug';
        $result->class    = self::class;
        $result->depr     = false;
        $result->category = 200;

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
        $locSta = 'Przeslijmi.AgileDataBirPlug.Operations.MergeWithBir.fields.';
        $locSco = 'Przeslijmi.AgileDataBirPlug.scope.';

        // Convert multi field aggregation into form records.
        $mapColumnsRecords = self::packMultiFieldsIntoRecord($step, 'mapColumns', [
            'sourceField' => '',
            'destinationProp' => '',
        ]);

        // Create fields.
        $fields[] = [
            'type' => 'select',
            'id' => 'nipSource',
            'value' => ( $step->nipSource ?? '' ),
            'name' => $loc->get($locSta . 'nipSource.name'),
            'desc' => $loc->get($locSta . 'nipSource.desc'),
            'options' => [],
            'isAvailablePropChooser' => true,
            'group' => $loc->get('Przeslijmi.AgileData.tabs.operation'),
        ];
        $fields[] = [
            'type' => 'multi',
            'id' => 'mapColumns',
            'allowAdding' => true,
            'allowDeleting' => true,
            'allowReorder' => true,
            'name' => $loc->get($locSta . 'mapColumns.name'),
            'desc' => $loc->get($locSta . 'mapColumns.desc'),
            'subFields' => [
                [
                    'name' => $loc->get($locSta . 'mapColumns.sourceField.name'),
                    'type' => 'select',
                    'id' => 'mapColumns_sourceField',
                    'options' => Dictionaries::getColumnOptions(),
                ],
                [
                    'name' => $loc->get($locSta . 'mapColumns.destinationProp.name'),
                    'type' => 'text',
                    'id' => 'mapColumns_destinationProp',
                    'htmlData' => [
                        'copy-source-on-dbl' => 'mapColumns_sourceField',
                    ],
                ],
            ],
            'values' => $mapColumnsRecords,
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
            'id' => 'oneRow',
            'value' => ( $step->oneRow ?? 'nipCeidg' ),
            'name' => $loc->get($locSta . 'oneRow.name'),
            'desc' => $loc->get($locSta . 'oneRow.desc'),
            'options' => [
                'nipCeidg' => $loc->get($locSta . 'oneRow.options.nipCeidg'),
                'nipRural' => $loc->get($locSta . 'oneRow.options.nipRural'),
                'nipOther' => $loc->get($locSta . 'oneRow.options.nipOther'),
            ],
            'group' => $loc->get('Przeslijmi.AgileData.tabs.operation'),
        ];

        return $fields;
    }

    /**
     * Prevalidator is optional in operation class and converts step if it is needed.
     *
     * @param stdClass $step Original step.
     *
     * @return stdClass Converted step.
     */
    public function preValidation(stdClass $step): stdClass
    {

        // Unpack mapColumns.
        if (isset($step->mapColumns) === false) {
            $step = $this->unpackMultiFieldsToRecords($step, 'mapColumns');
        }

        return $step;
    }

    /**
     * Validates plug definition.
     *
     * @return void
     */
    public function validate(): void
    {

        // Lvd.
        $existingProps = array_column($this->getPropsAvailableBefore($this->getStepId()), 'name');

        // Test nodes.
        $this->testNodes($this->getStepPathInPlug(), $this->getStep(), [
            'nipSource' => [ '!stringEnum', $existingProps ],
            'mapColumns' => '!array',
            'registry' => [ '!arrayEnum', [ 'krs', 'ceidg', 'rural', 'other' ] ],
            'oneRow' => [ '!stringEnum', [ 'nipCeidg', 'nipRural', 'nipOther' ] ],
        ]);

        // Test each map columns definition.
        foreach ($this->getStep()->mapColumns as $colKey => $colDef) {
            $this->testNodes($this->getStepPathInPlug() . '@mapColumns*' . $colKey, $colDef, [
                'sourceField' => [ '!stringEnum', array_keys(Dictionaries::getColumnOptions()) ],
                'destinationProp' => '!propName',
            ]);
        }
    }

    /**
     * Reads data from BIR plug.
     *
     * @return void
     */
    public function perform(): void
    {

        // Current data.
        $data     = $this->getCallingTask()->getRecords();
        $newData  = [];
        $nips     = [];
        $oldProps = $this->getCallingTask()->getProperties();
        $newProps = [];

        // Get list of nips for which merging will be done.
        foreach ($data as $recordId => $record) {
            if (isset($record->properties->{$this->getStep()->nipSource}) === true) {

                // Clear from anything other than digits.
                $nip = preg_replace('/[^0-9]/', '', $record->properties->{$this->getStep()->nipSource});

                // Add.
                if (empty($nip) === false) {
                    $nips[] = $nip;
                }
            }
        }

        // Get unique NIPs.
        $nips = array_unique($nips);

        // Get index of NIP's to return.
        foreach ($this->scanBirIndex($nips) as $nip => $entry) {

            // Define that this is a NIP on which we currently are working.
            $this->currentIndexEntry = $entry;

            // Get all records for this NIP.
            $this->translatePropsToLocale = false;
            $record                       = $this->getRecordsForNip((string) $nip);

            // Add all those records int final data.
            $newData = array_merge($newData, $record);
        }

        // Make data key a NIP.
        foreach ($newData as $recordId => $record) {
            $newData[$record->properties->{'nip'}] = $record->properties;
            unset($newData[$recordId]);
        }

        // Merge data.
        foreach ($data as $recordId => $record) {

            // Lvd.
            $nip           = ( $record->properties->{$this->getStep()->nipSource} ?? null );
            $newDataForNip = ( $newData[$nip] ?? null );

            // Add every merged property to final data.
            foreach ($this->getStep()->mapColumns as $map) {

                // Create info for data type getter.
                $newProps[] = $map->{'sourceField'};

                // Find value for this property.
                $value = ( $newDataForNip->{$map->{'sourceField'}} ?? '' );

                // Add every merged property value to final data.
                $data[$recordId]->properties->{$map->{'destinationProp'}} = $value;
            }
        }//end foreach

        // Calc data types.
        foreach ($oldProps as $oldPropName => $oldPropDef) {
            $dataTypes[$oldPropName] = $oldPropDef->dataType;
        }
        $dataTypes = array_merge(
            $dataTypes,
            Dictionaries::getDataTypes(true, $newProps)
        );

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

        // That is for start.
        $this->setProps($inputProps);

        // Lvd.
        $dataTypes = Dictionaries::getDataTypes(false);

        // Get fields to calc data type.
        foreach ($this->getStep()->mapColumns as $map) {
            $this->addProp(
                $map->{'destinationProp'},
                $dataTypes[$map->{'sourceField'}],
                [ $this->getStep()->nipSource ]
            );
        }

        return $this->availableProps;
    }
}
