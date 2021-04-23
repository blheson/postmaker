let defaultData = {
    page: 0,
    getPage: function () {
        return this.page
    },
    front: null,
    content: null,
    back: null,
    cachePage: 1,
    cache: [],
    formStatus: 'add',
    getSection: (section) => {
        section = section.toLowerCase()
        return {
            section,
            'imgSrc': section == 'front' ? imageDefault.front() : (section == 'content' ? imageDefault.content() : imageDefault.back()),
            'text': section == 'front' ? 'THIS WILL BE THE TITLE OF THE CAROUSEL' : (section == 'content' ? 'This will be where the content will go. You can structure your content per page' : 'Kindly save and share')
        }
    }
}

const uiCtrl = {

    nextButton: document.querySelector(".next"),
    formTitle: document.querySelector(".form_title"),
    pageCounter: document.querySelector(".page_box"),
    textarea: document.querySelector("textarea"),
    textarea_label: document.querySelector(".textarea_label"),
    workingImg: document.querySelector(".working_img img"),
    downloadBox: document.querySelector(".download_box"),
    currentPageDesign: () => document.querySelector(`img.rendered_page_${defaultData.page}`),
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
            text: body.querySelector("textarea"),
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
                if (form.group().text.value.length > 50) {
                    middleware.info('Too many characters')
                    helper.waitFetch(false)
                    return;
                }
                fd = frontSection.formInput(fd, target, defaultData.front)
                break;
            case 'content':
                if (form.group().text.value.length > 128) {
                    middleware.info('Too many characters')
                    helper.waitFetch(false)

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
        let request = new Request(`${dir}api/slide/store.php`, {
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
                throw new Error(result.message);
            }
            let img = document.createElement('img');
            let dPage = defaultData.page
            // let dPage = defaultData.page + 1
            img.dataset.page = dPage
            img.classList.add('foodslide_rendered_img', `rendered_page_${dPage}`)

            img.setAttribute('width', '100%');
            let imgSrc = dir + result.message;
            img.src = imgSrc

            defaultData.cache[dPage] = {
                imgSrc,
                'section': form.group().section.value,
                'text': form.group().text.value
            }


            // defaultData.cache[dPage].text = form.group().title.value

            if (formDom.querySelector('select[name=section]').value == 'front') {


                uiCtrl.workingImg.src = dir + imageDefault.front()
                // defaultData.front = result.message;
                // uiCtrl.render.prepend(img)
            }
            if (formDom.querySelector('select[name=section]').value == 'content') {

                // defaultData.content = result.message;
                img.classList.add('content_img_render');
                uiCtrl.workingImg.src = dir + imageDefault.content();
            }
            if (formDom.querySelector('select[name=section]').value == 'back') {
                uiCtrl.workingImg.src = dir + imageDefault.back()
                img.classList.add('back_img_render');
            }

            uiCtrl.render.appendChild(img)

            uiCtrl.pageCounter.innerText = defaultData.getPage()
            middleware.info(`Design ${defaultData.page} Successfuly saved`, 'success')
            helper.waitFetch(false)
            if (document.querySelector('#download_btn')) return
            let a = document.createElement('a');
            a.innerText = 'download'
            a.id = 'download_btn'
            a.classList.add('download_btn', 'btn', 'btn-primary')
            uiCtrl.downloadBox.appendChild(a)
        }).catch(error => {
            defaultData.page -= 1

            helper.waitFetch(false)
            middleware.info(error)
        })
    }
}
const section = {
    select: (page, createStatus = false, section = 'front') => {
        console.log(defaultData.cache)
        let sectionText = createStatus ? section : defaultData.cache[page].section
        uiCtrl.indicator.innerText = `${sectionText} cover`;
        uiCtrl.workingImg.src = createStatus ? dir + defaultData.getSection(section).imgSrc : dir + defaultData.cache[page].imgSrc
        uiCtrl.textarea_label.innerText = sectionText;
        uiCtrl.textarea.value = createStatus ? defaultData.getSection(section).text : defaultData.cache[page].text
        form.group().section.value = sectionText
        let status = createStatus ? 'add' : 'edit'
        uiCtrl.formTitle.innerText = `Fill form to ${status} design`
    }
}

const helper = {
    waitFetch: (status) => {
        form.group().section.disabled = status
        uiCtrl.nextButton.disabled = status
    }
}
const frontSection = {
    // select: (title = null) => {
    //     uiCtrl.indicator.innerText = 'Front cover';
    //     uiCtrl.workingImg.src = dir + imageDefault.front()
    //     uiCtrl.textarea_label.innerText = "Front";
    //     uiCtrl.textarea.name = "front";
    //     uiCtrl.textarea.value = title ? title : "THIS WILL BE THE TITLE OF THE CAROUSEL";
    // },
    formInput: (fd) => {
        fd.append('title', form.group().title.value)
        fd.append('newImagePath', form.group().newImagePath.value)
        fd.append('frontImage', form.group().frontImage.value)
        return fd;
    }
}
const contentSection = {
    // select: () => {
    //     uiCtrl.indicator.innerText = 'Content section';

    //     uiCtrl.workingImg.src = dir + imageDefault.content()

    //     uiCtrl.textarea_label.innerText = "Content";
    //     uiCtrl.textarea.name = "content";
    //     uiCtrl.textarea.value = "This will be where the content will go. You can structure your content per page";


    // },
    formInput: (fd, formDom, contentImg) => {

        let content = form.group().text.value;
        if (content.length > 110) {
            middleware.info('Error: kindly, reduce the number of words')
            helper.waitFetch(false)
            return
        }

        // fd.append('designedContentImg', contentImg)
        fd.append('content', form.group().text.value)
        fd.append('contentImage', form.group().contentImage.value)
        return fd;
    }
}
const backSection = {
    // select: () => {
    //     uiCtrl.indicator.innerText = `Back cover`;
    //     // form.group().title.style.display = "none";
    //     // form.group().content.style.display = "block";
    //     uiCtrl.workingImg.src = dir + imageDefault.back()

    //     uiCtrl.textarea_label.innerText = "Back";
    //     uiCtrl.textarea.value = "Kindly save and share";
    //     uiCtrl.textarea.name = "back";
    // },
    formInput: (fd, formDom, backImg) => {
        // fd.append('backImg', backImg)
        fd.append('backImage', form.group().backImage.value)
        fd.append('content', form.group().text.value)
        return fd;
    }
}
const change_content = function (e) {
    section.select(null, true, e.value)
}
const edit = function (page) {
    defaultData.formStatus = 'edit'
    section.select(page)
}
uiCtrl.nextButton.addEventListener('click', (e) => {

    helper.waitFetch(true)
    defaultData.page += 1
    form.process_form(form.group().body);


    // if (form.group().section.value == 'front' && defaultData.getPage() == 2) {
    //     contentSection.select();
    //     form.group().section.value = 'content'
    // }
});

form.group().section.addEventListener('change', (e) => {
    change_content(e.target)
});
form.group().body.addEventListener('submit', (e) => {
    helper.waitFetch(true)
    e.preventDefault();
    form.process_form(e.target)
})

//if start page 1 should be page 2 **
//if page 2 and front, change to page 1
//if page 1 and other pages available, get other page next
document.querySelector('.render').addEventListener('click', (e) => {
    section.select(e.target.dataset.page)
    // console.log(e.target)
    // console.log(e.target.dataset.page)
    // console.log(defaultData.cache)
    // e.path
    // e.target
    // e.srcElement
    // e.attributes
    // e.classList
    // e.currentSrc
    // e.dataset
    // e.offsetParent
    // e.nextSibling
})
 