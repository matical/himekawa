# General cross-region downloading tips

## Things you'll need
- [Dummydroid](http://www.onyxbits.de/dummydroid)
- JP region VPN

## Regions and New accounts
Google Play (henceforth referred to as GP) has a *roaming* feature when it comes to downloading region restricted apps. The original feature is meant for when you're travelling overseas.
 
This means you don't actually need to be on a JP VPN once you've downloaded it at least *once*. 

## build.prop
Besides region, GP filters (and subsequently prevents) app installs based on your identified GSF ID, which is tied to your device capabilities. Dummydroid lets you register a custom device and spits out a GSF ID.

For games, just take note of is your SDK version (Android version) and OpenGL ES version. GP doesn't really care too much about the specifics as long as it isn't too ridiculous.

Some relatively new games like Theater Days (ミリシタ) requires OpenGL ES >3.0. TD does actually support x86 platforms, but as of time of this writing, GLES >3 only exists on beta builds. 

If you're using the recommended prop file you should these already set.

```
ro.product.cpu.abilist=armeabi-v7a,armeabi
ro.build.version.sdk=27 // android 8.1
ro.opengles.version=196609 // gles 0x30001 aka 3.1
```
