"Use strict"
let text = document.querySelector("input[name=text]");
text.addEventListener("input", ()=>{          
    let submit = document.querySelector("input[type=submit]");
    if(text.value.length > 60){
        let status = document.querySelector(".status_input");
        status.innerText='Character should not be more than 60';    
        submit.disabled=true;
    }else{
        console.log('here');
        submit.disabled=false;
        status.innerText='';
    }
})
$('h3.template').click(()=>{
    $('.default_template').toggle(200);
})

$('input[name=check]').change(()=>{

    $('.background_colour').toggle();
})