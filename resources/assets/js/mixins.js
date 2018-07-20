import toDate from "date-fns/toDate";
import {upperFirst} from "lodash-es";
import format from "date-fns/format";
import fuzzyDiff from "date-fns/formatDistance";
import differenceInDays from "date-fns/differenceInDays";

export default {
    now() {
        return new Date()
    },
    formatPrettyDate(iso) {
        return format(toDate(iso), "MMM do, H:mm 'JST'");
    },
    diffDate(iso) {
        return upperFirst(
            fuzzyDiff(toDate(iso), this.now(), {
                addSuffix: true
            })
        );
    },
    diffInDays(from) {
        return differenceInDays(this.now(), from);
    },
    isRecent(iso) {
        return this.diffInDays(toDate(iso)) < 3;
    },
}
