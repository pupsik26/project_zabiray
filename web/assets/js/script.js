// window.onload = function() {
//     if (window.jQuery) {
//         // jQuery is loaded
//         alert("Yeah!");
//     } else {
//         // jQuery is not loaded
//         alert("Doesn't Work");
//     }
// }

function nextButton()
{
    $('#send').removeClass('disabled').addClass('send').prop('disabled', false);
    $('#notification').addClass('hidden');
}

function dateIdentification()
{
    let date = $('#date');
    let selected_date = $('#card-number option:selected');
    let date_now = date.attr('data-nowTstmp');
    let date_card = selected_date.attr('data-date');
    date.text(date_card);
    let date_card_tstmp = selected_date.attr('data-tstmp');
    checkDate(date_card_tstmp, date_now);
}

function checkDate(cardDate, nowDate)
{
    if (nowDate > cardDate) {
        let selected_date = $('#card-number option:selected');
        $('#notification').text('Карта ' +
            selected_date.val() +
            ' является более не действительной на ' +
            $('#date').attr('data-now') +
            ', так как срок ее действия прошел ' +
            selected_date.attr('data-date')).removeClass('hidden')
            .append('<button onclick="nextButton()" class="send" id="next">Продолжить, карта действительная</button>');
        $('#send').removeClass('send').addClass('disabled').prop('disabled', false);
    } else {
        $('#send').removeClass('disabled').addClass('send').prop('disabled', false);
        $('#notification').text('').addClass('hidden');
    }
}

dateIdentification()

$('#card-number').change(function () {
    dateIdentification()
});

$('#send').click(function () {
    $('.send-notification').show().delay(3000).slideUp(300);
});