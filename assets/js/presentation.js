const defaultData = {
    page: 0,
    getPage: function () {
        return this.page
    },
    front: null,
    content: null,
    back: null,
    cachePage: 1,
    cache: []
}
const uiCtrl = {

    nextButton: document.querySelector(".next"),
    pageCounter: document.querySelector(".page-box"),
    textarea: document.querySelector("textarea"),
    textarea_label: document.querySelector(".textarea_label"),
    workingImg: document.querySelector(".working_img img"),
    currentPageDesign: () => document.querySelector(`img.renderedpage_${defaultData.page}`),
    // render: {
    //     front: document.querySelector(".render .front_render"),
    //     content: document.querySelector(".render .content_render"),
    //     back: document.querySelector(".render .back_render"),
    // },
    render: document.querySelector(".render"),

    indicator: document.querySelector(".indicator")
}

const form = {
    group: () => {
        let body = document.querySelector(".edit-form")
        return {
            body,
            section: body.querySelector("select[name=section]"),
            font: body.querySelector("select[name=font]"),
            title: body.querySelector("textarea[name=front]"),
            content: body.querySelector("textarea[name=content]"),
            back: body.querySelector("textarea[name=back]"),
            frontImage: body.querySelector("input[name=frontImage]"),
            contentImage: body.querySelector("input[name=contentImage]"),
            backImage: body.querySelector("input[name=backImage]"),
            newImagePath: body.querySelector("input[name=newImagePath]"),
            designTemplate: body.querySelector("input[name=designTemplate]"),
            submit: body.querySelector("input[name=submit]")
        }
    },
    prepareForm: (fd, target) => {
        let logo_box = target.querySelector('input[name=logo]')
        let section = target.querySelector('select[name=section]').value;
        let font = target.querySelector('select[name=font]').value;
        // let title = target.querySelector('textarea[name=title]').value;
        fd.append('section', section)
        fd.append('font', font)
        // fd.append('title', title)
        fd.append('logo', logo_box.type == 'file' ? logo_box.files[0] : logo_box.value)
        // fd.append('designTemplate', form.group().backImage.value)
        fd.append('newImagePath', form.group().newImagePath.value)

        switch (section) {
            case 'front':
                fd = frontSection.formInput(fd, target, defaultData.front)
                break;
            case 'content':
                if (form.group().content.value.length > 128) {
                    alert('Not allowed')
                    return;
                }
                fd = contentSection.formInput(fd, target, defaultData.content)
                break;
            case 'back':
                fd = backSection.formInput(fd, target, defaultData.back)
                break;
            default:
                break;
        }
        return fd;
    },
    process_form: function (formDom) {

        let fd = new FormData();

        fd = this.prepareForm(fd, formDom)
        if (!fd) return
        let request = new Request(`${dir}api/process_slide.php`, {
            method: 'post',
            body: fd
        });

        fetch(request).then(response => {

            if (response.status !== 200)
                throw new Error(result.message);

            return response.json()
        }
        ).then(result => {

            if (result.error) {
                middleware.info(result.message)
                return
            }
            let img = document.createElement('img');
            let dPage = defaultData.page + 1
            img.dataset.page = dPage
            img.classList.add('renderedImg', `renderedpage_${dPage}`)

            img.setAttribute('width', '100%');
            let imgSrc = dir + result.message;
            img.src = imgSrc
            helper.waitFetch(false)
            defaultData.cache[dPage] = {
                imgSrc,
                'section': form.group().section.value
            }

            if (uiCtrl.currentPageDesign() !== null && uiCtrl.currentPageDesign().dataset.page == dPage) {
                uiCtrl.currentPageDesign().src = imgSrc
                middleware.info('Page wasnt changed, but design updated', 'success')
                uiCtrl.workingImg.src = imgSrc
                return
            }
            if (formDom.querySelector('select[name=section]').value == 'front') {

                defaultData.cache[dPage].front = form.group().title.value
                uiCtrl.workingImg.src = dir + imageDefault.front()
                // defaultData.front = result.message;
                uiCtrl.render.prepend(img)
            }
            if (formDom.querySelector('select[name=section]').value == 'content') {
                defaultData.cache[dPage].content = form.group().content.value;
                // defaultData.content = result.message;
                img.classList.add('content_img_render');
                uiCtrl.workingImg.src = dir + imageDefault.content();
            }
            if (formDom.querySelector('select[name=section]').value == 'back') {
                defaultData.cache[dPage].back = form.group().back.value

                // if (defaultData.back) {//front image set

                //     defaultData.back = result.message;
                //     uiCtrl.render.back.querySelector('img').src = imgSrc
                //     return
                // }
                uiCtrl.workingImg.src = dir + imageDefault.back()


                defaultData.back = imgSrc;
                img.classList.add('back_img_render');
            }

            uiCtrl.render.appendChild(img)
            defaultData.page += 1
            uiCtrl.pageCounter.innerText = defaultData.getPage()
            middleware.info(`Design ${defaultData.page} Successfuly saved`, 'success')
        }).catch(error => {
            helper.waitFetch(false)
            middleware.info(error)

        })
        helper.waitFetch(false)

    }
}


const helper = {
    waitFetch: (status) => {
        //    console.log(status)
        form.group().section.disabled = status
        uiCtrl.nextButton.disabled = status
    }

}
const frontSection = {
    select: () => {
        uiCtrl.indicator.innerText = `Front cover`;
        uiCtrl.workingImg.src = dir + imageDefault.front()
        uiCtrl.textarea_label.innerText = "Title";
        uiCtrl.textarea.name = "front";
        uiCtrl.textarea.value = "THIS WILL BE THE TITLE OF THE CAROUSEL";
    },
    formInput: (fd, formDom, frontImg) => {
        fd.append('title', form.group().title.value)
        fd.append('newImagePath', form.group().newImagePath.value)
        fd.append('frontImage', form.group().frontImage.value)
        return fd;
    }
}
const contentSection = {
    select: () => {
        uiCtrl.indicator.innerText = 'Content section';

        uiCtrl.workingImg.src = dir + imageDefault.content()

        uiCtrl.textarea_label.innerText = "Content";
        uiCtrl.textarea.name = "content";
        uiCtrl.textarea.value = "This will be where the content will go. You can structure your content per page";


    },
    formInput: (fd, formDom, contentImg) => {

        let content = form.group().content.value;
        if (content.length > 110) {
            middleware.info('Error: kindly, reduce the number of words')
            helper.waitFetch(false)
            return
        }

        // fd.append('designedContentImg', contentImg)
        fd.append('content', form.group().content.value)
        fd.append('contentImage', form.group().contentImage.value)
        return fd;
    }
}
const backSection = {
    select: () => {
        uiCtrl.indicator.innerText = `Back cover`;
        // form.group().title.style.display = "none";
        // form.group().content.style.display = "block";
        uiCtrl.workingImg.src = dir + imageDefault.back()

        uiCtrl.textarea_label.innerText = "Back Text";
        uiCtrl.textarea.value = "Kindly save and share";
        uiCtrl.textarea.name = "back";
    },
    formInput: (fd, formDom, backImg) => {
        // fd.append('backImg', backImg)
        fd.append('backImage', form.group().backImage.value)
        fd.append('content', form.group().back.value)
        return fd;
    }
}
const change_content = function () {

    switch (this.value) {
        case 'content':
            contentSection.select();
            break;
        case 'back':
            backSection.select();
            break;
        case 'front':
            frontSection.select();
            break;
    }
}
uiCtrl.nextButton.addEventListener('click', (e) => {
    helper.waitFetch(true)
    form.process_form(form.group().body);
   

    if (form.group().section.value == 'front' && defaultData.getPage() == 2) {
        contentSection.select();
        form.group().section.value = 'content'
    }
});

form.group().section.addEventListener('change', change_content);
form.group().body.addEventListener('submit', (e) => {
    helper.waitFetch(true)
    e.preventDefault();
    form.process_form(e.target)
})

//if start page 1 should be page 2 **
//if page 2 and front, change to page 1
//if page 1 and other pages available, get other page next
document.querySelector('.render').addEventListener('click', (e) => {
    console.log(e.target)
    console.log(e.target.dataset.page)
    console.log(defaultData.cache)
    e.path
    e.target
    e.srcElement
    e.attributes
    e.classList
    e.currentSrc
    e.dataset
    e.offsetParent
    e.nextSibling
})
document.querySelectorAll('img.renderedImg').forEach((e) => {
    console.log(e)
})