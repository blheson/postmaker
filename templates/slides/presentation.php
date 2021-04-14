<?php $dir = '../../';

include $dir . "system/initiate.php";

use Controller\Template\Slide\FoodSlide as foodslide;

$slide = new foodslide();
$newImagePath = "assets/images/render/";

$frontImage = "assets/images/templates/foodslide/template_front.png";
$backImage = "assets/images/templates/foodslide/template_back.png";
$contentImage = "assets/images/templates/foodslide/template_content.png";

$designTemplate = "assets/images/templates/circleprice/circle-price-tag.png";


include $dir . "includes/header.php";
?>
<style>
    .swiper-container {
        width: 600px;
        height: 300px;
    }
</style>

<main class="container" style="padding: 2rem;box-shadow: 0 0 10px 1px #ececec;">
    <div class="header">
        <h1 class="text-center">Instagram Carousel</h1>
    </div>
    <center>
        <div class="breadcrumb">
            <a href="<?= $dir ?>">home</a>
        </div>
    </center>

    <!-- show template type -->
    <section class="template">

        <div class="row">
            <div class="col-md-12 col-lg-4 col-sm-12">
                <h3 class="title template">Template <i class="fa fa-caret-down pull-right"></i></h3>
                <div class="d-none">
                    <div class="slide-section mb-3" style="overflow: hidden;">
                        <!-- Swiper -->
                        <div class="swiper-container">
                            <div class="swiper-wrapper">
                                <div class="swiper-slide">
                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <img src="<?= $dir; ?>assets/images/templates/foodslide/front.png" loading="lazy" class="blur-up lazyload" alt="" width="100%">
                                    </div>

                                </div>
                                <div class="swiper-slide">
                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <img src="<?= $dir; ?>assets/images/templates/foodslide/content.png" loading="lazy" class="blur-up lazyload" alt="" width="100%">
                                    </div>


                                </div>
                                <div class="swiper-slide">

                                    <div class="col-md-12 col-lg-12 col-sm-12">
                                        <img src="<?= $dir; ?>assets/images/templates/foodslide/back.png" loading="lazy" class="blur-up lazyloaded" alt="" width="100%">
                                    </div>

                                </div>
                            </div>
                            <!-- Add Pagination -->
                            <div class="swiper-pagination"></div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6 col-lg-6 col-sm-6">
                        <img src="<?= $dir; ?>assets/images/templates/foodslide/front.png" loading="lazy" class="blur-up lazyload" alt="" width="100%">
                        <small>Front section</small>
                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                        <img src="<?= $dir; ?>assets/images/templates/foodslide/content.png" loading="lazy" class="blur-up lazyload" alt="" width="100%">
                        <small>Content section</small>

                    </div>
                    <div class="col-md-6 col-lg-6 col-sm-6">
                        <img src="<?= $dir; ?>assets/images/templates/foodslide/back.png" loading="lazy" class="blur-up lazyload" alt="" width="100%">
                        <small>Back section</small>

                    </div>
                </div>
            </div>
            <div class="col-md-12 col-lg-4 col-sm-12">
                <h3 class="title">Fill form to edit design </h3>

                <div class="card">
                    <div class="row">
                        <div class="col-6">
                            <div class="indicator-box text-center">
                                <span class="indicator">Front cover</span>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="next">
                                <div class="btn btn-warning">next design</div>
                            </div>
                        </div>
                        <div class="col-3">
                            <div class="next">
                                <div class="btn btn-warning">Page <span class="page-box">1</spn>
                                </div>
                            </div>
                        </div>

                    </div>

                    <hr>
                    <form method="post" enctype="multipart/form-data" class="edit-form" id='slide-form'>
                        <div class="row">
                    
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Add logo</label>

                                        <input type="<?=$_SESSION['savedLogo']?'hidden':'file'?>" name="logo" class="form-control" value="<?=$_SESSION['savedLogo']??null?>">
                                    </div>
                                </div>
                          
                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">Choose section <i class="fa fa-info-circle"></i></label>
                                    <select name="section" class="form-control">
                                        <option value="front">Front</option>
                                        <option value="content">Content</option>
                                        <option value="back">Back</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="">Choose Font</label>
                            <select name="font" class="form-control">
                                <?php
                                $fonts = $slide->model->get_font();
                                foreach ($fonts as $key => $font) :

                                ?>
                                    <option value="<?= $font ?>"><?= $key ?></option>
                                <?php
                                endforeach;
                                ?>
                            </select>
                        </div>

                        <div class="form-group textarea">
                            <label for="" class="textarea_label">Title</label>

                            <textarea name="title" id="" cols="30" rows="10" class="form-control" value="This will be the title of the carousel">THIS WILL BE THE TITLE OF THE CAROUSEL</textarea>
                            <textarea name="content" style="display:none" id="" cols="30" rows="10" class="form-control" value="This will be the title of the carousel">The main content will go here</textarea>

                        </div>

                        <input type="hidden" name="frontImage" value="<?= $frontImage ?>">
                        <input type="hidden" name="newImagePath" value="<?= $newImagePath; ?>">
                        <input type="hidden" name="designTemplate" value="<?= $designTemplate; ?>">
                        <input type="hidden" name="contentImage" value="<?= $contentImage; ?>">
                        <input type="submit" class="btn btn-submit">

                    </form>
                </div>
            </div>
            <div id="render" class="col-md-12 mt-3 col-lg-4">

                <h3 class="title">Final Render</h3>
                <!-- render finished image -->
              
                    <div>
                        <div class="render">
                            
                        </div>

                        <div class="form-group">
                            <div>
                                <a href="<?= $image_link; ?>" download><button class="btn btn-submit">
                                        Download Image
                                    </button></a>

                            </div>

                        </div>
                    </div>
            


            </div>
        </div>
    </section>
</main>

<?php
echo "<script>let  dir = '$dir'</script>";
include $dir . "includes/footer.php";
?>
 
<script src="<?=$dir?>assets/js/presentation.js">

</script>