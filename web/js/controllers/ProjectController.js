import BaseController from './BaseController';

export default class ProjectController extends BaseController{
    indexAction(){
        console.log(this.scopeElements);
    }
}