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

        WatchedApp::create([
            'name'           => 'Azur Lane',
            'slug'           => 'azur',
            'original_title' => 'アズールレーン',
            'package_name'   => 'com.YoStarJP.AzurLane',
        ]);

        WatchedApp::create([
            'name'           => 'Kemono Friends Pavilion',
            'slug'           => 'kemono',
            'original_title' => 'けものフレンズぱびりおん',
            'package_name'   => 'com.bushiroad.kemofure',
        ]);

        WatchedApp::create([
            'name'           => 'Kirara Fantasia',
            'slug'           => 'fantasia',
            'original_title' => 'きららファンタジア',
            'package_name'   => 'com.aniplex.kirarafantasia',
        ]);

        WatchedApp::create([
            'name'           => 'Tokyo 7th Sisters',
            'slug'           => 't7s',
            'original_title' => 'Tokyo 7th シスターズ - アイドル育成＆本格音ゲー',
            'package_name'   => 'jp.ne.donuts.t7s',
        ]);

        WatchedApp::create([
            'name'           => 'Princess Connect Re:Dive',
            'slug'           => 'priconne',
            'original_title' => 'プリンセスコネクト！Re:Dive',
            'package_name'   => 'jp.co.cygames.princessconnectredive',
        ]);
    }
}
