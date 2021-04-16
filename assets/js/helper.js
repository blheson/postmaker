const middleware = {
    createInfoDom: function () {
        let div = document.createElement('div')
        div.classList.add('info')
        document.body.appendChild(div)
        return div
    },
    // infoDom: this.createInfoDom(),
    info: function (info, status = 'error') {
        dom = this.createInfoDom()

        dom.innerText = info;
        dom.classList.add('fadeOut', status);
        dom.style.cssText = `left:calc(50% - ${dom.offsetWidth / 2}px);`

        this.clear(dom, status);
    }
    ,
    clear: function (info, status) {

        setTimeout(() => {

            info.classList.remove('fadeOut', status);
            document.body.removeChild(info)
        }, 3000);
    }
    
 
}