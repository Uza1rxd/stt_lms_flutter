<?php

namespace App\Repositories;

use Abedin\Maker\Repositories\Repository;
use App\Enum\MediaTypeEnum;
use App\Models\OfferBanner;

class OfferBannerRepository extends Repository
{
    public static function model()
    {
        return OfferBanner::class;
    }

    public static function storeByRequest($request, $isActive)
    {
        $thumbnail = MediaRepository::storeByRequest(
            $request->file('thumbnail'),
            'subscription-banner/thumbnail',
            MediaTypeEnum::IMAGE
        );

        self::create([
            'media_id' => $thumbnail->id,
            'title' => $request->title,
            'description' => $request->description ?? null,
            'is_active' => $isActive,
            'created_at' => now(),
        ]);
    }

    public static function updateByRequest($request, $isActive, $banner)
    {
        $thumbnail = $request->hasFile('thumbnail') ? MediaRepository::updateByRequest(
            $request->file('thumbnail'),
            $banner->media,
            'subscription-banner/thumbnail',
            MediaTypeEnum::IMAGE
        ) : $banner->media;

        return self::update($banner, [
            'media_id' => $thumbnail->id ?? $banner->media_id,
            'title' => $request->title ?? $banner->title,
            'description' => $request->description ?? $banner->description,
            'is_active' => $isActive,
            'updated_at' => now(),
        ]);
    }
}
