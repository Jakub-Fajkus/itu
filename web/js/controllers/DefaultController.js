import BaseController from './BaseController';
import Form from '../libraries/Form';

export default class DefaultController extends BaseController{
    indexAction(){
        console.log(this.scopeElements);
    }

    sortableExampleAction() {/*cokoliv*/}

    formExampleAction()
    {
        new Form($('[name="appbundle_task"]')[0]);
    }
}
// přidat handle přidat dvojklik