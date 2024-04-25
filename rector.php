<?php

use Rector\Config\RectorConfig;

return RectorConfig::configure()
    // here we can define, what prepared sets of rules will be applied
    ->withPreparedSets(
        true,
        true,
        true
    );
