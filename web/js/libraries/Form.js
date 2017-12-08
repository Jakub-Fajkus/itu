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
        let $outElement = $('[data-type="datetime"]'),
            $dateElement = $('<input type="text" />'),
            $timeElement = $('<input type="text" />');
        $outElement.parent().append($dateElement);
        $outElement.parent().append($timeElement);
        $outElement.hide();

        let dateVal = (+(new Date())),
            timeVal = 0;
        $dateElement.pickadate({
            formatSubmit: 'yyyy/mm/dd',
            onSet: function (context) {
                console.log(context.select);
                dateVal = context.select / 1000;
            }
        });
        $timeElement.pickatime({
            format: 'H:i',
            onSet: function (context) {
                console.log(context.select);
                timeVal = context.select * 60;
            }
        });
        this.$formElement.submit(e => {
            e.preventDefault();

            $outElement.val(dateVal+timeVal);
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