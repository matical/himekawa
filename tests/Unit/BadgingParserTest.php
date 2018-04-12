<?php

namespace Tests\Unit;

use Tests\TestCase;
use yuki\Badging\Parser;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BadgingParserTest extends TestCase
{
    protected $rawBadging = <<<EOF
package: name='jp.co.bandainamcoent.BNEI0242' versionCode='128' versionName='3.8.4' platformBuildVersionName='8.0.0'
install-location:'preferExternal'
uses-permission: name='android.permission.INTERNET'
uses-permission: name='android.permission.WRITE_EXTERNAL_STORAGE'
uses-permission: name='com.android.vending.BILLING'
uses-permission: name='android.permission.ACCESS_NETWORK_STATE'
uses-permission: name='android.permission.READ_EXTERNAL_STORAGE'
uses-permission: name='android.permission.DISABLE_KEYGUARD'
uses-permission: name='android.permission.WAKE_LOCK'
uses-permission: name='android.permission.GET_TASKS'
uses-permission: name='android.permission.VIBRATE'
sdkVersion:'14'
targetSdkVersion:'24'
application-label:'デレステ'
application-label-ca:'デレステ'
application-label-da:'デレステ'
application-label-fa:'デレステ'
application-label-ja:'デレステ'
application-label-nb:'デレステ'
application-label-de:'デレステ'
application-label-af:'デレステ'
application-label-bg:'デレステ'
application-label-th:'デレステ'
application-label-fi:'デレステ'
application-label-hi:'デレステ'
application-label-vi:'デレステ'
application-label-sk:'デレステ'
application-label-uk:'デレステ'
application-label-el:'デレステ'
application-label-nl:'デレステ'
application-label-pl:'デレステ'
application-label-sl:'デレステ'
application-label-tl:'デレステ'
application-label-am:'デレステ'
application-label-in:'デレステ'
application-label-ko:'デレステ'
application-label-ro:'デレステ'
application-label-ar:'デレステ'
application-label-fr:'デレステ'
application-label-hr:'デレステ'
application-label-sr:'デレステ'
application-label-tr:'デレステ'
application-label-cs:'デレステ'
application-label-es:'デレステ'
application-label-it:'デレステ'
application-label-lt:'デレステ'
application-label-pt:'デレステ'
application-label-hu:'デレステ'
application-label-ru:'デレステ'
application-label-zu:'デレステ'
application-label-lv:'デレステ'
application-label-sv:'デレステ'
application-label-iw:'デレステ'
application-label-sw:'デレステ'
application-label-fr-CA:'デレステ'
application-label-lo-LA:'デレステ'
application-label-en-GB:'デレステ'
application-label-bn-BD:'デレステ'
application-label-et-EE:'デレステ'
application-label-ka-GE:'デレステ'
application-label-ky-KG:'デレステ'
application-label-km-KH:'デレステ'
application-label-zh-HK:'デレステ'
application-label-si-LK:'デレステ'
application-label-mk-MK:'デレステ'
application-label-ur-PK:'デレステ'
application-label-sq-AL:'デレステ'
application-label-hy-AM:'デレステ'
application-label-my-MM:'デレステ'
application-label-zh-CN:'デレステ'
application-label-pa-IN:'デレステ'
application-label-ta-IN:'デレステ'
application-label-te-IN:'デレステ'
application-label-ml-IN:'デレステ'
application-label-en-IN:'デレステ'
application-label-kn-IN:'デレステ'
application-label-mr-IN:'デレステ'
application-label-gu-IN:'デレステ'
application-label-mn-MN:'デレステ'
application-label-ne-NP:'デレステ'
application-label-pt-BR:'デレステ'
application-label-gl-ES:'デレステ'
application-label-eu-ES:'デレステ'
application-label-is-IS:'デレステ'
application-label-es-US:'デレステ'
application-label-pt-PT:'デレステ'
application-label-en-AU:'デレステ'
application-label-zh-TW:'デレステ'
application-label-ms-MY:'デレステ'
application-label-az-AZ:'デレステ'
application-label-kk-KZ:'デレステ'
application-label-uz-UZ:'デレステ'
application-icon-120:'res/drawable-ldpi-v4/app_icon.png'
application-icon-160:'res/drawable-mdpi-v4/app_icon.png'
application-icon-213:'res/drawable-hdpi-v4/app_icon.png'
application-icon-240:'res/drawable-hdpi-v4/app_icon.png'
application-icon-320:'res/drawable-xhdpi-v4/app_icon.png'
application-icon-480:'res/drawable-xxhdpi-v4/app_icon.png'
application-icon-640:'res/drawable-xxxhdpi-v4/app_icon.png'
application: label='デレステ' icon='res/drawable-mdpi-v4/app_icon.png'
application-isGame
launchable-activity: name='com.unity3d.player.UnityPlayerNativeActivity'  label='デレステ' icon=''
leanback-launchable-activity: name='com.unity3d.player.UnityPlayerNativeActivity'  label='デレステ' icon='' banner=''
uses-permission: name='jp.co.bandainamcoent.BNEI0242.permission.C2D_MESSAGE'
uses-permission: name='com.google.android.c2dm.permission.RECEIVE'
uses-permission: name='android.permission.USE_CREDENTIALS'
feature-group: label=''
  uses-gl-es: '0x20000'
  uses-feature-not-required: name='android.hardware.sensor.accelerometer'
  uses-feature-not-required: name='android.hardware.touchscreen'
  uses-feature-not-required: name='android.hardware.touchscreen.multitouch'
  uses-feature-not-required: name='android.hardware.touchscreen.multitouch.distinct'
  uses-feature: name='android.hardware.screen.landscape'
  uses-implied-feature: name='android.hardware.screen.landscape' reason='one or more activities have specified a landscape orientation'
main
other-activities
other-receivers
other-services
supports-screens: 'small' 'normal' 'large' 'xlarge'
supports-any-density: 'true'
locales: '--_--' 'ca' 'da' 'fa' 'ja' 'nb' 'de' 'af' 'bg' 'th' 'fi' 'hi' 'vi' 'sk' 'uk' 'el' 'nl' 'pl' 'sl' 'tl' 'am' 'in' 'ko' 'ro' 'ar' 'fr' 'hr' 'sr' 'tr' 'cs' 'es' 'it' 'lt' 'pt' 'hu' 'ru' 'zu' 'lv' 'sv' 'iw' 'sw' 'fr-CA' 'lo-LA' 'en-GB' 'bn-BD' 'et-EE' 'ka-GE' 'ky-KG' 'km-KH' 'zh-HK' 'si-LK' 'mk-MK' 'ur-PK' 'sq-AL' 'hy-AM' 'my-MM' 'zh-CN' 'pa-IN' 'ta-IN' 'te-IN' 'ml-IN' 'en-IN' 'kn-IN' 'mr-IN' 'gu-IN' 'mn-MN' 'ne-NP' 'pt-BR' 'gl-ES' 'eu-ES' 'is-IS' 'es-US' 'pt-PT' 'en-AU' 'zh-TW' 'ms-MY' 'az-AZ' 'kk-KZ' 'uz-UZ'
densities: '120' '160' '213' '240' '320' '480' '640'
native-code: 'armeabi-v7a' 'x86'
EOF;

    /**
     * @return void
     */
    public function testParser()
    {
        $expected = [
            'name'                     => 'jp.co.bandainamcoent.BNEI0242',
            'versionCode'              => 128,
            'versionName'              => '3.8.4',
            'platformBuildVersionName' => '8.0.0',
        ];

        $parser = new Parser();
        $this->assertSame($expected, $parser->parse($this->rawBadging));
    }
}
