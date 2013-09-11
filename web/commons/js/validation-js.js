/**
 * Created with JetBrains PhpStorm.
 * User: benny
 * Date: 9/11/13
 * Time: 4:22 PM
 * Author:Lepebina
 * To change this template use File | Settings | File Templates.
 */



function copySelectedLeft()
{
    var n1 = document.getElementById('left_field');
    var n2 = document.getElementById('hris_dataqualitybundle_validationtype_leftExpression');
    n2.value =n1.options[n1.selectedIndex].text;
}

function copySelectedRight()
{
    var n1 = document.getElementById('right_field');
    var n2 = document.getElementById('hris_dataqualitybundle_validationtype_rightExpression');
    n2.value =n1.options[n1.selectedIndex].text;
}

function copyValueOfLeftAddButton()
{
    var n1 = document.getElementById('add');
    var n2 = document.getElementById('hris_dataqualitybundle_validationtype_leftExpression');
    n2.value = n2.value+n1.value;
}

function copyValueOfLeftMinusButton()
{
    var n1 = document.getElementById('minus');
    var n2 = document.getElementById('hris_dataqualitybundle_validationtype_leftExpression');
    n2.value = n2.value+n1.value;
}

function copyValueOfLeftMultiplyButton()
{
    var n1 = document.getElementById('multiply');
    var n2 = document.getElementById('hris_dataqualitybundle_validationtype_leftExpression');
    n2.value = n2.value+n1.value;
}

function copyValueOfLeftDivideButton()
{
    var n1 = document.getElementById('divide');
    var n2 = document.getElementById('hris_dataqualitybundle_validationtype_leftExpression');
    n2.value = n2.value+n1.value;
}

function copyValueOfLeftReminderButton()
{
    var n1 = document.getElementById('reminder');
    var n2 = document.getElementById('hris_dataqualitybundle_validationtype_leftExpression');
    n2.value = n2.value+n1.value;
}

function copyValueOfLeftBracketOneButton()
{
    var n1 = document.getElementById('brckt1');
    var n2 = document.getElementById('hris_dataqualitybundle_validationtype_leftExpression');
    n2.value = n2.value+n1.value;
}

function copyValueOfLeftBracketTwoButton()
{
    var n1 = document.getElementById('brckt2');
    var n2 = document.getElementById('hris_dataqualitybundle_validationtype_leftExpression');
    n2.value = n2.value+n1.value;
}


function copyValueOfRightAddButton()
{
    var n1 = document.getElementById('add1');
    var n2 = document.getElementById('hris_dataqualitybundle_validationtype_rightExpression');
    n2.value = n2.value+n1.value;
}

function copyValueOfRightMinusButton()
{
    var n1 = document.getElementById('minus1');
    var n2 = document.getElementById('hris_dataqualitybundle_validationtype_rightExpression');
    n2.value = n2.value+n1.value;
}

function copyValueOfRightMultiplyButton()
{
    var n1 = document.getElementById('multiply1');
    var n2 = document.getElementById('hris_dataqualitybundle_validationtype_rightExpression');
    n2.value = n2.value+n1.value;
}

function copyValueOfRightDivideButton()
{
    var n1 = document.getElementById('divide1');
    var n2 = document.getElementById('hris_dataqualitybundle_validationtype_rightExpression');
    n2.value = n2.value+n1.value;
}

function copyValueOfRightReminderButton()
{
    var n1 = document.getElementById('reminder1');
    var n2 = document.getElementById('hris_dataqualitybundle_validationtype_rightExpression');
    n2.value = n2.value+n1.value;
}

function copyValueOfRightBracketOneButton()
{
    var n1 = document.getElementById('brckt11');
    var n2 = document.getElementById('hris_dataqualitybundle_validationtype_rightExpression');
    n2.value = n2.value+n1.value;
}

function copyValueOfRightBracketTwoButton()
{
    var n1 = document.getElementById('brckt21');
    var n2 = document.getElementById('hris_dataqualitybundle_validationtype_rightExpression');
    n2.value = n2.value+n1.value;
}

