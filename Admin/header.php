<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $_title ?? 'Untitled' ?></title>
    <link rel="stylesheet" href="../css/header.css">
    

    <!--use google font -->
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Sharp" rel="stylesheet" />

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="../js/header.js"></script>
</head>

<body>
    <div class="container">
        <aside>
            <div class="top">
                <div class="logo">
                    <img src="../assests/logo/logo2.png" alt="">
                    <!--<h2>HAPPY FITNESS</h2>-->
                </div>
            </div>

            <div class="top">
                <div class="logo1">
                    <img src="../images/profile-1.jpg" alt="">
                    <h2>Hi, Lee Zhi Hua</h2>
                </div>
            </div>

            <div class="sidebar">


                <a href="index.php">
                    <span class="material-icons-sharp">grid_view</span>
                    <h3>Dashboard</h3>
                </a>

                <a href="admin.php">
                    <span class="material-icons-sharp">
                        admin_panel_settings
                    </span>
                    <h3>Admin</h3>
                </a>

                <a href="member.php">
                    <span class="material-icons-sharp">person</span>
                    <h3>Member</h3>
                </a>

                <a href="order.php">
                    <span class="material-icons-sharp">receipt_long</span>
                    <h3>Orders</h3>
                </a>

                <a href="category.php">
                    <span class="material-icons-sharp">category</span>
                    <h3>Category</h3>
                </a>


                <a href="product.php">
                    <span class="material-icons-sharp">
                        fitness_center
                    </span>
                    <h3>Products</h3>
                </a>

                <a href="promotion.php">
                    <span class="material-icons-sharp">
                        discount
                    </span>
                    <h3>Promotions</h3>
                </a>



                <a href="productReview.php">
                    <span class="material-icons-sharp">
                        stars
                    </span>
                    <h3>Reviews</h3>
                </a>


                <a href="batchInsertionForm.php">
                    <span class="material-icons-sharp">
                        add_circle_outline
                    </span>
                    <h3>Batch Adding</h3>
                </a>

                <a href="#">
                    <span class="material-icons-sharp">logout</span>
                    <h3>Logout</h3>
                </a>



                <a href="#">

                </a>



            </div>
        </aside>



        <main>
            <div class="header-container">
                <h1 class="page-header"><?= $_title ?? 'Untitled' ?></h1>
                <br>




                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        const links = document.querySelectorAll('aside .sidebar a');

                        const activeLink = localStorage.getItem('activeLink');

                        if (activeLink) {
                            document.querySelector(`aside .sidebar a[href="${activeLink}"]`).classList.add('active');
                        }

                        links.forEach(link => {
                            link.addEventListener('click', function() {

                                links.forEach(l => l.classList.remove('active'));

                                this.classList.add('active');

                                localStorage.setItem('activeLink', this.getAttribute('href'));
                            });
                        });
                    });
                </script>