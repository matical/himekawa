import Vue from 'vue';
import toDate from "date-fns/toDate";
import format from "date-fns/format";
import {upperFirst} from "lodash-es";

Vue.filter('humanBytes', sizeInBytes => {
    const units = ["B", "KiB", "MiB", "GiB", "TiB"];

    if (sizeInBytes === 0) {
        return "0 " + units[1];
    }

    for (var i = 0; sizeInBytes > 1024; i ++) {
        sizeInBytes /= 1024;
    }

    return sizeInBytes.toFixed(2) + " " + units[i];
});

Vue.filter('prettyDate', date => {
    return format(toDate(date), "MMM do, H:mm 'JST'");
});

Vue.filter('upperFirst', lowercase => {

});
