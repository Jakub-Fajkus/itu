const FLASH_MSG_CLS_PREFIX = 'fl__msh fl__msh--';
const CLASS_SHOWN = 'fl__wrap';
const CLASS_HIDDEN = CLASS_SHOWN + 'hidden';

//todo ad datepicker

//div fl__wrap
////p fl__msh

export function forceJquery(el) {
    return (el instanceof $)? el : $(el);
}

export function flashMessageInit($wrap) {
    $wrap = forceJquery($wrap);
    return (text, status = 'success') => {
        let $content = $(`<p class="${FLASH_MSG_CLS_PREFIX+status}">${text}</p>`);
        $wrap.append($content);
        setTimeout(() => {
            $content.remove();
        }, 10*1000);
    }
}