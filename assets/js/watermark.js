const uiCtrl = function () {
    let render = document.querySelector('.render');
    let downloadBox = document.querySelector('.downloadBox')
    return {
        render,
        downloadBox,
        'renderBox': render.querySelector('img'),
        'downloadBtn': downloadBox.querySelector('a')
    }
}
const form = {
    group: () => {
        let body = document.querySelector(".edit-form")
        return {
            body,
            'logoPosition': document.querySelector('select[name=pos]'),
            'file': document.querySelector('input[name=file]'),
            'logo': document.querySelector('input[name=logo]'),
        }
    },
    prepareForm: function (fd) {
        fd.append('logo', form.group().logo.files[0])
        fd.append('file', form.group().file.files[0])
        fd.append('logoPosition', form.group().logoPosition.value)
        fd.append('type', 'watermark')
        return fd;
    },
    fetch: function () {
        let fd = new FormData();
        fd = form.prepareForm(fd, form.group().body)
        if (!fd) return
        let request = new Request(`${dir}api/watermark.php`, {
            method: 'POST',
            body: fd
        });

        fetch(request).then(response => {
            let res = response.json()

            if (response.status != '200') {
                throw new Error(response.error)
            }
            return res
        }).then(result => {
            if (result.error)
                throw new Error(result.message)


            if (!uiCtrl().downloadBtn ) {
                let img = document.createElement('img')
                uiCtrl().render.appendChild(img)
 
                let a = document.createElement('a');
              
                a.innerText = 'download'
                a.classList.add('btn', 'btn-primary')
                uiCtrl().downloadBox.appendChild(a)
                uiCtrl().renderBox = uiCtrl().render.querySelector('img')
                uiCtrl().downloadBtn = uiCtrl().downloadBox.querySelector('a')
            }
            uiCtrl().downloadBtn.setAttribute('download', `blim-watermark_${(new Date()).getTime()}`)
            uiCtrl().renderBox.src = dir + result.message
            uiCtrl().downloadBtn.href = dir + result.message

        }).catch(error => {
            middleware.info(error)
        })
    }
}
form.group().body.addEventListener('submit', (e) => {
    e.preventDefault();
    form.fetch()
})
