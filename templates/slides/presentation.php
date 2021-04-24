<?php $dir = '../../';

include $dir . "system/initiate.php";

use Controller\Template\Slide\FoodSlide as foodslide;
use Controller\Constant as constant;

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
        /* width: 600px; */
        height: 300px;
    }
</style>

<main class="container" style="box-shadow: 0 0 10px 1px #ececec;">
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

            <div class="col-md-6 col-lg-4 col-sm-6">
                <div class="title_box">


                    <h3 class="title form_title">Fill form to create design </h3>

                </div>
                <div class="card">
                    <div class="row">
                        <div class="col-6">
                            <div class="indicator-box text-center">
                                <span class="indicator">Front cover</span>
                            </div>
                        </div>
                        <div class="col-3">
                            <!-- <div class="next"> -->
                            <button class="next btn btn-warning">add new +</button>
                            <!-- </div> -->

                        </div>
                        <!-- <div class="col-3">
                            <div class="next">
                                <div class="btn btn-warning">Page <span class="page_box">1</span>
                                </div>
                            </div>
                        </div> -->

                    </div>

                    <hr>
                    <form method="post" enctype="multipart/form-data" class="edit-form" id='slide-form'>
                        <div class="row">

                            <div class="col-6">
                                <div class="form-group">
                                    <label for="">Add logo</label>
                                    <?php

                                    $logoLink = constant::rootDir() . '/' . constant::rootImgPath() . '/' . ($_SESSION['savedLogo'] ?? null);

                                    $check = function () {
                                        unset($_SESSION['savedLogo']);
                                        return 'file';
                                    };

                                    $logoStatus = isset($_SESSION['savedLogo']) && file_exists($logoLink) ?
                                        'hidden' : $check()

                                    ?>
                                    <input type="<?= $logoStatus ?>" name="logo" class="form-control" value="<?= $_SESSION['savedLogo'] ?? null ?>">
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
                                $fonts = $slide->font->get_font();
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

                            <textarea name="front" id="" rows="3" class="form-control" value="This will be the title of the carousel">THIS WILL BE THE TITLE OF THE CAROUSEL</textarea>
                            <!-- <textarea name="content" style="display:none" id="" cols="30" rows="10" class="form-control" value="This will be the title of the carousel">The main content will go here</textarea> -->

                        </div>

                        <input type="hidden" name="frontImage" value="<?= $frontImage ?>">
                        <input type="hidden" name="newImagePath" value="<?= $newImagePath; ?>">
                        <input type="hidden" name="designTemplate" value="<?= $designTemplate; ?>">
                        <input type="hidden" name="contentImage" value="<?= $contentImage; ?>">
                        <input type="hidden" name="backImage" value="<?= $backImage; ?>">


                        <!-- <input type="submit" class="btn btn-submit" value="save"> -->

                    </form>
                </div>
            </div>
            <div class="col-md-6 col-lg-4 col-sm-6">
                <div class="render row3">

                </div>
            </div>
            <div id="render" class="col-md-6 col-sm-6 col-lg-4">

                <div class="title_box">
                    <h3 class="title">Template view</h3>
                    <small class="next">
                        <span class="page_box">0</span> Design(s) saved

                    </small>
                </div>

                <!-- render finished image -->



                <div class="working_img">
                    <span style="    position: absolute; color: #ffc107;
    font-size: 12px;
    text-rendering: optimizespeed;">work in progress</span>
                    <img src="<?= $dir; ?>assets/images/templates/foodslide/front.png" alt="" loading="lazy">
                    <!-- <small>Currently working on Design <span class="page_counter">1</span></small> -->
                </div>

                <div>
                    <!--  <div class="swiper-container">
                    <div class="swiper-wrapper render">
                      <div class="swiper-slide">Slide 1</div>
                        <div class="swiper-slide">Slide 2</div>
                        <div class="swiper-slide">Slide 3</div> 
                    </div>
                </div>-->


                    <div class="form-group">
                        <div class="download_box">


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
<script>
    const imageDefault = {
        front: () => 'assets/images/templates/foodslide/front.png',
        content: () => 'assets/images/templates/foodslide/content.png',
        back: () => 'assets/images/templates/foodslide/back.png',
    }
</script>
<script src="<?= $dir ?>assets/js/helper.js">

</script>
<script src="<?= $dir ?>assets/js/presentation.js?v=1.0.0">

</script>
<!-- <script>
const swiper = new Swiper('.swiper-container', {
  // Optional parameters
  direction: 'vertical',
  loop: true,

  // If we need pagination
  pagination: {
    el: '.swiper-pagination',
  },

  // Navigation arrows
  navigation: {
    nextEl: '.swiper-button-next',
    prevEl: '.swiper-button-prev',
  },

  // And if we need scrollbar
  scrollbar: {
    el: '.swiper-scrollbar',
  },
});</script> -->