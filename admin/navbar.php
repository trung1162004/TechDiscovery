        <!-- Content Start -->
        <div class="content">
            <!-- Navbar Start -->
            <nav class="navbar navbar-expand bg-secondary navbar-dark sticky-top px-4 py-0">
                <a href="index.html" class="navbar-brand d-flex d-lg-none me-4">
                    <h2 class="text-primary mb-0"><i class="fa fa-user-edit"></i></h2>
                </a>
                <a href="#" class="sidebar-toggler flex-shrink-0">
                    <i class="fa fa-bars"></i>
                </a>
                <div class="navbar-nav align-items-center ms-auto">
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-envelope me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Message</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="img/rose2.jpeg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">We send you a message</h6>
                                        <small>1 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="img/rose2.jpeg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">We send you a message</h6>
                                        <small>5 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle" src="img/rose2.jpeg" alt="" style="width: 40px; height: 40px;">
                                    <div class="ms-2">
                                        <h6 class="fw-normal mb-0">We send you a message</h6>
                                        <small>15 minutes ago</small>
                                    </div>
                                </div>
                            </a>
                            <hr class="dropdown-divider">
                            <a href="#" class="dropdown-item text-center">See all message</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <i class="fa fa-bell me-lg-2"></i>
                            <span class="d-none d-lg-inline-flex">Notification</span>
                            <?php
                            $notificationTypes = ["new_order_notification", "cancel_order_notification", "complete_order_notification", "return_order_notification"];
                            $hasNotifications = false;

                            foreach ($notificationTypes as $type) {
                                if (isset($_SESSION[$type])) {
                                    $hasNotifications = true;
                                }
                            }

                            // Thêm class "red-dot" nếu có thông báo
                            $dotClass = $hasNotifications ? 'red-dot' : '';
                            ?>
                            <span class="<?php echo $dotClass; ?>"></span>
                        </a>
                        <div id="notification-dropdown" class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0">
                            <?php
                            if ($hasNotifications) {
                                foreach ($notificationTypes as $type) {
                                    if (isset($_SESSION[$type])) {
                                        $notificationData = $_SESSION[$type];
                                        echo '<a href="' . $notificationData["url"] . '" class="dropdown-item">';
                                        echo '<h6 class="fw-normal mb-0">' . $notificationData["message"] . '</h6>';
                                        echo '<small>' . $notificationData["time"] . '</small>';
                                        echo '</a>';
                                        unset($_SESSION[$type]);
                                    }
                                }
                            } else {
                                echo '<p class="dropdown-item text-center">No new notifications.</p>';
                            }
                            ?>
                            <a href="#" class="dropdown-item text-center">See all notifications</a>
                        </div>
                    </div>
                    <div class="nav-item dropdown">
                        <a href="#" class="nav-link dropdown-toggle" data-bs-toggle="dropdown">
                            <img class="rounded-circle me-lg-2" src="../image/logo.png" alt="" style="width: 40px; height: 40px;">
                            <span class="d-none d-lg-inline-flex">TechDiscovery</span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-end bg-secondary border-0 rounded-0 rounded-bottom m-0">
                            <a href="admin_profile.php" class="dropdown-item">My Profile</a>
                            <a href="#" class="dropdown-item">Settings</a>
                            <a href="../logout.php" class="dropdown-item">Log Out</a>
                        </div>
                    </div>
                </div>
            </nav>
            <!-- Navbar End -->