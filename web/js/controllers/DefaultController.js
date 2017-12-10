import BaseController from './BaseController';
import {forceJquery, replace} from "../libraries/helpers";
import {postJSON} from "../libraries/ajax";

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
                                $container.append($new);
                                dnd($new);
                                ip($new);
                            }
                        );
                    }
                );
            }
        );
        $(this.scopeElements.hideCompleted).click(
            function () {
                //todo @Risa
                postJSON(target.getAttribute('data-url'));
            }
        );
    }

    _initProjects($parent = $('body')) {

        let modal = this.modal, dnd = (p) => this._initDND(p), me = (p) => this._initProjects(p);

        $parent.find('[data-sortgroup="tasks"]').find('li').dblclick(edit);
        $parent.find('[data-handle="project"]').dblclick(edit);
        $parent.find('[data-multipleSelector="editProject"]').click(edit);

        $parent.find('[data-multipleSelector="completeCheck"]').change(
            ({target}) => {
                postJSON(target.getAttribute('data-url'), {completed: target.checked});

                let $line = $(target).closest('[data-sort-name]'), $parent = $line.parent();
                $line.detach();
                if (target.checked) {
                    $parent.append($line)
                } else {
                    $parent.prepend($line);
                }
            }
        );


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
                    modal.deleteForm.onSuccess(
                        () => {
                            $(this).closest('[data-sort-name]').remove();
                        }
                    );
                }
            );
        }
    }
}