import moment from 'moment';

export default class Form {
    formElement = undefined;
    $formElement = undefined;

    constructor(el) {
        if (el instanceof $) {
            this.$formElement = el;
            this.formElement = el[0];
        } else {

            this.$formElement = $(el);
            this.formElement = el;
        }
        let $outElement = $('[data-type="datetime"]');
        let dateVal = (+(new Date())),
            timeVal = 0;
        let oldDate = '', oldTime = '', oldVal = $outElement.val();


        if(oldVal && + !isNaN(oldVal))
        {
            console.log(+oldVal);
            let odt = moment(+oldVal * 1000);
            oldDate = `data-value="${odt.format('YYYY/MM/DD/')}"`;
            oldTime = `data-value="${odt.format('HH:mm')}"`;
            let clone = odt.clone();
            clone.millisecond(0);
            clone.seconds(0);
            clone.minutes(0);
            clone.hours(-1);
            dateVal = +clone.unix();
            timeVal = +odt.unix() - dateVal;

        }
        let $dateElement = $(`<input type="text" ${oldDate}/>`),
            $timeElement = $(`<input type="text" ${oldTime}/>`);
        $outElement.parent().append($dateElement);
        $outElement.parent().append($timeElement);
        $outElement.hide();


        $dateElement.pickadate({ //todo Edit
            formatSubmit: 'yyyy/mm/dd',
            onSet: function (context) {
                console.log(context.select);
                dateVal = context.select / 1000;
            }
        });
        $timeElement.pickatime({
            format: 'H:i',
            formatSubmit: 'H:i',
            onSet: function (context) {
                console.log(context.select);
                timeVal = (context.select + 60) * 60;
            }
        });
        this.$formElement.submit(e => {
            e.preventDefault();
            $outElement.val(dateVal + timeVal);
            $.ajax({
                type: "POST",
                processData: false,
                contentType: false,
                url: this.formElement.action || '#',
                success: (data) => {/*todo*/
                    console.log(data);
                },
                data: new FormData(this.formElement),
                error: (XMLHttpRequest, textStatus, errorThrown) => {/*todo*/
                    console.error(data);
                }
            });
        });


    }
}