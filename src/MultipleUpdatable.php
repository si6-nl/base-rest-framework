<?php

namespace Si6\Base;

use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

trait MultipleUpdatable
{
    use MultipleUpdateCaseWhen;
    use MultipleUpdateWithSubQuery;
}
