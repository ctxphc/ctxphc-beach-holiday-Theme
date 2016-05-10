/**
 * Created by PhpStorm.
 * User: ken_kilgore1
 * Date: 5/17/2015
 * Time: 7:36 PM
 * version: 0.5
 */

/*jslint browser:true */

var $j = jQuery.noConflict();

$j(document).ready(function () {
    "use strict";
    var $formID = $j('#regForm');
    $j($formID).validationEngine({promptPosition: "centerRight"});
    $j($formID).validationEngine('attach');
});

$j(document).ready(function () {
    "use strict";
    if ($j("#memb_type_1:checked[type='radio']").val() === "1") {
        $j("#spouse_spacer").hide();
        $j("#spouse_info").hide();
        $j("#family_spacer").hide();
        $j("#family_info").hide();
    } else if ($j("#memb_type_2:checked[type='radio']").val() === "2") {
        $j("#spouse_spacer").hide();
        $j("#spouse_info").hide();
        $j("#family_spacer").show();
        $j("#family_info").show();
    } else if ($j("#memb_type_3:checked[type='radio']").val() === "3") {
        $j("#spouse_spacer").show();
        $j("#spouse_info").show();
        $j("#family_spacer").hide();
        $j("#family_info").hide();
    } else if ($j("#memb_type_4:checked[type='radio']").val() === "4") {
        $j("#spouse_spacer").show();
        $j("#spouse_info").show();
        $j("#family_spacer").show();
        $j("#family_info").show();
    } else {
        $j("#spouse_spacer").hide();
        $j("#spouse_info").hide();
        $j("#family_spacer").hide();
        $j("#family_info").hide();
    }
    $j("form input[name='memb_type'][type='radio']").click(function () {
        if ($j("#memb_type_1:checked[type='radio']").val() === "1") {
            $j("#spouse_spacer").hide();
            $j("#spouse_info").hide();
            $j("#family_spacer").hide();
            $j("#family_info").hide();
        } else if ($j("#memb_type_2:checked[type='radio']").val() === "2") {
            $j("#spouse_spacer").hide();
            $j("#spouse_info").hide();
            $j("#family_spacer").show();
            $j("#family_info").show();
        } else if ($j("#memb_type_3:checked[type='radio']").val() === "3") {
            $j("#spouse_spacer").show();
            $j("#spouse_info").show();
            $j("#family_spacer").hide();
            $j("#family_info").hide();
        } else if ($j("#memb_type_4:checked[type='radio']").val() === "4") {
            $j("#spouse_spacer").show();
            $j("#spouse_info").show();
            $j("#family_spacer").show();
            $j("#family_info").show();
        } else {
            $j("#spouse_spacer").hide();
            $j("#spouse_info").hide();
            $j("#family_spacer").hide();
            $j("#family_info").hide();
        }
    });
});
