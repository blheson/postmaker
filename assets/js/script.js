"Use strict"

$('h3.template').click(()=>{
    $('.default_template').toggle(200);
})

$('input[name=check]').change(()=>{
    $('.background_colour').toggle();
})