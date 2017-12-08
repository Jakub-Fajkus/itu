import {getJSON} from "./ajax";
import {forceJquery} from "./helpers";

const CLASS_HIDDEN = 'xxx';
const CLASS_SHOWN = 'yy';

export default class Modal {
    $wrapper = undefined;
    $container = undefined;

    lastLoaded = '';

    constructor(wrapper, closers, container) {
        this.$wrapper = forceJquery(wrapper);
        this.$container = forceJquery(container);
        this.__initCloser(closers);
    }

    __initCloser(closers)
    {
        forceJquery(closers).click(()=>{this.hide()});
    }

    hide()
    {
        this.$wrapper.removeClass(CLASS_SHOWN);
        this.$wrapper.addClass(CLASS_HIDDEN);
    }

    show()
    {
        this.$wrapper.removeClass(CLASS_HIDDEN);
        this.$wrapper.addClass(CLASS_SHOWN);
    }

    clear()
    {
        this.$container.empty();
    }

    setContent(newContent = this.lastLoaded)
    {
        this.clear();
        this.$container.append(newContent);
    }

    load(url)
    {
        getJSON(url).then((responseData) => {
            this.lastLoaded =responseData.html;
        });
    }
}