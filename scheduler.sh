#! /bin/sh
#
# scheduler.sh
# Copyright (C) 2021 jbriceno <jbriceno@doris>
#
# Distributed under terms of the MIT license.
#


php app/artisan schedule:run --verbose --no-interaction >> /dev/null 2>&1
