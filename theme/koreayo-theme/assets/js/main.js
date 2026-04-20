/**
 * Koreayo Theme - Main JavaScript
 *
 * @package Koreayo
 * @version 1.0.0
 */

(function() {
  'use strict';

  // DOM Ready
  document.addEventListener('DOMContentLoaded', function() {
    initMobileMenu();
    initSearchModal();
    initStickyHeader();
    initSmoothScroll();
    initNewsletterForm();
  });

  /**
   * Mobile Menu Toggle
   */
  function initMobileMenu() {
    const menuToggle = document.querySelector('.mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');

    if (!menuToggle || !mobileMenu) return;

    menuToggle.addEventListener('click', function() {
      const isExpanded = menuToggle.getAttribute('aria-expanded') === 'true';

      menuToggle.setAttribute('aria-expanded', !isExpanded);
      mobileMenu.setAttribute('aria-hidden', isExpanded);

      // Toggle body scroll
      document.body.style.overflow = isExpanded ? '' : 'hidden';

      // Animate hamburger
      menuToggle.classList.toggle('active');
    });

    // Close menu on escape key
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && menuToggle.getAttribute('aria-expanded') === 'true') {
        menuToggle.click();
      }
    });

    // Close menu when clicking outside
    mobileMenu.addEventListener('click', function(e) {
      if (e.target === mobileMenu) {
        menuToggle.click();
      }
    });
  }

  /**
   * Search Modal
   */
  function initSearchModal() {
    const searchToggle = document.querySelector('.search-toggle');
    const searchModal = document.getElementById('search-modal');
    const searchClose = document.querySelector('.search-close');
    const searchInput = document.querySelector('.search-input');

    if (!searchToggle || !searchModal) return;

    searchToggle.addEventListener('click', function() {
      searchModal.setAttribute('aria-hidden', 'false');
      searchModal.classList.add('active');
      document.body.style.overflow = 'hidden';

      // Focus search input
      setTimeout(function() {
        if (searchInput) searchInput.focus();
      }, 100);
    });

    function closeSearch() {
      searchModal.setAttribute('aria-hidden', 'true');
      searchModal.classList.remove('active');
      document.body.style.overflow = '';
    }

    if (searchClose) {
      searchClose.addEventListener('click', closeSearch);
    }

    // Close on escape
    document.addEventListener('keydown', function(e) {
      if (e.key === 'Escape' && searchModal.classList.contains('active')) {
        closeSearch();
      }
    });

    // Close when clicking overlay
    searchModal.addEventListener('click', function(e) {
      if (e.target === searchModal) {
        closeSearch();
      }
    });
  }

  /**
   * Sticky Header with Hide on Scroll Down
   */
  function initStickyHeader() {
    const header = document.querySelector('.site-header');
    if (!header) return;

    let lastScrollY = window.scrollY;
    let ticking = false;

    function updateHeader() {
      const currentScrollY = window.scrollY;

      if (currentScrollY > 100) {
        header.classList.add('scrolled');

        if (currentScrollY > lastScrollY && currentScrollY > 300) {
          // Scrolling down
          header.classList.add('hidden');
        } else {
          // Scrolling up
          header.classList.remove('hidden');
        }
      } else {
        header.classList.remove('scrolled', 'hidden');
      }

      lastScrollY = currentScrollY;
      ticking = false;
    }

    window.addEventListener('scroll', function() {
      if (!ticking) {
        window.requestAnimationFrame(updateHeader);
        ticking = true;
      }
    });
  }

  /**
   * Smooth Scroll for Anchor Links
   */
  function initSmoothScroll() {
    document.querySelectorAll('a[href^="#"]').forEach(function(anchor) {
      anchor.addEventListener('click', function(e) {
        const targetId = this.getAttribute('href');

        if (targetId === '#') return;

        const targetElement = document.querySelector(targetId);

        if (targetElement) {
          e.preventDefault();

          const headerHeight = document.querySelector('.site-header')?.offsetHeight || 0;
          const targetPosition = targetElement.getBoundingClientRect().top + window.scrollY - headerHeight - 20;

          window.scrollTo({
            top: targetPosition,
            behavior: 'smooth'
          });

          // Update URL
          history.pushState(null, null, targetId);
        }
      });
    });
  }

  /**
   * Newsletter Form Handler
   */
  function initNewsletterForm() {
    const forms = document.querySelectorAll('.newsletter-form');

    forms.forEach(function(form) {
      form.addEventListener('submit', function(e) {
        e.preventDefault();

        const emailInput = form.querySelector('input[type="email"]');
        const submitBtn = form.querySelector('button[type="submit"]');

        if (!emailInput || !submitBtn) return;

        const email = emailInput.value.trim();
        const originalBtnText = submitBtn.textContent;

        // Basic email validation
        if (!isValidEmail(email)) {
          showFormMessage(form, 'Please enter a valid email address.', 'error');
          return;
        }

        // Disable form
        emailInput.disabled = true;
        submitBtn.disabled = true;
        submitBtn.textContent = 'Subscribing...';

        // Simulate API call (replace with actual endpoint)
        setTimeout(function() {
          // Success
          showFormMessage(form, 'Thank you for subscribing!', 'success');
          emailInput.value = '';

          // Re-enable form
          emailInput.disabled = false;
          submitBtn.disabled = false;
          submitBtn.textContent = originalBtnText;
        }, 1500);
      });
    });
  }

  /**
   * Helper: Validate Email
   */
  function isValidEmail(email) {
    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    return re.test(email);
  }

  /**
   * Helper: Show Form Message
   */
  function showFormMessage(form, message, type) {
    // Remove existing message
    const existingMsg = form.querySelector('.form-message');
    if (existingMsg) existingMsg.remove();

    // Create message element
    const msgEl = document.createElement('div');
    msgEl.className = 'form-message form-message-' + type;
    msgEl.textContent = message;
    msgEl.style.cssText = 'margin-top: 0.75rem; padding: 0.5rem; border-radius: 4px; font-size: 0.875rem;';

    if (type === 'error') {
      msgEl.style.background = '#fef2f2';
      msgEl.style.color = '#dc2626';
    } else {
      msgEl.style.background = '#f0fdf4';
      msgEl.style.color = '#16a34a';
    }

    form.appendChild(msgEl);

    // Remove after 5 seconds
    setTimeout(function() {
      msgEl.remove();
    }, 5000);
  }

  /**
   * Lazy Load Images
   */
  if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver(function(entries, observer) {
      entries.forEach(function(entry) {
        if (entry.isIntersecting) {
          const img = entry.target;

          if (img.dataset.src) {
            img.src = img.dataset.src;
            img.removeAttribute('data-src');
          }

          if (img.dataset.srcset) {
            img.srcset = img.dataset.srcset;
            img.removeAttribute('data-srcset');
          }

          img.classList.add('loaded');
          observer.unobserve(img);
        }
      });
    }, {
      rootMargin: '50px 0px',
      threshold: 0.1
    });

    document.querySelectorAll('img[data-src]').forEach(function(img) {
      imageObserver.observe(img);
    });
  }

})();
