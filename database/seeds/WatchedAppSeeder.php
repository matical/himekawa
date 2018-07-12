<?php

use himekawa\WatchedApp;
use Illuminate\Database\Seeder;

class WatchedAppSeeder extends Seeder
{
    protected static $apps = [
        [
            'name'           => 'Starlight Stage',
            'slug'           => 'deresute',
            'original_title' => 'アイドルマスター シンデレラガールズ スターライトステージ',
            'package_name'   => 'jp.co.bandainamcoent.BNEI0242',
        ],
        [
            'name'           => 'Theater Days',
            'slug'           => 'mirishita',
            'original_title' => 'アイドルマスター ミリオンライブ！ シアターデイズ',
            'package_name'   => 'com.bandainamcoent.imas_millionlive_theaterdays',
        ],
        [
            'name'           => 'Live On Stage',
            'slug'           => 'los',
            'original_title' => 'アイドルマスター SideM LIVE ON ST@GE！',
            'package_name'   => 'com.bandainamcoent.imas_SideM_LIVEONSTAGE',
        ],
        [
            'name'           => 'Photokatsu',
            'slug'           => 'photokatsu',
            'original_title' => 'アイカツ！フォトonステージ！！',
            'package_name'   => 'com.bandainamcoent.aktposjp',
        ],
        [
            'name'           => 'Girls Band Party',
            'slug'           => 'gbp',
            'original_title' => 'バンドリ！ ガールズバンドパーティ！',
            'package_name'   => 'jp.co.craftegg.band',
        ],
        [
            'name'           => 'F/GO',
            'slug'           => 'fgo',
            'original_title' => 'Fate/Grand Order',
            'package_name'   => 'com.aniplex.fategrandorder',
        ],
        [
            'name'           => 'Yuyuyi',
            'slug'           => 'yuyuyui',
            'original_title' => '結城友奈は勇者である 花結いのきらめき',
            'package_name'   => 'jp.co.altplus.yuyuyui',
        ],
        [
            'name'           => 'Azur Lane',
            'slug'           => 'azur',
            'original_title' => 'アズールレーン',
            'package_name'   => 'com.YoStarJP.AzurLane',
        ],
        [
            'name'           => 'Kemono Friends Pavilion',
            'slug'           => 'kemono',
            'original_title' => 'けものフレンズぱびりおん',
            'package_name'   => 'com.bushiroad.kemofure',
        ],
        [
            'name'           => 'Kirara Fantasia',
            'slug'           => 'fantasia',
            'original_title' => 'きららファンタジア',
            'package_name'   => 'com.aniplex.kirarafantasia',
        ],
        [
            'name'           => 'Tokyo 7th Sisters',
            'slug'           => 't7s',
            'original_title' => 'Tokyo 7th シスターズ - アイドル育成＆本格音ゲー',
            'package_name'   => 'jp.ne.donuts.t7s',
        ],
        [
            'name'           => 'Princess Connect Re:Dive',
            'slug'           => 'priconne',
            'original_title' => 'プリンセスコネクト！Re:Dive',
            'package_name'   => 'jp.co.cygames.princessconnectredive',
        ],
    ];

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (static::$apps as $app) {
            WatchedApp::create($app);
        }
    }
}
