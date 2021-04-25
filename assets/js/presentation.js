let defaultData = {
    page: 0,
    editIndex: 0,
    imageDataIndex: 0,
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
    },
    rootDir: dir
}

const uiCtrl = {

    nextButton: document.querySelector(".next"),
    formTitle: document.querySelector(".form_title"),
    pageCounter: document.querySelector(".page_box"),
    textarea: document.querySelector("textarea"),
    textarea_label: document.querySelector(".textarea_label"),
    workingImg: document.querySelector(".working_img img"),
    downloadBox: document.querySelector(".download_box"),

    render: document.querySelector(".render"),

    indicator: document.querySelector(".indicator"),
    currentPageDesign: () => document.querySelector(`img.rendered_page_${defaultData.imageDataIndex}`),
}

const form = {
    edit: function (page) {
        defaultData.formStatus = 'edit'
        section.select(page)
    },
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
    processForm: function (formDom) {

        let fd = new FormData();

        fd = this.prepareForm(fd, formDom)
        if (!fd) return
        let request = new Request(`${dir}api/slide/store.php`, {
            method: 'post',
            body: fd
        });
        crud.store(request).then(result => {
            if (result.error)
                throw new Error(result.message);

            let imageIndex = defaultData.formStatus == 'edit' ? defaultData.editIndex : defaultData.imageDataIndex
            if (defaultData.formStatus == 'edit') {

                helper.renderImg.editImageTag(uiCtrl.currentPageDesign(), result.message)
            } else {
                let img = helper.renderImg.createImageTag(result.message)
                uiCtrl.render.appendChild(helper.renderImg.imageBox(img))

                uiCtrl.pageCounter.innerText = defaultData.getPage();
            }
            defaultData.cache[defaultData.imageIndex] = {
                'imgSrc': defaultData.rootDir + result.message,
                'section': form.group().section.value,
                'text': form.group().text.value
            }
            uiCtrl.workingImg.src = formDom.querySelector('select[name=section]').value == 'front' ? dir + imageDefault.front() : (formDom.querySelector('select[name=section]').value == 'content' ? dir + imageDefault.content() : dir + imageDefault.back())

            middleware.info(`Design ${defaultData.page} Successfully saved`, 'success')

            helper.waitFetch(false)

        }).catch(error => {
            defaultData.page -= 1
            defaultData.imageDataIndex -= 1
            helper.waitFetch(false)
            middleware.info(error)
        })
        // fetch(request).then(response => {

        //     if (response.status !== 200)
        //         throw new Error(result.message);

        //     return response.json()
        // }
        // ).then(result => {

        //     if (result.error)
        //         throw new Error(result.message);

        //     let dPage = defaultData.page

        //     defaultData.cache[dPage] = {
        //         'imgSrc': defaultData.rootDir + result.message,
        //         'section': form.group().section.value,
        //         'text': form.group().text.value
        //     }

        //     uiCtrl.workingImg.src = formDom.querySelector('select[name=section]').value == 'front' ? dir + imageDefault.front() : (formDom.querySelector('select[name=section]').value == 'content' ? dir + imageDefault.content() : dir + imageDefault.back())

        //     let img = helper.renderImg.createImageTag(dPage, result.message)
        //     uiCtrl.render.appendChild(helper.renderImg.imageBox(img))

        //     uiCtrl.pageCounter.innerText = defaultData.getPage();

        //     middleware.info(`Design ${defaultData.page} Successfuly saved`, 'success')

        //     helper.waitFetch(false)

        // }).catch(error => {
        //     defaultData.page -= 1
        //     helper.waitFetch(false)
        //     middleware.info(error)
        // })
    }
}
const section = {
    select: (page, createStatus = false, section = 'front') => {

        let sectionText = createStatus ? section : defaultData.cache[page].section
        uiCtrl.indicator.innerText = `${sectionText} cover`;
        uiCtrl.workingImg.src = createStatus ? dir + defaultData.getSection(section).imgSrc : dir + defaultData.cache[page].imgSrc
        uiCtrl.textarea_label.innerText = sectionText;
        uiCtrl.textarea.value = createStatus ? defaultData.getSection(section).text : defaultData.cache[page].text
        form.group().section.value = sectionText
        let status = createStatus ? 'add' : 'edit'
        uiCtrl.formTitle.innerText = `Fill form to ${status} design`
    },
    changeContent: function (e) {
        this.select(null, true, e.value)
    }
}

const helper = {
    waitFetch: (status) => {
        form.group().section.disabled = status
        uiCtrl.nextButton.disabled = status
    },
    renderImg: {
        createEditTool: (src) => {
            let div = document.createElement('div')
            div.setAttribute('class', 'edit_tool')
            let span = document.createElement('span')
            span.innerText = 'del'
            span.setAttribute('style', 'padding-right:5px')
            span.setAttribute('class', 'delete_image')
            let a = document.createElement('a')
            a.innerText = 'dwnl'
            a.href = src
            a.setAttribute('download', 'image')
            div.appendChild(span)
            div.appendChild(a)
            return div
        },
        imageBox: function (img) {
            let div = document.createElement('div');
            div.setAttribute('class', 'foodslide_rendered_block')
            div.appendChild(img)
            div.appendChild(this.createEditTool(img.src))
            return div
        },
        editImageTag: function (img, src) {
            img.src = defaultData.rootDir + src
        },
        createImageTag: function (src) {
            let img = document.createElement('img');
            img.dataset.page = defaultData.imageDataIndex
            img.classList.add('foodslide_rendered_img', `rendered_page_${defaultData.imageDataIndex}`)
            img.setAttribute('width', '100%');
            let imgSrc = defaultData.rootDir + src;
            img.src = imgSrc
            return img
        }
    }
}
const frontSection = {

    formInput: (fd) => {
        fd.append('title', form.group().title.value)
        fd.append('newImagePath', form.group().newImagePath.value)
        fd.append('frontImage', form.group().frontImage.value)
        return fd;
    }
}
const contentSection = {

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

    formInput: (fd, formDom, backImg) => {
        // fd.append('backImg', backImg)
        fd.append('backImage', form.group().backImage.value)
        fd.append('content', form.group().text.value)
        return fd;
    }
}


uiCtrl.nextButton.addEventListener('click', (e) => {

    helper.waitFetch(true)
    if (defaultData.formStatus == 'edit') {
        form.processForm(form.group().body);
        return
    }
    defaultData.page += 1
    defaultData.imageDataIndex += 1
    form.processForm(form.group().body);
});

form.group().section.addEventListener('change', (e) => {
    section.changeContent(e.target)
});
// form.group().body.addEventListener('submit', (e) => {
//     helper.waitFetch(true)
//     e.preventDefault();
//     form.processForm(e.target)
// })




//if start, page 1 should be page 2 **
//if page 2 and front, change to page 1
//if page 1 and other pages available, get other page next
document.querySelector('.render').addEventListener('click', (e) => {
    if (e.target.classList.contains('foodslide_rendered_img'))
        section.select(e.target.dataset.page)

    if (e.target.classList.contains('delete_image')) {
        if (confirm('This action cannot be undone')) {
            let src = e.target.parentElement.querySelector('a').href
            crud.delete(`${dir}api/slide/delete.php?src=${src}`).then(result => {
                if (result.error)
                    throw new Error(result.message)


                e.target.parentElement.parentElement.remove(e.target)
                middleware.info(result.message, 'success')
                defaultData.page -= 1
                uiCtrl.pageCounter.innerText = defaultData.page
            }).catch(error => {
                middleware.info(error.message)
            })
        }

    }
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
             // if (formDom.querySelector('select[name=section]').value == 'front') {
            //     uiCtrl.workingImg.src = dir + imageDefault.front()
            // }else if (formDom.querySelector('select[name=section]').value == 'content') {
            //     uiCtrl.workingImg.src = dir + imageDefault.content();
            // }else {
            //     uiCtrl.workingImg.src = dir + imageDefault.back()
            // }
                        // if (document.querySelector('#download_btn')) return
            // let a = document.createElement('a');
            // a.innerText = 'download'
            // a.id = 'download_btn'
            // a.classList.add('download_btn', 'btn', 'btn-primary')
            // uiCtrl.downloadBox.appendChild(a)