<!-- Social Links Sidebar -->
<div id="socialSidebar" class="social-sidebar">
    <button class="sidebar-close-btn" aria-label="Close">
        <i class="bi bi-x"></i>
    </button>
    <div class="sidebar-content">
        <p class="social-links-title">Contact Us</p>
        <div class="social-icons-list">
            <a href="https://wa.me/2347062412190" target="_blank" rel="noopener noreferrer" class="sidebar-icon-link">
                <div class="sidebar-icon-circle" style="background:#25D366;">
                    <i class="bi bi-whatsapp"></i>
                </div>
                <div class="sidebar-icon-name">WhatsApp 1</div>
            </a>
            <a href="https://wa.me/2348135836125" target="_blank" rel="noopener noreferrer" class="sidebar-icon-link">
                <div class="sidebar-icon-circle" style="background:#25D366;">
                    <i class="bi bi-whatsapp"></i>
                </div>
                <div class="sidebar-icon-name">WhatsApp 2</div>
            </a>
        </div>
    </div>
</div>

<style>
.social-sidebar {
    position: fixed;
    right: 0;
    top: 50%;
    transform: translateY(-50%);
    z-index: 9999;
    background: white;
    border-radius: 12px 0 0 12px;
    box-shadow: -2px 0 20px rgba(0,0,0,0.1);
    padding: 16px 12px;
    display: flex;
    flex-direction: column;
    align-items: center;
}

.sidebar-close-btn {
    align-self: flex-end;
    background: #f0f0f0;
    border: none;
    cursor: pointer;
    color: #555;
    padding: 0;
    margin-bottom: 6px;
    width: 22px;
    height: 22px;
    border-radius: 50%;
    line-height: 1;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: background 0.2s ease, color 0.2s ease;
}

.sidebar-close-btn:hover {
    background: #e0e0e0;
    color: #111;
}

.sidebar-close-btn i {
    font-size: 1rem;
}

.sidebar-content {
    display: flex;
    flex-direction: column;
    align-items: center;
}

.social-links-title {
    font-size: 0.7rem;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 1px;
    margin-bottom: 12px;
    text-align: center;
    font-weight: 600;
}

.social-icons-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
    align-items: center;
}

.sidebar-icon-link {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 5px;
    text-decoration: none;
    transition: all 0.2s ease;
}

.sidebar-icon-link:hover {
    transform: translateX(-4px);
}

.sidebar-icon-circle {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: transform 0.2s ease;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
}

.sidebar-icon-circle:hover {
    transform: scale(1.05);
}

.sidebar-icon-circle i {
    font-size: 1.3rem;
    color: white;
}

.sidebar-icon-name {
    font-size: 0.6rem;
    color: #555;
    font-weight: 500;
    text-align: center;
}

/* Mobile Responsive */
@media (max-width: 768px) {
    .social-sidebar {
        top: auto;
        bottom: 20px;
        right: 20px;
        left: auto;
        transform: none;
        border-radius: 50px;
        flex-direction: row;
        padding: 10px 18px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.15);
        align-items: center;
    }

    .sidebar-close-btn {
        align-self: center;
        margin-bottom: 0;
        margin-left: 8px;
        order: 1;
    }

    .sidebar-content {
        flex-direction: row;
        gap: 15px;
    }

    .social-links-title {
        display: none;
    }

    .social-icons-list {
        flex-direction: row;
        gap: 20px;
    }

    .sidebar-icon-link {
        flex-direction: row;
        gap: 8px;
    }

    .sidebar-icon-circle {
        width: 35px;
        height: 35px;
    }

    .sidebar-icon-circle i {
        font-size: 1.1rem;
    }

    .sidebar-icon-name {
        font-size: 0.7rem;
    }

    .sidebar-icon-link:hover {
        transform: translateY(-2px);
    }
}

/* Extra small devices */
@media (max-width: 480px) {
    .social-sidebar {
        bottom: 10px;
        right: 10px;
        padding: 8px 14px;
    }

    .social-icons-list {
        gap: 12px;
    }

    .sidebar-icon-name {
        display: none;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    document.querySelector('.sidebar-close-btn').addEventListener('click', function () {
        document.getElementById('socialSidebar').style.display = 'none';
    });
});
</script>