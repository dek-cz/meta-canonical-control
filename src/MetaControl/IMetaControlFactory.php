<?php
declare(strict_types = 1);

namespace Vrestihnat\MetaControl;

interface IMetaControlFactory
{

    public function create(): MetaControl;

}
