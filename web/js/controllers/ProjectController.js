import BaseController from './BaseController';

export default class ProjectController extends BaseController{
    indexAction(){
        console.log(this.scopeElements);
    }

    newAction(){
        console.log(this.scopeElements);
    }

    showAction(){

    }
    editAction(){

    }
}