<?php
@include 'config.php';
$productSaved = FALSE;
if (isset($_POST['submit'])) {
    $errors = array();
    $product_Name = isset($_POST["name"]) ? $_POST['name'] : '';
    $product_Price = isset($_POST["price"]) ? $_POST['price'] : '';
    if (empty($product_Name)) {
        $errors[] = 'Please provide a product name';
    }
    if ($product_Price == 0) {
        $errors[] = 'Please provide product price';
    }
    if (!is_dir(UPLOAD_DIR)) {
        mkdir(UPLOAD_DIR, 0777, TRUE);
    }
    $file_name = $_FILES['file']['name'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $tmp = explode(".", $file_name);
    $file_ext = end($tmp);

    $extensions = array("jpeg", "jpg", "png");
    if (in_array($file_ext, $extensions) === false) {
        $errors[] = "Extension not allowed, please select jpeg ot png file";
    }
    if (empty($errors)) {
        $insert_product = mysqli_query($conn, "INSERT INTO products(name,price,image) VALUES('$product_Name','$product_Price','$file_name')");
        if ($insert_product == 1) {
            move_uploaded_file($file_tmp, UPLOAD_DIR."/$file_name");
            $productSaved = TRUE;
            $message[] = 'Product added successfully.';
            $product_Name = '';
            $product_Price = '';
        }else{
            $message[] = 'Error adding product to database';
        }
    } else {
        print_r($errors[0]);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=yes" />
    <meta charset="UTF-8" />
    <!-- The above 3 meta tags must come first in the head -->

    <title>Save product details</title>

    <script src="https://code.jquery.com/jquery-3.2.1.min.js" type="text/javascript"></script>
    <style type="text/css">
        body {
            padding: 30px;
        }

        .form-container {
            margin-left: 80px;
        }

        .form-container .messages {
            margin-bottom: 15px;
        }

        .form-container input[type="text"],
        .form-container input[type="number"] {
            display: block;
            margin-bottom: 15px;
            width: 150px;
        }

        .form-container input[type="file"] {
            margin-bottom: 15px;
        }

        .form-container label {
            display: inline-block;
            float: left;
            width: 100px;
        }

        .form-container button {
            display: block;
            padding: 5px 10px;
            background-color: #8daf15;
            color: #fff;
            border: none;
        }

        .form-container .link-to-product-details {
            margin-top: 20px;
            display: inline-block;
        }

        .message {
            background-color: var(--blue);
            position: sticky;
            top: 0;
            left: 0;
            z-index: 10000;
            border-radius: .5rem;
            background-color: var(--white);
            padding: 1.5rem 2rem;
            margin: 0 auto;
            max-width: 1200px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1.5rem;
        }
    </style>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>

<body>
    <?php
    if (isset($message)) {
        foreach ($message as $message) {
            echo '<div class="message"><span>' . $message . '</span> <i class="fas fa-times" onclick="this.parentElement.style.display = `none`;"></i> </div>';
        }
        ;
    }
    ;
    ?>
    <div class="form-container">
        <h2>Add a product</h2>

        <div class="messages">

        </div>

        <form action="" method="post" enctype="multipart/form-data">
            <label for="name">Name</label>
            <input type="text" id="name" name="name" value="<?php echo isset($product_Name) ? $product_Name : ''; ?>">

            <label for="price">Price</label>
            <input type="number" id="price" name="price" min="0"
                value="<?php echo isset($product_Price) ? $product_Price : ''; ?>">

            <label for="file">Image</label>
            <input type="file" id="file" name="file">

            <button type="submit" id="submit" name="submit" class="button">
                Submit
            </button>
        </form>
        <?php
            ?>
            <a href="display.php" class="link-to-product-details">
                See Products
            </a>
            <?php
        ?>
    </div>

</body>

</html>