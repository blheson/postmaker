<?php
include "system/initiate.php";
$plain_template = "assets/images/plain_template.png";
$watermark_template = "assets/images/templates/watermark/product-watermark.png";
$circle_price_template = "assets/images/templates/circleprice/circle-price-tag.png";

include "includes/header.php";
?>

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
        <div class="row">
            <h3>Templates</h3>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6">
                <div class="card">
                    <div class="default_template">
                        <a href="templates/plain.php">
                            <img src="<?= $plain_template; ?>" alt="<?= basename($plain_template); ?>" width="100%">
                        </a>
                        <p>Plain Template</p>
                    </div>
                </div>

            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6">
                <div class="card">
                    <div class="default_template">

                        <a href="templates/watermark.php">
                            <img src="<?= $watermark_template; ?>" alt="<?= basename($watermark_template); ?>" width="100%">
                        </a>
                        <p>Water Mark template</p>
                    </div>
                </div>

            </div>
            <div class="col-lg-3 col-md-6 col-sm-6 col-xs-6">
                <div class="card">
                    <div class="default_template">
                        <a href="templates/circleprice.php">
                            <img src="<?= $circle_price_template; ?>" alt="<?= basename($circle_price_template); ?>" width="100%">
                        </a>
                        <p>Circle price tag Template</p>
                    </div>
                </div>

            </div>
        </div>
    </section>
</main>
<?php
include "includes/footer.php";
?>