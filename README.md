# fuelphp-countcache
An fuelphp observer to maintain a count cache in another table

##usage

Put the included file in the fuel/app/classes/observer folder and then use from the model which is on the many side of the one-to-many relationship 

```php
protected static $_observers = array(
    'Observer_Countcache' => array(
        'events' => array('after_insert'),
        'destination_model' => 'Model_Definition',
        'foreign_key' => 'foreign_key_name',
        'count_field' => 'field_to_maintain_total',
        'where' => array(),
    ),
);
```

Fields:
- destination_model: Model which holds the field where the count is to be stored
- foriegn_key: foreign key name in the current model
- count_field: the name of the field where the count will be stored (in destination_model)
- where: a ORM query compatiable where array 

