import _ from 'lodash';
import {postJSON, getJSON} from '../libraries/ajax';

export default class BaseController {
    scopeElements = {};

    constructor() {
        _.each($('[data-selector]'), el => this.scopeElements[el.getAttribute('data-selector')] = el);
        _.each($('[data-multipleSelector]'), el => {
            let key = el.getAttribute('data-multipleSelector');
            if (!this.scopeElements[key]) {
                this.scopeElements[key] = [];
            }
            this.scopeElements[key].push(el);
        });


        _.each($('[data-sortable]'), el => {
            let $el = $(el);
            let definition = JSON.parse(el.getAttribute('data-sortable'));
            $el.sortable({
                activate: () => {
                    el.backup = $el.children();
                },
                update: ()  =>{
                    let data = [];
                    _.each($el.children(), line => {
                        if(!$(line).hasClass('ui-sortable-placeholder'))
                        {
                            data.push(line.getAttribute('data-sort-name'));
                        }
                    });
                    postJSON(definition.url, data).catch(
                        () => {
                            $el.empty();
                            _.each(el.backup, back => {
                                if(!$(back).hasClass('ui-sortable-placeholder'))
                                {
                                    $el.append(back)
                                }
                            });
                        }
                    );
                },
                ... definition.settings
            });
        });
    }


}