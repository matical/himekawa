import parseISO from 'date-fns/parseISO';
import { upperFirst } from 'lodash-es';
import fuzzyDiff from 'date-fns/formatDistance';
import differenceInDays from 'date-fns/differenceInDays';

export default {
    now() {
        return new Date();
    },
    diffDate(iso) {
        return upperFirst(
            fuzzyDiff(parseISO(iso), this.now(), {
                addSuffix: true,
            })
        );
    },
    diffInDays(from) {
        return differenceInDays(this.now(), from);
    },
    isRecent(iso) {
        return this.diffInDays(parseISO(iso)) < 3;
    },
};
