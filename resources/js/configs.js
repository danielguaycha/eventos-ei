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
        $('[data-toggle="tooltip"]').tooltip({
            container: '.ei-parent-content',
            placement: autoPlacement,
            html: true
        })
    });
});

const autoPlacement = function (tip, element) {
    let $document = $('.ei-parent-content');
    let offset = $(element).offset();
    let width = $document.outerWidth();
    if (offset.left < 100) {
        return  'right';
    }
    if (offset.left - width <= 100)
        return  'left';
    return "bottom"
};

String.prototype.pick = function(min, max) {
    let n, chars = '';

    if (typeof max === 'undefined') {
        n = min;
    } else {
        n = min + Math.floor(Math.random() * (max - min + 1));
    }

    for (let i = 0; i < n; i++) {
        chars += this.charAt(Math.floor(Math.random() * this.length));
    }

    return chars;
};


// Credit to @Christoph: http://stackoverflow.com/a/962890/464744
String.prototype.shuffle = function() {
    let array = this.split('');
    let tmp, current, top = array.length;

    if (top) while (--top) {
        current = Math.floor(Math.random() * (top + 1));
        tmp = array[current];
        array[current] = array[top];
        array[top] = tmp;
    }

    return array.join('');
};

window.generatePassword = function (length) {
    const specials = '@.#$%';
    const lowercase = 'abcdefghijklmnopqrstuvwxyz';
    const uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    const numbers = '0123456789123456789';

    let all = specials + lowercase + uppercase + numbers + numbers;

    let password = '';
    password += specials.pick(1);
    password += lowercase.pick(1);
    password += uppercase.pick(1);
    password += numbers.pick(1);
    password += all.pick(8, 8);
    password = password.shuffle();
    return password;
}
