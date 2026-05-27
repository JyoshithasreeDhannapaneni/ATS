/**
 * Neutara ATS — 3D Animation Engine
 * Product-level interactive animations for the internal application
 */
(function() {
    'use strict';

    var ATS3D = {
        cursorGlow: null,
        mouseX: 0,
        mouseY: 0,
        rafId: null,

        init: function() {
            if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                return;
            }
            this.createCursorGlow();
            this.initRippleEffect();
            this.initCardTilt();
            this.initScrollReveal();
            this.initNavHoverEffects();
            this.initTableRowEffects();
            this.initButtonPress();
            this.initSmoothCounters();
        },

        createCursorGlow: function() {
            var glow = document.createElement('div');
            glow.id = 'cursor-glow';
            glow.style.opacity = '0';
            document.body.appendChild(glow);
            this.cursorGlow = glow;

            var self = this;
            document.addEventListener('mousemove', function(e) {
                self.mouseX = e.clientX;
                self.mouseY = e.clientY;
                if (!self.rafId) {
                    self.rafId = requestAnimationFrame(function() {
                        if (self.cursorGlow) {
                            self.cursorGlow.style.left = self.mouseX + 'px';
                            self.cursorGlow.style.top = self.mouseY + 'px';
                            self.cursorGlow.style.opacity = '1';
                        }
                        self.rafId = null;
                    });
                }
            });

            document.addEventListener('mouseleave', function() {
                if (self.cursorGlow) self.cursorGlow.style.opacity = '0';
            });
        },

        initRippleEffect: function() {
            var targets = document.querySelectorAll('.button, .buttonDown, .buttonCalendar, .buttonDownCalendar, .arrowButton, #header ul#primary a');
            for (var i = 0; i < targets.length; i++) {
                targets[i].addEventListener('click', function(e) {
                    var el = this;
                    var rect = el.getBoundingClientRect();
                    var ripple = document.createElement('span');
                    ripple.className = 'ripple';
                    var size = Math.max(rect.width, rect.height) * 2;
                    ripple.style.width = ripple.style.height = size + 'px';
                    ripple.style.left = (e.clientX - rect.left - size / 2) + 'px';
                    ripple.style.top = (e.clientY - rect.top - size / 2) + 'px';
                    el.style.position = 'relative';
                    el.style.overflow = 'hidden';
                    el.appendChild(ripple);
                    setTimeout(function() {
                        if (ripple.parentNode) ripple.parentNode.removeChild(ripple);
                    }, 700);
                });
            }
        },

        initCardTilt: function() {
            var cards = document.querySelectorAll('.dashboard-card, .stat-card, div.cpSection, div#formBlock');
            for (var i = 0; i < cards.length; i++) {
                (function(card) {
                    card.addEventListener('mousemove', function(e) {
                        var rect = card.getBoundingClientRect();
                        var x = e.clientX - rect.left;
                        var y = e.clientY - rect.top;
                        var centerX = rect.width / 2;
                        var centerY = rect.height / 2;
                        var rotateX = ((y - centerY) / centerY) * -3;
                        var rotateY = ((x - centerX) / centerX) * 3;
                        card.style.transform = 'perspective(800px) rotateX(' + rotateX + 'deg) rotateY(' + rotateY + 'deg) translateY(-4px)';
                    });

                    card.addEventListener('mouseleave', function() {
                        card.style.transform = '';
                        card.style.transition = 'transform 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
                        setTimeout(function() {
                            card.style.transition = '';
                        }, 400);
                    });
                })(cards[i]);
            }
        },

        initScrollReveal: function() {
            if (!('IntersectionObserver' in window)) return;

            var animateElements = document.querySelectorAll(
                'table.sortable, table.sortablepair, table.notsortable, ' +
                '.dashboard-card, .stat-card, div.cpSection, table.detailsOutside, ' +
                '.editTable, table.searchTable, table.statisticsTable, p.note, p.noteUnsized'
            );

            for (var i = 0; i < animateElements.length; i++) {
                animateElements[i].style.opacity = '0';
            }

            var observer = new IntersectionObserver(function(entries) {
                for (var j = 0; j < entries.length; j++) {
                    if (entries[j].isIntersecting) {
                        entries[j].target.style.opacity = '';
                        entries[j].target.style.animationPlayState = 'running';
                        observer.unobserve(entries[j].target);
                    }
                }
            }, { threshold: 0.05, rootMargin: '0px 0px -30px 0px' });

            for (var k = 0; k < animateElements.length; k++) {
                animateElements[k].style.animationPlayState = 'paused';
                observer.observe(animateElements[k]);
            }
        },

        initNavHoverEffects: function() {
            var navLinks = document.querySelectorAll('#header ul#primary a');
            for (var i = 0; i < navLinks.length; i++) {
                (function(link) {
                    link.addEventListener('mouseenter', function() {
                        var icon = link.querySelector('.nav-icon');
                        if (icon) {
                            icon.style.transform = 'scale(1.2) translateX(2px)';
                            icon.style.transition = 'all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1)';
                        }
                    });
                    link.addEventListener('mouseleave', function() {
                        var icon = link.querySelector('.nav-icon');
                        if (icon) {
                            icon.style.transform = '';
                        }
                    });
                })(navLinks[i]);
            }
        },

        initTableRowEffects: function() {
            var rows = document.querySelectorAll('table.sortable tbody tr, table.sortablepair tbody tr');
            for (var i = 0; i < rows.length; i++) {
                (function(row) {
                    row.addEventListener('click', function() {
                        row.classList.add('row-clicked');
                        setTimeout(function() {
                            row.classList.remove('row-clicked');
                        }, 500);
                    });
                })(rows[i]);
            }
        },

        initButtonPress: function() {
            var buttons = document.querySelectorAll('.button, .buttonDown, input[type="submit"], input[type="button"]');
            for (var i = 0; i < buttons.length; i++) {
                (function(btn) {
                    btn.addEventListener('mousedown', function() {
                        btn.style.transform = 'translateY(1px) scale(0.97)';
                        btn.style.transition = 'transform 0.1s ease';
                    });
                    btn.addEventListener('mouseup', function() {
                        btn.style.transform = '';
                        btn.style.transition = 'transform 0.2s cubic-bezier(0.34, 1.56, 0.64, 1)';
                    });
                    btn.addEventListener('mouseleave', function() {
                        btn.style.transform = '';
                    });
                })(buttons[i]);
            }
        },

        initSmoothCounters: function() {
            var statNumbers = document.querySelectorAll('.stat-number');
            if (!statNumbers.length) return;

            for (var i = 0; i < statNumbers.length; i++) {
                (function(el) {
                    var text = el.textContent.trim();
                    var num = parseInt(text.replace(/[^0-9]/g, ''), 10);
                    if (isNaN(num) || num === 0) return;

                    var prefix = text.match(/^[^0-9]*/)[0] || '';
                    var suffix = text.match(/[^0-9]*$/)[0] || '';
                    var duration = 800;
                    var start = null;

                    el.textContent = prefix + '0' + suffix;

                    var animate = function(timestamp) {
                        if (!start) start = timestamp;
                        var progress = Math.min((timestamp - start) / duration, 1);
                        var eased = 1 - Math.pow(1 - progress, 3);
                        el.textContent = prefix + Math.floor(eased * num) + suffix;
                        if (progress < 1) requestAnimationFrame(animate);
                    };

                    if ('IntersectionObserver' in window) {
                        var obs = new IntersectionObserver(function(entries) {
                            if (entries[0].isIntersecting) {
                                requestAnimationFrame(animate);
                                obs.unobserve(el);
                            }
                        }, { threshold: 0.5 });
                        obs.observe(el);
                    } else {
                        requestAnimationFrame(animate);
                    }
                })(statNumbers[i]);
            }
        }
    };

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() { ATS3D.init(); });
    } else {
        ATS3D.init();
    }
})();
