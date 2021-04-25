uiCtrl = () => {
    let render= document.querySelector(".render")
    return {
        render,
        'renderImage': render.querySelector('img'),
        'downloadBtn': document.querySelector('.downloadBox a'),
    }
}

const form = {
    group: () => {
        let body = document.querySelector(".edit-form")
        return {
            body,
            'font': document.querySelector('select[name=font]'),
            'text': document.querySelector('input[name=text]'),
            'footer': document.querySelector('input[name=footer]'),
            'color': document.querySelector('input[name=color]'),
            'background': document.querySelector('input[name=background]'),
            'newImagePath': document.querySelector('input[name=newImagePath]'),
            'designTemplate': document.querySelector('input[name=designTemplate]'),
            'defaultImage': document.querySelector('input[name=defaultImage]'),
        }
    },
    processForm: () => {
        let fd = new FormData();
        fd.append('text', form.group().text.value)
        fd.append('footer', form.group().footer.value)
        fd.append('font', form.group().font.value)
        fd.append('color', form.group().color.value)
        fd.append('background', form.group().background.value)
        fd.append('newImagePath', form.group().newImagePath.value)
        fd.append('designTemplate', form.group().designTemplate.value)
        fd.append('defaultImage', form.group().defaultImage.value)
        return fd;
    },
    fetch: () => {

        let fd = form.processForm()
        let request = new Request(`${dir}api/square/plain/store.php`, {
            method: 'post',
            body: fd
        })
        crud.store(request).then((result) => {
            if (result.error) {
                middleware.info(result.message)
                return
            }

            uiCtrl().renderImage.src =  uiCtrl().downloadBtn.href  = dir + result.message
            uiCtrl().downloadBtn.setAttribute('download', `postmaker_plain_${(new Date()).getTime()}`)
 
            middleware.info('Image was successfully saved','success')
        }).catch(error => {
            middleware.info(error)
        })
    }
}

form.group().body.addEventListener('submit', (e) => {
    e.preventDefault();
    form.fetch()
})
