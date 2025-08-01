<?php
namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Models\Subscriber;

class SubscriberRepository extends Repository
{
    public static function model()
    {
        return Subscriber::class;    
    }
}