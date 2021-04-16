const default_data = {
    page: 1,
    front: null,
    content: null,
    back: null,
    cachePage: 1,
    cache:[]
}
const ui_ctrl = {

    nextButton: document.querySelector(".next"),
    pageCounter: document.querySelector(".page-box"),
    textarea: document.querySelector("textarea"),
    textarea_label: document.querySelector(".textarea_label"),
    workingImg: document.querySelector(".working_img img"),
    currentPageDesign:()=>document.querySelector(`img.renderedpage_${default_data.page}`),
    // render: {
    //     front: document.querySelector(".render .front_render"),
    //     content: document.querySelector(".render .content_render"),
    //     back: document.querySelector(".render .back_render"),
    // },
    render:document.querySelector(".render"),

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
                fd = frontSection.formInput(fd, target, default_data.front)
                break;
            case 'content':
                if (form.group().content.value.length > 128) {
                    alert('Not allowed')
                    return;
                }
                fd = contentSection.formInput(fd, target, default_data.content)
                break;
            case 'back':
                fd = backSection.formInput(fd, target, default_data.back)
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
            
            let img = document.createElement('img');
            img.dataset.page = default_data.page
            img.classList.add(`renderedpage_${default_data.page}`)
            
            img.setAttribute('width', '100%');
            let img_src = dir + result.message;
            img.src = img_src
            helper.waitFetch(false)
            default_data.cache[default_data.page] = {
                img_src,
                'section':form.group().section.value
            }
        
            if(ui_ctrl.currentPageDesign() !== null && ui_ctrl.currentPageDesign().dataset.page == default_data.page){
                console.log('here')
                ui_ctrl.currentPageDesign().src = img_src
                middleware.info('Page wasnt changed, but design updated','success')
                    ui_ctrl.workingImg.src =img_src
                return
            }
            if (formDom.querySelector('select[name=section]').value == 'front') {
                default_data.cache[default_data.page].front=form.group().title.value
                
                // if (default_data.front) {//front image set

                //     default_data.front = result.message;
                //     ui_ctrl.render.front.querySelector('img').src = img_src
                //     return
                // }

                default_data.front = result.message;

                img.id = 'front_img_render';

                // ui_ctrl.render.front.prepend(img)
                ui_ctrl.render.prepend(img)
            }
            if (formDom.querySelector('select[name=section]').value == 'content') {
                default_data.cache[default_data.page].content=form.group().content.value
                
                default_data.content = result.message;
           
                img.classList.add('content_img_render');

                // img.src = dir + default_data.content;
                // ui_ctrl.render.content.appendChild(img)
            
            }
            if (formDom.querySelector('select[name=section]').value == 'back') {
                default_data.cache[default_data.page].back=form.group().back.value
                
                if (default_data.back) {//front image set

                    default_data.back = result.message;
                    ui_ctrl.render.back.querySelector('img').src = img_src
                    console.log(default_data.back)
                    return
                }
                default_data.back = img_src;
                img.classList.add('back_img_render');
                // ui_ctrl.render.back.appendChild(img)
                
            }
            middleware.info('','success')
            ui_ctrl.render.appendChild(img)
            ui_ctrl.workingImg.src =img_src
        }).catch(error => {
            helper.waitFetch(false)
            middleware.info(error)

        })
    }
}


const helper= {
   waitFetch: (status)=>{
    //    console.log(status)
        form.group().section.disabled=status
        ui_ctrl.nextButton.disabled=status
   }

}
const frontSection = {
    select: () => {
        ui_ctrl.indicator.innerText = `Front cover`;
        ui_ctrl.workingImg.src = dir+img.frontDefault

        ui_ctrl.textarea_label.innerText = "Title";
        ui_ctrl.textarea.name = "front";
        ui_ctrl.textarea.value = "THIS WILL BE THE TITLE OF THE CAROUSEL";


    },
    formInput: (fd, formDom, frontImg) => {

        fd.append('title', form.group().title.value)
        // fd.append('frontImg', frontImg)
        fd.append('newImagePath', form.group().newImagePath.value)
        fd.append('frontImage', form.group().frontImage.value)
        // fd.append('designTemplate', form.group().frontImage.value)
        return fd;
    }
}
const contentSection = {
    select: () => {
        ui_ctrl.indicator.innerText = 'Content section';
 
        ui_ctrl.workingImg.src = dir+img.contentDefault

        ui_ctrl.textarea_label.innerText = "Content";
        ui_ctrl.textarea.name = "content";
        ui_ctrl.textarea.value = "This will be where the content will go. You can structure your content per page";


    },
    formInput: (fd, formDom, contentImg) => {

        let content = form.group().content.value;
        if (content.length > 110) {
            alert('not allowed')
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
        ui_ctrl.indicator.innerText = `Back cover`;
        // form.group().title.style.display = "none";
        // form.group().content.style.display = "block";
        ui_ctrl.workingImg.src = dir+img.backDefault
        ui_ctrl.textarea_label.innerText = "Back Text";
        ui_ctrl.textarea.value = "Kindly save and share";
        ui_ctrl.textarea.name = "back";
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
ui_ctrl.nextButton.addEventListener('click', () => {

  
    if(!ui_ctrl.render.querySelector('img')){
        middleware.info('Kindly save your first design before creating a new page')
        return
    }
    if(!ui_ctrl.render.querySelector(`img.renderedpage_${default_data.page}`)){
        middleware.info('Kindly save your design before creating a new page')
        return
    }
    // helper.waitFetch(true)
    // if (default_data.page == 1 && default_data.cachePage > 1)
    //     default_data.page = default_data.cachePage

    // if (form.group().section.value == 'front' && default_data.front && default_data.page > 1) {
    //     default_data.cachePage = default_data.page
    //     default_data.page = 0
    // }

    // if(form.group().section.value == 'back' && default_data.back && default_data.page > 1){
    //     default_data.cachePage = default_data.page
    //     default_data.page = 0
    // }    
    
    // form.process_form(form.group().body)
 
// if(form.group().section.value == 'back' && default_data.back){
 
//     // default_data.page = default_data.cachePage
//     return
// }
 
    default_data.page += 1
    ui_ctrl.pageCounter.innerText = default_data.page
    if(form.group().section.value == 'front' &&  default_data.page == 2){
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