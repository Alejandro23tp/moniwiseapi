<?php

class M_TipoUsuarios extends \DB\SQL\Mapper
{
    public function __construct()
    {
        parent::__construct(\Base::instance()->get('DB'), 'tiposusuario');
    }
}
