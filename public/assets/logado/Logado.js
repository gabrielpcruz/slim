let Logado = (() => {

    let logado = () => {
        console.log("arroz")
    };

    return {
        init: () => {
            logado();
        }
    }
})();

jQuery(function () {
    Logado.init();
})

