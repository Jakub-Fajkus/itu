import BaseController from './BaseController';

export default class DefaultController extends BaseController{
    indexAction(){
        console.log(this.scopeElements);
    }
}