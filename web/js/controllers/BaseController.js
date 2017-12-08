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

            let connect = el.getAttribute('data-sortGroup');
            if(connect)
            {
                definition.settings.connectWith = `[data-sortGroup="${connect}"]`;
            }

            $el.sortable({
                activate: () => {
                    el.backup = $el.children();
                },
                update: () => {
                    $el.sortable("option", {disabled: true});
                    let data = [];
                    _.each($el.children(), line => {
                        if (!$(line).hasClass('ui-sortable-placeholder')) {
                            data.push(line.getAttribute('data-sort-name'));
                            console.log(line);
                        }
                    });
                    console.log(data);
                    postJSON(definition.url, data)
                        .then(() => {
                            $el.sortable("option", {disabled: false});
                        })
                        .catch(
                            () => {
                                $el.empty();
                                _.each(el.backup, back => {
                                    if (!$(back).hasClass('ui-sortable-placeholder')) {
                                        $el.append(back)
                                    }
                                });
                                $el.sortable("option", {disabled: false});
                            }
                        );
                },

                ...definition.settings
            });
        });
    }


}