import BaseController from './BaseController';

export default class TaskController extends BaseController{
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