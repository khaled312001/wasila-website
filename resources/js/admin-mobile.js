// Admin Dashboard Mobile Enhancements

document.addEventListener('DOMContentLoaded', function() {
    // Mobile sidebar functionality
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    const mainContent = document.getElementById('main-content');
    
    // Touch gestures for mobile sidebar
    let startX = 0;
    let currentX = 0;
    let isDragging = false;
    
    // Handle touch start
    document.addEventListener('touchstart', function(e) {
        if (window.innerWidth <= 768) {
            startX = e.touches[0].clientX;
            isDragging = true;
        }
    });
    
    // Handle touch move
    document.addEventListener('touchmove', function(e) {
        if (isDragging && window.innerWidth <= 768) {
            currentX = e.touches[0].clientX;
            const diffX = startX - currentX;
            
            // Swipe right to open sidebar
            if (diffX < -50 && !sidebar.classList.contains('mobile-open')) {
                openMobileSidebar();
            }
            // Swipe left to close sidebar
            else if (diffX > 50 && sidebar.classList.contains('mobile-open')) {
                closeMobileSidebar();
            }
        }
    });
    
    // Handle touch end
    document.addEventListener('touchend', function() {
        isDragging = false;
    });
    
    // Mobile sidebar functions
    window.openMobileSidebar = function() {
        sidebar.classList.add('mobile-open');
        overlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    };
    
    window.closeMobileSidebar = function() {
        sidebar.classList.remove('mobile-open');
        overlay.classList.remove('active');
        document.body.style.overflow = '';
    };
    
    window.toggleMobileSidebar = function() {
        if (sidebar.classList.contains('mobile-open')) {
            closeMobileSidebar();
        } else {
            openMobileSidebar();
        }
    };
    
    // Close sidebar when clicking on navigation items
    const sidebarItems = document.querySelectorAll('.sidebar-item');
    sidebarItems.forEach(item => {
        item.addEventListener('click', function() {
            if (window.innerWidth <= 768) {
                setTimeout(() => {
                    closeMobileSidebar();
                }, 150);
            }
        });
    });
    
    // Handle window resize
    window.addEventListener('resize', function() {
        if (window.innerWidth > 768) {
            closeMobileSidebar();
        }
    });
    
    // Mobile card animations
    const observerOptions = {
        threshold: 0.1,
        rootMargin: '0px 0px -50px 0px'
    };
    
    const observer = new IntersectionObserver(function(entries) {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.style.animationPlayState = 'running';
            }
        });
    }, observerOptions);
    
    // Observe mobile cards
    const mobileCards = document.querySelectorAll('.mobile-card');
    mobileCards.forEach(card => {
        card.style.animationPlayState = 'paused';
        observer.observe(card);
    });
    
    // Mobile table enhancements
    const mobileTables = document.querySelectorAll('.mobile-table');
    mobileTables.forEach(table => {
        // Add horizontal scroll indicator
        const scrollIndicator = document.createElement('div');
        scrollIndicator.className = 'scroll-indicator';
        scrollIndicator.innerHTML = '← اسحب للعرض →';
        scrollIndicator.style.cssText = `
            position: absolute;
            top: 10px;
            right: 10px;
            background: rgba(8, 120, 139, 0.1);
            color: #08788B;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            z-index: 10;
            opacity: 0;
            transition: opacity 0.3s ease;
            pointer-events: none;
        `;
        
        table.parentElement.style.position = 'relative';
        table.parentElement.appendChild(scrollIndicator);
        
        // Show/hide scroll indicator
        table.addEventListener('scroll', function() {
            if (this.scrollLeft > 0) {
                scrollIndicator.style.opacity = '1';
                this.classList.add('scrollable');
            } else {
                scrollIndicator.style.opacity = '0';
                this.classList.remove('scrollable');
            }
        });
        
        // Add touch scroll momentum
        let isScrolling = false;
        let scrollTimeout;
        
        table.addEventListener('touchstart', function() {
            isScrolling = true;
        });
        
        table.addEventListener('touchmove', function() {
            if (isScrolling) {
                clearTimeout(scrollTimeout);
                scrollTimeout = setTimeout(() => {
                    isScrolling = false;
                }, 150);
            }
        });
        
        // Add swipe gestures for table navigation
        let startX = 0;
        let startY = 0;
        
        table.addEventListener('touchstart', function(e) {
            startX = e.touches[0].clientX;
            startY = e.touches[0].clientY;
        });
        
        table.addEventListener('touchend', function(e) {
            if (!isScrolling) return;
            
            const endX = e.changedTouches[0].clientX;
            const endY = e.changedTouches[0].clientY;
            const diffX = startX - endX;
            const diffY = startY - endY;
            
            // Only handle horizontal swipes
            if (Math.abs(diffX) > Math.abs(diffY) && Math.abs(diffX) > 50) {
                if (diffX > 0) {
                    // Swipe left - scroll right
                    this.scrollBy({ left: 100, behavior: 'smooth' });
                } else {
                    // Swipe right - scroll left
                    this.scrollBy({ left: -100, behavior: 'smooth' });
                }
            }
        });
        
        // Add keyboard navigation
        table.addEventListener('keydown', function(e) {
            if (e.key === 'ArrowLeft') {
                this.scrollBy({ left: -50, behavior: 'smooth' });
            } else if (e.key === 'ArrowRight') {
                this.scrollBy({ left: 50, behavior: 'smooth' });
            }
        });
        
        // Make table focusable for keyboard navigation
        table.setAttribute('tabindex', '0');
        
        // Add accessibility attributes
        table.setAttribute('role', 'table');
        table.setAttribute('aria-label', 'جدول البيانات');
    });
    
    // Mobile form enhancements
    const mobileForms = document.querySelectorAll('.mobile-form');
    mobileForms.forEach(form => {
        const inputs = form.querySelectorAll('input, textarea, select');
        
        inputs.forEach(input => {
            // Add floating label effect
            input.addEventListener('focus', function() {
                this.parentElement.classList.add('focused');
            });
            
            input.addEventListener('blur', function() {
                if (!this.value) {
                    this.parentElement.classList.remove('focused');
                }
            });
            
            // Prevent zoom on iOS
            if (input.type === 'text' || input.type === 'email' || input.type === 'tel') {
                input.style.fontSize = '16px';
            }
        });
    });
    
    // Mobile button enhancements
    const mobileButtons = document.querySelectorAll('.mobile-btn');
    mobileButtons.forEach(button => {
        button.addEventListener('touchstart', function() {
            this.style.transform = 'scale(0.98)';
        });
        
        button.addEventListener('touchend', function() {
            this.style.transform = 'scale(1)';
        });
    });
    
    // Mobile chart responsiveness
    const charts = document.querySelectorAll('canvas');
    charts.forEach(chart => {
        const resizeObserver = new ResizeObserver(function(entries) {
            entries.forEach(entry => {
                if (entry.target.chart) {
                    entry.target.chart.resize();
                }
            });
        });
        
        resizeObserver.observe(chart);
    });
    
    // Mobile performance optimizations
    if (window.innerWidth <= 768) {
        // Reduce animations on low-end devices
        if (navigator.hardwareConcurrency && navigator.hardwareConcurrency < 4) {
            document.documentElement.style.setProperty('--animation-duration', '0.2s');
        }
        
        // Optimize images
        const images = document.querySelectorAll('img');
        images.forEach(img => {
            if (img.dataset.src) {
                img.src = img.dataset.src;
            }
        });
    }
    
    // Mobile keyboard handling
    const viewport = document.querySelector('meta[name="viewport"]');
    if (viewport) {
        let originalContent = viewport.content;
        
        document.addEventListener('focusin', function() {
            if (window.innerWidth <= 768) {
                viewport.content = 'width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no';
            }
        });
        
        document.addEventListener('focusout', function() {
            if (window.innerWidth <= 768) {
                viewport.content = originalContent;
            }
        });
    }
    
    // Mobile notification enhancements
    const notifications = document.querySelectorAll('.bg-green-100, .bg-red-100');
    notifications.forEach(notification => {
        // Add swipe to dismiss
        let startX = 0;
        let currentX = 0;
        
        notification.addEventListener('touchstart', function(e) {
            startX = e.touches[0].clientX;
        });
        
        notification.addEventListener('touchmove', function(e) {
            currentX = e.touches[0].clientX;
            const diffX = startX - currentX;
            
            if (Math.abs(diffX) > 50) {
                this.style.transform = `translateX(${-diffX}px)`;
                this.style.opacity = Math.max(0, 1 - Math.abs(diffX) / 200);
            }
        });
        
        notification.addEventListener('touchend', function() {
            const diffX = startX - currentX;
            
            if (Math.abs(diffX) > 100) {
                this.style.transition = 'all 0.3s ease';
                this.style.transform = 'translateX(-100%)';
                this.style.opacity = '0';
                
                setTimeout(() => {
                    this.remove();
                }, 300);
            } else {
                this.style.transition = 'all 0.3s ease';
                this.style.transform = 'translateX(0)';
                this.style.opacity = '1';
            }
        });
    });
    
    // Mobile accessibility enhancements
    if (window.innerWidth <= 768) {
        // Increase touch targets
        const touchTargets = document.querySelectorAll('button, a, input[type="checkbox"], input[type="radio"]');
        touchTargets.forEach(target => {
            const rect = target.getBoundingClientRect();
            if (rect.width < 44 || rect.height < 44) {
                target.style.minWidth = '44px';
                target.style.minHeight = '44px';
            }
        });
        
        // Add focus indicators
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Tab') {
                document.body.classList.add('keyboard-navigation');
            }
        });
        
        document.addEventListener('mousedown', function() {
            document.body.classList.remove('keyboard-navigation');
        });
    }
});

// Mobile utility functions
window.mobileUtils = {
    // Check if device is mobile
    isMobile: function() {
        return window.innerWidth <= 768;
    },
    
    // Check if device is touch
    isTouch: function() {
        return 'ontouchstart' in window || navigator.maxTouchPoints > 0;
    },
    
    // Vibrate device (if supported)
    vibrate: function(duration = 50) {
        if (navigator.vibrate) {
            navigator.vibrate(duration);
        }
    },
    
    // Show mobile toast
    showToast: function(message, type = 'info') {
        const toast = document.createElement('div');
        toast.className = `mobile-toast mobile-toast-${type}`;
        toast.textContent = message;
        toast.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            left: 20px;
            background: ${type === 'error' ? '#ef4444' : type === 'success' ? '#10b981' : '#3b82f6'};
            color: white;
            padding: 12px 16px;
            border-radius: 8px;
            z-index: 9999;
            transform: translateY(-100px);
            transition: transform 0.3s ease;
            text-align: center;
            font-weight: 500;
        `;
        
        document.body.appendChild(toast);
        
        setTimeout(() => {
            toast.style.transform = 'translateY(0)';
        }, 100);
        
        setTimeout(() => {
            toast.style.transform = 'translateY(-100px)';
            setTimeout(() => {
                toast.remove();
            }, 300);
        }, 3000);
    },
    
    // Table utilities
    table: {
        // Scroll table to start
        scrollToStart: function(table) {
            if (table) {
                table.scrollTo({ left: 0, behavior: 'smooth' });
            }
        },
        
        // Scroll table to end
        scrollToEnd: function(table) {
            if (table) {
                table.scrollTo({ left: table.scrollWidth, behavior: 'smooth' });
            }
        },
        
        // Toggle table column visibility
        toggleColumn: function(table, columnIndex, show) {
            const rows = table.querySelectorAll('tr');
            rows.forEach(row => {
                const cell = row.children[columnIndex];
                if (cell) {
                    cell.style.display = show ? '' : 'none';
                }
            });
        },
        
        // Get table scroll position
        getScrollPosition: function(table) {
            return {
                left: table.scrollLeft,
                right: table.scrollWidth - table.scrollLeft - table.clientWidth
            };
        },
        
        // Check if table is scrollable
        isScrollable: function(table) {
            return table.scrollWidth > table.clientWidth;
        }
    }
};
