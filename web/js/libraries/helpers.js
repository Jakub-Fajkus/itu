const FLASH_MSG_CLS_PREFIX = 'fl__msg fl__msg--';
const CLASS_HIDDEN = 'hidden';

export function forceJquery(el) {
    return (el instanceof $)? el : $(el);
}

let $wrap = $('[data-selector="flashMsgWrapper"]');

export function flashMessage(text, status = 'success') {
    $wrap.removeClass(CLASS_HIDDEN);
    let $content = $(`<p class="${FLASH_MSG_CLS_PREFIX+status}">${text}</p>`);
    $wrap.append($content);
    setTimeout(() => {
        $content.remove();
        if(!$wrap.children().length)
        {
            $wrap.addClass(CLASS_HIDDEN);
        }
    }, 3*1000);
}

export function replace($el, $new) {
    let $prev = $el.prev();
    if($prev.length)
    {
        $el.remove();
        $prev.after($new);
    }
    else
    {
        let $parent = $el.parent();
        $el.remove();
        $parent.prepend($new);
    }
}
