$(document).ready(function () {
    $('.menu-open').click(function () {
        $('.ei-sidebar').toggleClass('open');
        $('#ei-overlay').toggleClass('open');
    })

    $('#ei-overlay').click(function () {
        $('#ei-overlay').toggleClass('open');
        $('.ei-sidebar').toggleClass('open');
    })

    // toogle
    $(document).ready(function (){
        $('[data-toggle="tooltip"]').tooltip()
    });
})

window.generatePassword = function (length) {
    let result           = '';
    const characters       = 'ACDDASDeirwdgoep@@!!#@$$#.123456789@@';
    const charactersLength = characters.length;
    for ( let i = 0; i < length; i++ ) {
        result += characters.charAt(Math.floor(Math.random() * charactersLength));
    }
    return result;
}
