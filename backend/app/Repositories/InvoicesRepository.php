<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\Invoice;

class InvoicesRepository extends Repository
{
    public static function model()
    {
        return Invoice::class;
    }
}
