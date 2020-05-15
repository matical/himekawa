'use strict';
const decamelize = require('decamelize');

const USER_AGENT =
    'Android-Finsky/19.1.58-all ' +
    '(versionCode=81915800,sdk=28,device=ASUS_X00QD,' +
    'hardware=qcom,product=ZE620KL,' +
    'buildId=PPR1.180610.009:user)';

const OLD_USER_AGENT =
    'Android-Finsky/6.8.44.F-all%20%5B0%5D%203087104 ' +
    '(api=3,versionCode=80684400,sdk=23,device=bullhead,' +
    'hardware=bullhead,product=bullhead,platformVersionRelease=6.0.1,' +
    'model=Nexus%205X,buildId=MHC19Q,isWideScreen=0)';

const DOWNLOAD_MANAGER_USER_AGENT =
    'AndroidDownloadManager/9.0.1 (Linux; U; Android 9.0.1; ASUS_X00QD Build/PPR1.180610.009)';

const OLD_DOWNLOAD_MANAGER_UA =
    'AndroidDownloadManager/6.0.1 (Linux; U; Android 6.0.1; Nexus 5X Build/MHC19Q)';

const defaults = {
    username: process.env.GOOGLE_LOGIN,
    password: process.env.GOOGLE_PASSWORD,
    androidId: process.env.ANDROID_ID,
    authToken: process.env.GOOGLE_AUTHTOKEN,
    countryCode: 'jp',
    language: 'ja_JP',
    useCache: false,
    debug: process.env.DEBUG,
    apiUserAgent: USER_AGENT,
    downloadUserAgent: DOWNLOAD_MANAGER_USER_AGENT,
    sdkVersion: 28,
};

const alias = Object.keys(defaults).reduce((a, k) => {
    a[decamelize(k, '-')] = k;
    return a;
}, {});

module.exports = require('rc')(
    'gpcli',
    defaults,
    require('minimist')(process.argv, {
        alias: alias,
    })
);

if (!module.parent) {
    console.log(module.exports);
}
