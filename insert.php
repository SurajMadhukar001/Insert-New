<?php
include('db.php');  // Including the database connection

if (isset($_POST['insert_product'])) {
    // Getting the form inputs
    $product_title = $_POST['product_title'];
    $product_description = $_POST['product_description'];
    $product_keywords = $_POST['product_keywords'];
    $product_category = $_POST['product_categories'];  // Get category ID
    $product_brand = $_POST['product_brands'];  // Get brand ID
    $product_price = $_POST['product_price'];
    $product_status = 'true';

    // Accessing the uploaded images
    $product_image1 = $_FILES['product_image1']['name'];
    $product_image2 = $_FILES['product_image2']['name'];
    $product_image3 = $_FILES['product_image3']['name'];
    $product_img1 = $_FILES['product_image1']['tmp_name'];
    $product_img2 = $_FILES['product_image2']['tmp_name'];
    $product_img3 = $_FILES['product_image3']['tmp_name'];

    // Checking if all the required fields are filled
    if ($product_title == "" || $product_description == "" || $product_keywords == "" || $product_category == "" || $product_brand == "" || $product_price == "" || $product_image1 == "" || $product_image2 == "" || $product_image3 == "") {
        echo "<script>alert('Please fill all the available fields');</script>";
    } else {
        // Moving the uploaded images to the product-images directory
        move_uploaded_file($product_img1, "./product-images/$product_image1");
        move_uploaded_file($product_img2, "./product-images/$product_image2");
        move_uploaded_file($product_img3, "./product-images/$product_image3");

        // Prepared statement to prevent SQL injection
        $stmt = $connection->prepare("INSERT INTO `product` 
            (product_title, product_description, product_keywords, category_id, brands_id, product_image1, product_image2, product_image3, product_price, date, stamp) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)");
        $stmt->bind_param("ssssssssss", $product_title, $product_description, $product_keywords, $product_category, $product_brand, $product_image1, $product_image2, $product_image3, $product_price, $product_status);

        // Executing the query
        if ($stmt->execute()) {
            echo "<script>alert('Product has been inserted successfully');</script>";
        } else {
            echo "<script>alert('Error inserting product');</script>";
        }

        // Close the prepared statement
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Insert Product</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.0/css/all.min.css">
</head>
<body class="bg-warning">
    <div class="container mt-3">
        <h1 class="text-center">Insert Your Product</h1>
        <form action="" method="post" enctype="multipart/form-data">
            <div class="form-outline mb-4 w-50 m-auto">
                <label for="product_title" class="form-label">Product title</label>
                <input type="text" class="form-control" name="product_title" id="product_title" placeholder="Enter Product Title" autocomplete="off">
            </div>

            <div class="form-outline mb-4 w-50 m-auto">
                <label for="product_description" class="form-label">Product description</label>
                <input type="text" class="form-control" name="product_description" id="product_description" placeholder="Enter Product description" autocomplete="off">
            </div>

            <div class="form-outline mb-4 w-50 m-auto">
                <label for="product_keywords" class="form-label">Product Keywords</label>
                <input type="text" class="form-control" name="product_keywords" id="product_keywords" placeholder="Enter Your Product Keywords" autocomplete="off">
            </div>

            <div class="form-outline mb-4 w-50 m-auto">
                <select name="product_categories" id="product_categories" class="form-select">
                    <option value="">Select a Category</option>
                    <?php
                    // Fetching categories from the database
                    $selectingquery = "SELECT * FROM `category`";
                    $result = mysqli_query($connection, $selectingquery);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $category_title = $row['Name'];
                        $category_id = $row['Sn'];
                        echo "<option value='$category_id'>$category_title</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-outline mb-4 w-50 m-auto">
                <select name="product_brands" id="product_brands" class="form-select">
                    <option value="">Select a Brand</option>
                    <?php
                    // Fetching brands from the database
                    $selectingquery = "SELECT * FROM `iant`";
                    $result = mysqli_query($connection, $selectingquery);
                    while ($row = mysqli_fetch_assoc($result)) {
                        $brand_title = $row['Tittle'];
                        $brand_id = $row['Sn'];
                        echo "<option value='$brand_id'>$brand_title</option>";
                    }
                    ?>
                </select>
            </div>

            <div class="form-outline mb-4 w-50 m-auto">
                <label for="product_image1" class="form-label">Product Image 1</label>
                <input type="file" name="product_image1" class="form-control" id="product_image1" required="required">
            </div>

            <div class="form-outline mb-4 w-50 m-auto">
                <label for="product_image2" class="form-label">Product Image 2</label>
                <input type="file" name="product_image2" class="form-control" id="product_image2" required="required">
            </div>

            <div class="form-outline mb-4 w-50 m-auto">
                <label for="product_image3" class="form-label">Product Image 3</label>
                <input type="file" name="product_image3" class="form-control" id="product_image3" required="required">
            </div>

            <div class="form-outline mb-4 w-50 m-auto">
                <label for="product_price" class="form-label">Product Price</label>
                <input type="text" name="product_price" class="form-control" id="product_price" placeholder="Enter your price" autocomplete="off" required="required">
            </div>

            <div class="form-outline mb-4 w-50 m-auto">
                <input type="submit" name="insert_product" class="btn btn-primary" value="Insert Product">
            </div>
        </form>
    </div>
</body>
</html>
