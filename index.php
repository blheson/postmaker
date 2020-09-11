<?php
include "system/initiate.php";
$plain_template = "assets/images/plain_template.png";
$watermark_template = "assets/images/templates/watermark/product-watermark.png";
$circle_price_template = "assets/images/templates/circleprice/circle-price-tag.png";
$carousel_template = "assets/images/templates/watermark/product-watermark.png";
include "includes/header.php";
?>
<section class="hero bg-color">
    <div class="container">


        <div class="row">
            <div class="col-md-6">
                <!-- mouldA -->
                <h2 class="hero-title">Mould Content Designs
                </h2>
                <span class="d-block mb-5">Create content designs from predefined templates</span>
                
                <button class="btn btn-lg btn-warning">
                        Get started
                </button>
            </div>
        </div>
    </div>

</section>
<main class="container" style="padding:2rem">


    <!-- show template type -->
    <section class="template">
        <div class="card">
            <form action="" class="form inline-form row">
                <div class="form-group col-md-10">
                    <input type="text" class="form-control " name="q" placeholder="search template">

                </div>
                <div class="form-group col-md-2">
                    <button type="submit" class="btn btn-submit"><i class="fa fa-search"></i></button>
                </div>
            </form>
        </div>
        <div class="mt-5">
            <h3 class="text-center mb-3">Single Templates</h3>
            <div class="row">
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6">
                    <div class="card mb-2">
                        <div class="template_card">
                            <a href="templates/plain.php">
                                <img src="<?= $plain_template; ?>" alt="<?= basename($plain_template); ?>" width="100%">
                            </a>
                            <p>Plain</p>
                        </div>
                    </div>

                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6">
                    <div class="card  mb-2">
                        <div class="template_card">

                            <a href="templates/watermark.php">
                                <img src="<?= $watermark_template; ?>" alt="<?= basename($watermark_template); ?>" width="100%">
                            </a>
                            <p>Watermark</p>
                        </div>
                    </div>

                </div>
                <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6">
                    <div class="card  mb-2">
                        <div class="template_card">
                            <a href="templates/circleprice.php">
                                <img src="<?= $circle_price_template; ?>" alt="<?= basename($circle_price_template); ?>" width="100%">
                            </a>
                            <p>Circle Price Tag</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="mt-5">
            <h3 class="text-center mb-3">Carousels</h3>
            <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6">
                    <div class="card mb-2">
                        <div class="template_card">
                            <a href="templates/slides/presentation.php">
                                <img src="<?= $carousel_template; ?>" alt="<?= basename($carousel_template); ?>" width="100%">
                            </a>
                            <p>Slide</p>
                        </div>
                    </div>

                </div>
            </div>
        </div>

    </section>
</main>
<?php
include "includes/footer.php";
?>