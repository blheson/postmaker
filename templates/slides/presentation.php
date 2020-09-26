<?php $dir = '../../';

include $dir . "system/initiate.php";
// $square_image = $app->get_factory('SquareImage');
$array = ['class' => 'FoodSlide', 'namespace' => 'Controller\Template\Square\Slide\\'];
// unset($_SESSION['saved_logo']);
$slide = $app->get_factory($array);

$new_image_path = "assets/images/render/";

$front_image = "assets/images/templates/foodslide/template_front.png";
$back_image = "assets/images/templates/foodslide/template_back.png";
$content_image = "assets/images/templates/foodslide/template_content.png";

$design_template = "assets/images/templates/circleprice/circle-price-tag.png";

if (isset($_POST['section']))
    $image_link = $slide->process($_POST);


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
            <div class="col-md-12 col-lg-8 col-sm-12">
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
                    <form method="post" enctype="multipart/form-data" class="edit-form">
                        <div class="row">
                            <?php if (!isset($_SESSION['saved_logo'])) : ?>
                                <div class="col-6">
                                    <div class="form-group">
                                        <label for="">Add logo</label>

                                        <input type="file" name="logo" class="form-control" required>
                                    </div>
                                </div>
                            <?php endif; ?>
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

                        <input type="hidden" name="front_image" value="<?= $dir . $front_image ?>">
                        <input type="hidden" name="new_image_path" value="<?= $dir . $new_image_path; ?>">
                        <input type="hidden" name="design_template" value="<?= $dir . $design_template; ?>">
                        <input type="submit" class="btn btn-submit">

                    </form>
                </div>
            </div>

            <div id="render" class="col-md-12 mt-3">

                <h3 class="title">Final Render</h3>
                <!-- render finished image -->
                <?php
                if (isset($_POST['title'])) :
                ?>
                    <div>
                        <div class="render">
                            <img src="<?= $image_link; ?>" alt="rendered image" width="600px">
                        </div>

                        <div class="form-group">
                            <div>
                                <a href="<?= $image_link; ?>" download><button class="btn btn-submit">
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
include $dir . "includes/footer.php";
?>
<script>

    const uiCtr = {
        section: document.querySelector("select[name=section]"),
        next_button: document.querySelector(".next"),
        page_counter: document.querySelector(".page-box"),
        form: document.querySelector(".edit-form"),
        title: document.querySelector("textarea[name=title]"),
        content: document.querySelector("textarea[name=content]"),
        textarea: document.querySelector(".textarea"),
        textarea_label: document.querySelector(".textarea_label"),
        submit: document.querySelector("input[name=submit]"),
        indicator: document.querySelector(".indicator")
    }
    console.log(uiCtr.section);
    var page = 1;
    uiCtr.next_button.addEventListener('click', () => {
        page += 1;
        uiCtr.page_counter.innerText = page;
    });


    const changeContent = function() {

        switch (this.value) {
            case 'content':
                uiCtr.indicator.innerText = `Content section`;
                uiCtr.title.style.display = "none";
                uiCtr.content.style.display = "block";
                uiCtr.textarea_label.innerText = "Content";
                break;
            case 'back':
                uiCtr.indicator.innerText = `Back cover`;
                uiCtr.title.style.display = "none";
                uiCtr.content.style.display = "none";
            
                uiCtr.textarea_label.innerText = "";

                break;
            default:
                uiCtr.indicator.innerText = `Front cover`;
                uiCtr.title.style.display = "block";
                uiCtr.content.style.display = "none";
                uiCtr.textarea_label.innerText = "Title";

                break;
        }

    };
    uiCtr.section.addEventListener('change', changeContent);
</script>