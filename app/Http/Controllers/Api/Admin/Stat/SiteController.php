<?php


namespace App\Http\Controllers\Api\Admin\Stat;
use App\Http\Controllers\Controller;
use App\Http\Resources\Api\SiteResource;
use App\Models\Site;


class SiteController extends Controller
{
    public function index(){
        $sites = Site::query()
            ->withCount('subscribers')
            ->withCount('unsubscribers')
            ->get();
        SiteResource::withoutWrapping();
        return SiteResource::collection($sites);
    }

    public function show(Site $site){
        $site = Site::where('id', $site->id)
            ->withCount('subscribers')
            ->withCount('unsubscribers')
            ->first();
        return new SiteResource($site);
    }
}
