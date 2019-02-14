<?php
/**
 * Created by PhpStorm.
 * User: huzairuje
 * Date: 1/20/19
 * Time: 5:12 AM
 */

namespace App\Transformers\NearbyLocation;


use App\Models\Activity;
use App\Models\Community;
use App\Models\Event;
use App\Models\User;
use App\Services\Mobile\NearbyLocation\SubmitUserLocationService;

class AllModuleNearbyLocationTransformer
{
    protected $userModel;
    protected $communityModel;

    public function __construct()
    {
        $this->userModel = new User();
        $this->communityModel = new Community();
    }

    public function transformActivity(Activity $activity) {
        return [
            'nearby_module' => $activity->name,
            'collections' => [
                [
                    'type' => $activity->name,
                    'geometry' => [
                        'coordinates' => [
                            $activity->lat, $activity->lon
                        ]
                    ],
                    'properties' => [
                        'id' => $activity->id,
                        'name' => $activity->name,
                        'description' => $activity->name,
                        'is_public' => $activity->is_public,
                        'tag' => $activity->tag,
                        'created_by' => $activity->created_by,
                    ]
                ]
            ]
        ];
    }

    public function transformEvent(Event $event) {
        return [
            'nearby_module' => $event->name,
            'collections' => [
                [
                    'type' => $event->name,
                    'geometry' => [
                        'coordinates' => [
                            $event->lat, $event->lon
                        ]
                    ],
                    'properties' => [
                        'id' => $event->id,
                        'name' => $event->name,
                        'description' => $event->name,
                        'is_public' => $event->is_public,
                        'tag' => $event->tag,
                        'created_by' => $event->created_by,
                    ]
                ]
            ]
        ];
    }

    public function transformCommunity(Community $community) {

        return [
            'nearby_module' => $community->id,
            'collections' => [
                [
                    'type' => $community,
                    'geometry' => [
                        'coordinates' => [
                            $community, $community
                        ]
                    ],
                    'properties' => [
                        'id' => $community,
                        'name' => $community,
                        'description' => $community,
                        'is_public' => $community,
                        'tag' => $community,
                        'created_by' => $community,
                    ]
                ]
            ]
        ];
    }

//    public function transformAllModule(User $user, Activity $activity, Community $community, Event $event)
    public function transformAllModule(Activity $activity)
    {
        return [
            'nearby_module' => 'all_module',
            'collections' => [
                [
                    'type' => 'activity',
                    'geometry' => [
                        'type' => 'point',
                        'coordinates' => [
                            $activity->lat, $activity->lon
                        ],
                    ],
                    'properties' => [
                        'id' => $activity->id,
                        'name' => $activity->name,
                        'description' => $activity->name,
                        'is_public' => $activity->is_public,
                        'tag' => $activity->tag,
                        'created_by' => $activity->created_by,
                    ],
                ],
                [
                    'type' => 'community',
                    'geometry' => [
                        'type' => 'point',
                        'coordinates' => [
                            $activity->lat, $activity->lon
                        ],
                    ],
                    'properties' => [
                        'id' => $activity->id,
                        'name' => $activity->name,
                        'description' => $activity->name,
                        'is_public' => $activity->is_public,
                        'tag' => $activity->tag,
                        'created_by' => $activity->created_by,
                    ],
                ],
                [
                    'type' => 'event',
                    'geometry' => [
                        'type' => 'point',
                        'coordinates' => [
                            $activity->lat, $activity->lon
                        ],
                    ],
                    'properties' => [
                        'id' => $activity->id,
                        'name' => $activity->name,
                        'description' => $activity->name,
                        'is_public' => $activity->is_public,
                        'tag' => $activity->tag,
                        'created_by' => $activity->created_by,
                    ],
                ]

            ],
        ];
    }

}
