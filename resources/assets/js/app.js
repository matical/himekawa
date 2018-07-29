import Vue from 'vue';
import {
    MdApp,
    MdIcon,
    MdList,
    MdButton,
    MdAvatar,
    MdDrawer,
    MdContent,
    MdToolbar,
    MdElevation,
} from 'vue-material/dist/components';

import yuki from './components/ShortLinks/Yuki';
import himekawa from './components/Index/Himekawa';
import mixins from './mixins';
import './filters';

Vue.use(MdApp);
Vue.use(MdIcon);
Vue.use(MdList);
Vue.use(MdDrawer);
Vue.use(MdButton);
Vue.use(MdAvatar);
Vue.use(MdToolbar);
Vue.use(MdContent);
Vue.use(MdElevation);

Vue.mixin({methods: mixins});

new Vue({
    el: '#app',
    data: {
        menuVisible: false
    },
    components: {
        himekawa,
        yuki
    },
    methods: {
        toggleMenu() {
            this.menuVisible = ! this.menuVisible
        }
    }
});
