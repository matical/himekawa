# General cross-region downloading tips

## Things you'll need
- [Dummydroid](http://www.onyxbits.de/dummydroid)
- JP region VPN

## Regions and New accounts
Google Play (henceforth referred to as GP) has a *roaming* feature when it comes to downloading region restricted apps. The original feature is meant for when you're travelling overseas.
 
This means you don't actually need to be on a JP VPN once you've downloaded it at least *once*. 

## build.prop
Besides region, GP filters (and subsequently prevent) apps based on your device capabilities. Dummydroid lets you register devices and spits out a GSF ID. This is how GP does its filtering.

The props you should usually take note of is your SDK version (Android version) and OpenGL ES version. 

Some relatively new games like Theater Days (ミリシタ) requires OpenGL ES >3.0. TD does actually support x86 platforms, but as of time of this writing, GLES >3 only exists on beta builds. 

If you're using the recommended prop file you should these already set.

```
ro.product.cpu.abilist=armeabi-v7a,armeabi
ro.build.version.sdk=27 // android 8.1
ro.opengles.version=196609 // gles 0x30001 aka 3.1
```
