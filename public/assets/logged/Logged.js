let Logged = (() => {

    let logged = () => {
    };

    return {
        init: () => {
            logged();
        }
    }
})();

jQuery(function () {
    Logged.init();
})

