module.exports = function(api, pkg, vc) {
    return api
        .details(pkg)
        .then(res => vc || res.details.appDetails.versionCode)
        .then(versionCode => api.download(pkg, versionCode).then(res => res));
};
