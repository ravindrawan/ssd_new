document.addEventListener('DOMContentLoaded', () => {

    // === Mobile Navigation & Dropdowns ===
    const hamburger = document.querySelector('.hamburger');
    const navMenu = document.querySelector('.nav-menu');
    
    if (hamburger && navMenu) {
        hamburger.addEventListener('click', () => {
            navMenu.classList.toggle('active');
        });
    }

    const dropdowns = document.querySelectorAll('.dropdown');
    dropdowns.forEach(dropdown => {
        dropdown.addEventListener('click', function(e) {
            if (window.innerWidth <= 992) {
                const content = this.querySelector('.dropdown-content');
                if(content) {
                    content.style.display = content.style.display === 'block' ? 'none' : 'block';
                }
            }
        });
    });

    // === Home Banner Slider logic ===
    let currentSlide = 0;
    let slideInterval;
    let slides = [];

    function showSlide(index) {
        if (slides.length === 0) return;
        slides.forEach(slide => slide.classList.remove('active'));
        if (index >= slides.length) {
            currentSlide = 0;
        } else if (index < 0) {
            currentSlide = slides.length - 1;
        } else {
            currentSlide = index;
        }

        const activeSlide = slides[currentSlide];
        if (activeSlide.dataset.bg && !activeSlide.style.backgroundImage) {
            activeSlide.style.backgroundImage = `url('${activeSlide.dataset.bg}')`;
        }

        // Preload next slide for a seamless sliding experience
        const nextIndex = (currentSlide + 1) % slides.length;
        const nextSlide = slides[nextIndex];
        if (nextSlide && nextSlide.dataset.bg && !nextSlide.style.backgroundImage) {
            nextSlide.style.backgroundImage = `url('${nextSlide.dataset.bg}')`;
        }

        activeSlide.classList.add('active');

        const sliderContainer = document.getElementById('homeSlider');
        if (sliderContainer) {
            sliderContainer.style.transform = `translateX(-${currentSlide * 100}%)`;
        }
    }

    function autoSlide() {
        showSlide(currentSlide + 1);
    }

    function startSlideIter() {
        if (slideInterval) clearInterval(slideInterval);
        slideInterval = setInterval(autoSlide, 5000); // 5 seconds
    }

    function resetSlideInterval() {
        clearInterval(slideInterval);
        startSlideIter();
    }

    function initHomeSlider() {
        slides = document.querySelectorAll('.slide');
        const prevBtn = document.getElementById('prevBtn');
        const nextBtn = document.getElementById('nextBtn');
        
        if (slides.length > 0) {
            if (nextBtn) {
                nextBtn.onclick = () => {
                    showSlide(currentSlide + 1);
                    resetSlideInterval();
                };
            }
            if (prevBtn) {
                prevBtn.onclick = () => {
                    showSlide(currentSlide - 1);
                    resetSlideInterval();
                };
            }
            currentSlide = 0;
            showSlide(0);
            startSlideIter();
        }
    }

    function fetchBanners() {
        const sliderContainer = document.getElementById('homeSlider');
        if (!sliderContainer) return;

        const defaultBanners = [
            { title: "Empowering Vulnerable Communities", image_url: "slider1.jpg" },
            { title: "Caring for Our Elders", image_url: "slider2.jpg" },
            { title: "Social Development & Integration", image_url: "slider3.jpg" },
            { title: "Vocational Training Centers", image_url: "slider4.jpg" },
            { title: "Caring Communities for Seniors", image_url: "slider5.jpg" }
        ];

        const renderBanners = (bannersList) => {
            sliderContainer.innerHTML = bannersList.map((b, index) => {
                if (index === 0) {
                    return `<div class="slide active" style="background-image: url('${b.image_url}');">
                                ${b.title ? `<div class="slide-caption">
                                    <h3>${b.title}</h3>
                                </div>` : ''}
                            </div>`;
                }
                return `<div class="slide" data-bg="${b.image_url}">
                            ${b.title ? `<div class="slide-caption">
                                <h3>${b.title}</h3>
                            </div>` : ''}
                        </div>`;
            }).join('');
            initHomeSlider();
        };

        fetch('manage_banners.php?v=' + Date.now())
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success' && data.banners.length > 0) {
                renderBanners(data.banners);
            } else {
                renderBanners(defaultBanners);
            }
        })
        .catch(err => {
            console.error("Banners fetch failed:", err);
            renderBanners(defaultBanners);
        });
    }

    // === Tab Switching Logic ===
    document.querySelectorAll('.announcement-tabs').forEach(tabContainer => {
        const btns = tabContainer.querySelectorAll('.tab-btn');
        btns.forEach(btn => {
            btn.addEventListener('click', function() {
                btns.forEach(b => b.classList.remove('active'));
                this.classList.add('active');
                
                const tab = this.getAttribute('data-tab');
                const containerId = tabContainer.id;
                if (containerId === 'announcementTabs') {
                    renderAnnouncements(tab);
                } else if (containerId === 'newsTabs') {
                    renderNews(tab);
                } else if (containerId === 'coursesTabs') {
                    renderCourses(tab);
                } else if (containerId === 'linksTabs') {
                    renderLinks(tab);
                }
            });
        });
    });

    // === Login Modal Logic ===
    const loginBtn = document.getElementById('loginBtn');
    const loginModal = document.getElementById('loginModal');
    const closeModal = document.getElementById('closeModal');
    const loginForm = document.getElementById('loginForm');
    const errorMsg = document.getElementById('errorMsg');

    if(loginBtn && loginModal) {
        loginBtn.addEventListener('click', (e) => {
            e.preventDefault();
            loginModal.classList.add('active');
        });
    }

    if(closeModal && loginModal) {
        closeModal.addEventListener('click', () => {
            loginModal.classList.remove('active');
            if(errorMsg) errorMsg.style.display = 'none';
        });

        window.addEventListener('click', (e) => {
            if (e.target === loginModal) {
                loginModal.classList.remove('active');
                if(errorMsg) errorMsg.style.display = 'none';
            }
        });
    }

    if(loginForm) {
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const uname = document.getElementById('username')?.value.trim();
            const pass = document.getElementById('password')?.value.trim();

            const handleLocalFallback = () => {
                // Seed fallback logic
                if (uname === 'admin' && pass === 'admin123') {
                    const fallbackUser = { username: 'admin', full_name: 'Administrator', role: 'admin' };
                    sessionStorage.setItem('loggedInUser', JSON.stringify(fallbackUser));
                    if(errorMsg) errorMsg.style.display = 'none';
                    loginModal.classList.remove('active');
                    loginForm.reset();
                    window.location.href = 'members.html';
                    return true;
                }
                return false;
            };

            const formData = new FormData();
            formData.append('username', uname);
            formData.append('password', pass);

            fetch('auth.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'success') {
                    sessionStorage.setItem('loggedInUser', JSON.stringify(data.user));
                    if(errorMsg) errorMsg.style.display = 'none';
                    loginModal.classList.remove('active');
                    loginForm.reset();
                    window.location.href = 'members.html';
                } else {
                    if (!handleLocalFallback()) {
                        if(errorMsg) {
                            errorMsg.innerText = data.message;
                            errorMsg.style.display = 'block';
                        }
                    }
                }
            })
            .catch(error => {
                console.warn('Database login failed. Trying local fallback:', error);
                if (!handleLocalFallback()) {
                    if(errorMsg) {
                        errorMsg.innerHTML = "Access Failed. <br><small>Try: <b>admin / admin123</b></small>";
                        errorMsg.style.display = 'block';
                    }
                }
            });
        });
    }

    // === About Us Modal Logic ===
    const aboutUsModal = document.getElementById('aboutUsModal');
    const closeAboutModal = document.getElementById('closeAboutModal');
    
    if(closeAboutModal && aboutUsModal) {
        closeAboutModal.addEventListener('click', () => {
            aboutUsModal.classList.remove('active');
        });
        window.addEventListener('click', (e) => {
            if (e.target === aboutUsModal) aboutUsModal.classList.remove('active');
        });
    }

    window.openAboutUsTab = function(tabId) {
        const panes = document.querySelectorAll('.about-pane');
        const tabBtns = document.querySelectorAll('.about-tab-btn');
        
        panes.forEach(pane => pane.style.display = 'none');
        tabBtns.forEach(btn => btn.classList.remove('active'));
        
        const activePane = document.getElementById(`tab-${tabId}`);
        const activeBtn = document.querySelector(`.about-tab-btn[data-tab="${tabId}"]`);
        
        if(activePane) activePane.style.display = 'block';
        if(activeBtn) activeBtn.classList.add('active');
        
        if (aboutUsModal) aboutUsModal.classList.add('active');
    };
    
    document.querySelectorAll('.about-tab-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            openAboutUsTab(btn.dataset.tab);
        });
    });

    // === Services Modal Logic ===
    const servicesModal = document.getElementById('servicesModal');
    const closeServicesModal = document.getElementById('closeServicesModal');
    
    if(closeServicesModal && servicesModal) {
        closeServicesModal.addEventListener('click', () => {
            servicesModal.classList.remove('active');
        });
        window.addEventListener('click', (e) => {
            if (e.target === servicesModal) servicesModal.classList.remove('active');
        });
    }


    window.openServicesTab = function(tabId) {
        if (servicesModal) {
            servicesModal.classList.add('active');
        }
        setTimeout(() => {
            let serviceId = tabId;
            if (tabId) {
                const oldTabMapping = {
                    'investigations': 6,
                    'engineering': 7,
                    'construction': 8,
                    'operation': 5,
                    'institutional': 10
                };
                if (oldTabMapping[tabId]) {
                    serviceId = oldTabMapping[tabId];
                }
            } else if (globalServices.length > 0) {
                serviceId = globalServices[0].id;
            }
            if (serviceId) {
                selectActiveService(serviceId);
            }
        }, 100);
    };



    // === Downloads Modal Logic ===
    const downloadsModal = document.getElementById('downloadsModal');
    const closeDownloadsModal = document.getElementById('closeDownloadsModal');
    
    if(closeDownloadsModal && downloadsModal) {
        closeDownloadsModal.addEventListener('click', () => {
            downloadsModal.classList.remove('active');
        });
        window.addEventListener('click', (e) => {
            if (e.target === downloadsModal) downloadsModal.classList.remove('active');
        });
    }

    window.openDownloadsTab = function(tabId) {
        const panes = document.querySelectorAll('.downloads-pane');
        const tabBtns = document.querySelectorAll('.downloads-tab-btn');
        panes.forEach(p => p.style.display = 'none');
        tabBtns.forEach(b => b.classList.remove('active'));
        
        const activePane = document.getElementById(`dtab-${tabId}`);
        const activeBtn = document.querySelector(`.downloads-tab-btn[data-tab="${tabId}"]`);
        if(activePane) activePane.style.display = 'block';
        if(activeBtn) activeBtn.classList.add('active');
        if (downloadsModal) downloadsModal.classList.add('active');
    };

    document.querySelectorAll('.downloads-tab-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            openDownloadsTab(btn.dataset.tab);
        });
    });

    // === Procurement Notices Modal Logic ===
    const procurementsModal = document.getElementById('procurementsModal');
    const closeProcurementsModal = document.getElementById('closeProcurementsModal');
    
    window.openProcurementsModal = function() {
        if (procurementsModal) procurementsModal.classList.add('active');
        fetchProcurements();
    };

    if (closeProcurementsModal && procurementsModal) {
        closeProcurementsModal.addEventListener('click', () => procurementsModal.classList.remove('active'));
        window.addEventListener('click', (e) => {
            if (e.target === procurementsModal) procurementsModal.classList.remove('active');
        });
    }

    // === Gallery Modal Logic ===
    const galleryModal = document.getElementById('galleryModal');
    const closeGalleryModal = document.getElementById('closeGalleryModal');
    
    window.openGalleryModal = function() {
        if (galleryModal) galleryModal.classList.add('active');
        fetchGallery();
    };

    if (closeGalleryModal && galleryModal) {
        closeGalleryModal.addEventListener('click', () => galleryModal.classList.remove('active'));
        window.addEventListener('click', (e) => {
            if (e.target === galleryModal) galleryModal.classList.remove('active');
        });
    }

    // === Projects / Programs Modal Logic ===
    const projectsModal = document.getElementById('projectsModal');
    const closeProjectsModal = document.getElementById('closeProjectsModal');
    
    window.openProjectsModal = function(tabId) {
        if (projectsModal) projectsModal.classList.add('active');
        openProjectsTab(tabId);
    };

    if (closeProjectsModal && projectsModal) {
        closeProjectsModal.addEventListener('click', () => projectsModal.classList.remove('active'));
        window.addEventListener('click', (e) => {
            if (e.target === projectsModal) projectsModal.classList.remove('active');
        });
    }

    window.openProjectsTab = function(tabId) {
        const panes = document.querySelectorAll('.projects-pane');
        const tabBtns = document.querySelectorAll('.projects-tab-btn');
        panes.forEach(p => p.style.display = 'none');
        tabBtns.forEach(b => b.classList.remove('active'));
        
        const activePane = document.getElementById(`ptab-${tabId}`);
        const activeBtn = document.querySelector(`.projects-tab-btn[data-tab="${tabId}"]`);
        if(activePane) activePane.style.display = 'block';
        if(activeBtn) activeBtn.classList.add('active');
    };

    document.querySelectorAll('.projects-tab-btn').forEach(btn => {
        btn.addEventListener('click', (e) => {
            e.preventDefault();
            openProjectsTab(btn.dataset.tab);
        });
    });

    // === News Detail Modal Logic ===
    const newsModal = document.getElementById('newsModal');
    const closeNewsModal = document.getElementById('closeNewsModal');
    
    if (closeNewsModal && newsModal) {
        closeNewsModal.addEventListener('click', () => newsModal.classList.remove('active'));
        window.addEventListener('click', (e) => {
            if (e.target === newsModal) newsModal.classList.remove('active');
        });
    }

    window.openNewsModal = function(id) {
        const article = globalNews.find(item => item.id == id);
        if (!article) return;

        document.getElementById('newsModalTitle').innerText = article.title;
        document.getElementById('newsModalDate').innerHTML = `<i class="far fa-calendar-alt" style="color:var(--accent-gold); margin-right:5px;"></i> ${article.news_date}`;
        document.getElementById('newsModalContent').innerText = article.content;

        const imgContainer = document.getElementById('newsModalImageContainer');
        const img = document.getElementById('newsModalImage');
        if (article.image_url) {
            img.src = article.image_url;
            imgContainer.style.display = 'block';
        } else {
            imgContainer.style.display = 'none';
        }

        const slider = document.getElementById('newsModalSlider');
        if (article.image_before || article.image_after) {
            document.getElementById('newsModalBefore').src = article.image_before || 'logo2.jpg';
            document.getElementById('newsModalAfter').src = article.image_after || 'logo2.jpg';
            slider.style.display = 'grid';
        } else {
            slider.style.display = 'none';
        }

        const linkContainer = document.getElementById('newsModalLinkContainer');
        const link = document.getElementById('newsModalLink');
        if (linkContainer && link) {
            if (article.url && article.url !== '#' && article.url.trim() !== '') {
                link.href = article.url;
                linkContainer.style.display = 'block';
            } else {
                linkContainer.style.display = 'none';
            }
        }

        if (newsModal) newsModal.classList.add('active');
    };

    // === Conference Hall Booking Calendar ===
    let calendarDate = new Date();
    let bookingsList = [];

    // === Vehicle Booking Calendar State ===
    let calendarDateBus = new Date();
    let busBookingsList = [];

    function getLocaleString(lang) {
        if (lang === 'si') return 'si-LK';
        if (lang === 'ta') return 'ta-LK';
        return 'en-US';
    }

    function initCalendar() {
        const prevBtn = document.getElementById('prevMonthBtn');
        const nextBtn = document.getElementById('nextMonthBtn');
        if (!prevBtn || !nextBtn) return;

        prevBtn.addEventListener('click', () => {
            calendarDate.setMonth(calendarDate.getMonth() - 1);
            renderCalendar();
        });

        nextBtn.addEventListener('click', () => {
            calendarDate.setMonth(calendarDate.getMonth() + 1);
            renderCalendar();
        });

        fetchBookings();
    }

    function fetchBookings() {
        fetch('manage_bookings.php?v=' + Date.now())
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                bookingsList = data.bookings;
                renderCalendar();
            }
        })
        .catch(err => console.error("Error fetching bookings:", err));
    }

    function renderCalendar() {
        const monthYearEl = document.getElementById('calendarMonthYear');
        const daysContainer = document.getElementById('calendarDays');
        if (!monthYearEl || !daysContainer) return;

        const year = calendarDate.getFullYear();
        const month = calendarDate.getMonth();

        // Localized Month & Year header
        const locale = getLocaleString(activeLanguage);
        monthYearEl.innerText = calendarDate.toLocaleDateString(locale, { month: 'long', year: 'numeric' });

        // Clear previous days
        daysContainer.innerHTML = '';

        // First day of the month
        const firstDayIndex = new Date(year, month, 1).getDay();
        // Last day of the month
        const lastDay = new Date(year, month + 1, 0).getDate();

        // Empty cells before the first day
        for (let i = 0; i < firstDayIndex; i++) {
            const emptyDiv = document.createElement('div');
            emptyDiv.className = 'calendar-day empty';
            daysContainer.appendChild(emptyDiv);
        }

        // Today's date
        const today = new Date();

        // Populate days
        for (let day = 1; day <= lastDay; day++) {
            const dayDiv = document.createElement('div');
            dayDiv.className = 'calendar-day';
            dayDiv.innerText = day;

            // Date string format YYYY-MM-DD
            const monthStr = String(month + 1).padStart(2, '0');
            const dayStr = String(day).padStart(2, '0');
            const dateStr = `${year}-${monthStr}-${dayStr}`;

            // Check if today
            if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                dayDiv.classList.add('today');
            }

            // Find booking for this date
            const booking = bookingsList.find(b => b.booking_date === dateStr);

            if (booking) {
                dayDiv.classList.add('booked');
                dayDiv.addEventListener('click', () => selectBooking(dateStr, booking, dayDiv));
            } else {
                dayDiv.classList.add('available');
                dayDiv.addEventListener('click', () => selectAvailable(dateStr, dayDiv));
            }

            daysContainer.appendChild(dayDiv);
        }
    }

    function selectBooking(dateStr, booking, element) {
        document.querySelectorAll('#hall-bookings .calendar-day').forEach(el => el.classList.remove('selected'));
        element.classList.add('selected');

        const pane = document.getElementById('bookingDetailsPane');
        const content = document.getElementById('bookingDetailsContent');
        if (!pane || !content) return;

        pane.classList.add('active');

        const vocab = translationsData[activeLanguage] || {};
        const bookedByLabel = vocab['lbl_booked_by'] || 'Booked By';
        const purposeLabel = vocab['lbl_booking_purpose'] || 'Purpose';
        const dateLabel = vocab['lbl_booking_date'] || 'Date';

        content.innerHTML = `
            <div class="details-row-item">
                <div class="details-icon"><i class="fas fa-calendar-day"></i></div>
                <div class="details-info">
                    <span class="details-label-new">${dateLabel}</span>
                    <span class="details-value-new">${dateStr}</span>
                </div>
            </div>
            <div class="details-row-item">
                <div class="details-icon"><i class="fas fa-user-tie"></i></div>
                <div class="details-info">
                    <span class="details-label-new">${bookedByLabel}</span>
                    <span class="details-value-new">${booking.booked_by}</span>
                </div>
            </div>
            <div class="details-row-item">
                <div class="details-icon"><i class="fas fa-bookmark"></i></div>
                <div class="details-info">
                    <span class="details-label-new">${purposeLabel}</span>
                    <span class="details-value-new">${booking.title}</span>
                </div>
            </div>
        `;
    }

    function selectAvailable(dateStr, element) {
        document.querySelectorAll('#hall-bookings .calendar-day').forEach(el => el.classList.remove('selected'));
        element.classList.add('selected');

        const pane = document.getElementById('bookingDetailsPane');
        const content = document.getElementById('bookingDetailsContent');
        if (!pane || !content) return;

        pane.classList.remove('active');

        const vocab = translationsData[activeLanguage] || {};
        const dateLabel = vocab['lbl_booking_date'] || 'Date';
        const availableLabel = vocab['lbl_available'] || 'Available';

        content.innerHTML = `
            <div class="details-row-item">
                <div class="details-icon"><i class="fas fa-calendar-day"></i></div>
                <div class="details-info">
                    <span class="details-label-new">${dateLabel}</span>
                    <span class="details-value-new">${dateStr}</span>
                </div>
            </div>
            <div class="details-row-item">
                <div class="details-icon green-icon"><i class="fas fa-check-circle"></i></div>
                <div class="details-info">
                    <span class="details-value-new status-avail">${availableLabel}</span>
                </div>
            </div>
        `;
    }

    // === Vehicle Booking Calendar Functions ===
    function initBusCalendar() {
        const prevBtn = document.getElementById('prevBusMonthBtn');
        const nextBtn = document.getElementById('nextBusMonthBtn');
        if (!prevBtn || !nextBtn) return;

        prevBtn.addEventListener('click', () => {
            calendarDateBus.setMonth(calendarDateBus.getMonth() - 1);
            renderBusCalendar();
        });

        nextBtn.addEventListener('click', () => {
            calendarDateBus.setMonth(calendarDateBus.getMonth() + 1);
            renderBusCalendar();
        });

        fetchBusBookings();
    }

    function fetchBusBookings() {
        fetch('manage_bus_bookings.php?v=' + Date.now())
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                busBookingsList = data.bookings;
                renderBusCalendar();
            }
        })
        .catch(err => console.error("Error fetching bus bookings:", err));
    }

    function renderBusCalendar() {
        const monthYearEl = document.getElementById('busCalendarMonthYear');
        const daysContainer = document.getElementById('busCalendarDays');
        if (!monthYearEl || !daysContainer) return;

        const year = calendarDateBus.getFullYear();
        const month = calendarDateBus.getMonth();

        // Localized Month & Year header
        const locale = getLocaleString(activeLanguage);
        monthYearEl.innerText = calendarDateBus.toLocaleDateString(locale, { month: 'long', year: 'numeric' });

        // Clear previous days
        daysContainer.innerHTML = '';

        // First day of the month
        const firstDayIndex = new Date(year, month, 1).getDay();
        // Last day of the month
        const lastDay = new Date(year, month + 1, 0).getDate();

        // Empty cells before the first day
        for (let i = 0; i < firstDayIndex; i++) {
            const emptyDiv = document.createElement('div');
            emptyDiv.className = 'calendar-day empty';
            daysContainer.appendChild(emptyDiv);
        }

        // Today's date
        const today = new Date();

        // Populate days
        for (let day = 1; day <= lastDay; day++) {
            const dayDiv = document.createElement('div');
            dayDiv.className = 'calendar-day';
            dayDiv.innerText = day;

            // Date string format YYYY-MM-DD
            const monthStr = String(month + 1).padStart(2, '0');
            const dayStr = String(day).padStart(2, '0');
            const dateStr = `${year}-${monthStr}-${dayStr}`;

            // Check if today
            if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
                dayDiv.classList.add('today');
            }

            // Find booking for this date
            const booking = busBookingsList.find(b => b.booking_date === dateStr);

            if (booking) {
                dayDiv.classList.add('booked');
                dayDiv.addEventListener('click', () => selectBusBooking(dateStr, booking, dayDiv));
            } else {
                dayDiv.classList.add('available');
                dayDiv.addEventListener('click', () => selectBusAvailable(dateStr, dayDiv));
            }

            daysContainer.appendChild(dayDiv);
        }
    }

    function selectBusBooking(dateStr, booking, element) {
        document.querySelectorAll('#bus-bookings .calendar-day').forEach(el => el.classList.remove('selected'));
        element.classList.add('selected');

        const pane = document.getElementById('busBookingDetailsPane');
        const content = document.getElementById('busBookingDetailsContent');
        if (!pane || !content) return;

        pane.classList.add('active');

        const vocab = translationsData[activeLanguage] || {};
        const bookedByLabel = vocab['lbl_booked_by'] || 'Booked By';
        const purposeLabel = vocab['lbl_booking_purpose'] || 'Purpose';
        const dateLabel = vocab['lbl_booking_date'] || 'Date';

        content.innerHTML = `
            <div class="details-row-item">
                <div class="details-icon"><i class="fas fa-calendar-day"></i></div>
                <div class="details-info">
                    <span class="details-label-new">${dateLabel}</span>
                    <span class="details-value-new">${dateStr}</span>
                </div>
            </div>
            <div class="details-row-item">
                <div class="details-icon"><i class="fas fa-user-tie"></i></div>
                <div class="details-info">
                    <span class="details-label-new">${bookedByLabel}</span>
                    <span class="details-value-new">${booking.booked_by}</span>
                </div>
            </div>
            <div class="details-row-item">
                <div class="details-icon"><i class="fas fa-bookmark"></i></div>
                <div class="details-info">
                    <span class="details-label-new">${purposeLabel}</span>
                    <span class="details-value-new">${booking.title}</span>
                </div>
            </div>
        `;
    }

    function selectBusAvailable(dateStr, element) {
        document.querySelectorAll('#bus-bookings .calendar-day').forEach(el => el.classList.remove('selected'));
        element.classList.add('selected');

        const pane = document.getElementById('busBookingDetailsPane');
        const content = document.getElementById('busBookingDetailsContent');
        if (!pane || !content) return;

        pane.classList.remove('active');

        const vocab = translationsData[activeLanguage] || {};
        const dateLabel = vocab['lbl_booking_date'] || 'Date';
        const availableLabel = vocab['lbl_available'] || 'Available';

        content.innerHTML = `
            <div class="details-row-item">
                <div class="details-icon"><i class="fas fa-calendar-day"></i></div>
                <div class="details-info">
                    <span class="details-label-new">${dateLabel}</span>
                    <span class="details-value-new">${dateStr}</span>
                </div>
            </div>
            <div class="details-row-item">
                <div class="details-icon green-icon"><i class="fas fa-check-circle"></i></div>
                <div class="details-info">
                    <span class="details-value-new status-avail">${availableLabel}</span>
                </div>
            </div>
        `;
    }

    // === Global Language Switcher ===
    let activeLanguage = localStorage.getItem('selectedLanguage') || 'en';
    window.setLanguage = setLanguage;

    function setLanguage(lang) {
        if (typeof translationsData === 'undefined' || !translationsData[lang]) return;

        activeLanguage = lang;
        localStorage.setItem('selectedLanguage', lang);

        document.body.classList.remove('lang-en', 'lang-si', 'lang-ta');
        document.body.classList.add('lang-' + lang);

        document.querySelectorAll('#langSwitcher a').forEach(a => {
            if (a.getAttribute('data-lang') === lang) {
                a.classList.add('active');
            } else {
                a.classList.remove('active');
            }
        });

        const vocab = translationsData[lang];

        document.querySelectorAll('[data-i18n]').forEach(el => {
            const key = el.getAttribute('data-i18n');
            if (vocab[key]) {
                el.innerHTML = vocab[key];
            }
        });

        document.querySelectorAll('[data-i18n-placeholder]').forEach(el => {
            const key = el.getAttribute('data-i18n-placeholder');
            if (vocab[key]) {
                el.setAttribute('placeholder', vocab[key]);
            }
        });

        // Update dynamic content headings
        applySiteSettings();
        renderProjectsContent();
        renderStaff();
        renderCalendar();
        renderBusCalendar();
        renderServices();
        fetchDownloads();
    }

    const langLinks = document.querySelectorAll('#langSwitcher a[data-lang]');
    langLinks.forEach(link => {
        link.addEventListener('click', (e) => {
            e.preventDefault();
            const selectedLang = link.getAttribute('data-lang');
            setLanguage(selectedLang);
        });
    });

    // === Global Search Bar Logic ===
    const searchInputs = document.querySelectorAll('.search-bar input');
    const searchBtns = document.querySelectorAll('.search-bar button');

    function executeSearch(query) {
        query = query.toLowerCase().trim();
        if (!query) return;

        // Custom redirections
        if (query.includes("rti") || query.includes("information") || query.includes("තොරතුරු")) {
            const rtiSec = document.querySelector('.rti-container');
            if (rtiSec) rtiSec.scrollIntoView({ behavior: 'smooth' });
            return;
        }

        if (query.includes("suggestion") || query.includes("feedback") || query.includes("යෝජනා")) {
            const sugSec = document.querySelector('.suggestions-container');
            if (sugSec) sugSec.scrollIntoView({ behavior: 'smooth' });
            return;
        }

        if (query.includes("vision") || query.includes("mission") || query.includes("දැක්ම") || query.includes("මෙහෙවර")) {
            const el = document.getElementById("vision");
            if (el) el.scrollIntoView({ behavior: 'smooth' });
            return;
        }

        if (query.includes("contact") || query.includes("phone") || query.includes("email") || query.includes("ලිපිනය") || query.includes("දුරකථන")) {
            const el = document.getElementById("contact");
            if (el) el.scrollIntoView({ behavior: 'smooth' });
            return;
        }

        if (query.includes("service") || query.includes("welfare") || query.includes("සේවා")) {
            openServicesTab('investigations');
            return;
        }

        if (query.includes("download") || query.includes("form") || query.includes("අයදුම්පත්")) {
            openDownloadsTab('formats');
            return;
        }

        if (query.includes("procurement") || query.includes("tender") || query.includes("ප්‍රසම්පාදන")) {
            openProcurementsModal();
            return;
        }

        if (query.includes("gallery") || query.includes("photo") || query.includes("ගැලරිය")) {
            openGalleryModal();
            return;
        }

        alert(`Search results for "${query}" not found. Try 'RTI', 'welfare', 'forms', or 'contact'.`);
    }

    searchBtns.forEach((btn, index) => {
        btn.addEventListener('click', () => {
            if (searchInputs[index]) executeSearch(searchInputs[index].value);
        });
    });

    searchInputs.forEach((input) => {
        input.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                e.preventDefault();
                executeSearch(input.value);
            }
        });
    });

    // === Premium Email Link Helper (Event Delegation) ===
    document.addEventListener('click', function(e) {
        const link = e.target.closest('a[href^="mailto:"]');
        if (!link) return;
        
        e.preventDefault();
        const email = link.getAttribute('href').replace('mailto:', '');
        
        const existingPopup = document.getElementById('emailHelperPopup');
        if (existingPopup) existingPopup.remove();
        
        const popup = document.createElement('div');
        popup.id = 'emailHelperPopup';
        popup.style.cssText = `
            position: absolute;
            background: white;
            border: 1px solid #cbd5e1;
            box-shadow: 0 10px 25px rgba(0,0,0,0.15);
            border-radius: 8px;
            padding: 6px 0;
            z-index: 2500;
            min-width: 220px;
            font-size: 0.85rem;
            display: flex;
            flex-direction: column;
            font-family: sans-serif;
        `;
        
        const rect = link.getBoundingClientRect();
        popup.style.top = `${window.scrollY + rect.bottom + 5}px`;
        popup.style.left = `${window.scrollX + rect.left}px`;
        
        popup.innerHTML = `
            <a href="https://mail.google.com/mail/?view=cm&fs=1&to=${encodeURIComponent(email)}" target="_blank" style="padding: 10px 15px; color: #1e293b; display: flex; align-items: center; gap: 10px; text-decoration: none; transition: 0.2s;"><i class="fab fa-google" style="color: #ea4335; font-size: 1rem;"></i> Open in Gmail</a>
            <a href="https://compose.mail.yahoo.com/?to=${encodeURIComponent(email)}" target="_blank" style="padding: 10px 15px; color: #1e293b; display: flex; align-items: center; gap: 10px; text-decoration: none; transition: 0.2s;"><i class="fab fa-yahoo" style="color: #6001d2; font-size: 1rem;"></i> Open in Yahoo Mail</a>
            <a href="#" id="popupCopyBtn" style="padding: 10px 15px; color: #1e293b; display: flex; align-items: center; gap: 10px; text-decoration: none; transition: 0.2s;"><i class="fas fa-copy" style="color: #64748b; font-size: 1rem;"></i> Copy Email Address</a>
            <a href="mailto:${email}" style="padding: 10px 15px; color: #1e293b; display: flex; align-items: center; gap: 10px; border-top: 1px solid #e2e8f0; text-decoration: none; transition: 0.2s;"><i class="fas fa-envelope" style="color: #1e3a8a; font-size: 1rem;"></i> Use Default Mail App</a>
        `;
        
        document.body.appendChild(popup);
        
        const popupLinks = popup.querySelectorAll('a');
        popupLinks.forEach(pLink => {
            pLink.addEventListener('mouseenter', () => pLink.style.background = '#f1f5f9');
            pLink.addEventListener('mouseleave', () => pLink.style.background = 'transparent');
        });
        
        const copyBtn = popup.querySelector('#popupCopyBtn');
        copyBtn.addEventListener('click', function(eBtn) {
            eBtn.preventDefault();
            navigator.clipboard.writeText(email).then(() => {
                copyBtn.innerHTML = '<i class="fas fa-check" style="color: #10b981; font-size: 1rem;"></i> Address Copied!';
                setTimeout(() => {
                    popup.remove();
                }, 1000);
            });
        });
        
        const closeHandler = function(eWindow) {
            if (!popup.contains(eWindow.target) && eWindow.target !== link && !link.contains(eWindow.target)) {
                popup.remove();
                document.removeEventListener('click', closeHandler);
            }
        };
        setTimeout(() => {
            document.addEventListener('click', closeHandler);
        }, 50);
    });

    // === Suggestions Submission Form Logic ===
    const suggestionForm = document.getElementById('suggestionForm');
    const suggestionStatus = document.getElementById('suggestionStatus');

    if (suggestionForm) {
        suggestionForm.addEventListener('submit', (e) => {
            e.preventDefault();
            
            const name = document.getElementById('sugName').value.trim();
            const email = document.getElementById('sugEmail').value.trim();
            const message = document.getElementById('sugMessage').value.trim();

            if (!name || !email || !message) return;

            const formData = new FormData();
            formData.append('name', name);
            formData.append('email', email);
            formData.append('message', message);

            if (suggestionStatus) {
                suggestionStatus.style.display = 'block';
                suggestionStatus.className = 'form-status';
                suggestionStatus.innerText = 'Submitting suggestion...';
            }

            fetch('submit_suggestion.php', {
                method: 'POST',
                body: formData
            })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    suggestionStatus.className = 'form-status success';
                    suggestionStatus.innerText = translationsData[activeLanguage]?.form_success || 'Suggestion submitted successfully!';
                    suggestionForm.reset();
                } else {
                    suggestionStatus.className = 'form-status error';
                    suggestionStatus.innerText = data.message || 'Failed to submit suggestion.';
                }
            })
            .catch(err => {
                console.error("Failed to submit suggestion:", err);
                suggestionStatus.className = 'form-status error';
                suggestionStatus.innerText = translationsData[activeLanguage]?.form_error || 'Submission failed. Please check network.';
            });
        });
    }

    // === Chatbot Search Assistant Logic ===
    const chatbotBubble = document.getElementById('chatbotBubble');
    const chatbotPanel = document.getElementById('chatbotPanel');
    const closeChatbot = document.getElementById('closeChatbot');
    const chatbotInput = document.getElementById('chatbotInput');
    const chatbotSend = document.getElementById('chatbotSend');
    const chatbotMessages = document.getElementById('chatbotMessages');

    if (chatbotBubble && chatbotPanel) {
        chatbotBubble.addEventListener('click', () => {
            chatbotPanel.classList.add('active');
            chatbotBubble.style.display = 'none';
        });
    }

    if (closeChatbot && chatbotPanel && chatbotBubble) {
        closeChatbot.addEventListener('click', () => {
            chatbotPanel.classList.remove('active');
            chatbotBubble.style.display = 'flex';
        });
    }

    function appendChatMessage(text, sender) {
        if (!chatbotMessages) return;
        const msgDiv = document.createElement('div');
        msgDiv.className = `chat-msg ${sender}`;
        msgDiv.innerHTML = `<div class="msg-bubble">${text}</div>`;
        chatbotMessages.appendChild(msgDiv);
        chatbotMessages.scrollTop = chatbotMessages.scrollHeight;
    }

    function handleChatbotResponse(userText) {
        const text = userText.toLowerCase().trim();
        let response = "";
        let actionHtml = "";
        const lang = activeLanguage;

        // Keywords Matching Matrix (English, Sinhala, Tamil)
        if (text.includes("rti") || text.includes("නිලධාරී") || text.includes("அதிகாரி") || text.includes("information officer") || text.includes("දීප්ති")) {
            const ioName = siteSettings.rti_officer_name?.[lang] || siteSettings.rti_officer_name?.en || "Mrs. Deepthi Pradeepa De Silva";
            const ioTitle = siteSettings.rti_officer_title?.[lang] || siteSettings.rti_officer_title?.en || "Administrative Officer";
            const aoName = siteSettings.rti_appellate_name?.[lang] || siteSettings.rti_appellate_name?.en || "Mrs. G.G. Dilani Gunasinghe";
            const aoTitle = siteSettings.rti_appellate_title?.[lang] || siteSettings.rti_appellate_title?.en || "Provincial Director";

            if (lang === 'si') {
                response = `තොරතුරු දැනගැනීමේ අයිතිය (RTI) නිලධාරීන්ගේ තොරතුරු මෙන්න:<br><b>තොරතුරු නිලධාරී:</b> ${ioName} (${ioTitle})<br><b>නම් කළ නිලධාරී (අභියාචනා):</b> ${aoName} (${aoTitle})`;
            } else if (lang === 'ta') {
                response = `தகவல் அறியும் உரிமை (RTI) அதிகாரிகள் விபரம்:<br><b>தகவல் அதிகாரி:</b> ${ioName} (${ioTitle})<br><b>மேல்முறையீட்டு அதிகாரி:</b> ${aoName} (${aoTitle})`;
            } else {
                response = `Here are the Right to Information (RTI) Officers details:<br><b>Information Officer:</b> ${ioName} (${ioTitle})<br><b>Designated Officer:</b> ${aoName} (${aoTitle})`;
            }
            actionHtml = `<a href="http://www.rticommission.lk" target="_blank" class="bot-link-btn"><i class="fas fa-external-link-alt"></i> Visit RTI Website</a>`;
        } 
        else if (text.includes("contact") || text.includes("phone") || text.includes("address") || text.includes("email") || text.includes("සම්බන්ධතා") || text.includes("ලිපිනය") || text.includes("දුරකථන") || text.includes("முகவரி")) {
            const cAddr = siteSettings.contact_address?.[lang] || siteSettings.contact_address?.en || "Provincial Council Complex, Kurunegala";
            const cPhone = siteSettings.contact_phone?.[lang] || siteSettings.contact_phone?.en || "037-2223483";
            const cEmail = siteSettings.contact_email?.[lang] || siteSettings.contact_email?.en || "socidepnwp@gmail.com";

            if (lang === 'si') {
                response = `<b>සම්බන්ධතා තොරතුරු:</b><br>ලිපිනය: ${cAddr}<br>දුරකථන: ${cPhone}<br>ඊමේල්: ${cEmail}`;
            } else if (lang === 'ta') {
                response = `<b>தொடர்பு விபரங்கள்:</b><br>முகவரி: ${cAddr}<br>தொலைபேசி: ${cPhone}<br>மின்னஞ்சல்: ${cEmail}`;
            } else {
                response = `<b>Contact Information:</b><br>Address: ${cAddr}<br>Phone: ${cPhone}<br>Email: ${cEmail}`;
            }
            actionHtml = `<a href="#" onclick="document.getElementById('contact').scrollIntoView({behavior:'smooth'}); return false;" class="bot-link-btn"><i class="fas fa-map-marker-alt"></i> Scroll to Contact Us</a>`;
        } 
        else if (text.includes("download") || text.includes("form") || text.includes("circular") || text.includes("බාගත") || text.includes("பதிவிறக்க")) {
            if (lang === 'si') {
                response = "අයදුම්පත් සහ චක්‍රලේඛ බාගත කිරීම සඳහා කරුණාකර අපගේ ලේඛන බාගත කිරීම් අංශයට පිවිසෙන්න.";
            } else if (lang === 'ta') {
                response = "விண்ணப்ப படிவங்கள் மற்றும் சுற்றறிக்கைகளை பதிவிறக்கம் செய்ய பதிவிறக்கங்கள் பகுதிக்குச் செல்லவும்.";
            } else {
                response = "Please access the document downloads portal to download application forms and circulars.";
            }
            actionHtml = `<a href="#" onclick="openDownloadsTab('formats'); return false;" class="bot-link-btn"><i class="fas fa-download"></i> Open Downloads Modal</a>`;
        } 
        else if (text.includes("service") || text.includes("elderly") || text.includes("disability") || text.includes("disabled") || text.includes("rehab") || text.includes("සේවා") || text.includes("ආබාධිත") || text.includes("වැඩිහිටි")) {
            if (lang === 'si') {
                response = "අප දෙපාර්තමේන්තුව වැඩිහිටි සත්කාර, ආබාධිත ආධාර, ජීවනෝපාය සහන සහ වෘත්තීය පුහුණු සේවා රැසක් පිරිනමයි.";
            } else if (lang === 'ta') {
                response = "எங்கள் திணைக்களம் முதியோர் பராமரிப்பு, மாற்றுத்திறனாளிகள் உதவி மற்றும் வாழ்வாதார பயிற்சி சேவைகளை வழங்குகிறது.";
            } else {
                response = "Our department provides elderly care, disability assistance, livelihood support, and vocational training services.";
            }
            actionHtml = `<a href="#" onclick="openServicesTab('investigations'); return false;" class="bot-link-btn"><i class="fas fa-heart"></i> Open Services Modal</a>`;
        }
        else if (text.includes("news") || text.includes("announcement") || text.includes("පුවත්") || text.includes("නිවේදන") || text.includes("செய்தி")) {
            if (lang === 'si') {
                response = "නවතම ප්‍රවෘත්ති සහ නිවේදන සඳහා අපගේ මුල් පිටුවේ පුවත් තීරුව සහ නිවේදන පුවරුව නරඹන්න.";
            } else if (lang === 'ta') {
                response = "சமீபத்திய செய்திகள் மற்றும் அறிவிப்புகளுக்கு எங்கள் முகப்புப் பக்கத்தின் செய்திப் பலகையைப் பார்க்கவும்.";
            } else {
                response = "Please check the news and announcements feeds on our home page for the latest updates.";
            }
            actionHtml = `<a href="#" onclick="document.getElementById('news').scrollIntoView({behavior:'smooth'}); return false;" class="bot-link-btn"><i class="fas fa-newspaper"></i> Scroll to News Section</a>`;
        }
        else if (text.includes("procurement") || text.includes("tender") || text.includes("ලංසු") || text.includes("ஒப்பந்த")) {
            if (lang === 'si') {
                response = "සක්‍රීය ප්‍රසම්පාදන සහ ටෙන්ඩර් නිවේදන බැලීමට පහත බොත්තම ක්ලික් කරන්න.";
            } else if (lang === 'ta') {
                response = "செயலில் உள்ள கொள்முதல் மற்றும் ஏல விபரங்களை பார்க்க கீழே சொடுக்கவும்.";
            } else {
                response = "Click the button below to view active procurement and tender notices.";
            }
            actionHtml = `<a href="#" onclick="openProcurementsModal(); return false;" class="bot-link-btn"><i class="fas fa-file-contract"></i> Open Procurements Modal</a>`;
        }
        else {
            if (lang === 'si') {
                response = `ඔබ සොයන "${userText}" පිළිබඳව නිශ්චිත තොරතුරක් හමු නොවුණි. කරුණාකර වෙනත් වචනයකින් සොයන්න හෝ පහත විකල්ප තෝරන්න.`;
            } else if (lang === 'ta') {
                response = `நீங்கள் தேடிய "${userText}" பற்றிய தகவல்கள் கிடைக்கவில்லை. தயவுசெய்து வேறு வார்த்தைகளில் தேடவும்.`;
            } else {
                response = `Sorry, I couldn't find details for "${userText}". Try searching for 'RTI', 'contact', 'forms', 'news' or 'services'.`;
            }
        }

        setTimeout(() => {
            appendChatMessage(response + (actionHtml ? "<br>" + actionHtml : ""), "bot");
        }, 600);
    }

    function processChatbotInput() {
        if (!chatbotInput) return;
        const text = chatbotInput.value.trim();
        if (!text) return;
        appendChatMessage(text, "user");
        chatbotInput.value = "";
        handleChatbotResponse(text);
    }

    if (chatbotSend) {
        chatbotSend.addEventListener('click', processChatbotInput);
    }
    if (chatbotInput) {
        chatbotInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') processChatbotInput();
        });
    }

    // Handle Quick replies
    document.addEventListener('click', (e) => {
        const btn = e.target.closest('.quick-reply-btn');
        if (!btn) return;
        const query = btn.dataset.query;
        let queryLabel = btn.innerText;
        appendChatMessage(queryLabel, "user");
        handleChatbotResponse(query);
    });


    // === Dynamic CMS Loading ===
    let siteSettings = {};
    let globalProjects = [];
    let globalServices = [];

    function loadSiteSettings() {
        fetch('manage_settings.php?v=' + Date.now())
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                siteSettings = data.settings;
                applySiteSettings();
            }
        })
        .catch(err => console.error("Error loading settings:", err));

        fetchProjects();
        fetchDownloads();
        fetchStaff();
        fetchAnnouncements();
        fetchNews();
        fetchCourses();
        fetchLinks();
        fetchBanners();
        fetchServices();
    }

    function applySiteSettings() {
        const lang = activeLanguage;
        
        // About Us descriptions
        const overviewEl = document.getElementById('aboutOverviewText');
        const objectivesEl = document.getElementById('aboutObjectivesText');
        const achievementsEl = document.getElementById('aboutAchievementsText');

        if (overviewEl && siteSettings.about_overview) overviewEl.innerText = siteSettings.about_overview[lang] || siteSettings.about_overview.en;
        if (objectivesEl && siteSettings.about_objectives) objectivesEl.innerText = siteSettings.about_objectives[lang] || siteSettings.about_objectives.en;
        if (achievementsEl && siteSettings.about_achievements) achievementsEl.innerText = siteSettings.about_achievements[lang] || siteSettings.about_achievements.en;

        // Vision & Mission
        const visionTextEl = document.getElementById('visionText');
        const missionTextEl = document.getElementById('missionText');
        if (visionTextEl && siteSettings.site_vision) visionTextEl.innerText = siteSettings.site_vision[lang] || siteSettings.site_vision.en;
        if (missionTextEl && siteSettings.site_mission) missionTextEl.innerText = siteSettings.site_mission[lang] || siteSettings.site_mission.en;
        const newsBarTextEl = document.getElementById('newsBarText');
        if (newsBarTextEl && siteSettings.news_bar) {
            newsBarTextEl.innerHTML = siteSettings.news_bar[lang] || siteSettings.news_bar.en;
        }

        // Services Modal bullet lists
        const populateServiceList = (elId, rawText) => {
            const listEl = document.getElementById(elId);
            if (!listEl) return;
            if (!rawText) {
                listEl.innerHTML = '';
                return;
            }
            const lines = rawText.split('\n').map(l => l.trim()).filter(l => l.length > 0);
            listEl.innerHTML = lines.map(line => `
                <li><i class="fas fa-check-circle" style="color:var(--primary-light); margin-right: 8px;"></i> <span>${line}</span></li>
            `).join('');
        };

        populateServiceList('servicesInvestigationsList', siteSettings.service_inv_list ? (siteSettings.service_inv_list[lang] || siteSettings.service_inv_list.en) : '');
        populateServiceList('servicesEngineeringList', siteSettings.service_eng_list ? (siteSettings.service_eng_list[lang] || siteSettings.service_eng_list.en) : '');
        populateServiceList('servicesConstructionList', siteSettings.service_const_list ? (siteSettings.service_const_list[lang] || siteSettings.service_const_list.en) : '');
        populateServiceList('servicesOperationList', siteSettings.service_op_list ? (siteSettings.service_op_list[lang] || siteSettings.service_op_list.en) : '');
        populateServiceList('servicesInstitutionalList', siteSettings.service_inst_list ? (siteSettings.service_inst_list[lang] || siteSettings.service_inst_list.en) : '');

        const engDescEl = document.getElementById('servicesEngineeringDesc');
        if (engDescEl && siteSettings.service_eng_desc) {
            engDescEl.innerText = siteSettings.service_eng_desc[lang] || siteSettings.service_eng_desc.en;
        }

        const constDescEl = document.getElementById('servicesConstructionDesc');
        if (constDescEl && siteSettings.service_const_desc) {
            constDescEl.innerText = siteSettings.service_const_desc[lang] || siteSettings.service_const_desc.en;
        }

        // Organization Chart image
        const orgChartImageEl = document.getElementById('orgChartImage');
        if (orgChartImageEl && siteSettings.org_chart_url) {
            orgChartImageEl.src = siteSettings.org_chart_url[lang] || siteSettings.org_chart_url.en;
        }

        // Header Branding Logos & Titles
        const natLogoEl = document.getElementById('nationalLogo');
        const provLogoEl = document.getElementById('provincialLogo');
        const headTitleEnEl = document.getElementById('headerTitleEn');
        const headTitleSiEl = document.getElementById('headerTitleSi');
        const headTitleTaEl = document.getElementById('headerTitleTa');

        if (natLogoEl && siteSettings.header_national_logo) {
            const logoSrc = siteSettings.header_national_logo[lang] || siteSettings.header_national_logo.en;
            natLogoEl.src = logoSrc ? logoSrc : 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5f/Emblem_of_Sri_Lanka.svg/200px-Emblem_of_Sri_Lanka.svg.png';
        }
        if (provLogoEl && siteSettings.header_provincial_logo) {
            const logoSrc = siteSettings.header_provincial_logo[lang] || siteSettings.header_provincial_logo.en;
            provLogoEl.src = logoSrc ? logoSrc : 'Nwp_sri_lanka.png';
        }
        if (headTitleEnEl && siteSettings.header_title_en) {
            headTitleEnEl.innerText = siteSettings.header_title_en[lang] || siteSettings.header_title_en.en;
        }
        if (headTitleSiEl && siteSettings.header_title_si) {
            headTitleSiEl.innerText = siteSettings.header_title_si[lang] || siteSettings.header_title_si.en;
        }
        if (headTitleTaEl && siteSettings.header_title_ta) {
            headTitleTaEl.innerText = siteSettings.header_title_ta[lang] || siteSettings.header_title_ta.en;
        }

        // Citizen's Charter links
        const charterSiLinkEl = document.getElementById('charterSiLink');
        const charterEnLinkEl = document.getElementById('charterEnLink');
        if (charterSiLinkEl && siteSettings.citizen_charter_si_url) {
            charterSiLinkEl.href = siteSettings.citizen_charter_si_url[lang] || siteSettings.citizen_charter_si_url.en;
        }
        if (charterEnLinkEl && siteSettings.citizen_charter_en_url) {
            charterEnLinkEl.href = siteSettings.citizen_charter_en_url[lang] || siteSettings.citizen_charter_en_url.en;
        }

        // RTI Application links
        const rtiAppSiLinkEl = document.getElementById('rtiAppSiLink');
        const rtiAppEnLinkEl = document.getElementById('rtiAppEnLink');
        const rtiAppTaLinkEl = document.getElementById('rtiAppTaLink');
        if (rtiAppSiLinkEl && siteSettings.rti_app_si_url) {
            rtiAppSiLinkEl.href = siteSettings.rti_app_si_url[lang] || siteSettings.rti_app_si_url.en;
        }
        if (rtiAppEnLinkEl && siteSettings.rti_app_en_url) {
            rtiAppEnLinkEl.href = siteSettings.rti_app_en_url[lang] || siteSettings.rti_app_en_url.en;
        }
        if (rtiAppTaLinkEl && siteSettings.rti_app_ta_url) {
            rtiAppTaLinkEl.href = siteSettings.rti_app_ta_url[lang] || siteSettings.rti_app_ta_url.en;
        }

        // Fax
        const faxEl = document.getElementById('footerContactFax');
        if (faxEl && siteSettings.contact_fax) {
            faxEl.innerHTML = `<i class="fas fa-fax"></i> ` + (siteSettings.contact_fax[lang] || siteSettings.contact_fax.en);
        }

        // Social Links
        const ytLinkEl = document.getElementById('footerYoutubeLink');
        const fbLinkEl = document.getElementById('footerFacebookLink');
        if (ytLinkEl && siteSettings.social_youtube) {
            ytLinkEl.href = siteSettings.social_youtube[lang] || siteSettings.social_youtube.en;
        }
        if (fbLinkEl && siteSettings.social_facebook) {
            fbLinkEl.href = siteSettings.social_facebook[lang] || siteSettings.social_facebook.en;
        }
    }

    function fetchDownloads() {
        const formatsList = document.getElementById('formats-list');
        const transfersList = document.getElementById('transfers-list');
        const circularsList = document.getElementById('circulars-list');
        const ratesList = document.getElementById('rates-list');

        fetch('manage_downloads.php?v=' + Date.now())
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                const renderCategory = (listEl, catName) => {
                    if (!listEl) return;
                    const filtered = data.downloads.filter(d => d.category === catName);

                    if (filtered.length > 0) {
                        listEl.innerHTML = filtered.map(d => {
                            const lang = activeLanguage;
                            const title = (lang === 'si' && d.title_si) ? d.title_si : ((lang === 'ta' && d.title_ta) ? d.title_ta : d.title);
                            const desc = (lang === 'si' && d.description_si) ? d.description_si : ((lang === 'ta' && d.description_ta) ? d.description_ta : d.description);
                            const fileUrl = (lang === 'si' && d.file_url_si) ? d.file_url_si : ((lang === 'ta' && d.file_url_ta) ? d.file_url_ta : d.file_url);
                            const vocab = translationsData[lang] || {};
                            const btnText = vocab['btn_view_document'] || 'View / Download';

                            return `
                                <div class="download-item">
                                    <div class="download-info">
                                        <div class="icon-box"><i class="fas ${d.icon_class || 'fa-file-alt'}"></i></div>
                                        <div class="dl-text">
                                            <strong>${title}</strong>
                                            <p>${desc || ''}</p>
                                        </div>
                                    </div>
                                    <a href="${fileUrl}" class="dl-btn" style="text-decoration:none;" target="_blank">
                                        <i class="fas fa-file-pdf"></i> ${btnText}
                                    </a>
                                </div>
                            `;
                        }).join('');
                    } else {
                        listEl.innerHTML = '<p style="padding:10px; color:#64748b;">No files available in this category.</p>';
                    }
                };

                renderCategory(formatsList, 'forms');
                renderCategory(transfersList, 'transfers');
                renderCategory(circularsList, 'circulars');
                renderCategory(ratesList, 'rates');
            }
        })
        .catch(err => console.error("Downloads load failed:", err));
    }

    let globalStaff = [];
    function fetchStaff() {
        fetch('manage_officers.php?v=' + Date.now())
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                globalStaff = data.officers;
                renderStaff();
            }
        })
        .catch(err => console.error("Staff load failed:", err));
    }

    function renderStaff() {
        const staffBody = document.getElementById('aboutStaffTableBody');
        if (!staffBody || globalStaff.length === 0) return;

        const lang = activeLanguage;
        
        const localDict = {
            si: {
                // Names
                "T H M D C E Peiris": "ටී. එච්. එම්. ඩී. සී. ඊ. පීරිස් මිය",
                "E M S B Ekanayake": "ඊ. එම්. එස්. බී. ඒකනායක මිය",
                "S D D Rajapakshe": "එස්. ඩී. ඩී. රාජපක්ෂ මයා",
                "W M H D K Wickramasinghe": "ඩබ්ලිව්. එම්. එච්. ඩී. කේ. වික්‍රමසිංහ මයා",
                "D M B Dissanayake": "ඩී. එම්. බී. දිසානායක මයා",
                "R A N A Bandara": "ආර්. ඒ. එන්. ඒ. බණ්ඩාර මයා",
                "G A K M Bandara": "ජී. ඒ. කේ. එම්. බණ්ඩාර මයා",
                "I A A L Jayasinghe": "අයි. ඒ. ඒ. එල්. ජයසිංහ මයා",
                "Deepthi Pradeepa De Silva": "දීප්ති ප්‍රදීපා ද සිල්වා මිය",
                "G.G. Dilani Gunasinghe": "ජී.ජී. දිලානි ගුණසිංහ මිය",
                "R. M. Pathirana": "ආර්. එම්. පතිරණ මිය",
                "H. K. N. Herath": "එච්. කේ. එන්. හේරත් මයා",
                
                // Titles
                "Administrative Officer": "පරිපාලන නිලධාරී",
                "Provincial Director": "පළාත් අධ්‍යක්ෂ",
                "Additional director": "අතිරේක අධ්‍යක්ෂ",
                "Chief Accountant": "ප්‍රධාන ගණකාධිකාරී",
                "Chief Engineer": "ප්‍රධාන ඉංජිනේරු",
                "Chief Engineer (Education)": "ප්‍රධාන ඉංජිනේරු (අධ්‍යාපන)",
                "Chief Engineer (Structural Design & O)": "ප්‍රධාන ඉංජිනේරු (ව්‍යුහාත්මක සැලසුම්)",
                "Chief Engineer (Health)": "ප්‍රධාන ඉංජිනේරු (සෞඛ්‍ය)",
                "Divisional Engineer": "කොට්ඨාස ඉංජිනේරු",
                "Provincial Director - Social Services NWP": "පළාත් සමාජ සේවා අධ්‍යක්ෂ - වයඹ",
                "Provincial Director - Department of Social Services NWP": "පළාත් සමාජ සේවා අධ්‍යක්ෂ - වයඹ",
                "Assistant Director": "සහකාර අධ්‍යක්ෂ",
                
                // Divisions
                "Head Office": "ප්‍රධාන කාර්යාලය",
                "Kurunegala": "කුරුණෑගල",
                "Kuliyapitiya": "කුලියාපිටිය"
            },
            ta: {
                // Names
                "T H M D C E Peiris": "டி. எச். எம். டி. சி. ஈ. பீரிஸ்",
                "E M S B Ekanayake": "ஈ. எம். எஸ். பி. ஏகநாயக்க",
                "S D D Rajapakshe": "எஸ். டி. டி. ராஜபக்ஷ",
                "W M H D K Wickramasinghe": "டபிள்யூ. எம். எச். டி. கே. விக்ரமசிங்க",
                "D M B Dissanayake": "டி. எம். பி. திஸாநாயக்க",
                "R A N A Bandara": "ஆர். ஏ. என். ஏ. பண்டார",
                "G A K M Bandara": "ஜி. ஏ. கே. எம். பண்டார",
                "I A A L Jayasinghe": "ஐ. ஏ. ஏ. எல். ஜெயசிங்க",
                "Deepthi Pradeepa De Silva": "திருமதிகள். தீப்தி பிரதீபா த சில்வா",
                "G.G. Dilani Gunasinghe": "திருமதி. ஜி.ஜி. திலானி குணசிங்க",
                "R. M. Pathirana": "திருமதி. ஆர். எம். பத்திரண",
                "H. K. N. Herath": "திரு. எச். கே. என். ஹேரத்",
                
                // Titles
                "Administrative Officer": "நிர்வாக அதிகாரி",
                "Provincial Director": "மாகாண பணிப்பாளர்",
                "Additional director": "கூடுதல் பணிப்பாளர்",
                "Chief Accountant": "தலைமை கணக்காளர்",
                "Chief Engineer": "தலைமை பொறியாளர்",
                "Chief Engineer (Education)": "தலைமை பொறியாளர் (கல்வி)",
                "Chief Engineer (Structural Design & O)": "தலைமை பொறியாளர் (கட்டமைப்பு வடிவமைப்பு)",
                "Chief Engineer (Health)": "தலைமை பொறியாளர் (சுகாதாரம்)",
                "Divisional Engineer": "பிரிவு பொறியாளர்",
                "Provincial Director - Social Services NWP": "மாகாண சமூக சேவைகள் பணிப்பாளர் - வடமேல்",
                "Provincial Director - Department of Social Services NWP": "மாகாண சமூக சேவைகள் பணிப்பாளர் - வடமேல்",
                "Assistant Director": "உதவி பணிப்பாளர்",
                
                // Divisions
                "Head Office": "தலைமை அலுவலகம்",
                "Kurunegala": "குருநாகல்",
                "Kuliyapitiya": "குளியாப்பிட்டிய"
            }
        };

        const normalize = (str) => {
            if (!str) return '';
            // Collapse whitespace, remove "Mrs. ", "Mr. " prefix case-insensitively, and trim
            return str.replace(/\s+/g, ' ').replace(/^(mrs\.|mr\.)\s*/i, '').trim();
        };

        const t = (val, group) => {
            if (lang === 'en') return val;
            const normVal = normalize(val);
            if (localDict[lang] && localDict[lang][group]) {
                const dict = localDict[lang][group];
                // Direct lookup on exact matching key
                if (dict[val]) return dict[val];
                // Try looking up using normalized keys
                for (let key in dict) {
                    if (normalize(key) === normVal) {
                        return dict[key];
                    }
                }
            }
            return val;
        };

        let index = 1;
        staffBody.innerHTML = globalStaff.map(o => {
            const photoHtml = o.photo_url 
                ? `<img src="${o.photo_url}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" style="width: 64px; height: 64px; object-fit: cover; border-radius: 50%; border: 3px solid #ffffff; box-shadow: 0 4px 10px rgba(0,0,0,0.08); display: block; margin: 0 auto;">
                   <div style="display: none; width: 64px; height: 64px; border-radius: 50%; background: #f1f5f9; align-items: center; justify-content: center; margin: 0 auto; color: #94a3b8; font-size: 1.6rem; border: 3px solid #ffffff; box-shadow: 0 4px 10px rgba(0,0,0,0.08);"><i class="fas fa-user-tie"></i></div>`
                : `<div style="width: 64px; height: 64px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: #94a3b8; font-size: 1.6rem; border: 3px solid #ffffff; box-shadow: 0 4px 10px rgba(0,0,0,0.08);"><i class="fas fa-user-tie"></i></div>`;
            
            const translatedName = t(o.name, 'names');
            const translatedTitle = t(o.title, 'titles');
            const translatedDiv = t(o.division || 'Head Office', 'divisions');

            return `
                <tr>
                    <td style="text-align: center; vertical-align: middle; color: #4b5563;">${index++}</td>
                    <td style="text-align: center; vertical-align: middle;">${photoHtml}</td>
                    <td style="vertical-align: middle;"><strong style="font-size: 0.95rem; color: #111827; display: block;">${translatedName}</strong></td>
                    <td style="vertical-align: middle; color: #374151;">${translatedTitle}</td>
                    <td style="text-align: center; vertical-align: middle; color: #374151;">${translatedDiv}</td>
                    <td style="vertical-align: middle;">
                        ${o.email ? `<a href="mailto:${o.email}" style="color: var(--primary-light); font-weight: 500; display: inline-flex; align-items: center; gap: 8px;"><i class="far fa-envelope" style="color: var(--primary-light);"></i> ${o.email}</a>` : '<span style="color:#94a3b8;">N/A</span>'}
                    </td>
                </tr>
            `;
        }).join('');
    }function fetchProcurements() {
        const procurementsList = document.getElementById('procurementsList');
        if (!procurementsList) return;
        procurementsList.innerHTML = '<p style="padding:20px; text-align:center; color:#64748b;">Loading procurement notices...</p>';

        fetch('manage_procurements.php?v=' + Date.now())
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success' && data.procurements.length > 0) {
                procurementsList.innerHTML = data.procurements.map(p => `
                    <div class="download-item">
                        <div class="download-info">
                            <div class="icon-box"><i class="fas fa-file-pdf"></i></div>
                            <div class="dl-text">
                                <strong>${p.title}</strong>
                                <p>Published on: ${p.publish_date} | Status: <span style="font-weight:600; color:${p.status === 'active' ? '#10b981' : '#ef4444'};">${p.status.toUpperCase()}</span></p>
                            </div>
                        </div>
                        <a href="${p.file_url}" class="dl-btn" target="_blank" style="text-decoration:none;">
                            <i class="fas fa-cloud-download-alt"></i> View Notice
                        </a>
                    </div>
                `).join('');
            } else {
                procurementsList.innerHTML = '<p style="padding:20px; text-align:center; color:#64748b;">No active procurement notices available.</p>';
            }
        })
        .catch(err => {
            console.error("Tenders load failed:", err);
            procurementsList.innerHTML = '<p style="padding:20px; text-align:center; color:#dc2626;">Failed to load notices. Please try again.</p>';
        });
    }

    function fetchGallery() {
        const galleryGrid = document.getElementById('galleryGrid');
        if (!galleryGrid) return;
        galleryGrid.innerHTML = '<p style="padding:20px; text-align:center; color:#64748b; grid-column: 1 / -1;">Loading gallery...</p>';

        fetch('manage_gallery.php?v=' + Date.now())
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success' && data.gallery.length > 0) {
                galleryGrid.innerHTML = data.gallery.map(g => `
                    <div class="gallery-item">
                        <img src="${g.image_url}" alt="${g.title}" onclick="window.open('${g.image_url}', '_blank')">
                        <div class="gallery-overlay">${g.title}</div>
                    </div>
                `).join('');
            } else {
                galleryGrid.innerHTML = '<p style="padding:20px; text-align:center; color:#64748b; grid-column: 1 / -1;">No photos available in gallery.</p>';
            }
        })
        .catch(err => {
            console.error("Gallery load failed:", err);
            galleryGrid.innerHTML = '<p style="padding:20px; text-align:center; color:#dc2626; grid-column: 1 / -1;">Failed to load gallery.</p>';
        });
    }

    function fetchProjects() {
        fetch('manage_projects.php?v=' + Date.now())
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                globalProjects = data.projects;
                renderProjectsContent();
            }
        })
        .catch(err => console.error("Projects load failed:", err));
    }

    function renderProjectsContent() {
        const lang = activeLanguage;
        
        const summaryPane = document.getElementById('projSummaryContainer');
        const keyPane = document.getElementById('projKeyContainer');
        const completedPane = document.getElementById('projCompletedContainer');

        const getProjectImagesHtml = (imageUrl) => {
            if (!imageUrl) return '';
            let images = [];
            if (imageUrl.startsWith('[')) {
                try {
                    images = JSON.parse(imageUrl);
                } catch (e) {
                    images = [imageUrl];
                }
            } else {
                images = [imageUrl];
            }
            if (images.length === 0) return '';
            return `<img src="${images[0]}" style="width: 120px; height: 90px; object-fit: cover; border-radius: 8px; border:1px solid #ddd;" alt="">`;
        };

        const renderPaneList = (paneEl, cat) => {
            if (!paneEl) return;
            const filtered = globalProjects.filter(p => p.category === cat);
            if (filtered.length > 0) {
                paneEl.innerHTML = filtered.map(p => `
                    <div class="download-item" style="align-items: flex-start; gap: 15px; flex-direction: row; text-align: left; padding:15px; margin-bottom:12px;">
                        ${getProjectImagesHtml(p.image_url)}
                        <div class="dl-text" style="flex:1;">
                            <strong style="color:var(--primary-blue); font-size:1.05rem;">${p['title_' + lang] || p.title_en}</strong>
                            <p style="margin: 5px 0; line-height:1.4; font-size:0.88rem;">${p['description_' + lang] || p.description_en}</p>
                            ${p.financial_details ? `<span style="font-weight: 600; color: var(--accent-gold); font-size:0.85rem;">${p.financial_details}</span>` : ''}
                        </div>
                    </div>
                `).join('');
            } else {
                paneEl.innerHTML = '<p style="padding:15px; color:#64748b;">No details available.</p>';
            }
        };

        renderPaneList(summaryPane, 'summary');
        renderPaneList(keyPane, 'key');
        renderPaneList(completedPane, 'completed');
    }

    let globalAnnouncements = [];
    function fetchAnnouncements() {
        fetch('manage_announcements.php?v=' + Date.now())
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                globalAnnouncements = data.announcements;
                const activeTab = document.querySelector('#announcementTabs .tab-btn.active')?.getAttribute('data-tab') || 'internal';
                renderAnnouncements(activeTab);
            }
        })
        .catch(err => console.error("Error fetching announcements:", err));
    }
    function renderAnnouncements(category) {
        const list = document.getElementById('announcementsList');
        if (!list) return;
        const filtered = globalAnnouncements.filter(a => a.category === category);
        if (filtered.length > 0) {
            list.innerHTML = filtered.map(a => `
                <li>
                    <a href="${a.url || '#'}">
                        <i class="fas fa-caret-right"></i> ${a.title}
                        ${a.badge ? `<span class="badge">${a.badge}</span>` : ''}
                    </a>
                </li>
            `).join('');
        } else {
            list.innerHTML = '<li style="color:#64748b; font-style:italic; padding-left:10px;">No announcements available.</li>';
        }
    }

    let globalNews = [];
    function fetchNews() {
        fetch('manage_news.php?v=' + Date.now())
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                globalNews = data.news;
                const activeTab = document.querySelector('#newsTabs .tab-btn.active')?.getAttribute('data-tab') || 'dept-news';
                renderNews(activeTab);
            }
        })
        .catch(err => console.error("Error fetching news:", err));
    }
    function renderNews(category) {
        const list = document.getElementById('newsList');
        if (!list) return;
        const filtered = globalNews.filter(n => n.category === category);
        if (filtered.length > 0) {
            list.innerHTML = filtered.map(n => `
                <div class="news-card">
                    ${n.image_url ? `<img src="${n.image_url}" alt="News Image" loading="lazy">` : ''}
                    <div class="news-info">
                        <h4>${n.title}</h4>
                        <span class="news-date"><i class="far fa-calendar-alt"></i> ${n.news_date}</span>
                        <p>${n.content}</p>
                        <a href="#" class="read-more" onclick="openNewsModal(${n.id}); return false;">View more »</a>
                    </div>
                </div>
            `).join('');
        } else {
            list.innerHTML = '<div style="color:#64748b; padding:20px; text-align:center; font-style:italic;">No news articles available.</div>';
        }
    }

    let globalCourses = [];
    function fetchCourses() {
        fetch('manage_courses.php?v=' + Date.now())
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                globalCourses = data.courses;
                const activeTab = document.querySelector('#coursesTabs .tab-btn.active')?.getAttribute('data-tab') || 'upcoming';
                renderCourses(activeTab);
            }
        })
        .catch(err => console.error("Error fetching courses:", err));
    }
    function renderCourses(category) {
        const list = document.getElementById('coursesList');
        if (!list) return;
        const filtered = globalCourses.filter(c => c.category === category);
        if (filtered.length > 0) {
            list.innerHTML = filtered.map(c => `
                <li>
                    <div class="course-item" onclick="if('${c.url}' !== '#') window.open('${c.url}', '_blank');" style="cursor: ${c.url !== '#' ? 'pointer' : 'default'};">
                        <div class="course-icon"><i class="fas ${c.icon_class || 'fa-graduation-cap'}"></i></div>
                        <div class="course-info">
                            <strong>${c.title}</strong>
                            <span><i class="far fa-calendar-alt"></i> ${c.event_date} | ${c.location}</span>
                        </div>
                    </div>
                </li>
            `).join('');
        } else {
            list.innerHTML = '<li style="color:#64748b; padding:20px; text-align:center; font-style:italic; list-style:none;">No sessions available.</li>';
        }
    }

    let globalLinks = [];
    function fetchLinks() {
        fetch('manage_links.php?v=' + Date.now())
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                globalLinks = data.links;
                const activeTab = document.querySelector('#linksTabs .tab-btn.active')?.getAttribute('data-tab') || 'govt-links';
                renderLinks(activeTab);
            }
        })
        .catch(err => console.error("Error fetching links:", err));
    }
    function renderLinks(category) {
        const list = document.getElementById('linksList');
        if (!list) return;
        const filtered = globalLinks.filter(l => l.category === category);
        if (filtered.length > 0) {
            list.innerHTML = filtered.map(l => `
                <li style="display: flex; align-items: center; gap: 12px; margin-bottom: 10px;">
                    ${l.image_url ? `<img src="${l.image_url}" alt="Icon" loading="lazy" onerror="this.style.display='none'; this.nextElementSibling.style.display='inline-flex';"><i class="fas fa-external-link-alt link-icon-placeholder" style="display:none; color:var(--primary-light);"></i>` : '<i class="fas fa-external-link-alt link-icon-placeholder" style="color:var(--primary-light);"></i>'}
                    <a href="${l.url}" target="_blank" style="text-decoration:none; color:var(--text-dark); font-weight:500; font-size:0.95rem;">${l.title}</a>
                </li>
            `).join('');
        } else {
            list.innerHTML = '<li style="color:#64748b; padding:10px; font-style:italic; list-style:none;">No links configured.</li>';
        }
    }

    function fetchServices() {
        fetch('manage_services.php?v=' + Date.now())
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                globalServices = data.services;
                renderServices();
            }
        })
        .catch(err => console.error("Error fetching services:", err));
    }

    window.selectActiveService = function(serviceId) {
        const tabBtns = document.querySelectorAll('.services-tab-btn');
        tabBtns.forEach(b => b.classList.remove('active'));

        const activeBtn = document.querySelector(`.services-tab-btn[data-id="${serviceId}"]`);
        if (activeBtn) {
            activeBtn.classList.add('active');
        }

        const s = globalServices.find(item => item.id == serviceId);
        const contentArea = document.getElementById('servicesContentArea');
        if (!s || !contentArea) return;

        const lang = activeLanguage;
        const title = s['title_' + lang] || s.title_en;
        const shortDesc = s['short_desc_' + lang] || s.short_desc_si || s.short_desc_en;
        const bulletsText = s['bullets_' + lang] || s.bullets_en || '';
        const longDesc = s['long_desc_' + lang] || s.long_desc_en || '';

        // Find standard numbering
        const idx = globalServices.findIndex(item => item.id == serviceId);
        const serviceNum = idx !== -1 ? idx + 1 : '';

        // Build bullet list HTML if it exists
        let bulletsHtml = '';
        if (bulletsText.trim()) {
            const lines = bulletsText.split('\n').map(l => l.trim()).filter(l => l.length > 0);
            bulletsHtml = `
                <div class="services-list-container" style="display: flex; flex-direction: column; background: white; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.02); margin-top: 15px;">
                    ${lines.map((line, index) => `
                        <div class="service-list-row" style="display: flex; align-items: flex-start; gap: 12px; padding: 14px 18px; border-bottom: ${index === lines.length - 1 ? 'none' : '1px solid #f1f5f9'};">
                            <i class="fas fa-check-circle" style="color: #2563eb; font-size: 1.05rem; margin-top: 3px; flex-shrink: 0;"></i> 
                            <span style="font-family: 'Noto Sans Sinhala', 'Outfit', sans-serif; font-size: 0.94rem; color: #1e293b; line-height: 1.5; font-weight: 500;">${line}</span>
                        </div>
                    `).join('')}
                </div>
            `;
        }

        let longDescHtml = '';
        if (longDesc.trim()) {
            longDescHtml = `
                <div style="margin-top: 20px; font-style: italic; color: #475569; font-size: 0.94rem; border-left: 4px solid var(--accent-gold); padding: 12px 18px; background: #f8fafc; border-radius: 0 8px 8px 0; font-family: 'Noto Sans Sinhala', 'Outfit', sans-serif; line-height: 1.6;">
                    ${longDesc}
                </div>
            `;
        }

        contentArea.innerHTML = `
            <h2 class="content-title" style="font-family: 'Noto Sans Sinhala', 'Outfit', sans-serif; font-weight: 600; font-size: 1.35rem; color: var(--text-dark); margin-bottom: 12px;">
                ${serviceNum ? serviceNum + '. ' : ''}${title}
            </h2>
            <hr style="border: none; height: 3px; background-color: var(--primary-light); margin-bottom: 20px; border-radius: 2px;">
            <p style="margin: 0; font-size: 0.95rem; line-height: 1.6; color: #475569; font-family: 'Noto Sans Sinhala', 'Outfit', sans-serif;">${shortDesc}</p>
            ${bulletsHtml}
            ${longDescHtml}
        `;
    };

    function renderServices() {
        const sidebarNav = document.getElementById('servicesSidebarNav');
        if (!sidebarNav) return;

        const lang = activeLanguage;

        if (globalServices.length > 0) {
            sidebarNav.innerHTML = globalServices.map(s => {
                const title = s['title_' + lang] || s.title_en;
                return `
                    <li>
                        <a href="#" class="services-tab-btn" data-id="${s.id}" style="display: flex; align-items: center; gap: 12px; padding: 12px 20px; font-size: 0.9rem; font-weight: 600; transition: 0.2s;">
                            <i class="${s.icon_class || 'fa-concierge-bell'}" style="width: 20px; text-align: center;"></i>
                            <span style="font-family: 'Noto Sans Sinhala', 'Outfit', sans-serif;">${title}</span>
                        </a>
                    </li>
                `;
            }).join('');

            // Attach event listeners
            document.querySelectorAll('.services-tab-btn').forEach(btn => {
                btn.addEventListener('click', (e) => {
                    e.preventDefault();
                    const serviceId = btn.getAttribute('data-id');
                    selectActiveService(serviceId);
                });
            });

            // Find currently active service or select the first one
            const activeBtn = document.querySelector('.services-tab-btn.active');
            let activeId = activeBtn ? activeBtn.getAttribute('data-id') : globalServices[0].id;
            selectActiveService(activeId);
        } else {
            sidebarNav.innerHTML = '<p style="padding:15px; color:#64748b; font-style:italic;">No services available.</p>';
            const contentArea = document.getElementById('servicesContentArea');
            if (contentArea) {
                contentArea.innerHTML = '<p style="padding:20px; color:#64748b; font-style:italic; text-align:center;">No services available.</p>';
            }
        }
    }

    // Initialize translations and default selections
    let storedLang = localStorage.getItem('selectedLanguage') || 'en';
    
    // Fallback if translations.json loading fails
    if (typeof translationsData !== 'undefined') {
        setLanguage(storedLang);
    }
    
    loadSiteSettings();
    initCalendar();
    initBusCalendar();

});
