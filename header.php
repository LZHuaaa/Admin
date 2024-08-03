<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_title ?? 'Untitled' ?></title>
    <link rel="stylesheet" href="css/header.css">

    <!--use google font -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="js/header.js"></script>

</head>

<body>
    <div class="container">
        <aside>
            <div class="top">
                <div class="logo">
                    <img src="images/logo.png" alt="">
                    <h2>EGA<span class="danger">TOR</span></h2>
                </div>

                <div class="close" id="close-btn">
                    <span class="material-icons-sharp">close</span>
                </div>
            </div>

            <div class="sidebar">
                <a href="#">
                    <span class="material-icons-sharp">grid_view</span>
                    <h3>Dashboard</h3>
                </a>
                
                <a href="#">
                    <span class="material-icons-sharp">person</span>
                    <h3>Members</h3>
                </a>

                <a href="#">
                    <span class="material-icons-sharp">receipt_long</span>
                    <h3>Orders</h3>
                </a>

                <a href="#" class="active">
                    <span class="material-icons-sharp">insights</span>
                    <h3>Analytics</h3>
                </a>

          

                <a href="#">
                    <span class="material-icons-sharp">inventory</span>
                    <h3>Products</h3>
                </a>

                <a href="#">
                    <span class="material-icons-sharp">report_gmailerrorred</span>
                    <h3>Reports</h3>
                </a>

                <a href="#">
                    <span class="material-icons-sharp">settings</span>
                    <h3>Settings</h3>
                </a>

                <a href="#">
                    <span class="material-icons-sharp">add</span>
                    <h3>Add Product</h3>
                </a>

                <a href="#">
                    <span class="material-icons-sharp">logout</span>
                    <h3>Logout</h3>
                </a>
            </div>
        </aside>

        

        <main>
        <h1><?= $_title ?? 'Untitled' ?></h1>

        