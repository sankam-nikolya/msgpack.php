<?php

use MessagePack\Packer;
use MessagePack\PackOptions;
use MessagePack\Type\Map;
use MessagePack\TypeTransformer\MapTransformer;

require __DIR__.'/autoload.php';

$packer = new Packer(PackOptions::FORCE_ARR);
$packer->registerTransformer(new MapTransformer());

$packedArray = $packer->pack([1, 2, 3]);
$packedMap = $packer->pack(new Map([1, 2, 3]));

\printf("Packed array: %s\n", \implode(' ', \str_split(\bin2hex($packedArray), 2)));
\printf("Packed map:   %s\n", \implode(' ', \str_split(\bin2hex($packedMap), 2)));
