<?php

declare(strict_types = 1);

namespace Dekcz\MetaControl;

interface IMetaControlFactory
{

    public function create(): MetaControl;

}
