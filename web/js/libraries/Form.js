
export default class Form {

    formElement = undefined;
    $formElement = undefined;

    constructor(el) {
        if(el instanceof  $)
        {
            this.$formElement = el;
            this.formElement = el[0];
        } else {

            this.$formElement = $(el);
            this.formElement = el;
        }

        this.$formElement.submit(e => {
            e.preventDefault();
            $.ajax({
                type: "POST",
                processData: false,
                contentType: false,
                url: this.formElement.action||'#',
                success: (data) => {/*todo*/ console.log(data);},
                data: new FormData(this.formElement),
                error: (XMLHttpRequest, textStatus, errorThrown) => {/*todo*/console.error(data);}
            });
        });
    }
}