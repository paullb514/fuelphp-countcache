<?php

/**
 * Fuel
 *
 * Fuel is a fast, lightweight, community driven PHP5 framework.
 *
 * @package    Fuel
 * @version    1.7
 * @author     Fuel Development Team
 * @license    MIT License
 * @copyright  2010 - 2015 Fuel Development Team
 * @link       http://fuelphp.com
 */

/**
 * Countcache observer. Maintains a count in another table
 */
class Observer_Countcache extends Orm\Observer
{

        /**
         * @var  string  default property to set the count to
         */
        public static $count_variable = 'count';

        /**
         * @var  array  ORM friendly array for a where clause. This restricts which elements that are included
         */
        protected $_where;

        /**
         * @var  string  whether to overwrite an already set timestamp
         */
        protected $_count_field;

        /**
         * @var  string  whether to overwrite an already set timestamp
         */
        protected $_foreign_key;

        /**
         * @var  string  The model to which the data will be saved
         */
        protected $_destination_model;

        /**
         * Set the properties for this observer instance, based on the parent model's
         * configuration or the defined defaults.
         *
         * @param  string  Model class this observer is called on
         */
        public function __construct($class)
        {
                $props = $class::observers(get_class($this));

                if (!isset($props['foreign_key']))
                {
                        throw new Exception('Foreign Key is a required attribute');
                }
                if (!isset($props['destination_model']))
                {
                        throw new Exception('Destination Model is a required attribute');
                }

                $this->_destination_model = $props['destination_model'];
                $this->_foreign_key = $props['foreign_key'];
                $this->_where = isset($props['where']) ? $props['where'] : array();
                $this->_count_field = isset($props['count_field']) ? $props['count_field'] : static::$count_field;
        }

        /**
         * Update the parent count to the number of matching elements
         *
         * @param  Model  Model object subject of this observer method
         */
        public function after_insert(Orm\Model $model)
        {
                $destination_model = new $this->_destination_model();

                $where = array($this->_foreign_key => $model->{$this->_foreign_key});
                $where_clause = array_merge($where, $this->_where);

                $count = $model->count(array('where' => $where_clause));

                $result = $destination_model::find($model->{$this->_foreign_key});
                $result->set($this->_count_field, $count);
                $result->save();
        }
}
