<?php

echo `php ../app/console  env=prod cache:clear`;
echo `php ../app/console  env=prod cache:warm`;