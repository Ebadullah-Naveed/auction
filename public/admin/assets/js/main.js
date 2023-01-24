
/**
 * Ids of the elements
 */
const loaderId = '#loader';

/**
 * Classess
 */
const filterCollapseBtnClass = '.filter_collapse_btn';
const filterSubmitBtnClass = '.submit_filter';
const filterRefreshBtnClass = '.filter_refresh_btn';

/**
 * @description: These functions are use to show and hide loader
 */
const showLoader = () => {
    $(loaderId).show();
}
const hideLoader = () => {
    $(loaderId).hide();
}

/**
 * @description: This function is used to customize the toast dialog
 */
function toastColorRender() {
    $(".swal2-content").prepend(`<span style="padding-right: 10px;color:#000;"><i class="fa fa-info-circle"></i></span>`);
    $(".swal2-header").parent().css("background-color", "#f0ce8c");
    $(".swal2-header").parent().parent().css("width", "fit-content");
    $(".swal2-content").css("display", "inline-flex");
    $(".swal2-content #swal2-content").css("color", "#000000");
}

/**
 * @description: Initialize Toast
 */
const Toast = Swal.mixin({
    toast: true,
    position: "top",
    showConfirmButton: false,
    timer: 10000,
    onOpen: (toast) => {
        // setTimeout(function(){toastColorRender();},0);
    }
});

/**
 * @description: Helper function to format JSON data in textarea
 * @param {object} data
 */
const prettyPrint = (elementId) => {
    var ugly = $(elementId).val();
    if( (ugly != null) && (ugly != '') ){
        var obj = JSON.parse(ugly);
        var pretty = JSON.stringify(obj, undefined, 4);
        $(elementId).val(pretty);
    }
}

/**
 * @description Helper function to format amount into K form
 */
function kFormatter(num) {
    return Math.abs(num) > 999 ? Math.sign(num)*((Math.abs(num)/1000).toFixed(1)) + 'k' : Math.sign(num)*Math.abs(num)
}

/**
 * Filter collapse on submit btn event
 */
// $(filterSubmitBtnClass).click(function(){
//     $(filterCollapseBtnClass).click();
// });
$(filterRefreshBtnClass).click(function(){
    $(filterSubmitBtnClass).click();
});

/**
 * @description: Example starter JavaScript for disabling form submissions if there are invalid fields
 */
(function() {
    'use strict';
    window.addEventListener('load', function() {
        // Fetch all the forms we want to apply custom Bootstrap validation styles to
        var forms = document.getElementsByClassName('needs-validation');
        // Loop over them and prevent submission
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();