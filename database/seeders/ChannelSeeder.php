<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ChannelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $channels = [
            [
                'name' => 'France 24',
                'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/8/8a/France24.svg/200px-France24.svg.png',
                'country' => 'France',
                'category' => 'News',
                'stream_url' => 'https://cdn.france24.com/live/F24_FR_HI_HLS/live_web.m3u8',
                'is_active' => true,
            ],
            [
                'name' => 'Euronews',
                'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/d/d7/Euronews_2016_logo.svg/200px-Euronews_2016_logo.svg.png',
                'country' => 'International',
                'category' => 'News',
                'stream_url' => 'https://euronews-euronews-world-1-fr.samsung.wurl.tv/playlist.m3u8',
                'is_active' => true,
            ],
            [
                'name' => 'Al Jazeera English',
                'logo' => 'https://upload.wikimedia.org/wikipedia/en/thumb/f/f2/Aljazeera_eng.svg/200px-Aljazeera_eng.svg.png',
                'country' => 'Qatar',
                'category' => 'News',
                'stream_url' => 'https://live-hls-web-aje.getaj.net/AJE/index.m3u8',
                'is_active' => true,
            ],
            [
                'name' => 'Red Bull TV',
                'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/3/35/Red_Bull_TV_logo.svg/200px-Red_Bull_TV_logo.svg.png',
                'country' => 'Austria',
                'category' => 'Sports',
                'stream_url' => 'https://rbmn-live.akamaized.net/hls/live/590964/BoRB-AT/master.m3u8',
                'is_active' => true,
            ],
            [
                'name' => 'NASA TV',
                'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/e/e5/NASA_logo.svg/200px-NASA_logo.svg.png',
                'country' => 'USA',
                'category' => 'Science',
                'stream_url' => 'https://ntv1.akamaized.net/hls/live/2014075/NASA-NTV1-HLS/master.m3u8',
                'is_active' => true,
            ],
            [
                'name' => 'Bloomberg TV',
                'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/56/Bloomberg_Television_logo.svg/200px-Bloomberg_Television_logo.svg.png',
                'country' => 'USA',
                'category' => 'Business',
                'stream_url' => 'https://bloomberg-bloombergtv-1-fr.samsung.wurl.tv/playlist.m3u8',
                'is_active' => true,
            ],
            [
                'name' => 'Fashion TV',
                'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/7/7a/Fashion_TV_logo.svg/200px-Fashion_TV_logo.svg.png',
                'country' => 'France',
                'category' => 'Lifestyle',
                'stream_url' => 'https://fashiontv-fashiontv-1-eu.rakuten.wurl.tv/playlist.m3u8',
                'is_active' => true,
            ],
            [
                'name' => 'Tastemade',
                'logo' => 'https://upload.wikimedia.org/wikipedia/commons/thumb/0/0a/Tastemade_logo.svg/200px-Tastemade_logo.svg.png',
                'country' => 'USA',
                'category' => 'Food',
                'stream_url' => 'https://tastemade-freetv16min-samsung-fr.amagi.tv/playlist.m3u8',
                'is_active' => true,
            ],
        ];

        foreach ($channels as $channel) {
            DB::table('channels')->insert([
                'name' => $channel['name'],
                'logo' => $channel['logo'],
                'country' => $channel['country'],
                'category' => $channel['category'],
                'stream_url' => $channel['stream_url'],
                'is_active' => $channel['is_active'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
