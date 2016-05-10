/**
 * Created by ken_kilgore1 on 1/11/2015.
 */

jQuery(document).ready(formValidation);


function formValidation() {
    var $formID = jQuery('#regForm');
    jQuery($formID).validationEngine({promptPosition: "centerRight"});
    jQuery($formID).validationEngine('attach');
}