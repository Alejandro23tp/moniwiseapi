
<?php

class M_Ingresos extends \DB\SQL\Mapper
{
    public function __construct()
    {
        parent::__construct(\Base::instance()->get('DB'), 'ingresos');
    }    
}