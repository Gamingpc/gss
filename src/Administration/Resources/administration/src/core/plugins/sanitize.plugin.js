import { warn } from 'src/core/service/utils/debug.utils';
import Sanitizer from 'src/core/helper/sanitizer.helper';

let pluginInstalled = false;

export default {
    install(Vue) {
        if (pluginInstalled) {
            warn('Sanitize Plugin', 'This plugin is already installed');
            return false;
        }

        Vue.prototype.$sanitizer = Sanitizer;
        Vue.prototype.$sanitize = Sanitizer.sanitize;

        pluginInstalled = true;

        return true;
    }
};
