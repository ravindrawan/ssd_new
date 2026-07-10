<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Department of Social Services - NWP | Home</title>
    <script>
        window.onerror = function(msg, url, line, col, error) {
            var formData = new FormData();
            formData.append('error', msg);
            formData.append('url', url);
            formData.append('line', line);
            formData.append('col', col);
            fetch('log_error.php', {
                method: 'POST',
                body: formData
            });
            return false;
        };
    </script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&family=Noto+Sans+Sinhala:wght@400;600&family=Times+New+Roman&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="styles.css?v=9">
</head>
<body>

    <!-- Top utility bar: Lang, Search, Login -->
    <div class="top-utility-bar">
        <div class="container utility-container">
            <div class="lang-selector" id="langSwitcher">
                <a href="#" class="active" data-lang="en">English</a> | 
                <a href="#" data-lang="si">සිංහල</a> | 
                <a href="#" data-lang="ta">தமிழ்</a>
            </div>
            
            <div class="utility-right">
                <div class="search-bar">
                    <input type="text" placeholder="Search ..." data-i18n-placeholder="placeholder_search">
                    <button><i class="fas fa-search"></i></button>
                </div>
                <a href="#" class="member-login-btn" id="loginBtn"><i class="fas fa-user-lock"></i><span data-i18n="btn_login"> Members Login</span></a>
            </div>
        </div>
    </div>

    <!-- Main Branding Header -->
    <header class="branding-header">
        <div class="container branding-container" onclick="window.location.href='index.php'" style="cursor:pointer;">
            <div class="all-logos">
                <img id="nationalLogo" src="https://upload.wikimedia.org/wikipedia/commons/thumb/5/5f/Emblem_of_Sri_Lanka.svg/200px-Emblem_of_Sri_Lanka.svg.png" alt="National Logo" class="emblem" onerror="this.src='logo2.jpg'">
                <img id="provincialLogo" src="Nwp_sri_lanka.png" alt="Provincial Logo" class="emblem" onerror="this.src='logo2.jpg'">
            </div>
            
            <div class="department-titles">
                <h1 class="eng-title" id="headerTitleEn">DEPARTMENT OF SOCIAL SERVICES - NWP</h1>
                <h2 class="sin-title" id="headerTitleSi">වයඹ පළාත් සමාජ සේවා දෙපාර්තමේන්තුව</h2>
                <h3 class="tam-title" id="headerTitleTa">வடமேல் மாகாண சமூக சேவைகள் திணைக்களம்</h3>
            </div>
        </div>
    </header>

    <!-- Horizontal Navigation Panel -->
    <nav class="horizontal-nav">
        <div class="container">
            <div class="hamburger">
                <i class="fas fa-bars"></i> Menu
            </div>
            <ul class="nav-menu">
                <li><a href="index.php" data-i18n="nav_home">Home</a></li>
                <li class="dropdown">
                    <a href="#"><span data-i18n="nav_about">About Us</span> <i class="fas fa-caret-down"></i></a>
                    <ul class="dropdown-content">
                        <li><a href="#" onclick="openAboutUsTab('overview')" data-i18n="nav_about_overview">Overview / Description</a></li>
                        <li><a href="#" onclick="openAboutUsTab('orgchart')" data-i18n="nav_about_orgchart">Organization Chart</a></li>
                        <li><a href="#" onclick="openAboutUsTab('objectives')" data-i18n="nav_about_objectives">Purpose & Objectives</a></li>
                        <li><a href="#" onclick="openAboutUsTab('achievements')" data-i18n="nav_about_achievements">Achievements</a></li>
                        <li><a href="#" onclick="openAboutUsTab('citizen')" data-i18n="nav_about_citizen">Citizen's Charter</a></li>
                        <li><a href="#" onclick="openAboutUsTab('staff')" data-i18n="nav_about_staff">Staff Details</a></li>
                    </ul>
                </li>
                <li><a href="#" onclick="openServicesTab()" data-i18n="nav_services">Services</a></li>
                <li><a href="#" onclick="openProcurementsModal()" data-i18n="nav_procurement">Procurement Notice</a></li>
                <li class="dropdown">
                    <a href="#"><span data-i18n="nav_downloads">Downloads</span> <i class="fas fa-caret-down"></i></a>
                    <ul class="dropdown-content">
                        <li><a href="#" onclick="openDownloadsTab('formats')" data-i18n="dl_formats">Application Forms</a></li>
                        <li><a href="#" onclick="openDownloadsTab('circulars')" data-i18n="dl_circulars">Department Circulars</a></li>
                        <li><a href="#" onclick="openDownloadsTab('rates')" data-i18n="dl_rates">Welfare Guidelines</a></li>
                    </ul>
                </li>
                <li><a href="#" onclick="openGalleryModal()" data-i18n="nav_gallery">Gallery</a></li>
                <li><a href="#contact" data-i18n="nav_contact">Contact Us</a></li>
            </ul>
        </div>
    </nav>

    <!-- Banner (Auto Changing) -->
    <section class="main-banner">
        <div class="banner-slider" id="homeSlider">
            <!-- Dynamically populated -->
            <div class="slide active" style="background: #1e293b; display:flex; align-items:center; justify-content:center; color:white; font-size:1.2rem;"><i class="fas fa-spinner fa-spin" style="margin-right:10px;"></i> Loading slider...</div>
        </div>
        <div class="banner-controls">
            <button id="prevBtn"><i class="fas fa-chevron-left"></i></button>
            <button id="nextBtn"><i class="fas fa-chevron-right"></i></button>
        </div>
    </section>

    <!-- Welcome Strip (News line directly under navigation / banner) -->
    <div class="welcome-strip">
        <div class="container">
            <marquee behavior="scroll" direction="left" scrollamount="5" onmouseover="this.stop();" onmouseout="this.start();">
                <i class="fas fa-bullhorn" style="color: #fbbf24; margin-right: 10px;"></i> 
                <span id="newsBarText" data-i18n="welcome_strip_text"><strong>Welcome to the Official Web Portal of the Wayamba Province Social Services Department</strong> - Serving the poor, elderly, and disabled individuals with compassion.</span>
            </marquee>
        </div>
    </div>

    <!-- Content Sections (Vision, Mission, Announcements, News, RTI, Suggestions) -->
    <section class="main-content container">
        <div class="content-grid">
            
            <!-- Left Column: Primary Content -->
            <div class="left-col">
                <div class="vm-section boxed-panel beautiful-vm" id="vision">
                    <div class="vm-item vision-card">
                        <div class="vm-icon"><i class="fas fa-eye"></i></div>
                        <div class="vm-content">
                            <div class="vm-title" data-i18n="lbl_vision">Vision</div>
                            <div class="vm-text" id="visionText" data-i18n="vm_vision_text">To empower vulnerable populations to become active contributors to sustainable national development.</div>
                        </div>
                    </div>
                    <div class="vm-divider"></div>
                    <div class="vm-item mission-card">
                        <div class="vm-icon"><i class="fas fa-bullseye"></i></div>
                        <div class="vm-content">
                            <div class="vm-title" data-i18n="lbl_mission">Mission</div>
                            <div class="vm-text" id="missionText" data-i18n="vm_mission_text">To contribute to national development by providing equitable welfare and relief services to minimize the disadvantages faced by the poor and vulnerable people of Wayamba Province due to various circumstances.</div>
                        </div>
                    </div>
                </div>

                <div class="news-section boxed-panel mt-4" id="news">
                    <h3 class="panel-header"><span data-i18n="heading_news">Latest News</span> <i class="fas fa-newspaper"></i></h3>
                    <div class="announcement-tabs" id="newsTabs">
                        <button class="tab-btn active" data-tab="dept-news" data-i18n="tab_dept">Department</button>
                        <button class="tab-btn" data-tab="prov-news" data-i18n="tab_prov">Provincial</button>
                    </div>
                    <div class="news-slider">
                        <marquee direction="up" scrollamount="2" onmouseover="this.stop();" onmouseout="this.start();" height="300px" id="newsList">
                            <!-- Dynamically loaded -->
                        </marquee>
                    </div>
                </div>

                <!-- Conference Hall Booking Calendar Section -->
                <div class="hall-booking-section boxed-panel mt-4" id="hall-bookings">
                    <h3 class="panel-header"><span data-i18n="heading_booking">Conference Hall Booking Calendar</span> <i class="fas fa-calendar-alt"></i></h3>
                    <div class="calendar-wrapper">
                        <!-- Calendar Header: Month/Year navigation -->
                        <div class="calendar-header">
                            <button id="prevMonthBtn" class="calendar-nav-btn"><i class="fas fa-chevron-left"></i></button>
                            <h4 id="calendarMonthYear" class="calendar-month-year"></h4>
                            <button id="nextMonthBtn" class="calendar-nav-btn"><i class="fas fa-chevron-right"></i></button>
                        </div>
                        
                        <!-- Calendar Days Grid -->
                        <div class="calendar-grid-container">
                            <div class="calendar-weekdays">
                                <div>Sun</div>
                                <div>Mon</div>
                                <div>Tue</div>
                                <div>Wed</div>
                                <div>Thu</div>
                                <div>Fri</div>
                                <div>Sat</div>
                            </div>
                            <!-- Days will be dynamically populated by JS -->
                            <div id="calendarDays" class="calendar-grid-days"></div>
                        </div>
                        
                        <!-- Legend -->
                        <div class="calendar-legend">
                            <span class="legend-item avail-badge"><span class="legend-color"></span> <span data-i18n="lbl_available">Available</span></span>
                            <span class="legend-item booked-badge"><span class="legend-color"></span> <span data-i18n="lbl_booked">Reserved</span></span>
                        </div>
                        
                        <!-- Booking Details Pane -->
                        <div class="booking-details-pane" id="bookingDetailsPane">
                            <h5 class="details-header"><i class="fas fa-info-circle"></i> <span data-i18n="lbl_booking_details">Booking Details</span></h5>
                            <div id="bookingDetailsContent" class="details-content">
                                <div class="no-booking-msg-wrapper">
                                    <div class="no-booking-icon"><i class="far fa-calendar-check"></i></div>
                                    <span class="no-booking-msg" data-i18n="lbl_no_booking">Select a reserved date (marked in red) to view booking details.</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Booking Instructions -->
                        <div class="booking-instructions">
                            <i class="fas fa-info-circle"></i> <span data-i18n="lbl_booking_instructions">Note: The meeting hall is available for use free of charge for official purposes, subject to approval from the Provincial Director. To check date availability, refer to this calendar. To request a booking, contact the administration department.</span>
                        </div>
                    </div>
                </div>



                <!-- RTI Information Panel -->
                <div class="rti-container boxed-panel mt-4">
                    <h3 class="panel-header"><span data-i18n="lbl_rti_title">Right to Information (RTI)</span> <i class="fas fa-info-circle"></i></h3>
                    <div class="rti-content-body">
                        <div class="rti-card">
                            <h4 class="officer-type" data-i18n="lbl_rti_officer">Information Officer</h4>
                            <div class="rti-details-text">
                                <p class="name" id="rtiOfficerName" data-i18n="lbl_rti_officer_name"><strong>Name:</strong> Mrs. Deepthi Pradeepa De Silva</p>
                                <p class="designation" id="rtiOfficerTitle" data-i18n="lbl_rti_officer_title"><strong>Designation:</strong> Administrative Officer</p>
                            </div>
                        </div>
                        
                        <div class="rti-card mt-3">
                            <h4 class="officer-type" data-i18n="lbl_rti_appellate">Designated Officer (Appeals)</h4>
                            <div class="rti-details-text">
                                <p class="name" id="rtiAppellateName" data-i18n="lbl_rti_appellate_name"><strong>Name:</strong> Mrs. G.G. Dilani Gunasinghe</p>
                                <p class="designation" id="rtiAppellateTitle" data-i18n="lbl_rti_appellate_title"><strong>Designation:</strong> Provincial Director of Social Services - Wayamba</p>
                            </div>
                        </div>
                        
                        <div class="rti-card mt-3">
                            <h4 class="officer-type" data-i18n="lbl_rti_downloads">RTI Application Downloads</h4>
                            <div class="rti-details-text" style="display: flex; gap: 10px; flex-wrap: wrap; margin-top: 8px;">
                                <a id="rtiAppSiLink" href="#" download style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: rgba(153, 27, 27, 0.08); color: var(--portal-blue); border-radius: 4px; font-size: 0.85rem; font-weight: 600; border: 1px solid rgba(153, 27, 27, 0.15); transition: all 0.3s; text-decoration: none;">
                                    <i class="fas fa-file-pdf" style="color: #ef4444;"></i> <span data-i18n="lbl_rti_lang_si">Sinhala</span>
                                </a>
                                <a id="rtiAppEnLink" href="#" download style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: rgba(153, 27, 27, 0.08); color: var(--portal-blue); border-radius: 4px; font-size: 0.85rem; font-weight: 600; border: 1px solid rgba(153, 27, 27, 0.15); transition: all 0.3s; text-decoration: none;">
                                    <i class="fas fa-file-pdf" style="color: #ef4444;"></i> <span data-i18n="lbl_rti_lang_en">English</span>
                                </a>
                                <a id="rtiAppTaLink" href="#" download style="display: inline-flex; align-items: center; gap: 6px; padding: 6px 12px; background: rgba(153, 27, 27, 0.08); color: var(--portal-blue); border-radius: 4px; font-size: 0.85rem; font-weight: 600; border: 1px solid rgba(153, 27, 27, 0.15); transition: all 0.3s; text-decoration: none;">
                                    <i class="fas fa-file-pdf" style="color: #ef4444;"></i> <span data-i18n="lbl_rti_lang_ta">Tamil</span>
                                </a>
                            </div>
                        </div>
                        
                        <div class="rti-footer mt-4">
                            <p class="rti-note" data-i18n="lbl_rti_commission_note">For more information regarding RTI, visit the RTI Commission website.</p>
                            <a href="http://www.rticommission.lk" target="_blank" class="rti-btn"><i class="fas fa-external-link-alt"></i> <span data-i18n="lbl_rti_btn">Visit RTI Commission</span></a>
                        </div>
                    </div>
                </div>

                <!-- Department Systems Panel -->
                <div class="systems-container boxed-panel mt-4">
                    <h3 class="panel-header">
                        <span data-i18n="heading_systems">Department Systems</span>
                        <i class="fas fa-cubes"></i>
                    </h3>
                    <div class="systems-grid">
                        <a href="https://elders-route-north-western-province-council-production-2.apps.red-k8s.akaza.lk" target="_blank" class="system-btn">
                            <div class="system-btn-icon"><i class="fas fa-home-user"></i></div>
                            <div class="system-btn-text">
                                <span class="sys-title" data-i18n="sys_elder_homes">Elder Homes System</span>
                                <span class="sys-desc">Management & Records</span>
                            </div>
                            <div class="system-btn-arrow"><i class="fas fa-chevron-right"></i></div>
                        </a>
                        <a href="https://ssd-progress-git-north-western-province-council-production-3.apps.red-k8s.akaza.lk" target="_blank" class="system-btn">
                            <div class="system-btn-icon"><i class="fas fa-chart-line"></i></div>
                            <div class="system-btn-text">
                                <span class="sys-title" data-i18n="sys_monthly_progress">Monthly Progress View</span>
                                <span class="sys-desc">Performance & Reports</span>
                            </div>
                            <div class="system-btn-arrow"><i class="fas fa-chevron-right"></i></div>
                        </a>
                        <a href="https://ssd-recroom-git-north-western-province-council-production-3.apps.red-k8s.akaza.lk" target="_blank" class="system-btn">
                            <div class="system-btn-icon"><i class="fas fa-archive"></i></div>
                            <div class="system-btn-text">
                                <span class="sys-title" data-i18n="sys_ssd_rec">SSD Rec</span>
                                <span class="sys-desc">Physical Record Archives</span>
                            </div>
                            <div class="system-btn-arrow"><i class="fas fa-chevron-right"></i></div>
                        </a>
                        <a href="https://ssd-stores-git-north-western-province-council-production-3.apps.red-k8s.akaza.lk" target="_blank" class="system-btn">
                            <div class="system-btn-icon"><i class="fas fa-warehouse"></i></div>
                            <div class="system-btn-text">
                                <span class="sys-title" data-i18n="sys_ssd_store">SSD Store</span>
                                <span class="sys-desc">Inventory & Warehouse</span>
                            </div>
                            <div class="system-btn-arrow"><i class="fas fa-chevron-right"></i></div>
                        </a>
                        <a href="https://ssd-equip-git-north-western-province-council-production-3.apps.red-k8s.akaza.lk" target="_blank" class="system-btn">
                            <div class="system-btn-icon"><i class="fas fa-tools"></i></div>
                            <div class="system-btn-text">
                                <span class="sys-title" data-i18n="sys_ssd_equipment">SSD Equipment</span>
                                <span class="sys-desc">Equipment Tracking</span>
                            </div>
                            <div class="system-btn-arrow"><i class="fas fa-chevron-right"></i></div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Right Column: Secondary Content -->
            <div class="right-col">
                <div class="courses-section boxed-panel">
                    <h3 class="panel-header"><span data-i18n="heading_courses">WORKSHOPS & PROGRAMS</span> <i class="fas fa-calendar-alt"></i></h3>
                    <div class="announcement-tabs" id="coursesTabs">
                        <button class="tab-btn active" data-tab="upcoming" data-i18n="tab_upcoming">Upcoming Sessions</button>
                        <button class="tab-btn" data-tab="completed" data-i18n="tab_completed_courses">Completed Programs</button>
                    </div>
                    <ul class="courses-list" id="coursesList" style="max-height: 250px; overflow-y: auto; padding: 10px;">
                        <!-- Dynamically loaded -->
                    </ul>
                </div>

                <div class="announcements boxed-panel mt-4">
                    <h3 class="panel-header" data-i18n="heading_announcements">Announcements</h3>
                    <div class="announcement-tabs" id="announcementTabs">
                        <button class="tab-btn active" data-tab="internal" data-i18n="tab_internal">Internal</button>
                        <button class="tab-btn" data-tab="outside" data-i18n="tab_outside">Public</button>
                    </div>
                    <div class="announcement-content">
                        <marquee direction="up" scrollamount="2" onmouseover="this.stop();" onmouseout="this.start();" height="350px">
                            <ul class="announcement-list" id="announcementsList">
                                <!-- Dynamically loaded -->
                            </ul>
                        </marquee>
                    </div>
                </div>

                <div class="bus-booking-section boxed-panel mt-4" id="bus-bookings">
                    <h3 class="panel-header"><span data-i18n="heading_bus_booking">Vehicle Booking Calendar</span> <i class="fas fa-bus"></i></h3>
                    <div class="calendar-wrapper">
                        <!-- Calendar Header: Month/Year navigation -->
                        <div class="calendar-header">
                            <button id="prevBusMonthBtn" class="calendar-nav-btn"><i class="fas fa-chevron-left"></i></button>
                            <h4 id="busCalendarMonthYear" class="calendar-month-year"></h4>
                            <button id="nextBusMonthBtn" class="calendar-nav-btn"><i class="fas fa-chevron-right"></i></button>
                        </div>
                        
                        <!-- Calendar Days Grid -->
                        <div class="calendar-grid-container">
                            <div class="calendar-weekdays">
                                <div>Sun</div>
                                <div>Mon</div>
                                <div>Tue</div>
                                <div>Wed</div>
                                <div>Thu</div>
                                <div>Fri</div>
                                <div>Sat</div>
                            </div>
                            <!-- Days will be dynamically populated by JS -->
                            <div id="busCalendarDays" class="calendar-grid-days"></div>
                        </div>
                        
                        <!-- Legend -->
                        <div class="calendar-legend">
                            <span class="legend-item avail-badge"><span class="legend-color"></span> <span data-i18n="lbl_available">Available</span></span>
                            <span class="legend-item booked-badge"><span class="legend-color"></span> <span data-i18n="lbl_booked">Reserved</span></span>
                        </div>
                        
                        <!-- Booking Details Pane -->
                        <div class="booking-details-pane" id="busBookingDetailsPane">
                            <h5 class="details-header"><i class="fas fa-info-circle"></i> <span data-i18n="lbl_booking_details">Booking Details</span></h5>
                            <div id="busBookingDetailsContent" class="details-content">
                                <div class="no-booking-msg-wrapper">
                                    <div class="no-booking-icon"><i class="far fa-calendar-check"></i></div>
                                    <span class="no-booking-msg" data-i18n="lbl_no_booking">Select a reserved date (marked in red) to view booking details.</span>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Booking Instructions -->
                        <div class="booking-instructions">
                            <i class="fas fa-info-circle"></i> <span data-i18n="lbl_bus_booking_instructions">Note: The department bus is available for use for official purposes, subject to approval from the Provincial Director. To check date availability, refer to this calendar. To request a booking, contact the establishment section.</span>
                        </div>
                    </div>
                </div>

                <!-- Suggestions & Complaints Box -->
                <div class="suggestions-container boxed-panel mt-4">
                    <h3 class="panel-header"><span data-i18n="lbl_suggestion_title">Suggestions & Feedback</span> <i class="fas fa-comment-dots"></i></h3>
                    <div class="suggestions-body">
                        <p class="desc" data-i18n="lbl_suggestion_desc">We value your input. Please submit your suggestions, complaints, or feedback using the form below.</p>
                        <form id="suggestionForm" class="suggestion-form">
                            <div class="form-grid" style="grid-template-columns: 1fr;">
                                <div class="form-group">
                                    <label data-i18n="form_name">Full Name</label>
                                    <input type="text" id="sugName" required class="form-control">
                                </div>
                                <div class="form-group mt-3">
                                    <label data-i18n="form_email">Email Address</label>
                                    <input type="email" id="sugEmail" required class="form-control">
                                </div>
                            </div>
                            <div class="form-group mt-3">
                                <label data-i18n="form_message">Message / Suggestion</label>
                                <textarea id="sugMessage" required class="form-control" rows="4"></textarea>
                            </div>
                            <div class="form-status mt-2" id="suggestionStatus" style="display:none;"></div>
                            <button type="submit" class="submit-btn mt-5"><span data-i18n="form_submit">Submit Suggestion</span> <i class="fas fa-paper-plane"></i></button>
                        </form>
                    </div>
                </div>

                <div class="links-section boxed-panel mt-4">
                    <h3 class="panel-header"><span data-i18n="heading_links">Important Links</span> <i class="fas fa-external-link-alt"></i></h3>
                    <div class="announcement-tabs" id="linksTabs">
                        <button class="tab-btn active" data-tab="govt-links" data-i18n="tab_govt">Government</button>
                        <button class="tab-btn" data-tab="tech-links" data-i18n="tab_eng">Welfare Resources</button>
                    </div>
                    <ul class="external-links" id="linksList">
                        <!-- Dynamically loaded -->
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Map & Footer Details -->
    <footer class="main-footer" id="contact">
        <div class="container footer-grid">
            <div class="footer-box">
                <h4>වයඹ පළාත් සමාජ සේවා දෙපාර්තමේන්තුව</h4>
                <ul class="footer-list">
                    <li id="footerContactAddress"><i class="fas fa-map-marker-alt"></i> පළාත් සභා සංකීර්ණය, කුරුණෑගල</li>
                    <li id="footerContactPhone"><i class="fas fa-phone"></i> 037-2223483</li>
                    <li id="footerContactFax"><i class="fas fa-fax"></i> 037-2224976</li>
                    <li id="footerContactEmail"><i class="fas fa-envelope"></i> socidepnwp@gmail.com</li>
                    <li><i class="fas fa-globe"></i> www.socialdept.nw.gov.lk</li>
                </ul>
                <div class="social-icons-bar mt-3">
                    <a id="footerYoutubeLink" href="http://www.youtube.com/@socialchap" target="_blank" class="social-icon-btn yt-btn" title="YouTube"><i class="fab fa-youtube"></i></a>
                    <a id="footerFacebookLink" href="https://facebook.com/socialchap" target="_blank" class="social-icon-btn fb-btn" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                </div>
            </div>
            
            <div class="footer-box">
                <h4 data-i18n="nav_services">Services</h4>
                <ul class="footer-list">
                    <li><a href="#" onclick="openServicesTab('investigations')">Elders Homes Management</a></li>
                    <li><a href="#" onclick="openServicesTab('engineering')">Services for Disabled Persons</a></li>
                    <li><a href="#" onclick="openServicesTab('construction')">Elderly Welfare Projects</a></li>
                    <li><a href="#" onclick="openServicesTab('operation')">Special Medical Assistance</a></li>
                    <li><a href="#" onclick="openServicesTab('institutional')">Institutional Supervision</a></li>
                </ul>
            </div>
            
            <div class="footer-box map-box">
                <h4 data-i18n="footer_map">Location Map</h4>
                <div class="map-placeholder">
                    <iframe id="footerContactMap" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d126490.13327670732!2d80.28841443690623!3d7.494747385966427!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ae33a1e944b419b%3A0xe542385cc820b924!2sKurunegala!5e0!3m2!1sen!2slk!4v1714207907572!5m2!1sen!2slk" width="100%" height="150" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                </div>
            </div>
        </div>
        
        <div class="visitor-counter">
            <div class="container">
                <span><span data-i18n="footer_visitors">Total Visitors: </span><span class="badge" id="visitorBadge" style="background:var(--primary-light); padding: 5px 10px; border-radius: 5px; font-weight:700; letter-spacing:2px;">0 0 4 7 8 2 1</span></span>
                <span data-i18n="footer_powered">Powered by Digital Division - NWP © 2026</span>
            </div>
        </div>
    </footer>


    <!-- ================= MODALS ================= -->

    <!-- Member Login Modal -->
    <div class="modal" id="loginModal">
        <div class="modal-content login-modal-content">
            <span class="close-btn login-modal-close" id="closeModal">&times;</span>
            <div class="modal-body login-modal-body">
                <h2 class="login-modal-title" data-i18n="modal_login_header">USER LOGIN</h2>
                <form id="loginForm">
                    
                    <!-- Username Container (Icon on left) -->
                    <div class="login-input-wrapper username-wrapper">
                        <div class="login-icon-circle">
                            <i class="fas fa-user"></i>
                        </div>
                        <input type="text" id="username" class="login-input-field" placeholder="Username" data-i18n-placeholder="placeholder_username" required autocomplete="off">
                    </div>

                    <!-- Password Container (Icon on right) -->
                    <div class="login-input-wrapper password-wrapper">
                        <input type="password" id="password" class="login-input-field" placeholder="Password" data-i18n-placeholder="placeholder_password" required>
                        <div class="login-icon-circle">
                            <i class="fas fa-lock"></i>
                        </div>
                    </div>

                    <div class="error-msg" id="errorMsg" style="display:none; color: #fca5a5; background: rgba(153, 27, 27, 0.4); border: 1px solid rgba(153, 27, 27, 0.5); padding: 10px; border-radius: 8px; font-size: 0.88rem; margin-bottom: 15px;"><i class="fas fa-exclamation-circle"></i> <span data-i18n="login_error">Invalid credentials provided.</span></div>
                    
                    <!-- Submit Button -->
                    <button type="submit" class="login-btn-pill" data-i18n="btn_authenticate">LOGIN</button>
                    
                    <p class="login-restricted-text" data-i18n="login_restricted">Restricted Access for Authorized NWP Personnel Only</p>
                </form>
            </div>
        </div>
    </div>

    <!-- About Us Modal (In-Page Split Layout) -->
    <div class="modal" id="aboutUsModal">
        <div class="modal-content about-modal-content">
            <div class="modal-header">
                <h2 data-i18n="modal_about_title">About the Department</h2>
                <span class="close-btn" id="closeAboutModal">&times;</span>
            </div>
            <div class="modal-body about-modal-body">
                <aside class="sidebar about-sidebar">
                    <ul class="sidebar-nav">
                        <li><a href="#" class="about-tab-btn active" data-tab="overview"><i class="fas fa-align-left"></i> <span data-i18n="nav_about_overview">Overview / Description</span></a></li>
                        <li><a href="#" class="about-tab-btn" data-tab="orgchart"><i class="fas fa-sitemap"></i> <span data-i18n="nav_about_orgchart">Organization Chart</span></a></li>
                        <li><a href="#" class="about-tab-btn" data-tab="objectives"><i class="fas fa-bullseye"></i> <span data-i18n="nav_about_objectives">Purpose & Objectives</span></a></li>
                        <li><a href="#" class="about-tab-btn" data-tab="achievements"><i class="fas fa-trophy"></i> <span data-i18n="nav_about_achievements">Achievements</span></a></li>
                        <li><a href="#" class="about-tab-btn" data-tab="citizen"><i class="fas fa-file-alt"></i> <span data-i18n="nav_about_citizen">Citizen's Charter</span></a></li>
                        <li><a href="#" class="about-tab-btn" data-tab="staff"><i class="fas fa-users-gear"></i> <span data-i18n="nav_about_staff">Staff Details</span></a></li>
                    </ul>
                </aside>
                <main class="content-area about-content-area">
                    <div id="tab-overview" class="about-pane" style="display:block;">
                        <h2 class="content-title" data-i18n="nav_about_overview">Overview / Description</h2>
                        <p id="aboutOverviewText">The Department of Social Services of the Wayamba Provincial Council is dedicated to enhancing the welfare and social development of vulnerable populations including elders, disabled individuals, and impoverished citizens in the North Western Province.</p>
                    </div>
                    <div id="tab-orgchart" class="about-pane" style="display:none;">
                        <h2 class="content-title" data-i18n="nav_about_orgchart">Organizational Structure</h2>
                        <div style="text-align: center;">
                            <img id="orgChartImage" src="logo2.jpg" alt="Organizational Chart" style="max-width:100%; max-height:450px; height:auto; border-radius:5px; border:1px solid #ddd; padding: 10px; background: white;">
                            <p style="margin-top: 10px; font-style: italic; color: #64748b;">Wayamba Provincial Social Services Department Hierarchy Chart</p>
                        </div>
                    </div>
                    <div id="tab-objectives" class="about-pane" style="display:none;">
                        <h2 class="content-title" data-i18n="nav_about_objectives">Purpose & Objectives</h2>
                        <p id="aboutObjectivesText">To provide equitable, accessible, and high-quality social welfare and relief services, empowering disadvantaged groups and integrating them into the mainstream of national development.</p>
                    </div>
                    <div id="tab-achievements" class="about-pane" style="display:none;">
                        <h2 class="content-title" data-i18n="nav_about_achievements">Achievements</h2>
                        <p id="aboutAchievementsText">Empowered thousands of individuals with self-employment grants, established specialized care facilities, and actively integrated vulnerable families into community welfare projects.</p>
                    </div>
                    <div id="tab-citizen" class="about-pane" style="display:none;">
                        <h2 class="content-title" data-i18n="citizen_charter_title">Citizen's Charter Downloads</h2>
                        <p data-i18n="citizen_charter_desc">Please click on the links below to download the relevant Citizen's Charter details in varying formats.</p>
                        <ul class="announcement-list">
                            <li><a id="charterSiLink" href="RTI_Request_Form.pdf" download><i class="fas fa-file-pdf" style="color: red;"></i> <span>Citizen's Charter (Sinhala) - PDF</span></a></li>
                            <li><a id="charterEnLink" href="RTI_Request_Form.pdf" download><i class="fas fa-file-pdf" style="color: red;"></i> <span>Citizen's Charter (English) - PDF</span></a></li>
                        </ul>
                    </div>
                    <div id="tab-staff" class="about-pane" style="display:none;">
                        <h2 class="content-title" data-i18n="nav_about_staff">Staff Details</h2>
                        <div class="staff-table-wrapper">
                            <table class="staff-table">
                                <thead>
                                    <tr>
                                        <th data-i18n="col_ser" class="col-ser-header" style="width: 80px; text-align: center;">Ser No</th>
                                        <th data-i18n="col_photo" class="col-photo-header" style="width: 100px; text-align: center;">Photo</th>
                                        <th data-i18n="col_name" style="min-width: 160px;">Full Name</th>
                                        <th data-i18n="col_designation" style="min-width: 150px;">Designation</th>
                                        <th data-i18n="col_institution" style="min-width: 150px;">Institution</th>
                                        <th data-i18n="col_email" style="min-width: 180px;">Email</th>
                                    </tr>
                                </thead>
                                <tbody id="aboutStaffTableBody">
                                    <!-- Dynamic -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <!-- Services Modal (Split Layout) -->
    <div class="modal" id="servicesModal">
        <div class="modal-content about-modal-content">
            <div class="modal-header">
                <h2 data-i18n="modal_services_title">අපගේ සේවාවන් | Our Services</h2>
                <span class="close-btn" id="closeServicesModal">&times;</span>
            </div>
            <div class="modal-body about-modal-body" id="servicesModalBody">
                <aside class="sidebar about-sidebar" id="servicesSidebar">
                    <ul class="services-sidebar-nav" id="servicesSidebarNav">
                        <!-- Dynamically populated -->
                    </ul>
                </aside>
                <main class="content-area about-content-area" id="servicesContentArea" style="background: #ffffff; padding: 30px;">
                    <!-- Dynamically populated -->
                </main>
            </div>
        </div>
    </div>


    <!-- Downloads Modal (Split Layout) -->
    <div class="modal" id="downloadsModal">
        <div class="modal-content about-modal-content">
            <div class="modal-header">
                <h2 data-i18n="modal_downloads_title">Document Downloads</h2>
                <span class="close-btn" id="closeDownloadsModal">&times;</span>
            </div>
            <div class="modal-body about-modal-body">
                <aside class="sidebar about-sidebar">
                    <ul class="sidebar-nav">
                        <li><a href="#" class="downloads-tab-btn active" data-tab="formats"><i class="fas fa-file-invoice"></i> <span data-i18n="dl_formats">Application Forms</span></a></li>
                        <li><a href="#" class="downloads-tab-btn" data-tab="circulars"><i class="fas fa-book"></i> <span data-i18n="dl_circulars">Circulars</span></a></li>
                        <li><a href="#" class="downloads-tab-btn" data-tab="rates"><i class="fas fa-info-circle"></i> <span data-i18n="dl_rates">Welfare Guidelines</span></a></li>
                    </ul>
                </aside>
                <main class="content-area about-content-area">
                    <div id="dtab-formats" class="downloads-pane" style="display:block;">
                        <h2 class="content-title" data-i18n="dl_formats">Application Forms</h2>
                        <p data-i18n="dl_formats_desc">Standard templates, welfare requests, and document formats for public and official use.</p>
                        <div class="downloads-list" id="formats-list" style="margin-top: 15px;"></div>
                    </div>

                    <div id="dtab-circulars" class="downloads-pane" style="display:none;">
                        <h2 class="content-title" data-i18n="dl_circulars">Circulars</h2>
                        <p data-i18n="dl_circulars_desc">Official provincial council social services guidelines and national circulars.</p>
                        <div class="downloads-list" id="circulars-list" style="margin-top: 15px;"></div>
                    </div>
                    <div id="dtab-rates" class="downloads-pane" style="display:none;">
                        <h2 class="content-title" data-i18n="dl_rates">Welfare Guidelines</h2>
                        <p data-i18n="dl_rates_desc">Guideline booklets and criteria details for social services benefit schemes.</p>
                        <div class="downloads-list" id="rates-list" style="margin-top: 15px;"></div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <!-- Procurement Notices Modal -->
    <div class="modal" id="procurementsModal">
        <div class="modal-content about-modal-content">
            <div class="modal-header">
                <h2 data-i18n="modal_procurement_title">Procurement & Tender Notices</h2>
                <span class="close-btn" id="closeProcurementsModal">&times;</span>
            </div>
            <div class="modal-body about-modal-body" style="padding: 20px 30px; display: block;">
                <p style="margin-bottom: 20px; color: #64748b;" data-i18n="procurement_desc">Below are the active and upcoming procurement and bidding opportunities for the Wayamba Social Services Department.</p>
                <div class="downloads-list" id="procurementsList">
                    <!-- Dynamically populated -->
                </div>
            </div>
        </div>
    </div>

    <!-- Gallery Modal -->
    <div class="modal" id="galleryModal">
        <div class="modal-content about-modal-content" style="max-width: 900px;">
            <div class="modal-header">
                <h2 data-i18n="modal_gallery_title">Department Media Gallery</h2>
                <span class="close-btn" id="closeGalleryModal">&times;</span>
            </div>
            <div class="modal-body about-modal-body" style="display:block; padding:20px;">
                <div class="gallery-grid" id="galleryGrid">
                    <!-- Dynamically populated -->
                </div>
            </div>
        </div>
    </div>

    <!-- Projects / Programs Modal (Split Layout) -->
    <div class="modal" id="projectsModal">
        <div class="modal-content about-modal-content">
            <div class="modal-header">
                <h2 data-i18n="modal_projects_title">Welfare Programs Summary</h2>
                <span class="close-btn" id="closeProjectsModal">&times;</span>
            </div>
            <div class="modal-body about-modal-body">
                <aside class="sidebar about-sidebar">
                    <ul class="sidebar-nav">
                        <li><a href="#" class="projects-tab-btn active" data-tab="summary"><i class="fas fa-chart-pie"></i> <span data-i18n="proj_summary">Welfare Summary</span></a></li>
                        <li><a href="#" class="projects-tab-btn" data-tab="key-projects"><i class="fas fa-people-carry-box"></i> <span data-i18n="proj_key">Key Programs</span></a></li>
                        <li><a href="#" class="projects-tab-btn" data-tab="completed"><i class="fas fa-check-double"></i> <span data-i18n="proj_completed">Completed Programs</span></a></li>
                    </ul>
                </aside>
                <main class="content-area about-content-area">
                    <div id="ptab-summary" class="projects-pane" style="display:block;">
                        <h2 class="content-title" data-i18n="proj_summary">Welfare Summary</h2>
                        <p data-i18n="proj_summary_desc">Total recipient counts and financial summaries for the current year.</p>
                        <div id="projSummaryContainer" class="mt-3"></div>
                    </div>
                    <div id="ptab-key-projects" class="projects-pane" style="display:none;">
                        <h2 class="content-title" data-i18n="proj_key">Key Social Programs</h2>
                        <p data-i18n="proj_key_desc">Major social welfare and infrastructure projects currently in progress.</p>
                        <div id="projKeyContainer" class="mt-3"></div>
                    </div>
                    <div id="ptab-completed" class="projects-pane" style="display:none;">
                        <h2 class="content-title" data-i18n="proj_completed">Completed Programs</h2>
                        <p data-i18n="proj_completed_desc">Archive of recently completed community support programs.</p>
                        <div id="projCompletedContainer" class="mt-3"></div>
                    </div>
                </main>
            </div>
        </div>
    </div>

    <!-- News Details Modal -->
    <div class="modal" id="newsModal">
        <div class="modal-content" style="max-width: 700px;">
            <div class="modal-header">
                <h2 id="newsModalTitle">News Detail</h2>
                <span class="close-btn" id="closeNewsModal">&times;</span>
            </div>
            <div class="modal-body" style="padding: 20px;">
                <p id="newsModalDate" style="color: #64748b; font-size: 0.9rem; font-weight: 600; margin-bottom: 10px;"></p>
                <div id="newsModalImageContainer" style="text-align: center; margin-bottom: 20px; display:none;">
                    <img id="newsModalImage" src="" alt="News Image" style="max-width: 100%; max-height: 350px; border-radius: 10px;">
                </div>
                <div id="newsModalContent" style="line-height: 1.6; color:#334155; margin-bottom: 20px;"></div>
                <div id="newsModalLinkContainer" style="display:none; margin-top:20px; text-align:right;">
                    <a id="newsModalLink" href="#" target="_blank" class="rti-btn">
                        <i class="fas fa-external-link-alt"></i> <span data-i18n="btn_visit_link">Visit Link</span>
                    </a>
                </div>
                <div id="newsModalSlider" class="news-detail-slider" style="display:none; grid-template-columns: 1fr 1fr; gap:15px; margin-top:20px;">
                    <div>
                        <h4 style="margin-bottom: 5px; font-size:0.9rem; color:#64748b; text-transform:uppercase;" data-i18n="news_before">Before</h4>
                        <img id="newsModalBefore" src="" alt="Before" style="width: 100%; height:180px; object-fit:cover; border-radius: 8px;">
                    </div>
                    <div>
                        <h4 style="margin-bottom: 5px; font-size:0.9rem; color:#64748b; text-transform:uppercase;" data-i18n="news_after">After</h4>
                        <img id="newsModalAfter" src="" alt="After" style="width: 100%; height:180px; object-fit:cover; border-radius: 8px;">
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- ================= CHATBOT SEARCH WIDGET ================= -->
    <div class="chatbot-bubble" id="chatbotBubble" title="Open Welfare Assistant">
        <i class="fas fa-comments"></i>
        <span class="chatbot-label" data-i18n="bot_bubble">Welfare Assistant</span>
    </div>

    <div class="chatbot-panel" id="chatbotPanel">
        <div class="chatbot-header">
            <div class="chatbot-header-left">
                <div class="bot-avatar"><i class="fas fa-robot"></i></div>
                <div>
                    <h4 data-i18n="bot_title">Welfare Assistant</h4>
                    <span class="bot-status"><span class="status-dot"></span> Online</span>
                </div>
            </div>
            <button class="chatbot-close-btn" id="closeChatbot"><i class="fas fa-times"></i></button>
        </div>
        <div class="chatbot-messages" id="chatbotMessages">
            <div class="chat-msg bot">
                <div class="msg-bubble" data-i18n="bot_welcome">
                    Hello! Welcome to the Wayamba Social Services Department. I am your chatbot search assistant. How can I help you today?
                </div>
            </div>
            <div class="chat-quick-replies" id="chatbotQuickReplies">
                <button class="quick-reply-btn" data-query="rti" data-i18n="bot_quick_rti">RTI Officers</button>
                <button class="quick-reply-btn" data-query="contact" data-i18n="bot_quick_contact">Contact Details</button>
                <button class="quick-reply-btn" data-query="news" data-i18n="bot_quick_news">Latest News</button>
                <button class="quick-reply-btn" data-query="downloads" data-i18n="bot_quick_downloads">Download Forms</button>
                <button class="quick-reply-btn" data-query="services" data-i18n="bot_quick_services">Services Offered</button>
            </div>
        </div>
        <div class="chatbot-input-container">
            <input type="text" id="chatbotInput" placeholder="Ask anything or type search terms..." data-i18n-placeholder="bot_placeholder">
            <button id="chatbotSend"><i class="fas fa-paper-plane"></i></button>
        </div>
    </div>


    <!-- ================= SCRIPTS ================= -->
    <script src="translations.js?v=8"></script>
    <script src="script.js?v=9"></script>

</body>
</html>
