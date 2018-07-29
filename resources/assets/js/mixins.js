import toDate from "date-fns/toDate";
import {upperFirst} from "lodash-es";
import fuzzyDiff from "date-fns/formatDistance";
import differenceInDays from "date-fns/differenceInDays";

export default {
    now() {
        return new Date()
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
