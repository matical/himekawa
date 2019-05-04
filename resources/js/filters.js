import Vue from 'vue';
import parseISO from 'date-fns/parseISO';
import format from 'date-fns/format';

Vue.filter('humanBytes', sizeInBytes => {
    const units = ['B', 'KiB', 'MiB', 'GiB', 'TiB'];

    if (sizeInBytes === 0) {
        return '0 ' + units[1];
    }

    for (var i = 0; sizeInBytes > 1024; i++) {
        sizeInBytes /= 1024;
    }

    return sizeInBytes.toFixed(2) + ' ' + units[i];
});

Vue.filter('prettyDate', date => {
    return format(parseISO(date), "MMM do, H:mm 'JST'");
});
