let protocol = window.location.protocol;
let hostname = window.location.hostname;
let uri = protocol + "//" + hostname;

function* range(start, end) {
    yield start;
    if (start === end) return;
    yield* range(start + 1, end);
}

function valida_cnh (cnh) {
    if (!cnh.match("[0-9]")) {
        return false;
    }

    if (cnh === "11111111111" || cnh === "22222222222" || cnh === "33333333333"
        || cnh === "44444444444" || cnh === "55555555555" || cnh === "66666666666"
        || cnh === "77777777777" || cnh === "88888888888" || cnh === "99999999999"
        || cnh === "00000000000") {
        return false;
    }

    parcial = cnh.substr(0, 9);

    for (i = 0 , j = 2, s = 0; i < parcial.length; i++, j++) {
        s += parcial[i] * j;
    }

    resto = s % 11;
    if (resto <= 1) {
        dv1 = 0;
    } else {
        dv1 = 11 - resto;
    }

    parcial = dv1 + parcial;

    for (i = 0, j = 2, s = 0; i < parcial.length; i++, j++) {
        s += parcial[i] * j;
    }

    resto = s % 11;
    if (resto <= 1) {
        dv2 = 0;
    } else {
        dv2 = 11 - resto;
    }

    return "" + dv1 + "" + dv2 === cnh.substr(cnh.length - 2, 2);

}

function valida_cpf(cpf) {
    cpf = cpf.replace('.', '').replace('.', '').replace('-', '');

    if (cpf.length != 11 || cpf.match("/^{$c[0]}{11}$/")) {
        return false;
    }

    for (s = 10, n = 0, i = 0; s >= 2; n += cpf[i++] * s--) {
    }

    if (cpf[9] != (((n %= 11) < 2) ? 0 : 11 - n)) {
        return false;
    }

    for (s = 11, n = 0, i = 0; s >= 2; n += cpf[i++] * s--) {
    }

    if (cpf[10] != (((n %= 11) < 2) ? 0 : 11 - n)) {
        return false;
    }

    return true;
}