<!DOCTYPE html>
<html lang="en">
<?php
if (!isset($_SESSION['username']) && !isset($_SESSION['id_login']) && !isset($_SESSION['idsesi'])) {
    redirect('Login');
} else {
    $this->load->model('ModelUsers');
    $menu_diakses = $this->uri->segment(0) . "/" . $this->uri->segment(1) . "/" . $this->uri->segment(2);
    $this->ModelUsers->save_riwayat($_SESSION['id_login'], date("Y-m-d h:i:s"), $menu_diakses);
}

?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="<?php echo base_url(); ?>desain/logopmi.png">
    <title><?= $this->core->NamaAPP() ?></title>
    <!-- This page CSS -->
    <!-- Bootstrap MDB -->
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.6.3/css/all.css">
    <!-- Bootstrap core CSS -->
    <!-- <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css" rel="stylesheet"> -->
    <!-- Material Design Bootstrap -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.6.1/css/mdb.min.css" rel="stylesheet">
    <!-- \\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\\ -->

    <link href="<?php echo base_url(); ?>desain/assets/node_modules/select2/dist/css/select2.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo base_url(); ?>desain/assets/node_modules/morrisjs/morris.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>desain/dist/css/style.min.css" rel="stylesheet">
    <!-- Dashboard 31 Page CSS -->
    <link href="<?php echo base_url(); ?>desain/dist/css/pages/dashboard3.css" rel="stylesheet">

    <link href="<?php echo base_url(); ?>desain/dist/css/pages/floating-label.css" rel="stylesheet">

    <link href="<?php echo base_url(); ?>desain/assets/node_modules/icheck/skins/all.css" rel="stylesheet">

    <link href="<?php echo base_url(); ?>desain/dist/css/pages/form-icheck.css" rel="stylesheet">

    <link href="<?php echo base_url(); ?>desain/dist/css/pages/file-upload.css" rel="stylesheet">

    <link href="<?php echo base_url(); ?>desain/assets/node_modules/toast-master/css/jquery.toast.css" rel="stylesheet">

    <link href="<?php echo base_url(); ?>desain/dist/css/pages/other-pages.css" rel="stylesheet">

    <!-- Custom CSS -->
    <link href="<?php echo base_url() ?>desain/dist/css/style.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo base_url() ?>desain/dist/css/style2.css" rel="stylesheet">

    <link href="<?php echo base_url(); ?>desain/MyCSS.css" rel="stylesheet">

    <link href="<?php echo base_url(); ?>desain/assets/node_modules/bootstrap-select/bootstrap-select.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>desain/assets/node_modules/multiselect/css/multi-select.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>desain/assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>desain/assets/node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.min.css" rel="stylesheet" />
    <link href="<?php echo base_url(); ?>desain/dist/css/pages/other-pages.css" rel="stylesheet">

    <link href="<?php echo base_url(); ?>desain/assets/node_modules/bootstrap-table/dist/bootstrap-table.min.css" rel="stylesheet" type="text/css" />

    <link href="<?php echo base_url(); ?>desain/dist/css/pages/ecommerce.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>desain/step.css" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo base_url(); ?>desain/assets/node_modules/dropify/dist/css/dropify.min.css">

    <link href="<?php echo base_url(); ?>desain/assets/node_modules/bootstrap-material-datetimepicker/css/bootstrap-material-datetimepicker.css" rel="stylesheet">
    <!-- Page plugins css -->
    <link href="<?php echo base_url(); ?>desain/assets/node_modules/clockpicker/dist/jquery-clockpicker.min.css" rel="stylesheet">
    <!-- Color picker plugins css -->
    <link href="<?php echo base_url(); ?>desain/assets/node_modules/jquery-asColorPicker-master/css/asColorPicker.css" rel="stylesheet">
    <!-- Date picker plugins css -->
    <link href="<?php echo base_url(); ?>desain/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.css" rel="stylesheet" type="text/css" />
    <!-- Daterange picker plugins css -->
    <link href="<?php echo base_url(); ?>desain/assets/node_modules/timepicker/bootstrap-timepicker.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>desain/assets/node_modules/bootstrap-daterangepicker/daterangepicker.css" rel="stylesheet">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/jquery/jquery-3.2.1.min.js"></script>
    <script src="https://js.pusher.com/4.3/pusher.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-steps/1.1.0/jquery.steps.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAT7nTQXQ5P2XLjVN7wFvP77m_pDEWXYi4&sensor=false"></script>
</head>

<body class="skin-blue fixed-layout">
    <!-- ============================================================== -->
    <!-- Preloader - style you can find in spinners.css -->
    <!-- ============================================================== -->
    <div class="preloader">
        <div class="loader">
            <!-- <div class="loader__figure"></div> -->
            <p class="loader__label">E - Presensi</p>
        </div>
    </div>
    <!-- ============================================================== -->
    <!-- Main wrapper - style you can find in pages.scss -->
    <!-- ============================================================== -->
    <div id="main-wrapper">
        <!-- ============================================================== -->
        <!-- Topbar header - style you can find in pages.scss -->
        <!-- ============================================================== -->
        <header class="topbar">
            <nav class="navbar top-navbar navbar-expand-md navbar-dark">
                <!-- ============================================================== -->
                <!-- Logo -->
                <!-- ============================================================== -->
                <div class="navbar-header">
                    <a class="navbar-brand" href="<?php echo base_url() ?>">
                        <!-- Logo icon --><b>
                            <!--You can put here icon as well // <i class="wi wi-sunset"></i> //-->
                            <!-- Dark Logo icon -->
                            <!-- <?php echo base_url(); ?>desain/logo.png -->
                            <!-- <img src="<?php echo base_url(); ?>desain/assets/images/logo-icon.png" alt="homepage" class="dark-logo" /> -->
                            <!-- <img src="<?php echo base_url(); ?>desain/logo.png" alt="homepage" class="dark-logo" style="max-width: 70px;" /> -->
                            <!-- Light Logo icon -->
                            <!-- <img src="<?php echo base_url(); ?>desain/assets/images/logo-light-icon.png" alt="homepage" class="light-logo" /> -->
                            <!-- <img src="<?php echo base_url(); ?>desain/logo.png" alt="homepage" class="light-logo" style="max-width: 70px;" /> -->
                        </b>
                        <!--End Logo icon -->
                        <!-- Logo text --><span class="text-white">
                            <!-- dark Logo text -->
                            <!-- <img src="<?php echo base_url(); ?>desain/assets/images/logo-text.png" alt="homepage" class="dark-logo" /> -->
                            <!-- Light Logo text -->
                            <!-- <img src="<?php echo base_url(); ?>desain/assets/images/logo-light-text.png" class="light-logo" alt="homepage" /> -->
                            <b style="font-size: 15px;"><?= $this->core->NamaAPP() ?></b>
                        </span> </a>
                </div>
                <!-- ============================================================== -->
                <!-- End Logo -->
                <!-- ============================================================== -->
                <div class="navbar-collapse blue-gradient">
                    <!-- ============================================================== -->
                    <!-- toggle and nav items -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav mr-auto">
                        <!-- This is  -->
                        <li class="nav-item"> <a class="nav-link nav-toggler d-block d-sm-none waves-effect waves-dark" href="javascript:void(0)"><i class="ti-menu"></i></a> </li>
                        <li class="nav-item"> <a class="nav-link sidebartoggler d-none d-lg-block d-md-block waves-effect waves-dark" href="javascript:void(0)"><i class="icon-menu"></i></a> </li>
                        <!-- ============================================================== -->
                        <!-- Search -->
                        <!-- ============================================================== -->
                        <li class="nav-item">
                            <form class="app-search d-none d-md-block d-lg-block">
                                <input type="text" readonly class="form-control" placeholder="Search & enter" value="<?php echo @$_SESSION['outlet'] ?>" readonly>
                            </form>
                        </li>
                    </ul>
                    <!-- ============================================================== -->
                    <!-- User profile and search -->
                    <!-- ============================================================== -->
                    <ul class="navbar-nav my-lg-0">
                        <!-- ============================================================== -->
                        <!-- Comment -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <!-- <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="ti-email"></i>
                                <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                            </a> -->
                            <div class="dropdown-menu dropdown-menu-right mailbox animated bounceInDown">
                                <ul>
                                    <li>
                                        <div class="drop-title">Notifications</div>
                                    </li>
                                    <li>
                                        <div class="message-center">
                                            <!-- Message -->
                                            <a href="javascript:void(0)">
                                                <div class="btn btn-danger btn-circle"><i class="fa fa-link"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Luanch Admin</h5> <span class="mail-desc">Just see the my new admin!</span> <span class="time">9:30 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)">
                                                <div class="btn btn-success btn-circle"><i class="ti-calendar"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Event today</h5> <span class="mail-desc">Just a reminder that you have event</span> <span class="time">9:10 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)">
                                                <div class="btn btn-info btn-circle"><i class="ti-settings"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Settings</h5> <span class="mail-desc">You can customize this template as you want</span> <span class="time">9:08 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)">
                                                <div class="btn btn-primary btn-circle"><i class="ti-user"></i></div>
                                                <div class="mail-contnet">
                                                    <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center link" href="javascript:void(0);"> <strong>Check all notifications</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- End Comment -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- Messages -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown">
                            <!-- <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" id="2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"> <i class="icon-note"></i>
                                <div class="notify"> <span class="heartbit"></span> <span class="point"></span> </div>
                            </a> -->
                            <div class="dropdown-menu mailbox dropdown-menu-right animated bounceInDown" aria-labelledby="2">
                                <ul>
                                    <li>
                                        <div class="drop-title">You have 4 new messages</div>
                                    </li>
                                    <li>
                                        <div class="message-center">
                                            <!-- Message -->
                                            <a href="javascript:void(0)">
                                                <div class="user-img"> <img src="<?php echo base_url(); ?>desain/assets/images/users/1.jpg" alt="user" class="img-circle"> <span class="profile-status online pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:30 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)">
                                                <div class="user-img"> <img src="<?php echo base_url(); ?>desain/assets/images/users/2.jpg" alt="user" class="img-circle"> <span class="profile-status busy pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>Sonu Nigam</h5> <span class="mail-desc">I've sung a song! See you at</span> <span class="time">9:10 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)">
                                                <div class="user-img"> <img src="<?php echo base_url(); ?>desain/assets/images/users/3.jpg" alt="user" class="img-circle"> <span class="profile-status away pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>Arijit Sinh</h5> <span class="mail-desc">I am a singer!</span> <span class="time">9:08 AM</span>
                                                </div>
                                            </a>
                                            <!-- Message -->
                                            <a href="javascript:void(0)">
                                                <div class="user-img"> <img src="<?php echo base_url(); ?>desain/assets/images/users/4.jpg" alt="user" class="img-circle"> <span class="profile-status offline pull-right"></span> </div>
                                                <div class="mail-contnet">
                                                    <h5>Pavan kumar</h5> <span class="mail-desc">Just see the my admin!</span> <span class="time">9:02 AM</span>
                                                </div>
                                            </a>
                                        </div>
                                    </li>
                                    <li>
                                        <a class="nav-link text-center link" href="javascript:void(0);"> <strong>See all e-Mails</strong> <i class="fa fa-angle-right"></i> </a>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- End Messages -->
                        <!-- ============================================================== -->
                        <!-- ============================================================== -->
                        <!-- mega menu -->
                        <!-- ============================================================== -->
                        <li class="nav-item dropdown mega-dropdown">
                            <!-- <a class="nav-link dropdown-toggle waves-effect waves-dark" href="" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="ti-layout-width-default"></i></a> -->
                            <div class="dropdown-menu animated bounceInDown">
                                <ul class="mega-dropdown-menu row">
                                    <li class="col-lg-3 col-xlg-2 m-b-30">
                                        <h4 class="m-b-20">CAROUSEL</h4>
                                        <!-- CAROUSEL -->
                                        <div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
                                            <div class="carousel-inner" role="listbox">
                                                <div class="carousel-item active">
                                                    <div class="container"> <img class="d-block img-fluid" src="<?php echo base_url(); ?>desain/assets/images/big/img1.jpg" alt="First slide"></div>
                                                </div>
                                                <div class="carousel-item">
                                                    <div class="container"><img class="d-block img-fluid" src="<?php echo base_url(); ?>desain/assets/images/big/img2.jpg" alt="Second slide"></div>
                                                </div>
                                                <div class="carousel-item">
                                                    <div class="container"><img class="d-block img-fluid" src="<?php echo base_url(); ?>desain/assets/images/big/img3.jpg" alt="Third slide"></div>
                                                </div>
                                            </div>
                                            <a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev"> <span class="carousel-control-prev-icon" aria-hidden="true"></span> <span class="sr-only">Previous</span> </a>
                                            <a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next"> <span class="carousel-control-next-icon" aria-hidden="true"></span> <span class="sr-only">Next</span> </a>
                                        </div>
                                        <!-- End CAROUSEL -->
                                    </li>
                                    <li class="col-lg-3 m-b-30">
                                        <h4 class="m-b-20">ACCORDION</h4>
                                        <!-- Accordian -->
                                        <div id="accordion" class="nav-accordion" role="tablist" aria-multiselectable="true">
                                            <div class="card">
                                                <div class="card-header" role="tab" id="headingOne">
                                                    <h5 class="mb-0">
                                                        <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                            Collapsible Group Item #1
                                                        </a>
                                                    </h5>
                                                </div>
                                                <div id="collapseOne" class="collapse show" role="tabpanel" aria-labelledby="headingOne">
                                                    <div class="card-body"> Anim pariatur cliche reprehenderit, enim eiusmod high. </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" role="tab" id="headingTwo">
                                                    <h5 class="mb-0">
                                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                                            Collapsible Group Item #2
                                                        </a>
                                                    </h5>
                                                </div>
                                                <div id="collapseTwo" class="collapse" role="tabpanel" aria-labelledby="headingTwo">
                                                    <div class="card-body"> Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. </div>
                                                </div>
                                            </div>
                                            <div class="card">
                                                <div class="card-header" role="tab" id="headingThree">
                                                    <h5 class="mb-0">
                                                        <a class="collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                                            Collapsible Group Item #3
                                                        </a>
                                                    </h5>
                                                </div>
                                                <div id="collapseThree" class="collapse" role="tabpanel" aria-labelledby="headingThree">
                                                    <div class="card-body"> Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. </div>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                    <li class="col-lg-3  m-b-30">
                                        <h4 class="m-b-20">CONTACT US</h4>
                                        <!-- Contact -->
                                        <form>
                                            <div class="form-group">
                                                <input type="text" class="form-control" id="exampleInputname1" placeholder="Enter Name">
                                            </div>
                                            <div class="form-group">
                                                <input type="email" class="form-control" placeholder="Enter email">
                                            </div>
                                            <div class="form-group">
                                                <textarea class="form-control" id="exampleTextarea" rows="3" placeholder="Message"></textarea>
                                            </div>
                                            <button type="submit" class="btn btn-info">Submit</button>
                                        </form>
                                    </li>
                                    <li class="col-lg-3 col-xlg-4 m-b-30">
                                        <h4 class="m-b-20">List style</h4>
                                        <!-- List style -->
                                        <ul class="list-style-none">
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> You can give link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> Give link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> Another Give link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> Forth link</a></li>
                                            <li><a href="javascript:void(0)"><i class="fa fa-check text-success"></i> Another fifth link</a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                        </li>
                        <!-- ============================================================== -->
                        <!-- End mega menu -->
                        <!-- ============================================================== -->
                        <li class="nav-item right-side-toggle"> <a class="nav-link  waves-effect waves-light" href="javascript:void(0)"><i class="ti-settings"></i></a></li>
                    </ul>
                </div>
            </nav>
        </header>
        <!-- ============================================================== -->
        <!-- End Topbar header -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <aside class="left-sidebar">
            <!-- Sidebar scroll-->
            <div class="scroll-sidebar">
                <!-- User Profile-->
                <div class="user-profile">
                    <div class="user-pro-body">
                        <div class="text-center"><img src="<?php echo base_url(); ?>desain/logopmi.png" alt="user-img" class="img-circle" style="width: 100px !important;"></div>
                        <div class="dropdown text-center">
                            <a href="javascript:void(0)" class="dropdown-toggle u-dropdown link hide-menu" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"><?php echo $_SESSION['nama'] ?><span class="caret"></span></a>
                            <div class="dropdown-menu animated flipInY">
                                <!-- text-->
                                <!-- <a href="javascript:void(0)" class="dropdown-item"><i class="ti-user"></i> My Profile</a> -->
                                <!-- text-->
                                <!-- <a href="javascript:void(0)" class="dropdown-item"><i class="ti-wallet"></i> My Balance</a> -->
                                <!-- text-->
                                <!-- <a href="javascript:void(0)" class="dropdown-item"><i class="ti-email"></i> Inbox</a> -->
                                <!-- text-->
                                <!-- <div class="dropdown-divider"></div> -->
                                <!-- text-->
                                <!-- <a href="javascript:void(0)" class="dropdown-item"><i class="ti-settings"></i> Account Setting</a> -->
                                <!-- text-->
                                <div class="dropdown-divider"></div>
                                <!-- text-->
                                <a href="<?php echo base_url() ?>Login/logout" class="dropdown-item"><i class="fa fa-power-off"></i> Logout</a>
                                <!-- text-->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Sidebar navigation-->
                <nav class="sidebar-nav">
                    <?php
                    $this->load->view('Core/menu');
                    ?>
                </nav>
                <!-- End Sidebar navigation -->
            </div>
            <!-- End Sidebar scroll-->
        </aside>
        <!-- ============================================================== -->
        <!-- End Left Sidebar - style you can find in sidebar.scss  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- Page wrapper  -->
        <!-- ============================================================== -->
        <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Container fluid  -->
            <!-- ============================================================== -->
            <div class="container-fluid">
                <!-- ============================================================== -->
                <!-- Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <div class="row page-titles">
                    <div class="col-md-5 align-self-center">
                        <h4 class="text-themecolor"><?php echo $title ?></h4>
                    </div>
                    <div class="col-md-7 align-self-center text-right">
                        <div class="d-flex justify-content-end align-items-center">
                            <ol class="breadcrumb">
                                <?php if ($this->uri->segment(1)): ?>
                                    <li class="breadcrumb-item
                                <?php if (!$this->uri->segment(2)): ?>
                                  active
                                <?php endif; ?>
                                "><a href="javascript:void(0)"><?php echo $this->uri->segment(1); ?></a></li>
                                <?php endif; ?>

                                <?php if ($this->uri->segment(2)): ?>
                                    <li class="breadcrumb-item
                                <?php if (!$this->uri->segment(3)): ?>
                                  active
                                <?php endif; ?>
                                "><?php echo $this->uri->segment(2); ?></li>
                                <?php endif; ?>

                                <?php if ($this->uri->segment(3)): ?>
                                    <li class="breadcrumb-item active"><?php echo $this->uri->segment(3); ?></li>
                                <?php endif; ?>


                            </ol>
                            <?php echo @$btn_title; ?>
                            <!-- <button type="button" class="btn btn-info d-none d-lg-block m-l-15"><i class="fa fa-plus-circle"></i> Create New</button> -->
                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Bread crumb and right sidebar toggle -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Info box -->
                <!-- ============================================================== -->
                <?php echo @$notif ?>

                <?php $this->load->view(@$body); ?>

                <!-- ============================================================== -->
                <!-- End Page Content -->
                <!-- ============================================================== -->
                <!-- ============================================================== -->
                <!-- Right sidebar -->
                <!-- ============================================================== -->
                <!-- .right-sidebar -->
                <div class="right-sidebar">
                    <div class="slimscrollright">
                        <div class="rpanel-title"> Service Panel <span><i class="ti-close right-side-toggle"></i></span> </div>
                        <div class="r-panel-body">
                            <ul id="themecolors" class="m-t-20">
                                <li><b>With Light sidebar</b></li>
                                <li><a href="javascript:void(0)" data-skin="skin-default" class="default-theme working">1</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-green" class="green-theme">2</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-red" class="red-theme">3</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-blue" class="blue-theme">4</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-purple" class="purple-theme">5</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-megna" class="megna-theme">6</a></li>
                                <li class="d-block m-t-30"><b>With Dark sidebar</b></li>
                                <li><a href="javascript:void(0)" data-skin="skin-default-dark" class="default-dark-theme ">7</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-green-dark" class="green-dark-theme">8</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-red-dark" class="red-dark-theme">9</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-blue-dark" class="blue-dark-theme">10</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-purple-dark" class="purple-dark-theme">11</a></li>
                                <li><a href="javascript:void(0)" data-skin="skin-megna-dark" class="megna-dark-theme ">12</a></li>
                            </ul>

                        </div>
                    </div>
                </div>
                <!-- ============================================================== -->
                <!-- End Right sidebar -->
                <!-- ============================================================== -->
            </div>
            <!-- ============================================================== -->
            <!-- End Container fluid  -->
            <!-- ============================================================== -->
        </div>
        <!-- ============================================================== -->
        <!-- End Page wrapper  -->
        <!-- ============================================================== -->
        <!-- ============================================================== -->
        <!-- footer -->
        <!-- ============================================================== -->
        <footer class="footer">
            @2025 <?= $this->core->NamaAPP() ?>. Allright reserved.
            <!-- <img src="<?php echo base_url(); ?>desain/logo.png" alt="user-img" style="width: 30px !important;"> -->
        </footer>
        <!-- ============================================================== -->
        <!-- End footer -->
        <!-- ============================================================== -->
    </div>
    <!-- ============================================================== -->
    <!-- End Wrapper -->
    <!-- ============================================================== -->
    <!-- ============================================================== -->
    <!-- All Jquery -->
    <!-- ============================================================== -->
    <!-- <script src="<?php echo base_url(); ?>desain/assets/node_modules/jquery/jquery-3.2.1.min.js"></script> -->
    <!-- Bootstrap popper Core JavaScript -->
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/popper/popper.min.js"></script>
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/bootstrap/dist/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="<?php echo base_url(); ?>desain/dist/js/perfect-scrollbar.jquery.min.js"></script>
    <!--Wave Effects -->
    <script src="<?php echo base_url(); ?>desain/dist/js/waves.js"></script>
    <!--Menu sidebar -->
    <script src="<?php echo base_url(); ?>desain/dist/js/sidebarmenu.js"></script>
    <!-- ============================================================== -->
    <!-- This page plugins -->
    <!-- ============================================================== -->
    <!--morris JavaScript -->
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/raphael/raphael-min.js"></script>
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/morrisjs/morris.min.js"></script>
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/jquery-sparkline/jquery.sparkline.min.js"></script>
    <!-- Popup message jquery -->
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/toast-master/js/jquery.toast.js"></script>
    <!-- jQuery peity -->
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/peity/jquery.peity.min.js"></script>
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/peity/jquery.peity.init.js"></script>
    <!-- <script src="<?php echo base_url(); ?>desain/dist/js/dashboard3.js"></script> -->

    <!--stickey kit -->
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/sticky-kit-master/dist/sticky-kit.min.js"></script>
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/sparkline/jquery.sparkline.min.js"></script>
    <!-- This is data table -->
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/datatables/jquery.dataTables.min.js"></script>
    <!-- icheck -->
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/icheck/icheck.min.js"></script>
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/icheck/icheck.init.js"></script>
    <script src="<?php echo base_url(); ?>desain/dist/js/pages/jasny-bootstrap.js"></script>
    <!-- start - This is for export functionality only -->
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.flash.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/2.5.0/jszip.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/pdfmake.min.js"></script>
    <script src="https://cdn.rawgit.com/bpampuch/pdfmake/0.1.18/build/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/1.2.2/js/buttons.print.min.js"></script>
    <!-- select2 -->
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url() ?>desain/dist/js/custom.min.js"></script>
    <script src="<?php echo base_url(); ?>desain/assets/jquery.mask.js"></script>
    <!-- <script src="<?php echo base_url(); ?>desain/dist/js/pages/mask.js"></script> -->

    <script src="<?php echo base_url(); ?>desain/assets/node_modules/dropify/dist/js/dropify.min.js"></script>

    <!-- Editable -->
    <script type="text/javascript" src="<?php echo base_url(); ?>desain/assets/node_modules/x-editable/dist/bootstrap3-editable/js/bootstrap-editable.js"></script>

    <!-- end - This is for export functionality only -->
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/switchery/dist/switchery.min.js"></script>
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/select2/dist/js/select2.full.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/bootstrap-select/bootstrap-select.min.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js"></script>
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/bootstrap-touchspin/dist/jquery.bootstrap-touchspin.js" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>desain/assets/node_modules/multiselect/js/jquery.multi-select.js"></script>

    <!-- Plugin JavaScript -->
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/moment/moment.js"></script>
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/bootstrap-material-datetimepicker/js/bootstrap-material-datetimepicker.js"></script>
    <!-- Clock Plugin JavaScript -->
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/clockpicker/dist/jquery-clockpicker.min.js"></script>
    <!-- Color Picker Plugin JavaScript -->
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/jquery-asColorPicker-master/libs/jquery-asColor.js"></script>
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/jquery-asColorPicker-master/libs/jquery-asGradient.js"></script>
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/jquery-asColorPicker-master/dist/jquery-asColorPicker.min.js"></script>
    <!-- Date Picker Plugin JavaScript -->
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/bootstrap-datepicker/bootstrap-datepicker.min.js"></script>
    <!-- Date range Plugin JavaScript -->
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/timepicker/bootstrap-timepicker.min.js"></script>
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/bootstrap-daterangepicker/daterangepicker.js"></script>



    <!-- bootstrap -->
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/bootstrap-table/dist/bootstrap-table.min.js"></script>
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/bootstrap-table/dist/bootstrap-table.ints.js"></script>
    <!-- Chart JS -->
    <script src="<?php echo base_url(); ?>desain/assets/node_modules/echarts/echarts-all.js"></script>
    <?php echo @$this->session->flashdata('notifJS') ?>
    <script src="<?php echo base_url(); ?>desain/MyJS.js"></script>
    <style>
        select {
            display: inline !important;
        }
    </style>
</body>

</html>