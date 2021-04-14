<?php
$dir = '../';
include $dir . "system/initiate.php";

use Controller\Helper as helper;
use Controller\Template\Watermark as watermark;
$watermark  = new watermark();
$newImagePath = "assets/images/render/";
$design_template = "assets/images/templates/watermark/product-watermark.png";

if (isset($_POST['watermark'])) {

    $image_link =  $watermark->logo_on_product($_FILES['file'], $_FILES['logo'], $_POST['pos'],100);
}
include $dir . "includes/header.php";
?>

<main class="container" style="padding: 2rem;box-shadow: 0 0 10px 1px #ececec;">

    <section class="header">
        <h1 class="text-center">Watermark Template</h1>
    </section>
    <center>
        <div class="breadcrumb">
            <a href="<?= $dir ?>"><i class="fa fa-home"></i></a>
        </div>
    </center>
    <!-- show template type -->
    <section class="template">
        <div class="row">
            <div class="col-md-4">
                <h3 class="title template">Template <i class="fa fa-caret-down pull-right"></i></h3>
                <div class="default_template" style="display: none;">
                    <img src="<?= $dir . $design_template; ?>" alt="<?= basename($design_template); ?>">
                </div>
            </div>
            <div class="col-md-4">
                <?= helper::showError() ?>
                <?= helper::showSuccess() ?>
                <h3 class="title">Fill form to edit design</h3>
                <div class="card">

                    <form method="post" enctype="multipart/form-data">
                        <input type="hidden" name="watermark">

                        <div class="form-group">
                            <label for="">Add logo</label>

                            <input type="file" name="logo" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label for="">Add file</label>
                            <input type="file" name="file" class="form-control" value="" required>
                        </div>
                        <div class="form-group">
                            <label for="">Logo Position</label>
                            <select name="pos" id="" class="form-control">
                                <option value="md">Center</option>
                                <option value="tl">Top left</option>
                                <option value="tc">Top center</option>
                                <option value="tr">Top right</option>
                                <option value="bl">Bottom left</option>
                                <option value="bc">Bottom center</option>
                                <option value="br">Bottom right</option>
                            </select>
                        </div>
                        <input type="submit" class="btn btn-submit">
                    </form>
                </div>
            </div>

            <div id="render" class="col-md-4">

                <h3 class="title">Final Render</h3>
                <!-- render finished image -->
                <?php
                if (isset($_POST['watermark'])) :
                ?>
                    <div>
                        <div class="render">
                            <img src="<?= $image_link; ?>" alt="rendered image" value="" width="100%">
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