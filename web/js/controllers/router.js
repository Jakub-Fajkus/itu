import controllers from './controllers';
import $ from 'jquery';

function start() {
    let controllerName = $('[data-controller="name"]').val(),
        actionName = $('[data-controller="action"]').val();
    if (controllerName && actionName) {
        console.log(`${controllerName}->${actionName}`);
        (new (controllers[controllerName])())[actionName]();
    } else {
        console.warn('NO CONTROLLER');
    }

}

const router = {start};
export default router;