/*
 https://www.smashingmagazine.com/2016/07/improving-user-flow-through-page-transitions/

 You can copy paste this code in your console on smashingmagazine.com
 in order to have cross-fade transition when change page.
 */

var cache = {};
function loadPage(url) {
    if (cache[url]) {
        return new Promise(function (resolve) {
            resolve(cache[url]);
        });
    }

    return fetch(url, {
        method: 'GET'
    }).then(function (response) {
        cache[url] = response.text();
        return cache[url];
    });
}

var main = document.querySelector('#main-page');

function changePage() {
    document.querySelector('.flex-container').className = "flex-container";

    var url = window.location.href;

    loadPage(url).then(function (responseText) {
        var wrapper = document.createElement('div');
        wrapper.innerHTML = responseText;
        console.log(responseText);

        var oldContent = document.querySelector('.cc');
        var newContent = wrapper.querySelector('.cc');


        main.appendChild(newContent);
        oldContent.parentNode.removeChild(oldContent);
        document.querySelector('.flex-container').className = "flex-container hide";
        window.scrollTo(0, 0);
    });
}

window.addEventListener('popstate', changePage);

document.addEventListener('click', function (e) {

    var el = e.target;

    while (el && !el.href) {
        el = el.parentNode;
    }

    if (el) {
        e.preventDefault();

        history.pushState(null, null, el.href);
        changePage();

        return;
    }
});
