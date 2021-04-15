const ui_ctrl = {

    next_button: document.querySelector(".next"),
    page_counter: document.querySelector(".page-box"),
    textarea: document.querySelector(".textarea"),
    textarea_label: document.querySelector(".textarea_label"),
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
            title: body.querySelector("textarea[name=title]"),
            content: body.querySelector("textarea[name=content]"),
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
        let title = target.querySelector('textarea[name=title]').value;
        fd.append('section', section)
        fd.append('font', font)
        fd.append('title', title)
        fd.append('logo', logo_box.type == 'file' ? logo_box.files[0] : logo_box.value)
        switch (section) {
            case 'front':
                fd = front_section.form_input(fd, target, default_data.front)
                break;
            case 'content':
                if (form.group().content.value.length > 128) {
                    alert('Not allowed')
                    return;
                }
                fd = content_section.form_input(fd, target, default_data.content)
                break;
            case 'back':

                break;
            default:
                break;
        }
        return fd;
    },
    process_form: function (form_dom) {

        let fd = new FormData();

        fd = this.prepareForm(fd, form_dom)
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

            if (form_dom.querySelector('select[name=section]').value == 'front') {
                let img_src = dir + result.message;
                if (default_data.front) {//front image set

                    default_data.front = result.message;
                    ui_ctrl.render.querySelector('#front_img_render').src = img_src
                    return
                }

                default_data.front = result.message;
                let img = document.createElement('img');
                img.width = 600;
                img.id = 'front_img_render';
                img.src = img_src;
                ui_ctrl.render.prepend(img)
            }
            if (form_dom.querySelector('select[name=section]').value == 'content') {
                default_data.content = result.message;
                let img = document.createElement('img');
                img.width = 600;
                img.classList.add('content_img_render');

                img.src = dir + default_data.content;
                ui_ctrl.render.appendChild(img)
            }

        }).catch(error => console.log(error))
    }
}

const default_data = {
    page: 1,
    front: null,
    content: null,
    cachePage: 1,

}

const content_section = {
    select: () => {
        ui_ctrl.indicator.innerText = 'Content section';
        form.group().title.style.display = "none";
        form.group().content.style.display = "block";
        ui_ctrl.textarea_label.innerText = "Content";
    },
    form_input: (fd, form_dom, contentImg) => {
        let logo_box = form_dom.querySelector('input[name=logo]')
        let content = form.group().content.value;
        if (content.length > 110) {
            alert('not allowed')
            return
        }
        fd.append('logo', logo_box.type == 'file' ? logo_box.files[0] : logo_box.value)
        fd.append('designedContentImg', contentImg)
        fd.append('newImagePath', form.group().newImagePath.value)
        fd.append('content', form.group().content.value)
        fd.append('contentImage', form.group().contentImage.value)
        fd.append('designTemplate', form.group().designTemplate.value)
        return fd;
    }
}
const front_section = {
    select: () => {
        ui_ctrl.indicator.innerText = `Front cover`;
        form.group().title.style.display = "block";
        form.group().content.style.display = "none";
        ui_ctrl.textarea_label.innerText = "Title";
    },
    form_input: (fd, form_dom, frontImg) => {
        let logo_box = form_dom.querySelector('input[name=logo]')

        fd.append('logo', logo_box.type == 'file' ? logo_box.files[0] : logo_box.value)
        fd.append('frontImg', frontImg)
        fd.append('newImagePath', form.group().newImagePath.value)
        fd.append('frontImage', form.group().frontImage.value)
        fd.append('designTemplate', form.group().frontImage.value)
        return fd;
    }
}
const back_section = {
    select: () => {
        ui_ctrl.indicator.innerText = `Back cover`;
        ui_ctrl.form.title.style.display = "none";
        ui_ctrl.form.content.style.display = "none";

        ui_ctrl.textarea_label.innerText = "";

    },
    form_input: (fd, form_dom, backImg) => {

        fd.append('backImg', backImg)
        fd.append('newImagePath', form.group().newImagePath.value)
        fd.append('frontImage', form.group().frontImage.value)
        fd.append('designTemplate', form.group().frontImage.value)
        return fd;
    }
}
const change_content = function () {

    switch (this.value) {
        case 'content':
            content_section.select();
            break;
        case 'back':
            back_section.select();
            break;
        case 'front':
            front_section.select();
            break;
    }

}

ui_ctrl.next_button.addEventListener('click', () => {
    if(default_data.page == 1 && default_data.cachePage > 1 ){
        default_data.page = default_data.cachePage
      }
    if(form.group().section.value == 'front' && default_data.front && default_data.page > 1){
        default_data.cachePage = default_data.page
        default_data.page = 0
    }
 
    default_data.page += 1
    ui_ctrl.page_counter.innerText = default_data.page
    form.process_form(form.group().body)
});

form.group().section.addEventListener('change', change_content);
form.group().body.addEventListener('submit', (e) => {
    e.preventDefault();
    form.process_form(e.target)
})

//if start page 1 should be page 2 **
//if page 2 and front, change to page 1
//if page 1 and other pages available, get other page next