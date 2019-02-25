<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 1/18/19
 * Time: 12:20 AM
 */

namespace App\Services\Mobile\NearbyLocation;


use App\Http\Requests\NearbyLocation\SubmitNearbyLocationRequest;
use App\Models\Activity;
use App\Models\Community;
use App\Models\Event;
use App\Models\User;
use App\Transformers\NearbyLocation\AllModuleNearbyLocationTransformer;
use App\Transformers\NearbyLocation\UserLocationTransformer;
use Illuminate\Support\Facades\DB;

class SubmitUserLocationService
{
    protected $userModel;
    protected $communitiesModel;
    protected $activitiesModel;
    protected $eventsModel;
    protected $allModuleNearbyLocationTransformer;
    protected $userLocationTransformer;

    public function __construct(User $userModel,
                                Community $communitiesModel,
                                Activity $activitiesModel,
                                Event $eventsModel,
                                AllModuleNearbyLocationTransformer $allModuleNearbyLocationTransformer,
                                UserLocationTransformer $userLocationTransformer)
    {
        $this->userModel = $userModel;
        $this->communitiesModel = $communitiesModel;
        $this->activitiesModel = $activitiesModel;
        $this->eventsModel = $eventsModel;
        $this->allModuleNearbyLocationTransformer = $allModuleNearbyLocationTransformer;
        $this->userLocationTransformer = $userLocationTransformer;
    }

    /**this method calculate user Location and what's on nearby
     * DON'T TOUCH IF NECESSARY, THIS IS THE CORE OF NEARBY DEFAULT
     * IF NEEDED TO ADD ANOTHER MODULE ASK huzairuje.
     * 6371 is unit on KM
     * 3959 is unit on Miles
     * see Haversine Formula https://en.wikipedia.org/wiki/Haversine_formula
     * or this implementation Store Locator on Google Maps Platform
     * https://developers.google.com/maps/solutions/store-locator/clothing-store-locator
     * @param SubmitNearbyLocationRequest $request
     * @return $queryEvent
     */
    public function getNearbyUser(SubmitNearbyLocationRequest $request)
    {
        $dataLat = (float)$request->input(['lat']);
        $dataLon = (float)$request->input(['lon']);
        $dataDistance = (float)$request->input(['distance']);

        $queryCommunity = $this->communitiesModel->select(
            'type',
            'id',
            'name',
            'description',
            'tag',
            'lat',
            'lon',
            DB::raw('(6371 * acos( cos( radians('.$dataLat.') ) * cos( radians( lat ) ) * cos( radians( lon )
                 - radians('.$dataLon.') ) + sin( radians('.$dataLat.') ) * sin( radians( lat ) ) ) ) as distance'))
            ->whereRaw('(6371 * acos( cos( radians('.$dataLat.') ) * cos( radians( lat ) ) * cos( radians( lon )
                 - radians('.$dataLon.') ) + sin( radians('.$dataLat.') ) * sin( radians( lat ) ) ) ) < '.$dataDistance.'');

        $queryActivity = $this->activitiesModel->select(
            'type',
            'id',
            'name',
            'description',
            'tag',
            'lat',
            'lon',
            DB::raw('(6371 * acos( cos( radians('.$dataLat.') ) * cos( radians( lat ) ) * cos( radians( lon )
                 - radians('.$dataLon.') ) + sin( radians('.$dataLat.') ) * sin( radians( lat ) ) ) ) as distance'))
            ->whereRaw('(6371 * acos( cos( radians('.$dataLat.') ) * cos( radians( lat ) ) * cos( radians( lon )
                 - radians('.$dataLon.') ) + sin( radians('.$dataLat.') ) * sin( radians( lat ) ) ) ) < '.$dataDistance.'')
            ->unionAll($queryCommunity);

        $queryEvent = $this->eventsModel->select(
            'type',
            'id',
            'name',
            'description',
            'tag',
            'lat',
            'lon',
            DB::raw('(6371 * acos( cos( radians('.$dataLat.') ) * cos( radians( lat ) ) * cos( radians( lon )
                 - radians('.$dataLon.') ) + sin( radians('.$dataLat.') ) * sin( radians( lat ) ) ) ) as distance'))
            ->whereRaw('(6371 * acos( cos( radians('.$dataLat.') ) * cos( radians( lat ) ) * cos( radians( lon )
                 - radians('.$dataLon.') ) + sin( radians('.$dataLat.') ) * sin( radians( lat ) ) ) ) < '.$dataDistance.'')
            ->unionAll($queryActivity);

        return $queryEvent
            ->orderBy('distance', 'asc');

    }

}
