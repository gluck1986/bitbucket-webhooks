<?php

require_once dirname(__DIR__)
    .DIRECTORY_SEPARATOR . 'src'
    .DIRECTORY_SEPARATOR . '_bootstrap.php';

\BitbucketWebhooks\core\run(
    \BitbucketWebhooks\config\getConfig()
);