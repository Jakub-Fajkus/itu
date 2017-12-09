import BaseController from './BaseController';
import {replace} from "../libraries/helpers";

export default class DefaultController extends BaseController {
    indexAction() {
        this._initProjects();

        let modal = this.modal, dnd = (p) => this._initDND(p), ip = (p) => this._initProjects(p);
        $(this.scopeElements.addProject).click(
            function () {
                modal.loadFormNow(this.getAttribute('data-new-url')).then(
                    (form) => {
                        form.onSuccess(
                            (response) => {
                                let $new = $(response.html);
                                let $container = $('[data-sortgroup="projects"]');
                                if($container.length) {
                                    $container.children.first().after($new);
                                }
                                else {
                                    $container.append($new);
                                }
                                dnd($new);
                                ip($new);
                            }
                        );
                    }
                );
            }
        );
    }

    _initProjects($parent = $('body')) {

        let modal = this.modal, dnd = (p) => this._initDND(p), me = (p) => this._initProjects(p);

        $parent.find('[data-sortgroup="tasks"]').find('li').dblclick(edit);
        $parent.find('[data-handle="project"]').dblclick(edit);
        $parent.find('[data-multipleSelector="editProject"]').click(edit);


        $parent.find('[data-handle="project"]').find('[data-new-url]').click(function () {
            modal.loadFormNow(this.getAttribute('data-new-url')).then(
                (form) => {
                    let $project = $(this).closest('div[data-sort-name]'),
                        projectId = $project.attr('data-sort-name');
                    form.$formElement.find('#appbundle_task_project').val(`${projectId}`);

                    form.onSuccess(
                        (response) => {
                            let $new = $(response.html);
                            replace($project, $new);
                            dnd($new);
                            me($new);
                        }
                    );
                }
            );
        });


        function edit() {
            modal.loadFormNow(this.getAttribute('data-edit-url')).then(
                (form) => {
                    form.onSuccess(
                        (response) => {
                            let $new = $(response.html);
                            replace($(this).closest('div[data-sort-name]'), $new);
                            dnd($new);
                            me($new);
                        }
                    );
                }
            );
        }
    }
}