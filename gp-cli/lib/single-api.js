var GooglePlayAPI = require('gpapi').GooglePlayAPI;
var cfg = require('../single-cli');

var api = GooglePlayAPI(cfg);

module.exports = api;
