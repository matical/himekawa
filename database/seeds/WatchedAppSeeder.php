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
            'slug'           => 'deresute',
            'original_title' => 'アイドルマスター シンデレラガールズ スターライトステージ',
            'package_name'   => 'jp.co.bandainamcoent.BNEI0242',
        ]);

        WatchedApp::create([
            'name'           => 'Theater Days',
            'slug'           => 'mirishita',
            'original_title' => 'アイドルマスター ミリオンライブ！ シアターデイズ',
            'package_name'   => 'com.bandainamcoent.imas_millionlive_theaterdays',
        ]);

        WatchedApp::create([
            'name'           => 'Live On Stage',
            'slug'           => 'los',
            'original_title' => 'アイドルマスター SideM LIVE ON ST@GE！',
            'package_name'   => 'com.bandainamcoent.imas_SideM_LIVEONSTAGE',
        ]);

        WatchedApp::create([
            'name'           => 'Photokatsu',
            'slug'           => 'photokatsu',
            'original_title' => 'アイカツ！フォトonステージ！！',
            'package_name'   => 'com.bandainamcoent.aktposjp',
        ]);

        WatchedApp::create([
            'name'           => 'Girls Band Party',
            'slug'           => 'gbp',
            'original_title' => 'バンドリ！ ガールズバンドパーティ！',
            'package_name'   => 'jp.co.craftegg.band',
        ]);

        WatchedApp::create([
            'name'           => 'F/GO',
            'slug'           => 'fgo',
            'original_title' => 'Fate/Grand Order',
            'package_name'   => 'com.aniplex.fategrandorder',
        ]);

        WatchedApp::create([
            'name'           => 'Yuyuyi',
            'slug'           => 'yuyuyui',
            'original_title' => '結城友奈は勇者である 花結いのきらめき',
            'package_name'   => 'jp.co.altplus.yuyuyui',
        ]);
    }
}
