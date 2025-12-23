<script>
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    const menuIcon = mobileMenuButton.querySelector('i');

    // Toggle mobile menu
    mobileMenuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
        menuIcon.classList.toggle('fa-bars');
        menuIcon.classList.toggle('fa-times');
    });

    // Close mobile menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!mobileMenu.contains(e.target) && !mobileMenuButton.contains(e.target)) {
            mobileMenu.classList.add('hidden');
            menuIcon.classList.add('fa-bars');
            menuIcon.classList.remove('fa-times');
        }
    });

    // Close mobile menu when clicking a link
    mobileMenu.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            mobileMenu.classList.add('hidden');
            menuIcon.classList.add('fa-bars');
            menuIcon.classList.remove('fa-times');
        });
    });

    // Close mobile menu on window resize
    window.addEventListener('resize', () => {
        if (window.innerWidth >= 768) {
            mobileMenu.classList.add('hidden');
            menuIcon.classList.add('fa-bars');
            menuIcon.classList.remove('fa-times');
        }
    });

    // Handle image loading errors
    document.querySelectorAll('img').forEach(img => {
        img.addEventListener('error', function() {
            // Hide the broken image
            this.style.display = 'none';
            // Show the fallback text
            this.nextElementSibling.classList.remove('hidden');
        });
    });
</script>


<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/prism.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-javascript.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-bash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/components/prism-html.min.js"></script>
<script>
    function copyCode(button) {
        const pre = button.nextElementSibling;
        const code = pre.querySelector('code');
        const text = code.innerText;

        navigator.clipboard.writeText(text).then(() => {
            // Change button text temporarily
            const originalText = button.innerHTML;
            button.innerHTML = '<i class="fas fa-check"></i> Copied!';
            button.style.backgroundColor = 'rgba(34, 197, 94, 0.1)';
            button.style.color = '#22c55e';

            // Reset button after 2 seconds
            setTimeout(() => {
                button.innerHTML = originalText;
                button.style.backgroundColor = 'rgba(99, 102, 241, 0.1)';
                button.style.color = '#6366f1';
            }, 2000);
        }).catch(err => {
            console.error('Failed to copy text: ', err);
            // Show error state
            button.innerHTML = '<i class="fas fa-times"></i> Failed';
            button.style.backgroundColor = 'rgba(239, 68, 68, 0.1)';
            button.style.color = '#ef4444';

            setTimeout(() => {
                button.innerHTML = '<i class="fas fa-copy"></i> Copy';
                button.style.backgroundColor = 'rgba(99, 102, 241, 0.1)';
                button.style.color = '#6366f1';
            }, 2000);
        });
    }

    // Add copy buttons to all code blocks automatically
    document.addEventListener('DOMContentLoaded', () => {
        const codeBlocks = document.querySelectorAll('pre');
        codeBlocks.forEach(pre => {
            // Only add if not already wrapped
            if (!pre.parentElement.classList.contains('code-wrapper')) {
                const wrapper = document.createElement('div');
                wrapper.className = 'code-wrapper';

                const button = document.createElement('button');
                button.className = 'copy-button';
                button.innerHTML = '<i class="fas fa-copy"></i> Copy';
                button.onclick = function() { copyCode(this); };

                // Insert the wrapper
                pre.parentNode.insertBefore(wrapper, pre);
                wrapper.appendChild(button);
                wrapper.appendChild(pre);
            }
        });
    });

    // Active link highlighting
    const sections = document.querySelectorAll('section');
    const navLinks = document.querySelectorAll('.nav-link');

    window.addEventListener('scroll', () => {
        let current = '';

        sections.forEach(section => {
            const sectionTop = section.offsetTop;
            const sectionHeight = section.clientHeight;
            if (pageYOffset >= sectionTop - 180) {
                current = section.getAttribute('id');
            }
        });

        navLinks.forEach(link => {
            link.classList.remove('active');
            if (link.getAttribute('href').slice(1) === current) {
                link.classList.add('active');
            }
        });
    });
</script>
