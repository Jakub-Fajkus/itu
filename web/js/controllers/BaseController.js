import $ from 'jquery';
import _ from 'lodash';

export default class BaseController {
    scopeElements = {};

    constructor() {
        let selectors = $('[data-selector]'),
            multipleSelectors = $('[data-multipleSelector]');
        _.each(selectors, el => this.scopeElements[el.getAttribute('data-selector')] = el);
        _.each(multipleSelectors, el => {
            let key = el.getAttribute('data-multipleSelector');
            if (!this.scopeElements[key]) {
                this.scopeElements[key] = [];
            }
            this.scopeElements[key].push(el);
        });
    }


}