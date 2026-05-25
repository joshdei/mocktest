<!-- Social Links Popup Modal -->
<div id="socialPopup" class="popup-modal" style="display: none;">
    <div class="popup-overlay"></div>
    <div class="popup-container">
        <button class="popup-close" onclick="closeSocialPopup()">&times;</button>
        
        <div class="popup-content">
            {{-- <div class="popup-icon">🌍</div>
            <h2 class="popup-title">Connect With Us!</h2>
            <p class="popup-subtitle">Join our community to get exam updates, free materials, and study tips directly on your favorite platforms.</p>
            
            <div class="popup-benefits">
                <div class="benefit-item">
                    <span class="benefit-icon">✅</span>
                    <span>Instant Exam Updates</span>
                </div>
                <div class="benefit-item">
                    <span class="benefit-icon">✅</span>
                    <span>Free Past Questions</span>
                </div>
                <div class="benefit-item">
                    <span class="benefit-icon">✅</span>
                    <span>Study Tips & Tricks</span>
                </div>
                <div class="benefit-item">
                    <span class="benefit-icon">✅</span>
                    <span>Early Access to Materials</span>
                </div>
            </div> --}}
            
            <!-- Social Links from Database -->
            @php
                $links = App\Models\Link::latest()->where('status', 'active')->get();
            @endphp
            
            @if($links->count() > 0)
            <div class="popup-social-links">
                <p class="social-links-title">Follow us on:</p>
                <div class="social-icons-grid">
                    @foreach ($links as $link)
                        <a href="{{ $link->url }}" target="_blank" class="social-icon-link" title="{{ $link->title }}">
                            @php 
                                $title = strtolower($link->title); 
                                $icon = '';
                                $bgColor = '';
                                
                                if(str_contains($title, 'whatsapp')) {
                                    $icon = 'fab fa-whatsapp';
                                    $bgColor = '#25D366';
                                } elseif(str_contains($title, 'facebook')) {
                                    $icon = 'fab fa-facebook-f';
                                    $bgColor = '#1877F2';
                                } elseif(str_contains($title, 'telegram')) {
                                    $icon = 'fab fa-telegram-plane';
                                    $bgColor = '#26A5E4';
                                } elseif(str_contains($title, 'email')) {
                                    $icon = 'fas fa-envelope';
                                    $bgColor = '#EA4335';
                                } elseif(str_contains($title, 'youtube')) {
                                    $icon = 'fab fa-youtube';
                                    $bgColor = '#FF0000';
                                } elseif(str_contains($title, 'instagram')) {
                                    $icon = 'fab fa-instagram';
                                    $bgColor = '#E4405F';
                                } elseif(str_contains($title, 'twitter') || str_contains($title, 'x.com')) {
                                    $icon = 'fab fa-twitter';
                                    $bgColor = '#1DA1F2';
                                } elseif(str_contains($title, 'linkedin')) {
                                    $icon = 'fab fa-linkedin-in';
                                    $bgColor = '#0077B5';
                                } else {
                                    $icon = 'fas fa-link';
                                    $bgColor = '#6B7280';
                                }
                            @endphp
                            <div class="social-icon-circle" style="background: {{ $bgColor }};">
                                <i class="{{ $icon }} social-icon-fa"></i>
                            </div>
                            <span class="social-icon-name">{{ $link->title }}</span>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif
            
            {{-- <div class="popup-divider">
                <span>or</span>
            </div> --}}
            
            {{-- <!-- Email Subscription -->
            <form id="popupNewsletterForm" onsubmit="submitPopupSubscription(event)">
                @csrf
                <div class="popup-input-group">
                    <input type="email" id="popup-email" placeholder="Enter your email address" required>
                    <button type="submit" class="popup-subscribe-btn">Subscribe →</button>
                </div>
                <label class="popup-checkbox">
                    <input type="checkbox" id="popup-consent" required>  
                    <span>I want to receive study tips and updates. <a href="#privacy" onclick="showPol('privacy')">Privacy Policy</a></span>
                </label>
                <p class="popup-note">🎓 Join 5,000+ students already subscribed!</p>
            </form> --}}
            
            <div id="popupMessage" class="popup-message" style="display: none;"></div>
            
            <button class="popup-not-now" onclick="closeSocialPopup()">Maybe later →</button>
        </div>
    </div>
</div>

<style>
/* Popup Modal Styles */
.popup-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 10000;
    display: none;
    align-items: center;
    justify-content: center;
}

.popup-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.8);
    backdrop-filter: blur(8px);
}

.popup-container {
    position: relative;
    background: white;
    border-radius: 32px;
    max-width: 550px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
    animation: popupSlideIn 0.4s ease;
}

@keyframes popupSlideIn {
    from {
        opacity: 0;
        transform: translateY(-50px) scale(0.95);
    }
    to {
        opacity: 1;
        transform: translateY(0) scale(1);
    }
}

.popup-close {
    position: absolute;
    top: 16px;
    right: 20px;
    background: none;
    border: none;
    font-size: 28px;
    cursor: pointer;
    color: #999;
    z-index: 10;
    transition: all 0.2s;
    width: 36px;
    height: 36px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}

.popup-close:hover {
    color: #333;
    background: #f0f0f0;
}

.popup-content {
    padding: 40px 32px;
    text-align: center;
}

.popup-icon {
    font-size: 4rem;
    margin-bottom: 16px;
}

.popup-title {
    font-size: 1.8rem;
    font-weight: 700;
    margin-bottom: 12px;
    background: linear-gradient(135deg, #1A6B3C, #2D8A52);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}

.popup-subtitle {
    color: #666;
    margin-bottom: 24px;
    font-size: 0.95rem;
    line-height: 1.5;
}

.popup-benefits {
    background: #f8fafc;
    border-radius: 20px;
    padding: 20px;
    margin-bottom: 28px;
    text-align: left;
}

.benefit-item {
    display: flex;
    align-items: center;
    gap: 12px;
    margin-bottom: 12px;
    font-size: 0.9rem;
    color: #333;
}

.benefit-item:last-child {
    margin-bottom: 0;
}

.benefit-icon {
    font-size: 1rem;
}

/* Social Links Styles */
.popup-social-links {
    margin-bottom: 28px;
}

.social-links-title {
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 16px;
    text-transform: uppercase;
    letter-spacing: 1px;
}

.social-icons-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(90px, 1fr));
    gap: 16px;
}

.social-icon-link {
    text-decoration: none;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 8px;
    padding: 12px 8px;
    border-radius: 16px;
    transition: all 0.2s;
    background: #f8fafc;
}

.social-icon-link:hover {
    transform: translateY(-3px);
    background: #f0fdf4;
}

.social-icon-circle {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.social-icon-fa {
    font-size: 1.8rem;
    color: white;
}

.social-icon-name {
    font-size: 0.7rem;
    color: #333;
    font-weight: 500;
}

/* Divider */
.popup-divider {
    position: relative;
    text-align: center;
    margin: 20px 0;
}

.popup-divider span {
    background: white;
    padding: 0 16px;
    color: #999;
    font-size: 0.8rem;
}

.popup-divider::before {
    content: '';
    position: absolute;
    top: 50%;
    left: 0;
    right: 0;
    height: 1px;
    background: #e5e7eb;
    z-index: -1;
}

/* Input Group */
.popup-input-group {
    display: flex;
    gap: 12px;
    margin-bottom: 16px;
}

.popup-input-group input {
    flex: 1;
    padding: 14px 16px;
    border: 1px solid #e5e7eb;
    border-radius: 60px;
    font-size: 0.95rem;
    transition: all 0.2s;
}

.popup-input-group input:focus {
    outline: none;
    border-color: #1A6B3C;
    box-shadow: 0 0 0 3px rgba(26, 107, 60, 0.1);
}

.popup-subscribe-btn {
    background: linear-gradient(135deg, #1A6B3C, #2D8A52);
    color: white;
    border: none;
    padding: 14px 28px;
    border-radius: 60px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s;
    white-space: nowrap;
}

.popup-subscribe-btn:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 20px rgba(26, 107, 60, 0.3);
}

.popup-checkbox {
    display: flex;
    align-items: flex-start;
    gap: 10px;
    margin-bottom: 12px;
    font-size: 0.75rem;
    color: #666;
    text-align: left;
    cursor: pointer;
}

.popup-checkbox input {
    margin-top: 2px;
    cursor: pointer;
}

.popup-checkbox a {
    color: #1A6B3C;
    text-decoration: none;
}

.popup-checkbox a:hover {
    text-decoration: underline;
}

.popup-note {
    font-size: 0.7rem;
    color: #999;
    margin-top: 12px;
}

.popup-message {
    margin-top: 16px;
    padding: 12px;
    border-radius: 12px;
    font-size: 0.85rem;
}

.popup-message.success {
    background: #d1fae5;
    color: #059669;
}

.popup-message.error {
    background: #fee2e2;
    color: #dc2626;
}

.popup-not-now {
    background: none;
    border: none;
    color: #999;
    cursor: pointer;
    font-size: 0.85rem;
    margin-top: 20px;
    transition: color 0.2s;
}

.popup-not-now:hover {
    color: #1A6B3C;
}

@media (max-width: 600px) {
    .popup-content {
        padding: 32px 20px;
    }
    
    .popup-title {
        font-size: 1.5rem;
    }
    
    .popup-input-group {
        flex-direction: column;
    }
    
    .popup-subscribe-btn {
        width: 100%;
    }
    
    .social-icons-grid {
        grid-template-columns: repeat(2, 1fr);
        gap: 12px;
    }
}
</style>

<script>
// Social Popup - Shows on EVERY page refresh
let socialPopupShown = false;

function showSocialPopup() {
    if (socialPopupShown) return;
    
    const popup = document.getElementById('socialPopup');
    if (popup) {
        popup.style.display = 'flex';
        socialPopupShown = true;
        // REMOVED sessionStorage.setItem - this was limiting to once per session
    }
}
 
function closeSocialPopup() {
    const popup = document.getElementById('socialPopup');
    if (popup) {
        popup.style.display = 'none';
    }
}

// Submit popup subscription
function submitPopupSubscription(event) {
    event.preventDefault();
    
    const email = document.getElementById('popup-email').value;
    const consent = document.getElementById('popup-consent').checked;
    const messageDiv = document.getElementById('popupMessage');
    const submitBtn = document.querySelector('.popup-subscribe-btn');
    const originalBtnText = submitBtn.textContent;
    
    if (!consent) {
        showPopupMessage('Please agree to receive updates', 'error');
        return;
    }
    
    submitBtn.textContent = 'Subscribing...';
    submitBtn.disabled = true;
    
    fetch('{{ url("newsletter.subscribe") }}', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Content-Type': 'application/json',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showPopupMessage('✅ Thank you! Check your email for confirmation.', 'success');
            document.getElementById('popup-email').value = '';
            document.getElementById('popup-consent').checked = false;
            
            setTimeout(() => {
                closeSocialPopup();
            }, 3000);
        } else {
            showPopupMessage(data.message || '❌ Subscription failed. Please try again.', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showPopupMessage('❌ Something went wrong. Please try again.', 'error');
    })
    .finally(() => {
        submitBtn.textContent = originalBtnText;
        submitBtn.disabled = false;
    });
}

function showPopupMessage(message, type) {
    const messageDiv = document.getElementById('popupMessage');
    messageDiv.textContent = message;
    messageDiv.className = `popup-message ${type}`;
    messageDiv.style.display = 'block';
    
    setTimeout(() => {
        messageDiv.style.display = 'none';
    }, 5000);
}

// Show popup after delay - EVERY TIME PAGE LOADS
document.addEventListener('DOMContentLoaded', function() {
    // No sessionStorage check - shows on every page load
    setTimeout(() => {
        showSocialPopup();
    }, 3000); // Shows after 3 seconds (you can change this)
    
    // Also show on scroll to 50% of page (optional)
    let hasTriggered = false;
    window.addEventListener('scroll', function() {
        if (!hasTriggered) {
            const scrollPercent = (window.scrollY + window.innerHeight) / document.documentElement.scrollHeight;
            
            if (scrollPercent > 0.5) {
                hasTriggered = true;
                setTimeout(() => {
                    if (document.getElementById('socialPopup').style.display !== 'flex') {
                        showSocialPopup();
                    }
                }, 500);
            }
        }
    });
});

// Close on escape key
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeSocialPopup();
    }
});

// Also close when clicking the overlay
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('popup-overlay')) {
        closeSocialPopup();
    }
});
</script>

<!-- Make sure to include Font Awesome in your layout -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">