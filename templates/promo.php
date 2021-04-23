<?php
// header('Content-type: image/png');
$dir= '../';
    include $dir."system/initiate.php";
    $square_image = $app->get_factory('SquareImage');
   
    $newImagePath= "assets/images/render/";
    $defaultImage= "assets/images/blank_image.png";
    $design_template="assets/images/plain_template.png";

if(isset($_POST['text'])){
    $image_link = $square_image->add_data_on_blank_image($_POST);
}   

    include $dir."includes/header.php";
?>
<center>
    <div class="breadcrumbs">
        <a href="<?=$dir?>"><i class="fa fa-home"></i></a>
    </div>
</center>
<main class="container" style="padding: 2rem;box-shadow: 0 0 10px 1px #ececec;"> 
<section class="header">
        <h1 class="text-center">Plain Template</h1>
    </section>
    <!-- show template type -->
    <section class="template">
        <div class="row">
            <div class="col-md-4">
                <h3 class="title template">Template <i class="fa fa-caret-down pull-right"></i></h3>
                <div class="default_template">
                    <img src="<?=$dir.$design_template;?>" alt="<?=basename($design_template);?>" loading="lazy">
                </div>
            </div>
            <div class="col-md-4"> <h3 class="title">Fill form to edit design</h3>
                <div class="card">  
               
                <form method="post" enctype="multipart">
                  
                    <div class="form-group">
                        <label for="">Choose Font</label>
                        <select name="font" class="form-control"> 
                             <?php
                                $fonts = $square_image->font->get_font();
                     
                                foreach($fonts as $key => $font):
                                
                            ?>
                            <option value="<?=$font?>"><?=$key?></option>
                            <?php
                                endforeach;
                            ?>
                        </select>
                    </div>
                   
                    <div class="form-group">
                         
                        <div> 
                            <small class="status_input">
                            </small>
                        </div>
                        <label for="">Edit Main text</label>

                        <input type="text" name="text" class="form-control" value="Always make do with the best of today">
                    </div>
                    <div class="form-group">
                        <label for="">Edit footer</label>
                         <input type="text" name="footer" class="form-control" value="This is a footer">
                     </div>
                     <div class="form-group background_colour" style="display:none">
                         <label for="">Change background colour</label>
                         <input type="color" name="background" class="form-control" value="#ffffff">
                     </div>
                     <div class="row">
                            <div class="col-md-1 pull-left">
                                <input type="checkbox" name="check">
                            </div>
                            <div class="col-md-10">
                                <label for=""  >Check to see advance option</label>
                            </div>
                     </div>
                    <input type="hidden" name="defaultImage" value="<?=$dir.$defaultImage?>">
                    <input type="hidden" name="newImagePath" value="<?=$dir.$newImagePath;?>">
                    <input type="hidden" name="design_template" value="<?=$dir.$design_template;?>">
                    <input type="submit" class="btn btn-submit">
                </form>
                </div>
            </div>

            <div id="render" class="col-md-4">
                
                <h3 class="title">Final Render</h3>
                  <!-- render finished image -->
                  <?php
                    if(isset($_POST['text'])):
                ?>
                <div>
                    <div class="render">
                         <img src="<?=$image_link;?>" alt="rendered image" value="<?=$_POST['text']?>" width="100%" loading="lazy">
                    </div>
                   
                    <div class="form-group">
                        <div>
                            <a href="<?=$image_link;?>" download><button class="btn btn-submit" >
                                   Download Image
                            </button></a>
                         
                        </div>
                        
                    </div>
                </div>
                    <?php
                        endif;
                    ?>
               
        
            </div>
        </div>
    </section>
</main>

<?php
    include $dir."includes/footer.php";
?>