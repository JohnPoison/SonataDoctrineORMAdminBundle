<?php
/**
 * Created by JetBrains PhpStorm.
 * User: firesnake
 * Date: 01.03.12
 * Time: 16:11
 */

namespace Sonata\DoctrineORMAdminBundle\Filter;

use Sonata\AdminBundle\Form\Type\BooleanType;
use Sonata\AdminBundle\Form\Type\Filter\DatepickerType;
use Sonata\DoctrineORMAdminBundle\Filter\Filter;

class DatepickerFilter extends \Sonata\DoctrineORMAdminBundle\Filter\NumberFilter
{
    /**
     * @param $type
     * @return bool
     */
    private function getOperator($type)
    {
        $choices = array(
            DatepickerType::TYPE_EQUAL            => '=',
            DatepickerType::TYPE_GREATER_EQUAL    => '>=',
            DatepickerType::TYPE_GREATER_THAN     => '>',
            DatepickerType::TYPE_LESS_EQUAL       => '<=',
            DatepickerType::TYPE_LESS_THAN        => '<',
        );

        return isset($choices[$type]) ? $choices[$type] : false;
    }

    public function getRenderSettings()
    {
        return array('sonata_type_filter_datepicker', array(
            'field_type'    => 'sonata_type_datepicker',
            'field_options' => $this->getFieldOptions(),
            'label'         => $this->getLabel()
        ));
    }

    /**
     * @param \Sonata\DoctrineORMAdminBundle\Tests\Filter\QueryBuilder $queryBuilder
     * @param string $alias
     * @param string $field
     * @param string $data
     * @return
     */
    public function filter($queryBuilder, $alias, $field, $data)
    {
        if (!$data || !is_array($data) || !array_key_exists('value', $data) || !preg_match('/^\d{2}.\d{2}.\d{4}$/', $data['value'])) {
            return;
        }


        $type = isset($data['type']) ? $data['type'] : false;

        $operator = $this->getOperator($type);

        if (!$operator) {
            $operator = '=';
        }

        // c.name > '1' => c.name OPERATOR :FIELDNAME
        $this->applyWhere($queryBuilder, sprintf('%s.%s %s :%s', $alias, $field, $operator, $this->getName()));
        $queryBuilder->setParameter($this->getName(),  $data['value']);
    }

}
