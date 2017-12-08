import _ from 'lodash';
import {postJSON} from '../libraries/ajax';
import Modal from '../libraries/Modal';
import {flashMessageInit} from "../libraries/helpers";

export default class BaseController {
    scopeElements = {};
    modal = undefined;
    __addFlashMessage = () => {};

    constructor() {
        this.__initSelectors();
        this.__initDND();
        this.__initFlash();
        this.__initModal();
        window.flash = (a,b) => {this._addFlashMessage(a,b)};
    }

    __initFlash()
    {
        if(this.scopeElements.flashMsgWrapper) {
            this.__addFlashMessage = flashMessageInit(this.scopeElements.flashMsgWrapper);
        }
    }

    __initModal()
    {
        if(this.scopeElements.modalWrapper && this.scopeElements.modalClosers && this.scopeElements.modalContainer) {
            this.modal = new Modal(
                this.scopeElements.modalWrapper,
                this.scopeElements.modalClosers,
                this.scopeElements.modalContainer
            )
        }
    }

    _addFlashMessage(...attr)
    {
        this.__addFlashMessage(...attr);
    }

    __initSelectors()
    {
        _.each($('[data-selector]'), el => this.scopeElements[el.getAttribute('data-selector')] = el);
        _.each($('[data-multipleSelector]'), el => {
            let key = el.getAttribute('data-multipleSelector');
            if (!this.scopeElements[key]) {
                this.scopeElements[key] = [];
            }
            this.scopeElements[key].push(el);
        });
    }

    __initDND() //todo add HANDLE
    {
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
                        }
                    });
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