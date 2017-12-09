import {getJSON} from "./ajax";
import {forceJquery} from "./helpers";
import Form from '../libraries/Form'

const CLASS_HIDDEN = 'mw';
const CLASS_SHOWN = CLASS_HIDDEN + 'mw--active';

export default class Modal {
    $wrapper = undefined;
    $container = undefined;
    form = undefined;
    locked = false;
    lastLoaded = '';

    constructor(wrapper, closers, container) {
        this.$wrapper = forceJquery(wrapper);
        this.$container = forceJquery(container);
        this.__initCloser(closers);
    }

    __initCloser(closers) {
        forceJquery(closers).click(() => {
            this.hide()
        });
    }

    hide() {
        this.$wrapper.removeClass(CLASS_SHOWN);
        this.$wrapper.addClass(CLASS_HIDDEN);
    }

    show() {
        this.$wrapper.removeClass(CLASS_HIDDEN);
        this.$wrapper.addClass(CLASS_SHOWN);
    }

    clear() {
        this.$container.empty();
    }

    setContent(newContent = this.lastLoaded) {
        this.clear();
        this.$container.append(newContent);
    }

    load(url) {
        if (!this.locked) {
            this.locked = true;
            return getJSON(url).then((responseData) => {
                this.lastLoaded = responseData.html;
                this.locked = false;
                return responseData;
            });
        }
        let fake = {then: ()=>fake, catch:()=>fake};
        return fake;
    }

    loadFormNow(url)
    {
        debugger;
        return this.load(url).then(()=> {
            debugger;
            this.setContent();
            this.show();
            return this.initForm();
        });
    }

    initForm() {
        return this.form = new Form(this.$wrapper.find('form'), this);
    }
}