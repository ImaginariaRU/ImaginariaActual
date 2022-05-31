const _zalgo_limit = 0 + Math.floor(Math.random() * 100);

const _zalgo_scramble = function () {
    [].concat(...[...document.all].map(e => [...e.childNodes])).filter(n => n.nodeType === Node.TEXT_NODE).sort(() => 0.5 - Math.random()).slice(0, _zalgo_limit).map(n => n.textContent = n.textContent.replace(/([\u0000-\u007F\u0400-\u04FF])/g, (_, c) => c + [...Array(Math.floor(Math.random() * 30))].map(() => String.fromCharCode(0x300 + Math.floor(Math.random() * 79))).join('')));
}

const fun = () => {
    console.log('Chaos influence is ' + _zalgo_limit);
    _zalgo_scramble();
};

const zalgo = 1;
if (zalgo) window.addEventListener('DOMContentLoaded', fun);
