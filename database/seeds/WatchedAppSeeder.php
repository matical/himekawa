<?php

use himekawa\WatchedApp;
use Illuminate\Database\Seeder;

class WatchedAppSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        WatchedApp::create([
            'name'           => 'Starlight Stage',
            'original_title' => 'アイドルマスター シンデレラガールズ スターライトステージ',
            'package_name'   => 'jp.co.bandainamcoent.BNEI0242',
        ]);

        WatchedApp::create([
            'name'           => 'Theater Days',
            'original_title' => 'アイドルマスター ミリオンライブ！ シアターデイズ',
            'package_name'   => 'com.bandainamcoent.imas_millionlive_theaterdays',
        ]);

        WatchedApp::create([
            'name'           => 'Live On Stage',
            'original_title' => 'アイドルマスター SideM LIVE ON ST@GE！',
            'package_name'   => 'com.bandainamcoent.imas_SideM_LIVEONSTAGE',
        ]);

        WatchedApp::create([
            'name'           => 'Photokatsu',
            'original_title' => 'アイカツ！フォトonステージ！！',
            'package_name'   => 'com.bandainamcoent.aktposjp',
        ]);

        WatchedApp::create([
            'name'           => 'Girls Band Party',
            'original_title' => 'バンドリ！ ガールズバンドパーティ！',
            'package_name'   => 'jp.co.craftegg.band',
        ]);

        WatchedApp::create([
            'name'           => 'F/GO',
            'original_title' => 'Fate/Grand Order',
            'package_name'   => 'com.aniplex.fategrandorder',
        ]);

        WatchedApp::create([
            'name'           => 'Magia Record',
            'original_title' => 'マギアレコード 魔法少女まどかマギカ外伝',
            'package_name'   => 'com.aniplex.magireco',
        ]);
    }
}
