// Custom animated toast notifications
window.showToast = function(message, type = 'success') {
    const container = document.getElementById('toastContainer');
    if (!container) return;
    
    const toast = document.createElement('div');
    toast.className = `toast-msg ${type}`;
    
    let icon = 'fa-check-circle';
    if (type === 'error') icon = 'fa-exclamation-circle';
    if (type === 'warning') icon = 'fa-exclamation-triangle';
    
    toast.innerHTML = `
        <i class="fas ${icon}"></i>
        <span>${message}</span>
    `;
    
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'toastOut 0.3s ease-in forwards';
        toast.addEventListener('animationend', () => {
            toast.remove();
        });
    }, 3500);
};

// Custom modal confirmation prompt
window.showConfirm = function(title, message) {
    return new Promise((resolve) => {
        const modal = document.getElementById('confirmModal');
        const titleEl = document.getElementById('confirmTitle');
        const messageEl = document.getElementById('confirmMessage');
        const btnYes = document.getElementById('confirmBtnYes');
        const btnNo = document.getElementById('confirmBtnNo');
        
        if (!modal) {
            resolve(confirm(message));
            return;
        }
        
        titleEl.innerText = title;
        messageEl.innerText = message;
        modal.classList.add('active');
        
        const cleanup = (value) => {
            modal.classList.remove('active');
            btnYes.removeEventListener('click', onYes);
            btnNo.removeEventListener('click', onNo);
            resolve(value);
        };
        
        const onYes = () => cleanup(true);
        const onNo = () => cleanup(false);
        
        btnYes.addEventListener('click', onYes);
        btnNo.addEventListener('click', onNo);
    });
};

// Live image previews
window.showLivePreview = function(inputEl, previewImgId) {
    const previewImg = document.getElementById(previewImgId);
    if (!previewImg) return;
    const url = inputEl.value.trim();
    if (url) {
        previewImg.src = url;
        previewImg.style.display = 'block';
    } else {
        previewImg.style.display = 'none';
        previewImg.src = '';
    }
};

// Live list filter
window.filterTable = function(input, tbodyId) {
    const filter = input.value.toLowerCase().trim();
    const tbody = document.getElementById(tbodyId);
    if (!tbody) return;
    const rows = tbody.getElementsByTagName('tr');
    for (let i = 0; i < rows.length; i++) {
        const row = rows[i];
        if (row.cells.length === 1 && row.cells[0].colSpan > 1) continue;
        
        let match = false;
        for (let j = 0; j < row.cells.length; j++) {
            const cellText = row.cells[j].innerText.toLowerCase();
            if (cellText.indexOf(filter) > -1) {
                match = true;
                break;
            }
        }
        row.style.display = match ? '' : 'none';
    }
};

// Settings accordion control
window.toggleSettingsAccordion = function(headerEl) {
    const activeHeader = document.querySelector('.sett-accordion-header.active');
    const activeBody = document.querySelector('.sett-accordion-body.active');
    
    const targetSecId = headerEl.dataset.sec;
    const targetBody = document.getElementById(targetSecId);
    
    if (activeHeader && activeHeader !== headerEl) {
        activeHeader.classList.remove('active');
        if (activeBody) activeBody.classList.remove('active');
    }
    
    headerEl.classList.toggle('active');
    if (targetBody) targetBody.classList.toggle('active');
};

// Check log state with server session
fetch('auth.php?action=check&v=' + Date.now())
.then(res => res.json())
.then(data => {
    if (data.status === 'success' && data.authenticated) {
        const user = data.user;
        sessionStorage.setItem('loggedInUser', JSON.stringify(user));
        document.getElementById('loggedUserName').innerText = user.full_name || user.username;
        document.getElementById('loggedUserRole').innerText = user.role.toUpperCase() + ' ACCESS';
    } else {
        showToast("Session expired or unauthorized access. Redirecting to home page...", "error");
        setTimeout(() => {
            sessionStorage.removeItem('loggedInUser');
            window.location.href = 'index.php';
        }, 2000);
    }
})
.catch(err => {
    console.warn("Session check API failed, checking local state fallback:", err);
    const loggedUser = sessionStorage.getItem('loggedInUser');
    if (!loggedUser) {
        showToast("Unauthorized access. Redirecting to home page...", "error");
        setTimeout(() => {
            window.location.href = 'index.php';
        }, 2000);
    } else {
        const user = JSON.parse(loggedUser);
        document.getElementById('loggedUserName').innerText = user.full_name || user.username;
        document.getElementById('loggedUserRole').innerText = user.role.toUpperCase() + ' ACCESS';
    }
});

// Logout handler
window.handleLogout = function(e) {
    if (e) e.preventDefault();
    fetch('auth.php?action=logout')
    .finally(() => {
        sessionStorage.removeItem('loggedInUser');
        window.location.href = 'index.php';
    });
};

// View Section Switcher
document.querySelectorAll('.sidebar-menu li a').forEach(link => {
    link.addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelectorAll('.sidebar-menu li a').forEach(a => a.classList.remove('active'));
        this.classList.add('active');

        const target = this.dataset.target;
        document.querySelectorAll('.view-section').forEach(sec => sec.classList.remove('active'));
        const targetSec = document.getElementById(target);
        if (targetSec) targetSec.classList.add('active');

        // Load appropriate triggers
        if (target === 'sug-inbox') {
            loadSuggestions();
        } else if (target === 'about-officers') {
            loadOfficersPreview();
        } else if (target === 'circulars-list-view') {
            loadDownloadsPreview();
        } else if (target === 'dashboard-view') {
            loadDashboardStats();
        }
    });
});

// CMS Inner Tabs Switcher
window.switchAdminTab = function(tabName, btn) {
    document.querySelectorAll('.admin-tab-content').forEach(pane => pane.style.display = 'none');
    document.querySelectorAll('.admin-tab-btn').forEach(b => b.classList.remove('active'));
    
    const targetPane = document.getElementById(`admin-tab-${tabName}`);
    if (targetPane) targetPane.style.display = 'block';
    if (btn) btn.classList.add('active');

    if (tabName === 'services') {
        loadServices();
    }
};

// Helper to scroll smoothly to a form, apply a flash highlight, and focus the first field
window.highlightAndScrollToForm = function(formId) {
    const form = document.getElementById(formId);
    if (!form) return;
    const card = form.closest('.admin-card');
    if (card) {
        card.scrollIntoView({ behavior: 'smooth', block: 'start' });
        card.style.transition = 'box-shadow 0.3s ease';
        card.style.boxShadow = '0 0 0 4px var(--portal-blue)';
        setTimeout(() => {
            card.style.boxShadow = '';
        }, 1500);
    } else {
        const contentArea = document.querySelector('.content-area');
        if (contentArea) {
            contentArea.scrollTo({ top: 0, behavior: 'smooth' });
        }
    }
    
    setTimeout(() => {
        const firstInput = form.querySelector('input:not([type="hidden"]), select, textarea');
        if (firstInput) {
            firstInput.focus();
        }
    }, 300);
};

// Helper to highlight the active editing row in a table
window.highlightTableRow = function(tableBodyId, rowId) {
    const tbody = document.getElementById(tableBodyId);
    if (!tbody) return;
    tbody.querySelectorAll('tr').forEach(tr => tr.classList.remove('selected-row'));
    const row = document.getElementById(rowId);
    if (row) {
        row.classList.add('selected-row');
    }
};

// Generic event listener to make all rows in .premium-table clickable
document.addEventListener('click', function(e) {
    const tr = e.target.closest('.premium-table tbody tr');
    if (tr && !e.target.closest('button') && !e.target.closest('a') && !e.target.closest('input') && !e.target.closest('select') && !e.target.closest('textarea')) {
        const editBtn = tr.querySelector('.action-icon-btn.edit');
        if (editBtn) {
            editBtn.click();
        }
    }
});

// Fetch API dynamic lists helper
function fetchAdminData(url, tableBodyId, templateFn) {
    const body = document.getElementById(tableBodyId);
    if (!body) return;
    body.innerHTML = '<tr><td colspan="6" style="color:#64748b; font-style:italic;">Loading...</td></tr>';
    
    fetch(url)
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            const keys = Object.keys(data);
            const list = data[keys[1]] || [];
            if (list.length > 0) {
                body.innerHTML = list.map(item => templateFn(item)).join('');
            } else {
                body.innerHTML = '<tr><td colspan="6" style="color:#64748b; font-style:italic; text-align:center;">No records available.</td></tr>';
            }
        }
    })
    .catch(err => {
        console.error("Fetch failed for " + url, err);
        body.innerHTML = '<tr><td colspan="6" style="color:#dc2626; font-weight:500;">Failed to load records.</td></tr>';
    });
}

// Load Dashboard stats
window.loadDashboardStats = function() {
    fetch('manage_news.php?v=' + Date.now())
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            const newsCount = data.news.length;
            document.getElementById('stat-news-count').innerText = newsCount;
        }
    });

    fetch('manage_officers.php?v=' + Date.now())
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            const staffCount = data.officers.length;
            document.getElementById('stat-staff-count').innerText = staffCount;
            document.getElementById('badge-staff').innerText = staffCount;
        }
    });

    fetch('manage_suggestions.php?v=' + Date.now())
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            const sugCount = data.suggestions.length;
            document.getElementById('stat-sug-count').innerText = sugCount;
            document.getElementById('badge-suggestions').innerText = sugCount;
            
            const previewList = document.getElementById('dashSuggestionsPreview');
            if (previewList) {
                if (sugCount > 0) {
                    const recents = data.suggestions.slice(0, 3);
                    previewList.innerHTML = recents.map(s => `
                        <div class="preview-sug-item">
                            <div class="preview-sug-head">
                                <span><b>${s.subject}</b></span>
                                <span class="preview-sug-meta">${s.submitted_at}</span>
                            </div>
                            <div class="preview-sug-body">${s.message.substring(0, 100)}${s.message.length > 100 ? '...' : ''}</div>
                        </div>
                    `).join('');
                } else {
                    previewList.innerHTML = '<p style="color:var(--text-muted); font-style:italic;">No suggestions in inbox.</p>';
                }
            }
        }
    });

    fetch('manage_downloads.php?v=' + Date.now())
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            const dlCount = data.downloads.length;
            document.getElementById('stat-dl-count').innerText = dlCount;
            document.getElementById('badge-downloads').innerText = dlCount;
        }
    });
};

// Suggestions stats/loader
window.loadSuggestions = function() {
    const container = document.getElementById('suggestionsListContainer');
    if (!container) return;
    container.innerHTML = '<p style="color:#64748b; font-style:italic;">Loading suggestions inbox...</p>';

    fetch('manage_suggestions.php?v=' + Date.now())
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success' && data.suggestions.length > 0) {
            container.innerHTML = data.suggestions.map(s => `
                <div class="sug-card">
                    <div class="sug-header-row">
                        <span class="sug-subject-badge"><i class="fas fa-tag"></i> ${s.subject}</span>
                        <span class="sug-date"><i class="far fa-calendar-alt"></i> ${s.submitted_at}</span>
                    </div>
                    
                    <div class="sug-user-info">
                        <div class="info-item"><i class="fas fa-user"></i> Name: <strong>${s.name}</strong></div>
                        <div class="info-item"><i class="fas fa-envelope"></i> Email: <a href="mailto:${s.email}">${s.email}</a></div>
                        <div class="info-item"><i class="fas fa-phone"></i> Phone: <span>${s.phone || 'N/A'}</span></div>
                    </div>
                    
                    <div class="sug-body-content">
                        <div class="body-label">Message / Suggestion</div>
                        <p class="body-text">${s.message}</p>
                    </div>
                    
                    <div class="sug-footer">
                        <button class="sug-delete-btn-premium" onclick="deleteSuggestion(${s.id})">
                            <i class="fas fa-trash-alt"></i> Delete Suggestion
                        </button>
                    </div>
                </div>
            `).join('');
        } else {
            container.innerHTML = '<p style="color:#64748b; font-style:italic; padding: 20px; text-align:center;">Inbox is empty. No suggestions submitted yet.</p>';
        }
    })
    .catch(err => {
        console.error("Suggestions load failed:", err);
        container.innerHTML = '<p style="color:#ef4444; font-weight:500;">Failed to load suggestions database.</p>';
    });
};

// Delete Suggestion
window.deleteSuggestion = function(id) {
    showConfirm("Delete Suggestion", "Are you sure you want to delete this suggestion?")
    .then(approved => {
        if (!approved) return;
        fetch(`manage_suggestions.php?id=${id}`, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast(data.message || "Suggestion deleted successfully!");
                loadSuggestions();
                loadDashboardStats();
            } else {
                showToast(data.message || "Failed to delete.", "error");
            }
        })
        .catch(err => {
            console.error("Delete suggestion failed:", err);
            showToast("Failed to delete suggestion.", "error");
        });
    });
};

// News Form Submission
const newsForm = document.getElementById('newsForm');
if (newsForm) {
    newsForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append('id', document.getElementById('newsId').value);
        formData.append('category', document.getElementById('newsCategory').value);
        formData.append('title', document.getElementById('newsTitle').value);
        formData.append('content', document.getElementById('newsContent').value);
        formData.append('news_date', document.getElementById('newsDate').value);
        formData.append('url', document.getElementById('newsUrl').value);
        formData.append('image_url', document.getElementById('newsImageUrl').value);
        formData.append('image_before', document.getElementById('newsImageBeforeUrl').value);
        formData.append('image_after', document.getElementById('newsImageAfterUrl').value);

        fetch('manage_news.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast("News article saved successfully!");
                newsForm.reset();
                document.getElementById('newsId').value = '';
                document.getElementById('newsImagePreview').style.display = 'none';
                document.getElementById('newsImageBeforePreview').style.display = 'none';
                document.getElementById('newsImageAfterPreview').style.display = 'none';
                loadNews();
                loadDashboardStats();
            } else {
                showToast(data.message, "error");
            }
        })
        .catch(err => {
            console.error("Save news error:", err);
            showToast("Failed to save news article.", "error");
        });
    });
}

let adminNewsList = [];
window.loadNews = function() {
    const body = document.getElementById('newsTableBody');
    if (!body) return;
    body.innerHTML = '<tr><td colspan="4" style="color:#64748b; font-style:italic;">Loading...</td></tr>';
    
    fetch('manage_news.php?v=' + Date.now())
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            adminNewsList = data.news;
            if (adminNewsList.length > 0) {
                body.innerHTML = adminNewsList.map(n => `
                    <tr id="news-row-${n.id}">
                        <td>${n.news_date}</td>
                        <td><b>${n.category}</b></td>
                        <td>${n.title}</td>
                        <td>
                            <button class="action-icon-btn edit" onclick="event.stopPropagation(); editNews(${n.id})"><i class="fas fa-edit"></i></button>
                            <button class="action-icon-btn delete" onclick="event.stopPropagation(); deleteNews(${n.id})"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                `).join('');
            } else {
                body.innerHTML = '<tr><td colspan="4" style="color:#64748b; font-style:italic; text-align:center;">No records available.</td></tr>';
            }
        }
    })
    .catch(err => {
        console.error("Fetch failed for news", err);
        body.innerHTML = '<tr><td colspan="4" style="color:#dc2626; font-weight:500;">Failed to load records.</td></tr>';
    });
};

window.editNews = function(id) {
    const n = adminNewsList.find(item => item.id == id);
    if (!n) return;
    document.getElementById('newsId').value = n.id;
    document.getElementById('newsCategory').value = n.category;
    document.getElementById('newsTitle').value = n.title;
    document.getElementById('newsContent').value = n.content;
    document.getElementById('newsDate').value = n.news_date;
    document.getElementById('newsUrl').value = n.url || '';
    document.getElementById('newsImageUrl').value = n.image_url || '';
    document.getElementById('newsImageBeforeUrl').value = n.image_before || '';
    document.getElementById('newsImageAfterUrl').value = n.image_after || '';
    
    showLivePreview(document.getElementById('newsImageUrl'), 'newsImagePreview');
    showLivePreview(document.getElementById('newsImageBeforeUrl'), 'newsImageBeforePreview');
    showLivePreview(document.getElementById('newsImageAfterUrl'), 'newsImageAfterPreview');

    highlightTableRow('newsTableBody', 'news-row-' + id);
    highlightAndScrollToForm('newsForm');
};

window.deleteNews = function(id) {
    showConfirm("Delete News", "Are you sure you want to delete this news article?")
    .then(approved => {
        if (!approved) return;
        fetch(`manage_news.php?id=${id}`, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast("News article deleted successfully!");
                loadNews();
                loadDashboardStats();
            } else {
                showToast(data.message || "Failed to delete.", "error");
            }
        })
        .catch(err => {
            console.error("Delete news error:", err);
            showToast("Failed to delete news article.", "error");
        });
    });
};

// Announcements logic
const annForm = document.getElementById('annForm');
if (annForm) {
    annForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append('id', document.getElementById('annId').value);
        formData.append('category', document.getElementById('annCategory').value);
        formData.append('title', document.getElementById('annTitle').value);
        formData.append('url', document.getElementById('annUrl').value);
        formData.append('badge', document.getElementById('annBadge').value);

        fetch('manage_announcements.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast("Announcement saved successfully!");
                annForm.reset();
                document.getElementById('annId').value = '';
                loadAnnouncements();
            } else {
                showToast(data.message, "error");
            }
        })
        .catch(err => {
            console.error("Save announcement error:", err);
            showToast("Failed to save announcement.", "error");
        });
    });
}

window.loadAnnouncements = function() {
    fetchAdminData('manage_announcements.php', 'annTableBody', (a) => `
        <tr id="ann-row-${a.id}">
            <td><b>${a.category}</b></td>
            <td>${a.title}</td>
            <td>${a.badge || 'None'}</td>
            <td>
                <button class="action-icon-btn edit" onclick="event.stopPropagation(); editAnn(${a.id}, '${a.category}', \`${a.title.replace(/'/g, "\\'")}\`, '${a.url}', '${a.badge || ''}')"><i class="fas fa-edit"></i></button>
                <button class="action-icon-btn delete" onclick="event.stopPropagation(); deleteAnn(${a.id})"><i class="fas fa-trash-alt"></i></button>
            </td>
        </tr>
    `);
};

window.editAnn = function(id, cat, title, url, badge) {
    document.getElementById('annId').value = id;
    document.getElementById('annCategory').value = cat;
    document.getElementById('annTitle').value = title;
    document.getElementById('annUrl').value = url;
    document.getElementById('annBadge').value = badge;
    highlightTableRow('annTableBody', 'ann-row-' + id);
    highlightAndScrollToForm('annForm');
};

window.deleteAnn = function(id) {
    showConfirm("Delete Announcement", "Are you sure you want to delete this announcement?")
    .then(approved => {
        if (!approved) return;
        fetch(`manage_announcements.php?id=${id}`, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast("Announcement deleted successfully!");
                loadAnnouncements();
            } else {
                showToast(data.message || "Failed to delete.", "error");
            }
        })
        .catch(err => {
            console.error("Delete announcement error:", err);
            showToast("Failed to delete announcement.", "error");
        });
    });
};

// Promise-based File Upload helper for settings form submission
window.uploadFilePromise = function(fileInputId, btnId) {
    return new Promise((resolve, reject) => {
        const fileInput = document.getElementById(fileInputId);
        const btn = document.getElementById(btnId);
        if (!fileInput || fileInput.files.length === 0) {
            resolve(null);
            return;
        }
        
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
        }
        
        const formData = new FormData();
        formData.append('file', fileInput.files[0]);
        
        fetch('upload.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-upload"></i> Upload';
            }
            if (data.status === 'success') {
                resolve(data.file_url);
            } else {
                reject(new Error(data.message || 'Upload failed'));
            }
        })
        .catch(err => {
            if (btn) {
                btn.disabled = false;
                btn.innerHTML = '<i class="fas fa-upload"></i> Upload';
            }
            reject(err);
        });
    });
};

// Reusable File Upload AJAX Helper
window.handleFileUpload = function(fileInputId, urlInputId, btnId) {
    const fileInput = document.getElementById(fileInputId);
    const urlInput = document.getElementById(urlInputId);
    const btn = document.getElementById(btnId);
    
    if (!fileInput || fileInput.files.length === 0) {
        showToast("Please select a file to upload.", "warning");
        return;
    }
    
    btn.disabled = true;
    btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
    
    const formData = new FormData();
    formData.append('file', fileInput.files[0]);
    
    fetch('upload.php', {
        method: 'POST',
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-upload"></i> Upload';
        if (data.status === 'success') {
            urlInput.value = data.file_url;
            
            let previewImgId = '';
            if (urlInputId === 'newsImageUrl') previewImgId = 'newsImagePreview';
            else if (urlInputId === 'newsImageBeforeUrl') previewImgId = 'newsImageBeforePreview';
            else if (urlInputId === 'newsImageAfterUrl') previewImgId = 'newsImageAfterPreview';
            else if (urlInputId === 'offPhotoUrl') previewImgId = 'offPhotoPreview';
            else if (urlInputId === 'projImageUrl') previewImgId = 'projImagePreview';
            else if (urlInputId === 'linkImageUrl') previewImgId = 'linkImagePreview';
            else if (urlInputId === 'galImageUrl') previewImgId = 'galImagePreview';
            else if (urlInputId === 'bannerImageUrl') previewImgId = 'bannerImagePreview';
            else if (urlInputId === 'setOrgChartUrl') previewImgId = 'orgChartPreview';
            else if (urlInputId === 'setHeaderNatLogo') previewImgId = 'headerNatLogoPreview';
            else if (urlInputId === 'setHeaderProvLogo') previewImgId = 'headerProvLogoPreview';
            
            if (previewImgId) {
                showLivePreview(urlInput, previewImgId);
            }
            showToast("File uploaded successfully!");
        } else {
            showToast("Upload failed: " + data.message, "error");
        }
    })
    .catch(err => {
        btn.disabled = false;
        btn.innerHTML = '<i class="fas fa-upload"></i> Upload';
        console.error("Upload error:", err);
        showToast("Upload error. Please try again.", "error");
    });
};

// Custom Banner Upload Handler (supports single and bulk upload up to 10 photos)
window.handleBannerUpload = function() {
    const fileInput = document.getElementById('bannerPhotoSelect');
    const urlInput = document.getElementById('bannerImageUrl');
    const btn = document.getElementById('bannerUploadBtn');
    
    if (!fileInput || fileInput.files.length === 0) {
        showToast("Please select a file to upload.", "warning");
        return;
    }
    
    const files = fileInput.files;
    if (files.length > 10) {
        showToast("You can upload a maximum of 10 photos at once.", "warning");
        return;
    }
    
    if (files.length === 1) {
        // Single file upload: standard workflow
        btn.disabled = true;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading...';
        
        const formData = new FormData();
        formData.append('file', files[0]);
        
        fetch('upload.php', {
            method: 'POST',
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-upload"></i> Upload';
            if (data.status === 'success') {
                urlInput.value = data.file_url;
                showLivePreview(urlInput, 'bannerImagePreview');
                showToast("File uploaded successfully!");
            } else {
                showToast("Upload failed: " + data.message, "error");
            }
        })
        .catch(err => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-upload"></i> Upload';
            console.error("Upload error:", err);
            showToast("Upload error. Please try again.", "error");
        });
    } else {
        // Multiple files upload: bulk create banners
        btn.disabled = true;
        
        const submitBtn = document.getElementById('bannerSubmitBtn');
        if (submitBtn) submitBtn.disabled = true;
        
        let uploadedCount = 0;
        let failedCount = 0;
        
        // Sequential uploads using Promise chain to prevent database/server race conditions
        let promiseChain = Promise.resolve();
        
        Array.from(files).forEach((file, index) => {
            promiseChain = promiseChain.then(() => {
                btn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Uploading ${index + 1}/${files.length}...`;
                
                const formData = new FormData();
                formData.append('file', file);
                
                return fetch('upload.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.status === 'success') {
                        // Create banner entry
                        const bannerData = new FormData();
                        bannerData.append('id', '');
                        // Friendly title from filename
                        const nameWithoutExt = file.name.substring(0, file.name.lastIndexOf('.')) || file.name;
                        bannerData.append('title', nameWithoutExt);
                        bannerData.append('image_url', data.file_url);
                        
                        const currentCount = adminBannersList.length;
                        bannerData.append('sort_order', currentCount + index + 1);
                        
                        return fetch('manage_banners.php', {
                            method: 'POST',
                            body: bannerData
                        })
                        .then(res => res.json())
                        .then(bData => {
                            if (bData.status === 'success') {
                                uploadedCount++;
                            } else {
                                failedCount++;
                            }
                        });
                    } else {
                        failedCount++;
                    }
                })
                .catch(err => {
                    console.error("Upload failed for:", file.name, err);
                    failedCount++;
                });
            });
        });
        
        promiseChain.then(() => {
            btn.disabled = false;
            btn.innerHTML = '<i class="fas fa-upload"></i> Upload';
            if (submitBtn) submitBtn.disabled = false;
            
            if (uploadedCount > 0) {
                showToast(`Successfully uploaded and saved ${uploadedCount} banner(s).` + (failedCount > 0 ? ` (${failedCount} failed)` : ''));
                fileInput.value = ''; // Clear file selection
                loadBanners();
                loadDashboardStats();
            } else {
                showToast("Failed to upload photos.", "error");
            }
        });
    }
};

// Downloads logic
let adminDownloadsList = [];
const dlForm = document.getElementById('dlForm');
if (dlForm) {
    dlForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append('id', document.getElementById('dlId').value);
        formData.append('category', document.getElementById('dlCategory').value);
        formData.append('title', document.getElementById('dlTitle').value);
        formData.append('title_si', document.getElementById('dlTitleSi').value);
        formData.append('title_ta', document.getElementById('dlTitleTa').value);
        formData.append('description', document.getElementById('dlDesc').value);
        formData.append('description_si', document.getElementById('dlDescSi').value);
        formData.append('description_ta', document.getElementById('dlDescTa').value);
        formData.append('file_url', document.getElementById('dlFileUrl').value);
        formData.append('file_url_si', document.getElementById('dlFileUrlSi').value);
        formData.append('file_url_ta', document.getElementById('dlFileUrlTa').value);

        fetch('manage_downloads.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast("Document saved successfully!");
                dlForm.reset();
                document.getElementById('dlId').value = '';
                loadDownloads();
                loadDashboardStats();
            } else {
                showToast(data.message, "error");
            }
        })
        .catch(err => {
            console.error("Save downloads error:", err);
            showToast("Failed to save document.", "error");
        });
    });
}

window.loadDownloads = function() {
    const body = document.getElementById('dlTableBody');
    if (!body) return;
    body.innerHTML = '<tr><td colspan="4" style="color:#64748b; font-style:italic;">Loading...</td></tr>';
    
    fetch('manage_downloads.php?v=' + Date.now())
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            adminDownloadsList = data.downloads;
            if (adminDownloadsList.length > 0) {
                body.innerHTML = adminDownloadsList.map(d => `
                    <tr id="dl-row-${d.id}">
                        <td><b>${d.category}</b></td>
                        <td>${d.title}</td>
                        <td>${d.description}</td>
                        <td>
                            <button class="action-icon-btn edit" onclick="event.stopPropagation(); editDl(${d.id})"><i class="fas fa-edit"></i></button>
                            <button class="action-icon-btn delete" onclick="event.stopPropagation(); deleteDl(${d.id})"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                `).join('');
            } else {
                body.innerHTML = '<tr><td colspan="4" style="color:#64748b; font-style:italic; text-align:center;">No records available.</td></tr>';
            }
        }
    })
    .catch(err => {
        console.error("Fetch failed for downloads", err);
        body.innerHTML = '<tr><td colspan="4" style="color:#dc2626; font-weight:500;">Failed to load records.</td></tr>';
    });
};

window.editDl = function(id) {
    const d = adminDownloadsList.find(item => item.id == id);
    if (!d) return;
    document.getElementById('dlId').value = d.id;
    document.getElementById('dlCategory').value = d.category;
    document.getElementById('dlTitle').value = d.title;
    document.getElementById('dlTitleSi').value = d.title_si || '';
    document.getElementById('dlTitleTa').value = d.title_ta || '';
    document.getElementById('dlDesc').value = d.description;
    document.getElementById('dlDescSi').value = d.description_si || '';
    document.getElementById('dlDescTa').value = d.description_ta || '';
    document.getElementById('dlFileUrl').value = d.file_url || '';
    document.getElementById('dlFileUrlSi').value = d.file_url_si || '';
    document.getElementById('dlFileUrlTa').value = d.file_url_ta || '';
    
    highlightTableRow('dlTableBody', 'dl-row-' + id);
    highlightAndScrollToForm('dlForm');
};

window.deleteDl = function(id) {
    showConfirm("Delete Document", "Are you sure you want to delete this document?")
    .then(approved => {
        if (!approved) return;
        fetch(`manage_downloads.php?id=${id}`, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast("Document deleted successfully!");
                loadDownloads();
                loadDashboardStats();
            } else {
                showToast(data.message || "Failed to delete.", "error");
            }
        })
        .catch(err => {
            console.error("Delete download error:", err);
            showToast("Failed to delete document.", "error");
        });
    });
};

// Procurements Notices logic
const procForm = document.getElementById('procForm');
if (procForm) {
    procForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append('id', document.getElementById('procId').value);
        formData.append('title', document.getElementById('procTitle').value);
        formData.append('publish_date', document.getElementById('procDate').value);
        formData.append('status', document.getElementById('procStatus').value);
        formData.append('file_url', document.getElementById('procFileUrl').value);

        fetch('manage_procurements.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast("Procurement notice saved successfully!");
                procForm.reset();
                document.getElementById('procId').value = '';
                document.getElementById('procFileUrl').value = '';
                loadProcurements();
            } else {
                showToast(data.message, "error");
            }
        })
        .catch(err => {
            console.error("Save procurement error:", err);
            showToast("Failed to save procurement notice.", "error");
        });
    });
}

let adminProcurementsList = [];
window.loadProcurements = function() {
    const body = document.getElementById('procTableBody');
    if (!body) return;
    body.innerHTML = '<tr><td colspan="4" style="color:#64748b; font-style:italic;">Loading...</td></tr>';

    fetch('manage_procurements.php?v=' + Date.now())
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            adminProcurementsList = data.procurements;
            if (adminProcurementsList.length > 0) {
                body.innerHTML = adminProcurementsList.map(p => `
                    <tr id="proc-row-${p.id}">
                        <td>${p.publish_date}</td>
                        <td>${p.title}</td>
                        <td><span style="color:${p.status === 'active' ? 'green' : 'red'}; font-weight:600;">${p.status.toUpperCase()}</span></td>
                        <td>
                            <button class="action-icon-btn edit" onclick="event.stopPropagation(); editProc(${p.id})"><i class="fas fa-edit"></i></button>
                            <button class="action-icon-btn delete" onclick="event.stopPropagation(); deleteProc(${p.id})"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                `).join('');
            } else {
                body.innerHTML = '<tr><td colspan="4" style="color:#64748b; font-style:italic; text-align:center;">No records available.</td></tr>';
            }
        }
    })
    .catch(err => {
        console.error("Tenders load failed:", err);
        body.innerHTML = '<tr><td colspan="4" style="color:#dc2626; font-weight:500;">Failed to load records.</td></tr>';
    });
};

window.editProc = function(id) {
    const p = adminProcurementsList.find(item => item.id == id);
    if (!p) return;
    document.getElementById('procId').value = p.id;
    document.getElementById('procTitle').value = p.title;
    document.getElementById('procDate').value = p.publish_date;
    document.getElementById('procStatus').value = p.status;
    document.getElementById('procFileUrl').value = p.file_url || '';
    highlightTableRow('procTableBody', 'proc-row-' + id);
    highlightAndScrollToForm('procForm');
};

window.deleteProc = function(id) {
    showConfirm("Delete Procurement", "Are you sure you want to delete this procurement notice?")
    .then(approved => {
        if (!approved) return;
        fetch(`manage_procurements.php?id=${id}`, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast("Procurement notice deleted successfully!");
                loadProcurements();
            } else {
                showToast(data.message || "Failed to delete.", "error");
            }
        })
        .catch(err => {
            console.error("Delete procurement error:", err);
            showToast("Failed to delete procurement notice.", "error");
        });
    });
};

// Officers Management logic
const offForm = document.getElementById('offForm');
if (offForm) {
    offForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append('id', document.getElementById('offId').value);
        formData.append('name', document.getElementById('offName').value);
        formData.append('title', document.getElementById('offTitle').value);
        formData.append('phone', document.getElementById('offPhone').value);
        formData.append('email', document.getElementById('offEmail').value);
        formData.append('category', document.getElementById('offCategory').value);
        formData.append('photo_url', document.getElementById('offPhotoUrl').value);
        formData.append('sort_order', document.getElementById('offSortOrder').value || 0);
        formData.append('division', 'Head Office');

        fetch('manage_officers.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast("Officer details saved successfully!");
                cancelOffEdit();
                loadOfficers();
                loadDashboardStats();
            } else {
                showToast(data.message, "error");
            }
        })
        .catch(err => {
            console.error("Save officer error:", err);
            showToast("Failed to save officer details.", "error");
        });
    });
}

let adminOfficersList = [];
let officerSortCol = '';
let officerSortDir = 'asc';

window.loadOfficers = function() {
    const body = document.getElementById('offTableBody');
    if (!body) return;
    body.innerHTML = '<tr><td colspan="6" style="color:#64748b; font-style:italic;">Loading...</td></tr>';

    fetch('manage_officers.php?v=' + Date.now())
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            adminOfficersList = data.officers;
            if (officerSortCol) {
                const col = officerSortCol;
                const dir = officerSortDir;
                officerSortCol = '';
                officerSortDir = dir;
                sortOfficers(col);
            } else {
                renderOfficersTable();
            }
        }
    })
    .catch(err => {
        console.error("Staff load failed:", err);
        body.innerHTML = '<tr><td colspan="6" style="color:#dc2626; font-weight:500;">Failed to load records.</td></tr>';
    });
};

window.renderOfficersTable = function() {
    const body = document.getElementById('offTableBody');
    if (!body) return;
    const activeId = document.getElementById('offId').value;
    if (adminOfficersList.length > 0) {
        body.innerHTML = adminOfficersList.map(o => {
            const photoHtml = o.photo_url 
                ? `<img src="${o.photo_url}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" style="width: 56px; height: 56px; object-fit: cover; border-radius: 50%; border: 3px solid #ffffff; box-shadow: 0 4px 10px rgba(0,0,0,0.08); display: block; margin: 0 auto;">
                   <div style="display: none; width: 56px; height: 56px; border-radius: 50%; background: #f1f5f9; align-items: center; justify-content: center; margin: 0 auto; color: #94a3b8; font-size: 1.4rem; border: 3px solid #ffffff; box-shadow: 0 4px 10px rgba(0,0,0,0.08);"><i class="fas fa-user-tie"></i></div>`
                : `<div style="width: 56px; height: 56px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: #94a3b8; font-size: 1.4rem; border: 3px solid #ffffff; box-shadow: 0 4px 10px rgba(0,0,0,0.08);"><i class="fas fa-user-tie"></i></div>`;
            
            return `
                <tr id="off-row-${o.id}" class="${o.id == activeId ? 'selected-row' : ''}">
                    <td style="text-align: center; vertical-align: middle;">${photoHtml}</td>
                    <td style="vertical-align: middle;"><strong style="font-size: 0.95rem; color: #0f172a; display: block; margin-bottom: 2px;">${o.name}</strong></td>
                    <td style="vertical-align: middle;"><span style="font-size: 0.88rem; color: #475569; font-weight: 500;">${o.title}</span></td>
                    <td style="vertical-align: middle;"><span style="font-size: 0.88rem; color: #64748b;">${o.phone}</span></td>
                    <td style="vertical-align: middle;"><span class="badge" style="background:#e2e8f0; color:#475569; padding:4px 8px; border-radius:12px; font-size:0.75rem; font-weight:600; text-transform:uppercase;">${o.category}</span></td>
                    <td style="vertical-align: middle; text-align: center;">
                        <button class="action-icon-btn edit" onclick="event.stopPropagation(); editOff(${o.id})"><i class="fas fa-edit"></i></button>
                        <button class="action-icon-btn delete" onclick="event.stopPropagation(); deleteOff(${o.id})"><i class="fas fa-trash-alt"></i></button>
                    </td>
                </tr>
            `;
        }).join('');
    } else {
        body.innerHTML = '<tr><td colspan="6" style="color:#64748b; font-style:italic; text-align:center;">No records available.</td></tr>';
    }
};

window.sortOfficers = function(field) {
    if (officerSortCol === field) {
        officerSortDir = officerSortDir === 'asc' ? 'desc' : 'asc';
    } else {
        officerSortCol = field;
        officerSortDir = 'asc';
    }

    adminOfficersList.sort((a, b) => {
        let valA = (a[field] || '').toString().toLowerCase();
        let valB = (b[field] || '').toString().toLowerCase();
        
        if (valA < valB) return officerSortDir === 'asc' ? -1 : 1;
        if (valA > valB) return officerSortDir === 'asc' ? 1 : -1;
        return 0;
    });

    renderOfficersTable();
    updateOfficerSortIcons();
};

function updateOfficerSortIcons() {
    const fields = ['name', 'title', 'phone', 'category'];
    fields.forEach(f => {
        const iconSpan = document.getElementById('sort-icon-' + f);
        if (iconSpan) {
            if (officerSortCol === f) {
                iconSpan.innerHTML = officerSortDir === 'asc' ? '<i class="fas fa-sort-up"></i>' : '<i class="fas fa-sort-down"></i>';
                iconSpan.style.color = 'var(--accent-gold)';
            } else {
                iconSpan.innerHTML = '<i class="fas fa-sort"></i>';
                iconSpan.style.color = 'rgba(255,255,255,0.6)';
            }
        }
    });
}

window.cancelOffEdit = function() {
    const form = document.getElementById('offForm');
    if (form) form.reset();
    document.getElementById('offId').value = '';
    document.getElementById('offSortOrder').value = '0';
    document.getElementById('offPhotoPreview').style.display = 'none';
    document.getElementById('offPhotoPreview').src = '';
    document.getElementById('offCancelBtn').style.display = 'none';
    document.getElementById('offFormTitle').innerHTML = '<i class="fas fa-plus"></i> Add New Officer Details';
    
    document.querySelectorAll('#offTableBody tr').forEach(tr => tr.classList.remove('selected-row'));
};

window.editOff = function(id) {
    const o = adminOfficersList.find(item => item.id == id);
    if (!o) return;
    document.getElementById('offId').value = o.id;
    document.getElementById('offName').value = o.name;
    document.getElementById('offTitle').value = o.title;
    document.getElementById('offPhone').value = o.phone;
    document.getElementById('offEmail').value = o.email || '';
    document.getElementById('offCategory').value = o.category;
    document.getElementById('offPhotoUrl').value = o.photo_url || '';
    document.getElementById('offSortOrder').value = o.sort_order || 0;
    
    showLivePreview(document.getElementById('offPhotoUrl'), 'offPhotoPreview');
    
    document.getElementById('offCancelBtn').style.display = 'inline-flex';
    document.getElementById('offFormTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Officer Details';
    
    highlightTableRow('offTableBody', 'off-row-' + id);
    highlightAndScrollToForm('offForm');
};

window.deleteOff = function(id) {
    showConfirm("Remove Officer", "Are you sure you want to remove this officer from the directory?")
    .then(approved => {
        if (!approved) return;
        fetch(`manage_officers.php?id=${id}`, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast("Officer removed successfully!");
                loadOfficers();
                loadDashboardStats();
            } else {
                showToast(data.message || "Failed to delete.", "error");
            }
        })
        .catch(err => {
            console.error("Delete officer error:", err);
            showToast("Failed to delete officer details.", "error");
        });
    });
};

// Welfare Projects logic
const projForm = document.getElementById('projForm');
if (projForm) {
    projForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append('id', document.getElementById('projId').value);
        formData.append('category', document.getElementById('projCategory').value);
        formData.append('title_en', document.getElementById('projTitleEn').value);
        formData.append('title_si', document.getElementById('projTitleSi').value);
        formData.append('title_ta', document.getElementById('projTitleTa').value);
        formData.append('description_en', document.getElementById('projDescEn').value);
        formData.append('description_si', document.getElementById('projDescSi').value);
        formData.append('description_ta', document.getElementById('projDescTa').value);
        formData.append('image_url', document.getElementById('projImageUrl').value);
        formData.append('financial_details', document.getElementById('projFinancial').value);

        fetch('manage_projects.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast("Welfare project saved successfully!");
                projForm.reset();
                document.getElementById('projId').value = '';
                document.getElementById('projImagePreview').style.display = 'none';
                loadProjects();
            } else {
                showToast(data.message, "error");
            }
        })
        .catch(err => {
            console.error("Save project error:", err);
            showToast("Failed to save welfare project.", "error");
        });
    });
}

window.loadProjects = function() {
    fetchAdminData('manage_projects.php', 'projTableBody', (p) => `
        <tr id="proj-row-${p.id}">
            <td><b>${p.category.toUpperCase()}</b></td>
            <td>${p.title_en}</td>
            <td>${p.financial_details || 'N/A'}</td>
            <td>
                <button class="action-icon-btn edit" onclick="event.stopPropagation(); editProj(${p.id}, '${p.category}', \`${p.title_en.replace(/'/g, "\\'")}\`, \`${p.title_si.replace(/'/g, "\\'")}\`, \`${p.title_ta.replace(/'/g, "\\'")}\`, \`${p.description_en.replace(/'/g, "\\'")}\`, \`${p.description_si.replace(/'/g, "\\'")}\`, \`${p.description_ta.replace(/'/g, "\\'")}\`, '${p.image_url || ''}', \`${(p.financial_details || '').replace(/'/g, "\\'")}\`)"><i class="fas fa-edit"></i></button>
                <button class="action-icon-btn delete" onclick="event.stopPropagation(); deleteProj(${p.id})"><i class="fas fa-trash-alt"></i></button>
            </td>
        </tr>
    `);
};

window.editProj = function(id, cat, tEn, tSi, tTa, dEn, dSi, dTa, imgUrl, financials) {
    document.getElementById('projId').value = id;
    document.getElementById('projCategory').value = cat;
    document.getElementById('projTitleEn').value = tEn;
    document.getElementById('projTitleSi').value = tSi;
    document.getElementById('projTitleTa').value = tTa;
    document.getElementById('projDescEn').value = dEn;
    document.getElementById('projDescSi').value = dSi;
    document.getElementById('projDescTa').value = dTa;
    document.getElementById('projImageUrl').value = imgUrl;
    document.getElementById('projFinancial').value = financials;
    
    showLivePreview(document.getElementById('projImageUrl'), 'projImagePreview');

    highlightTableRow('projTableBody', 'proj-row-' + id);
    highlightAndScrollToForm('projForm');
};

window.deleteProj = function(id) {
    showConfirm("Delete Project", "Are you sure you want to delete this welfare project?")
    .then(approved => {
        if (!approved) return;
        fetch(`manage_projects.php?id=${id}`, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast("Welfare project deleted successfully!");
                loadProjects();
            } else {
                showToast(data.message || "Failed to delete.", "error");
            }
        })
        .catch(err => {
            console.error("Delete project error:", err);
            showToast("Failed to delete welfare project.", "error");
        });
    });
};

// Workshops & Programs logic
const courseForm = document.getElementById('courseForm');
if (courseForm) {
    courseForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append('id', document.getElementById('courseId').value);
        formData.append('category', document.getElementById('courseCategory').value);
        formData.append('title', document.getElementById('courseTitle').value);
        formData.append('event_date', document.getElementById('courseDate').value);
        formData.append('location', document.getElementById('courseLocation').value);
        formData.append('icon_class', document.getElementById('courseIconClass').value);
        formData.append('url', document.getElementById('courseUrl').value);

        fetch('manage_courses.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast("Program session saved successfully!");
                courseForm.reset();
                document.getElementById('courseId').value = '';
                loadCourses();
            } else {
                showToast(data.message, "error");
            }
        })
        .catch(err => {
            console.error("Save course error:", err);
            showToast("Failed to save program session.", "error");
        });
    });
}

window.loadCourses = function() {
    fetchAdminData('manage_courses.php', 'courseTableBody', (c) => `
        <tr id="course-row-${c.id}">
            <td><b>${c.category.toUpperCase()}</b></td>
            <td>${c.title}</td>
            <td>${c.event_date}</td>
            <td>${c.location}</td>
            <td>
                <button class="action-icon-btn edit" onclick="event.stopPropagation(); editCourse(${c.id}, '${c.category}', \`${c.title.replace(/'/g, "\\'")}\`, '${c.event_date}', \`${c.location.replace(/'/g, "\\'")}\`, '${c.icon_class}', '${c.url}')"><i class="fas fa-edit"></i></button>
                <button class="action-icon-btn delete" onclick="event.stopPropagation(); deleteCourse(${c.id})"><i class="fas fa-trash-alt"></i></button>
            </td>
        </tr>
    `);
};

window.editCourse = function(id, cat, title, date, loc, icon, url) {
    document.getElementById('courseId').value = id;
    document.getElementById('courseCategory').value = cat;
    document.getElementById('courseTitle').value = title;
    document.getElementById('courseDate').value = date;
    document.getElementById('courseLocation').value = loc;
    document.getElementById('courseIconClass').value = icon;
    document.getElementById('courseUrl').value = url;
    highlightTableRow('courseTableBody', 'course-row-' + id);
    highlightAndScrollToForm('courseForm');
};

window.deleteCourse = function(id) {
    showConfirm("Delete Program", "Are you sure you want to delete this workshop/program?")
    .then(approved => {
        if (!approved) return;
        fetch(`manage_courses.php?id=${id}`, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast("Program session deleted successfully!");
                loadCourses();
            } else {
                showToast(data.message || "Failed to delete.", "error");
            }
        })
        .catch(err => {
            console.error("Delete course error:", err);
            showToast("Failed to delete program session.", "error");
        });
    });
};

// Important Links logic
const linkForm = document.getElementById('linkForm');
if (linkForm) {
    linkForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append('id', document.getElementById('linkId').value);
        formData.append('category', document.getElementById('linkCategory').value);
        formData.append('title', document.getElementById('linkTitle').value);
        formData.append('url', document.getElementById('linkUrl').value);
        formData.append('image_url', document.getElementById('linkImageUrl').value);

        fetch('manage_links.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast("Link saved successfully!");
                linkForm.reset();
                document.getElementById('linkId').value = '';
                document.getElementById('linkImagePreview').style.display = 'none';
                loadLinks();
            } else {
                showToast(data.message, "error");
            }
        })
        .catch(err => {
            console.error("Save link error:", err);
            showToast("Failed to save link details.", "error");
        });
    });
}

window.loadLinks = function() {
    fetchAdminData('manage_links.php', 'linkTableBody', (l) => `
        <tr id="link-row-${l.id}">
            <td><b>${l.category.toUpperCase()}</b></td>
            <td>${l.title}</td>
            <td><a href="${l.url}" target="_blank">${l.url}</a></td>
            <td>
                <button class="action-icon-btn edit" onclick="event.stopPropagation(); editLink(${l.id}, '${l.category}', \`${l.title.replace(/'/g, "\\'")}\`, '${l.url}', '${l.image_url || ''}')"><i class="fas fa-edit"></i></button>
                <button class="action-icon-btn delete" onclick="event.stopPropagation(); deleteLink(${l.id})"><i class="fas fa-trash-alt"></i></button>
            </td>
        </tr>
    `);
};

window.editLink = function(id, cat, title, url, img) {
    document.getElementById('linkId').value = id;
    document.getElementById('linkCategory').value = cat;
    document.getElementById('linkTitle').value = title;
    document.getElementById('linkUrl').value = url;
    document.getElementById('linkImageUrl').value = img;
    
    showLivePreview(document.getElementById('linkImageUrl'), 'linkImagePreview');

    highlightTableRow('linkTableBody', 'link-row-' + id);
    highlightAndScrollToForm('linkForm');
};

window.deleteLink = function(id) {
    showConfirm("Delete Link", "Are you sure you want to delete this link?")
    .then(approved => {
        if (!approved) return;
        fetch(`manage_links.php?id=${id}`, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast("Link deleted successfully!");
                loadLinks();
            } else {
                showToast(data.message || "Failed to delete.", "error");
            }
        })
        .catch(err => {
            console.error("Delete link error:", err);
            showToast("Failed to delete link.", "error");
        });
    });
};

// Gallery Photos logic
const galForm = document.getElementById('galForm');
if (galForm) {
    galForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append('id', document.getElementById('galId').value);
        formData.append('title', document.getElementById('galTitle').value);
        formData.append('image_url', document.getElementById('galImageUrl').value);
        formData.append('description', document.getElementById('galDesc').value);

        fetch('manage_gallery.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast("Gallery photo saved successfully!");
                galForm.reset();
                document.getElementById('galId').value = '';
                document.getElementById('galImagePreview').style.display = 'none';
                loadGallery();
            } else {
                showToast(data.message, "error");
            }
        })
        .catch(err => {
            console.error("Save gallery photo error:", err);
            showToast("Failed to save gallery photo.", "error");
        });
    });
}

window.loadGallery = function() {
    fetchAdminData('manage_gallery.php', 'galTableBody', (g) => `
        <tr id="gal-row-${g.id}">
            <td><img src="${g.image_url}" style="width:60px; height:45px; object-fit:cover; border-radius:4px;"></td>
            <td>${g.title}</td>
            <td>${g.description || 'No description'}</td>
            <td>
                <button class="action-icon-btn edit" onclick="event.stopPropagation(); editGal(${g.id}, \`${g.title.replace(/'/g, "\\'")}\`, '${g.image_url}', \`${(g.description || '').replace(/'/g, "\\'")}\`)"><i class="fas fa-edit"></i></button>
                <button class="action-icon-btn delete" onclick="event.stopPropagation(); deleteGal(${g.id})"><i class="fas fa-trash-alt"></i></button>
            </td>
        </tr>
    `);
};

window.editGal = function(id, title, img, desc) {
    document.getElementById('galId').value = id;
    document.getElementById('galTitle').value = title;
    document.getElementById('galImageUrl').value = img;
    document.getElementById('galDesc').value = desc;
    
    showLivePreview(document.getElementById('galImageUrl'), 'galImagePreview');

    highlightTableRow('galTableBody', 'gal-row-' + id);
    highlightAndScrollToForm('galForm');
};

window.deleteGal = function(id) {
    showConfirm("Delete Gallery Photo", "Are you sure you want to delete this gallery photo?")
    .then(approved => {
        if (!approved) return;
        fetch(`manage_gallery.php?id=${id}`, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast("Gallery photo deleted successfully!");
                loadGallery();
            } else {
                showToast(data.message || "Failed to delete.", "error");
            }
        })
        .catch(err => {
            console.error("Delete gallery photo error:", err);
            showToast("Failed to delete gallery photo.", "error");
        });
    });
};

// Banners logic
const bannerForm = document.getElementById('bannerForm');
if (bannerForm) {
    bannerForm.addEventListener('submit', (e) => {
        e.preventDefault();
        
        const bannerId = document.getElementById('bannerId').value;
        const bannerTitle = document.getElementById('bannerTitle').value;
        const bannerImageUrlEl = document.getElementById('bannerImageUrl');
        const bannerSortOrder = document.getElementById('bannerSortOrder').value || 0;
        const fileInput = document.getElementById('bannerPhotoSelect');
        
        const saveBannerData = (url) => {
            const formData = new FormData();
            formData.append('id', bannerId);
            formData.append('title', bannerTitle);
            formData.append('image_url', url);
            formData.append('sort_order', bannerSortOrder);
            
            fetch('manage_banners.php', { method: 'POST', body: formData })
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    showToast(data.message || "Banner saved successfully!");
                    cancelBannerEdit();
                    loadBanners();
                    loadDashboardStats();
                } else {
                    showToast(data.message, 'error');
                }
            })
            .catch(err => {
                console.error("Save banner failed:", err);
                showToast("Failed to save banner.", 'error');
            });
        };

        if (!bannerImageUrlEl.value.trim()) {
            if (fileInput && fileInput.files.length > 0) {
                // Auto-upload first
                const submitBtn = document.getElementById('bannerSubmitBtn');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Uploading & Saving...';
                
                const formData = new FormData();
                formData.append('file', fileInput.files[0]);
                
                fetch('upload.php', {
                    method: 'POST',
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> Save Banner';
                    if (data.status === 'success') {
                        bannerImageUrlEl.value = data.file_url;
                        saveBannerData(data.file_url);
                    } else {
                        showToast("Upload failed: " + data.message, "error");
                    }
                })
                .catch(err => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = '<i class="fas fa-save"></i> Save Banner';
                    console.error("Upload error:", err);
                    showToast("Upload error during auto-save. Please try again.", "error");
                });
            } else {
                showToast("Please select an image file or enter an image URL.", "warning");
            }
        } else {
            saveBannerData(bannerImageUrlEl.value.trim());
        }
    });
}

let adminBannersList = [];
window.loadBanners = function() {
    const body = document.getElementById('bannerTableBody');
    if (!body) return;
    body.innerHTML = '<tr><td colspan="4" style="color:#64748b; font-style:italic;">Loading...</td></tr>';
    
    fetch('manage_banners.php?v=' + Date.now())
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            adminBannersList = data.banners;
            if (adminBannersList.length > 0) {
                body.innerHTML = adminBannersList.map(b => `
                    <tr id="banner-row-${b.id}">
                        <td style="text-align:center;"><img src="${b.image_url}" style="max-width:80px; max-height:50px; border-radius:4px; object-fit:cover;"></td>
                        <td>${b.title || '<i>No Title</i>'}</td>
                        <td>${b.sort_order}</td>
                        <td style="text-align:center;">
                            <button type="button" class="action-icon-btn edit" onclick="event.stopPropagation(); editBanner(${b.id})"><i class="fas fa-edit"></i></button>
                            <button type="button" class="action-icon-btn delete" onclick="event.stopPropagation(); deleteBanner(${b.id})"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                `).join('');
            } else {
                body.innerHTML = '<tr><td colspan="4" style="color:#64748b; font-style:italic; text-align:center;">No banners available.</td></tr>';
            }
        }
    })
    .catch(err => {
        console.error("Fetch failed for banners", err);
        body.innerHTML = '<tr><td colspan="4" style="color:#dc2626; font-weight:500;">Failed to load records.</td></tr>';
    });
};

window.cancelBannerEdit = function() {
    const form = document.getElementById('bannerForm');
    if (form) form.reset();
    document.getElementById('bannerId').value = '';
    document.getElementById('bannerSortOrder').value = '0';
    document.getElementById('bannerImagePreview').style.display = 'none';
    document.getElementById('bannerImagePreview').src = '';
    document.getElementById('bannerCancelBtn').style.display = 'none';
    document.getElementById('bannerFormTitle').innerHTML = '<i class="fas fa-plus"></i> Add Home Banner Slide';
    
    document.querySelectorAll('#bannerTableBody tr').forEach(tr => tr.classList.remove('selected-row'));
};

window.editBanner = function(id) {
    const b = adminBannersList.find(item => item.id == id);
    if (!b) return;
    document.getElementById('bannerId').value = b.id;
    document.getElementById('bannerTitle').value = b.title || '';
    document.getElementById('bannerImageUrl').value = b.image_url;
    document.getElementById('bannerSortOrder').value = b.sort_order || 0;
    
    showLivePreview(document.getElementById('bannerImageUrl'), 'bannerImagePreview');
    
    document.getElementById('bannerCancelBtn').style.display = 'inline-flex';
    document.getElementById('bannerFormTitle').innerHTML = '<i class="fas fa-edit"></i> Edit Home Banner Slide';
    
    highlightTableRow('bannerTableBody', 'banner-row-' + id);
    highlightAndScrollToForm('bannerForm');
};

window.deleteBanner = function(id) {
    showConfirm("Delete Banner", "Are you sure you want to delete this home banner slide?")
    .then(approved => {
        if (!approved) return;
        fetch(`manage_banners.php?id=${id}`, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast(data.message || "Banner deleted successfully.");
                loadBanners();
                loadDashboardStats();
            } else {
                showToast(data.message || "Failed to delete banner.", 'error');
            }
        })
        .catch(err => {
            console.error("Delete banner failed:", err);
            showToast("Failed to delete banner.", 'error');
        });
    });
};

// Load Settings form values
window.loadSettingsEditor = function() {
    fetch('manage_settings.php?v=' + Date.now())
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            const s = data.settings;
            
            document.getElementById('setNewsEn').value = s.news_bar?.en || '';
            document.getElementById('setNewsSi').value = s.news_bar?.si || '';
            document.getElementById('setNewsTa').value = s.news_bar?.ta || '';
            
            document.getElementById('setVisionEn').value = s.site_vision?.en || '';
            document.getElementById('setVisionSi').value = s.site_vision?.si || '';
            document.getElementById('setVisionTa').value = s.site_vision?.ta || '';

            document.getElementById('setMissionEn').value = s.site_mission?.en || '';
            document.getElementById('setMissionSi').value = s.site_mission?.si || '';
            document.getElementById('setMissionTa').value = s.site_mission?.ta || '';

            document.getElementById('setOverviewEn').value = s.about_overview?.en || '';
            document.getElementById('setOverviewSi').value = s.about_overview?.si || '';
            document.getElementById('setOverviewTa').value = s.about_overview?.ta || '';

            document.getElementById('setObjectivesEn').value = s.about_objectives?.en || '';
            document.getElementById('setObjectivesSi').value = s.about_objectives?.si || '';
            document.getElementById('setObjectivesTa').value = s.about_objectives?.ta || '';

            document.getElementById('setAchievementsEn').value = s.about_achievements?.en || '';
            document.getElementById('setAchievementsSi').value = s.about_achievements?.si || '';
            document.getElementById('setAchievementsTa').value = s.about_achievements?.ta || '';

            document.getElementById('setOrgChartUrl').value = s.org_chart_url?.en || '';
            showLivePreview(document.getElementById('setOrgChartUrl'), 'orgChartPreview');

            document.getElementById('setServiceInvListEn').value = s.service_inv_list?.en || '';
            document.getElementById('setServiceInvListSi').value = s.service_inv_list?.si || '';
            document.getElementById('setServiceInvListTa').value = s.service_inv_list?.ta || '';

            document.getElementById('setServiceEngListEn').value = s.service_eng_list?.en || '';
            document.getElementById('setServiceEngListSi').value = s.service_eng_list?.si || '';
            document.getElementById('setServiceEngListTa').value = s.service_eng_list?.ta || '';

            document.getElementById('setServiceEngDescEn').value = s.service_eng_desc?.en || '';
            document.getElementById('setServiceEngDescSi').value = s.service_eng_desc?.si || '';
            document.getElementById('setServiceEngDescTa').value = s.service_eng_desc?.ta || '';

            document.getElementById('setServiceConstListEn').value = s.service_const_list?.en || '';
            document.getElementById('setServiceConstListSi').value = s.service_const_list?.si || '';
            document.getElementById('setServiceConstListTa').value = s.service_const_list?.ta || '';

            document.getElementById('setServiceConstDescEn').value = s.service_const_desc?.en || '';
            document.getElementById('setServiceConstDescSi').value = s.service_const_desc?.si || '';
            document.getElementById('setServiceConstDescTa').value = s.service_const_desc?.ta || '';

            document.getElementById('setServiceOpListEn').value = s.service_op_list?.en || '';
            document.getElementById('setServiceOpListSi').value = s.service_op_list?.si || '';
            document.getElementById('setServiceOpListTa').value = s.service_op_list?.ta || '';

            document.getElementById('setServiceInstListEn').value = s.service_inst_list?.en || '';
            document.getElementById('setServiceInstListSi').value = s.service_inst_list?.si || '';
            document.getElementById('setServiceInstListTa').value = s.service_inst_list?.ta || '';

            document.getElementById('setRtiIOEn').value = s.rti_officer_name?.en || '';
            document.getElementById('setRtiIOSi').value = s.rti_officer_name?.si || '';
            document.getElementById('setRtiIOTa').value = s.rti_officer_name?.ta || '';

            document.getElementById('setRtiIOTitleEn').value = s.rti_officer_title?.en || '';
            document.getElementById('setRtiIOTitleSi').value = s.rti_officer_title?.si || '';
            document.getElementById('setRtiIOTitleTa').value = s.rti_officer_title?.ta || '';

            document.getElementById('setRtiAOEn').value = s.rti_appellate_name?.en || '';
            document.getElementById('setRtiAOSi').value = s.rti_appellate_name?.si || '';
            document.getElementById('setRtiAOTa').value = s.rti_appellate_name?.ta || '';

            document.getElementById('setRtiAOTitleEn').value = s.rti_appellate_title?.en || '';
            document.getElementById('setRtiAOTitleSi').value = s.rti_appellate_title?.si || '';
            document.getElementById('setRtiAOTitleTa').value = s.rti_appellate_title?.ta || '';

            document.getElementById('setRtiAppSiUrl').value = s.rti_app_si_url?.en || '';
            document.getElementById('setRtiAppEnUrl').value = s.rti_app_en_url?.en || '';
            document.getElementById('setRtiAppTaUrl').value = s.rti_app_ta_url?.en || '';

            document.getElementById('setContactPhoneEn').value = s.contact_phone?.en || '';
            document.getElementById('setContactFaxEn').value = s.contact_fax?.en || '';
            document.getElementById('setContactEmailEn').value = s.contact_email?.en || '';

            document.getElementById('setContactAddressEn').value = s.contact_address?.en || '';
            document.getElementById('setContactAddressSi').value = s.contact_address?.si || '';
            document.getElementById('setContactAddressTa').value = s.contact_address?.ta || '';
            
            document.getElementById('setContactMapEn').value = s.contact_map_url?.en || '';

            document.getElementById('setSocialFacebookEn').value = s.social_facebook?.en || '';
            document.getElementById('setSocialYoutubeEn').value = s.social_youtube?.en || '';

            document.getElementById('setCharterSiUrl').value = s.citizen_charter_si_url?.en || '';
            document.getElementById('setCharterEnUrl').value = s.citizen_charter_en_url?.en || '';

            // Header Branding
            document.getElementById('setHeaderNatLogo').value = s.header_national_logo?.en || '';
            showLivePreview(document.getElementById('setHeaderNatLogo'), 'headerNatLogoPreview');
            document.getElementById('setHeaderProvLogo').value = s.header_provincial_logo?.en || '';
            showLivePreview(document.getElementById('setHeaderProvLogo'), 'headerProvLogoPreview');
            
            document.getElementById('setHeaderTitleEn').value = s.header_title_en?.en || '';
            document.getElementById('setHeaderTitleSi').value = s.header_title_si?.si || '';
            document.getElementById('setHeaderTitleTa').value = s.header_title_ta?.ta || '';
        }
    })
    .catch(err => {
        console.error("Load settings failed:", err);
    });
};

const settingsForm = document.getElementById('settingsForm');
if (settingsForm) {
    settingsForm.addEventListener('submit', async (e) => {
        e.preventDefault();
        
        const submitBtn = settingsForm.querySelector('.btn-submit');
        const originalBtnText = submitBtn ? submitBtn.innerHTML : 'Update Settings';
        if (submitBtn) {
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Processing uploads...';
        }

        try {
            const uploads = [
                { fileId: 'orgChartPhotoSelect', urlId: 'setOrgChartUrl', btnId: 'orgChartUploadBtn', previewImgId: 'orgChartPreview' },
                { fileId: 'charterSiFileSelect', urlId: 'setCharterSiUrl', btnId: 'charterSiUploadBtn' },
                { fileId: 'charterEnFileSelect', urlId: 'setCharterEnUrl', btnId: 'charterEnUploadBtn' },
                { fileId: 'rtiAppSiFileSelect', urlId: 'setRtiAppSiUrl', btnId: 'rtiAppSiUploadBtn' },
                { fileId: 'rtiAppEnFileSelect', urlId: 'setRtiAppEnUrl', btnId: 'rtiAppEnUploadBtn' },
                { fileId: 'rtiAppTaFileSelect', urlId: 'setRtiAppTaUrl', btnId: 'rtiAppTaUploadBtn' },
                { fileId: 'headerNatLogoFileSelect', urlId: 'setHeaderNatLogo', btnId: 'headerNatLogoUploadBtn', previewImgId: 'headerNatLogoPreview' },
                { fileId: 'headerProvLogoFileSelect', urlId: 'setHeaderProvLogo', btnId: 'headerProvLogoUploadBtn', previewImgId: 'headerProvLogoPreview' }
            ];

            for (const item of uploads) {
                const fileInput = document.getElementById(item.fileId);
                if (fileInput && fileInput.files.length > 0) {
                    if (submitBtn) {
                        submitBtn.innerHTML = `<i class="fas fa-spinner fa-spin"></i> Uploading ${fileInput.files[0].name}...`;
                    }
                    const uploadedUrl = await window.uploadFilePromise(item.fileId, item.btnId);
                    if (uploadedUrl) {
                        const urlInput = document.getElementById(item.urlId);
                        if (urlInput) {
                            urlInput.value = uploadedUrl;
                            if (item.previewImgId) {
                                showLivePreview(urlInput, item.previewImgId);
                            }
                        }
                        fileInput.value = ''; // clear the file selector
                    }
                }
            }
        } catch (err) {
            console.error("Auto-upload before settings save failed:", err);
            showToast("File upload failed: " + err.message, 'error');
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
            return;
        }

        if (submitBtn) {
            submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Saving Settings...';
        }

        const formData = new FormData();
        formData.append('news_bar_en', document.getElementById('setNewsEn').value);
        formData.append('news_bar_si', document.getElementById('setNewsSi').value);
        formData.append('news_bar_ta', document.getElementById('setNewsTa').value);
        
        formData.append('site_vision_en', document.getElementById('setVisionEn').value);
        formData.append('site_vision_si', document.getElementById('setVisionSi').value);
        formData.append('site_vision_ta', document.getElementById('setVisionTa').value);

        formData.append('site_mission_en', document.getElementById('setMissionEn').value);
        formData.append('site_mission_si', document.getElementById('setMissionSi').value);
        formData.append('site_mission_ta', document.getElementById('setMissionTa').value);

        formData.append('about_overview_en', document.getElementById('setOverviewEn').value);
        formData.append('about_overview_si', document.getElementById('setOverviewSi').value);
        formData.append('about_overview_ta', document.getElementById('setOverviewTa').value);

        formData.append('about_objectives_en', document.getElementById('setObjectivesEn').value);
        formData.append('about_objectives_si', document.getElementById('setObjectivesSi').value);
        formData.append('about_objectives_ta', document.getElementById('setObjectivesTa').value);

        formData.append('about_achievements_en', document.getElementById('setAchievementsEn').value);
        formData.append('about_achievements_si', document.getElementById('setAchievementsSi').value);
        formData.append('about_achievements_ta', document.getElementById('setAchievementsTa').value);

        formData.append('org_chart_url_en', document.getElementById('setOrgChartUrl').value);
        formData.append('org_chart_url_si', document.getElementById('setOrgChartUrl').value);
        formData.append('org_chart_url_ta', document.getElementById('setOrgChartUrl').value);

        formData.append('service_inv_list_en', document.getElementById('setServiceInvListEn').value);
        formData.append('service_inv_list_si', document.getElementById('setServiceInvListSi').value);
        formData.append('service_inv_list_ta', document.getElementById('setServiceInvListTa').value);

        formData.append('service_eng_list_en', document.getElementById('setServiceEngListEn').value);
        formData.append('service_eng_list_si', document.getElementById('setServiceEngListSi').value);
        formData.append('service_eng_list_ta', document.getElementById('setServiceEngListTa').value);

        formData.append('service_eng_desc_en', document.getElementById('setServiceEngDescEn').value);
        formData.append('service_eng_desc_si', document.getElementById('setServiceEngDescSi').value);
        formData.append('service_eng_desc_ta', document.getElementById('setServiceEngDescTa').value);

        formData.append('service_const_list_en', document.getElementById('setServiceConstListEn').value);
        formData.append('service_const_list_si', document.getElementById('setServiceConstListSi').value);
        formData.append('service_const_list_ta', document.getElementById('setServiceConstListTa').value);

        formData.append('service_const_desc_en', document.getElementById('setServiceConstDescEn').value);
        formData.append('service_const_desc_si', document.getElementById('setServiceConstDescSi').value);
        formData.append('service_const_desc_ta', document.getElementById('setServiceConstDescTa').value);

        formData.append('service_op_list_en', document.getElementById('setServiceOpListEn').value);
        formData.append('service_op_list_si', document.getElementById('setServiceOpListSi').value);
        formData.append('service_op_list_ta', document.getElementById('setServiceOpListTa').value);

        formData.append('service_inst_list_en', document.getElementById('setServiceInstListEn').value);
        formData.append('service_inst_list_si', document.getElementById('setServiceInstListSi').value);
        formData.append('service_inst_list_ta', document.getElementById('setServiceInstListTa').value);

        formData.append('rti_officer_name_en', document.getElementById('setRtiIOEn').value);
        formData.append('rti_officer_name_si', document.getElementById('setRtiIOSi').value);
        formData.append('rti_officer_name_ta', document.getElementById('setRtiIOTa').value);

        formData.append('rti_officer_title_en', document.getElementById('setRtiIOTitleEn').value);
        formData.append('rti_officer_title_si', document.getElementById('setRtiIOTitleSi').value);
        formData.append('rti_officer_title_ta', document.getElementById('setRtiIOTitleTa').value);

        formData.append('rti_appellate_name_en', document.getElementById('setRtiAOEn').value);
        formData.append('rti_appellate_name_si', document.getElementById('setRtiAOSi').value);
        formData.append('rti_appellate_name_ta', document.getElementById('setRtiAOTa').value);

        formData.append('rti_appellate_title_en', document.getElementById('setRtiAOTitleEn').value);
        formData.append('rti_appellate_title_si', document.getElementById('setRtiAOTitleSi').value);
        formData.append('rti_appellate_title_ta', document.getElementById('setRtiAOTitleTa').value);

        formData.append('rti_app_si_url_en', document.getElementById('setRtiAppSiUrl').value);
        formData.append('rti_app_si_url_si', document.getElementById('setRtiAppSiUrl').value);
        formData.append('rti_app_si_url_ta', document.getElementById('setRtiAppSiUrl').value);

        formData.append('rti_app_en_url_en', document.getElementById('setRtiAppEnUrl').value);
        formData.append('rti_app_en_url_si', document.getElementById('setRtiAppEnUrl').value);
        formData.append('rti_app_en_url_ta', document.getElementById('setRtiAppEnUrl').value);

        formData.append('rti_app_ta_url_en', document.getElementById('setRtiAppTaUrl').value);
        formData.append('rti_app_ta_url_si', document.getElementById('setRtiAppTaUrl').value);
        formData.append('rti_app_ta_url_ta', document.getElementById('setRtiAppTaUrl').value);

        const phoneVal = document.getElementById('setContactPhoneEn').value;
        formData.append('contact_phone_en', phoneVal);
        formData.append('contact_phone_si', phoneVal);
        formData.append('contact_phone_ta', phoneVal);

        const faxVal = document.getElementById('setContactFaxEn').value;
        formData.append('contact_fax_en', faxVal);
        formData.append('contact_fax_si', faxVal);
        formData.append('contact_fax_ta', faxVal);

        const emailVal = document.getElementById('setContactEmailEn').value;
        formData.append('contact_email_en', emailVal);
        formData.append('contact_email_si', emailVal);
        formData.append('contact_email_ta', emailVal);

        formData.append('contact_address_en', document.getElementById('setContactAddressEn').value);
        formData.append('contact_address_si', document.getElementById('setContactAddressSi').value);
        formData.append('contact_address_ta', document.getElementById('setContactAddressTa').value);

        const mapVal = document.getElementById('setContactMapEn').value;
        formData.append('contact_map_url_en', mapVal);
        formData.append('contact_map_url_si', mapVal);
        formData.append('contact_map_url_ta', mapVal);

        formData.append('social_facebook_en', document.getElementById('setSocialFacebookEn').value);
        formData.append('social_facebook_si', document.getElementById('setSocialFacebookEn').value);
        formData.append('social_facebook_ta', document.getElementById('setSocialFacebookEn').value);

        formData.append('social_youtube_en', document.getElementById('setSocialYoutubeEn').value);
        formData.append('social_youtube_si', document.getElementById('setSocialYoutubeEn').value);
        formData.append('social_youtube_ta', document.getElementById('setSocialYoutubeEn').value);

        formData.append('citizen_charter_si_url_en', document.getElementById('setCharterSiUrl').value);
        formData.append('citizen_charter_si_url_si', document.getElementById('setCharterSiUrl').value);
        formData.append('citizen_charter_si_url_ta', document.getElementById('setCharterSiUrl').value);

        formData.append('citizen_charter_en_url_en', document.getElementById('setCharterEnUrl').value);
        formData.append('citizen_charter_en_url_si', document.getElementById('setCharterEnUrl').value);
        formData.append('citizen_charter_en_url_ta', document.getElementById('setCharterEnUrl').value);

        // Header Branding
        const natLogoVal = document.getElementById('setHeaderNatLogo').value;
        formData.append('header_national_logo_en', natLogoVal);
        formData.append('header_national_logo_si', natLogoVal);
        formData.append('header_national_logo_ta', natLogoVal);

        const provLogoVal = document.getElementById('setHeaderProvLogo').value;
        formData.append('header_provincial_logo_en', provLogoVal);
        formData.append('header_provincial_logo_si', provLogoVal);
        formData.append('header_provincial_logo_ta', provLogoVal);

        const titleEnVal = document.getElementById('setHeaderTitleEn').value;
        formData.append('header_title_en_en', titleEnVal);
        formData.append('header_title_en_si', titleEnVal);
        formData.append('header_title_en_ta', titleEnVal);

        const titleSiVal = document.getElementById('setHeaderTitleSi').value;
        formData.append('header_title_si_en', titleSiVal);
        formData.append('header_title_si_si', titleSiVal);
        formData.append('header_title_si_ta', titleSiVal);

        const titleTaVal = document.getElementById('setHeaderTitleTa').value;
        formData.append('header_title_ta_en', titleTaVal);
        formData.append('header_title_ta_si', titleTaVal);
        formData.append('header_title_ta_ta', titleTaVal);

        fetch('manage_settings.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
            if (data.status === 'success') {
                showToast("Settings updated successfully!");
                loadSettingsEditor();
            } else {
                showToast(data.message, 'error');
            }
        })
        .catch(err => {
            if (submitBtn) {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
            console.error("Save settings failed:", err);
            showToast("Failed to save settings.", 'error');
        });
    });
}

// Preview views population
window.loadOfficersPreview = function() {
    fetchAdminData('manage_officers.php', 'previewOffTableBody', (o) => {
        const photoHtml = o.photo_url 
            ? `<img src="${o.photo_url}" onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';" style="width: 60px; height: 60px; object-fit: cover; border-radius: 50%; border: 3px solid #ffffff; box-shadow: 0 4px 10px rgba(0,0,0,0.08); display: block; margin: 0 auto;">
               <div style="display: none; width: 60px; height: 60px; border-radius: 50%; background: #f1f5f9; align-items: center; justify-content: center; margin: 0 auto; color: #94a3b8; font-size: 1.5rem; border: 3px solid #ffffff; box-shadow: 0 4px 10px rgba(0,0,0,0.08);"><i class="fas fa-user-tie"></i></div>`
            : `<div style="width: 60px; height: 60px; border-radius: 50%; background: #f1f5f9; display: flex; align-items: center; justify-content: center; margin: 0 auto; color: #94a3b8; font-size: 1.5rem; border: 3px solid #ffffff; box-shadow: 0 4px 10px rgba(0,0,0,0.08);"><i class="fas fa-user-tie"></i></div>`;
        
        return `
            <tr>
                <td style="text-align: center; vertical-align: middle;">${photoHtml}</td>
                <td style="vertical-align: middle;"><strong style="font-size: 0.95rem; color: #0f172a; display: block; margin-bottom: 2px;">${o.name}</strong></td>
                <td style="vertical-align: middle;"><span style="font-size: 0.88rem; color: #475569; font-weight: 500;">${o.title}</span></td>
                <td style="vertical-align: middle;"><span style="font-size: 0.88rem; color: #64748b;">${o.phone}</span></td>
                <td style="vertical-align: middle;"><span class="badge" style="background:#e2e8f0; color:#475569; padding:4px 8px; border-radius:12px; font-size:0.75rem; font-weight:600; text-transform:uppercase;">${o.category}</span></td>
                <td style="vertical-align: middle;">
                    ${o.email ? `<a href="mailto:${o.email}" style="color: var(--portal-blue); font-weight: 500; font-size: 0.88rem; display: inline-flex; align-items: center; gap: 6px;"><i class="far fa-envelope"></i> ${o.email}</a>` : '<span style="color:#94a3b8; font-size:0.88rem;">N/A</span>'}
                </td>
            </tr>
        `;
    });
};

window.loadDownloadsPreview = function() {
    fetchAdminData('manage_downloads.php', 'previewDlTableBody', (d) => `
        <tr>
            <td><b>${d.category.toUpperCase()}</b></td>
            <td>${d.title}</td>
            <td>${d.description}</td>
        </tr>
    `);
};

// Booking Form Submission
const bookingForm = document.getElementById('bookingForm');
if (bookingForm) {
    bookingForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append('id', document.getElementById('bookingId').value);
        formData.append('booking_date', document.getElementById('bookingDate').value);
        formData.append('booked_by', document.getElementById('bookingBookedBy').value);
        formData.append('title', document.getElementById('bookingTitle').value);

        fetch('manage_bookings.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast(data.message || "Booking saved successfully!");
                bookingForm.reset();
                document.getElementById('bookingId').value = '';
                loadBookings();
                loadDashboardStats();
            } else {
                showToast(data.message, "error");
            }
        })
        .catch(err => {
            console.error("Save booking error:", err);
            showToast("Failed to save booking.", "error");
        });
    });
}

let adminBookingsList = [];
window.loadBookings = function() {
    const body = document.getElementById('bookingTableBody');
    if (!body) return;
    body.innerHTML = '<tr><td colspan="4" style="color:#64748b; font-style:italic;">Loading...</td></tr>';
    
    fetch('manage_bookings.php?v=' + Date.now())
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            adminBookingsList = data.bookings;
            if (adminBookingsList.length > 0) {
                body.innerHTML = adminBookingsList.map(b => `
                    <tr id="booking-row-${b.id}">
                        <td>${b.booking_date}</td>
                        <td><b>${b.booked_by}</b></td>
                        <td>${b.title}</td>
                        <td>
                            <button class="action-icon-btn edit" onclick="event.stopPropagation(); editBooking(${b.id})"><i class="fas fa-edit"></i></button>
                            <button class="action-icon-btn delete" onclick="event.stopPropagation(); deleteBooking(${b.id})"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                `).join('');
            } else {
                body.innerHTML = '<tr><td colspan="4" style="color:#64748b; font-style:italic; text-align:center;">No bookings available.</td></tr>';
            }
        }
    })
    .catch(err => {
        console.error("Fetch failed for bookings", err);
        body.innerHTML = '<tr><td colspan="4" style="color:#dc2626; font-weight:500;">Failed to load bookings.</td></tr>';
    });
};

window.editBooking = function(id) {
    const b = adminBookingsList.find(item => item.id == id);
    if (!b) return;
    document.getElementById('bookingId').value = b.id;
    document.getElementById('bookingDate').value = b.booking_date;
    document.getElementById('bookingBookedBy').value = b.booked_by;
    document.getElementById('bookingTitle').value = b.title;
    
    highlightAndScrollToForm('bookingForm');
    highlightTableRow('bookingTableBody', `booking-row-${id}`);
};

window.deleteBooking = function(id) {
    showConfirm("Delete Booking", "Are you sure you want to delete this hall booking?")
    .then(approved => {
        if (!approved) return;
        fetch(`manage_bookings.php?id=${id}`, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast(data.message || "Booking deleted successfully!");
                loadBookings();
                loadDashboardStats();
            } else {
                showToast(data.message || "Failed to delete booking.", "error");
            }
        })
        .catch(err => {
            console.error("Delete booking failed:", err);
            showToast("Failed to delete booking.", "error");
        });
    });
};

// Bus Booking Form Submission
const busBookingForm = document.getElementById('busBookingForm');
if (busBookingForm) {
    busBookingForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append('id', document.getElementById('busBookingId').value);
        formData.append('booking_date', document.getElementById('busBookingDate').value);
        formData.append('booked_by', document.getElementById('busBookingBookedBy').value);
        formData.append('title', document.getElementById('busBookingTitle').value);

        fetch('manage_bus_bookings.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast(data.message || "Vehicle booking saved successfully!");
                busBookingForm.reset();
                document.getElementById('busBookingId').value = '';
                loadBusBookings();
            } else {
                showToast(data.message, "error");
            }
        })
        .catch(err => {
            console.error("Save bus booking error:", err);
            showToast("Failed to save vehicle booking.", "error");
        });
    });
}

let adminBusBookingsList = [];
window.loadBusBookings = function() {
    const body = document.getElementById('busBookingTableBody');
    if (!body) return;
    body.innerHTML = '<tr><td colspan="4" style="color:#64748b; font-style:italic;">Loading...</td></tr>';
    
    fetch('manage_bus_bookings.php?v=' + Date.now())
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            adminBusBookingsList = data.bookings;
            if (adminBusBookingsList.length > 0) {
                body.innerHTML = adminBusBookingsList.map(b => `
                    <tr id="bus-booking-row-${b.id}">
                        <td>${b.booking_date}</td>
                        <td><b>${b.booked_by}</b></td>
                        <td>${b.title}</td>
                        <td>
                            <button class="action-icon-btn edit" onclick="event.stopPropagation(); editBusBooking(${b.id})"><i class="fas fa-edit"></i></button>
                            <button class="action-icon-btn delete" onclick="event.stopPropagation(); deleteBusBooking(${b.id})"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                `).join('');
            } else {
                body.innerHTML = '<tr><td colspan="4" style="color:#64748b; font-style:italic; text-align:center;">No vehicle bookings available.</td></tr>';
            }
        }
    })
    .catch(err => {
        console.error("Fetch failed for bus bookings", err);
        body.innerHTML = '<tr><td colspan="4" style="color:#dc2626; font-weight:500;">Failed to load vehicle bookings.</td></tr>';
    });
};

window.editBusBooking = function(id) {
    const b = adminBusBookingsList.find(item => item.id == id);
    if (!b) return;
    document.getElementById('busBookingId').value = b.id;
    document.getElementById('busBookingDate').value = b.booking_date;
    document.getElementById('busBookingBookedBy').value = b.booked_by;
    document.getElementById('busBookingTitle').value = b.title;
    
    highlightAndScrollToForm('busBookingForm');
    highlightTableRow('busBookingTableBody', `bus-booking-row-${id}`);
};

window.deleteBusBooking = function(id) {
    showConfirm("Delete Vehicle Booking", "Are you sure you want to delete this vehicle booking?")
    .then(approved => {
        if (!approved) return;
        fetch(`manage_bus_bookings.php?id=${id}`, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast(data.message || "Vehicle booking deleted successfully!");
                loadBusBookings();
            } else {
                showToast(data.message || "Failed to delete vehicle booking.", "error");
            }
        })
        .catch(err => {
            console.error("Delete bus booking failed:", err);
            showToast("Failed to delete vehicle booking.", "error");
        });
    });
};


// Services tab logic
const servicesForm = document.getElementById('servicesForm');
if (servicesForm) {
    servicesForm.addEventListener('submit', (e) => {
        e.preventDefault();
        const formData = new FormData();
        formData.append('id', document.getElementById('serviceId').value);
        formData.append('icon_class', document.getElementById('serviceIconClass').value);
        formData.append('icon_bg', document.getElementById('serviceIconBg').value);
        
        formData.append('title_en', document.getElementById('serviceTitleEn').value);
        formData.append('title_si', document.getElementById('serviceTitleSi').value);
        formData.append('title_ta', document.getElementById('serviceTitleTa').value);
        
        formData.append('short_desc_en', document.getElementById('serviceShortDescEn').value);
        formData.append('short_desc_si', document.getElementById('serviceShortDescSi').value);
        formData.append('short_desc_ta', document.getElementById('serviceShortDescTa').value);
        
        formData.append('bullets_en', document.getElementById('serviceBulletsEn').value);
        formData.append('bullets_si', document.getElementById('serviceBulletsSi').value);
        formData.append('bullets_ta', document.getElementById('serviceBulletsTa').value);
        
        formData.append('long_desc_en', document.getElementById('serviceLongDescEn').value);
        formData.append('long_desc_si', document.getElementById('serviceLongDescSi').value);
        formData.append('long_desc_ta', document.getElementById('serviceLongDescTa').value);
        
        formData.append('sort_order', document.getElementById('serviceSortOrder').value);

        fetch('manage_services.php', { method: 'POST', body: formData })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast(data.message || "Service saved successfully!");
                resetServicesForm();
                loadServices();
                loadDashboardStats();
            } else {
                showToast(data.message, "error");
            }
        })
        .catch(err => {
            console.error("Save service error:", err);
            showToast("Failed to save service.", "error");
        });
    });
}

let adminServicesList = [];
window.loadServices = function() {
    const body = document.getElementById('servicesTableBody');
    if (!body) return;
    body.innerHTML = '<tr><td colspan="5" style="color:#64748b; font-style:italic;">Loading...</td></tr>';
    
    fetch('manage_services.php?v=' + Date.now())
    .then(res => res.json())
    .then(data => {
        if (data.status === 'success') {
            adminServicesList = data.services;
            if (adminServicesList.length > 0) {
                body.innerHTML = adminServicesList.map(s => `
                    <tr id="service-row-${s.id}">
                        <td>${s.sort_order}</td>
                        <td style="text-align: center;"><span style="background: ${s.icon_bg}; padding: 5px; border-radius: 4px; color: white;"><i class="${s.icon_class}"></i></span></td>
                        <td>${s.title_en}</td>
                        <td>${s.title_si}</td>
                        <td>
                            <button class="action-icon-btn edit" onclick="event.stopPropagation(); editService(${s.id})"><i class="fas fa-edit"></i></button>
                            <button class="action-icon-btn delete" onclick="event.stopPropagation(); deleteService(${s.id})"><i class="fas fa-trash-alt"></i></button>
                        </td>
                    </tr>
                `).join('');
            } else {
                body.innerHTML = '<tr><td colspan="5" style="color:#64748b; font-style:italic; text-align:center;">No records available.</td></tr>';
            }
        }
    })
    .catch(err => {
        console.error("Fetch failed for services", err);
        body.innerHTML = '<tr><td colspan="5" style="color:#dc2626; font-weight:500;">Failed to load records.</td></tr>';
    });
};

window.editService = function(id) {
    const s = adminServicesList.find(item => item.id == id);
    if (!s) return;
    document.getElementById('serviceId').value = s.id;
    document.getElementById('serviceIconClass').value = s.icon_class;
    document.getElementById('serviceIconBg').value = s.icon_bg;
    
    document.getElementById('serviceTitleEn').value = s.title_en;
    document.getElementById('serviceTitleSi').value = s.title_si;
    document.getElementById('serviceTitleTa').value = s.title_ta;
    
    document.getElementById('serviceShortDescEn').value = s.short_desc_en;
    document.getElementById('serviceShortDescSi').value = s.short_desc_si;
    document.getElementById('serviceShortDescTa').value = s.short_desc_ta;
    
    document.getElementById('serviceBulletsEn').value = s.bullets_en || '';
    document.getElementById('serviceBulletsSi').value = s.bullets_si || '';
    document.getElementById('serviceBulletsTa').value = s.bullets_ta || '';
    
    document.getElementById('serviceLongDescEn').value = s.long_desc_en || '';
    document.getElementById('serviceLongDescSi').value = s.long_desc_si || '';
    document.getElementById('serviceLongDescTa').value = s.long_desc_ta || '';
    
    document.getElementById('serviceSortOrder').value = s.sort_order;

    document.getElementById('servicesFormTitle').innerHTML = `<i class="fas fa-edit"></i> Edit Service: ${s.title_en}`;
    document.getElementById('cancelServiceEditBtn').style.display = 'inline-block';

    highlightTableRow('servicesTableBody', 'service-row-' + id);
    highlightAndScrollToForm('servicesForm');
};

window.deleteService = function(id) {
    showConfirm("Delete Service", "Are you sure you want to delete this service? This will remove it from the home page grid.")
    .then(approved => {
        if (!approved) return;
        fetch(`manage_services.php?id=${id}`, { method: 'DELETE' })
        .then(res => res.json())
        .then(data => {
            if (data.status === 'success') {
                showToast(data.message || "Service deleted successfully!");
                loadServices();
                loadDashboardStats();
            } else {
                showToast(data.message || "Failed to delete.", "error");
            }
        })
        .catch(err => {
            console.error("Delete service error:", err);
            showToast("Failed to delete service.", "error");
        });
    });
};

window.resetServicesForm = function() {
    if (servicesForm) servicesForm.reset();
    document.getElementById('serviceId').value = '';
    document.getElementById('servicesFormTitle').innerHTML = `<i class="fas fa-plus"></i> Add New Service`;
    document.getElementById('cancelServiceEditBtn').style.display = 'none';
    const tbody = document.getElementById('servicesTableBody');
    if (tbody) tbody.querySelectorAll('tr').forEach(tr => tr.classList.remove('selected-row'));
};

// Initialize admin tables loading
loadNews();
loadAnnouncements();
loadDownloads();
loadProcurements();
loadOfficers();
loadProjects();
loadCourses();
loadLinks();
loadGallery();
loadBanners();
loadSettingsEditor();
loadDashboardStats();
loadBookings();
loadBusBookings();
loadServices();

// Mobile Sidebar Drawer Toggle Logic
(function() {
    const adminHamburger = document.getElementById('adminHamburger');
    const adminSidebar = document.getElementById('adminSidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    if (adminHamburger && adminSidebar && sidebarOverlay) {
        const toggleSidebar = () => {
            adminSidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('active');
        };

        const closeSidebar = () => {
            adminSidebar.classList.remove('active');
            sidebarOverlay.classList.remove('active');
        };

        adminHamburger.addEventListener('click', toggleSidebar);
        sidebarOverlay.addEventListener('click', closeSidebar);

        // Auto close drawer when a navigation link is clicked
        const navLinks = adminSidebar.querySelectorAll('.sidebar-menu li a');
        navLinks.forEach(link => {
            link.addEventListener('click', closeSidebar);
        });
    }
})();
