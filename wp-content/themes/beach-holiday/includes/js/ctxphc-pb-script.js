/**
 * Created by ken_kilgore1 on 5/28/2015.
 */

/*jslint browser:true */

var $j = jQuery.noConflict();

$j(document).ready(function () {
    "use strict";
    var $formID = $j('#pbMembOnlyRegForm');
    $j($formID).validationEngine({promptPosition: "centerRight"});
    $j($formID).validationEngine('attach');
});

$j(document).ready(function () {
    "use strict";
    if ($j("#pb_attendee_count_2:checked[type='radio']").val() === "2") {
        $j("#pb_attendee_2").show();
        $j("#pb_attendee_3").hide();
        $j("#pb_attendee_4").hide();
    } else if ($j("#pb_attendee_count_3:checked[type='radio']").val() === "3") {
        $j("#pb_attendee_2").show();
        $j("#pb_attendee_3").show();
        $j("#pb_attendee_4").hide();
    } else if ($j("#pb_attendee_count_4:checked[type='radio']").val() === "4") {
        $j("#pb_attendee_2").show();
        $j("#pb_attendee_3").show();
        $j("#pb_attendee_4").show();
    } else {
        $j("#pb_attendee_2").hide();
        $j("#pb_attendee_3").hide();
        $j("#pb_attendee_4").hide();
    }

    $j("form input[name='attendee_count'][type='radio']").click(function () {
        if ($j("#pb_attendee_count_2:checked[type='radio']").val() === "2") {
            $j("#pb_attendee_2").show();
            $j("#pb_attendee_3").hide();
            $j("#pb_attendee_4").hide();
        } else if ($j("#pb_attendee_count_3:checked[type='radio']").val() === "3") {
            $j("#pb_attendee_2").show();
            $j("#pb_attendee_3").show();
            $j("#pb_attendee_4").hide();
        } else if ($j("#pb_attendee_count_4:checked[type='radio']").val() === "4") {
            $j("#pb_attendee_2").show();
            $j("#pb_attendee_3").show();
            $j("#pb_attendee_4").show();
        } else {
            $j("#pb_attendee_2").hide();
            $j("#pb_attendee_3").hide();
            $j("#pb_attendee_4").hide();
        }
    });
});